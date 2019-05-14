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
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cadastro</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
</head>
<body>
	<main>
		<h1 class="">Cadastro</h1>
		<p>Por favor preencha todos os campos!</p>
		<form action="assets/configs/insert-user.php" method="POST">
			<div>
				<label for="nome">Nome:</label>
				<input type="text" id="nome" name="nome_cadastro" maxlength="100" required>
				<br>
				<small id="nome-help">Insira seu nome completo.</small>
			</div>
			<br>
			<div>
				<label for="matricula">Matrícula:</label>
				<input type="text" id="matricula" name="matricula_cadastro" minlength="10" maxlength="10" required>
				<br>
				<small id="matricula-help">Insira sua matrícula do curso.</small>
			</div>
			<br>
			<div>
				<label for="senha">Senha:</label>
				<input type="password" id="senha" name="senha_cadastro" minlength="8" maxlength="20" required>
				<br>
				<small id="senha-help">Sua deve ter de 8 a 10 digitos.</small>
			</div>
			<br>
			<button type="submit">Enviar</button>
			<br>
			<br>
		</form>
		<a href="index.php"><button>Cancelar</button></a>
	</main>	

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script>	

		/*
		O input number não é tão compatível ainda, então usa-se
		um tipo texto e uma formatação de apenas números para a 
		matrícula inserida, exibindo uma mensagem se algo diferente
		for inserido.
			*/
		$("input[name=matricula_cadastro]").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				e.preventDefault();
				alert("Insira apenas números");
				return false;
			}
		});

		/*
		Após digitar a matrícula no input, é feita uma pesquisa
		no banco e dados para ver se há alguma matrícula existente,
		caso houver será exibida uma mensagem que a matrícula já
		foi cadastrada, caso contrário exibirá que a matrícula está
		disponível, e caso usuário limpe o input o texto voltará a ser
		como era, pedindo para preencher o campo.
		*Essa verificação não impede que o formulário seja enviado!
		*/
		$("input[name=matricula_cadastro]").focusout(function() {
			var matricula_disponivel = $(this).val();

			if (matricula_disponivel != "") {
				$.ajax({
					url: "assets/validations/verifica_matricula.php",
					type: "POST",
					data:{matricula_disponivel : matricula_disponivel},
					dataType: "JSON",
					success: function(response) {
						$("#matricula-help").text(response);
					}
				});
			} else {
				$('#matricula-help').text("Insira sua matrícula do curso.");
			}

		});
	</script>
</body>
</html>