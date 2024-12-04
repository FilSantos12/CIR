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
</head>
<body>
<div class="container mt-5">
    <form method="POST" action="index.html">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome:</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
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

     // Captura os parâmetros da URL
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');
    const message = urlParams.get('message');

    if (type && message) {
        // Define as classes e conteúdo do modal com base no tipo
        const modalHeader = document.getElementById('modal-header');
        const modalTitle = document.getElementById('responseModalLabel');
        const modalBody = document.getElementById('modal-body');

        modalHeader.className = `modal-header bg-${type} text-white`;
        modalTitle.textContent = type === 'success' ? 'Sucesso' : 'Erro';
        modalBody.textContent = decodeURIComponent(message);

        // Exibe o modal
        const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
        responseModal.show();
    }
    // Exibe o modal automaticamente se houver uma mensagem definida
    <?php if (!empty($modalMessage)): ?>
    var responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
    responseModal.show();
    <?php endif; ?>
</script>
</body>
</html>
