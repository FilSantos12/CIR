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

                // Adiciona o campo oculto para o ID do processo
                echo "<input type='hidden' name='id[]' value='" . htmlspecialchars($row['id']) . "'>";

                echo "<td><input type='date' name='data_solicitacao[]' class='form-control' value='" . htmlspecialchars($row['data_solicitacao']) . "'></td>";
                echo "<td>
                    <select name='tipo[]' class='form-select' style='width: 150px'>
                        <option value=''</option>
                        <option value='Básica'" . ($row['tipo'] == 'Básica' ? ' selected' : '') . ">Básica</option>
                        <option value='Com Ações'" . ($row['tipo'] == 'Com Ações' ? ' selected' : '') . ">Com Ações</option>
                        <option value='Com Espólio'" . ($row['tipo'] == 'Com Espólio' ? ' selected' : '') . ">Com Espólio</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='documentos[]' class='form-select' style='width: 200px'>
                        <option value=''</option>
                        <option value='Pendente de Envio'" . ($row['documentos'] == 'Pendente de Envio' ? ' selected' : '') . ">Pendente de Envio</option>
                        <option value='Com Pendências'" . ($row['documentos'] == 'Com Pendências' ? ' selected' : '') . ">Com Pendências</option>
                        <option value='Enviados'" . ($row['documentos'] == 'Enviados' ? ' selected' : '') . ">Enviados</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='conferencia[]' class='form-select' style='width: 210px'>
                        <option value=''</option>
                        <option value='Pendente'" . ($row['conferencia'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                        <option value='Em Andamento'" . ($row['conferencia'] == 'Em Andamento' ? ' selected' : '') . ">Em Andamento</option>
                        <option value='Conferido pelo Cliente'" . ($row['conferencia'] == 'Conferido pelo Cliente' ? ' selected' : '') . ">Conferido pelo Cliente</option>
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
                        <option value=''</option>
                        <option value='Não'" . ($row['doacao'] == 'Não' ? ' selected' : '') . ">Não</option>
                        <option value='Sim'" . ($row['doacao'] == 'Sim' ? ' selected' : '') . ">Sim</option>
                    </select>
                </td>";
                echo "<td><input type='text' name='dados_doacao[]' class='form-control' value='" . htmlspecialchars($row['dados_doacao']) . "'></td>";
                echo "<td>
                    <select name='parcelamento[]' class='form-select' style='width: 100px'>
                        <option value=''</option>
                        <option value='1'" . ($row['parcelamento'] == '1' ? ' selected' : '') . ">1</option>
                        <option value='2'" . ($row['parcelamento'] == '2' ? ' selected' : '') . ">2</option>
                        <option value='3'" . ($row['parcelamento'] == '3' ? ' selected' : '') . ">3</option>
                        <option value='4'" . ($row['parcelamento'] == '4' ? ' selected' : '') . ">4</option>
                        <option value='5'" . ($row['parcelamento'] == '5' ? ' selected' : '') . ">5</option>
                        <option value='6'" . ($row['parcelamento'] == '6' ? ' selected' : '') . ">6</option>
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
                        <option value=''</option>
                        <option value='Pendente'" . ($row['transmissao'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                        <option value='Em Andamento'" . ($row['transmissao'] == 'Em Andamento' ? ' selected' : '') . ">Em Andamento</option>
                        <option value='Transmitido'" . ($row['transmissao'] == 'Transmitido' ? ' selected' : '') . ">Transmitido</option>
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
                        <option value=''</option>
                        <option value='Sim'" . ($row['boleto_enviado'] == 'Sim' ? ' selected' : '') . ">Sim</option>
                        <option value='Não'" . ($row['boleto_enviado'] == 'Não' ? ' selected' : '') . ">Não</option>
                    </select>
                </td>";
                echo "<td>
                    <select name='pagamento[]' class='form-select' style='width: 150px'>
                        <option value=''</option>
                        <option value='Pendente'" . ($row['pagamento'] == 'Pendente' ? ' selected' : '') . ">Pendente</option>
                        <option value='Pago'" . ($row['pagamento'] == 'Pago' ? ' selected' : '') . ">Pago</option>
                    </select>
                </td>";
                echo "<td><button type='submit' class='btn btn-success btn-sm'>Salvar</button></td>";
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
    campo.addEventListener('input', function () {
        let valor = campo.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        valor = (parseFloat(valor) / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
        campo.value = valor;
    });
});

</script>