<?php 

include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$matricula_cadastro = $_POST['matricula_cadastro'];
$nome_cadastro = $_POST['nome_cadastro'];
$senha_hashed = password_hash($_POST['senha_cadastro'], PASSWORD_DEFAULT);

$query = ('INSERT INTO usuarios (matricula, nome, senha) VALUES (?,?,?)');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(1, $matricula_cadastro);
	$stmt->bindParam(2, $nome_cadastro);
	$stmt->bindParam(3, $senha_hashed);
	$stmt->execute();

	$affectedRows = $stmt->rowCount();

	if ($affectedRows > 0) {
		echo '<script>alert("Cadastro efetuado com sucesso!"); window.location.replace("../../index.php");</script>';
	}else{
		echo '<script>alert("Não foi possível efetuar cadastro! Tente novamente."); window.location.replace("../../cadastro.php");</script>';
	};

} catch (Exception $erro) {
	return $erro->getMessage();
}

