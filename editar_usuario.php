
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

//********************************* Recebe os dados do frontend ****************************************/

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['nome'], $data['email'])) {
    $id = intval($data['id']); // Garante que seja um número inteiro
    $nome = $data['nome'];
    $email = $data['email'];
    

    if ($id > 0) { // Valida se o ID é válido
        $sql = "UPDATE usuario SET nome = ?, email = ?, WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $nome, $email, $id);

            if ($stmt->execute()) { // Executa a consulta
                echo json_encode(["sucesso" => true]);// Resposta de sucesso
            } else {
                echo json_encode(["sucesso" => false, "mensagem" => "Erro ao executar a consulta: " . $stmt->error]);// Resposta de erro
            }

            $stmt->close();
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro na preparação da consulta: " . $conn->error]);
        }
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "ID inválido."]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados incompletos recebidos."]);
}


$conn->close();

?>