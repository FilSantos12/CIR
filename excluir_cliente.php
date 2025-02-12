<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php'; // Certifique-se de incluir a conexão

$dados = json_decode(file_get_contents("php://input"), true);

if (!$dados || !isset($dados['id'])) {
    echo json_encode(["sucesso" => false, "mensagem" => "ID do cliente não enviado!"]);
    exit;
}

$id_cliente = $dados['id'];

try {
    // Excluir primeiro os processos vinculados ao cliente
    $stmt = $conn->prepare("DELETE FROM processos WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $stmt->close();

    // Agora, excluir o cliente
    $stmt = $conn->prepare("DELETE FROM cliente WHERE id = ?");
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $stmt->close();

    echo json_encode(["sucesso" => true, "mensagem" => "Cliente excluído com sucesso!"]);
} catch (mysqli_sql_exception $e) {
    echo json_encode(["sucesso" => false, "mensagem" => "Erro ao excluir: " . $e->getMessage()]);
}
?>
