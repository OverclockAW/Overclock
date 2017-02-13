<?php 
	namespace xampp\htdocs\Overclock;

	use xampp\htdocs\Overclock\Aplicacion as App;

	class Campo implements \JsonSerializable{
		private $idPartida;
		private $cartas;
		private $rol;
		
		const MAX_CARTAS_CAMPO = 5;
		
	public function __construct($id, $cartas, $rol){
		$this->idPartida = $id;
		$this->rol = $rol;
		
		$this->cartas = array();
		$tamArray = sizeof($cartas);
		for($i = 0; $i < $tamArray; $i++){
			array_push($this->cartas, $cartas[$i]);
		}
	}
	
	public function getCartas(){
		return $this->cartas;
	}
	
	public function setCartas($cartasCampo){
		$this->cartas = $cartasCampo;
	}
	
	// Coloca una carta dada en el campo
	public function colocar($carta){
		$tamArray = sizeof($this->cartas);
		if($tamArray < self::MAX_CARTAS_CAMPO){
			array_push($this->cartas, $carta);
			return sizeof($this->cartas);
		}
		return false;
	}
	
	// Mira si se puede gastar la energía de una carta dada en el campo
	public function gastarEnergia($posMiCarta, $cantidad, $id){
		return $this->cartas[$posMiCarta]->gastarEnergia($cantidad, $id);
	}
	
	// Restaura la energía de una carta dada en el campo
	public function restaurarEnergia($target, $valor, $id){
		return $this->cartas[$target]->restaurarEnergia($valor, $id);
	}
	
	// Hiere a una carta dada del campo. Si es un ataque en área, hiere a todos sus enemigos
	public function herir($target, $valor, $area){
		if($area == "Si"){
			$total = 0;
			$resultHerir = new \stdClass();
			foreach($this->cartas as $c){
				$resultHerir = $c->herir($valor);
				$total += $resultHerir->hp;
			}
			$result = new \stdClass();
			$result->nombre = "todos sus enemigos";
			$result->hp = $total;
			return $result; 
		}
		return $this->cartas[$target]->herir($valor);
	}
	
	// Cura a una carta del campo. Si es una cura en área, cura a todos sus amigos
	public function curar($target, $valor, $area){
		if($area == "Si"){
			foreach($this->cartas as $c){
				$c->sanar($valor);
			}
			$result = new \stdClass();
			$result->nombre = "todos sus amigos";
			$result->hp = $valor;
			return $result;
		}
		return $this->cartas[$target]->sanar($valor);
	}
	
	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}

	// Devuelve un objeto Campo a partir de una array de valores
	public static function fromArrayToCampo($argumentos){
		$arrayCampo = $argumentos;
		$cartasCampo = array(); // Array $this->cartas de $campo
		$arrayCartas = array();
		$rol;
		if(sizeof($arrayCampo) == 3){
			$arrayCartas = $arrayCampo["cartas"];
			foreach($arrayCartas as $c){
				array_push($cartasCampo, new Carta($c["nombre"], $c["descripcion"], $c["imagen"], $c["hp"], $c["energia"], $c["tipo"]));
			}
			$rol = $arrayCampo["rol"];
		}
		else{
			$rol = $arrayCampo["rol"];
		}
		return new Campo($arrayCampo["idPartida"],$cartasCampo, $arrayCampo["rol"]);
	}
	
	//Carga las cartas del campo actual
	public static function cargarCartas($rol, $id){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$rol = $conn->real_escape_string($rol);
		$id = $conn->real_escape_string($id);
		$query = "SELECT id_carta,vida_restante, energia_restante FROM campo_actual WHERE rol = '$rol' AND id_partida = '$id'";
		$rs = $conn->query($query);
		$cartas = array();
		if($rs->num_rows > 0){
			while($fila = $rs->fetch_assoc()){
				$carta = Carta::cargarCarta($fila['id_carta']);
				$carta->actualizarHp($fila['vida_restante']);
				$carta->setEnergia($fila['energia_restante']);
				array_push($cartas,$carta);
			}
		}
		return $cartas;
	}
	
	// Borra una carta de la tabla campo_actual
	public function cartaDestruidaBD($id,$nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$borrarCarta = "DELETE FROM campo_actual WHERE id_partida = '$id' AND id_carta = '$nombre'";
		$borrar = $conn->query($borrarCarta);
	}
}


?>