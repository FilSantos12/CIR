<?php
require 'db_connection.php'; // Inclui a conexão com o banco de dados

header('Content-Type: application/json');

$sql = "SELECT * FROM historico_processos ORDER BY data_solicitacao DESC";
$result = $conn->query($sql);

$historico = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

echo json_encode(['historico' => $historico]);
$conn->close();
?>
