<?php
$servername = "127.0.0.1:3306";
$username = "root"; // usuário padrão do XAMPP
$password = "admin";     // senha padrão (geralmente vazia)
$dbname = "web_service_db"; // substitua pelo nome do banco

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
