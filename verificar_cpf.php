<?php
$conn = new mysqli('db_connection.php');

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$cpf = $_POST['cpf'];
$sql = "SELECT * FROM cliente WHERE cpf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "duplicado";
} else {
    echo "disponivel";
}

$stmt->close();
$conn->close();
?>
<!--mensagem de duplicidade no cpf-->
<div id="mensagem-erro" style="color: red;">
    Este CPF já está cadastrado no sistema.
</div>