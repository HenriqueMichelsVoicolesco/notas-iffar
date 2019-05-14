<?php 
include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$query = ('SELECT * FROM usuarios INNER JOIN atividades ON (matricula_aluno = matricula)');

try {

	if (isset($_POST['matricula_filtro'])){
		$matricula_pesquisa = $_POST['matricula_filtro'];
		
		$query .= ' WHERE matricula = ? ORDER BY data_cadastro DESC';

		$stmt = $conn->prepare($query);
		$stmt->bindParam('1', $matricula_pesquisa);
		$stmt->execute();

		$selectedRows = $stmt->rowCount();

		$result = $stmt->fetchAll();

		$response = [];

		if ($selectedRows > 0) {
			foreach ($result as $column) { 
				$data = ['id_atividade' => $column['id_atividade'],
				'matricula' => $column['matricula'],
				'nome' => $column['nome'], 
				'avaliacao' => $column['avaliacao'],
				'data_atividade' => $column['data_atividade'], 
				'nota' => $column['nota']];
				array_push($response, $data);
			}
		}

		echo json_encode($response);

	} else {

		$query .= ' ORDER BY data_cadastro DESC';

		$stmt = $conn->prepare($query);
		$stmt->execute();

		$selectedRows = $stmt->rowCount();

		$result = $stmt->fetchAll();

		$response = [];

		if ($selectedRows > 0) {
			foreach ($result as $column) { 
				$data = ['id_atividade' => $column['id_atividade'],
				'matricula' => $column['matricula'],
				'nome' => $column['nome'], 
				'avaliacao' => $column['avaliacao'],
				'data_atividade' => $column['data_atividade'], 
				'nota' => $column['nota']];
				array_push($response, $data);
			}
		}

		echo json_encode($response);
	}

} catch (Exception $erro) {
	return $erro->getMessage();
};

