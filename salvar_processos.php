<?php
// Conexão com o banco de dados
include 'db_connection.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// SQL para inserir os dados
$sql = "INSERT INTO processos (
    data_solicitacao, tipo, documentos, conferencia, imposto_a_pagar, 
    doacao, dados_doacao, parcelamento, imposto_a_restituir, transmissao, 
    data_transmissao, enviada_ao_cliente, observacoes, valor_cobrado, 
    boleto_enviado, pagamento
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar a consulta
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Erro ao preparar SQL: " . $conn->error);
}


// Associar os valores
$stmt->bind_param(
    'ssiidisidissdii',
    $data_solicitacao,
    $tipo,
    $documentos,
    $conferencia,
    $imposto_a_pagar,
    $doacao,
    $dados_doacao,
    $parcelamento,
    $imposto_a_restituir,
    $transmissao,
    $data_transmissao,
    $enviada_ao_cliente,
    $observacoes,
    $valor_cobrado,
    $boleto_enviado,
    $pagamento
);

// Executar a consulta
if ($stmt->execute()) {
    echo "Dados inseridos com sucesso!";
} else {
    echo "Erro ao inserir os dados: " . $stmt->error;
}

// Fechar conexão
$stmt->close();
$conn->close();
?>
