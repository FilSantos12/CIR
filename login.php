<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['username']); // Remove espaços extras
    $senha = trim($_POST['password']);

    // Conexão com o banco de dados
    include 'db_connection.php'; // Certifique-se de que este arquivo define corretamente $conn

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta para verificar se o usuário existe
    $sql = "SELECT * FROM usuario WHERE nome = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Verifica a senha
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                // Login bem-sucedido
                $_SESSION['username'] = $nome;
                header('Location: index.php'); // Redireciona para o painel
                exit(); // Garante que o restante do script não será executado
            } else {
                echo "Usuário ou senha inválidos.";
            }
        } else {
            echo "Usuário não encontrado.";
        }

        $stmt->close();
    } else {
        echo "Erro na preparação da consulta.";
    }

    $conn->close();
}

// Exibir erros para depuração
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
