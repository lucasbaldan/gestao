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
    private int $codigo;
    private $data;
    private $dataFinal;
    private $tpExcecao;
    private $funcionarios_selecionados;

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdExcecao']) ? filter_input(INPUT_POST, 'cdExcecao', FILTER_SANITIZE_NUMBER_INT) : 0;
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
            $this->codigo = !empty($dados['cdExcecao']) ? filter_input(INPUT_POST, 'cdExcecao', FILTER_SANITIZE_NUMBER_INT) : 0;
            $this->data = !empty($dados['dataExcecao']) ? \App\Helppers\DateFormat::ConverteBRtoDB(htmlspecialchars($dados['dataExcecao'], ENT_QUOTES, "UTF-8")) : '';
            $this->dataFinal = !empty($dados['dataFinal']) ? date("Y-m-d", strtotime(str_replace('/', '-', $dados['dataFinal']))) : '';
            $this->tpExcecao = isset($dados['tipoExcecao']) ? $dados['tipoExcecao'] : '';
            $this->funcionarios_selecionados = isset($dados['to']) ? ($dados['to']) : '';

            if (empty($this->data) || empty($this->tpExcecao) || (empty($this->funcionarios_selecionados) && !isset($this->codigo))) {
                throw new Exception("Erro ao processar a operação, tente novamente mais tarde!");
            }
            if(!empty($this->dataFinal) && $this->dataFinal < $this->data){
                throw new Exception("A data final não pode ser inferior a data inicial");
            }

            $cad = new \App\Models\Excecoes;


            if (empty($this->codigo)) {

                $conn = \App\Conn\Conn::getConn(true);
                $insert = new \App\Conn\Insert($conn);

                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);

                foreach ($this->funcionarios_selecionados as $funcionario) {

                    $verificaDuplicidade = $cad->verificaDuplicidade($this->data, $this->dataFinal, intval($funcionario));
                    if ($verificaDuplicidade) {
                        throw new Exception("Já existe uma exceção Cadastrada entre as datas informadas para o funcionário: <b>".$verificaDuplicidade[0]['NM_FUNCIONARIO']."</b>");
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

                $cad->setCodigo($this->codigo);
                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);

                $verificaDuplicidade = $cad->verificaDuplicidade($this->data, $this->dataFinal, $this->funcionarios_selecionados[0]);
                if ($verificaDuplicidade && $verificaDuplicidade[0]["CD_EXCECAO"] != $this->codigo) {
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
            $this->codigo = isset($dados['cdExcecao']) ? filter_input(INPUT_POST, 'cdExcecao', FILTER_SANITIZE_NUMBER_INT) : 0;

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
