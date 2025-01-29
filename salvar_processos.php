<?php
require_once 'db_connection.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os dados esperados estão definidos e são arrays
    if (
        isset($_POST['data_solicitacao'], $_POST['tipo'], $_POST['documentos'], $_POST['conferencia'], 
              $_POST['imposto_a_pagar'], $_POST['doacao'], $_POST['dados_doacao'], $_POST['parcelamento'], 
              $_POST['imposto_a_restituir'], $_POST['transmissao'], $_POST['data_transmissao'], 
              $_POST['enviada_ao_cliente'], $_POST['observacoes'], $_POST['valor_cobrado'], 
              $_POST['boleto_enviado'], $_POST['pagamento'], $_POST['id']) &&
        is_array($_POST['data_solicitacao'])
    ) {
        // Recupera os dados do formulário
        $data_solicitacao = $_POST['data_solicitacao'];
        $tipo = $_POST['tipo'];
        $documentos = $_POST['documentos'];
        $conferencia = $_POST['conferencia'];
        $imposto_a_pagar = $_POST['imposto_a_pagar'];
        $doacao = $_POST['doacao'];
        $dados_doacao = $_POST['dados_doacao'];
        $parcelamento = $_POST['parcelamento'];
        $imposto_a_restituir = $_POST['imposto_a_restituir'];
        $transmissao = $_POST['transmissao'];
        $data_transmissao = $_POST['data_transmissao'];
        $enviada_ao_cliente = $_POST['enviada_ao_cliente'];
        $observacoes = $_POST['observacoes'];
        $valor_cobrado = $_POST['valor_cobrado'];
        $boleto_enviado = $_POST['boleto_enviado'];
        $pagamento = $_POST['pagamento'];
        $id = $_POST['id'];

        // Prepara a consulta SQL
        $sql = "
            UPDATE processos 
            SET 
                data_solicitacao = ?, 
                tipo = ?, 
                documentos = ?, 
                conferencia = ?, 
                imposto_a_pagar = ?, 
                doacao = ?, 
                dados_doacao = ?, 
                parcelamento = ?, 
                imposto_a_restituir = ?, 
                transmissao = ?, 
                data_transmissao = ?, 
                enviada_ao_cliente = ?, 
                observacoes = ?, 
                valor_cobrado = ?, 
                boleto_enviado = ?, 
                pagamento = ? 
            WHERE id = ?
        ";

        // Prepara a declaração
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro ao preparar a consulta: " . $conn->error);
        }

        //echo "<pre>";
        //print_r($_POST);
        //echo "</pre>";
        //exit();

                    // Filtrar IDs não vazios
            $id = array_filter($_POST['id'], function ($value) {
                return !empty($value) && is_numeric($value);
            });


        // Itera sobre os dados e executa a atualização para cada linha
        for ($i = 0; $i < count($tipo); $i++) {
            // Verifica se o ID está definido e é válido
            if (!isset($id[$i]) || !is_numeric($id[$i])) {
                die("ID inválido ou ausente para a linha $i.");
            }

            // Vincula os parâmetros
            $stmt->bind_param(
                'sssssssssssssssi', // Tipos dos parâmetros
                $data_solicitacao[$i],
                $tipo[$i],
                $documentos[$i],
                $conferencia[$i],
                $imposto_a_pagar[$i],
                $doacao[$i],
                $dados_doacao[$i],
                $parcelamento[$i],
                $imposto_a_restituir[$i],
                $transmissao[$i],
                $data_transmissao[$i],
                $enviada_ao_cliente[$i],
                $observacoes[$i],
                $valor_cobrado[$i],
                $boleto_enviado[$i],
                $pagamento[$i],
                $id[$i] // ID para a cláusula WHERE
            );

            // Executa a atualização
            if (!$stmt->execute()) {
                die("Erro ao executar a atualização para a linha $i: " . $stmt->error);
            }
        }

        // Fecha a declaração
        $stmt->close();

        // Redireciona de volta para a página de controle de clientes
        header('Location: buscar_cliente.php');
        exit();
    } else {
        die("Dados do formulário inválidos ou incompletos.");
    }
} else {
    die("Método de requisição inválido.");
}
?>
