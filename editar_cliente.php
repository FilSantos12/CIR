
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connection.php';

//********************************* Recebe os dados do frontend ****************************************/

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['nomeCliente'], $data['cpf'], $data['senhaGovBr'], $data['procuracao'], $data['dataVencimento'], $data['telefone'],
$data['email'], $data['prioridade'], $data['servico_solicitado'], $data['ano'], $data['status_servico'])) {
    $id = intval($data['id']); // Garante que seja um número inteiro
    $nomeCliente = $data['nomeCliente'];
    $cpf = $data['cpf'];
    $senhaGovBr = $data['senhaGovBr'];
    $procuracao = $data['procuracao'];
    $dataVencimento = $data['dataVencimento'];
    $telefone = $data['telefone'];
    $email = $data['email'];
    $prioridade = $data['prioridade'];
    $servico_solicitado = $$data['servico_solicitado'];
    $ano = $data['ano'];
    $status_servico = $data['status_servico'];

    if ($id > 0) { // Valida se o ID é válido
        $sql = "UPDATE cliente SET nomeCliente = ?, cpf = ?, senhaGovBr = ?, procuracao = ?, dataVencimento = ?, 
        telefone = ?, email = ?, prioridade = ?, servico_solicitado = ?, ano = ?, status_servico = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssi", $nomeCliente, $cpf, $senhaGovBr, $procuracao, $dataVencimento, $telefone,
            $email, $prioridade, $servico_solicitado, $ano, $status_servico, $id);

            if ($stmt->execute()) {
                echo json_encode(["sucesso" => true]);
            } else {
                echo json_encode(["sucesso" => false, "mensagem" => "Erro ao executar a consulta: " . $stmt->error]);
            }

            $stmt->close();
        } else {
            echo json_encode(["sucesso" => false, "mensagem" => "Erro na preparação da consulta: " . $conn->error]);
        }
    } else {
        echo json_encode(["sucesso" => false, "mensagem" => "ID inválido."]);
    }
} else {
    echo json_encode(["sucesso" => false, "mensagem" => "Dados incompletos recebidos."]);
}


$conn->close();

?>