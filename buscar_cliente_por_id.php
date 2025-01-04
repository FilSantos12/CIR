<?php
header('Content-Type: application/json');

// Verifica se o parâmetro 'id' foi enviado
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID do cliente não fornecido ou inválido.']);
    exit;
}

$id = intval($_GET['id']); // Garante que o ID seja um número inteiro

// Inclui a conexão com o banco de dados
include 'db_connection.php';  // Certifique-se de que o arquivo de conexão está correto

// Verifica se a conexão foi bem-sucedida
if (!$conn) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na conexão com o banco de dados.']);
    exit;
}

try {
    // Prepara a consulta ao banco de dados com mysqli
    $query = "SELECT * FROM cliente WHERE id = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na preparação da consulta.']);
        exit;
    }

    // Vincula o parâmetro 'id' à consulta
    $stmt->bind_param("i", $id);  // 'i' significa inteiro

    // Executa a consulta
    $stmt->execute();

    // Obtém o resultado
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

    if ($cliente) {
        echo json_encode($cliente); // Retorna os dados do cliente como JSON
    } else {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Cliente não encontrado.']);
    }

    // Fecha a consulta
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao consultar o banco de dados: ' . $e->getMessage()]);
}

// Fecha a conexão
$conn->close();
?>
