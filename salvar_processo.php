<?php

// Ativa exibição de erros para depuração
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log'); // Salva os erros no arquivo 'php_errors.log'
ini_set('display_errors', 0); // Não exibe erros na saída HTML para evitar problemas no JSON
error_reporting(E_ALL);

// Configurações do banco de dados
include 'db_connection.php'; // Inclui o arquivo de conexão com o banco

// Verifica a conexão
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Conexão falhou: " . $conn->connect_error]);
    exit;
}

// Captura os dados JSON enviados pelo front-end
$input = file_get_contents('php://input');
error_log("JSON recebido: " . $input);
$data = json_decode($input, true);

// Verifica se o JSON é válido
if (!$data) {
    echo json_encode(["status" => "error", "message" => "JSON inválido!", "recebido" => $input]);
    exit;
}

// Se for um único objeto, transforma em array
if (!isset($data[0])) {
    $data = [$data];
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

// Itera sobre os dados e insere no banco
foreach ($data as $row) {
    // Converte valores monetários para float
    $imposto_pagar = (float) str_replace(['R$', '.', ','], ['', '', '.'], $row['imposto_pagar']);
    $imposto_restituir = (float) str_replace(['R$', '.', ','], ['', '', '.'], $row['imposto_restituir']);
    $valor_cobrado = (float) str_replace(['R$', '.', ','], ['', '', '.'], $row['valor_cobrado']);

    // Faz o bind dos parâmetros
    $stmt->bind_param(
        "issssdsssdsssdsss",
        $row['id_cliente'],
        $row['data_solicitacao'],
        $row['tipo'],
        $row['documentos'],
        $row['conferencia'],
        $imposto_pagar,
        $row['doacao'],
        $row['dados_doacao'],
        $row['parcelamento'],
        $imposto_restituir,
        $row['transmissao'],
        $row['data_transmissao'],
        $row['enviada_cliente'],
        $row['observacoes'],
        $valor_cobrado,
        $row['boleto_enviado'],
        $row['pagamento']
    );

    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Erro ao salvar o processo: " . $stmt->error]);
        exit;
    }
}

// Fecha a conexão
$stmt->close();
$conn->close();

// Envia resposta JSON corretamente
header('Content-Type: application/json');
echo json_encode(["status" => "success", "message" => "Processos salvos com sucesso!"]);
exit;


?>
