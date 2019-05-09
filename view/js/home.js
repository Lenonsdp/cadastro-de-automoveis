$(function() {
	pagina = 0;

	vincularEventos();
	inicializarSelectsAno(['#ano_modelo', '#ano_fabricacao'], 2019, 2000);
	inicializarSelectsAno(['#filtro_ano_modelo', '#filtro_ano_fabricacao'], 2019, 2000, true);
	inicializarSelectsMarca(['#marca', '#filtro_marca']);
	$('#placa').inputmask({mask: 'AAA-9999'});
	
	var idAutomovel = obterIdUrl();

	if (idAutomovel != false) {
		editarAutomovel(idAutomovel);
		$('#salvar').val('Editar');
		$('#coluna_acoes_lista, #incluir_pedido').hide();
	} else {
		$('#box').show(); 
		listar();
	}

	$('#preco, #preco_fipe, #preco_de, #preco_ate').inputmask('currency', {'autoUnmask': true,
		'radixPoint': ',',
		'groupSeparator': '.',
		'allowMinus': false,
		'prefix': '',         
		'digits': 2,
		'digitsOptional': false,
		'rightAlign': true,
		'unmaskAsNumber': true
    });
});

function vincularEventos() {
	$('#incluir_pedido').on('click', function() {
		$('#box_incluir').each(function() {
			this.reset();
		});
		$('#box').hide();
		$('#box_incluir').show();
		$('#coluna_acoes_lista, #incluir_pedido').hide();
		$('#salvar').val('Salvar');
	});

	$('.cancelar').on('click', function() {
		$('#box').show();
		$('#box_incluir').hide();
		$('#box_imprimir').hide();
		$('#coluna_acoes_lista').show();
		$('#incluir_pedido').show();
		listar();
		window.location.hash = '';
	});

	$('#excluir').on('click', function() {
		if (confirm('Deseja realmente excluir?')) {
			var listaExcluir =  [];

			$('#tabela_automoveis tbody input:checkbox:checked').each(function() {
				listaExcluir.push($(this).parents('tr').attr('data-id'));
			});

			if (!listaExcluir.length) {
				alert('Selecione algum veículo para exclusão');
			} else {
				$.ajax({
					'type': 'POST',
					'url': 'http://localhost/cadastro_automoveis/controller/recebeDadosFormularios.php',
					'data': {
						'action': 'excluir',
						'listaExcluir': listaExcluir
					},
					'complete': function() {
						setTimeout(function() {$('#box_excluir').fadeIn();}, 1000);
						setTimeout(function() {$('#box_excluir').fadeOut();}, 3000);
						listar();
					}
				});
			}
		}
	});

	$('#imprimir').on('click', function(){
		$('#box').hide();
		$('#box_incluir').hide();
		$('#coluna_acoes_lista').hide();
		$('#incluir_pedido').hide();
		$('#box_imprimir').show();
		obterDadosAutomovel();
	});

	$('#impressao').on('click', function() {
		$('header, footer, #coluna_acoes, #box_imprimir input, #box_imprimir #impressao').hide();
		$('#lista_imprimir').css('margin-top', 0);

		setTimeout(function() {
			window.print();
		}, 1);

		setTimeout(function() {
			window.location.href = 'http://localhost/cadastro_automoveis/view/paginas/home.php';
		}, 1);
	});

	$('#salvar').on('click', function() {
		var camposObrigatorios = ['#descricao', '#placa'];
		var idAutomovel = obterIdUrl();
		$(camposObrigatorios.join(', ')).parent().removeClass('warning');

		if ($('#descricao').val().trim() != '' && $('#placa').val() != '' && $('#placa').val().indexOf('_') == -1) {
			var listaAcessorios = [];
   			
			$('input:checkbox:checked').each(function() {
				listaAcessorios.push($(this).val());
			});

			$.ajax({
				'type': 'POST',
				'url': 'http://localhost/cadastro_automoveis/controller/recebeDadosFormularios.php',
				'data': {
					'action': 'salvarAutomovel',
					'idAutomovel': idAutomovel,
					'descricao': $('#descricao').val(),
					'placa': $('#placa').val(),
					'renavam': $('#renavam').val(),
					'ano_modelo': $('#ano_modelo').val(),
					'ano_fabricacao': $('#ano_fabricacao').val(),
					'cor': $('#cor').val(),
					'km': $('#km').val(),
					'marca': $('#marca').val(),
					'preco': $('#preco').val(),
					'preco_fipe': $('#preco_fipe').val(),
					'acessorios': listaAcessorios
				},	
				'complete': function(resp) {
					$('#box').show(),
					$('#box_incluir').hide(),
					$('#coluna_acoes_lista').show(),
					$('#incluir_pedido').show(),
					setTimeout(function() {$('#box_sucesso').fadeIn();}, 1000);
					setTimeout(function() {$('#box_sucesso').fadeOut();}, 3000);
					listar();
					window.location.hash = '';
				}
			});
		} else {
			$.each(camposObrigatorios, function(i, seletor) {
				if ($(seletor).val().trim() == '') {
					$(seletor).parent().addClass('warning');
				}

				if (seletor == '#placa') {
					if ($('#placa').val().indexOf('_') > -1) {
						$(seletor).parent().addClass('warning');
					}
				}
			});
			alert('Preencha os campos obrigatórios.');
		}
	});

	$('#filtro').on('click', function() {
		$('#filtro_caixa').slideToggle(150);
	});

	$('#tabela_automoveis th input[type="checkbox"]').on('click', function() {
	 	var _this = $(this);

	 	$.each($('#tabela_automoveis tbody input[type="checkbox"]'), function() {
	        $(this).prop('checked', _this.is(':checked'));
	 	});
    });

    $('#sair').on('click', function() {
    	$.ajax({
			'type': 'POST',
			'url': 'http://localhost/cadastro_automoveis/controller/valida_login.php',
			'data': {
				'action': 'deslogar'
			},
			'complete': function() {
				window.location.href = 'http://localhost/cadastro_automoveis/view/paginas/index.php';
			}
		});
    });

    $(document).on('click', '#tabela_automoveis tbody tr td:not(:first-child)', function() {
		var idAutomovel = $(this).parent().attr('data-id');
		window.location.hash = idAutomovel; 
		$('#coluna_acoes_lista, #incluir_pedido').hide();
		editarAutomovel(idAutomovel);
		obterDadosAutomovel(idAutomovel);
		$('#salvar').val('Editar');
	});

	$('#btn_pesquisar').on('click', function() {
		listar();
	});

	$('#proximo').on('click', function() {
		if (pagina != ultimaPagina) {
			pagina++;
			listar();
		}


		return false;
	});

	$('#anterior').on('click', function() {
		if (pagina >= 1) {
			pagina--;
			listar();
		}

		return false;
	});
}

