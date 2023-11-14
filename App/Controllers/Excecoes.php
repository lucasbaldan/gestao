<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use DateTime;
use Error;
use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Excecao = new Excecoes;
    $Excecao->$method($_POST);
} else {
    header("Location: /gestao/public/pages/generalError.php");
    exit;
    die;
}

class Excecoes
{
    private int $codigo;
    private string $data;
    private string $dataFinal;
    private int $tpExcecao;
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
            $this->dataFinal = !empty($dados['dataFinal']) ? \App\Helppers\DateFormat::ConverteBRtoDB(htmlspecialchars($dados['dataFinal'], ENT_QUOTES, "UTF-8")) : '';
            $this->tpExcecao = !empty($dados['tipoExcecao']) ? filter_input(INPUT_POST, 'tipoExcecao', FILTER_SANITIZE_NUMBER_INT) : 0;
            $this->funcionarios_selecionados = isset($dados['to']) ? ($dados['to']) : '';

            if (empty($this->data) || $this->tpExcecao == 0 || (empty($this->funcionarios_selecionados) && $this->codigo == 0)) {
                throw new Exception("Erro ao processar a operação, tente novamente mais tarde!", 500);
            }
            if (!empty($this->dataFinal) && $this->dataFinal < $this->data) {
                throw new Exception("A data final não pode ser inferior a data inicial", 400);
            }

            $cad = new \App\Models\Excecoes;
            $conn = \App\Conn\Conn::getConn(true);


            if (empty($this->codigo)) {
                $insert = new \App\Conn\Insert($conn);

                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);

                foreach ($this->funcionarios_selecionados as $funcionario) {

                    $verificaDuplicidade = $cad->verificaDuplicidade($this->data, $this->dataFinal, intval($funcionario));
                    if ($verificaDuplicidade) {
                        throw new Exception("Já existe uma exceção Cadastrada entre as datas informadas para o funcionário: <b>" . $verificaDuplicidade[0]['NM_FUNCIONARIO'] . "</b>", 400);
                    }

                    $cad->setFuncionario(intval($funcionario));
                    $cad->inserir($insert);

                    if (!$cad->getResult()) {
                        throw new Exception($cad->getMessage(), 500);
                    }
                }
                $insert->Commit();
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

                if (!$cad->getResult()) {
                    $status = 'erro';
                    $response = $cad->getMessage();
                }
            }
            http_response_code(200);
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
