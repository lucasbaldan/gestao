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

            $Funcoes = new \App\Models\Funcoes;
            $Funcoes->listar($this->codigo, $stringPesquisa);
            if(!$Funcoes->getResult()){
                throw new Exception("Erro ao executar consulta no banco de dados! </br> ".$Funcoes->getMessage(), 500);
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

            if (empty($this->codigo)) {
                if(empty($this->nome)){
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\Funcoes;
                $cad->setNome($this->nome);

                $duplicado = $cad->listar(null, $this->nome);
                if ($duplicado) {
                    throw new Exception("Registro já Cadastrado!");
                }

                $cad->inserir();
                if ($cad->getResult() == true) {
                    $status = 'inserido';
                } else {
                    $status = 'erro';
                }
            } else {
                if(empty($this->nome)){
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\Funcoes;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);

                $duplicado = $cad->listar(null, $this->nome);
                if ($duplicado && $duplicado[0]['CD_FUNCAO'] != $this->codigo) {
                    throw new Exception("Registro já Cadastrado!");
                }

                $cad->alterar();
                if ($cad->getResult() == true) {
                    $status = 'alterado';
                } else {
                    $status =  'erro';
                }
            }
            $response = null;
        } catch (Exception $th) {
            $status = 'erro';
            $response = $th->getMessage();
        }

        $response = json_encode(array("status" => $status, "response" => $response));
        echo $response;
    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Funcoes;
            $cad->setCodigo($this->codigo);
            $cad->excluir();
            if ($cad->getResult() == true) {
                $status = 'excluido';
                $response = '';
            } else {
                $status = 'erro';
                $response = $cad->getMessage();
            }
        } catch (Exception $th) {
            $status = 'erro operação';
            $response = '';
        }

        $response = json_encode(["status" => $status, "response" => $response]);
        echo $response;

    }
}
