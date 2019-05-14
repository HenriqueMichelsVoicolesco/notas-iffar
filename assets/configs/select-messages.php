<?php 
session_start();
include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$id_atividade = $_POST['id_atividade'];
$remetente = $_SESSION['matricula'];
$destinatario = $_POST['destinatario'] ?? 2017000000; 
//Operador novo do php 7 (Null Coalesce), substitui o isset(var) ? var : 'something';

$query = ('SELECT nome, mensagem, data_mensagem FROM mensagens INNER JOIN usuarios 
	ON (id_atividade = id_atividade AND remetente = matricula) WHERE 
	id_atividade = :id AND ((remetente = :remetente AND destinatario = :destinatario) 
	OR (remetente = :destinatario AND destinatario = :remetente)) 
	ORDER BY data_mensagem ASC');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(':id', $id_atividade);
	$stmt->bindParam(':remetente', $remetente);
	$stmt->bindParam(':destinatario', $destinatario);
	$stmt->execute();
	
	$selectedRows = $stmt->rowCount();

	$result = $stmt->fetchAll();

	$response = [];

	if ($selectedRows > 0) {
		
		foreach ($result as $column) {
			$data = ['nome' => $column['nome'],
			'mensagem' => $column['mensagem'],
			'data_mensagem' => $column['data_mensagem']]; 
			array_push($response, $data);
		}

		echo json_encode($response);  

	}else{
		echo json_encode('');
	};

} catch (Exception $erro) {
	return $erro->getMessage();
};

