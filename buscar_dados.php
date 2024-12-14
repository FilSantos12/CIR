<?php
include 'db_connection.php'; // Inclui a conexão com o banco

// Consulta ao banco de dados
$sql = "SELECT id, nome, email FROM usuario";
$result = $conn->query($sql);

// Array para armazenar os resultados
$data = [];

if ($result->num_rows > 0) {
    // Percorre todas as linhas retornadas pela consulta
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Adiciona cada linha ao array $data
    }
}

// Retorna os dados como JSON para ser usado no front-end
header('Content-Type: application/json');
echo json_encode($data);

// Fecha a conexão
$conn->close();
?>
