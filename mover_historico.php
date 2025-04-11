<?php
// Inclui o arquivo de conexão com o banco de dados
require_once 'db_connection.php';
echo "Arquivo de conexão carregado!<br>";

// Função para registrar logs no arquivo
function logErro($mensagem) {
    $arquivo = 'erro_log.txt';
    $dataHora = date("Y-m-d H:i:s");
    file_put_contents($arquivo, "[$dataHora] $mensagem" . PHP_EOL, FILE_APPEND);
}

try {
    // Testa se a conexão foi criada
    if (!isset($conn)) {
        logErro("Erro: Variável \$conn não está definida.");
        die("Erro na conexão.");
    }

    // Inicia a transação
    $conn->begin_transaction();
    logErro("Transação iniciada.");

    // Verifica se existem registros antigos para mover
    $queryCheck = "SELECT COUNT(*) FROM processos WHERE YEAR(data_solicitacao) < YEAR(CURRENT_DATE)";
    $stmtCheck = $conn->prepare($queryCheck);

    if (!$stmtCheck) {
        logErro("Erro ao preparar consulta: " . $conn->error);
        die("Erro ao preparar consulta.");
    }

    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    logErro("Registros antigos encontrados: $count");
    $stmtCheck->close();

    if ($count > 0) {
        // Move registros antigos para a tabela de histórico
        $queryMove = "
            INSERT INTO historico_processos (
                id_cliente, nomeCliente, cpf, procuracao, data_solicitacao, tipo, documentos, conferencia, 
                imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, 
                transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, 
                boleto_enviado, pagamento
            )
            SELECT 
                p.id_cliente, c.nomeCliente, c.cpf, c.procuracao, p.data_solicitacao, p.tipo, p.documentos, p.conferencia, 
                p.imposto_pagar, p.doacao, p.dados_doacao, p.parcelamento, p.imposto_restituir, 
                p.transmissao, p.data_transmissao, p.enviada_cliente, p.observacoes, p.valor_cobrado, 
                p.boleto_enviado, p.pagamento
            FROM processos p
            JOIN cliente c ON p.id_cliente = c.id
            WHERE YEAR(p.data_solicitacao) < YEAR(CURRENT_DATE);
        ";
        $stmtMove = $conn->prepare($queryMove);

        if (!$stmtMove) {
            logErro("Erro ao preparar consulta de inserção: " . $conn->error);
            die("Erro ao preparar consulta de inserção.");
        }

        $stmtMove->execute();
        $rowsMoved = $stmtMove->affected_rows;
        logErro("Registros movidos: $rowsMoved");
        $stmtMove->close();

        if ($rowsMoved > 0) {
            // Atualiza os registros antigos para zerar os campos
            $queryUpdate = "
                UPDATE processos
                SET
                    data_solicitacao = NULL,
                    tipo = '',
                    documentos = '',
                    conferencia = '',
                    imposto_pagar = 0,
                    doacao = '',
                    dados_doacao = '',
                    parcelamento = '',
                    imposto_restituir = 0,
                    transmissao = '',
                    data_transmissao = NULL,
                    enviada_cliente = NULL,
                    observacoes = '',
                    valor_cobrado = 0,
                    boleto_enviado = '',
                    pagamento = ''
                WHERE YEAR(data_solicitacao) < YEAR(CURRENT_DATE);
            ";
            $stmtUpdate = $conn->prepare($queryUpdate);

            if (!$stmtUpdate) {
                logErro("Erro ao preparar UPDATE: " . $conn->error);
                die("Erro ao preparar UPDATE.");
            }

            $stmtUpdate->execute();
            logErro("Registros da tabela 'processos' atualizados com sucesso.");
            $stmtUpdate->close();
        } else {
            logErro("Nenhum registro foi movido. UPDATE não executado.");
        }

        // Finaliza a transação
        $conn->commit();
        logErro("Transação confirmada com sucesso.");

        echo "<script>
                alert('Transação concluída com sucesso!');
                window.location.href='controle_clientes.html';
              </script>";
        exit;

    } else {
        // Nenhum dado antigo encontrado, cancela a transação
        $conn->rollback();
        logErro("Nenhum registro antigo encontrado. Transação cancelada.");

        echo "<script>
                alert('Nenhum registro antigo encontrado. Transação cancelada.');
                window.location.href='controle_clientes.html';
              </script>";
        exit;
    }

} catch (Exception $e) {
    if ($conn->errno) {
        $conn->rollback();
        logErro("Erro detectado! Transação revertida.");
    }

    logErro("Erro: " . $e->getMessage());

    echo "<script>
            alert('Erro: " . addslashes($e->getMessage()) . "');
            window.location.href='controle_clientes.html';
          </script>";
    exit;
}
?>
