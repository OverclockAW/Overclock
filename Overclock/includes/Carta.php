<?php

namespace xampp\htdocs\Overclock;
use xampp\htdocs\Overclock\Aplicacion as App;


class Carta implements \JsonSerializable{

	//id generico de la carta para obtener los datos
	//private $idCarta;
	//nombre de la carta  id generico de la carta para obtener los datos
	private $nombre;
	//desc de la carta
	private $descripcion;
	//imagen de la carta
	private $imagen;
	//vida actual de la carta
	private $hp;
	//vida maxima de la carta
	private $hpMax;
	//energia de la carta
	private $energia;
	//energia maxima de la carta
	private $energiaMax;	
	//tipo de la carta
	private $tipo;
	//habilidades que posee una carta
	private $habilidades;

	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
	
	public function __construct($nombre, $descripcion, $imagen, $hp, $energia, $tipo){
		$this->nombre = $nombre;
		$this->descripcion = $descripcion;
		$this->imagen = $imagen;
		$this->hp = $hp;
		$this->hpMax = $hp;
		$this->energia = $energia;
		$this->energiaMax = $energia;
		$this->tipo = $tipo;
		$this->habilidades = self::buscaHabilidades($nombre);
	}
	
	// Busca las habilidades de la carta en la base de datos y las mete en el array de habilidades
	private function buscaHabilidades($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $conn->real_escape_string($nombre);
		//$select = "SELECT * FROM habilidades";
		$select = "SELECT h.nombre, h.tipo, h.valor, h.coste, h.area FROM carta_habilidad AS ch JOIN habilidades AS h WHERE ch.carta_id = '$nombre' AND ch.habilidad_id = h.nombre";
		$rs = $conn->query($select);
		$habilidades = array();
			while($rs && $fila = $rs->fetch_assoc()){
				$hab = new Habilidad($fila['nombre'], $fila['tipo'], $fila['valor'], $fila['coste'], $fila['area']);
				array_push($habilidades,$hab);
			}
		
		return $habilidades;
	}

	// Filtra las cartas de gestionCartas dada una opción del desplegable y un texto de búsqueda
	public static function filtrarCartas($filtro, $busqueda){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$busqueda = $conn->real_escape_string($busqueda);
		$filtro = $conn->real_escape_string($filtro);
		$cartas = array();
		$query= '';
		
		if($busqueda != "")
			$query = "SELECT * FROM cartas WHERE $filtro LIKE '%$busqueda%'";
		
		else
			$query = "SELECT * FROM cartas";
		$rs = $conn->query($query);
		if($rs){
			while($fila = $rs->fetch_assoc()){
				$carta = new Carta($fila['nombre'], $fila['descripcion'], $fila['img'], $fila['hp'], $fila['energia'], $fila['tipo']);
				array_push($cartas,$carta);
			}
		}

		return $cartas;
	}
	
	// Devuelve una carta dada su id (nombre), cogiendo todos sus valores de la base de datos "cartas"
	public static function cargarCarta($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $conn->real_escape_string($nombre);
		$query = "SELECT * FROM cartas WHERE nombre = '$nombre'";
		$rs2 = $conn->query($query);
		$fila = $rs2->fetch_assoc();
		$carta = new Carta($fila['nombre'],$fila['descripcion'],$fila['img'],$fila['hp'],$fila['energia'],$fila['tipo']);
		$rs2->free();
		return $carta;
	}
	
	// Busca cuál es el límite de una carta en el mazo, para poder luego cargar el número de cartas iguales que se pueden tener en el mazo
	public function limiteMazo(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $this->nombre;
		$query = "SELECT limite_mazo FROM cartas WHERE nombre = '$nombre'";
		$rs2 = $conn->query($query);
		$fila = $rs2->fetch_assoc();
		$limite = $fila['limite_mazo'];
		$rs2->free();
		return $limite;
	}
	
	// Borra una carta de la base de datos
	public static function borrarCarta($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $conn->real_escape_string($nombre);
		$query = "DELETE FROM cartas WHERE nombre = '$nombre'";
		$rs = $conn->query($query);
		return true;
	}
	
	// Modifica una carta de la base de datos dados unos nuevos parámetros. Se usa en gestionCartas
	public static function modificarCarta($nombre, $hp, $energia, $tipo){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		//Escapamos caracteres
		$nombre = $conn->real_escape_string($nombre);
		$hp = $conn->real_escape_string($hp);
		$energia = $conn->real_escape_string($energia);
		$tipo = $conn->real_escape_string($tipo);
		//Realizamos el update a cada campo
		$query = "UPDATE cartas SET hp = '$hp' WHERE nombre = '$nombre'";
		$rs = $conn->query($query);
		//Realizamos el update a cada campo
		$query = "UPDATE cartas SET energia = '$energia' WHERE nombre = '$nombre'";
		$rs = $conn->query($query);
		//Realizamos el update a cada campo
		$query = "UPDATE cartas SET tipo = '$tipo' WHERE nombre = '$nombre'";
		$rs = $conn->query($query);
		return true;
	}
	
	//////////////FUNCIONES GET////////////////

	public function getNombre(){
		return $this->nombre;
	}

	public function getDescripcion(){
		return $this->descripcion;
	}

	public function getImagen(){
		return $this->imagen;
	}
	
	// Devuelve la vida de una carta si tiene. Los eventos y objetos tienen 0 de vida en la base de datos, pero para el usuario no tienen vida
	public function getHp(){
		if($this->tipo == "Boss" || $this->tipo == "Personaje"){
			return $this->hp;
		}
			
		else return "";
	}

