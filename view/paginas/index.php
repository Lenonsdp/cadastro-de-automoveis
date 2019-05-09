<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
	<?php require ('header.php') ?>
	<form>
		<fieldset id="forma_login">
			<img id="img_login" src="../img/login.png">

			<div id="input_login">
				<input id="login" class="label_login" type="text" placeholder="Login" name="login">
				<input id="senha" class="label_login" type="password" placeholder="Senha" name="senha">
				<input id="btn_login" type="button" value="Logar">
			</div>
		</fieldset>
	</form>
	<?php require ('footer.php') ?>
</body>
<script src="../js/login.js" type="text/javascript"></script>
</html>