<?php
// Inclui o arquivo de conexão com o banco
require_once 'db_connection.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['tipo'] as $cliente_id => $tipo) {
        // Escapando os valores enviados pelo formulário
        $procuracao = isset($_POST['procuracao'][$cliente_id]) ? 1 : 0;
        $data_solicitacao = $conn->real_escape_string($_POST['data_solicitacao'][$cliente_id]);
        $documentos = isset($_POST['documentos'][$cliente_id]) ? 1 : 0;
        $conferencia = isset($_POST['conferencia'][$cliente_id]) ? 1 : 0;
        $imposto_a_pagar = $conn->real_escape_string($_POST['imposto_a_pagar'][$cliente_id]);
        $doacao = isset($_POST['doacao'][$cliente_id]) ? 1 : 0;
        $dados_doacao = $conn->real_escape_string($_POST['dados_doacao'][$cliente_id]);
        $parcelamento = isset($_POST['parcelamento'][$cliente_id]) ? 1 : 0;
        $imposto_a_restituir = $conn->real_escape_string($_POST['imposto_a_restituir'][$cliente_id]);
        $transmissao = isset($_POST['transmissao'][$cliente_id]) ? 1 : 0;
        $data_transmissao = $conn->real_escape_string($_POST['data_transmissao'][$cliente_id]);
        $enviada_ao_cliente = isset($_POST['enviada_ao_cliente'][$cliente_id]) ? 1 : 0;
        $observacoes = $conn->real_escape_string($_POST['observacoes'][$cliente_id]);
        $valor_cobrado = $conn->real_escape_string($_POST['valor_cobrado'][$cliente_id]);
        $boleto_enviado = isset($_POST['boleto_enviado'][$cliente_id]) ? 1 : 0;
        $pagamento = isset($_POST['pagamento'][$cliente_id]) ? 1 : 0;

        // Verifica se o registro já existe
        $check_sql = "SELECT id FROM processos WHERE id_cliente = $cliente_id";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            // Atualiza os dados
            $update_sql = "
                UPDATE processos
                SET procuracao = $procuracao, data_solicitacao = '$data_solicitacao', tipo = '$tipo', 
                    documentos = $documentos, conferencia = $conferencia, imposto_a_pagar = '$imposto_a_pagar', 
                    doacao = $doacao, dados_doacao = '$dados_doacao', parcelamento = $parcelamento, 
                    imposto_a_restituir = '$imposto_a_restituir', transmissao = $transmissao, 
                    data_transmissao = '$data_transmissao', enviada_ao_cliente = $enviada_ao_cliente, 
                    observacoes = '$observacoes', valor_cobrado = '$valor_cobrado', 
                    boleto_enviado = $boleto_enviado, pagamento = $pagamento
                WHERE id_cliente = $cliente_id
            ";
            $conn->query($update_sql);
        } else {
            // Insere novos dados
            $insert_sql = "
                INSERT INTO processos (id_cliente, procuracao, data_solicitacao, tipo, documentos, conferencia, 
                    imposto_a_pagar, doacao, dados_doacao, parcelamento, imposto_a_restituir, transmissao, 
                    data_transmissao, enviada_ao_cliente, observacoes, valor_cobrado, boleto_enviado, pagamento)
                VALUES (
                    $cliente_id, $procuracao, '$data_solicitacao', '$tipo', $documentos, $conferencia, 
                    '$imposto_a_pagar', $doacao, '$dados_doacao', $parcelamento, '$imposto_a_restituir', $transmissao, 
                    '$data_transmissao', $enviada_ao_cliente, '$observacoes', '$valor_cobrado', $boleto_enviado, $pagamento
                )
            ";
            $conn->query($insert_sql);
        }
    }
}

// Consulta SQL para buscar dados dos clientes e seus processos
$sql = "
    SELECT 
        cliente.id AS cliente_id, cliente.nomeCliente, cliente.cpf, cliente.procuracao,
        processos.procuracao AS processo_procuracao, processos.data_solicitacao, processos.tipo, 
        processos.documentos, processos.conferencia, processos.imposto_a_pagar, processos.doacao, 
        processos.dados_doacao, processos.parcelamento, processos.imposto_a_restituir, processos.transmissao, 
        processos.data_transmissao, processos.enviada_ao_cliente, processos.observacoes, 
        processos.valor_cobrado, processos.boleto_enviado, processos.pagamento
    FROM 
        cliente
    LEFT JOIN 
        processos ON cliente.id = processos.id_cliente
";

$result = $conn->query($sql);

// Exibe os dados na tabela
if ($result->num_rows > 0) {
    echo "<form method='POST' action=''>";
    echo "<table class='table table-striped table-hover'>";
    echo "<thead class='table-dark'>";
    echo "<tr><th>#</th><th>Cliente</th><th>CPF</th><th>Procuracao</th>";

    // Colunas adicionais
    echo "<th>Data Solicitação</th><th>Tipo</th><th>Documentos</th><th>Conferência</th><th>Imposto a Pagar</th>";
    echo "<th>Doação</th><th>Dados Doação</th><th>Parcelamento</th><th>Imposto a Restituir</th><th>Transmissão</th>";
    echo "<th>Data Transmissão</th><th>Enviada ao Cliente</th><th>Observações</th><th>Valor Cobrado</th>";
    echo "<th>Boleto Enviado</th><th>Pagamento</th><th>Ações</th></tr></thead><tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['cliente_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nomeCliente']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
        echo "<td><input type='checkbox' name='procuracao[" . $row['cliente_id'] . "]' " . ($row['processo_procuracao'] ? 'checked' : '') . "></td>";
        echo "<td><input type='date' name='data_solicitacao[" . $row['cliente_id'] . "]' value='" . htmlspecialchars($row['data_solicitacao']) . "'></td>";
        echo "<td><input type='text' name='tipo[" . $row['cliente_id'] . "]' value='" . htmlspecialchars($row['tipo']) . "'></td>";

        // Outras colunas e inputs aqui (baseado na lógica acima)
        echo "</tr>";
    }

    echo "</tbody></table></form>";
} else {
    echo "<p class='text-center text-muted'>Nenhum cliente encontrado.</p>";
}

$conn->close();
?>
