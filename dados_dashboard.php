<?php
header('Content-Type: application/json');
include 'db_connection.php'; // Arquivo que conecta ao banco

$sql = "SELECT id_cliente, tipo, conferencia, imposto_pagar,  valor_cobrado, 
                imposto_restituir, transmissao, boleto_enviado, pagamento 
        FROM processos";


$result = $conn->query($sql);

$processos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) { // Enquanto houverem resultados
        $processos[] = $row; // Adiciona o resultado ao array
    }
}

echo json_encode($processos);
$conn->close();
?>
