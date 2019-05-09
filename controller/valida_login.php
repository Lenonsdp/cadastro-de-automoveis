<?php 

require 'usuarioController.php';

if (isset($_POST['action'])) {
	$usuarioController = new UsuarioController();

	switch ($_POST['action']) {
		case 'logar':
			$usuario = new Usuario();
			$usuario->setLogin($_POST['login']);
			$usuario->setSenha($_POST['senha']);
			
			$usuarioValido = $usuarioController->validarUsuario($usuario);

			if (count($usuarioValido)) {
				$_SESSION['logado'] = true;
				$_SESSION['id_usuario'] = $usuarioValido[0]['id'];
			}

			echo $usuarioValido[0]['id'] > 0;
			break;
		case 'deslogar':
			$_SESSION['logado'] = false;
			session_destroy();
			break;
	}
}