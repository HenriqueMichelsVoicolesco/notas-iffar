<?php  
session_start();

if (!isset($_SESSION['matricula'], $_SESSION['usuario'])) {
	header('Location: index.php');
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
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.modal.min.css">
</head>
<body>
	<main>
		<h1></h1>
		<h2>Notas - Tópicos Especiais Para Internet (PHP)</h2>
		<h3></h3>
		<table id="userTable">
			<thead>
				<tr>
					<th>Atividade</th>
					<th>Nota</th>
					<th style="width: 10%; text-align:center;">Data de Início</th> 
					<th style="width: 5%; text-align:center;">Comentários</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table> 
	</main>	

	<div class="modal" id="mensagens">
		<h2>Comentários</h2>
		<div class="content">
			<p></p>
		</div>
		<br>
		<form method="POST">
			<label for="mensagem">
				Insira sua mensagem<br>
				<textarea id="mensagem" name="mensagem" maxlength="500" required></textarea>
			</label>
			<br>
			<br>
			<input type="submit" value="Enviar Mensagem">
		</form>
	</div>

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/jquery.modal.min.js"></script>
	<script>
		$(document).ready(function(){

		/*
		Ao carregar o documento chama-se a função para preencher
		a tabela de notas.
		*/
		fill_table();

		/*	
		A função irá pesquisar todas as notas inseridas e atribuira os 
		resultado na variável, chamando a função e passando a variável
		com os resultados como parametro.
		*/
		function fill_table(){
			$.ajax({
				url: "assets/configs/login-fetch.php",
				dataType: "JSON",
				beforeSend: function(){
					$("#userTable thead").after("<tr id='carregando'><td style='text-align: center;'' colspan='4'>Carregando!</td></tr>");
				},
				success:function(response){
					var response_table = response;
					display_table(response_table);
				},
				complete:function(){
					$("#carregando").remove();			
				}
			});
		}

		/*
		A função recebe como parametro os resultados da função anterior 
		(fill_table), logo no ínicio altera o título da página e cria uma
		mensagem (h1) de boas vindas com o nome do usuário e a opção de deslogar.
		Em seguida é verificado se no primeiro objeto do vetor tem mais de 1
		chave, se houver, cria-se uma variavel(vetor) e itera cada posição 
		com o código html para cada grupo de dados que houver e exibe na tabela, 
		caso seja menor ou igual a 1 cria-se uma variavel(vetor) e atribui uma 
		coluna com a mensagem de que não há nenhuma nota inserida e exibe na tabela. 
		*/
		function display_table(response_table){
			document.title = response_table[0].nome;
			$("h1").html("Bem Vindo " + response_table[0].nome + " | <a href='assets/configs/logout.php'>Sair</a>");
			if (Object.keys(response_table[0]).length > 1) {
				for (var i in response_table) {
					var html_table = "<tr>" +
					"<td>" + response_table[i].avaliacao + "</td>" +
					"<td>" + response_table[i].nota + "</td>" +
					"<td style='text-align:center;'>" + response_table[i].data_atividade + "</td>" +
					"<td style='text-align:center;'><a id='exibir' href='#mensagens' rel='modal:open' data-value='" + response_table[i].id_atividade + "'>Exibir</a></td>" +
					"</tr>";
					$("#userTable tbody").append(html_table);
				}
			} else {
				var html_table = "<tr>" +
				"<td colspan='4' style='text-align:center;'>Nenhuma nota inserida!</td>" +
				"</tr>";
				$("#userTable tbody").append(html_table);
			}
		}

		/*
		A função recebe um parametro, o id da atividade,
		faz a pesquisa e se der certo passa os resultados 
		para a variavel e chama a função usando a variavel 
		como parametro. Caso houver demora para enviar e 
		receber os dados, será exibida a mensagem "Carregando!" 
		até ser possível exibir os dados.
		*/
		function fill_messages(id_atividade){
			$.ajax({
				url: "assets/configs/select-messages.php",
				method: "POST",
				data: {id_atividade: id_atividade},
				dataType: "JSON",
				beforeSend: function(){
					$("#mensagens div").append("<p id='carregando' style='text-align: center;'>Carregando!</p>");
				},
				success:function(response){
					var response_messages = response;
					display_messages(response_messages);
				},
				complete:function(){
					$("#carregando").remove();			
				}
			});
		}

		/*
		A função recebe como parametro os resultados da função anterior 
		(fill_messages), cria uma variavel(vetor) e itera cada posição 
		com o código html para cada mensagem que houver. 
		*/
		function display_messages(response_messages){
			if (response_messages.length == "") {
				$("#mensagens div").append("<p>Não há nada por aqui c:</p>");
			} else {
				for (var i in response_messages) {
					var html_messages = "<h3>" + response_messages[i].nome + "</h3>" +
					"<p>" + response_messages[i].mensagem + "</p>" + 
					"<small>" + response_messages[i].data_mensagem + "</small><hr>";
					$("#mensagens div").append(html_messages).scrollTop($("#mensagens div")[0].scrollHeight);
				}
			}
		}

		/*
		Após fechar o modal de mensagens, todas as mensagens
		são apagadas para não manter as que foram iteradas 
		anteriormente e o textarea é limpo.
		*/
		$("#mensagens").on($.modal.AFTER_CLOSE, function(){
			$("#mensagens h3, #mensagens p, #mensagens small, #mensagens hr").remove();
			$("textarea").val('');
		});

		/*
		Ao clicar no link (a = anchor id = exibir) que abre as mensagens é 
		atribuido à variável o valor do id da atividade que foi 
		gerada dinamicamente ao criar as linhas da tabela.
		Ao submeter o formulário a variável mensagem recebe o que 
		estiver escrito no textarea, enviando junto com o 
		id da atividade para um destinatário já definido no php.
		***A variável está fora do evento porque precisa ser global.***
		*/
		var id_atividade = "";
		$("#userTable").on("click", "tbody td #exibir", function(){
			id_atividade = $(this).data("value");

			fill_messages(id_atividade);
		});

		/*
		Quando o formulário for submetido cria-se uma variável
		para armazenar a mensagem do textarea, logo após a 
		função send_message é chamada com os devidos parâmetros,
		retornando falso para o formulário ser enviado sem 
		recarregar a página.
		*/
		$("form").submit(function(){
			var mensagem = $("textarea[name=mensagem]").val();
			send_message(id_atividade, mensagem);
			return false;
		});

		/*
		A função recebe como parâmetro o id da atividade para
		ser perguntada e a mensagem, se tudo der certo a mensagem
		é enviada e o modal fecha.
		*/
		function send_message(id_atividade, mensagem){
			$.ajax({
				url: 'assets/configs/insert-messages.php',
				method: "POST",
				data: {id_atividade: id_atividade, mensagem: mensagem},
				success:function(response){
					$.modal.close();
				}
			});
		}
		
	});
</script>
</body>
</html>