<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

class Funcoes
{
    private $codigo;
    private string $nome;

    public function list()
    {

        try {
            $pegalista = new \App\Models\Funcoes;
            $lista = $pegalista->listar();
            return $lista;
        } catch (Exception $th) {
            return false;
        }
    }

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : '';

            $pegalista = new \App\Models\Funcoes;
            $lista = $pegalista->listar($this->codigo);
            echo json_encode($lista);
        } catch (Exception $th) {
            return json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function controlar($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncao']) ? $dados['cdFuncao'] : '';
            $this->nome = isset($dados['nameFuncao']) ? $dados['nameFuncao'] : '';

            if (empty($this->codigo) && empty($this->nome)) {
                throw new Exception("Campos Usuário e Senha não podem ser nulos!");
            }

            if (empty($this->codigo)) {

                $cad = new \App\Models\Funcoes;
                $cad->setNome($this->nome);
                $cad->inserir();
                if ($cad->getResult() == true) {
                    echo 'inserido';
                } else {
                    echo 'erro';
                }
            } else {

                $cad = new \App\Models\Funcoes;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);
                $cad->alterar();
                if ($cad->getResult() == true) {
                    echo 'alterado';
                } else {
                    echo 'erro';
                }
            }
        } catch (Exception $th) {
            echo 'erro operação';
        }
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
                echo 'excluido';
            } else {
                echo 'erro';
            }
        } catch (Exception $th) {
            echo 'erro operação';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Funcao = new Funcoes;
    $Funcao->$method($_POST);
}
