<?php

namespace xampp\htdocs\Overclock;

use xampp\htdocs\Overclock\Aplicacion as App;

class Partida implements \JsonSerializable{

	//id de la partida (tabla partida)
	private $id;
	//id jugador 1 (tabla partida)
	private $jugador1;
	// rol jugador 1 (tabla jugadores)
	private $rolj1;
	// id jugador 2 (tabla partida)
	private $jugador2;
	// rol jugador 2 (tabla jugadores)
	private $rolj2;
	// finalizada (tabla partida)
	private $finalizada;
	// objeto mano con rol j1(tabla mano_actual)
	private $manoj1;
	// objeto mano con rol j2(tabla mano_actual)
	private $manoj2;
	// objeto campo con rol j1(tabla campo_actual)
	private $campoj1;
	// objeto campo con rol j2(tabla campo_actual)
	private $campoj2;
	// objeto mazo con rol j1(tabla mazo_actual)
	private $mazoj1;
	// objeto mazo con rol j2(tabla mazo_actual)
	private $mazoj2;
	//puntos del jugador con rol 'Master'(tabla jugadores)
	private $wipes;
	//puntos del jugador con rol 'Aventurero'(tabla jugadores)
	private $bossesRestantes;
	//indica quien tiene el turno de la partida
	private $turnoActual;
	//indica el n˙mero de turnos en juego que lleva la partida. Inicialmente 0
	private $nTurno;
	//indica el n˙mero del ˙ltimo movimiento
	private $nMov;
	
	private $registro;
	
	//Dice si puede robar el master
	private $rMaster;
	
	//Dice si puede robar el Aventurero
	
	private $rAventurero;

	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
		
	public function __construct($id){
		$this->id = $id;
		self::cargar($this->id);
		
		$this->rolj1 = "Aventurero";
		$this->rolj2 = "Master";

		
		$this->mazoj1 = new Mazo($this->id, $this->rolj1);
		$this->mazoj1->barajar();
		
		$this->mazoj2 = new Mazo($this->id, $this->rolj2);
		$this->mazoj2->barajar();

		$this->manoj1 = self::cargarMano($this->rolj1, $this->mazoj1);
		$this->manoj2 = self::cargarMano($this->rolj2, $this->mazoj2);
		
		$cartas1 = Campo::cargarCartas($this->rolj1,$this->id);
		$cartas2 = Campo::cargarCartas($this->rolj2,$this->id);
			
		$this->campoj1 = new Campo($this->id, $cartas1, $this->rolj1);
		$this->campoj2 = new Campo($this->id, $cartas2, $this->rolj2);
	}

	//Carga la fila de la tabla partidas
	private function cargar($id){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$query = "SELECT * FROM partidas WHERE id = '$id'";
		$rs = $conn->query($query);
		$fila = $rs->fetch_assoc();
		$this->jugador1 = $fila['jugador1'];
		$this->jugador2 = $fila['jugador2'];
		$this->turnoActual = $fila['turno_actual'];
		$this->nTurno = $fila['turno'];
		$this->bossesRestantes = $fila['bosses'];
		$this->finalizada = $fila['finalizada'];
		if($this->finalizada == "Si"){
			$this->ganador = $fila["ganador"];
		}
		$this->wipes = $fila['wipes'];
		$this->registro = array();
		$this->registro = self::cargarRegistro();
		$this->nMov = $fila['nMov'];
		$this->rMaster = $fila['robaMaster'];
		$this->rAventurero = $fila['robaAventurero'];
	}
	
	// Carga el registro de habilidades de la partida actual
	private function cargarRegistro(){
		$id = $this->id;
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$select = "SELECT descripcion FROM registro_habilidades WHERE id_partida = '$id'";
		$rs = $conn->query($select);
		$fila;
		$descripciones = array();
		if($rs && $rs->num_rows > 0){
			while($fila = $rs->fetch_assoc()){		
				array_push($descripciones, $fila["descripcion"]);
			}
			$rs->free();
		}
		return $descripciones;
	}
	
	public function getRegistro(){
		return $this->registro;
	}
	
	// Carga la mano del rol dado, robando una carta a partir del segundo turno
	public function cargarMano($rol, $mazo){
		$mano = new Mano($this->id, $rol, $this->nTurno, $mazo);
		if($this->nTurno > 0){
			if($rol == "Master"){
				self::robar($this->mazoj2, $mano, $this->rMaster);
			}
			else{
				self::robar($this->mazoj1, $mano, $this->rAventurero);
			}
		}
		return $mano;
	}
	
	// Roba una carta del mazo y la mete en la manodel jugador
	public function robar($mazo, $mano, $roba){
		if(sizeof($mano->getCartas()) < 10){
			$mano->robar($mazo, $roba);
		}
	}

	public function getTurno(){
		return $this->turnoActual;
	}
	
	public function getNTurno(){
		return $this->nTurno;
	}
	
	public function getFinalizada(){
		return $this->finalizada;
	}
	public function getJ1(){
		return $this->jugador1;
	}
	
	public function getJ2(){
		return $this->jugador2;
	}
	
	public function getWipes(){
		return $this->wipes;
	}
	public function getMazo($rol){
		if($rol == "Master"){
			return $this->mazoj2;
		}
		return $this->mazoj1;
	}

	public function getMano($rol){
		if($rol == "Master"){
			return $this->manoj2;
		}
		return $this->manoj1;
	}

