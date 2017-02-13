<?php

namespace xampp\htdocs\Overclock;

use xampp\htdocs\Overclock\Aplicacion as App;

class Mano implements \JsonSerializable{

	private $idPartida;
	private $cartas;
	private $rol;
	
	const MAX_CARTAS_MANO = 10;
	
	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
	public function __construct($id, $rol, $turno, $mazo){
	$this->idPartida = $id;
		$this->rol = $rol;
		$this->cartas = self::cargarMano($id,$rol);
		if($turno == 0 and sizeof($this->cartas) == 0){
			$this->cartas = self::buscaMano($mazo);
			self::guardarMano($this->idPartida, $this->rol,$this->cartas);
		}
	}
	
	public function getCartas(){
		return $this->cartas;
	}
	
	// Busca la mano del jugador 
	private function buscaMano($mazo){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $this->idPartida;
		$rol = $this->rol;
		$mano = array();

		if($rol == "Aventurero"){
			
			//Primero cargo 5 personajes al azar de la bd
			$personajes = 0;
			$qPersonajes = "SELECT id, id_carta FROM mazo_actual WHERE id_partida = '$id' AND rol = '$rol'";
			$busPersonajes = $conn->query($qPersonajes);
			while($fila = $busPersonajes->fetch_assoc() and $personajes < 5){
				$n = $fila['id_carta'];
				$carta = Carta::cargarCarta($n);
				if($carta->getTipo() == "Personaje"){
					array_push($mano, $carta);
					$idFila = $fila['id'];
					$borPersonjes = "DELETE FROM mazo_actual WHERE id = '$idFila' AND id_partida = '$id'";
					$borPersonaje = $conn->query($borPersonjes);
					$personajes = $personajes+1;
				}
			}
				
			$busPersonajes->free();
				
			//Despues cargo 5 objetos al azar de la bd
			$qObjetos = "SELECT id, id_carta FROM mazo_actual WHERE id_partida = '$id' AND rol = '$rol'";
			$busObjetos = $conn->query($qObjetos);
				
			while($fila = $busObjetos->fetch_assoc() and sizeof($mano) < self::MAX_CARTAS_MANO){
				$n = $fila['id_carta'];
				$carta = Carta::cargarCarta($n);
				if($carta->getTipo() == "Objeto"){
					array_push($mano, $carta);
					$idFila = $fila['id'];
					$borObjetos = "DELETE FROM mazo_actual WHERE id = '$idFila' AND id_partida = '$id'";
					$borObjeto = $conn->query($borObjetos);
				}
			}
			$busObjetos->free();
			self::borrarDeMazo($mazo, $mano);
			return $mano;
		}
			
		else{
				
			//Cargo todos los bosses de la bd
			$qBosses = "SELECT id, id_carta FROM mazo_actual WHERE id_partida = '$id' AND rol = '$rol'";
			$busBosses = $conn->query($qBosses);
			$bosses = 0;
			while($fila = $busBosses->fetch_assoc() and $bosses < 6){
				$n = $fila['id_carta'];
				$carta = Carta::cargarCarta($n);
				if($carta->getTipo() == "Boss"){
					array_push($mano, $carta);
					$idFila = $fila['id'];
					$borBosses = "DELETE FROM mazo_actual WHERE id = '$idFila' AND id_partida = '$id'";
					$borBoss = $conn->query($borBosses);
					$bosses = $bosses+1;
				}
			}
			$busBosses->free();
				
			$qEventos = "SELECT id, id_carta FROM mazo_actual WHERE id_partida = '$id' AND rol = '$rol'";
			$busEventos = $conn->query($qEventos);
			while($fila = $busEventos->fetch_assoc() and sizeof($mano) < self::MAX_CARTAS_MANO){
				$n = $fila['id_carta'];
				$carta = Carta::cargarCarta($n);
				if($carta->getTipo() == "Evento"){
					array_push($mano, $carta);
					$idFila = $fila['id'];
					$borEventos = "DELETE FROM mazo_actual WHERE id = '$idFila' AND id_partida = '$id'";
					$borEvento = $conn->query($borEventos);
				}
			}
			$busEventos->free();	
			self::borrarDeMazo($mazo, $mano);
			return $mano;
		}
		return false;
	}
	
	// Borra del mazo una car
	private function borrarDeMazo($mazo, $mano){
		$cartasMazo = $mazo->getCartas();
		$contador = 0;
		while(sizeof($mano) < $contador){
			$contador2 = 0;
			$encontrado = false;
			$cartaMano = $mano[$contador];
			while(sizeof($cartasMazo) < $contador2 && !$encontrado){
				$cartaMazo = $cartasMazo[$contador2];
				if($cartaMazo->getNombre() == $cartaMano->getNombre()){
					$encontrado = false;
					unset($cartasMazo[$contador2]);
				}
				else{
					$contador2 = $contador2 + 1;
				}
			}
			$contador = $contador + 1;
		}
		$mazo->setCartas($cartasMazo);
	}
	
