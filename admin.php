<?php  
session_start();

if (!isset($_SESSION['matricula'], $_SESSION['usuario'])) {
	header('Location: index.php');
	exit();
} else if (isset($_SESSION['matricula']) && $_SESSION['usuario'] == 'aluno') {
	header('Location: aluno.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Administrador</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.modal.min.css">
</head>
<body>

	<main>
		<h1>Bem vindo Thiago Cassio Krug | <a href='assets/configs/logout.php'>Sair</a></h1>
		<h2>Inserir Notas</h2>

		<form method="POST" action="assets/configs/insert-nota.php">
			<div>
				<label for="matricula">Matrícula:</label>
				<input type="text" id="matricula" name="matricula_adm" minlength="10" maxlength="10" required>
				<br>
			</div>
			<br>
			<div>
				<label for="nome">Nome da Avaliação:</label>
				<input type="text" id="nome" name="avaliacao_adm" maxlength="500" required>
				<br>
			</div>
			<br>
			<div>
				<label for="data">Data de Ínício:</label>
				<input type="date" id="data" name="data_adm" required>
				<br>
			</div>
			<br>
			<div>
				<label for="nota">Nota:</label>
				<input type="text" id="nota" name="nota_adm" maxlength="500" required>
				<br>
			</div>
			<br>
			<button type="submit">Enviar</button>
		</form>

		<br>

		<h2>Filtrar Alunos</h2>

		<select id="select">
			<option value=" " disabled selected>Alunos - Filtro</option>
			<option value=" ">Todos</option>
		</select>

		<br><br>

		<table id="userTable" border="1">
			<thead>
				<tr>
					<th style="width: 10%">Matrícula</th>
					<th>Nome</th>
					<th>Avaliação</th>
					<th>Nota</th>
					<th style="width: 10%; text-align:center;">Data de Início</th>
					<th style="width: 5%; text-align:center;">Comentários</th>
					<th style="width: 5%; text-align:center;">Deletar</th>
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
		<form method="POST" id="form_mensagem">
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
			o filtro de usuários existentes e a tabela de notas
			*/
			fill_select();

			fill_table();

			/*
			A função recebe um parametro, o filtro de usuários,
			se for diferente de nulo pesquise o usuário pela matrícula,
			do contrário pesquise todos usuários. Se for bem sucedido
			atribui os resultado na variável e passa como parametro 
			chamando a função. Caso houver demora para enviar e receber 
			os dados, será exibida a mensagem "Carregando!" até ser 
			possível exibir os dados.
			*/
			function fill_table(matricula_filtro){
				if (matricula_filtro != " ") {
					$.ajax({
						url: "assets/configs/select-fetch.php",
						method: "POST",
						data: {matricula_filtro:matricula_filtro},
						dataType: "JSON",
						beforeSend: function(){
							$("#userTable thead").after("<tr id='carregando'><td style='text-align: center;' colspan='7'>Carregando!</td></tr>");
						},
						success:function(response){
							var response_table = response;
							display_table(response_table);
						},
						complete:function(){
							$("#carregando").remove();			
						}
					});
				} else if (matricula_filtro == " ") {
					$.ajax({
						url: "assets/configs/select-fetch.php",
						dataType: "JSON",
						beforeSend: function(){
							$("#userTable thead").after("<tr id='carregando'><td style='text-align: center;' colspan='7'>Carregando!</td></tr>");
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
			}

			/*
			A função recebe como parametro os resultados da função anterior 
			(fill_table), cria uma variavel(vetor) e itera cada posição 
			com o código html para cada grupo de dados que houver. 
			*/
			function display_table(response_table){
				for (var i in response_table) {
					var html_table = "<tr>" +
					"<td>" + response_table[i].matricula + "</td>" +
					"<td>" + response_table[i].nome + "</td>" +
					"<td>" + response_table[i].avaliacao + "</td>" +
					"<td>" + response_table[i].nota + "</td>" +
					"<td style='text-align:center;'>" + response_table[i].data_atividade + "</td>" +
					"<td style='text-align:center;'><a id='exibir' href='#mensagens' rel='modal:open' style='text-align' data-value='" + response_table[i].id_atividade + "'>Exibir</a></td>" +
					"<td style='text-align:center;'><a id='deletar' href='assets/configs/delete-nota.php?delete_nota=" + response_table[i].id_atividade + "'>Deletar</a></td>" +
					"</tr>";
					$("#userTable tbody").append(html_table);
				}
			}

			/*
			A função pesquisa todos os usuários e recebe o nome 
			e a matrícula como resposta, atribui na variável e chama
			a função, passando a variável como parametro.
			*/
			function fill_select(){
				$.ajax({
					url: "assets/configs/select-filter.php",
					dataType: "JSON",
					success:function(response){
						var response_select = response;
						display_select(response_select);
					}
				});
			}

			/*
			A função recebe como parametro os resultados da função anterior 
			(fill_select), cria uma variavel(vetor) e itera cada posição 
			com o código html para usuário que houver. 
			*/
			function display_select(response_select){
				for (var i in response_select) {
					var html_select = "<option value='" + response_select[i].matricula + "'>" + response_select[i].matricula + " - " + response_select[i].nome + "</option>";
					$("#select").append(html_select);
				}
			}

			/*
			A função recebe dois parametros, o id da atividade 
			e o destinatario da mensagem, faz a pesquisa e se 
			der certo passa os resultados para a variavel e chama 
			a função usando a variavel como parametro. Caso houver 
			demora para enviar e receber os dados, será exibida 
			a mensagem "Carregando!" até ser possível exibir os dados.
			*/
			function fill_messages(id_atividade, destinatario){
				$.ajax({
					url: "assets/configs/select-messages.php",
					method: "POST",
					data: {id_atividade:id_atividade, destinatario:destinatario},
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
			Ao modificar o input select as colunas criadas na tabela
			são removidas, é atribuído o valor do select que foi populado
			dinamicamente anteriormente a variável e chamada a função, 
			usando como parâmetro a variável que contém a matrícula. 
			*/
			$("#select").change(function(){
				$("tbody tr").remove();
				var matricula_filtro = $(this).val();

				fill_table(matricula_filtro);
			});

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
			atribuido à variável o valor do id da atividade e o 
			destinatário, ambos os valores foram gerados dinamicamente
			ao criar as linhas da tabela.
			Ao submeter o formulário a variável mensagem recebe o que 
			estiver escrito no textarea, enviando junto com o 
			id da atividade e o destinatário.
			***As variáveis estão fora do evento porque precisam ser globais.***
			*/
			var id_atividade = "";
			var destinatario = "";
			$("#userTable").on("click", "tbody td #exibir", function(){
				id_atividade = $(this).data("value");
				//O id está no data-value do link.
				destinatario = $(this).closest("tr").children("td:first").text();
				//O destinatário é o valor presente na primeira coluna.

				fill_messages(id_atividade, destinatario);

			});

			/*
			Quando o formulário for submetido cria-se uma variável
			para armazenar a mensagem do textarea, logo após a 
			função send_message é chamada com os devidos parâmetros,
			retornando falso para o formulário ser enviado sem 
			recarregar a página.
			*/
			$("#form_mensagem").submit(function(){
				var mensagem = $("textarea[name=mensagem]").val();
				send_message(id_atividade, mensagem, destinatario);
				return false;
			});

			/*
			A função recebe como parâmetro o id da atividade para
			ser perguntada e a mensagem e o destinatário, se tudo 
			der certo a mensagem é enviada e o modal fecha.
			*/
			function send_message(id_atividade, mensagem, destinatario){
				$.ajax({
					url: "assets/configs/insert-messages.php",
					method: "POST",
					data: 
					{id_atividade:id_atividade, mensagem:mensagem, destinatario:destinatario},
					success:function(){
						$.modal.close();
					}
				});
			}

			/*
			O input number não é tão compatível ainda, então usa-se
			um tipo texto e uma formatação de apenas números para a 
			matrícula inserida, exibindo uma mensagem se algo diferente
			for inserido.
				*/
			$("input[name=matricula_adm]").keypress(function (e) {
				if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					e.preventDefault();
					alert("Insira apenas números");
					return false;
				}
			});

			/*
			Ao clicar no link (a = anchor id = deletar) é exibido um 
			alert de confirmação para impedir exclusões acidentais.
			*/
			$("#userTable").on("click", "tbody td #deletar", function(){
				if (confirm("Deseja deletar esse registro?")){
					return true;
				}
				return false;
			});

		});
	</script>
</body>
</html>