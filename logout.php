<?php
session_start();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header('Location: login.html');
exit();
?>