	public function getHpMax(){
		return $this->hpMax;
	}

	public function getEnergia(){
		return $this->energia;
	}

	public function getEnergiaMax(){
		return $this->energiaMax;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function getHabilidades(){
		return $this->habilidades;
	}


	// Devuelve el rol de una carta dependiendo de su tipo
	public function rolCarta(){

		if($this->tipo == "Personaje" || $this->tipo == "Objeto"){
			return "Aventurero";
		}
		else{
			return "Master";
		}

	}
	
	// Devuelve la imagen del marco de la carta dependiendo del tipo de la misma. Se usa para las vistas
	public function getMarco(){
		$app = App::getSingleton();
		if($this->tipo == "Personaje"){
			return $app->resuelve('/img/carta.png');
		}
		else if($this->tipo == "Boss"){
			return $app->resuelve('/img/cartaJefe.png');
		}
		else if($this->tipo == "Evento"){
			return $app->resuelve('/img/cartaEvento.png');
		}
		else if($this->tipo == "Objeto"){
			return $app->resuelve('/img/cartaItem.png');
		}
	}
	
	///////////////////////////////////////////
	
	// Hiere a la carta y devuelve el nombre de la misma y la vida resultante. Si el golpe es mortal (<= 0), devuelve 0 como vida resultante
	public function herir($dmg){
		$this->hp -= $dmg;
		if($this->hp < 0) {
			$this->hp = 0;
		}
		$result = new \stdClass();
		$result->nombre = $this->nombre;
		$result->hp = $this->hp;
		return $result;
	}

	// Cura a la carta si no es objeto o evento y devuelve su nombre y la vida resultante.
	public function sanar($cura){
		if($this->tipo == "Boss" || $this->tipo == "Personaje"){
			$this->hp += $cura;
			$result = new \stdClass();
			if($this->hp >= $this->hpMax){
				$this->hp = $this->hpMax;
			}
			$result->hp = $cura;
			$result->nombre = $this->nombre;
			return $result;
		}
		return false;
	}

	// Actualiza la vida de la carta. Se usa para cargar la vida de las cartas del campo una vez cargadas en el campo (ahí se usa el constructor, que las carga con la vida máxima)
	public function actualizarHp($cantidad){
		$this->hp = $cantidad;
	}

	// Gasta la energía de la carta, insertando en la tabla de campo_actual. Devuelve true si puede gastarla (la habilidad se puede ejecutar) o falso si no puede gastarla
	public function gastarEnergia($cantidad, $id){
		// Coger energia_actual de la BD
		$this->energia = (int)self::consultarEnergia($this->nombre, $id);
		if($cantidad <= $this->energia){
			$this->energia -= $cantidad;
			self::actualizarEnergia($this->nombre, $this->energia, $id);
			return true;
		}else{
			return false;
		}
	}
	
	// Cambia la energía de la carta
	public function setEnergia($e){
		$this->energia = $e;
	}

	// Actualiza la energía de la carta en la tabla campo_actual. Para que se mantenga durante el turno.
	private function actualizarEnergia($nombreCarta, $energia, $id){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $conn->real_escape_string($id);
		$nombreCarta = $conn->real_escape_string($nombreCarta);
		$energia = $conn->real_escape_string($energia);
		$update = "UPDATE campo_actual SET energia_restante = '$energia' WHERE id_partida = '$id' AND id_carta = '$nombreCarta'";
		$rs = $conn->query($update);
		//$rs->free();
	}
	
	// Devuelve la energia restante de una carta en el campo
	private function consultarEnergia($nombreCarta, $id){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $conn->real_escape_string($id);
		$nombreCarta = $conn->real_escape_string($nombreCarta);
		$select = "SELECT energia_restante FROM campo_actual WHERE id_partida = '$id' AND id_carta = '$nombreCarta'";
		$rs = $conn->query($select);
		$energia;
		if($rs && $rs->num_rows > 0){
			$fila = $rs->fetch_assoc();
			$energia = $fila['energia_restante'];
		}
		return $energia;
	}
	
	// Restaura la energía de la carta dado un valor. Actualiza la tabla campo_actual con el nuevo valor
	public function restaurarEnergia($valor, $id){
		$result = new \stdClass();
		$this->energia += $valor;
		if($this->energia > $this->energiaMax){
			$this->energia = $this->energiaMax;
		}
		self::actualizarEnergia($this->nombre, $this->energia, $id);
		$result->e = $valor;
		$result->nombre = $this->nombre;
		return $result;
	}
	
	// Rellena toda la energía de la carta. Se utiliza al cambiar de turno, para que en el comienzo de cada turno, las cartas tengan la máxima energía
	public function restablecerEnergia(){
		$this->energia = $this->energiaMax;
	}

	// Devuelve un objeto Carta dado un array
	public static function fromArrayToCarta($argumentos){
		$ca = $argumentos;
		return new Carta($ca["nombre"], $ca["descripcion"], $ca["imagen"], $ca["hp"], $ca["energia"], $ca["tipo"]);
	}

	// Devuelve un array de valores del objeto Carta
	public function fromCartaToArray(){
		$paramsCarta = array();
		array_push($paramsCarta, $this->nombre);
		array_push($paramsCarta, $this->descripcion);
		array_push($paramsCarta, $this->hp);
		array_push($paramsCarta, $this->energia);
		array_push($paramsCarta, $this->tipo);
		array_push($paramsCarta, $this->habilidades);
		return $paramsCarta;
	}
}

?>


