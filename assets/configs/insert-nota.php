<?php 

include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$avaliacao_adm = $_POST['avaliacao_adm'];
$data_adm = $_POST['data_adm'];
$nota_adm = $_POST['nota_adm'];
$matricula_adm = $_POST['matricula_adm'];

$timestamp = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
$data_cadastro = $timestamp->format('Y-m-d H:i:s');

$query = ('INSERT INTO atividades (avaliacao, nota, data_atividade, matricula_aluno, data_cadastro) VALUES (?,?,?,?,?)');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(1, $avaliacao_adm);
	$stmt->bindParam(2, $nota_adm);
	$stmt->bindParam(3, $data_adm);
	$stmt->bindParam(4, $matricula_adm);
	$stmt->bindParam(5, $data_cadastro);
	$stmt->execute();

	$affectedRows = $stmt->rowCount();

	if ($affectedRows > 0) {
		echo '<script>alert("Atividade cadastrada com sucesso!"); window.location.replace("../../admin.php");</script>';
	}else{
		echo '<script>alert("Não foi possível cadastrar atividade! Tente novamente."); window.location.replace("../../admin.php");</script>';
	};

} catch (Exception $erro) {
	return $erro->getMessage();
}

