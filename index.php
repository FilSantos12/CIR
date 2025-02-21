<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    // Redireciona para a página de login se não estiver autenticado
    header('Location: login.html');
    exit();
}

// Obtém o nome do usuário da sessão
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Imposto de Renda</title>
    <!-- Link do CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Link CSS-->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar navbar-light" style="background-color: #ece072;">
        <div class="container-fluid">

            <!-- Nome do Sistema -->
            <a class="navbar-brand" href="#">Controle Imposto de Renda</a>

            <!-- Botão para navegação responsiva -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Conteúdo da barra -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto"></ul>

                <!-- Nome do usuário logado -->
                <div class="d-flex align-items-center">
                    <i class="bi bi-person-fill"></i>
                    <span class="navbar-text text-dark me-2">
                        <strong><?php echo htmlspecialchars($username); ?></strong>
                    </span>

                    <!-- Engrenagem com dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle" type="button" id="settingsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear-fill"></i> <!-- Ícone de engrenagem -->
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                            <li class="dropdown-item-text">Logout</li>
                            <li>
                                <a href="logout.php" class="btn btn-danger btn-sm w-100">Sair</a>
                            </li>
                        </ul>
                    </div>   
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        <!-- Menu Lateral -->
        <nav class="bg-dark text-light vh-100" style="width: 250px; position: sticky; top: 0; height: 100vh; overflow-y: auto;">
            <!--Botão inicio-->
            <ul class="nav flex-column">
                <li class="mt-2 nav-item">
                    <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="mostrarTelaInicio()">Início <i class="bi bi-house-door-fill"></i></button>
                </li>
            <!--Botão Dashboard-->
                <ul class="nav flex-column">
                    <li class="mt-2 nav-item">
                        <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="carregarDashboard()">Dashboard <i class="bi bi-bar-chart-fill"></i></button>
                    </li>
            <!--Clientes-->    
                <li class="mt-2 nav-item">
                    <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#submenu" aria-expanded="false" aria-controls="submenu">
                        Clientes <i class="bi bi-building-fill"></i>
                    </button>
                    <div class="collapse" id="submenu" data-bs-parent="#menuLateral">
                        <ul class="list-unstyled ps-3">
                            <li class="mt-2">
                                <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="mostrarTelaCadastroClientes()">Cadastro de Clientes <i class="bi bi-person-fill-gear"></i></button>
                            </li>
                            <li class="mt-2">
                                <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="mostrarListaClientes()">Consulta Clientes <i class="bi bi-clipboard-data-fill"></i></button>
                            </li>
                            <li class="mt-2">
                               <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="carregarControleClientes()">Controle de Clientes <i class="bi bi-kanban-fill"></i></button>
                            </li>
                        </ul>
                    </div>
                </li>
            <!--Configurações-->
                <li class="mt-2 nav-item">
                    <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#submenu1" aria-expanded="false" aria-controls="submenu1">
                        Configurações <i class="bi bi-gear-fill"></i>
                    </button>
                    <div class="collapse" id="submenu1" data-bs-parent="#menuLateral">
                        <ul class="list-unstyled ps-3">
                            <li class="mt-2">
                                <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="mostrarTelaCadastroUsuarios()">Cadastro de Usuario <i class="bi bi-person-plus-fill"></i></button>
                            </li>
                            <li class="mt-2">
                                <button class="btn btn-info text-start w-100 py-2 px-3 shadow-sm rounded-3" type="button" onclick="mostrarListaUsuarios()">Usuarios Cadastrados <i class="bi bi-people-fill"></i></button>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

        <!--********************************Conteúdo Principal *******************************************-->
        <div id="conteudoPrincipal" class="flex-grow-1 p-4">
            <h1>Sistema Controle De Imposto de Renda</h1>
            <img src="imagem/mesa.jpg" alt="Descrição da imagem" class="img-fluid" />
        </div> 
    </div>

    <!-- ************************************Link do JavaScript do Bootstrap *********************************-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- *************************************Ícones do Bootstrap **********************************************-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


</body>
<script src="script.js"></script>
<script>
    //*******************************Chama a função Dashboard para o botão****************************************************/
function carregarDashboard() {
    window.open("dashboard.html", "_blank");
}
</script>

<footer class="bg-dark text-white py-1 mt-5">
    <div class="container d-flex justify-content-between">
        <p class="mb-0 mx-auto text-center">&copy; 2025 Todos os direitos reservados. Desenvolvido por Filipe Santos / 
            <a href="https://filsantos12.github.io/MyPortifolio/index.html" class="text-white text-decoration-none">Contato</a>
        </p>
        <p class="mb-0">Versão 1.0.0</p>
    </div>
</footer>



</html>