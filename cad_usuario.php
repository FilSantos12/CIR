<?php
// Inclui o arquivo de conexão com o banco de dados
include 'db_connection.php';


// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Hasheia a senha

    // Prepara o comando SQL para inserir os dados
    $sql = "INSERT INTO usuario (nome, email, senha) VALUES ('$nome', '$email', '$senha')";

    // Executa o comando SQL e verifica o resultado
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";
    } else {
        echo "<p style='color: red;'>Erro ao cadastrar o usuário: " . $conn->error . "</p>";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>

    <!-- Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Resultado do Cadastro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php 
                        if (!empty($mensagem)) {
                            echo $mensagem;
                        }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

        <!-- JavaScript do Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Verifica se a variável erro está definida e exibe o modal
        <?php if (isset($erro)) { ?>
            var myModal = new bootstrap.Modal(document.getElementById('messageModal'), {
                keyboard: false
            });
            myModal.show();
        <?php } ?>
    </script>
