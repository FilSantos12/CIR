<?php

// Ativa o log de erros para depuração
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
ini_set('display_errors', 0); // Esconde os erros no navegador para segurança
error_reporting(E_ALL);

// Conexão com o banco de dados
include 'db_connection.php';
file_put_contents("log.txt", "JSON recebido: " . file_get_contents('php://input') . "\n", FILE_APPEND);


// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão: " . $conn->connect_error]);
    exit;
}

// Captura o JSON enviado
$input = file_get_contents('php://input');
error_log("JSON recebido: " . $input);
$data = json_decode($input, true);

// Valida JSON recebido
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

// Loop pelos dados recebidos
foreach ($data as $item) {
    
    // Valida se id_cliente foi recebido e é numérico
    if (!isset($item['id_cliente']) || empty($item['id_cliente']) || !is_numeric($item['id_cliente'])) {
        echo json_encode(["status" => "error", "message" => "ID do cliente não foi fornecido ou é inválido."]);
        exit;
    }
    
    $id_cliente = intval($item['id_cliente']); // Converte para número inteiro
    $data_solicitacao = formatarData($item['data_solicitacao'] ?? null);
    $tipo = $item['tipo'] ?? null;
    $documentos = $item['documentos'] ?? null;
    $conferencia = $item['conferencia'] ?? null;
    $imposto_pagar = converterMoeda($item['imposto_pagar'] ?? null);
    $doacao = $item['doacao'] ?? null;
    $dados_doacao = $item['dados_doacao'] ?? null;
    $parcelamento = $item['parcelamento'] ?? null;
    $imposto_restituir = converterMoeda($item['imposto_restituir'] ?? null);
    $transmissao = $item['transmissao'] ?? null;
    $data_transmissao = formatarData($item['data_transmissao'] ?? null);
    $enviada_cliente = $item['enviada_cliente'] ?? null;
    $observacoes = $item['observacoes'] ?? null;
    $valor_cobrado = converterMoeda($item['valor_cobrado'] ?? null);
    $boleto_enviado = $item['boleto_enviado'] ?? null;
    $pagamento = $item['pagamento'] ?? null;

    // Prepara a query para verificar se o ID já existe
    $checkSql = "SELECT COUNT(*) as count FROM processos WHERE id_cliente = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("i", $id_cliente);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    $checkStmt->close();

    if ($row['count'] > 0) {
        // Se já existe, faz um UPDATE
        $sql = "UPDATE processos SET 
                    data_solicitacao = ?, tipo = ?, documentos = ?, conferencia = ?, 
                    imposto_pagar = ?, doacao = ?, dados_doacao = ?, parcelamento = ?, 
                    imposto_restituir = ?, transmissao = ?, data_transmissao = ?, enviada_cliente = ?, 
                    observacoes = ?, valor_cobrado = ?, boleto_enviado = ?, pagamento = ? 
                WHERE id_cliente = ?";
    } else {
        // Se não existe, faz um INSERT
        $sql = "INSERT INTO processos (
                    id_cliente, data_solicitacao, tipo, documentos, conferencia, 
                    imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, 
                    transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, 
                    boleto_enviado, pagamento
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";
    }

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Erro ao preparar a query: " . $conn->error]);
        exit;
    }

    if ($row['count'] > 0) {
        // Bind dos parâmetros para UPDATE
        $stmt->bind_param(
            "ssssdsssdsssdsssi",
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
            $pagamento,
            $id_cliente // ID no final para WHERE
        );
    } else {
        // Bind dos parâmetros para INSERT
        $stmt->bind_param(
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
        );
    }

    // Executa a query
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Erro ao salvar o processo: " . $stmt->error]);
    } else {
        echo json_encode(["status" => "success", "message" => "Processo salvo com sucesso!"]);
    }
    

    $stmt->close();
}

// Fecha a conexão
$conn->close();
exit;

?>