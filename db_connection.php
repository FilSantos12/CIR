<?php
$servername = "127.0.0.1:3306";
$username = "root"; // usuário padrão do XAMPP
$password = "admin";     // senha padrão (geralmente vazia)
$database = 'web_service_db'; // Nome do banco de dados

// Criar conexão
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);

    
}
?>
