<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Arquivo que conecta ao banco

$sql = "SELECT tipo, conferencia, imposto_pagar, doacao, 
               parcelamento, imposto_restituir, transmissao, enviada_cliente, pagamento 
        FROM processos";
$result = $conn->query($sql);

$processos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $processos[] = $row;
    }
}

echo json_encode($processos);
$conn->close();
?>