	public function getCampo($rol){
		if($rol == "Master"){
			return $this->campoj2;
		}
		return $this->campoj1;
	}
	
	public function getBossesRestantes(){
		return $this->bossesRestantes;
	}
	
	public function getCartas($i, $rol){
		if($rol == Aventurero){
			return $this->manoj1->getCarta($i);
		}else{
			return $this->manoj2->getCarta($i);
		}
	}
	
	// Saca una carta de la mano para colocarla en el campo
	public function sacarCarta($argumentos){
		$result = new \stdClass();
	//	$result->posicion = false;
		$rol = $argumentos[0];  //Esto es el rol
		$carta;
		$ok = false;
		if($rol == "Aventurero"){
			$carta = $this->manoj1->getCarta($argumentos[1]);  //Esto es la pos de la carta que ha hecho click
			$result->posicion = $this->campoj1->colocar($carta);
			if($result->posicion != false){
				$result->descripcion = $rol." ha sacado ".$carta->getNombre();
				$this->manoj1->colocar($carta);
				self::eliminarCartaManoActual($carta,$rol);
				self::insertarCartaCampoActual($carta,$rol);
				$ok = true;
			}
		}
	
		//Si es Master
		else{
			$carta = $this->manoj2->getCarta($argumentos[1]); //Esto es la pos de la carta que ha hecho click
			$numBosses = 0;
			$cont = 0;
			$cartasMaster = $this->campoj2->getCartas();
			
			//Cuento el numero de cartas tipo boss en el campo
			while($cont < sizeof($cartasMaster)){
				$cAux = $cartasMaster[$cont];
				if($cAux->getTipo() == "Boss")
					$numBosses = $numBosses + 1;
				$cont = $cont + 1;
			}
			
			//Si la carta no es boss o es boss pero no hay otros bosses en el campo se puede colocar
			if($carta->getTipo() != "Boss" || ($carta->getTipo() == "Boss" && $numBosses == 0)){
				$result->posicion = $this->campoj2->colocar($carta);
				if($result->posicion != false){
					$result->descripcion = $rol." ha sacado ".$carta->getNombre();
					self::eliminarCartaManoActual($carta,$rol);
					self::insertarCartaCampoActual($carta,$rol);
					$this->manoj2->colocar($carta);
					$ok = true;
				}
			}
		}
		
		if($ok){
			// Crear fila de la tabla acciones
			$accion = new \stdClass();
			$accion->nombre = "sacarCarta";
			$accion->descripcion = $rol." ha sacado ".$carta->getNombre();
			$accion->carta = $carta->getNombre();
			$accion->vidaActual = $carta->getHp();
			$accion->campo = $rol;
			$accion->pos_origen = $argumentos[1];
			$accion->pos_destino = "null";
			$accion->id_carta_destino = "null";
			$accion->nombre_habilidad = "null";
			if($result->posicion != false){
				self::insertarAccion($accion);
				return $result;
			}
			else{
				return false;
			}
		}
		
		else{
			return false;
		}
	}
	
	// Inserta una acciÛn en la tabla acciones
	private function insertarAccion($accion){
		self::actualizarNMov();
		
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $this->id;
		$nombre = $accion->nombre;
		$descripcion = $accion->descripcion;
		$carta = $accion->carta;
		$vidaActual = $accion->vidaActual;
		$campo = $accion->campo;
		$pos_origen = $accion->pos_origen;
		$pos_destino = $accion->pos_destino;
		$id_carta_destino = $accion->id_carta_destino;
		$nombre_habilidad = $accion->nombre_habilidad;
		$n = $this->nMov;
		$insert = "INSERT INTO acciones (id_partida, nombre, descripcion, id_carta, vida, campo, pos_origen, pos_destino, id_carta_destino, nombre_habilidad, nMov) VALUES ('$id', '$nombre', '$descripcion', '$carta', '$vidaActual', '$campo', '$pos_origen', '$pos_destino', '$id_carta_destino', '$nombre_habilidad', '$n')";
		$rs = $conn->query($insert);
		
		$i = "INSERT INTO registro_habilidades (id_partida, descripcion) VALUES ('$id', '$descripcion')";
		$rs2 = $conn->query($i);
	}
	
	// Borra los movimientos de la tabla acciones que ya ha cogido
	private function deleteMovimientos(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$idPartida = $this->id;
		$n = $this->nMov;
		$delete = "DELETE FROM acciones WHERE id_partida = '$idPartida' AND nMov <= '$n'";
		$rs = $conn->query($delete);
	}
	
