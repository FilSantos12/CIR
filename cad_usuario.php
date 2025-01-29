<?php
include 'db_connection.php'; // Inclui o arquivo de conexão com o banco

$modalMessage = ""; // Inicializa a mensagem do modal
$modalType = ""; // Define o tipo de mensagem do modal

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hasheia a senha para segurança

    $sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    if ($conn->query($sql) === TRUE) {
        $modalMessage = "Usuário cadastrado com sucesso!";
        $modalType = "success";
    } else {
        $modalMessage = "Erro ao cadastrar usuário: " . $conn->error;
        $modalType = "danger";
    }

    $conn->close();

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
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
        <a href="index.php" class="btn btn-primary btn-lg">Voltar à Página Principal</a>
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

//*********************************** Exibe o modal automaticamente se houver uma mensagem definida

        <?php if (!empty($modalMessage)): ?>
        var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
        responseModal.show();
        <?php endif; ?>
    </script>
</body>
</html>