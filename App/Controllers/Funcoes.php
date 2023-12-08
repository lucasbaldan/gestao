<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Funcao = new Funcoes;
    $Funcao->$method($_POST);
}

class Funcoes
{
    private $codigo;
    private string $nome;

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : null;
            $stringPesquisa = isset($dados['stringPesquisa']) ? $dados['stringPesquisa'] : null;

            $Funcoes = new \App\Models\Funcoes(null);
            $Funcoes->listar($this->codigo, $stringPesquisa);
            if (!$Funcoes->getResult()) {
                throw new Exception("Erro ao executar consulta no banco de dados! </br> " . $Funcoes->getMessage(), 500);
            }
            $status = true;
            $response = $Funcoes->getContent();
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

    public function controlar($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : '';
            $this->nome = isset($dados['nameFuncao']) ? $dados['nameFuncao'] : '';

            if (empty($this->nome)) {
                throw new Exception("Preencha o campo Nome");
            }

            $cad = new \App\Models\Funcoes(["CODIGO" => $this->codigo, "NOME_FUNCAO" => $this->nome]);

            if (empty($this->codigo)) {

                $cad->inserir();
                if (!$cad->getResult()) {
                    throw new Exception($cad->getMessage(), 500);
                }
            } else {

                $cad->alterar();
                if (!$cad->getResult()) {
                    throw new Exception($cad->getResult(), 500);
                }
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

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro ao processar exclusão com Função de código nulo");
            }

            $cad = new \App\Models\Funcoes(["CODIGO" => $this->codigo]);
            $cad->excluir();
            if (!$cad->getResult()) {
                throw new Exception($cad->getResult(), 500);
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
}
