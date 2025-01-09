<?php
// Inclui o arquivo de conexão com o banco
require_once 'db_connection.php';

$nomeCliente = isset($_POST['nomeCliente']) ? $_POST['nomeCliente'] : '';

// Montar a consulta
if ($nomeCliente === '') {
    $query = "SELECT * FROM clientes"; // Seleciona todos os clientes
} else {
    $query = "SELECT * FROM clientes WHERE nome LIKE '%$nomeCliente%'"; // Busca pelo nome
}

// Verifica se o nome e CPF foram enviados
$nomeCliente = isset($_POST['nomeCliente']) ? $conn->real_escape_string($_POST['nomeCliente']) : '';
$cpf = isset($_POST['cpf']) ? $conn->real_escape_string($_POST['cpf']) : '';

// Formata o CPF se ele estiver sem pontuação
if (!empty($cpf) && preg_match('/^\d{11}$/', $cpf)) {
    $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

// Consulta SQL básica
$sql = "SELECT nomeCliente, cpf FROM cliente";

// Adiciona condições ao SQL se houver filtros
$conditions = [];
if (!empty($nomeCliente)) {
    $conditions[] = "nomeCliente LIKE '%$nomeCliente%'";
}
if (!empty($cpf)) {
    $conditions[] = "cpf LIKE '%$cpf%'";
}

// Combina as condições no SQL
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" OR ", $conditions);
}

// Debug (opcional): Exibe a consulta gerada para verificação
// echo "<pre>Consulta SQL: $sql</pre>";

$result = $conn->query($sql);

// Verifica se há resultados
if ($result->num_rows > 0) {
    // Exibe os clientes em formato HTML
    while ($row = $result->fetch_assoc()) {
        echo "<div class='mb-2'>";
        echo "<strong>Nome:</strong> " . htmlspecialchars($row['nomeCliente']) . "<br>";
        echo "<strong>CPF:</strong> " . htmlspecialchars($row['cpf']) . "<br>";
        echo "<button class='btn btn-primary btn-sm'>Selecionar</button>";
        echo "</div>";
    }
} else {
    echo "Nenhum cliente encontrado.";
}

// Fecha a conexão
$conn->close();
?>
