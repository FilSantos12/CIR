<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

// ********************************Recebe os dados do frontend ****************************************/

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = intval($data['id']); // Garante que seja um número inteiro

    if ($id > 0) { // Valida se o ID é válido
        $sql = "DELETE FROM cliente WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(["sucesso" => true]);
            } else {
                echo json_encode(["sucesso" => false, "mensagem" => "Erro ao executar a consulta: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro na preparação da consulta: " . $conn->error]);
        }
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "ID inválido."]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "ID não informado."]);
}


$conn->close();

?>
