<?php

namespace xampp\htdocs\Overclock;

use xampp\htdocs\Overclock\Aplicacion as App;

class Mazo implements \JsonSerializable{

	
	private $idPartida;
	private $cartas;
	private $rol;
	
	const MAX_CARTAS_MAZO = 30;

	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
	
	public function __construct($idPartida, $rol){
		$this->idPartida = $idPartida;
		$this->rol = $rol;
		$this->cartas = self::buscaMazo();
	}
	
	// Busca el mazo del jugador para asignarselo en la partida
	private function buscaMazo(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		
		$id = $this->idPartida;
		$rol = $this->rol;
		
		$buscarMazo = "SELECT id_carta FROM mazo_actual WHERE id_partida = '$id' AND rol = '$rol' ORDER BY RAND()"; 
		$busqueda = $conn->query($buscarMazo);
		$mazo = array();
		
		if($busqueda->num_rows > 0){
			$cargar = $conn->query($buscarMazo);
			while($fila = $cargar->fetch_assoc()){
				$carta = Carta::cargarCarta($fila['id_carta']);
				array_push($mazo, $carta);
			}
			$cargar->free();
			$busqueda->free();
			
		}
		
		else{
			if($rol == "Aventurero"){
				$cartas = "SELECT * FROM cartas WHERE tipo = 'Objeto' OR tipo = 'Personaje' ORDER BY RAND()";
			}else{
				$cartas = "SELECT * FROM cartas WHERE tipo = 'Evento' OR tipo = 'Boss' ORDER BY RAND()";
			}
			
			$busquedaCartas = $conn->query($cartas);
			if ($busquedaCartas) {
				while($fila = $busquedaCartas->fetch_assoc()){
					$n = $fila['nombre'];
					$carta = new Carta($n,$fila['descripcion'],$fila['img'],$fila['hp'],$fila['energia'],$fila['tipo']);
					$nCartas = $fila['limite_mazo'];
					$limite = 0;
					while($limite < $nCartas){
						$guardarMazo = "INSERT INTO mazo_actual (id_carta, id_partida, rol) VALUES ('$n', '$id', '$rol')";
						$m = $conn->query($guardarMazo);
						$limite += 1;
						array_push($mazo, $carta);
					}
				}

			  $busquedaCartas->free();
			}
		}
		return $mazo;
	}

	// Baraja las cartas del mazo
	public function barajar(){
		shuffle($this->cartas);
	}

	public function getIdPartida(){
		return $this->idPartida;
	}
	
	public function getCartas(){
		return $this->cartas;
	}
	
	public function setCartas($cartasMazo){
		$this->cartas = $cartasMazo;
	}
	
	public function getRol(){
		return $this->rol;
	}
	
	public function anadirAlFinal($carta){
		array_push($this->cartas, $carta);
	}
	
	// Mete una carta en el mazo del aventurero para que sea reciclada (lo exigen las reglas del juego)
	public function guardarCartaEliminada($idPartida,$nombreCarta, $miRol){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$rol;
		
		if($miRol == "Aventurero"){
			$rol = "Master";
		}
		else{
			$rol = "Aventurero";
		}
		
		$guardarMazo = "INSERT INTO mazo_actual (id_carta, id_partida, rol) VALUES ('$nombreCarta', '$idPartida', '$rol')";
		$guardar = $conn->query($guardarMazo);
	}
	
	// Borra una carta del mazo, cuando robas
	public function borrarCarta($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $this->idPartida;
		$borrar = "DELETE FROM mazo_actual WHERE id_partida = '$id' AND id_carta = '$nombre'";
		$rs = $conn->query($borrar);
	}
}
?>
