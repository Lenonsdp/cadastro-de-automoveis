<?php 
require "../model/usuario.class.php";
require "../model/DB.class.php";


class UsuarioController {
	
	private $db;

	public function __construct() {
		$this->db = new DB();
	}

	public function validarUsuario($usuario) {
		$hash = sha1(md5($usuario->getSenha() . LOGIN_SALT));

		$sql = 'SELECT id FROM usuario WHERE login = "' . $usuario->getLogin() . '" AND senha = "' . $hash . '"';
		
		return ($this->db->retornarDados($sql));
	}
}