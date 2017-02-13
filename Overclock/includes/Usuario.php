<?php

namespace xampp\htdocs\Overclock;

use xampp\htdocs\Overclock\Aplicacion as App;

class Usuario implements \JsonSerializable{
	
	public function JsonSerialize(){
		$vars = get_object_vars($this);

		return $vars;
	}
		
	public static function login($email, $password) {
		$user = self::buscaUsuario($email);
		if ($user && $user->compruebaBaneado() == false) {
			if($user->compruebaPassword($password))
				return $user;
		}    
		return false;
	}
	
	public static function usuarioExiste($email){
		$user = self::buscaUsuario($email);
		if(!$user)
			return false;
		else
			return true;
	}
	public static function modificarDatos($email, $password){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$emailActual = $app->emailUsuario();
		
		//Escapamos caracteres y ciframos contraseña
		$email = $conn->real_escape_string($email);
		$pass = $conn->real_escape_string($password);
		$aux = ['cost' => 11,'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),];
		$passCifrada = password_hash($pass, PASSWORD_BCRYPT, $aux);
		
		//Si el usuario solo cambia su contraseña accede aqui
		if($emailActual == $email){
			$sql = "UPDATE usuarios SET password = '$passCifrada' WHERE email= '$email'";
			$consulta = $conn->query($sql) or die ($conn->error);
			return $aux = self::buscaUsuario($email);
		}
		//Si modifica el email primero hay que ver si nadie usa el nuevo correo
		else{
			$user = self::buscaUsuario($email);
			if($user == false){
				$sql = "UPDATE usuarios SET email = '$email' WHERE email= '$emailActual'";
				$consulta = $conn->query($sql) or die ($conn->error);
				$sql2 = "UPDATE usuarios SET password = '$passCifrada' WHERE email= '$email'";
				$consulta2 = $conn->query($sql2) or die ($conn->error);
				$aux = self::buscaUsuario($email);
				return  $aux;
			}
		}
		return false;
	}

	public static function registrarUsuario($email,$user,$pass,$baneado,$rol){
		//Sirve para cifrar la contraseña
		$aux = [
			'cost' => 11,
			'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
		];
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		
		//Escapamos los caracteres
		$email = $conn->real_escape_string($email);
		$user = $conn->real_escape_string($user);
		$pass = $conn->real_escape_string($pass);
		
		//Ciframos la contraseña
		$passCifrada = password_hash($pass, PASSWORD_BCRYPT, $aux);
		
		//Llevamosa a cabo la consulta SQL
		$query = "INSERT INTO usuarios (email,usuario,password,baneado,rol,ganadas,perdidas) VALUES ('$email','$user','$passCifrada','$baneado','$rol',0,0)";
		$rs = $conn->query($query) or die ($conn->error);
	}
	
	public static function banearUsuario($email){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$email = $conn->real_escape_string($email);
		$user = self::buscaUsuario($email);
		$aux = "Si";
		//echo 'El usuario a banear: '.var_dump($user);
		//Compruebo que el usuario existe y que no se esta baneando a si mismo
		if($user && $email != $app->emailUsuario()){
			
			if($user->baneado() == 'Si'){
				$query = "UPDATE usuarios SET baneado = 'No' WHERE email = '$email'";
				$aux =  "No";
				//echo 'El usuario estaba baneado y se le ha desbaneado';
			}
				
			else{
				$query = "UPDATE usuarios SET baneado = 'Si' WHERE email = '$email'";
				$aux = "Si";
				//echo 'El usuario no estaba baneado y se le ha baneado';
			}
			$rs = $conn->query($query);
		}
		return $aux;
	}
	
	public static function borrarUsuario($email){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$email = $conn->real_escape_string($email);
		$user = self::buscaUsuario($email);
		//Compruebo que el usuario existe y que no se esta eliminando a si mismo
		if($user && $email != $app->emailUsuario()){
			$query = "DELETE FROM usuarios WHERE email = '$email'";
			$rs = $conn->query($query);
			//echo 'He borrado el usuario  '.$user;
			return true;
		}
		return false;
	}
	
	public static function filtrarUsuarios($filtro, $busqueda){
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$busqueda = $conn->real_escape_string($busqueda);
		$filtro = $conn->real_escape_string($filtro);
		$usuarios = array();
		$query= '';
		
		if($busqueda != "")
			$query = "SELECT * FROM usuarios WHERE $filtro LIKE '%$busqueda%' ORDER BY usuarios.ganadas/usuarios.perdidas DESC";
		
		else
			$query = "SELECT * FROM usuarios ORDER BY usuarios.ganadas/usuarios.perdidas DESC";
		$rs = $conn->query($query);
		if($rs){
			while($fila = $rs->fetch_assoc()){
				$user = new Usuario($fila['email'], $fila['usuario'], $fila['password'], $fila['rol'], $fila['baneado'], $fila['ganadas'], $fila['perdidas']);
				array_push($usuarios,$user);
			}
		}

		return $usuarios;
	}
	
	public static function buscaUsuario($email) {
		$app = App::getSingleton();
		$conn = $app->conexionBd();
		$email = $conn->real_escape_string($email);
		//Escapamos los caracteres
		$query = "SELECT * FROM usuarios WHERE email='$email'";
		$rs = $conn->query($query);
		if ($rs && $rs->num_rows == 1) {
		  $fila = $rs->fetch_assoc();
		  $user = new Usuario($fila['email'], $fila['usuario'], $fila['password'], $fila['rol'], $fila['baneado'], $fila['ganadas'], $fila['perdidas']);
		  $rs->free();
		  return $user;
		}
		return false;
	}
	
	
	
	private $email;
	private $usuario;
	private $password;
	private $rol;
	private $baneado;
	private $ganadas;
	private $perdidas;
	
	private function __construct($email, $username, $password, $rol, $baneado, $ganadas, $perdidas) {
		$this->email = $email;
		$this->usuario = $username;
		$this->password = $password;
		$this->rol = $rol;
		$this->baneado = $baneado;
		$this->ganadas = $ganadas;
		$this->perdidas = $perdidas;
		$this->banedo = $perdidas;
	}
	
	
	public function email() {
		return $this->email;
	}
	
	public function usuario() {
		return $this->usuario;
	}
	
	public function rol() {
		return $this->rol;
	}
	
	public function baneado() {
		return $this->baneado;
	}
	public function ganadas() {
		return $this->ganadas;
	}
	public function perdidas() {
		return $this->perdidas;
	}
	
	public function compruebaPassword($password){
		if(password_verify($password,$this->password))
			return true;
		else
			return false;
	}
	
	public function compruebaBaneado() {
		if($this->baneado == 'Si')
			return true;
		else
			return false;
	}
}

?>
