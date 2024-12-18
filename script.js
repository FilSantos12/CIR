//CLIENTE DO SISTEMA
            function mostrarTelaCadastroClientes() {

// Conteúdo da tela de cadastro de Clientes 
            const telaCadastro = `
            <h2>Cadastro de Clientes</h2>
        <form action="cad_cliente.php" method="POST">
            <!-- Nome do Cliente -->
        <div class="mb-3">
                <label for="nomeCliente" class="form-label">Nome do Cliente</label>
                <input type="text" class="form-control" id="nomeCliente" name="nomeCliente" required placeholder="Digite o nome do cliente">
            </div>

            <!-- CPF e Senha Gov.Br -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="Digite o CPF">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="senhaGovBr" class="form-label">senhaGovBr</label>
                    <input type="password" class="form-control" id="senhaGovBr" name="senhaGovBr" placeholder="Digite a senha Gov.Br">
                </div>
            </div>

            <!-- Procuração e Data de Vencimento -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Procuração</label>
                    <div>
                        <select class="form-select" id="procuracao" name="procuracao">
                            <option value="" disabled selected hidden>Selecione</option>
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dataVencimento" class="form-label">Data de Vencimento</label>
                    <input type="date" class="form-control" id="dataVencimento" name="dataVencimento">
                </div>
            </div>

            <!-- Telefone e Email -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required placeholder="Digite o telefone">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Digite o email">
                </div>
            </div>

        <!-- Serviço Solicitado e Prioridade -->
    <div class="row">
        <!--Prioridade-->
            <div class="col-md-4 mb-3">
                <label class="form-label">Prioridade</label>
                <select class="form-select" id="prioridade" name="prioridade">
                    <option value="" disabled selected hidden>Selecione</option>
                    <option value="baixa">Baixa</option>
                    <option value="media">Média</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
                <div class="col-md-4 mb-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="ano" name="ano" placeholder="Digite o ano">
                </div>
        <!--Serviço solicitado -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Serviço Solicitado</label>
                    <select class="form-select" id="servico_solicitado" name="servico_solicitado">
                        <option value="" disabled selected hidden>Selecione</option>
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>
        <!-- Data -->
            <div class="col-md-4 mb-3">
                <label for="dataSolicitacao" class="form-label">Data</label>
                <input type="date" class="form-control" id="dataSolicitacao" name="dataSolicitacao">
            </div>

        <form>
            <!-- Botão de Envio -->
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
    
            `;
            document.getElementById('conteudoPrincipal').innerHTML = telaCadastro;
        }

//Lista de Clientes Cadastrados
                function mostrarListaClientes() {
            const listaClientes = `
            <h2>Clientes Cadastrados</h2>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Usuário 1 - email1@exemplo.com
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editarCliente('Usuário 1')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirCliente('Usuário 1')">Excluir</button>
                        </div>
                    </li>
                </ul>
    
            `;
            document.getElementById('conteudoPrincipal').innerHTML = telaCadastro;
        }

    
//USUARIO DO SISTEMA
        function mostrarTelaCadastroUsuarios() {

// Conteúdo da tela de cadastro de usuarios 
            const telaCadastro = `
                <h2>Cadastro de Usuários</h2>
                <form action="cad_usuario.php" method="POST">
                    <div class="mb-3">
                        <label for="nomeUsuario" class="form-label">Nome do Usuário</label>
                        <input type="text" class="form-control" id="nomeUsuario" name="nome" required placeholder="Digite o nome">
                    </div>
                    <div class="mb-3">
                        <label for="emailUsuario" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="emailUsuario" name="email" required placeholder="Digite o e-mail">
                    </div>

                    <div class="mb-3">
                        <label for="senhaUsuario" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senhaUsuario" name="senha" required placeholder="Digite a senha">
                    </div>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
                
            `;
            document.getElementById('conteudoPrincipal').innerHTML = telaCadastro;

        }
//Lista de Usuarios Cadastrados
                function mostrarListaUsuarios() {
            const listaUsuarios = `
                <h2>Lista de Usuários</h2>
                <ul id="listaUsuarios" class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nome} (${email})
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirUsuario(${id})">Excluir</button>
                    </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nome} (${email})
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirUsuario(${id})">Excluir</button>
                    </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nome} (${email})
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirUsuario(${id})">Excluir</button>
                    </div>
                    </li>
                </ul>
            `;
            document.getElementById('conteudoPrincipal').innerHTML = listaUsuarios;
        }
//Voltar ao conteudo principal
        function mostrarTelaInicio() {
            const conteudoInicio = `
                <h1>Bem-vindo ao Web Service</h1>
                <p>Este é o conteúdo principal do sistema.</p>
            `;
            document.getElementById('conteudoPrincipal').innerHTML = conteudoInicio;
        }
//Buscar dados de usuarios 
function mostrarListaUsuarios() {
    // Faz requisição para o backend
    fetch("buscar_dados.php")
        .then(response => response.json())
        .then(data => {
            // Cria a estrutura da lista
            const listaUsuariosHTML = `
                <h2>Lista de Usuários</h2>
                <ul id="listaUsuarios" class="list-group"></ul>
            `;

            // Renderiza a estrutura na página
            document.getElementById('conteudoPrincipal').innerHTML = listaUsuariosHTML;

            // Adiciona os itens retornados do backend
            const listaUsuarios = document.getElementById("listaUsuarios");
            data.forEach(usuario => {
                const li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                li.innerHTML = `
                    ${usuario.id} - ${usuario.nome} (${usuario.email})
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario(${usuario.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirUsuario(${usuario.id})">Excluir</button>
                    </div>
                `;
                listaUsuarios.appendChild(li);
            });
        })
        .catch(error => console.error("Erro ao buscar dados:", error));
}


// Função para editar usuário
        function editarUsuario(id) {
            const nome = prompt("Digite o novo nome:");
            const email = prompt("Digite o novo email:");

            if (nome && email) {
                const payload = { id, nome, email };
                console.log("Dados enviados para edição:", payload); // DEBUG

                fetch("editar_usuario.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Resposta do servidor:", data); // DEBUG
                    if (data.sucesso) {
                        alert("Usuário editado com sucesso!");
                        mostrarListaUsuarios(); // Atualiza a lista
                    } else {
                        alert("Erro ao editar usuário: " + data.mensagem);
                    }
                })
                .catch(error => console.error("Erro:", error));
            }
        }


// Função para excluir usuário
        function excluirUsuario(id) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                const payload = { id };
                console.log("Dados enviados para exclusão:", payload); // DEBUG

                fetch("excluir_usuario.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Resposta do servidor:", data); // DEBUG
                    if (data.sucesso) {
                        alert("Usuário excluído com sucesso!");
                        mostrarListaUsuarios(); // Atualiza a lista
                    } else {
                        alert("Erro ao excluir usuário: " + data.mensagem);
                    }
                })
                .catch(error => console.error("Erro:", error));
            }
        }


        