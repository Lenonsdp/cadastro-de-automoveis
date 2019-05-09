<?php 
require '/var/www/html/cadastro_automoveis/config.php';

class DB {
	private $conexao;

	public function __construct() {
		$this->conexao = new mysqli(HOST, USER, PASS, BANCO);
		$this->conexao->set_charset('utf8');
	}

	public function inserirDados($insert) {
		$this->conexao->query($insert);
		return $this->conexao->insert_id;
	}

	public function retornarDados($select) {
		$res = $this->conexao->query($select);

		return $res->fetch_all(MYSQLI_ASSOC);		
	}

	public function atualizarDados($update) {
		$this->conexao->query($update);
	}

	public function excluirDados($delete) {
		$this->conexao->query($delete);
	}	
}


