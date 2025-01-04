//************************************* CLIENTE DO SISTEMA ********************************************/

            function mostrarTelaCadastroClientes() {

//***************************** Conteúdo da tela de cadastro de Clientes *******************************/

            const telaCadastro = `
            <h2>Cadastro de Clientes</h2>
        <form action="cad_cliente.php" method="POST">
        <input type="hidden" id="clienteId" name="clienteId">
            <!-- Nome do Cliente -->
        <div class="mb-3">
                <label for="nomeCliente" class="form-label">Nome do Cliente</label>
                <input type="text" class="form-control" id="nomeCliente" name="nomeCliente" required placeholder="Digite o nome do cliente">
            </div>

            <!-- CPF e Senha Gov.Br -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cpf" class="form-label">CPF</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required placeholder="Digite o CPF" oninput="mascaraCPF(this)">
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
                    <input type="text" class="form-control" id="telefone" name="telefone" required placeholder="Digite o telefone" oninput="mascaraTelefone(this)">
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
                <select class="form-select" id="ano" name="ano">
                    <option value="" disabled selected hidden>Selecione o ano</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                </select>
            </div>
        <!--Serviço solicitado -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Serviço Solicitado</label>
                    <select class="form-select" id="status_servico" name="status_servico">
                        <option value="" disabled selected hidden>Selecione</option>
                        <option value="sim">Sim</option>
                        <option value="nao">Não</option>
                    </select>
                </div>
        <!-- Data -->
            <div class="col-md-4 mb-3">
                <label for="servico_solicitado" class="form-label">Data</label>
                <input type="date" class="form-control" id="servico_solicitado" name="servico_solicitado">
            </div>

        <form>
            <!-- Botão de Envio -->
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
            `;
            document.getElementById('conteudoPrincipal').innerHTML = telaCadastro;
        }

//************************Mascara pata CPF*********************************************************/

            function mascaraCPF(input) {
                    let value = input.value.replace(/\D/g, ''); // Remove tudo que não for número

                    // Limita o número de caracteres a 11 (CPF tem 11 dígitos)
                    if (value.length > 11) {
                        value = value.slice(0, 11);
                    }

                    // Aplica a máscara (XXX.XXX.XXX-XX)
                    if (value.length <= 9) {
                        value = value.replace(/(\d{3})(\d{0,3})(\d{0,3})/, '$1.$2.$3');
                    } else {
                        value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                    }

                    input.value = value; // Atualiza o campo de input com a máscara
    }

//***********************Mascara do telefone******************************************************/
            function mascaraTelefone(input) {
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não for número

                // Limita o número de caracteres a 11
        if (value.length > 11) {
            value = value.slice(0, 11);
        }

        // Aplica a máscara (XX) XXXXX-XXXX
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d{0,5})(\d{0,4})/, '($1) $2-$3');
        } else {
            value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        }

        input.value = value; // Atualiza o campo de input com a máscara
    }

//************************************** Lista de Clientes Cadastrados *******************************/

                function mostrarListaClientes() {
            const listaCliente = `
                <h2>Lista de Clientes</h2>
                <ul id="listaCliente" class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nomeCliente} ${cpf} ${senhaGovBr} ${procuracao} ${dataVencimento} ${telefone} (${email})
                    ${prioridade} ${servico_solicitado} ${ano} ${status_servico} 
                    <div> 
                        <button class="btn btn-warning btn-sm me-2" onclick="editarCliente(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirCliente(${id})">Excluir</button>
                    </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nomeCliente} ${cpf} ${senhaGovBr} ${procuracao} ${dataVencimento} ${telefone} (${email})
                    ${prioridade} ${servico_solicitado} ${ano} ${status_servico} 
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarCliente(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirCliente(${id})">Excluir</button>
                    </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                    ${id} ${nomeCliente} ${cpf} ${senhaGovBr} ${procuracao} ${dataVencimento} ${telefone} (${email})
                    ${prioridade} ${servico_solicitado} ${ano} ${status_servico} 
                    <div>
                        <button class="btn btn-warning btn-sm me-2" onclick="editarCliente(${id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="excluirCliente(${id})">Excluir</button>
                    </div>
                    </li>
                </ul>
            `;
            document.getElementById('conteudoPrincipal').innerHTML = listaCliente;
        }

    
