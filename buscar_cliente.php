<?php

// Habilitar exibição de erros (apenas para depuração, remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir conexão com o banco de dados
require_once 'db_connection.php';

// Verificar se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die(json_encode(['erro' => 'Erro na conexão com o banco de dados: ' . $conn->connect_error]));
}

// Verificar se o nome do cliente foi enviado via POST
$nomeCliente = isset($_POST['nomeCliente']) ? trim($_POST['nomeCliente']) : '';

// Array para armazenar os resultados
$resultados = [
    'clientes' => [],  // Dados para a tabela de clientes
    'processos' => []  // Dados para a tabela de processos
];

// ** Buscar Clientes **
$sqlCliente = "SELECT id AS cliente_id, nomeCliente, cpf, procuracao FROM cliente";
$params = [];
$types = "";

// Adicionar filtro de nomeCliente se informado
if (!empty($nomeCliente)) {
    $sqlCliente .= " WHERE nomeCliente LIKE ?";
    $params[] = "%$nomeCliente%";
    $types .= "s";
}

// Preparar query
$stmtCliente = $conn->prepare($sqlCliente);
if ($stmtCliente === false) {
    die(json_encode(['erro' => 'Erro ao preparar a query de clientes: ' . $conn->error]));
}

// Fazer bind dos parâmetros
if (!empty($params)) {
    $stmtCliente->bind_param($types, ...$params);
}

// Executar query
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

// Armazenar os resultados dos clientes
if ($resultCliente->num_rows > 0) {
    while ($row = $resultCliente->fetch_assoc()) {
        $resultados['clientes'][] = $row;
    }
}
$stmtCliente->close();

// ** Buscar Processos (Correção do JOIN) **
$sqlProcessos = "
    SELECT 
        p.id, 
        p.id_cliente, 
        p.data_solicitacao, 
        p.tipo, 
        p.documentos, 
        p.conferencia, 
        p.imposto_pagar, 
        p.doacao, 
        p.dados_doacao, 
        p.parcelamento, 
        p.imposto_restituir, 
        p.transmissao, 
        p.data_transmissao, 
        p.enviada_cliente, 
        p.observacoes, 
        p.valor_cobrado, 
        p.boleto_enviado, 
        p.pagamento 
    FROM processos p
    JOIN cliente c ON p.id_cliente = c.id"; // Correção do JOIN

$params = [];
$types = "";

// Adicionar filtro se necessário
if (!empty($nomeCliente)) {
    $sqlProcessos .= " WHERE c.nomeCliente LIKE ?";
    $params[] = "%$nomeCliente%";
    $types .= "s";
}

// Preparar query
$stmtProcessos = $conn->prepare($sqlProcessos);
if ($stmtProcessos === false) {
    die(json_encode(['erro' => 'Erro ao preparar a query de processos: ' . $conn->error]));
}

// Fazer bind dos parâmetros
if (!empty($params)) {
    $stmtProcessos->bind_param($types, ...$params);
}

// Executar query
$stmtProcessos->execute();
$resultProcessos = $stmtProcessos->get_result();

// Armazenar os resultados dos processos
if ($resultProcessos->num_rows > 0) {
    while ($row = $resultProcessos->fetch_assoc()) {
        $resultados['processos'][] = $row;
    }
}
$stmtProcessos->close();

// Fechar conexão com o banco de dados
$conn->close();

// Retornar os dados em formato JSON
header('Content-Type: application/json');
echo json_encode($resultados);
?>
