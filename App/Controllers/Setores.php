<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Setor = new Setores;
    $Setor->$method($_POST);
}

class Setores
{
    private int $codigo;
    private string $nome;

    public function listSetoresJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;
            $stringPesquisa = isset($dados['stringPesquisa']) ? htmlspecialchars($dados['stringPesquisa'], ENT_QUOTES, "UTF-8") : null;
            $pegalistaDeSetores = new \App\Models\Setores(null);
            $pegalistaDeSetores->generalSearch($this->codigo, null, $stringPesquisa);
            if (!$pegalistaDeSetores->getResult()) {
                throw new Exception("Erro ao obter consulta de Setores " . $pegalistaDeSetores->getMessage(), 500);
            }
            $status = true;
            $response = $pegalistaDeSetores->getContent();
            http_response_code(200);
        } catch (Exception $th) {
            $status = false;
            $response = $th->getMessage();
            http_response_code($th->getCode());
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function controlarSetores($dados)
    {

        try {
            $this->codigo = !empty($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;
            $this->nome = isset($dados['nameSetor']) ? htmlspecialchars($dados['nameSetor'], ENT_QUOTES, "UTF-8") : '';

            if (empty($this->nome)) {
                throw new Exception("Preencha o campo Nome!");
            }

            $cad = new \App\Models\Setores(['CODIGO' => $this->codigo, 'NOME_SETOR' => $this->nome]);

            if ($this->codigo == 0) {
                $cad->inserirSetor();
            } else {
                $cad->alterarSetor();
            }

            if (!$cad->getResult()) {
                throw new Exception($cad->getMessage(), 500);
            }

            $status = true;
            $response = '';
            http_response_code(200);
        } catch (Exception $th) {
            $status = false;
            $response = $th->getMessage();
            http_response_code($th->getCode());
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function excluirSetores($dados)
    {

        try {
            $this->codigo = !empty($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;

            if ($this->codigo == 0) {
                throw new Exception("Erro ao processar Setor com código nulo", 500);
            }

            $cad = new \App\Models\Setores(["CODIGO" => $this->codigo]);
            $cad->excluirSetores();
            if (!$cad->getResult()) {
                throw new Exception($cad->getMessage(), 500);
            } 
            $status = true;
            $response = '';
        } catch (Exception $th) {
            $status = false;
            $response = $th->getMessage();
        }

        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function dataTable($dados){
        try {
            $pegalistaDeSetores = new \App\Models\Setores(null);
            $pegalistaDeSetores->dataTable($dados["draw"]);
            if (!$pegalistaDeSetores->getResult()) {
                throw new Exception($pegalistaDeSetores->getMessage(), 500);
            }
            $status = true;
            $response = $pegalistaDeSetores->getContent();
            http_response_code(200);
        } catch (Exception $th) {
            $status = false;
            $response = $th->getMessage();
            http_response_code($th->getCode());
        }
        $response = json_encode($response);
        //$response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }
}
