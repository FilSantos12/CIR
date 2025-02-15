<?php
session_start();

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['username']);
    $senha = trim($_POST['password']);

    include 'db_connection.php';

    if ($conn->connect_error) {
        $response['message'] = "Conexão falhou: " . $conn->connect_error;
        echo json_encode($response);
        exit();
    }

    $sql = "SELECT * FROM usuario WHERE nome = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($senha, $user['senha'])) {
                $_SESSION['username'] = $nome;
                $response['success'] = true;
                $response['message'] = "Login bem-sucedido!";
            } else {
                $response['message'] = "Usuário ou senha inválidos.";
            }
        } else {
            $response['message'] = "Usuário não encontrado.";
        }

        $stmt->close();
    } else {
        $response['message'] = "Erro na preparação da consulta.";
    }

    $conn->close();
}

echo json_encode($response);
?>