	// Roba una carta del mazo
	public function robar($mazo, $roba){
		  $app = App::getSingleton();
		  $conn = $app->conexionBd();
		  $cartasMazo = $mazo->getCartas();
		  $rol = $this->rol;
		  $id = $this->idPartida;
		  //Si es aventurero
		  if($rol == "Aventurero"){
			   if($roba == "Si"){
			    $contadorRecorrido = 0;
			    $contadorHeroes = 0;
			     
			    //Miro el numero de personajes en la mano
			    while($contadorRecorrido < sizeof($this->cartas)){
				     $carta = $this->cartas[$contadorRecorrido];
				     if($carta->getTipo() == "Personaje"){
				     	 $contadorHeroes = $contadorHeroes + 1;
				     }
				     $contadorRecorrido = $contadorRecorrido + 1;
			    }
			     
			    //Si hay un minimo de heroes en la mano cogemos solo una carta al azar del mazo
			    if($contadorHeroes > 3){
				     //Borra de mazo
				     $carta = $cartasMazo[0];
				     $mazo->borrarCarta($carta->getNombre());
				     unset($cartasMazo[0]);
				     $mazo->setCartas($cartasMazo);
				     //Guarda en mano
				     array_push($this->cartas, $carta);
				     self::guardarCarta($carta->getNombre()); 
			    }
			     
			    //Si no hay un minimo de heroes cargamos tantas cartas de personaje como sean necesarias
			    else{
				     $contadorRecorrido = 0;
				     $numMazo = sizeof($cartasMazo);
				     while($contadorHeroes < 4 && $contadorRecorrido < $numMazo){
				      $carta = $cartasMazo[$contadorRecorrido];
				      //Si es del personaje la guardo
					      if($carta->getTipo() == "Personaje"){
						       $mazo->borrarCarta($carta->getNombre());
						       unset($cartasMazo[$contadorRecorrido]);
						       $mazo->setCartas($cartasMazo);
						       //Guarda en mano
						       array_push($this->cartas, $carta);
						       self::guardarCarta($carta->getNombre()); 
						       $contadorHeroes = $contadorHeroes + 1;
					      }
				      $contadorRecorrido = $contadorRecorrido + 1;
			     	}
			    }
			
			    $updateRoba = "UPDATE partidas SET robaAventurero = 'No' WHERE id = '$id'"; 
			    $rs = $conn->query($updateRoba);
			   }
			  }
			  
			  //Si es master
			  else{
				   //Si puede robar en este turno (no ha dado a f5 para trampear)
				   if($roba == "Si"){
					    //Borra de mazo
					    $carta = $cartasMazo[0];
					    $mazo->borrarCarta($carta->getNombre());
					    unset($cartasMazo[0]);
					    $mazo->setCartas($cartasMazo);
					    //Guarda en mano
					    array_push($this->cartas, $carta);
					    self::guardarCarta($carta->getNombre());
					    $updateRoba = "UPDATE partidas SET robaMaster = 'No' WHERE id = '$id'"; 
					    $rs = $conn->query($updateRoba);
				   }
		 	 }
	 }
		
	
	// Coloca una carta en el campo
	public function colocar($carta){
		$contador = 0;
		$encontrado = false;
		while($contador < sizeof($this->cartas)&& !$encontrado){
			$aux = $this->cartas[$contador];
			if($aux->getNombre() == $carta->getNombre()){
				unset($this->cartas[$contador]);
				$encontrado = true;
			}
			else{
				$contador = $contador+1;
			}
		}
	}
	
	// Devuelve una carta de la mano dada una posición
	public function getCarta($i){
		return $this->cartas[$i];
	}
	
	// Devuelve un objeto Mano a partir de un array de valores
	public static function fromArrayToMano($argumentos){
		$arrayMano = $argumentos["cartas"];
		$cartasMano = array();
		$arrayCartas = array();
		$rol;
		if(sizeof($arrayMano) == 3){
			$arrayCartas = $arrayMano[1];
			foreach($arrayCartas as $c){
				array_push($cartasMano, new Carta($c["nombre"], $c["descripcion"], $c["imagen"], $c["hp"], $c["energia"], $c["tipo"]));
			}
			$rol = $arrayMano[2];
		}
		else{
			$rol = $arrayMano[1];
		}
		return new Mano($arrayMano[0], $cartasMano, $rol);
	}
	
	// Carga la mano del jugador, cargando a su vez las cartas de  la mano
	private function cargarMano($idPartida, $rol){
		$cartas = array();
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$idPartida = $conn->real_escape_string($idPartida);
		$rol = $conn->real_escape_string($rol);
		$query = "SELECT * FROM mano_actual WHERE id_partida = '$idPartida' AND rol = '$rol'";
		$rs = $conn->query($query);
		if($rs->num_rows > 0){
			$query = "SELECT id_carta FROM mano_actual WHERE rol = '$rol' AND id_partida = '$idPartida'";
			while($fila = $rs->fetch_assoc() and (sizeof($cartas) < self::MAX_CARTAS_MANO)){
				$carta = Carta::cargarCarta($fila['id_carta']);
				array_push($cartas,$carta);
			}
		}	
		return $cartas;
	}
	
	// Inserta en la tabla mano_actual las cartas de la mano del jugador, para que se mantengan en la partida
	private function guardarMano($idPartida, $rol, $cartas){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$idPartida = $conn->real_escape_string($idPartida);
		$rol = $conn->real_escape_string($rol);
		foreach($cartas as $c){
			$nombre = $c->getNombre();
			$query = "INSERT INTO mano_actual(id_carta,id_partida,rol) VALUES('$nombre','$idPartida','$rol')";
			$rs = $conn->query($query);
		}	
	}
	
	// Guarda una sola carta en mano_actual
	private function guardarCarta($nombre){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$idPartida = $this->idPartida;
		$rol = $this->rol;
		$query = "INSERT INTO mano_actual(id_carta,id_partida,rol) VALUES('$nombre','$idPartida','$rol')";
		$rs = $conn->query($query);
	}
}

?>
