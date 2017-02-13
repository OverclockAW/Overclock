<?php

namespace xampp\htdocs\Overclock;

use xampp\htdocs\Overclock\Aplicacion as App;

class Habilidad implements \JsonSerializable{
	
	private $nombre;
	private $tipo;
	private $valor;
	private $coste;
	private $area;
	
	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
	
	public function __construct($nombre, $tipo, $valor, $coste, $area){
		$this->nombre = $nombre;
		$this->tipo = $tipo;
		$this->valor = $valor;
		$this->coste = $coste;
		$this->area = $area;
	}
	
	public function getCoste(){
		return $this->coste;
	}
	
	public function getValor(){
		return $this->valor;
	}
	
	public function getArea(){
		return $this->area;
	}
	
	public function getTipo(){
		return $this->tipo;
	}
	
	public function getNombre(){
		return $this->nombre;
	}
	
	// Devuelve una Habilidad a partir de un array de valores
	public static function fromArrayToHabilidad($hab){
		return new Habilidad($hab["nombre"], $hab["tipo"], $hab["valor"], $hab["coste"], $hab["area"]);
	}
	
	// Devuelve una habilidad a partir de su id (nombre) de la tabla habilidades
	public static function cargarHabilidad($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $conn->real_escape_string($nombre);
		$query = "SELECT * FROM habilidades WHERE nombre = '$nombre'";
		$rs2 = $conn->query($query);
		$fila = $rs2->fetch_assoc();
		$hab = new Habilidad($fila['nombre'],$fila['tipo'],$fila['valor'],$fila['coste'],$fila['area']);
		$rs2->free();
		return $hab;
	}
}
?>
