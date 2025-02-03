<?php
// Incluir conexão com o banco de dados
require_once 'db_connection.php';

// Receber os dados enviados pelo frontend
$dados = json_decode(file_get_contents('php://input'), true);

// Preparar a query SQL
$sql = "
    INSERT INTO processos (
        id_cliente, procuracao, data_solicitacao, tipo, documentos, conferencia,
        imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir,
        transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado,
        boleto_enviado, pagamento
    ) VALUES (
        :id_cliente, :procuracao, :data_solicitacao, :tipo, :documentos, :conferencia,
        :imposto_pagar, :doacao, :dados_doacao, :parcelamento, :imposto_restituir,
        :transmissao, :data_transmissao, :enviada_cliente, :observacoes, :valor_cobrado,
        :boleto_enviado, :pagamento
    )
";

// Preparar e executar a query
$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id_cliente' => $dados['id_cliente'],
    ':procuracao' => $dados['procuracao'],
    ':data_solicitacao' => $dados['data_solicitacao'],
    ':tipo' => $dados['tipo'],
    ':documentos' => $dados['documentos'],
    ':conferencia' => $dados['conferencia'],
    ':imposto_pagar' => $dados['imposto_pagar'],
    ':doacao' => $dados['doacao'],
    ':dados_doacao' => $dados['dados_doacao'],
    ':parcelamento' => $dados['parcelamento'],
    ':imposto_restituir' => $dados['imposto_restituir'],
    ':transmissao' => $dados['transmissao'],
    ':data_transmissao' => $dados['data_transmissao'],
    ':enviada_cliente' => $dados['enviada_cliente'],
    ':observacoes' => $dados['observacoes'],
    ':valor_cobrado' => $dados['valor_cobrado'],
    ':boleto_enviado' => $dados['boleto_enviado'],
    ':pagamento' => $dados['pagamento'],
]);

// Retornar uma resposta para o frontend
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Dados salvos com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar os dados.']);
}
?>