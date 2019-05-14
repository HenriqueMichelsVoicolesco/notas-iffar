<?php  
session_start();

if (isset($_SESSION['matricula']) && $_SESSION['usuario'] == 'aluno') {
	header('Location: aluno.php');
	exit();
} else if (isset($_SESSION['matricula']) && $_SESSION['usuario'] == 'admin') {
	header('Location: admin.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Bem-Vindo!</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>
<body>
	<main>
		<h1 class="">Thiago Krug</h1>
		<h2>Tópicos Especiais para Internet - PHP</h2>
		<h3>Login</h3>
		<form action="assets/configs/login.php" method="POST">
			<div>
				<label for="matricula">Matrícula:</label>
				<input type="text" id="matricula" name="matricula_login" minlength="10" maxlength="10" required>
				<br>
				<small id="matricula-help">Insira sua matrícula do curso.</small>
			</div>
			<br>
			<div>
				<label for="senha">Senha:</label>
				<input type="password" id="senha" name="senha_login" minlength="8" maxlength="20" required>
				<br>
				<small id="senha-help">Sua senha não deve ser compartilhada.</small>
			</div>
			<br>
			<button type="submit">Enviar</button>
		</form>
		
		<p>Não possui um cadastro? <a href="cadastro.php">Clique aqui</a></p>

	</main>

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script>
		$("input[name=matricula_login]").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				e.preventDefault();
				alert("Insira apenas números");
				return false;
			}
		});
	</script>
</body>
</html>