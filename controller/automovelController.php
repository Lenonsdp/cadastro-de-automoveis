<?php 
require '/var/www/html/cadastro_automoveis/model/automovel.class.php';
require '/var/www/html/cadastro_automoveis/model/DB.class.php';


class AutomovelController {
	private $lastId_automovel;
	private $db;

	public function __construct() {
		$this->db = new DB();
	}
	
	public function inserirAutomovel($automovel) {
	
		$lastId_automovel = $this->db->inserirDados('INSERT INTO automovel (descricao, placa, renavam, ano_modelo, ano_fabricacao, cor, km, marca, preco, preco_fipe, id_usuario) VALUES ("' . $automovel->getDescricao() . '", "' . $automovel->getPlaca() . '", "' . $automovel->getRenavam() . '", "' . $automovel->getAnoModelo() . '", "' . $automovel->getAnoFabricacao() . '", "' . $automovel->getCor() . '", "' . $automovel->getKm() . '", "' . $automovel->getMarca() . '", "' . $automovel->getPreco() . '", "' . $automovel->getPrecoFipe() . '", "' . $_SESSION['id_usuario'] . '" ) ');	

		$this->inserirAcessorios($lastId_automovel, $automovel->getAcessorios());
	}

	public function obterAutomovelPorId($id) {
		return $this->db->retornarDados('SELECT * FROM automovel WHERE id = ' . $id);
	}

	public function alterarAutomovel($automovel) {
		$this->db->atualizarDados('UPDATE automovel SET descricao = "' . $automovel->getDescricao() . '", placa =  "' . $automovel->getPlaca() . '", renavam = "' . $automovel->getRenavam() . '", ano_modelo ="' . $automovel->getAnoModelo() . '", ano_fabricacao = "' . $automovel->getAnoFabricacao() . '", cor = "' . $automovel->getCor() . '", km = "' . $automovel->getKm() . '", marca = "' . $automovel->getMarca() . '", preco = "' . $automovel->getPreco() . '", preco_fipe = "' . $automovel->getPrecoFipe() . '" WHERE id = ' . $automovel->getIdAutomovel() . ' ');

		$this->db->excluirDados('DELETE FROM veiculo_acessorio WHERE id_automovel = '. $automovel->getIdAutomovel());
		$this->inserirAcessorios($automovel->getIdAutomovel(), $automovel->getAcessorios());
	}

	public function inserirAcessorios($idAutomovel, $acessorios) {
		foreach ($acessorios as $acessorio) {
			$this->db->inserirDados('INSERT INTO veiculo_acessorio (id_automovel, id_acessorio) VALUES ("' . $idAutomovel . '","' . $acessorio[0] . '" )');
		}
	}

	public function excluirAutomovel($ids) {
		$this->db->excluirDados('DELETE FROM automovel WHERE id IN(' . implode(', ', $ids) . ')');
	}

	public function obterAutomoveis($automovel, $count, $pagina = 0) {
		$filtro = array();

		$sql = 'SELECT ' . ($count ? 'COUNT(id) as totalRegistros' : '*') . ' FROM automovel ';

		if ($automovel->getDescricao() != '') {
			$filtro[] = 'descricao LIKE "%' . $automovel->getDescricao() . '%" OR placa LIKE "%' . $automovel->getPlaca() . '%" OR renavam LIKE "%' . $automovel->getRenavam() . '%"' ;
		} 

		if ($automovel->getAnoModelo() > 2000 && $automovel->getAnoModelo() < 2020) {
			$filtro[] = 'ano_modelo LIKE "' . $automovel->getAnoModelo() . '"';
		}

		if ($automovel->getAnoFabricacao()  > 2000 && $automovel->getAnoFabricacao() < 2020) {
			$filtro[] = 'ano_fabricacao LIKE "' . $automovel->getAnoFabricacao() . '"';
		}

		if ($automovel->getMarca() != '') {
			$filtro[] = 'marca LIKE "' . $automovel->getMarca() . '"';
		}

		if ($automovel->getKmDe() != '') {
			$filtro[] = 'km  >= "' . $automovel->getKmDe() . '"';
		}

		if ($automovel->getKmAte() != '') {
			$filtro[] = 'km  <= "' . $automovel->getKmAte() . '"';
		}

		if ($automovel->getPrecoDe() != '') {
			$filtro[] = $automovel->getPreco() . ' >= ' . $automovel->getPrecoDe();
		}

		if ($automovel->getPrecoAte() != '') {
			$filtro[] = $automovel->getPreco() . ' <= ' . $automovel->getPrecoAte();
		}
		
		if (count($filtro) > 0) {
			$sql .= 'WHERE ' . implode(' AND ', $filtro);	
		}

		if (!$count) {
			$sql .= ' LIMIT ' . ($pagina * PAGINACAO) . ', ' . PAGINACAO;
		}

		return $this->db->retornarDados($sql);
	}

	public function obterAcessorios($id) {
		return $this->db->retornarDados('SELECT id_acessorio FROM veiculo_acessorio WHERE id_automovel = ' . $id);
	}
}