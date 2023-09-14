<?php

namespace App\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use Dompdf\Dompdf;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo'])) {
    $method = $_POST['metodo'];
    $Funcionario = new \App\Models\Funcionarios;
    $dadosRelatorio = $Funcionario->$method($_POST);
}

//echo var_dump($dadosRelatorio);

$dompdf = new Dompdf();
$dompdf->loadHtml('hello world no dompdf');
$dompdf->setPaper('A4','portrait');
$dompdf->render();
$dompdf->stream();