//**************************************** USUARIO DO SISTEMA ***************************************/

        function mostrarTelaCadastroUsuarios() {

//*************************************** Conteúdo da tela de cadastro de usuarios ******************/
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
//**************************************** Lista de Usuarios Cadastrados ***************************/

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
//*****************************************Voltar ao conteudo principal ***********************/

        function mostrarTelaInicio() {
            const conteudoInicio = `
                <h1>Bem-vindo ao Web Service</h1>
                <p>Este é o conteúdo principal do sistema.</p>
            `;
            document.getElementById('conteudoPrincipal').innerHTML = conteudoInicio;
        }
//************************************* Buscar dados de usuarios ************************/

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


//***************************************** Função para editar usuário ****************************/

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


//**************************************** Função para excluir usuário ************************/

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
        
    //******************* Mostrar Lista de Clientes Cadastrados******************/

function mostrarListaClientes() {
    // Faz a requisição para o PHP
    fetch('buscar_dados_cliente.php')
        .then(response => response.json()) // Converte a resposta em JSON
        .then(clientes => {
            let tabelaClientes = `
                <h2>Lista de Clientes</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Senha Gov.Br</th>
                                <th>Procuração</th>
                                <th>Data Vencimento</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th>Prioridade</th>
                                <th>Serviço</th>
                                <th>Ano</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            // Adiciona cada cliente como uma linha na tabela
            clientes.forEach(cliente => {
                tabelaClientes += `
                    <tr>
                        <td>${cliente.id}</td>
                        <td>${cliente.nomeCliente}</td>
                        <td>${cliente.cpf}</td>
                        <td>${cliente.senhaGovBr}</td>
                        <td>${cliente.procuracao}</td>
                        <td>${cliente.dataVencimento}</td>
                        <td>${cliente.telefone}</td>
                        <td>${cliente.email}</td>
                        <td>${cliente.prioridade}</td>
                        <td>${cliente.servico_solicitado}</td>
                        <td>${cliente.ano}</td>
                        <td>${cliente.status_servico}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-warning btn-sm me-2" onclick="editarCliente(${cliente.id})">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="excluirCliente(${cliente.id})">Excluir</button>
                            </div>
                        </td>
                    </tr>
                `;
            });

            tabelaClientes += `
                        </tbody>
                    </table>
                </div>
            `;

            // Exibe a tabela na página
            document.getElementById('conteudoPrincipal').innerHTML = tabelaClientes;
        })
        .catch(error => console.error('Erro ao buscar cliente:', error));
}


//********************************************Excluir Cliente******************************************/

                function excluirCliente(id) {
            if (confirm("Tem certeza que deseja excluir este usuário?")) {
                const payload = { id };
                console.log("Dados enviados para exclusão:", payload); // DEBUG

                fetch("excluir_cliente.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(payload),
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Resposta do servidor:", data); // DEBUG
                    if (data.sucesso) {
                        alert("Cliente excluído com sucesso!");
                        mostrarListaClientes(); // Atualiza a lista
                    } else {
                        alert("Erro ao excluir usuário: " + data.mensagem);
                    }
                })
                .catch(error => console.error("Erro:", error));
            }
        }

//*******************************Editar Cliente*********************************************************/ 

function editarCliente(id) {
    // Faz a requisição para buscar os dados do cliente específico
    console.log('ID do cliente:', id); // Debug
    fetch(`buscar_cliente_por_id.php?id=${id}`)
        .then(response => response.json())
        .then(cliente => {
            // Verifica se os dados do cliente são válidos antes de preencher o formulário
            if (cliente && cliente.id) {
                // Cria o formulário de edição
                let formularioEdicao = `
                    <h2>Editar Cliente</h2>
                        <form method="POST" action="editar_cliente.php">
                            <input type="hidden" id="editarId" name="id" value="${cliente.id}">
                            
                            <!-- Nome do Cliente -->
                            <div class="mb-3">
                                <label for="editarNome" class="form-label">Nome do Cliente</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="editarNome" 
                                    name="nomeCliente" 
                                    required 
                                    value="${cliente.nomeCliente}" 
                                    placeholder="Digite o nome do cliente">
                            </div>

                            <!-- CPF e Senha Gov.Br -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editarCPF" class="form-label">CPF</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editarCPF" 
                                        name="cpf" 
                                        required 
                                        value="${cliente.cpf}" 
                                        placeholder="Digite o CPF">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editarSenhaGovBr" class="form-label">Senha Gov.Br</label>
                                    <input 
                                        type="password" 
                                        class="form-control" 
                                        id="editarSenhaGovBr" 
                                        name="senhaGovBr" 
                                        value="${cliente.senhaGovBr}" 
                                        placeholder="Digite a senha Gov.Br">
                                </div>
                            </div>

                            <!-- Procuração e Data de Vencimento -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editarProcuracao" class="form-label">Procuração</label>
                                    <select class="form-select" id="editarProcuracao" name="procuracao">
                                        <option value="Sim" ${cliente.procuracao === "Sim" ? "selected" : ""}>Sim</option>
                                        <option value="Não" ${cliente.procuracao === "Não" ? "selected" : ""}>Não</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editarDataVencimento" class="form-label">Data de Vencimento</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="editarDataVencimento" 
                                        name="dataVencimento" 
                                        value="${cliente.dataVencimento}" 
                                        required>
                                </div>
                            </div>

                            <!-- Telefone e Email -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="editarTelefone" class="form-label">Telefone</label>
                                    <input 
                                        type="text" 
                                        class="form-control" 
                                        id="editarTelefone" 
                                        name="telefone" 
                                        required 
                                        value="${cliente.telefone}" 
                                        placeholder="Digite o telefone">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="editarEmail" class="form-label">Email</label>
                                    <input 
                                        type="email" 
                                        class="form-control" 
                                        id="editarEmail" 
                                        name="email" 
                                        required 
                                        value="${cliente.email}" 
                                        placeholder="Digite o email">
                                </div>
                            </div>

                            <!-- Prioridade, Ano e Serviço Solicitado -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="editarPrioridade" class="form-label">Prioridade</label>
                                    <select class="form-select" id="editarPrioridade" name="prioridade">
                                        <option value="Baixa" ${cliente.prioridade === "Baixa" ? "selected" : ""}>Baixa</option>
                                        <option value="Média" ${cliente.prioridade === "Média" ? "selected" : ""}>Média</option>
                                        <option value="Alta" ${cliente.prioridade === "Alta" ? "selected" : ""}>Alta</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="editarAno" class="form-label">Ano</label>
                                    <select class="form-select" id="editarAno" name="ano">
                                        <option value="2025" ${cliente.ano === "2025" ? "selected" : ""}>2025</option>
                                        <option value="2024" ${cliente.ano === "2024" ? "selected" : ""}>2024</option>
                                        <option value="2023" ${cliente.ano === "2023" ? "selected" : ""}>2023</option>
                                        <option value="2022" ${cliente.ano === "2022" ? "selected" : ""}>2022</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="editarServicoSolicitado" class="form-label">Serviço Solicitado</label>
                                    <input 
                                        type="date" 
                                        class="form-control" 
                                        id="editarServicoSolicitado" 
                                        name="servico_solicitado" 
                                        value="${cliente.servico_solicitado}" 
                                        required>
                                </div>
                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-primary" onclick="salvarEdicaoCliente()">Salvar</button>
                                <button type="button" class="btn btn-secondary" onclick="mostrarListaClientes()">Cancelar</button>
                            </div>
                        </form>

                `;

                // Substitui o conteúdo principal pelo formulário de edição
                document.getElementById('conteudoPrincipal').innerHTML = formularioEdicao;
            } else {
                console.error('Cliente não encontrado ou dados inválidos');
            }
        })
        .catch(error => console.error('Erro ao buscar cliente para edição:', error));
}

function salvarEdicaoCliente() {
    const clienteEditado = {
        id: document.getElementById('editarId').value,
        nomeCliente: document.getElementById('editarNome').value.trim(),
        cpf: document.getElementById('editarCPF').value.trim(),
        email: document.getElementById('editarEmail').value.trim(),
        telefone: document.getElementById('editarTelefone').value.trim(),
        senhaGovBr: document.getElementById('editarSenhaGovBr').value.trim(),
        procuracao: document.getElementById('editarProcuracao').value,
        prioridade: document.getElementById('editarPrioridade').value,
        ano: document.getElementById('editarAno').value,
        servico_solicitado: document.getElementById('editarServicoSolicitado').value,
        dataVencimento: document.getElementById('editarDataVencimento').value,
    };

    // Verificar se todos os campos obrigatórios estão preenchidos
    if (!clienteEditado.nomeCliente || !clienteEditado.cpf || !clienteEditado.email || !clienteEditado.id || !clienteEditado.dataVencimento) {
        alert('Preencha todos os campos obrigatórios.');
        return;
    }

    // Verificar se a dataVencimento está no formato correto (YYYY-MM-DD)
    const dataVencimentoValida = /^(\d{4})-(\d{2})-(\d{2})$/.test(clienteEditado.dataVencimento);
    if (!dataVencimentoValida) {
        alert('A data de vencimento deve estar no formato YYYY-MM-DD.');
        return;
    }

    fetch('editar_cliente.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(clienteEditado),
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            alert('Cliente atualizado com sucesso!');
        } else {
            alert('Erro: ' + data.mensagem);
        }
    })
    .catch(error => console.error('Erro ao salvar edição:', error));
}


