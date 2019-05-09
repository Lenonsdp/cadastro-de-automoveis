<?php 
require '/var/www/html/cadastro_automoveis/config.php';


if (!$_SESSION['logado']) {
	header('location:index.php');
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
	<?php require ('header.php') ?>

	<form id="box_incluir" style="display: none;">
		<h1>Dados Cadastrais</h1>
		<hr>
		<div id="fileira1">
			<div>
				<label class="obrigatorio" for="descricao">Descrição</label>
				<input id="descricao" type="text" name="descricao" />
			</div>
			<div>
				<label class="obrigatorio" for="placa">Placa</label>
				<input id="placa" type="text" name="placa""/>
			</div>
			<div>
				<label for="renavam">Codigo RENAVAM</label>
				<input id="renavam" type="text" name="renavam" maxlength="11" />
			</div>
		</div>

		<div id="fileira2">
			<div>
				<label>Ano modelo</label>
				<select id="ano_modelo"></select>
			</div>
			<div>
				<label>Ano fabricação</label>
				<select id="ano_fabricacao"></select>
			</div>
			<div>
				<label for="cor">Cor</label>
				<input id="cor" type="text" name="cor"/>
			</div>
			<div>
				<label for="km">Km</label>
				<input id="km" type="text" name="km" maxlength="6" />
			</div>
			<div>
				<label>Marca</label>
				<select id="marca"></select>
			</div>
		</div>

		<div id="fileira3">
			<div>
				<label for="preco">Preço</label>
				<input id="preco" type="text" name="preco"/>
			</div>
			<div>
				<label for="preco_fipe">Preço fipe</label>
				<input id="preco_fipe" type="text" name="preco_fipe"/>
			</div>
		</div>

		<div id="componentes">
			<h1>Componentes Adicionais</h1>
			<hr>
			<input id="ar-condicionado" type="checkbox" value=1>
			<label for="ar-condicionado">Ar-condicionado</label>

			<input id="air_bag" type="checkbox" value =2>
			<label for="air_bag">Air bag</label>

			<input id="cd_player" type="checkbox" value =3>
			<label for="cd_player">Cd player</label>

			<input id="direcao_hidraulica" type="checkbox" value=4>
			<label for="direcao_hidraulica">Direção hidráulica</label>

			<input id="vidro_eletrico" type="checkbox" value=5>
			<label for="vidro_eletrico">Vidro elétrico</label>

			<input id="trava_eletrica" type="checkbox" value=6>
			<label for="trava_eletrica">Trava elétrica</label>

			<input id="cambio_automatico" type="checkbox" value=7>
			<label for="cambio_automatico">Câmbio automático</label>

			<input id="rodas_de_liga" type="checkbox" value=8>
			<label for="rodas_liga">Rodas de liga</label>

			<input id="alarme" type="checkbox" value=9>
			<label for="alarme">Alarme</label>
		</div>

		<div>
			<input id="salvar" type="button" name="salvar" value="Salvar">
			<input class="cancelar" type="button" name="cancelar" value="Cancelar">
		</div>
	</form>

	<div id="box" style="display: none;">
		<h1>Automóveis</h1>
		<hr>
		<div class="pesquisa">
			<input id="pesquisar" type="text" name="pesquisar" placeholder="Pesquisar"/>
			<button id="btn_pesquisar" name="btn_pesquisar"><i class="fas fa-search"></i></button>
			<input type="button" id="filtro" name="filtro" value="FILTROS">
		</div>
		
		<form id="filtro_caixa" style="display: none;">
			<div class="div-filtro">
				<label for="filtro_ano_modelo">Ano modelo</label>
				<select id="filtro_ano_modelo"></select>

				<label for="filtro_ano_fabricacao">Ano Fabricação</label>
				<select id="filtro_ano_fabricacao"></select>
			</div>
			<div class="div-filtro">
				<label for="filtro_marca">Marca</label>
				<select id="filtro_marca"></select>

				<label for="km_de">Qtd/Km de</label>
				<input type="text" name="km_de" id="km_de" maxlength="6">

				<label for="km_ate">Até</label>
				<input type="text" name="km_ate" id="km_ate" maxlength="6">
			</div>
			<div class="div-filtro">
				<label for="tipo_preco">Preço</label>
				<select id="tipo_preco">
					<option value="preco_venda">Preço venda</option>
					<option value="preco_fipe">Preço fipe</option>
				</select>

				<label for="preco_de">De</label>
				<input type="text" name="preco_de" id="preco_de">

				<label for="preco_ate"> Até</label>
				<input type="text" name="preco_ate" id="preco_ate">
			</div>
		</form>

		<table id="tabela_automoveis">
			<thead>
				<tr>
					<th style="width: 20px; margin-left: 8px;"><input type="checkbox" name=""></th>
					<th>Descrição</th>
					<th>Placa</th>
					<th>Marca</th>
				</tr>
			</thead>

			<tbody></tbody>
		</table>
		<div id="paginacao">
			<a class="block_anterior" id="anterior" href=""><label for="Anterior"><i class="fas fa-angle-left"></i>Anterior</label></a>
			<a id="proximo" href=""><label for="Proximo">Proximo<i class="fas fa-angle-right"></i></label></a>
		</div>
	</div>

	<div id="box_imprimir" style="display: none;">
		<table id="lista_imprimir">
			<thead>
				<tr>
					<th>Descrição</th>
					<th>Placa</th>
					<th>Renavam</th>
					<th>Ano Modelo</th>
					<th>Ano Fabricação</th>
					<th>Cor</th>
					<th>Km</th>
					<th>MArca</th>
					<th>Preço</th>
					<th>Preço Fipe</th>
				</tr>
			</thead>
				
			<tbody></tbody>
		</table>
		
		<input class="cancelar" type="button" name="cancelar" value="Cancelar">
		<nav id="impressao"><i class="fas fa-print"></i></nav>
	</div>	

	<div id="box_sucesso" style="display: none;">
		<span>Automóvel salvo com sucesso</span>
	</div>

	<div id="box_excluir" style="display: none;">
		<span>Exclusão efetuada com sucesso</span>
	</div>

	<div id="coluna_acoes" >
		<input id="incluir_pedido" type="button" name="incluir" value="Incluir"> </input>
		<div id="coluna_acoes_lista">
			<nav>
				<ul><i class="fas fa-print"></i><a href="#" class="acao" id="imprimir">Imprimir</a></ul>
				<ul><i class="fas fa-trash-alt"></i><a href="#" class="acao"  id="excluir">Excluir</a></ul>
			</nav>
			</div>
		</div>	
	</div>
	<?php require ('footer.php') ?>
</body>
<script src="../js/home.js" type="text/javascript"></script>
</html>