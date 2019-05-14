<?php 

include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$delete_nota = $_GET['delete_nota'];

$query = ("DELETE FROM atividades WHERE id_atividade = ?");

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(1, $delete_nota);
	$stmt->execute();

	$affectedRows = $stmt->rowCount();

	if ($affectedRows > 0) {
		echo '<script>alert("Registro deletado com sucesso!"); window.location.replace("../../admin.php");</script>';
	}else{
		echo '<script>alert("Não foi possível deletar registro! Tente novamente."); window.location.replace("../../admin.php");</script>';
	};

} catch (Exception $erro) {
	return $erro->getMessage();
}

