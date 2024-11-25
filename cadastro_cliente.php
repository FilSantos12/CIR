<?php
include 'db_connection.php'; // Inclui a conexão

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome_cliente'];
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha_govbr'];
    $procuracao = $_POST['procuracao'];
    $data_vencimento = $_POST['data_vencimento'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $prioridade = $_POST['prioridade'];
    $servico = $_POST['servico_solicitado'];
    $ano = $_POST['ano'];
    $selecao = $_POST['selecao'];
    $data = $_POST['data_solicitacao'];

    $sql = "INSERT INTO clientes (nome_cliente, cpf, senha_govbr, procuracao, data_vencimento, telefone, email, prioridade, servico_solicitado, ano, selecao, data_solicitacao)
            VALUES ('$nome', '$cpf', '$senha', '$procuracao', '$data_vencimento', '$telefone', '$email', '$prioridade', '$servico', $ano, '$selecao', '$data')";

    if ($conn->query($sql) === TRUE) {
        echo "Cliente cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
