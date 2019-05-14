<?php
include ('../configs/conexao.php');

$conectar = new ClassConexao();
$conn = $conectar->conectaDB();

$matricula = $_POST['matricula_disponivel'];

$query = ("SELECT * FROM usuarios WHERE matricula = ?");

try {
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $matricula);
    $stmt->execute();

    $selectedRows = $stmt->rowCount();

    $result = $stmt->fetchAll();

    if ($selectedRows > 0){
        echo json_encode('Matrícula já cadastrada!');
    } else {
        echo json_encode('Matrícula disponível!');
    }
} catch (Exception $erro) {
    return $erro->getMessage();
};
