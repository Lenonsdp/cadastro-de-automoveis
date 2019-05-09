<?php 

require 'automovelController.php';

if (isset($_POST['action'])) {
	$automovelController = new AutomovelController();
	$automovel = new Automovel();

	switch ($_POST['action']) {
		case 'salvarAutomovel':
			$automovel->setIdAutomovel($_POST['idAutomovel']);
			$automovel->setDescricao($_POST['descricao']);
			$automovel->setPlaca($_POST['placa']);
			$automovel->setRenavam($_POST['renavam']);
			$automovel->setAnoModelo($_POST['ano_modelo']);
			$automovel->setAnoFabricacao($_POST['ano_fabricacao']);
			$automovel->setCor($_POST['cor']);
			$automovel->setKm($_POST['km']);
			$automovel->setMarca($_POST['marca']);
			$automovel->setPreco($_POST['preco']);
			$automovel->setPrecoFipe($_POST['preco_fipe']);
			$automovel->setAcessorios(isset($_POST['acessorios']) ? $_POST['acessorios'] : array());
			
			if ($_POST['idAutomovel'] == 0) {
				$automovelController->inserirAutomovel($automovel);
			} else {
				$automovelController->alterarAutomovel($automovel);
			}	
			break;
		case 'obterAutomovel':
			$automovel = $automovelController->obterAutomovelPorId($_POST['id']);
			$acessorios = array();
			$objAcessorios = $automovelController->obterAcessorios($_POST['id']);

			foreach ($objAcessorios as $key => $value) {
				$acessorios[] = $value['id_acessorio'];
			}

			$automovel[0]['acessorios'] = $acessorios;
			echo json_encode($automovel);
			break;
		case 'excluir':
			$automovelController->excluirAutomovel($_POST['listaExcluir']);
			break;
		case 'listar':
			$automovel->setDescricao($_POST['pesquisar']);
			$automovel->setPlaca($_POST['pesquisar']);
			$automovel->setRenavam($_POST['pesquisar']);
			$automovel->setAnoModelo($_POST['ano_modelo']);
			$automovel->setAnoFabricacao($_POST['ano_fabricacao']);
			$automovel->setMarca($_POST['marca']);
			$automovel->setKmDe($_POST['km_de']);
			$automovel->setKmAte($_POST['km_ate']);

			if ($_POST['tipo_preco'] == 'preco_venda') {
				$automovel->setPreco('preco');
				$automovel->setPrecoDe($_POST['preco_de']);
				$automovel->setPrecoAte($_POST['preco_ate']);
			} else {
				$automovel->setPreco('preco_fipe');
				$automovel->setPrecoDe($_POST['preco_de']);
				$automovel->setPrecoAte($_POST['preco_ate']);
			}

			$retorno['automoveis'] = $automovelController->obterAutomoveis($automovel, false, $_POST['pagina']);
			$retorno['totalRegistros'] = (int) $automovelController->obterAutomoveis($automovel, true)[0]['totalRegistros'];
			$retorno['paginacao'] = PAGINACAO;
		
			echo json_encode($retorno);
			break;
	}
}


