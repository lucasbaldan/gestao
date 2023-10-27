<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $TipoExcecao = new TiposExcecoes;
    $TipoExcecao->$method($_POST);
}

class TiposExcecoes
{
    private $codigo;
    private string $nome;

    public function listJSON($dados)
    {
        try {
            $this->nome = isset($dados['nmTipoExcecao']) ? $dados['nmTipoExcecao'] : '';
            $this->codigo = isset($dados['cdTipoExcecao']) ? $dados['cdTipoExcecao'] : '';
            $pesquisaLIKE = isset($dados['stringPesquisa']) ? $dados['stringPesquisa']: null;

            $pegalista = new \App\Models\TiposExcecoes;
            $lista = $pegalista->generalSearch(null, $this->codigo, $this->nome, $pesquisaLIKE);
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
                if (empty($this->nome)) {
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\TiposExcecoes;
                $cad->setNome($this->nome);

                $duplicado = $cad->generalSearch(null, null, $this->nome);
                if ($duplicado) {
                    throw new Exception("Registro já Cadastrado!");
                }
                $cad->inserir();
                if ($cad->getResult() == true) {
                    $status =  'inserido';
                } else {
                    $status = 'erro';
                }
            } else {
                if (empty($this->nome)) {
                    throw new Exception("Preencha o campo Nome");
                }

                $cad = new \App\Models\TiposExcecoes;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);

                $duplicado = $cad->generalSearch(null, null, $this->nome);
                if ($duplicado && $duplicado[0]['CD_TIPO_EXCECAO'] != $this->codigo) {
                    throw new Exception("Registro já Cadastrado!");
                }
                $cad->alterar();
                if ($cad->getResult() == true) {
                    $status = 'alterado';
                } else {
                    $status = 'erro';
                }
            }
            $resposta = null;
        } catch (Exception $th) {
            $status = 'erro';
            $resposta = $th->getMessage();
        }
        $response = json_encode(array('status' => $status, 'response' => $resposta));
        echo $response;
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
                $status = 'excluido';
                $response = '';
            } else {
                $status = 'erro';
                $response = $cad->getMessage();
            }
        } catch (Exception $th) {
            $status = 'erro operação';
        }
        $response = json_encode(['status' => $status, 'response' => $response]);
        echo $response;
    }
}