	// Coge los movimientos de la tabla acciones y los devuelve para luego ser tratados en el navegador que no tiene el turno actual para que pueda reproducirlos
	public function getMovimientos(){ // Devuelve un array de movimientos a partir del ˙ltimo solicitado
		// Cada movimiento es un objeto que tiene el nombre de la funciÛn que lo creÛ, la descripciÛn y la carta afectada (objeto Carta)
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $this->id;
		$nMov = $this->nMov;
		$select = "SELECT * FROM acciones WHERE id_partida = '$id'";
		$rs = $conn->query($select);
		$movimientos = array();
		$fila;
		if($rs && $rs->num_rows > 0){
			while($fila = $rs->fetch_assoc()){
				
				$accion = new \stdClass();
				$accion->nombre = $fila["nombre"];
				$accion->descripcion = $fila["descripcion"];
				$c =  Carta::cargarCarta($fila["id_carta"]);
				if($fila["id_carta_destino"] != "null"){
					$cDestino = Carta::cargarCarta($fila["id_carta_destino"]);
					$accion->cartaDestino = $cDestino;
				}
				if($fila["pos_origen"] != "null"){
					$accion->posOrigen = $fila["pos_origen"];
				}
				if($fila["pos_destino"] != "null"){
					$accion->posDestino = $fila["pos_destino"];
				}
				if($fila["nombre_habilidad"] != "null"){
					$accion->habilidad = Habilidad::cargarHabilidad($fila["nombre_habilidad"]);
				}
				$c->actualizarHp($fila["vida"]);
				$accion->carta = $c;
				$accion->campo = $fila["campo"];
				
				array_push($movimientos, $accion);
			}
			//$rs->free();
			$result = new \stdClass;
			$result->nA = sizeof($this->campoj1->getCartas()); 
			$result->nM = sizeof($this->campoj2->getCartas());
			$result->movimientos = $movimientos;
			//echo 'Movimiento: '.var_dump($movimientos);
			self::deleteMovimientos();
			return $result;
		}
		else{
			return false;
		}
	}
	
	// Recibe una habilidad y las posiciones implicadas en ella desde la vista al pulsar el botÛn, y dependiendo de su tipo la ejecuta
	public function usarHabilidad($argumentos){
		$args = json_decode($argumentos);
		$posMiCarta = $args[0];
		
		$hab = Habilidad::fromArrayToHabilidad(get_object_vars($args[1]));
		//$hab = json_decode($args[1]);
		$targetAmigo = $args[2];
		$rolTarget = $args[3];
		$targetEnemigo = $args[4];
		$enMiCampo = $args[5]; // Si la carta que ha usado la habilidad est· en tu campo
		if($enMiCampo != true){
			return "No puedes usar esa carta.";
		}
		// 4 tipos de habilidades: ataque, curaciÛn, energÌa y robo de vida.
		$tipo = $hab->getTipo();
		$result = new \stdClass();
		//echo var_dump($tipo);
		switch ($tipo) {
		    case "Ataque":
		    	if($targetEnemigo == -1){ // Si el target es correcto
		    		return false;
		    	}
		        $result = self::atacar($posMiCarta, $hab, $targetEnemigo, $rolTarget);
		        break;
		    case "Curacion":
		    	if($targetAmigo == -1){// Si el target es correcto
		    		return false;
		    	}
		    	$result = self::curar($posMiCarta, $hab, $targetAmigo, $rolTarget);
		        break;
		    case "Energia":
		    	if($targetAmigo == -1){// Si el target es correcto
		    		return false;
		    	}
		    	$result = self::restaurarEnergia($posMiCarta, $hab, $targetAmigo, $rolTarget);
		        break;
		    case "RoboVida":
		    	if($targetEnemigo == -1){// Si el target es correcto
		    		return false;
		    	}
		    	$result = self::robarVida($posMiCarta, $hab, $targetEnemigo, $rolTarget);
		    	break;
		}
		
		return $result; // Devuelve lo necesario para reproducir el movimiento en el navegador del turno actual 
	}
	
