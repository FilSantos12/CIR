<?php
// Inicia o sistema de log
ini_set('log_errors', 1); // Ativa o log de erros
ini_set('error_log', __DIR__ . '/log.txt'); // Define o arquivo de log

// Função para registrar mensagens no log
function logMessage($message) {
    error_log($message);
}

logMessage('Script iniciado.');

// Verifica se o autoload do Composer e o arquivo de conexão com o banco existem
if (!file_exists('vendor/autoload.php')) {
    logMessage('Erro: Arquivo vendor/autoload.php não encontrado. Execute "composer install".');
    echo 'Erro: Arquivo vendor/autoload.php não encontrado. Execute "composer install".';
    exit();
}

if (!file_exists('db_connection.php')) {
    logMessage('Erro: Arquivo db_connection.php não encontrado.');
    echo 'Erro: Arquivo db_connection.php não encontrado.';
    exit();
}

require 'vendor/autoload.php'; // Carrega o autoload do Composer
require 'db_connection.php';   // Inclui o arquivo de conexão com o banco

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

// Obtendo o formato da URL
$formato = isset($_GET['formato']) ? strtolower($_GET['formato']) : '';

// Verifica se o formato é válido
if (empty($formato)) {
    logMessage('Erro: Parâmetro "formato" ausente na URL.');
    echo 'Por favor, forneça o parâmetro "formato" na URL (xlsx ou pdf).';
    exit();
}

if (!in_array($formato, ['xlsx', 'pdf'])) {
    logMessage('Erro: Formato inválido: ' . $formato);
    echo 'Formato inválido. Por favor, escolha entre "xlsx" ou "pdf".';
    exit();
}

try {
    logMessage('Iniciando a geração do relatório...');

    // Criando a planilha (para XLSX e PDF)
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Definir os cabeçalhos das colunas
    $cabecalhos = [
        'B1' => 'Nome Cliente', 'C1' => 'Data Solicitação', 
        'D1' => 'Tipo', 'E1' => 'Documentos', 'F1' => 'Conferência', 
        'G1' => 'Imposto a Pagar', 'H1' => 'Doação', 'I1' => 'Dados Doação', 
        'J1' => 'Parcelamento', 'K1' => 'Imposto a Restituir', 'L1' => 'Transmissão', 
        'M1' => 'Data Transmissão', 'N1' => 'Enviada ao Cliente', 'O1' => 'Observações', 
        'P1' => 'Valor Cobrado', 'Q1' => 'Boleto Enviado', 'R1' => 'Pagamento', 
        'S1' => 'CPF Cliente', 'T1' => 'Senha GovBr', 'U1' => 'Procuração'
    ];

    foreach ($cabecalhos as $coluna => $titulo) {
        $sheet->setCellValue($coluna, $titulo);
    }

    logMessage('Cabeçalhos da planilha definidos.');

    // Consulta SQL para buscar os dados
    $sql = "SELECT 
                c.nomeCliente, p.data_solicitacao, p.tipo, p.documentos, p.conferencia, 
                p.imposto_pagar, p.doacao, p.dados_doacao, p.parcelamento, p.imposto_restituir, 
                p.transmissao, p.data_transmissao, p.enviada_cliente, p.observacoes, 
                p.valor_cobrado, p.boleto_enviado, p.pagamento, c.cpf, c.senhaGovBr, c.procuracao
            FROM processos p
            LEFT JOIN cliente c ON p.id_cliente = c.id";

    logMessage('Executando consulta SQL...');
    $result = $conn->query($sql);

    if ($result === false) {
        $errorMessage = 'Erro na consulta SQL: ' . $conn->error;
        logMessage($errorMessage);
        echo $errorMessage;
        exit();
    }

    logMessage('Consulta SQL executada com sucesso.');

    $rowNum = 2; // Começar na linha 2, pois a linha 1 tem os cabeçalhos

    $dadosHTML = '<table border="1" width="100%">
        <tr>';
    foreach ($cabecalhos as $titulo) {
        $dadosHTML .= "<th>{$titulo}</th>";
    }
    $dadosHTML .= '</tr>';

    if ($result->num_rows > 0) {
        logMessage('Preenchendo a planilha e o HTML com os dados...');
        while ($row = $result->fetch_assoc()) {
            $coluna = 'A';
            foreach ($row as $valor) {
                $sheet->setCellValue($coluna . $rowNum, $valor);
                $coluna++;
            }

            // Construindo o conteúdo para o PDF
            $dadosHTML .= '<tr>';
            foreach ($row as $valor) {
                $dadosHTML .= "<td>{$valor}</td>";
            }
            $dadosHTML .= '</tr>';
            
            $rowNum++;
        }
        logMessage('Dados preenchidos com sucesso.');
    } else {
        $message = 'Nenhum dado encontrado para gerar o relatório.';
        logMessage($message);
        echo $message;
        exit();
    }
    $dadosHTML .= '</table>';

    if ($formato == 'xlsx') {
        logMessage('Gerando arquivo Excel...');
        // Gerar arquivo Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'relatorio_processos.xlsx';

        // Definir cabeçalhos para forçar o download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Salvar o arquivo Excel no output
        $writer->save('php://output');
        logMessage('Arquivo Excel gerado com sucesso.');
        exit();
    } elseif ($formato == 'pdf') {
    logMessage('Gerando PDF...');

    // Gerar PDF
    $options = new Options();
    $options->set('defaultFont', 'Arial'); // Define a fonte padrão
    $options->set('isRemoteEnabled', true); // Permite carregar recursos remotos (se necessário)

    $dompdf = new Dompdf($options);

    // CSS para ajustar o layout da tabela
    $css = "
        <style>
            body { font-family: Arial, sans-serif; font-size: 8px; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #000; padding: 4px; text-align: left; }
            th { background-color: #f2f2f2; }
            h2 { text-align: center; font-size: 12px; }
        </style>
    ";

    // HTML com o conteúdo do relatório
    $html = "<h2>Relatório de Processos</h2>" . $dadosHTML;

    // Carrega o HTML no Dompdf
    $dompdf->loadHtml($css . $html);

    // Define o tamanho do papel e a orientação (paisagem)
    $dompdf->setPaper('A4', 'landscape');

    // Renderiza o PDF
    $dompdf->render();

    // Enviar o PDF para download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="relatorio_processos.pdf"');
    echo $dompdf->output();
    logMessage('PDF gerado com sucesso.');
    exit();
    }
} catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
    $errorMessage = 'Erro ao gerar o relatório em Excel: ' . $e->getMessage();
    logMessage($errorMessage);
    echo $errorMessage;
    exit();
} catch (\Dompdf\Exception $e) {
    $errorMessage = 'Erro ao gerar o relatório em PDF: ' . $e->getMessage();
    logMessage($errorMessage);
    echo $errorMessage;
    exit();
} catch (\Exception $e) {
    $errorMessage = 'Erro inesperado: ' . $e->getMessage();
    logMessage($errorMessage);
    echo $errorMessage;
    exit();
}
?>