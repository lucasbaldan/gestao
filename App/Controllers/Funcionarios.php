<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Funcionario = new Funcionarios;
    $Funcionario->$method($_POST);
} else {
    header("Location: /gestao/public/pages/generalError.php");
    exit;
    die;
}

class Funcionarios
{
    private $codigo;
    private $nome;
    private $setor;
    //private $vinculosFuncionais;


    public function listRelFuncionario($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';
            $dataSelect = isset($dados['mesRelatorio']) ? $dados['mesRelatorio'] : '';
            $setor = isset($dados['cdSetor']) ? $dados['cdSetor'] : '';

            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listarTelaRelatorio($this->codigo, $dataSelect, $setor);
            $status = true;
            $response = $lista;
            http_response_code(200);
        } catch (Exception $th) {
            $status = false;
            $response = "Tente novamente mais Tarde! <br>" . $th->getMessage();
            http_response_code(500);
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listar($this->codigo);
            $status = true;
            $response = $lista;
            http_response_code(200);
        } catch (Exception $th) {
            $status = false;
            $response = "Tente novamente mais Tarde!  <br>" . $th->getMessage();
            http_response_code(500);
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function controlar($dadosFuncionario)
    {

        try {
            //$this->vinculosFuncionais = isset($infoFuncionario['vinculosFuncionais']) ? $infoFuncionario['vinculosFuncionais'] : '';
            $this->codigo = isset($dadosFuncionario['cdFuncionario']) ? $dadosFuncionario['cdFuncionario'] : '';
            $this->nome = isset($dadosFuncionario['nomeFuncionario']) ? $dadosFuncionario['nomeFuncionario'] : '';
            $this->setor = isset($dadosFuncionario['selectSetor']) ? $dadosFuncionario['selectSetor'] : '';

            if (empty($this->nome)) {
                throw new Exception("Preencha do campo NOME!", 400);
            }
            if (empty($this->setor)) {
                throw new Exception("Selecione o campo Setor!", 400);
            }

            $conn = \App\Conn\Conn::getConn(true);

            if (empty($this->codigo)) {
                $op = new \App\Conn\Insert($conn);

                $cad = new \App\Models\Funcionarios;
                $cad->setNome($this->nome);
                $cad->setSetor($this->setor);
                $cad->inserirFuncionario($op);

                if (!$cad->getResult()) {
                    throw new Exception("Erro ao cadastrar Funcionário" . $cad->getMessage(), 500);
                }

                $idFuncionarioInserido = $op->getLastInsert();

            } else {
                $op = new \App\Conn\Update($conn);

                $cad = new \App\Models\Funcionarios;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);
                $cad->setSetor($this->setor);
                $cad->alterarFuncionario($op);

                if (!$cad->getResult()) {
                    throw new Exception("Erro ao atualizar Funcionário " . $cad->getMessage(), 500);
                }

            }
            $op->Commit();
            $status = true;
            $response = '';
            http_response_code(200);
        } catch (Exception $th) {
            //$op->Rollback();
            $status = false;
            $response = $this->nome . $this->setor . $this->codigo;//$th->getMessage();
            http_response_code($th->getCode());
        }
        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro ao processar a Exclusão requisitada", 500);
            }

            $cad = new \App\Models\Funcionarios;
            $cad->setCodigo($this->codigo);
            $cad->excluir();
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


    }
