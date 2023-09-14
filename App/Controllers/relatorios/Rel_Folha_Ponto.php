<?php

namespace App\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use DateTime;
use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo'])) {
    $method = $_POST['metodo'];
    $Funcionario = new \App\Models\Funcionarios;
    $dadosRelatorio = $Funcionario->$method($_POST);

    setlocale(LC_TIME, 'pt_BR');


    $nome = $dadosRelatorio[0]['NOME'];
    $matricula = $dadosRelatorio[0]['MATRICULA'];
    $funcao = $dadosRelatorio[0]['NM_FUNCAO'];
    $horario = $dadosRelatorio[0]['DESC_HR_TRABALHO'];
    list($anoRelatorio, $mesRelatorio) = explode("-", $_POST['mesRelatorio']);

    $diasMes = cal_days_in_month(CAL_GREGORIAN, $mesRelatorio, $anoRelatorio);

}

//echo print_r($nome);

$html = '<html>
<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
        max-height: 100px;
        overflow: auto;
        text-align: center;
    }

    .tituloRelatorio {
        text-align: center;
    }

    .quadroInfoFuncionario {
        margin-top: 20px;
    }

    tr {
        vertical-align: middle;
    }

    .quadroPontoFuncionario {
        margin-top: 10px;
    }
</style>

<DIV class="tituloRelatorio"><u><b>FICHA DE PONTO DIÁRIO</b></u></DIV>

<body>
    <div class="quadroInfoFuncionario">
        <table>
            <tr>
                <td><b>SERVIDOR:</b> ' . $nome . ' <b>MATRÍCULA:</b> ' . $matricula . ' <b>FUNCÃO:</b>' . $funcao . '</td>
            </tr>
            <tr>
                <td><b>Horário de Trabalho: ' . $horario . '</b></td>
            </tr>
            <tr>
                <td>Mês de <b>' . $mesRelatorio . '</b> de ' . $anoRelatorio . '</td>
            </tr>
        </table>
    </div>
    <div class="quadroPontoFuncionario">
        <table>
            <thead>
                <tr>
                    <td>DIA</td>
                    <td>HORA de Entrada</td>
                    <td>ASSINATURA</td>
                    <td>HORA saída do almoço</td>
                    <td>ASSINATURA</td>
                    <td>HORA retorno do almoço</td>
                    <td>ASSINATURA</td>
                    <td>HORA da Saída</td>
                    <td>ASSINATURA</td>
                </tr>
            </thead>
            <tbody>';

for ($dia = 1; $dia <= $diasMes; $dia++) {
    $diaDaSemana = date('w', strtotime("$anoRelatorio-$mesRelatorio-$dia"));
    if($diaDaSemana == 0 ){
        $diaDaSemana = "DOMINGO";
    }
    else if($diaDaSemana == 6){
        $diaDaSemana = "SÁBADO";
    }
    else {
        $diaDaSemana = " ";
    }

    $html .= ' <tr>
    <td>'.$dia.'</td>
     <td>---</td>
     <td>'.$diaDaSemana.'</td>
     <td>---</td>
     <td>'.$diaDaSemana.'</td>
     <td>---</td>
     <td>'.$diaDaSemana.'</td>
     <td>---</td>
     <td>'.$diaDaSemana.'</td>
     </tr>';
}



$html .= '</tr>
                <tr>
                    <td colspan="9" style="text-align: left">Obs:</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream();
