<?php 
include ('conexao.php');
session_start();

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$matricula = $_SESSION['matricula'];

$query = ('SELECT matricula, nome FROM usuarios WHERE matricula != ?');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam('1', $matricula);
	$stmt->execute();

	$selectedRows = $stmt->rowCount();

	$result = $stmt->fetchAll();

	$response = [];

	if ($selectedRows > 0) {
		
		foreach ($result as $column) {
			$data = ['matricula' => $column['matricula'],
			'nome' => $column['nome']]; 
			array_push($response, $data);
		}

		echo json_encode($response);  

	}else{
		echo json_encode('');
	};

} catch (Exception $erro) {
	return $erro->getMessage();
};

