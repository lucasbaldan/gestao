<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

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
    $almoco = $dadosRelatorio[0]['ALMOCO'];
    $seg = $dadosRelatorio[0]['SEG'];
    $ter = $dadosRelatorio[0]['TER'];
    $qua = $dadosRelatorio[0]['QUA'];
    $qui = $dadosRelatorio[0]['QUI'];
    $sex = $dadosRelatorio[0]['SEX'];
    $dataFinalVinculo = $dadosRelatorio[0]['DATA_FINAL'];
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
                <td><b>SERVIDOR:</b> ' . strtoupper($nome) . ' <b>MATRÍCULA:</b> ' . strtoupper($matricula) . ' <b>FUNCÃO:</b>' . strtoupper($funcao) . '</td>
            </tr>
            <tr>
                <td><b>Horário de Trabalho: ' . strtoupper($horario) . '</b></td>
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

    if ("$anoRelatorio-$mesRelatorio-$dia" > $dataFinalVinculo) {
        $diaDaSemana = "SEM VINCULO";
        $hrEntradaSaida = "SEM VINCULO";
        $hrAlmoco = "SEM VINCULO";
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
