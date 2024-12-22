<?php
include 'db_connection.php';

$sql = "SELECT 
            id, 
            nomeCliente, 
            cpf, 
            senhaGovBr, 
            procuracao, 
            dataVencimento, 
            email, 
            telefone, 
            prioridade, 
            servico_solicitado, 
            ano, 
            status_servico 
        FROM cliente";

$result = $conn->query($sql);

if ($result === false) {
    die(json_encode(["error" => "Erro na consulta SQL: " . $conn->error]));
}

$clientes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row; // Adiciona cada linha ao array
    }
}

// Configurar o cabeçalho para JSON e retornar os dados
header('Content-Type: application/json');
echo json_encode($clientes);

$conn->close();
