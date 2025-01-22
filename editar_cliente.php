<?php
require 'db_connection.php';

// Decodifica os dados JSON enviados pelo JavaScript
$dadosJson = file_get_contents('php://input');

// Verifica se algo foi enviado
if (!$dadosJson) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado foi recebido']);
    exit;
}

// Decodifica o JSON para um array associativo
$dados = json_decode($dadosJson, true);

// Verifica se a decodificação foi bem-sucedida
if (!is_array($dados)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
    exit;
}

// Valida e sanitiza os campos recebidos
$id = isset($dados['id']) ? intval($dados['id']) : null;
$nomeCliente = isset($dados['nomeCliente']) ? trim($dados['nomeCliente']) : null;
$cpf = isset($dados['cpf']) ? trim($dados['cpf']) : null;
$email = isset($dados['email']) ? filter_var($dados['email'], FILTER_SANITIZE_EMAIL) : null;
$telefone = isset($dados['telefone']) ? trim($dados['telefone']) : null;
$senhaGovBr = isset($dados['senhaGovBr']) ? trim($dados['senhaGovBr']) : null;
$procuracao = isset($dados['procuracao']) ? trim($dados['procuracao']) : null;
$prioridade = isset($dados['prioridade']) ? trim($dados['prioridade']) : null;
$ano = isset($dados['ano']) ? intval($dados['ano']) : date('Y');
$servico_solicitado = isset($dados['servico_solicitado']) ? trim($dados['servico_solicitado']) : null;
$dataVencimento = isset($dados['dataVencimento']) ? trim($dados['dataVencimento']) : null;

// Validações adicionais
if (empty($id)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'ID do cliente é obrigatório']);
    exit;
}

if (empty($nomeCliente)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nome do cliente é obrigatório']);
    exit;
}

if (!empty($cpf)) {
    // Remove a máscara do CPF (pontos e hífen)
    $cpfLimpo = preg_replace('/\D/', '', $cpf);

    // Verifica se o CPF tem exatamente 11 dígitos
    if (!preg_match('/^\d{11}$/', $cpfLimpo)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'CPF inválido. Deve conter 11 dígitos.']);
        exit;
    }

    // Aqui você pode adicionar validações adicionais se necessário
}


if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Email inválido']);
    exit;
}

// Validação de data
if (!empty($dataVencimento)) {
    $dataObj = DateTime::createFromFormat('Y-m-d', $dataVencimento);
    if (!$dataObj || $dataObj->format('Y-m-d') !== $dataVencimento) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Formato de Data de Vencimento inválido. Use YYYY-MM-DD.']);
        exit;
    }
}

// Prepara a consulta SQL para atualizar o cliente
$sql = "UPDATE cliente 
        SET nomeCliente=?, cpf=?, email=?, telefone=?, senhaGovBr=?, procuracao=?, prioridade=?, ano=?, dataVencimento=?, servico_solicitado=? 
        WHERE id=?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao preparar a consulta: ' . $conn->error]);
    exit;
}

// Vincula os parâmetros à consulta
$stmt->bind_param(
    "sssssssissi",
    $nomeCliente,
    $cpf,
    $email,
    $telefone,
    $senhaGovBr,
    $procuracao,
    $prioridade,
    $ano,
    $dataVencimento,
    $servico_solicitado,
    $id
);

// Executa a consulta e retorna a resposta
if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cliente atualizado com sucesso']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar cliente: ' . $stmt->error]);
}

// Fecha o statement e a conexão
$stmt->close();
$conn->close();
?>
