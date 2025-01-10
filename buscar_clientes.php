<?php
// Inclui o arquivo de conexão com o banco
require_once 'db_connection.php';

// Recebe os dados do formulário
$nomeCliente = isset($_POST['nomeCliente']) ? $conn->real_escape_string($_POST['nomeCliente']) : '';
$cpf = isset($_POST['cpf']) ? $conn->real_escape_string($_POST['cpf']) : '';

// Formata o CPF se ele estiver sem pontuação
if (!empty($cpf) && preg_match('/^\d{11}$/', $cpf)) {
    $cpf = substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
}

// Consulta SQL básica
$sql = "SELECT id, nomeCliente, cpf, telefone, email FROM cliente";

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

// Executa a consulta
$result = $conn->query($sql);

// Verifica se há resultados
if ($result->num_rows > 0) {
    // Exibe os resultados em uma tabela
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-dark'>";
    echo "<tr>";
    echo "<th>#</th>";
    echo "<th>Nome</th>";
    echo "<th>CPF</th>";
    echo "<th>Telefone</th>";
    echo "<th>Email</th>";
    echo "<th>Ações</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nomeCliente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
        echo "<td>" . htmlspecialchars($row['telefone']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>";
        echo "<button class='btn btn-primary btn-sm me-2' onclick=\"window.location.href='dashboard.html?id=" . htmlspecialchars($row['id']) . "'\">Selecionar</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    // Exibe uma mensagem caso nenhum cliente seja encontrado
    echo "<p class='text-center text-muted'>Nenhum cliente encontrado.</p>";
}

// Fecha a conexão
$conn->close();
?>
