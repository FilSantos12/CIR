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
    // Teste se a conexão existe
    if (!isset($conn)) {
        logErro("Erro: Variável \$conn não está definida.");
        die("Erro na conexão.");
    }

    // Iniciar uma transação
    $conn->begin_transaction();
    logErro("Transação iniciada.");

    // Verificar se há registros antigos antes de mover
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
        // Mover registros antigos para o histórico
        $queryMove = "
                INSERT INTO historico_processos (
                    id_cliente, nomeCliente, cpf, procuracao, data_solicitacao, tipo, documentos, conferencia, 
                    imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, 
                    transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, 
                    boleto_enviado, pagamento
                )
                SELECT 
                    p.id_cliente, c.nomeCliente, c.cpf, c.procuracao, data_solicitacao, tipo, documentos, conferencia, 
                    imposto_pagar, doacao, dados_doacao, parcelamento, imposto_restituir, 
                    transmissao, data_transmissao, enviada_cliente, observacoes, valor_cobrado, 
                    boleto_enviado, pagamento
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

        // Somente excluir se a movimentação foi bem-sucedida
        if ($rowsMoved > 0) {
            $queryDelete = "
                DELETE FROM processos
                WHERE YEAR(data_solicitacao) < YEAR(CURRENT_DATE);
            ";
            $stmtDelete = $conn->prepare($queryDelete);
            
            if (!$stmtDelete) {
                logErro("Erro ao preparar consulta de exclusão: " . $conn->error);
                die("Erro ao preparar consulta de exclusão.");
            }

            $stmtDelete->execute();
            $rowsDeleted = $stmtDelete->affected_rows;
            logErro("Registros excluídos: $rowsDeleted");
            $stmtDelete->close();
        } else {
            $rowsDeleted = 0;
            logErro("Nenhum registro foi excluído.");
        }

        // Commit da transação
        $conn->commit();
        logErro("Transação confirmada com sucesso.");

        // Exibir pop-up e redirecionar
        echo "<script>
                alert('Transação concluída com sucesso!');
                window.location.href='controle_clientes.html';
              </script>";
        exit;
    } else {
        // Se não houver registros antigos, cancelar a transação
        $conn->rollback();
        logErro("Nenhum registro antigo encontrado. Transação cancelada.");
        echo "<script>
                Nenhum registro antigo encontrado. Transação cancelada.
             </script>";
        exit;
    }
} catch (Exception $e) {
    // Em caso de erro, desfazer a transação
    if ($conn->errno) {
        $conn->rollback();
        logErro("Erro detectado! Transação revertida.");
    }
    logErro("Erro: " . $e->getMessage());

    // Exibir mensagem de erro e redirecionar
    echo "<script>
            alert('Erro: " . addslashes($e->getMessage()) . "');
            window.location.href='controle_clientes.html';
          </script>";
    exit;
}
?>
