<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

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
            $this->codigo = isset($dados['cdExcecao']) ? $dados['cdExcecao'] : '';

            $pegalista = new \App\Models\Excecoes;
            $lista = $pegalista->listar($this->codigo);
            echo json_encode($lista);
        } catch (Exception $th) {
            return json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function controlar($dados)
    {
        try {
            $this->codigo = isset($dados['cdExcecao']) ? $dados['cdExcecao'] : '';
            $this->data = isset($dados['dataExcecao']) ? date("Y-m-d", strtotime(str_replace('/', '-', $dados['dataExcecao']))) : '';
            $this->dataFinal = !empty($dados['dataFinal']) ? date("Y-m-d", strtotime(str_replace('/', '-', $dados['dataFinal']))) : '';
            $this->tpExcecao = isset($dados['tipoExcecao']) ? $dados['tipoExcecao'] : '';
            $this->funcionarios_selecionados = isset($dados['to']) ? ($dados['to']) : '';

            if (empty($this->data) || empty($this->tpExcecao) || (empty($this->funcionarios_selecionados) && !isset($this->codigo))) {
                throw new Exception("Erro ao processa a operação, tente novamente mais tarde!");
            }

            if (empty($this->codigo)) {
                $conn = \App\Conn\Conn::getConn(true);
                $insert = new \App\Conn\Insert($conn);

                foreach ($this->funcionarios_selecionados as $funcionario) {

                    $cad = new \App\Models\Excecoes;
                    $cad->setData($this->data);
                    $cad->setDataFinal($this->dataFinal);
                    $cad->setTipoExcecao($this->tpExcecao);
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
