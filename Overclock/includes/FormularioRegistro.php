<?php

namespace xampp\htdocs\Overclock;

class FormularioRegistro extends Form {

  const HTML5_EMAIL_REGEXP = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

  public function __construct() {
    parent::__construct('formRegistro');
  }

  protected function generaCamposFormulario ($datos) {
    $email = 'user@example.org';
    $password = '12345';
	$user = 'Tu nombre';
    if ($datos) {
      $email = isset($datos['email']) ? $datos['email'] : $email;
      $password = isset($datos['password']) ? $datos['password'] : $password;
	  $user = isset($datos['user']) ? $datos['user'] : $user;
    }

    $camposFormulario=<<<EOF
		<p><label>Nombre usuario: </label><input type="text" name="user" class="registro" id="user" value="$user"/></p>
		<p><label>Correo electrónico: </label><img class = "iconosFormularios" src="img/eliminar.png" id="mailIcon"><input type="text" name="email" class="registro" id="email" value="$email"/> </p>
		<p><label>Contraseña: </label> <input  type="password" name="password" class="registro" id="pass1" value="$password"/></p>
		<p><label>Repetir Contraseña: </label><img class = "iconosFormularios" src="img/eliminar.png" id="passIcon"><input type="password" name="password2" class="registro" id="pass2" value="$password"/></p>
		<button type="submit" class="regButton" id="botonRegistro" disabled="true"> Crear cuenta </button>
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
      $result[] = '<p class = "rojo">El nombre de usuario no es v�lido';
      $ok = false;
    }
    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password ||  mb_strlen($password) < 4 ) {
      $result[] = '<p class = "rojo">La contrase�aa no es v�lida';
      $ok = false;
    }

    if ($ok) {

      $user = Usuario::buscaUsuario($email);
      if ($user == false) {
		if(Aplicacion::getSingleton()->usuarioLogueado()){
			if(Aplicacion::getSingleton()->tieneRol('Administrador', 'No eres administrador', 'No tienes los permisos suficientes.')){
				echo 'Rol: '.$_SESSION['rol'];
				$registro = Usuario::registrarUsuario($email, $datos['user'], $password, 'No', 'Administrador', 0, 0);
				$result[] = 'Cuenta con priviliegios de administracion creada con exito';
			}
// 			else{
// 				$result[] = '<p class = "rojo">Ya estas registrado';
// 			}
		}
		else{
			$registro = Usuario::registrarUsuario($datos['email'], $datos['user'], $datos['password'], 'No', 'Usuario', 0, 0);
			$result[] = '<p class = "dorado">Cuenta creada con exito';
		}
      }else
        $result[] = '<p class = "rojo">El email '.$datos['email'].' ya esta en uso';
    }
    $result[] = '';
    return $result;
  }
}

?>
