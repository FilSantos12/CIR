<?php

// Ativa o log de erros para depuração
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
ini_set('display_errors', 0); // Esconde os erros no navegador para segurança
error_reporting(E_ALL);

// Conexão com o banco de dados
include 'db_connection.php';

// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão: " . $conn->connect_error]);
    exit;
}

// Captura o JSON enviado
$input = file_get_contents('php://input');
error_log("JSON recebido: " . $input);
$data = json_decode($input, true);

// Valida JSON
if (!$data) {
    echo json_encode(["status" => "error", "message" => "JSON inválido!", "recebido" => $input]);
    exit;
}

// Se for um único objeto, transforma em array
if (!isset($data[0])) {
    $data = [$data];
}

// Função para converter valores monetários para float
function converterMoeda($valor) {
    return (float) str_replace(['R$', '.', ','], ['', '', '.'], $valor);
}

// Função para validar e formatar datas
function formatarData($data) {
    if (empty($data)) {
        return NULL; // Retorna NULL se o campo estiver vazio
    }

    // Tenta criar um objeto DateTime a partir da string
    $d = DateTime::createFromFormat('Y-m-d', $data);
    if ($d && $d->format('Y-m-d') === $data) {
        return $data; // Retorna a data no formato Y-m-d
    }

    return NULL; // Retorna NULL se a data for inválida
}

// Prepara a query SQL
$sql = "INSERT INTO processos (
            id_cliente, data_solicitacao, tipo, documentos, conferencia, 
            imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, 
            transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, 
            boleto_enviado, pagamento
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Erro ao preparar a query: " . $conn->error]);
    exit;
}

// Processa os dados e insere no banco
foreach ($data as $row) {
    // Converte valores vazios para NULL e corrige formatos
    $id_cliente = !empty($row['id_cliente']) ? (int)$row['id_cliente'] : NULL;
    $data_solicitacao = formatarData($row['data_solicitacao']); // Valida e formata a data
    $tipo = !empty($row['tipo']) ? $row['tipo'] : NULL;
    $documentos = !empty($row['documentos']) ? $row['documentos'] : NULL;
    $conferencia = !empty($row['conferencia']) ? $row['conferencia'] : NULL;
    $imposto_pagar = !empty($row['imposto_pagar']) ? converterMoeda($row['imposto_pagar']) : 0;
    $doacao = !empty($row['doacao']) ? $row['doacao'] : NULL;
    $dados_doacao = !empty($row['dados_doacao']) ? $row['dados_doacao'] : NULL;
    $parcelamento = !empty($row['parcelamento']) ? (int)$row['parcelamento'] : NULL;
    $imposto_restituir = !empty($row['imposto_restituir']) ? converterMoeda($row['imposto_restituir']) : 0;
    $transmissao = !empty($row['transmissao']) ? $row['transmissao'] : NULL;
    $data_transmissao = formatarData($row['data_transmissao']); // Valida e formata a data
    $enviada_cliente = formatarData($row['enviada_cliente']); // Valida e formata a data
    $observacoes = !empty($row['observacoes']) ? $row['observacoes'] : NULL;
    $valor_cobrado = !empty($row['valor_cobrado']) ? converterMoeda($row['valor_cobrado']) : 0;
    $boleto_enviado = !empty($row['boleto_enviado']) ? $row['boleto_enviado'] : NULL;
    $pagamento = !empty($row['pagamento']) ? $row['pagamento'] : NULL;

    // Faz o bind dos parâmetros (todos devem ser variáveis)
    if (!$stmt->bind_param(
        "issssdsssdsssdsss",
        $id_cliente,
        $data_solicitacao,
        $tipo,
        $documentos,
        $conferencia,
        $imposto_pagar,
        $doacao,
        $dados_doacao,
        $parcelamento,
        $imposto_restituir,
        $transmissao,
        $data_transmissao,
        $enviada_cliente,
        $observacoes,
        $valor_cobrado,
        $boleto_enviado,
        $pagamento
    )) {
        echo json_encode(["status" => "error", "message" => "Erro no bind_param: " . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Executa a query
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Erro ao salvar o processo: " . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }
}

// Fecha a conexão
$stmt->close();
$conn->close();

// Retorna sucesso
header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Processos salvos com sucesso!"]);
exit;

?>