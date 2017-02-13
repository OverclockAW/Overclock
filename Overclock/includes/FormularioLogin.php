<?php

namespace xampp\htdocs\Overclock;

class FormularioLogin extends Form {

  const HTML5_EMAIL_REGEXP = '^[a-zA-Z0-9.!#$%&\'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$';

  public function __construct() {
    parent::__construct('formLogin');
  }
  
  protected function generaCamposFormulario ($datos) {
    $email = 'user@example.org';
    $password = '12345';
    if ($datos) {
      $email = isset($datos['email']) ? $datos['email'] : $email;
      $password = isset($datos['password']) ? $datos['password'] : $password;
    }

    $camposFormulario=<<<EOF
			<p> <input type="text" name="email" class="camposPanelIzquierdo" value="$email"/></p>
			<p><input type="password" name="password" class="camposPanelIzquierdo" value="$password"/></p>
			<button type="submit" class="botonesPanelIzquierdo"> Conectarse </button>
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
      $result[] = '<p class = "rojo">El nombre de usuario no es válido';
      $ok = false;
    }

    $password = isset($datos['password']) ? $datos['password'] : null ;
    if ( ! $password ||  mb_strlen($password) < 4 ) {
      $result[] = '<p class = "rojo">La contraseña no es válida';
      $ok = false;
    }

    if ( $ok ) {
      $user = Usuario::login($email, $password);
      if ( $user ) {
        // SEGURIDAD: Forzamos que se genere una nueva cookie de sesión por si la han capturado antes de hacer login
        session_regenerate_id(true);
        Aplicacion::getSingleton()->login($user);
        $result = \xampp\htdocs\Overclock\Aplicacion::getSingleton()->resuelve('/miCuenta.php');
      }else {
        $result[] = '<p class = "rojo">Se ha producido un problema a la hora de conectarse';
      }
    }
    return $result;
  }
}
	
?>
