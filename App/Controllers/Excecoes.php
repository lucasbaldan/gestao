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

    public function list()
    {

        try {
            $pegalista = new \App\Models\Excecoes;
            $lista = $pegalista->listar();
            return $lista;
        } catch (Exception $th) {
            return false;
        }
    }

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
            $this->data = isset($dados['dataExcecao']) ? $dados['dataExcecao'] : '';
            $this->dataFinal = isset($dados['dataFinal']) ? $dados['dataFinal'] : '';
            $this->tpExcecao = isset($dados['tipoExcecao']) ? $dados['tipoExcecao'] : '';
            $this->funcionarios_selecionados = isset($dados['to']) ? ($dados['to']) : '';

            if (empty($this->data) || empty($this->data) || empty($this->tpExcecao) || (empty($this->funcionarios_selecionados) && !isset($this->codigo))) {
                throw new Exception("Campos Usuário e Senha não podem ser nulos!");
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
                    if ($cad->getResult() == false) {
                        echo 'erro';
                        $insert->Rollback();
                        break;
                    }
                }
                $insert->Commit();
                echo 'inserido';
            
            } else {

                $cad = new \App\Models\Excecoes;
                $cad->setCodigo($this->codigo);
                $cad->setData($this->data);
                $cad->setDataFinal($this->dataFinal);
                $cad->setTipoExcecao($this->tpExcecao);
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
            $this->codigo = isset($dados['cdExcecao']) ? $dados['cdExcecao'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Excecoes;
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
