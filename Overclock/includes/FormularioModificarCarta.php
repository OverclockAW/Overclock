<?php

namespace xampp\htdocs\Overclock;

class FormularioModificarCarta extends Form {

	private $nAux;
	private $hAux;
	private $eAux;
	private $tAux;
	
	
	const HTML5_EMAIL_REGEXP = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

  public function __construct() {
    parent::__construct('formModificarCarta');
  }
  
  protected function generaCamposFormulario ($datos) {
	$nombre = "";
	$hp = "";
	$energia = "";
	$tipo = "";
	if(isset($_REQUEST['nombre'])&& isset($_REQUEST['hp'])&& isset($_REQUEST['energia'])&& isset($_REQUEST['tipo'])){
		$nombre = $_REQUEST['nombre'];
		$hp = $_REQUEST['hp'];
		$energia = $_REQUEST['energia'];
		$tipo = $_REQUEST['tipo'];
	}
	else{
		$nombre = self::getNombre();
		$hp = self::getHp();
		$energia = self::getEnergia();
		$tipo = self::getTipo();
	}
	
	
	if ($datos) {
		$nombre = isset($datos['nombre']) ? $datos['nombre'] : $nombre;
		$hp = isset($datos['hp']) ? $datos['hp'] : $hp;
		$energia = isset($datos['energia']) ? $datos['energia'] : $energia;
		$tipo = isset($datos['tipo']) ? $datos['tipo'] : $tipo;
	}

    $camposFormulario=<<<EOF
	<p><label>Nombre: </label><input type="text" name="nombreCarta" class="registro" id="nombreCarta" value="$nombre" disabled="true"/> <img class = "iconosFormularios" src="img/ok.png" id="nombreIcon"></p>
	<p><label>Hp: </label><input type="text" name="hpCarta" class="registro" id="hpCarta" value="$hp"/><img class = "iconosFormularios" src ="img/ok.png" id="hpIcon"></p>
	<p><label>Energia: </label><input type="text" name="energiaCarta" class="registro" id="energiaCarta" value="$energia"/><img class = "iconosFormularios" src="img/ok.png" id="energiaIcon"></p>
	<p><label>Tipo: </label><input type="text" name="tipoCarta" class="registro" id="tipoCarta" value="$tipo"/> <img class = "iconosFormularios" src="img/ok.png" id="tipoIcon"></p>
	<button type="submit" class="modButton" id="modButton" disabled="false"> Modificar datos </button>
EOF;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos) {
    $result = array();
    $ok = true;
    $nombre = isset($datos['nombreCarta']) ? $datos['nombreCarta'] : null ;
    if (!$nombre) {
	  $result[] = '<p class = "rojo">El nombre ha sido suprimido';
      $ok = false;
    }
	
	$energia = isset($datos['energiaCarta']) ? $datos['energiaCarta'] : null ;
    if (!$energia || $energia < 1 ) {
	$result[] = '<p class = "rojo">Energia incorrecta';
      $ok = false;
    }
	
	$tipo = isset($datos['tipoCarta']) ? $datos['tipoCarta'] : null ;
    if (!$tipo && $tipo != "Personaje" && $tipo != "Boss" && $tipo != "Objeto" && $tipo != "Evento") {
	$result[] = '<p class = "rojo">Tipo no valido';
      $ok = false;
    }
	
	$hp = isset($datos['hpCarta']) ? $datos['hpCarta'] : null ;
    if (!$hp || ($hp < 1 && ($tipo == "Personaje" || $tipo == "Boss")) || ($hp != -1 && ($tipo == "Evento" || $tipo == "Objeto"))) {
	$result[] = '<p class = "rojo">Puntos de vida incorrectos';
      $ok = false;
    }
	
    if ($ok){
	  self::setNombre($nombre);	
	  self::setEnergia($energia);
	  self::setTipo($tipo);
	  self::setHp($hp);
      $modificar = Carta::modificarCarta($nombre, $hp, $energia, $tipo);
      if ($modificar) {
		$result[] = '<p class = "dorado">Carta modificada con exito';
      }else {
        $result[] = '<p class = "rojo">Error al tratar de modificar los parametros';
      }
    }

    return $result;
  }
  
  private function getNombre(){
	  return $this->nAux;
  }
  private function getHp(){
	  return $this->hAux;
  }
  private function getEnergia(){
	  return $this->eAux;
  }
  private function getTipo(){
	  return $this->tAux;
  }
  
  private function setNombre($nombre){
	  $this->nAux = $nombre;
  }
   private function setEnergia($energia){
	  $this->eAux = $energia;
  }
   private function setHp($hp){
	  $this->hAux = $hp;
  }
   private function setTipo($tipo){
	  $this->tAux = $tipo;
  }
}
	
?>