<?php 
session_start();

include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$id_atividade = $_POST['id_atividade'];
$remetente = $_SESSION['matricula'];
$destinatario = $_POST['destinatario'] ?? 2017000000;
//Operador novo do php 7 (Null Coalesce), substitui o isset(var) ? var : 'something';

$mensagem = $_POST['mensagem'];
$timestamp = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
$data_mensagem = $timestamp->format('Y-m-d H:i:s');

$query = ('INSERT INTO mensagens (id_atividade, remetente, destinatario, mensagem, data_mensagem) 
	VALUES (?,?,?,?,?)');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(1, $id_atividade);
	$stmt->bindParam(2, $remetente);
	$stmt->bindParam(3, $destinatario);
	$stmt->bindParam(4, $mensagem);
	$stmt->bindParam(5, $data_mensagem);
	$stmt->execute();

	$affectedRows = $stmt->rowCount();

	if ($affectedRows == 0) {
		echo '<script>alert("Não foi possível enviar mensagem! Tente novamente."); window.location.replace("../../index.php");</script>';
	}

} catch (Exception $erro) {
	return $erro->getMessage();
}

