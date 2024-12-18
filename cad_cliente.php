<?php
include 'db_connection.php'; // Inclui o arquivo de conexão com o banco

$modalMessage = ""; // Inicializa a mensagem do modal
$modalType = ""; // Define o tipo de mensagem do modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os valores do formulário, permitindo campos vazios
    $nomeCliente = isset($_POST['nomeCliente']) && !empty($_POST['nomeCliente']) ? $_POST['nomeCliente'] : 'Sem Nome';
    $cpf = isset($_POST['cpf']) && !empty($_POST['cpf']) ? $_POST['cpf'] : 'Não informado';
    $senhaGovBr = isset($_POST['senhaGovBr']) && !empty($_POST['senhaGovBr']) ? password_hash($_POST['senhaGovBr'], PASSWORD_DEFAULT) : null;
    $procuracao = isset($_POST['procuracao']) ? $_POST['procuracao'] : null;
    $dataVencimento = isset($_POST['dataVencimento']) ? $_POST['dataVencimento'] : null;
    $telefone = isset($_POST['telefone']) && !empty($_POST['telefone']) ? $_POST['telefone'] : 'Não informado';
    $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : null;
    $prioridade = isset($_POST['prioridade']) ? $_POST['prioridade'] : 'Baixa';
    $servico_solicitado = isset($_POST['servico_solicitado']) ? $_POST['servico_solicitado'] : null;
    $ano = isset($_POST['ano']) && !empty($_POST['ano']) ? $_POST['ano'] : date('Y');
    $status_servico = isset($_POST['status_servico']) ? $_POST['status_servico'] : 'Não';

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
    $modalMessage = "Cliente cadastrado com sucesso!";
    $modalType = "success";
} else {
    $modalMessage = "Erro ao cadastrar cliente: " . $stmt->error;
    $modalType = "danger";
}

$stmt->close();
$conn->close();

echo '<pre>';
print_r($_POST);
echo '</pre>';
exit;

}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .center-button {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ocupa toda a altura da tela */
        }
    </style>
</head>
<body>
    <div class="center-button">
        <a href="index.html" class="btn btn-primary btn-lg">Voltar à Página Principal</a>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-<?php echo $modalType; ?> text-white">
                    <h5 class="modal-title" id="responseModalLabel">
                        <?php echo $modalType === "success" ? "Sucesso" : "Erro"; ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo $modalMessage; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Exibe o modal automaticamente se houver uma mensagem definida
        <?php if (!empty($modalMessage)): ?>
        var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
        responseModal.show();
        <?php endif; ?>
    </script>
</body>
</html>