function inicializarSelectsAno(selectors, anoInicio, anoFim, selecione) {
	selecione = selecione || false;

	if (selecione) {
		$(selectors.join(', ')).append(
			$('<option>', {'text': 'Selecione'})
		);
	}

	for (var i = anoInicio; i >= anoFim; i--) {
		$(selectors.join(', ')).append(
			$('<option>', {'value': i, 'text': i})
		);
	}
}

function inicializarSelectsMarca(selectors) {
	$(selectors.join(', ')).append(
		$('<option>', {'value': '', 'text': 'Selecione'}),
		$('<option>', {'value': 'Alfa Romeu', 'text': 'Alfa Romeu'}),
		$('<option>', {'value': 'Audi', 'text': 'Audi'}),
		$('<option>', {'value': 'Bmw', 'text': 'BMW'}),
		$('<option>', {'value': 'Fiat', 'text': 'Fiat' }),
		$('<option>', {'value': 'Ford', 'text': 'Ford' }),
		$('<option>', {'value': 'Honda', 'text': 'Honda' }),
		$('<option>', {'value': 'Hyundai', 'text': 'Hyundai' }),
		$('<option>', {'value': 'Jeep', 'text': 'Jeep' }),
		$('<option>', {'value': 'Renault', 'text': 'Renault' }),
		$('<option>', {'value': 'Toyota', 'text': 'Toyota' }),
		$('<option>', {'value': 'Susuki', 'text': 'Susuki' }),
		$('<option>', {'value': 'Toyota', 'text': 'Toyota' }),
		$('<option>', {'value': 'Volkswagen', 'text': 'Volkswagen' }),
	)
}

