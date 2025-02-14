<?php


// Ativa o log de erros para depuração
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
ini_set('display_errors', 0); // Esconde os erros no navegador para segurança
error_reporting(E_ALL);

// Conexão com o banco de dados
include 'db_connection.php';// Inclui o arquivo de conexão
file_put_contents("log.txt", "JSON recebido: " . file_get_contents('php://input') . "\n", FILE_APPEND);// Salva o JSON recebido em um arquivo de log


// Verifica conexão
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Erro na conexão: " . $conn->connect_error]);// Retorna erro de conexão    
    exit;//
}

// Captura o JSON enviado
$input = file_get_contents('php://input');// Lê o JSON enviado
error_log("JSON recebido: " . $input);// Salva o JSON recebido no log de erros
$data = json_decode($input, true);// Decodifica o JSON em um array associativo

// Valida JSON recebido
if (!$data) { // Se o JSON for inválido
    echo json_encode(["status" => "error", "message" => "JSON inválido!", "recebido" => $input]); // Retorna erro de JSON inválido
    exit;
}

// Se for um único objeto, transforma em array
if (!isset($data[0])) {// Se não for um array
    $data = [$data];// Transforma em array
}

// Função para converter valores monetários para float
function converterMoeda($valor) {// Função para converter valores monetários para float
    return (float) str_replace(['R$', '.', ','], ['', '', '.'], $valor);// Remove R$, pontos e vírgulas e converte para float
}

// Função para validar e formatar datas
function formatarData($data) { // Função para validar e formatar datas
    if (empty($data)) { // Se o campo estiver vazio
        return NULL; // Retorna NULL se o campo estiver vazio
    }

    // Tenta criar um objeto DateTime a partir da string
    $d = DateTime::createFromFormat('Y-m-d', $data); // Tenta criar um objeto DateTime a partir da string
    if ($d && $d->format('Y-m-d') === $data) { // Se a data for válida
        return $data; // Retorna a data no formato Y-m-d
    }

    return NULL; // Retorna NULL se a data for inválida
}

// Loop pelos dados recebidos
foreach ($data as $item) { // Loop pelos dados recebidos
    
    // Valida se id_cliente foi recebido e é numérico
    if (!isset($item['id_cliente']) || empty($item['id_cliente']) || !is_numeric($item['id_cliente'])) { // Se o ID do cliente não foi fornecido ou é inválido
        echo json_encode(["status" => "error", "message" => "ID do cliente não foi fornecido ou é inválido."]); // Retorna erro de ID do cliente inválido
        exit;
    }
    
    $id_cliente = intval($item['id_cliente']); // Converte para número inteiro
    $data_solicitacao = formatarData($item['data_solicitacao'] ?? null); // Formata a data de solicitação
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
    $checkSql = "SELECT COUNT(*) as count FROM processos WHERE id_cliente = ?";// Query para verificar se o ID já existe
    $checkStmt = $conn->prepare($checkSql); // Prepara a query
    $checkStmt->bind_param("i", $id_cliente); // Faz o bind do parâmetro
    $checkStmt->execute(); // Executa a query
    $result = $checkStmt->get_result(); // Pega o resultado
    $row = $result->fetch_assoc(); // Pega a linha
    $checkStmt->close(); // Fecha a query

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
            "ssssdsssdsssssssi",
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