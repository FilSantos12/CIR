<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <title>Tabela de Clientes</title>
    <style>
        table th, table td {
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
        }
        table th {
            font-weight: bold;
            font-size: 20px;
        }
        .table-wrapper {
            overflow-x: auto;
        }
    </style>
</head>
<body>
<div class="container my-4">
    <div class="table-wrapper">
        <?php
        require_once 'db_connection.php';
        $nomeCliente = isset($_POST['nomeCliente']) ? $conn->real_escape_string($_POST['nomeCliente']) : '';

        $sql = "
            SELECT 
                cliente.id AS cliente_id, 
                cliente.nomeCliente,
                cliente.cpf, 
                cliente.procuracao,
 
                processos.*
            FROM 
                cliente
            LEFT JOIN 
                processos ON cliente.id = processos.id_cliente
        ";
        $conditions = [];
        if (!empty($nomeCliente)) {
            $conditions[] = "cliente.nomeCliente LIKE '%$nomeCliente%'";
        }
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" OR ", $conditions);
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<form method='POST' action='salvar_processos.php'>";
            echo "<table class='table table-bordered table-hover table-sm'>";
            echo "<thead class='table-dark'>";
            echo "<tr>
                <th>#</th>
                <th>Cliente</th>
                <th>CPF</th>
                <th>Procuração</th>
                <th>Data Solicitacao</th>
                <th>Tipo</th>
                <th>Documentos</th>
                <th>Conferência</th>
                <th>Imposto a Pagar</th>
                <th>Doação</th>
                <th>Dados da Doação</th>
                <th>Parcelamento</th>
                <th>Imposto a Restituir</th>
                <th>Transmissão</th>
                <th>Data de Transmissão</th>
                <th>Enviada ao Cliente</th>
                <th>Observações</th>
                <th>Valor Cobrado</th>
                <th>Boleto Enviado</th>
                <th>Pagamento</th>
                <th>Ações</th>
            </tr>";
            echo "</thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['cliente_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nomeCliente']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cpf']) . "</td>";
                echo "<td>" . htmlspecialchars($row['procuracao']) . "</td>";

                echo "<td><input type='date' name='data_solicitacao' class='form-control' value='" . htmlspecialchars($row['data_solicitacao']) . "'></td>";
                echo "<td>
                    <select name='tipo[]' class='form-select' style='width: 150px'>
                        <option value='Básica'>Básica</option>
                        <option value='Com Ações'>Com Ações</option>
                        <option value='Com Espólio'>Com Espólio</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='documentos[]' class='form-select' style='width: 200px'>
                        <option value='Pendente de Envio'>Pendente de Envio</option>
                        <option value='Com Pendências'>Com Pendências</option>
                        <option value='Enviados'>Enviados</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='conferencia[]' class='form-select' style='width: 210px'>
                        <option value='Pendente'>Pendente</option>
                        <option value='Em Andamento'>Em Andamento</option>
                        <option value='Conferido pelo Cliente'>Conferido pelo Cliente</option>
                    </select>
                </td>";

                echo "<td>
                    <div class='input-group'>
                        <span class='input-group-text'>R$</span>
                        <input type='text' name='imposto_a_pagar[]' class='form-control moeda' value='" . htmlspecialchars($row['imposto_a_pagar']) . "'>
                    </div>
                </td>";


                echo "<td>
                    <select name='doacao[]' class='form-select' style='width: 100px'>
                        <option value='Não'>Não</option>
                        <option value='Sim'>Sim</option>
                    </select>
                </td>";
                echo "<td><input type='text' name='dados_doacao[]' class='form-control' value='" . htmlspecialchars($row['dados_doacao']) . "'></td>";
                echo "<td>
                    <select name='parcelamento[]' class='form-select' style='width: 100px'>
                        <option>1</option><option>2</option><option>3</option>
                        <option>4</option><option>5</option><option>6</option>
                    </select>
                </td>";
                echo "<td>
                    <div class='input-group'>
                        <span class='input-group-text'>R$</span>
                        <input type='text' name='imposto_a_restituir[]' class='form-control moeda' value='" . htmlspecialchars($row['imposto_a_restituir']) . "'>
                    </div>
                </td>";

                echo "<td>
                    <select name='transmissao[]' class='form-select' style='width: 160px'>
                        <option value='Pendente'>Pendente</option>
                        <option value='Em Andamento'>Em Andamento</option>
                        <option value='Transmitido'>Transmitido</option>
                    </select>
                </td>";
                echo "<td><input type='date' name='data_transmissao[]' class='form-control' value='" . htmlspecialchars($row['data_transmissao']) . "'></td>";
                echo "<td><input type='date' name='enviada_ao_cliente[]' class='form-control' value='" . htmlspecialchars($row['enviada_ao_cliente']) . "'></td>";
                echo "<td><textarea name='observacoes[]' class='form-control'>" . htmlspecialchars($row['observacoes']) . "</textarea></td>";

                echo "<td>
                    <div class='input-group'>
                        <span class='input-group-text'>R$</span>
                        <input type='text' name='valor_cobrado[]' class='form-control moeda' value='" . htmlspecialchars($row['valor_cobrado']) . "'>
                    </div>
                </td>";

                echo "<td>
                    <select name='boleto_enviado[]' class='form-select'>
                        <option value='Sim'>Sim</option>
                        <option value='Não'>Não</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='pagamento[]' class='form-select' style='width: 150px'>
                        <option value='Pendente'>Pendente</option>
                        <option value='Pago'>Pago</option>
                    </select>
                </td>";
                echo "<td><button class='btn btn-success btn-sm'>Salvar</button></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</form>";
        } else {
            echo "<p class='text-center text-muted'>Nenhum cliente encontrado.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<script>

//***********Padronizando os campo que necessitam de inserção de valores tipo moeda*****************************

    // Função para formatar o valor como moeda
    function formatarMoeda(elemento) {
        elemento.addEventListener('input', function () {
            let valor = elemento.value.replace(/\D/g, ''); // Remove caracteres não numéricos
            valor = (valor / 100).toFixed(2).replace('.', ','); // Converte para formato decimal
            elemento.value = valor; // Atualiza o campo
        });
    }

    // Seleciona todos os inputs com a classe 'moeda' e aplica a formatação
    document.querySelectorAll('.moeda').forEach(function (campo) {
        formatarMoeda(campo);
    });
</script>


