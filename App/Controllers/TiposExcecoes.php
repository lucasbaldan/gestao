<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

class TiposExcecoes
{
    private $codigo;
    private string $nome;

    public function listJSON($dados)
    {
        try {
            $this->nome = isset($dados['nmTipoExcecao']) ? $dados['nmTipoExcecao'] : '';
            $this->codigo = isset($dados['cdTipoExcecao']) ? $dados['cdTipoExcecao'] : '';

            $pegalista = new \App\Models\TiposExcecoes;
            $lista = $pegalista->listar($this->codigo, $this->nome);
            echo json_encode($lista);
        } catch (Exception $th) {
            echo json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function controlar($dados)
    {

        try {
            $this->codigo = isset($dados['cdTipoExcecao']) ? $dados['cdTipoExcecao'] : '';
            $this->nome = isset($dados['nameTipoExcecao']) ? $dados['nameTipoExcecao'] : '';

            if (empty($this->codigo)) {
                if(empty($this->nome)){
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\TiposExcecoes;
                $cad->setNome($this->nome);
                $cad->inserir();
                if ($cad->getResult() == true) {
                    echo 'inserido';
                } else {
                    echo 'erro';
                }
            } else {
                if(empty($this->nome)){
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\TiposExcecoes;
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
            echo 'erro';
        }
    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdTipoExcecao']) ? $dados['cdTipoExcecao'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\TiposExcecoes;
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
    $TipoExcecao = new TiposExcecoes;
    $TipoExcecao->$method($_POST);
}
