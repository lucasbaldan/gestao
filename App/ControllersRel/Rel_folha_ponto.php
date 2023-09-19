<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo'])) {
    $method = $_POST['metodo'];
    $Funcionario = new \App\Models\Funcionarios;
    $dadosRelatorio = $Funcionario->$method($_POST);
}

setlocale(LC_TIME, 'pt_BR');

$nome = $dadosRelatorio[0]['NOME'];
$matricula = $dadosRelatorio[0]['MATRICULA'];
$funcao = $dadosRelatorio[0]['NM_FUNCAO'];
$horario = $dadosRelatorio[0]['DESC_HR_TRABALHO'];
list($anoRelatorio, $mesRelatorio) = explode("-", $_POST['mesRelatorio']);

//echo print_r($nome);

$html = '<html>
<style>
    *{
        margin: 0;
        padding: 0;
    }
    html{
        margin-top: 30px;
        margin-left: 30px;
        margin-right: 30px;
    }
    body{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10;
    }
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
        margin-left: 161px;
    }

    tr {
        vertical-align: middle;
    }

    .quadroPontoFuncionario {
        margin-top: 5px;
    }
    .pagebreak { 
        page-break-after: always; 
    }

</style>

<DIV class="tituloRelatorio"><u><b>FICHA DE PONTO DIÁRIO</b></u></DIV>

<body>
    <div class="quadroInfoFuncionario">
        <table>
            <tr>
                <td><b>SERVIDOR:</b> ' . strtoupper($nome) . ' <b>MATRÍCULA:</b> ' . strtoupper($matricula) . ' <b>FUNCÃO:</b>' . strtoupper($funcao) . '</td>
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


$diasMes = cal_days_in_month(CAL_GREGORIAN, $mesRelatorio, $anoRelatorio);

for ($dia = 1; $dia <= $diasMes; $dia++) {

    $data = $dia <= 9 ?  "$anoRelatorio-$mesRelatorio-" . 0 . "$dia" : "$anoRelatorio-$mesRelatorio-$dia";

    foreach ($dadosRelatorio as $dadoVinculoFuncional) {

        if ($data >= $dadoVinculoFuncional["DATA_INICIAL"] && $data <= $dadoVinculoFuncional["DATA_FINAL"]) {

            $almoco = $dadoVinculoFuncional['ALMOCO'];
            $seg = $dadoVinculoFuncional['SEG'];
            $ter = $dadoVinculoFuncional['TER'];
            $qua = $dadoVinculoFuncional['QUA'];
            $qui = $dadoVinculoFuncional['QUI'];
            $sex = $dadoVinculoFuncional['SEX'];
            $dataFinalVinculo = $dadoVinculoFuncional['DATA_FINAL'];
            $dataInicialVinculo = $dadoVinculoFuncional['DATA_INICIAL'];



            /////////////////////////////////////////////////////////////////////////////////////////

            if ($dataFinalVinculo < $data) {

                $diaDaSemana = "TERMINOU VINCULO";
                $hrEntradaSaida = "TERMINOU VINCULO";
                $hrAlmoco = "TERMINOU VINCULO";
            } else if ($dataInicialVinculo > $data) {

                $diaDaSemana = "NÃO COMEÇOU VINCULO";
                $hrEntradaSaida = "NÃO COMEÇOU VINCULO";
                $hrAlmoco = "NÃO COMEÇOU VINCULO";
            } else {

                $diaDaSemana = date('w', strtotime("$anoRelatorio-$mesRelatorio-$dia"));
                if ($diaDaSemana == 0) {
                    $diaDaSemana = "DOMINGO";
                    $hrEntradaSaida = "-------";
                    $hrAlmoco = "-------";
                } else if ($diaDaSemana == 6) {
                    $diaDaSemana = "SÁBADO";
                    $hrEntradaSaida = "-------";
                    $hrAlmoco = "-------";
                } else if (($diaDaSemana == 1 && $seg == 1) || ($diaDaSemana == 2 && $ter == 1) || ($diaDaSemana == 3 && $qua == 1) || ($diaDaSemana == 4 && $qui == 1) || ($diaDaSemana == 5 && $sex == 1)) {
                    $diaDaSemana = " ";
                    $hrEntradaSaida = " ";
                    $hrAlmoco = " ";
                } else {
                    $diaDaSemana = "-------";
                    $hrEntradaSaida = "-------";
                    $hrAlmoco = "------";
                }

                if ($almoco == 0) {
                    $hrAlmoco = "-------";
                }
            }

            $html .= ' <tr>
    <td>' . $dia . '</td>
     <td>' . $hrEntradaSaida . '</td>
     <td>' . $diaDaSemana . '</td>
     <td>' . $hrAlmoco . '</td>
     <td>' . $diaDaSemana . '</td>
     <td>' . $hrAlmoco . '</td>
     <td>' . $diaDaSemana . '</td>
     <td>' . $hrEntradaSaida . '</td>
     <td>' . $diaDaSemana . '</td>
     </tr>';
        }
    }
}


$html .= '</tr>
                <tr>
                    <td colspan="9" style="text-align: left">Obs:</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
<div class="pagebreak"></div>
</html>';

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
//$dompdf->setOptions(['margin-top' => 20, 'margin-right' => 10, 'margin-bottom' => 20, 'margin-left' => 10]);
$dompdf->render();
$dompdf->stream();

?>
<html>
   
</html>