	// Roba una vida del target proporcionado (ya v·lido)
	private function robarVida($posMiCarta, $hab, $target, $rolTarget){
		$miRol;
		$result = new \stdClass();
		$resultHerir = new \stdClass();
		$accion = new \stdClass();
		if($rolTarget == "Master"){
			$miRol = "Aventurero";
			$energiaGastada = $this->campoj1->gastarEnergia($posMiCarta, $hab->getCoste(), $this->id);
			$accion->carta = $this->campoj1->getCartas()[$posMiCarta]->getNombre();
			if($energiaGastada == true){ // Si ha podido gastar energÌa es que puede atacar
				$resultHerir = $this->campoj2->herir($target, $hab->getValor(), $hab->getArea());
				$resultCurar = $this->campoj2->curar($posMiCarta, $hab->getValor(), $hab->getArea());
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) usÛ ".utf8_decode($hab->getNombre())." contra ".utf8_decode($resultHerir->nombre)." y le robÛ ".$hab->getValor()." de vida!");
				
				if ($resultHerir->hp == 0){ // Si la carta ha muerto
					self::matarCarta($miRol, $target, $this->campoj2,  $this->mazoj2);
					$result->descripcion .= " Golpe mortal";
				}
				else{
					self::setVidas($resultHerir->nombre, $resultHerir->hp);
				}
				// Ahora regeneramos nuestra vida
				self::setVidas($this->campoj1->getCartas()[$posMiCarta]->getNombre(), $this->campoj1->getCartas()[$target]->getHp() + $hab->getValor());
				$result->valor = $resultHerir->hp;
	
				$rol;
				if($miRol == "Aventurero")
					$rol = "Master";
					else
						$rol = "Aventurero";
	
				$accion->nombre = "robarVida";
				$accion->descripcion = $result->descripcion;
				$accion->vidaActual = $resultHerir->hp;
				$accion->campo = $rol;
				$accion->pos_origen = $posMiCarta;
				$accion->pos_destino = $target;
				$accion->id_carta_destino = $resultHerir->nombre;
				$accion->nombre_habilidad = $hab->getNombre();
				self::insertarAccion($accion);
			}
			else{
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) intentÛ usar ".utf8_decode($hab->getNombre())." pero est· cansado.");
				$result->valor = false;
			}
		}

		$accion->descripcion = $result->descripcion;
		$accion->target = $target;
		$accion->valor = $result->valor;
		return $accion;
	}
	
	// Ataca a una carta proporcionada como target (es la posiciÛn de la carta seleccionada).
	private function atacar($posMiCarta, $hab, $target, $rolTarget){
		$miRol;
		$result = new \stdClass();
		$resultHerir = new \stdClass();
		$accion = new \stdClass();
		if($rolTarget == "Master"){
			$miRol = "Aventurero";
			$energiaGastada = $this->campoj1->gastarEnergia($posMiCarta, $hab->getCoste(), $this->id);
			$accion->carta = $this->campoj1->getCartas()[$posMiCarta]->getNombre();
			if($energiaGastada == true){ // Si ha podido gastar energÌa es que puede atacar
				$resultHerir = $this->campoj2->herir($target, $hab->getValor(), $hab->getArea());
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) usÛ ".utf8_decode($hab->getNombre())." contra ".utf8_decode($resultHerir->nombre)." y causÛ ".$hab->getValor()." de daÒo!");
				if ($resultHerir->hp == 0){ // Si ha muerto, la eliminamos de la tabla campo_actual
					self::matarCarta($miRol, $target, $this->campoj2,  $this->mazoj2);
					$result->descripcion .= " Golpe mortal";
				}
				else{ // Le actualizamos la vida en la tabla campo_actual
					self::setVidas($resultHerir->nombre, $resultHerir->hp);
				}
				$result->valor = $resultHerir->hp;
				
				$rol;
				if($miRol == "Aventurero")
					$rol = "Master";
				else
					$rol = "Aventurero";
				// Rellena el resto de la acciÛn para meterla luego en la tabla acciones
				$accion->nombre = "atacar";
				$accion->descripcion = $result->descripcion;
				$accion->vidaActual = $resultHerir->hp; // Tendr· -1 si ha habido daÒo en ·rea
				$accion->campo = $rol;
				$accion->pos_origen = $posMiCarta;
				$accion->pos_destino = $target;
				$accion->id_carta_destino = $resultHerir->nombre;
				$accion->nombre_habilidad = $hab->getNombre();
				self::insertarAccion($accion);
			}
			else{// Si no tiene energÌa suficiente devolvemos un mensaje que se pondr· sÛlamente en el log del usuario del turno actual (da una pista)
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) intentÛ usar ".utf8_decode($hab->getNombre())." pero est· cansado.");
				
				$result->valor = false;
			}
		}
		else{
			$miRol = "Master";
			$accion->carta = $this->campoj2->getCartas()[$posMiCarta]->getNombre();
			$energiaGastada = $this->campoj2->gastarEnergia($posMiCarta, $hab->getCoste(), $this->id);
			if($energiaGastada == true){ // Si ha podido gastar energÌa es que puede atacar
				$resultHerir = $this->campoj1->herir($target, $hab->getValor(), $hab->getArea());
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Ma) usÛ ".utf8_decode($hab->getNombre())." contra ".utf8_decode($resultHerir->nombre)." y causÛ ".$hab->getValor()." de daÒo!");
				if($hab->getArea() == "Si"){ // Si la habilidad es en ·rea, recorremos las cartas y las atacamos
					$i = 0;
					$nMuertos = 0;
					foreach($this->campoj1->getCartas() as $c){
						if ($c->getHp() - $hab->getValor() <= 0){ // Si ha muerto la carta
							// Quita la carta del campo y aÒadir golpe mortal al mensaje
							self::matarCarta($miRol, $i,  $this->campoj1,  $this->mazoj1);
							$nMuertos++;
						}
						else{ // Si no muere, le actualizamos la vida en la tabla campo_actual
							self::setVidas($c->getNombre(), $c->getHp() - $hab->getValor());
						}
						$i++;
					}
					if($nMuertos > 0){
						$result->descripcion.= utf8_encode(" AcabÛ con ".$nMuertos." enemigo");
						if($nMuertos > 1){
							$result->descripcion.= utf8_encode("s");
						}
					}
				}
				else{
					if ($resultHerir->hp == 0){
						// Quita la carta del campo y aÒadir golpe mortal al mensaje
						self::matarCarta($miRol, $target,  $this->campoj1,  $this->mazoj1);
						$result->descripcion.= " Golpe mortal";
					}
					else{// Si no muere, le actualizamos la vida en la tabla campo_actual
						self::setVidas($resultHerir->nombre, $resultHerir->hp);
					}
				}
				$result->valor = $resultHerir->hp;
				
				$rol;
				if($miRol == "Aventurero")
					$rol = "Master";
				else
					$rol = "Aventurero";
				// Rellena el resto de la acciÛn para meterla luego en la tabla acciones
				$accion->nombre = "atacar";
				$accion->descripcion = $result->descripcion;
				$accion->descripcion = $result->descripcion;
				$accion->vidaActual = $resultHerir->hp; // Tendr· -1 si ha habido daÒo en ·rea
				$accion->campo = $rol;
				$accion->pos_origen = $posMiCarta;
				$accion->pos_destino = $target;
				$accion->id_carta_destino = $resultHerir->nombre;
				$accion->nombre_habilidad = $hab->getNombre();
				self::insertarAccion($accion);
			}
			else{// Si no tiene energÌa suficiente devolvemos un mensaje que se pondr· sÛlamente en el log del usuario del turno actual (da una pista)
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Ma) intentÛ usar ".utf8_decode($hab->getNombre())." pero necesita sacrificios.");
				$result->valor = false;
			}
		}

		$accion->descripcion = $result->descripcion;
		$accion->valor = $result->valor;
		$accion->target = $target;
		return $accion;
	}
	
	// Actualiza la vida de la carta en campo_actual
	private function setVidas($nombre, $vida){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombre = $conn->real_escape_string($nombre);
		$vida = $conn->real_escape_string($vida);
		$id = $this->id;
		$id = $conn->real_escape_string($id);
		$update = "UPDATE campo_actual SET vida_restante = '$vida' WHERE id_carta = '$nombre' AND id_partida = '$id'";
		$rs = $conn->query($update);
	}
	
	//Borra una carta del campo (objeto y BD) y la guarda en el mazo (objeto y BD)
	private function matarCarta($miRol, $target, $campo, $mazo){
		$cartasCampo = $campo->getCartas();
		$cartasMazo = $mazo->getCartas();
		$nombre = $cartasCampo[$target]->getNombre();
		$id = $this->id;
		
		//Si la carta es de tipo Boss no volver· al mazo
		if($cartasCampo[$target]->getTipo() != "Boss"){
			//Guardo la carta destruida en el mazo
			array_push($cartasMazo, $cartasCampo[$target]);
			$mazo->setCartas($cartasMazo);
			
			//Guardo la carta tambien en la BD
			$mazo->guardarCartaEliminada($id, $nombre, $miRol);	
		}
		
		//Saco del array campo la carta eliminada
		unset($cartasCampo[$target]);
		$campo->setCartas($cartasCampo);
		
		//Tambien la borro de la BD campo
		$campo->cartaDestruidaBD($id, $nombre);
	}
	
	// Actualiza el n˙mero de movimientos que se han hecho en la partida
	private function actualizarNMov(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $conn->real_escape_string($this->id);
		$this->nMov = $this->nMov + 1;
		$nMov = $conn->real_escape_string($this->nMov);
		$update = "UPDATE partidas SET nMov = '$nMov' WHERE id = '$id'";
		$guardar = $conn->query($update);
		
	}
	
	// Cura al objetivo amigo, recibiendo su posiciÛn
	private function curar($posMiCarta, $hab, $target, $rolTarget){
		$miRol;
		$result = new \stdClass();
		$resultCurar = new \stdClass();
		$accion = new \stdClass();
		if($rolTarget == "Aventurero"){
			$miRol = "Aventurero";
			$energiaGastada = $this->campoj1->gastarEnergia($posMiCarta, $hab->getCoste(), $this->id);
			$accion->carta = $this->campoj1->getCartas()[$posMiCarta]->getNombre();
			if($energiaGastada == true){ // Si ha podido gastar energÌa es que puede atacar
				$resultCurar = $this->campoj1->curar($target, $hab->getValor(), $hab->getArea());
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) usÛ ".utf8_decode($hab->getNombre())." sobre ".utf8_decode($resultCurar->nombre)." y le curÛ ".utf8_decode($resultCurar->hp)."p de vida!");
				if($hab->getArea() == "Si"){ // Si la cura es en ·rea, curamos a todos (dentro curar· a los que no son objetos)
					$i = 0;
					foreach($this->campoj1->getCartas() as $c){
						self::setVidas($c->getNombre(), $c->getHp() + $hab->getValor());
						$i++;
					}
				}
				else{ // Si no es en ·rea, curamos sÛlo al objetivo
					self::setVidas($resultCurar->nombre, $this->campoj1->getCartas()[$target]->getHp() + $hab->getValor());
				}
				$result->valor = $resultCurar->hp;
				$accion->descripcion = $result->descripcion;
				$accion->nombre = "curar";
				$accion->vidaActual = $result->valor; // Tendr· -1 si ha habido daÒo en ·rea
				$accion->campo = $miRol;
				$accion->pos_origen = $posMiCarta;
				$accion->pos_destino = $target;
				$accion->id_carta_destino = $resultCurar->nombre;
				$accion->nombre_habilidad = $hab->getNombre();
				self::insertarAccion($accion);
			}
			else{// Si no tiene energÌa suficiente devolvemos un mensaje que se pondr· sÛlamente en el log del usuario del turno actual (da una pista)
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Av) intentÛ usar ".utf8_decode($hab->getNombre())." pero est· cansado.");
				$result->valor = false;
			}
		}
		else{
			$miRol = "Master";
			$accion->carta = $this->campoj2->getCartas()[$posMiCarta]->getNombre();
			$energiaGastada = $this->campoj2->gastarEnergia($posMiCarta, $hab->getCoste(), $this->id);
			if($energiaGastada == true){ // Si ha podido gastar energÌa es que puede curar
				$resultCurar = $this->campoj2->curar($target, $hab->getValor(), $hab->getArea());
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Ma) usÛ ".utf8_decode($hab->getNombre())." sobre ".utf8_decode($resultCurar->nombre)." y le curÛ ".utf8_decode($resultCurar->hp)."p de vida!");
				$result->valor = $resultCurar->hp;
				self::setVidas($resultCurar->nombre, $this->campoj2->getCartas()[$target]->getHp() + $hab->getValor());
				
				$accion->nombre = "curar";
				$accion->vidaActual = $result->valor; // Tendr· -1 si ha habido daÒo en ·rea
				$accion->campo = $miRol;
				$accion->pos_origen = $posMiCarta;
				$accion->pos_destino = $target;
				$accion->id_carta_destino = $resultCurar->nombre;
				$accion->nombre_habilidad = $hab->getNombre();
				self::insertarAccion($accion);
			}
			else{// Si no tiene energÌa suficiente devolvemos un mensaje que se pondr· sÛlamente en el log del usuario del turno actual (da una pista)
				$result->descripcion = utf8_encode(utf8_decode($accion->carta)." (Ma) intentÛ usar ".utf8_decode($hab->getNombre())." pero necesita sacrificios.");
				$result->valor = false;
			}
		}
		

		$accion->descripcion = $result->descripcion;
		$accion->target = $target;
		$accion->valor = $result->valor;
		return $accion;
	}
	
	// Restaura la energÌa del objetivo amigo. SÛlo el Master dispone de cartas con este tipo de habilidad
	private function restaurarEnergia($posMiCarta, $hab, $target, $rolTarget){
		$miRol;
		$result = new \stdClass();
		$resultCurar = new \stdClass();
		$accion = new \stdClass();
		if($rolTarget == "Master"){
			$miRol = "Master";
			$accion->carta = $this->campoj2->getCartas()[$posMiCarta]->getNombre();
			$resultEnergia = $this->campoj2->restaurarEnergia($target, $hab->getValor(), $this->id);
			$result->descripcion = utf8_encode("°Master usÛ ".utf8_decode($hab->getNombre())." sobre ".utf8_decode($resultEnergia->nombre)." y le restaurÛ ".utf8_decode($resultEnergia->e)."p de energÌa!");
			$result->valor = $resultEnergia->e;
		
			$accion->nombre = "energia";
			$accion->descripcion = $result->descripcion;
			$accion->vidaActual = $result->valor; // vidaActual representa la energÌa que le ha restaurado. Usamos la misma columna en la base de datos
			$accion->campo = $miRol;
			$accion->pos_origen = $posMiCarta;
			$accion->pos_destino = $target;
			$accion->id_carta_destino = $resultEnergia->nombre;
			$accion->nombre_habilidad = $hab->getNombre();
			self::insertarAccion($accion);
			$accion->target = $target;
			return $accion;
		}
	}
	
	// Busca si hay una partida disponible
	public static function buscar($email){
		$app = App::getSingleton();
		$conn = $app->conexionBd();

		$miPartidaConRival = "SELECT * FROM partidas WHERE jugador1 = '$email' AND jugador2 IS NULL";
		$r5 = $conn->query($miPartidaConRival);
		
		if($r5 && $r5->num_rows > 0){
			$result["ok"] = false; 
			$result["p"] = "Aun no hay partida";
			return $result;
		}
			
		$query = "SELECT * FROM partidas WHERE jugador1 = '$email' AND jugador2 IS NOT NULL";
		$rs = $conn->query($query);
		$result = array();
		$result["ok"] = false;
		$result["p"] = "Aun no hay partida";
		
		if ($rs && $rs->num_rows > 0) { // Si jugador 1 est· inscrito en el buscador
			$fila = $rs->fetch_assoc(); // Ahora comprobar si hay jugador 2 o no
			$result["ok"] = true;
			$result["p"] = $fila["id"];
			return $result;
		}
		
		$query = "SELECT * FROM partidas WHERE jugador1 <> '$email' AND jugador1 IS NOT NULL AND jugador2 IS NULL";
		// Pueden pasar dos cosas, que no existan filas o que existan (Hay jugadores buscando partida, y en ese caso, se inserta el email como jugador 2 en la primera fila que encuentre)
		$rs2 = $conn->query($query);
		
		if ($rs2 && $rs2->num_rows == 0){ // Si no hay filas, hay que comprobar que no est√© ya inscrito y ya inscribir el email en la tabla como jugador 1
			$q = "SELECT * FROM partidas WHERE (jugador1 = '$email' AND jugador2 IS NOT NULL) OR (jugador2 = '$email' AND jugador1 <> '$email')"; // HAY QUE HACER LO CONTRARIO
			$r = $conn->query($q);
			
			if($r && $r->num_rows > 0){ // Devolver la partida
				$fila = $r->fetch_assoc(); // Ahora comprobar si hay jugador 2 o no
				$result["ok"] = true;
				$result["p"] = $fila["id"];
				return $result;
			}
			
			if ($r && $r->num_rows == 0){
				$insert = "INSERT INTO partidas(jugador1) VALUES('$email')";
				$rs3 = $conn->query($insert);
			}
			
			$result["ok"] = false; //El jugador 2 no est√° inscrito a√∫n, seguimos buscando, entonces  devolvemos ok = false (jugador 1 = email ahora)
			$result["p"] = "Aun no hay partida";
			return $result;
		}
		else{ // Si hay otros jugadores buscando partida, buscamos un sitio
			$fila = $rs2->fetch_assoc(); // Cogemos el primer valor
			$idFila = $fila["id"];
			// Hay una partida disponible,entonces a√±adimos al propio jugador en jugador 2 y devolvemos la partida
			$update = "UPDATE partidas SET jugador2 = '$email' WHERE id = '$idFila'";
			$rs4 = $conn->query($update);
			$result["ok"] = true;
			$result["p"] = $fila["id"];
		}
		
		return $result;
	}

	// Cancela la b˙squeda del jugador cuando le das al botÛn de cancelar al buscar partida
	public static function eliminarBusqueda($email){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$email = $conn->real_escape_string($email);
		$borrar = "DELETE FROM partidas WHERE jugador1 = '$email' AND jugador2 IS NULL";
		$rs = $conn->query($borrar);
	}
	
	// Borra la fila de la tabla partidas correspondiente a la partida actual. Se usa al finalizar la partida o al rendirse
	private function borrarPartida(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $conn->real_escape_string($this->id);
		$delete = "DELETE FROM partidas WHERE id = '$id'";
		$rs = $conn->query($delete);
	}
	
	// Termina la partida de forma abrusca, mostrando mensaje al jugador que se rinde y redirige al Ranking
	public function rendirse($args){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $args[0];
		$id = $conn->real_escape_string($id);
		$email = $args[1];
		$result = new \stdClass();
		if($email == $this->jugador1){
			$update = "UPDATE partidas SET finalizada = 'Si', ganador = 'Master'  WHERE id = '$id'";
			$rs = $conn->query($update);
			self::actualizarGanadas($this->jugador2);
			self::actualizarPerdidas($this->jugador1);
			$result->ganador = $this->jugador2;
			$result->perdedor = $this->jugador1;
			//$rs->free();
		}
		if($email == $this->jugador2){
			$update = "UPDATE partidas SET finalizada = 'Si', ganador = 'Aventurero'  WHERE id = '$id'";
			$rs = $conn->query($update);
			self::actualizarGanadas($this->jugador1);
			self::actualizarPerdidas($this->jugador2);
			$result->ganador = $this->jugador1;
			$result->perdedor = $this->jugador2;
			//$rs->free();
		}
		$accion = new \stdClass();
		$accion->nombre = "rendirse";
		$accion->descripcion = $email." se ha rendido.";
		$accion->carta = 0;
		$accion->vidaActual = 0;
		$accion->campo = 0;
		$accion->pos_origen = 0;
		$accion->pos_destino = 0;
		$accion->id_carta_destino = 0;
		$accion->nombre_habilidad = "Menudo cagao estas hecho chico.";
		
		// Insertamos la acciÛn para que el otro jugador pueda ver que se ha rendido
		self::insertarAccion($accion);

		return $result;
	}
	
	// Termina el turno, comprobando si la partida ha finalizado
	public function finTurno(){ //id, turnoActual, nTurno
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$idPartida = $this->id;
		$siguienteTurno = self::siguienteTurno();
		$n = $this->nTurno + 1;
		$idPartida = $conn->real_escape_string($idPartida);
		$siguienteTurno = $conn->real_escape_string($siguienteTurno);
		//Estas dos lineas hay que ver si funcionan
		self::comprobarMaster();
		self::comprobarAventurero();
		$update = "UPDATE partidas SET turno = '$n', turno_actual = '$siguienteTurno', robaMaster = 'Si',robaAventurero = 'Si'  WHERE id = '$idPartida'";
		$rs = $conn->query($update);
		//$rs->free();
		
		if($this->wipes == 5){
			$update = "UPDATE partidas SET finalizada = 'Si', ganador = 'Master'  WHERE id = '$idPartida'";
			$rs = $conn->query($update);
			
			self::actualizarGanadas($this->jugador2);
			self::actualizarPerdidas($this->jugador1);
			unset($_SESSION['partida']);
			self::borrarPartida();
			//$rs->free();
		}
		if($this->bossesRestantes == 0){
			$update = "UPDATE partidas SET finalizada = 'Si', ganador = 'Aventurero'  WHERE id = '$idPartida'";
			$rs = $conn->query($update);
			self::actualizarGanadas($this->jugador1);
			self::actualizarPerdidas($this->jugador2);
			unset($_SESSION['partida']);
			self::borrarPartida();
			//$rs->free();
		}
		self::subirEnergia();
		return true;
	}
	
	// Actualiza el n˙mero de partidas ganadas por un jugador
	private function actualizarGanadas($usuario){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$usuario = $conn->real_escape_string($usuario);
		
		$query = "SELECT ganadas FROM usuarios WHERE email = '$usuario'";
		$rs = $conn->query($query);
		$fila = $rs->fetch_assoc();
		$n = (int)$fila['ganadas'] + 1;
		$n = $conn->real_escape_string($n);
		$update = "UPDATE usuarios SET ganadas = '$n' WHERE email = '$usuario'";
		$guardar = $conn->query($update);
	}
	
	// Actualiza el n˙mero de partidas perdidas por un jugador
	private function actualizarPerdidas($usuario){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$usuario = $conn->real_escape_string($usuario);
	
		$query = "SELECT perdidas FROM usuarios WHERE email = '$usuario'";
		$rs = $conn->query($query);
		$fila = $rs->fetch_assoc();
		$n = (int)$fila['perdidas'] + 1;
		$n = $conn->real_escape_string($n);
		$update = "UPDATE usuarios SET perdidas = '$n' WHERE email = '$usuario'";
		$guardar = $conn->query($update);
	}
	
	// Sube la energÌa a todas las cartas de la partida. Se usa al terminar el turno para que al principio de cada turno, las cartas tengan toda su energÌa
	private function subirEnergia(){		
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$id = $this->id;
		$id = $conn->real_escape_string($id);
		$update = "UPDATE campo_actual SET energia_restante = '10' WHERE id_partida = '$id'";
		$guardar = $conn->query($update);
	}
	// Mira si hay alg˙n boss en pie. Si no hay ninguno, resta uno a los bosses restantes
	private function comprobarMaster(){
		$cartasMazo = $this->mazoj2->getCartas();
		$cartasCampo = $this->campoj2->getCartas();
		$contador = 0;
		$contadorBosses = 0;
		$idPartida = $this->id;
		$tamArray = sizeof($cartasCampo);
		//Recorro todas las cartas del campo master
	
		while($contador < $tamArray){
			$cAux = $cartasCampo[$contador];
			if($cAux->getTipo() == "Boss")
				$contadorBosses = $contadorBosses + 1;
				//Si no son de tipo boss las elimino del campo y las meto en el mazo (objeto y BD)
				else{
					//Borro de campo
					unset($cartasCampo[$contador]);
					$this->campoj2->cartaDestruidaBD($idPartida,$cAux->getNombre());
					//Vuelve al mazo
					array_push($cartasMazo, $cAux);
					$this->mazoj2->guardarCartaEliminada($idPartida,$cAux->getNombre(), "Aventurero");
				}
				$contador = $contador + 1;
		}
		$this->campoj2->setCartas($cartasCampo);
		$this->mazoj2->setCartas($cartasMazo);
		if($contadorBosses == 0){
			self::actualizarNBosses();
		}
		if($this->bossesRestantes == 0){
			$update = "UPDATE partidas SET ganador = 'Aventurero', finalizada = 'Si' WHERE id = '$idPartida'";
		}
	}
	
	// Mira si hay alg˙n aventurero en pie. Si no hay, suma 1 al n˙mero de wipes
	private function comprobarAventurero(){
		$cartasMazo = $this->mazoj1->getCartas();
		$cartasCampo = $this->campoj1->getCartas();
		$contador = 0;
		$contadorPersonajes = 0;
		$idPartida = $this->id;
		$tamArray = sizeof($cartasCampo);
		while($contador < $tamArray){
			$cAux = $cartasCampo[$contador];
			if($cAux->getTipo() == "Personaje")
				$contadorPersonajes = $contadorPersonajes + 1;
			//Si no son de tipo Personaje las elimino del campo y las meto en el mazo (objeto y BD)
			else{
				//Borro de campo
				unset($cartasCampo[$contador]);
				$this->campoj1->cartaDestruidaBD($idPartida,$cAux->getNombre());
				//Vuelve al mazo
				array_push($cartasMazo, $cAux);
				$this->mazoj1->guardarCartaEliminada($idPartida,$cAux->getNombre(), "Master");
			}
			$contador = $contador + 1;
		}
		$this->campoj1->setCartas($cartasCampo);
		$this->mazoj1->setCartas($cartasMazo);
		if($contadorPersonajes == 0){
			self::actualizarNWipes();
		}
		if($this->wipes == 5){
			$update = "UPDATE partidas SET ganador = 'Master', finalizada = 'Si' WHERE id = '$idPartida'";
		}
	}
	
	//Actualiza el numero de bosses restante tanto en el objeto como en la BD
	private function actualizarNBosses(){
		if($this->nTurno > 0){
			$app = App::getSingleton();
			$conn = $app->conexionBd();
			$nBosses = $this->bossesRestantes;
			$nBosses = $nBosses - 1;		
			$id = $this->id;
			$update = "UPDATE partidas SET bosses = '$nBosses' WHERE id = '$id'";
			$guardar = $conn->query($update);	  
			//$guardar->free();
		}
	}
	//Actualiza el numero de wipes tanto en el objeto como en la BD
	private function actualizarNWipes(){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nWipes = $this->wipes;
		$nWipes = $nWipes + 1;
		$id = $this->id;
		$update = "UPDATE partidas SET wipes = '$nWipes' WHERE id = '$id'";
		$guardar = $conn->query($update);	
		//$guardar->free();
	}
	
	// Devuelve cu·l serÌa el siguiente turno
	private function siguienteTurno(){
		if($this->turnoActual == "Aventurero"){
			return "Master";
		}
		else{
			return "Aventurero";
		}
	}
	
	// Elimina una carta de la mano_actual
	private function eliminarCartaManoActual($carta, $rol){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombreCarta = $carta->getNombre();
		$rol = $conn->real_escape_string($rol);
		$nombreCarta = $conn->real_escape_string($nombreCarta);
		$idPartida = $this->id;
		$delete = "DELETE FROM mano_actual WHERE id_carta = '$nombreCarta' AND id_partida = '$idPartida' AND rol = '$rol' LIMIT 1";
		$rs = $conn->query($delete);
		//rs->free();
	}
	
	// Inserta una carta en el campo_actual
	private function insertarCartaCampoActual($carta, $rol){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$nombreCarta = $carta->getNombre();
		$vidaRestante = $carta->getHp();
		$idPartida = $this->id;
		$rol = $conn->real_escape_string($rol);
		$nombreCarta = $conn->real_escape_string($nombreCarta);
		$vidaRestante = $conn->real_escape_string($vidaRestante);		
		$insert = "INSERT INTO campo_actual (id_carta, id_partida, vida_restante, rol) VALUES ('$nombreCarta', '$idPartida', '$vidaRestante', '$rol')";
		$rs = $conn->query($insert);
		//$rs->free();
	}

	// Comprueba si es tu turno
	public function comprobarMiTurno($argumentos){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$rol = $argumentos[0];
		$rol = $conn->real_escape_string($rol);
		$idPartida = $this->id;
		$insert = "SELECT * FROM partidas WHERE turno_actual = '$rol' AND id = '$idPartida'";
		$rs = $conn->query($insert);

		if($rs && $rs->num_rows == 0){
			return "°A˙n no es tu turno!";
		}
		else return true;
	}
}
?>