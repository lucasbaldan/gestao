<?php

namespace App\Controllers;

require __DIR__ . '/../../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['metodo'])) {
    $method = $_POST['metodo'];
    $Funcionario = new \App\Models\Funcionarios;
    $dadosRelatorio = $Funcionario->$method($_POST);
}

//echo var_dump($dadosRelatorio);
