<?php
include 'db_connection.php'; // Inclui o arquivo de conexão com o banco

// Habilita exibição de erros e define retorno como JSON
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os valores do formulário, permitindo campos vazios
    $nomeCliente = isset($_POST['nomeCliente']) && !empty($_POST['nomeCliente']) ? $_POST['nomeCliente'] : 'Sem Nome';
    $cpf = isset($_POST['cpf']) && !empty($_POST['cpf']) ? $_POST['cpf'] : 'Não informado';
    $senhaGovBr = isset($_POST['senhaGovBr']) && !empty($_POST['senhaGovBr']) ? $_POST['senhaGovBr'] : null;
    $procuracao = isset($_POST['procuracao']) ? $_POST['procuracao'] : null;
    $dataVencimento = isset($_POST['dataVencimento']) && !empty($_POST['dataVencimento']) ? $_POST['dataVencimento'] : null;
    $telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? $_POST['telefone'] : 'Não informado';
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null;
    $prioridade = isset($_POST['prioridade']) ? $_POST['prioridade'] : 'Baixa';
    $servico_solicitado = isset($_POST['servico_solicitado']) && !empty($_POST['servico_solicitado']) ? $_POST['servico_solicitado'] : null;
    $ano = isset($_POST['ano']) && !empty($_POST['ano']) ? $_POST['ano'] : date('Y');
    $status_servico = isset($_POST['status_servico']) ? $_POST['status_servico'] : 'Não';

    // Verifica se o CPF já está cadastrado
    $sqlCheck = "SELECT cpf FROM cliente WHERE cpf = ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param("s", $cpf);
    $stmtCheck->execute();
    $resultCheck = $stmtCheck->get_result();

    if ($resultCheck->num_rows > 0) {
        // CPF já cadastrado
        $response['message'] = "Erro: Este CPF já está cadastrado no sistema.";
    } else {
        // Verifica se o campo dataVencimento foi preenchido
        if ($dataVencimento && !strtotime($dataVencimento)) {
            $response['message'] = "Erro: Data de vencimento inválida.";
        } else {
            $dataVencimento = $dataVencimento ? date('Y-m-d', strtotime($dataVencimento)) : null;

            // Prepara a consulta SQL
            $sql = "INSERT INTO cliente (nomeCliente, cpf, senhaGovBr, procuracao, dataVencimento, telefone, email, prioridade, servico_solicitado, ano, status_servico) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssssssssis",
                $nomeCliente,
                $cpf,
                $senhaGovBr,
                $procuracao,
                $dataVencimento,
                $telefone,
                $email,
                $prioridade,
                $servico_solicitado,
                $ano,
                $status_servico
            );

            // Executa a consulta
            if ($stmt->execute()) {
                // Pega o ID do cliente recém inserido
                $cliente_id = $conn->insert_id;

                // Insere o ID na tabela processos
                $sqlProcesso = "INSERT INTO processos (id_cliente) VALUES (?)";
                $stmtProcesso = $conn->prepare($sqlProcesso);

                if (!$stmtProcesso) {
    $response['message'] = "Erro preparando SQL de processos: " . $conn->error;
    echo json_encode($response);
    exit;
}


                if ($stmtProcesso === false) {
                    $response['message'] = "Cliente cadastrado, mas erro ao preparar inserção de processo: " . $conn->error;
                } else {
                    $stmtProcesso->bind_param("i", $cliente_id);
                    if ($stmtProcesso->execute()) {
                        $response['success'] = true;
                        $response['message'] = "Cliente e processo cadastrados com sucesso!";
                    } else {
                        $response['message'] = "Cliente cadastrado, mas erro ao cadastrar processo: " . $stmtProcesso->error;
                    }
                    $stmtProcesso->close();
                }
            } else {
                $response['message'] = "Erro ao cadastrar cliente: " . $stmt->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
if (!$response['success']) {
    error_log("Resposta com erro: " . print_r($response, true));
}
//file_put_contents('erro_log.txt', print_r($response, true), FILE_APPEND);

echo json_encode($response);
?>
