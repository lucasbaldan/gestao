<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Funcionario = new \App\Models\Funcionarios;
    $Excecoes = new \App\Models\Excecoes;
    $matriculasSelecionadas = isset($_POST['to']) ? ($_POST['to']) : '';
    $html = '';
}

$nomesMeses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

foreach ($matriculasSelecionadas as $matriculas) {
    $dadosRelatorio = [];
    $dadosExcecoes = [];
    $dadosRelatorio = $Funcionario->gerarRelatorio($_POST['mesRelatorio'], $matriculas);
    $dadosExcecoes = $Excecoes->selectExcecoesRelatorio($_POST['mesRelatorio'], $matriculas);

    $nome = $dadosRelatorio[0]['NOME'];
    $matricula = $dadosRelatorio[0]['MATRICULA'];
    $funcao = $dadosRelatorio[0]['NM_FUNCAO'];
    $horario = $dadosRelatorio[0]['DESC_HR_TRABALHO'];
    list($anoRelatorio, $mesRelatorio) = explode("-", $_POST['mesRelatorio']);

    $html .= '<html>
    <style>
    * {
        margin: 0;
        padding: 0;
    }

    html {
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table
    th,
    td {
        border: 1px solid black;
        overflow: auto;
        text-align: center;
    }

    .tituloRelatorio {
        text-align: center;
        font-size: 15px;
    }

    .cabecalho {
        margin-top: 20px;
    }

    .cabecalho img {
        max-width: 100px;
        height: auto;
        float: left; /* Alinha a imagem à esquerda */
        margin-left: 28px; 
    }

    .tableInfoFuncionario td{
        height: 20px
    }

    .quadroInfoFuncionario {
        width: 84,5%;
        margin-left: 111px;
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

<div class="tituloRelatorio"><u><b>FICHA DE PONTO DIÁRIO</b></u></div>

<body>
<div class="cabecalho">
<img src="http://localhost/gestao/public/img/brasaoPM.png">
    <div class="quadroInfoFuncionario">
        <table class="tableInfoFuncionario">
            <tr>
                <td><b>SERVIDOR:</b> ' . mb_strtoupper($nome, 'UTF-8'). '&nbsp;&nbsp;&nbsp; <b>MATRÍCULA:</b> ' . mb_strtoupper($matricula, 'UTF-8') . '&nbsp;&nbsp;&nbsp; <b>FUNCÃO:</b>' . mb_strtoupper($funcao, 'UTF-8') . '</td>
            </tr>
            <tr>
                <td><b>Horário de Trabalho: ' . mb_convert_encoding($horario, 'UTF-8', 'auto') . '</b></td>
            </tr>
            <tr>
                <td>Mês de <b>' . $nomesMeses[ltrim($mesRelatorio, '0')]  . '</b> de ' . $anoRelatorio . '</td>
            </tr>
        </table>
    </div>
    </div>
    <div class="quadroPontoFuncionario">
        <table>
        <thead style="font-size: 10px;">
        <tr>
            <td style="width: 30px;">DIA</td>
            <td style="width: 58px;">HORA de Entrada</td>
            <td>ASSINATURA</td>
            <td style="width: 58px;">HORA saída do almoço</td>
            <td>ASSINATURA</td>
            <td style="width: 58px;">HORA retorno do almoço</td>
            <td>ASSINATURA</td>
            <td style="width: 58px;">HORA da Saída</td>
            <td>ASSINATURA</td>
        </tr>
    </thead>
            <tbody style="font-size: 9px;">';


    $diasMes = cal_days_in_month(CAL_GREGORIAN, $mesRelatorio, $anoRelatorio);

    for ($dia = 1; $dia <= $diasMes; $dia++) {

        $data = $dia <= 9 ?  "$anoRelatorio-$mesRelatorio-" . 0 . "$dia" : "$anoRelatorio-$mesRelatorio-$dia";

        foreach ($dadosRelatorio as $dadoVinculoFuncional) {

            if ($data >= $dadoVinculoFuncional["DATA_INICIAL"] && ($data <= $dadoVinculoFuncional["DATA_FINAL"] || $dadoVinculoFuncional["DATA_FINAL"] == null)) {

                $almoco = $dadoVinculoFuncional['ALMOCO'];
                $seg = $dadoVinculoFuncional['SEG'];
                $ter = $dadoVinculoFuncional['TER'];
                $qua = $dadoVinculoFuncional['QUA'];
                $qui = $dadoVinculoFuncional['QUI'];
                $sex = $dadoVinculoFuncional['SEX'];
                $dataFinalVinculo = $dadoVinculoFuncional['DATA_FINAL'];
                $dataInicialVinculo = $dadoVinculoFuncional['DATA_INICIAL'];

                /////////////////////////////////////////////////////////////////////////////////////////

                // if ($dataFinalVinculo < $data) {

                //     $diaDaSemana = "TERMINOU VINCULO";
                //     $hrEntradaSaida = "TERMINOU VINCULO";
                //     $hrAlmoco = "TERMINOU VINCULO";
                // } else if ($dataInicialVinculo > $data) {

                //     $diaDaSemana = "NÃO COMEÇOU VINCULO";
                //     $hrEntradaSaida = "NÃO COMEÇOU VINCULO";
                //     $hrAlmoco = "NÃO COMEÇOU VINCULO";
                // } else {

                $diaDaSemana = date('w', strtotime($data));
                if ($diaDaSemana == 0) {
                    $diaDaSemana = "DOMINGO";
                    $hrEntradaSaida = "-------";
                    $hrAlmoco = "-------";
                    goto montadia;
                } else if ($diaDaSemana == 6) {
                    $diaDaSemana = "SÁBADO";
                    $hrEntradaSaida = "-------";
                    $hrAlmoco = "-------";
                    goto montadia;
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

                if (isset($dadosExcecoes)) {
                    foreach ($dadosExcecoes as $excecoes) {
                        if (($data >= $excecoes['DATA_INICIAL'] && $data <= $excecoes['DATA_FINAL']) || ($data == $excecoes['DATA_INICIAL'] && $excecoes['DATA_FINAL'] == null)) {
                            $diaDaSemana = $excecoes['NM_TIPO_EXCECAO'];
                            $hrEntradaSaida = "-------";
                            $hrAlmoco = "-------";
                        }
                    }
                }

                // }
                montadia:
                $html .= ' <tr>
    <td style="height: 18px">' . $dia . '</td>
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
</body>';

    if (!($matriculas === end($matriculasSelecionadas))) {
        $html .= '<div class="pagebreak"></div>';
    } else {
        $html .= '</html>';
    }
}
$dompdf = new Dompdf(['enable_remote' => true]);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("Folha Ponto ".$_POST['mesRelatorio'].".pdf");
