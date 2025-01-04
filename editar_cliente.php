<?php
require 'db_connection.php';

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$nome = $data['nomeCliente'];
$cpf = $data['cpf'];
$email = $data['email'];
$telefone = $data['telefone'];
$senhaGovBr = $data['senhaGovBr'];
$procuracao = $data['procuracao'];
$dataVencimento = $data['dataVencimento'];
$prioridade = $data['prioridade'];
$ano = $data['ano'];
$servico_solicitado = $data['servico_solicitado'];

$sql = "UPDATE cliente SET nomeCliente = ?, cpf = ?, email = ?, telefone = ?, senhaGovBr = ?, procuracao = ?, prioridade = ?, ano = ?, servico_solicitado = ?, dataVencimento = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssssi', $nome, $cpf, $email, $telefone, $senhaGovBr, $procuracao, $prioridade, $ano, $servico_solicitado, $dataVencimento, $id);

if ($stmt->execute()) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar cliente.']);
}
?>
