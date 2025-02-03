function iniciarControleClientes() {
    const conteudoPrincipal = document.getElementById("conteudoPrincipal");
    conteudoPrincipal.innerHTML = `
        <h2>Controle de Clientes</h2>
        <form id="formBusca" class="mb-4">
            <div class="input-group">
                <input type="text" id="nomeCliente" class="form-control" placeholder="Digite o nome do cliente" />
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        <div id="resultadoBusca">
            <!-- Resultados serão exibidos aqui -->
        </div>
    `;

    // Adicionar o evento de 'submit' no formulário
    const formBusca = document.getElementById("formBusca");
    formBusca.addEventListener("submit", function (event) {
        event.preventDefault(); // Impede o comportamento padrão (recarregar a página)
        buscarClientes(); // Executa a função de busca
    });

//*******************************Chamar a função para buscar todos os clientes ao iniciar
    buscarClientes();
}

function buscarClientes() {
    const nomeCliente = document.getElementById("nomeCliente").value || ""; // Valor vazio para buscar todos

    fetch('buscar_clientes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `nomeCliente=${encodeURIComponent(nomeCliente)}`,
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("resultadoBusca").innerHTML = data;
    })
    .catch(error => {
        console.error("Erro ao buscar clientes:", error);
    });
}

//**********************************Proteção das Páginas:*******************************/
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}