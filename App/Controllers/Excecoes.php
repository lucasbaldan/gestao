<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Error;
use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Excecao = new Excecoes;
    $Excecao->$method($_POST);
}

class Excecoes
{
    private $codigo;
    private $data;
    private $dataFinal;
    private $tpExcecao;
    private $funcionarios_selecionados;

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdExcecao']) ? filter_input(INPUT_POST, 'cdExcecao', FILTER_SANITIZE_NUMBER_INT) : null;
            $gridFormat = isset($dados['GridFormat']) ? $dados['GridFormat'] : false;

            $pegalista = new \App\Models\Excecoes;
            $pegalista->setCodigo($this->codigo);
            $lista = $pegalista->generalSearch(null, $gridFormat);
            http_response_code(200);
            $status = true;
            $response = $lista;
        } catch (Exception $th) {
            http_response_code(500);
            $status = false;
            $response = $th->getMessage();
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function controlar($dados)
    {
        try {
            $this->codigo = isset($dados['cdExcecao']) ? $dados['cdExcecao'] : '';
            $this->data = !empty($dados['dataExcecao']) ? date("Y-m-d", strtotime(str_replace('/', '-', $dados['dataExcecao']))) : '';
            $this->dataFinal = !empty($dados['dataFinal']) ? date("Y-m-d", strtotime(str_replace('/', '-', $dados['dataFinal']))) : '';
            $this->tpExcecao = isset($dados['tipoExcecao']) ? $dados['tipoExcecao'] : '';
            $this->funcionarios_selecionados = isset($dados['to']) ? ($dados['to']) : '';

            if (empty($this->data) || empty($this->tpExcecao) || (empty($this->funcionarios_selecionados) && !isset($this->codigo))) {
                throw new Exception("Erro ao processar a operação, tente novamente mais tarde!");
            }

            if (empty($this->codigo)) {

                $conn = \App\Conn\Conn::getConn(true);
                $insert = new \App\Conn\Insert($conn);

                $cad = new \App\Models\Excecoes;
                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);

                foreach ($this->funcionarios_selecionados as $funcionario) {

                    $verificaDuplicidade = $cad->verificaDuplicidade(intval($funcionario), $this->data, $this->dataFinal);
                    if ($verificaDuplicidade) {
                        throw new Exception("Já existe uma exceção Cadastrada entre as datas informadas para o funcionário");
                    }

                    $cad->setFuncionario(intval($funcionario));
                    $cad->inserir($insert);

                    if (!$cad->getResult()) {
                        throw new Exception($cad->getMessage());
                    }
                }
                $insert->Commit();
                $status = 'inserido';
                $response = '';
            } else {

                $cad = new \App\Models\Excecoes;
                $cad->setCodigo($this->codigo);
                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);

                $verificaDuplicidade = $cad->generalSearch("E.CD_EXCECAO", false, true);
                if ($verificaDuplicidade[0]["CD_EXCECAO"] != $this->codigo) {
                    throw new Exception("Já existe uma exceção Cadastrada entre as datas informadas para o funcionário");
                }

                $cad->alterar();

                if ($cad->getResult()) {
                    $status = 'alterado';
                    $response = '';
                } else {
                    $status = 'erro';
                    $response = $cad->getMessage();
                }
            }
        } catch (Exception $th) {
            $status = 'erro';
            $response = $th->getMessage();
        }

        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdExcecao']) ? $dados['cdExcecao'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro ao processar requisição. Tente novamente!");
            }

            $cad = new \App\Models\Excecoes;
            $cad->setCodigo($this->codigo);
            $cad->excluir();
            if ($cad->getResult()) {
                $status = 'excluido';
                $response = '';
            } else {
                $status = 'erro';
                $response = $cad->getMessage();
            }
        } catch (Exception $th) {
            $status = 'erro';
            $respose = $th->getMessage();
        }

        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }
}