function listar() {
	$.ajax({
		'type': 'POST',
		'url': 'http://localhost/cadastro_automoveis/controller/recebeDadosFormularios.php',
		'data': {
			'action': 'listar',
			'pesquisar': $('#pesquisar').val(),
			'ano_modelo': $('#filtro_ano_modelo').val(),
			'ano_fabricacao': $('#filtro_ano_fabricacao').val(),
			'marca': $('#filtro_marca').val(),
			'km_de': $('#km_de').val(),
			'km_ate': $('#km_ate').val(),
			'tipo_preco': $('#tipo_preco').val(),
			'preco_de': $('#preco_de').val(),
			'preco_ate': $('#preco_ate').val(),
			'pagina': pagina
		},	
		'success': function(resp) {
			var dados = $.parseJSON(resp);
			ultimaPagina = Math.ceil(dados['totalRegistros'] / dados['paginacao']) - 1;
			mostrarDadosTabela(dados['automoveis']);
			mostrarDadosTabelaImprimir(dados['automoveis']);
		}
	});
}

function mostrarDadosTabela(automoveis) {
	$('#tabela_automoveis tbody').empty();
	
	$.each(automoveis, function(key, automovel) {
		$('#tabela_automoveis tbody').append(
			$('<tr>', {'data-id': automovel.id}).append(
				$('<td>').append(
					$('<input>', {'type': 'checkbox'})
				),
				$('<td>', {'text': automovel.descricao}),
				$('<td>', {'text': automovel.placa}),
				$('<td>', {'text': automovel.marca})
			)
		);                        
	});
};

function mostrarDadosTabelaImprimir(automoveis) {
	$('#lista_imprimir tbody').empty();
	
	$.each(automoveis, function(key, automovel) {
		$('#lista_imprimir tbody').append(
			$('<tr>').append(
				$('<td>', {'text': automovel.descricao}),
				$('<td>', {'text': automovel.placa}),
				$('<td>', {'text': automovel.renavam}),
				$('<td>', {'text': automovel.ano_modelo}),
				$('<td>', {'text': automovel.ano_fabricacao}),
				$('<td>', {'text': automovel.cor}),
				$('<td>', {'text': automovel.km}),
				$('<td>', {'text': automovel.marca}),
				$('<td>', {'text': automovel.preco}),
				$('<td>', {'text': automovel.preco_fipe})
			)
		);                        
	});
};

function obterIdUrl() {
	res = 0;

	var id_pagina = window.location.href.split('#');

	if (typeof id_pagina[1] != 'undefined' && id_pagina[1] != '') {
		res = parseInt(id_pagina[1]);
	}

	return res;
}

function editarAutomovel(id) {
	$('#box').hide();
	$('#box_incluir').show();
}

function obterDadosAutomovel(idAutomovel) {
	$.ajax({
		'type': 'POST',
		'url': 'http://localhost/cadastro_automoveis/controller/recebeDadosFormularios.php',
		'data': {
			'action': 'obterAutomovel',
			'id': parseInt(idAutomovel)
		},
		success: function(automovel) {
			var dados = $.parseJSON(automovel);
			$('#descricao').val(dados[0].descricao);
			$('#placa').val(dados[0].placa);
			$('#renavam').val(dados[0].renavam);
			$('#ano_modelo').val(dados[0].ano_modelo);
			$('#ano_fabricacao').val(dados[0].ano_fabricacao);
			$('#cor').val(dados[0].cor);
			$('#km').val(dados[0].km);
			$('#marca').val(dados[0].marca);
			$('#preco').val(dados[0].preco);
			$('#preco_fipe').val(dados[0].preco_fipe);
			$('#componentes input[type="checkbox"]').prop('checked', false);
			$.each(dados[0].acessorios, function(){
				$('#componentes input[value="' + this + '"]').prop('checked', true);
			});
		}
	});
}