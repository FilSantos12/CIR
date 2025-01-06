function mostrarBuscaClientes() {
    const conteudoPrincipal = document.getElementById("conteudoPrincipal");
    conteudoPrincipal.innerHTML = `
        <h2>Busca de Clientes</h2>
        <form id="formBusca" class="mb-4">
            <div class="input-group">
                <input type="text" id="nomeCliente" class="form-control" placeholder="Digite o nome do cliente" />
                <button type="button" class="btn btn-primary" onclick="buscarClientes()">Buscar</button>
            </div>
        </form>
        <div id="resultadoBusca">
            <!-- Resultados serão exibidos aqui -->
        </div>
    `;
}

