<?php 
include ('conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$matricula = $_POST['matricula_login'];
$senha = $_POST['senha_login'];

$query = ('SELECT senha, usuario FROM usuarios WHERE matricula = ?');

try {
	$stmt = $conn->prepare($query);
	$stmt->bindParam(1, $matricula);
	$stmt->execute();

	$selectedRows = $stmt->rowCount();

	$result = $stmt->fetchAll();

	if ($selectedRows > 0) {

		foreach ($result as $column) {
			$senha_hashed = $column['senha'];
			$usuario = $column['usuario'];
		}
		
		if (password_verify($senha, $senha_hashed)){
			session_start();

			if ($usuario == 'admin') {
				$_SESSION['matricula'] = $matricula;
				$_SESSION['usuario'] = 'admin';

				header('Location: ../../admin.php');  
				exit();
			} else {
				$_SESSION['matricula'] = $matricula;
				$_SESSION['usuario'] = 'aluno';

				header('Location: ../../aluno.php');  
				exit();
			}

		} else {
			echo '<script>alert("Matrícula ou senha incorreta! Tente novamente."); window.location.replace("../../index.php");</script>';
		};

	}else{
		echo '<script>alert("Matrícula ou senha incorreta! Tente novamente."); window.location.replace("../../index.php");</script>';
	};

} catch (Exception $erro) {
	return $erro->getMessage();
};

