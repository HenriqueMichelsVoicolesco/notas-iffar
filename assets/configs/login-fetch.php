	<?php 
	include ('conexao.php');
	session_start();

	$conectar = new ClassConexao();
	$conn = $conectar->conectaDB();

	$matricula = $_SESSION['matricula'];

	$query = ('SELECT * FROM usuarios INNER JOIN atividades ON (matricula_aluno = matricula) WHERE matricula = ? ORDER BY data_cadastro DESC');

	try {
		$stmt = $conn->prepare($query);
		$stmt->bindParam(1, $matricula);
		$stmt->execute();

		$selectedRows = $stmt->rowCount();

		$result = $stmt->fetchAll();

		$response = [];

		/*
		Verifica se foi possível resgatar dados com a query utilizada, 
		se sim chama o foreach para atribuit a variavel(vetor) o valor
		de cada coluna resgatada do banco, e então cria um vetor bidimensional
		atribuindo cada dado do primeiro vetor em outro, assim permitindo
		recuperar varias colunas de dados.
		*/
		if ($selectedRows > 0) {

			foreach ($result as $column) {
				$data = ['id_atividade' => $column['id_atividade'],
				'nome' => $column['nome'],
				'avaliacao' => $column['avaliacao'], 
				'data_atividade' => $column['data_atividade'], 
				'nota' => $column['nota']]; 
				array_push($response, $data);
			}

			echo json_encode($response);  

		/*
		Caso não tenha obtido sucesso com query anterior, então é usada
		uma nova query solicitando apenas o nome do usuário, com o mesmo
		procedimento anterior, mas com apenas 1 loop.
		*/
		}else{
			$query = ("SELECT nome FROM usuarios WHERE matricula = ?");
			
			$stmt = $conn->prepare($query);
			$stmt->bindParam(1, $matricula);
			$stmt->execute();

			$result = $stmt->fetchAll();

			$response = [];

			foreach ($result as $column) {
					$data = ['nome' => $column['nome']]; 
					array_push($response, $data);

			echo json_encode($response); 
			};
		}
	} catch (Exception $erro) {
		return $erro->getMessage();
	};

