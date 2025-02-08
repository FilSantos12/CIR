<?php
require 'db_connection.php';

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT id, id_cliente, data_solicitacao, tipo, documentos, conferencia, imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, boleto_enviado, pagamento FROM tabela";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table border="1">';
    echo '<tr><th>Cliente</th><th>Data Solicitação</th><th>Tipo</th><th>Documentos</th><th>Conferência</th><th>Imposto a Pagar</th><th>Ações</th></tr>';
    
    while ($row = $result->fetch_assoc()) {
        echo '<tr id="linha-' . $row["id"] . '">';
        echo '<td><input type="text" id="id_cliente-' . $row["id"] . '" value="' . $row["id_cliente"] . '" disabled></td>';
        echo '<td><input type="date" id="data_solicitacao-' . $row["id"] . '" value="' . $row["data_solicitacao"] . '" disabled></td>';
        echo '<td><input type="text" id="tipo-' . $row["id"] . '" value="' . $row["tipo"] . '" disabled></td>';
        echo '<td><input type="text" id="documentos-' . $row["id"] . '" value="' . $row["documentos"] . '" disabled></td>';
        echo '<td><input type="text" id="conferencia-' . $row["id"] . '" value="' . $row["conferencia"] . '" disabled></td>';
        echo '<td><input type="text" id="imposto_pagar-' . $row["id"] . '" value="' . $row["imposto_pagar"] . '" disabled></td>';
        echo '<td>
                <button onclick="editar(' . $row["id"] . ')">Editar</button>
                <button onclick="salvar(' . $row["id"] . ')">Salvar</button>
              </td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo "Nenhum registro encontrado.";
}

$conn->close();
?>
