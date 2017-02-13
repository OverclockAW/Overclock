<?php

namespace xampp\htdocs\Overclock;

class FormularioModificarUsuario extends Form {

  const HTML5_EMAIL_REGEXP = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

  public function __construct() {
    parent::__construct('formModificarUsuario');
  }
  
  protected function generaCamposFormulario ($datos) {
    $email = Aplicacion::getSingleton()->emailUsuario();
 
    if ($datos) {
      $email = isset($datos['email']) ? $datos['email'] : $email;
    }

    $camposFormulario=<<<EOF
			<p><label>Correo electrónico: </label><input type="text" name="email" class="email" id="email" value= "$email" /><img class = "iconosFormularios" src="img/eliminar.png" id="mailIcon"></p>
			<p><label>Contraseña: </label><input type="password" name="password" class="password" id="password"/></p>
			<p><label>Repetir Contraseña: </label><input type="password" name="password2" class="password2" id="password2"/><img class = "iconosFormularios" src="img/eliminar.png" id="passIcon"></p>
			<button type="submit" class="modButton" id="modButton" disabled="true"> Modificar datos </button>
EOF;
    return $camposFormulario;
  }

  /**
   * Procesa los datos del formulario.
   */
  protected function procesaFormulario($datos) {
    $result = array();
    $ok = true;
    $email = isset($datos['email']) ? $datos['email'] : null ;
    if ( !$email || ! mb_ereg_match(self::HTML5_EMAIL_REGEXP, $email) ) {
      $result[] = '<p class = "rojo">El email no es válido';
      $ok = false;
    }

    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password ||  mb_strlen($password) < 4 ) {
      $result[] = '<p class = "rojo">La contraseña no es válida';
      $ok = false;
    }
	
	$password2 = isset($datos['password2']) ? $datos['password2'] : null ;
    if ( ! $password2 ||  mb_strlen($password2) < 4 ) {
      $result[] = '<p class = "rojo">La contraseña no es válida';
      $ok = false;
    }
	
    if ($password != $password2 ) {
      $result[] = '<p class = "rojo">Las contraseñas no coinciden';
      $ok = false;
    }

    if ($ok){
      $user = Usuario::modificarDatos($email, $password);
      if ($user) {
		
        Aplicacion::getSingleton()->modificarMail($user);
		$result[] = '<p class = "dorado">Datos de cuenta modificados con exito';
      }else {
        $result[] = '<p class = "rojo">Error al tratar de modificar los datos';
      }
    }
    return $result;
  }
}
	
?>
