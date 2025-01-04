<?php
require 'db_connection.php';

// Decodifica os dados JSON enviados pelo JavaScript
$dadosJson = file_get_contents('php://input');

// Verifica se algo foi enviado
if (!$dadosJson) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado foi recebido']);
    exit; // Para a execução do script
}

// Decodifica o JSON para um array associativo
$dados = json_decode($dadosJson, true);

// Verifica se a decodificação foi bem-sucedida
if (!is_array($dados)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos']);
    exit; // Para a execução do script
}

// Aqui você pode usar a variável $dados para processar os dados enviados
$id = $dados['id'];
$nomeCliente = $dados['nomeCliente'];
$cpf = $dados['cpf'];
$email = $dados['email'];
$telefone = $dados['telefone'];
$senhaGovBr = $dados['senhaGovBr'];
$procuracao = $dados['procuracao'];
$prioridade = $dados['prioridade'];
$ano = $dados['ano'];
$servico_solicitado = $dados['servico_solicitado'];
$dataVencimento = $dados['dataVencimento'];

// Verifica se o valor de dataVencimento é vazio ou inválido
if (empty($dataVencimento)) {
    // Definir um valor padrão ou enviar uma mensagem de erro
    echo json_encode(['sucesso' => false, 'mensagem' => 'Data de Vencimento inválida']);
    exit; // Finaliza a execução do script caso o dado seja inválido
}

// Garantir que a dataVencimento esteja no formato correto (YYYY-MM-DD)
if (DateTime::createFromFormat('Y-m-d', $dataVencimento) === false) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Formato de Data de Vencimento inválido']);
    exit;
}

// Faça as operações necessárias, como atualizar o banco de dados
$sql = "UPDATE cliente SET nomeCliente=?, cpf=?, email=?, telefone=?, senhaGovBr=?, procuracao=?, prioridade=?, ano=?, dataVencimento=?, servico_solicitado=? WHERE id=?";
$stmt = $conn->prepare($sql);

// Correção no número de placeholders e tipos:
$stmt->bind_param("sssssssissi", $nomeCliente, $cpf, $email, $telefone, $senhaGovBr, $procuracao, $prioridade, $ano, $dataVencimento, $servico_solicitado, $id);

// Executa a consulta
if ($stmt->execute()) {
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cliente atualizado com sucesso']);
} else {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao atualizar cliente: ' . $stmt->error]);
}
?>
