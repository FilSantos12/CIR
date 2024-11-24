        //CLIENTE DO SISTEMA
            function mostrarTelaCadastroClientes() {

            // Conteúdo da tela de cadastro de Clientes 
            const telaCadastro = `
            <h2>Cadastro de Clientes</h2>
        <form>
            <!-- Nome do Cliente -->
        <div class="mb-3">
                <label for="nomeCliente" class="form-label">Nome do Cliente</label>
                <input type="text" class="form-control" id="nomeCliente" placeholder="Digite o nome do cliente">
            </div>

            <!-- CPF e Senha Gov.Br -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" placeholder="Digite o CPF">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="senhaGovBr" class="form-label">Senha Gov.Br</label>
                    <input type="password" class="form-control" id="senhaGovBr" placeholder="Digite a senha Gov.Br">
                </div>
            </div>

            <!-- Procuração e Data de Vencimento -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Procuração</label>
                    <div>
                        <select class="form-select" id="procuracao">
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dataVencimento" class="form-label">Data de Vencimento</label>
                    <input type="date" class="form-control" id="dataVencimento">
                </div>
            </div>

            <!-- Telefone e Email -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="telefone" placeholder="Digite o telefone">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Digite o email">
                </div>
            </div>

        <!-- Serviço Solicitado e Prioridade -->
    <div class="row">
        <!--Prioridade-->
            <div class="col-md-4 mb-3">
                <label class="form-label">Prioridade</label>
                <select class="form-select" id="prioridade">
                    <option value="baixa">Baixa</option>
                    <option value="media">Média</option>
                    <option value="alta">Alta</option>
                </select>
            </div>
                <div class="col-md-4 mb-3">
                    <label for="ano" class="form-label">Ano</label>
                    <input type="number" class="form-control" id="ano" placeholder="Digite o ano">
                </div>
        <!--Serviço solicitado -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Serviço Solicitado</label>
                    <select class="form-select" id="selecaoServico">
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>
        <!-- Data -->
            <div class="col-md-4 mb-3">
                <label for="dataSolicitacao" class="form-label">Data</label>
                <input type="date" class="form-control" id="dataSolicitacao">
            </div>

        <form>
            <!-- Botão de Envio -->
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
    
            `;
            document.getElementById('conteudoPrincipal').innerHTML = telaCadastro;
        }


        //USUARIO DO SISTEMA
        function mostrarTelaCadastroUsuario() {

            // Conteúdo da tela de cadastro de usuarios 
            const telaCadastro = `
                <h2>Cadastro de Usuário</h2>
                <form>
                    <div class="mb-3">
                        <label for="nomeUsuario" class="form-label">Nome do Usuário</label>
                        <input type="text" class="form-control" id="nomeUsuario" placeholder="Digite o nome">
                    </div>
                    <div class="mb-3">
                        <label for="emailUsuario" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="emailUsuario" placeholder="Digite o e-mail">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="inputEstado">Nivel Acesso</label>
                            <select id="inputEstado" class="form-control">
                                <option selected>Escolha o nivel do cadastro</option>
                                <option>Administrador</option>
                                <option>Usuario</option>
                            </select>
                    </div>
                    <div class="mb-3">
                        <label for="senhaUsuario" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senhaUsuario" placeholder="Digite a senha">
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
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Usuário 1 - email1@exemplo.com
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario('Usuário 1')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirUsuario('Usuário 1')">Excluir</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Usuário 2 - email2@exemplo.com
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario('Usuário 2')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirUsuario('Usuário 2')">Excluir</button>
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Usuário 3 - email3@exemplo.com
                        <div>
                            <button class="btn btn-warning btn-sm me-2" onclick="editarUsuario('Usuário 3')">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="excluirUsuario('Usuário 3')">Excluir</button>
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