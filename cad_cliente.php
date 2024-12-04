<?php
include 'db_connection.php'; // Inclua o arquivo de conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os valores do formulário
    $nome_cliente = $_POST['nome_cliente'] ?? null;
    $cpf = $_POST['cpf'] ?? null;
    $senha_govbr = password_hash($_POST['senha_govbr'] ?? '', PASSWORD_DEFAULT); // Hash da senha
    $procuracao = $_POST['procuracao'] ?? null;
    $data_vencimento = $_POST['data_vencimento'] ?? null;
    $telefone = $_POST['telefone'] ?? null;
    $email = $_POST['email'] ?? null;
    $prioridade = $_POST['prioridade'] ?? null;
    $servico_solicitado = $_POST['servico_solicitado'] ?? null;
    $ano = $_POST['ano'] ?? null;
    $selecao = $_POST['selecao'] ?? null;
    $data_solicitacao = $_POST['data_solicitacao'] ?? null;

    // Valida os campos obrigatórios
    if (!$nome_cliente || !$cpf || !$senha_govbr || !$procuracao || !$prioridade) {
        echo "Por favor, preencha todos os campos obrigatórios.";
        exit;
    }

    // Prepara a consulta SQL
    $sql = "INSERT INTO cliente (nome, cpf, senha_gov, procuracao, data_vencimento, telefone, email, prioridade, servico_solicitado, ano, status_servico, data_solicitacao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssisis",
        $nome_cliente,
        $cpf,
        $senha_govbr,
        $procuracao,
        $data_vencimento,
        $telefone,
        $email,
        $prioridade,
        $servico_solicitado,
        $ano,
        $selecao,
        $data_solicitacao
    );

    // Executa a consulta e verifica erros
    if ($stmt->execute()) {
        echo "Cliente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar cliente: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
