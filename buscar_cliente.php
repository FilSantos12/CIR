<?php
// Incluir conexão com o banco de dados
require_once 'db_connection.php';

// Verificar se o nome do cliente foi enviado via POST
$nomeCliente = isset($_POST['nomeCliente']) ? trim($_POST['nomeCliente']) : '';

// Montar a query SQL para buscar na tabela cliente
$sql = "
    SELECT 
        id AS cliente_id, 
        nomeCliente,
        cpf, 
        procuracao
    FROM 
        cliente
";

// Adicionar condições à query, se necessário
$conditions = [];
if (!empty($nomeCliente)) {
    $conditions[] = "nomeCliente LIKE ?";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" OR ", $conditions);
}

// Preparar e executar a query usando prepared statements
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Erro ao preparar a query: " . $conn->error);
}

// Bind dos parâmetros, se houver
if (!empty($nomeCliente)) {
    $param = "%$nomeCliente%";
    $stmt->bind_param("s", $param);
}

// Executar a query
$stmt->execute();

// Obter os resultados
$result = $stmt->get_result();

// Verificar se há resultados
if ($result->num_rows > 0) {
    $clientes = [];

    // Loop para processar cada linha de resultado
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }

    // Retornar os dados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($clientes);
} else {
    // Retornar uma mensagem se nenhum cliente for encontrado
    header('Content-Type: application/json');
    echo json_encode(["mensagem" => "Nenhum cliente encontrado."]);
}

// Fechar statement e conexão
$stmt->close();
$conn->close();
?>