$(function() {
	vincularEventos();
});

function vincularEventos() {
	$('#btn_login').on('click', function() {
		$.ajax({
			'type': 'POST',
			'url': 'http://localhost/cadastro_automoveis/controller/valida_login.php',
			'data': {
				'action': 'logar',
				'login': $('#login').val(),
				'senha': $('#senha').val()
			},
			'success': function(data) {
				if (data) {
					window.location.href = 'http://localhost/cadastro_automoveis/view/paginas/home.php';
				} else {
					alert('Usuário e senha inválidos.');
				}
			}
		});
	});

	$('#senha').keypress(function(event){
	    var keycode = (event.keyCode ? event.keyCode : event.which);
	    if(keycode == '13'){
	     	$.ajax({
				'type': 'POST',
				'url': 'http://localhost/cadastro_automoveis/controller/valida_login.php',
				'data': {
					'action': 'logar',
					'login': $('#login').val(),
					'senha': $('#senha').val()
				},
				'success': function(data) {
					if (data == 1) {
						window.location.href = 'http://localhost/cadastro_automoveis/view/paginas/home.php';
					} else {
						alert('Usuário e senha inválidos.');
					}
				}
			});
    	}
	});
}