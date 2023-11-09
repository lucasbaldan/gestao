<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Setor = new Setores;
    $Setor->$method($_POST);
}

class Setores
{
    private int $codigo;
    private string $nome;

    public function listSetoresJSON($dados)
    {
         try {
             $this->codigo = isset($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;
              $pegalistaDeSetores = new \App\Models\Setores;
              $listaDeSetores = $pegalistaDeSetores->listarSetores($this->codigo);
              echo json_encode($listaDeSetores);
         } catch (Exception $th) {
             return json_encode(array('error' => "Erro ao executar operação."));
         }
    }

    public function controlarSetores($dados)
    {

        try {
            $this->codigo = !empty($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;
            $this->nome = isset($dados['nameSetor']) ? htmlspecialchars($dados['nameSetor'], ENT_QUOTES, "UTF-8") : '';

            if ($this->codigo == 0 && empty($this->nome)) {
                throw new Exception("Erro ao efetuar a operação, tente novamente mais tarde!");
            }

            if ($this->codigo == 0) {

                if (empty($this->nome)) {
                    throw new Exception("Preencha o campo Nome!");
                }

                $cad = new \App\Models\Setores;
                $cad->setNome($this->nome);

                $duplicado = $cad->listarSetores(null, $this->nome);
                if ($duplicado) {
                    throw new Exception("Registro já Cadastrado!");
                }
                $cad->inserirSetor();
                if ($cad->getResult() == true) {
                    $status = 'inserido';
                    $response = '';
                } else {
                    $status = 'erro';
                    $response = 'Erro ao executar a operação na base de dados <br> Erro : '. $cad->getMessage();
                }
            } else {

                if (empty($this->nome)) {
                    throw new Exception("Preencha o campo Nome!");
                }

                $cad = new \App\Models\Setores;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);

                $duplicado = $cad->listarSetores(null, $this->nome);
                if ($duplicado && $duplicado[0]['CD_SETOR'] != $this->codigo) {
                    throw new Exception("Registro já Cadastrado!");
                }

                $cad->alterarSetor();
                if ($cad->getResult() == true) {
                    $status =  'alterado';
                    $response = '';
                } else {
                    $status = 'erro';
                    $response = 'Erro ao executar a operação na base de dados <br> Erro : '. $cad->getMessage();
                }
            }
        } catch (Exception $th) {
         $status = 'erro';
         $response = $th->getMessage();
        }

        $response = json_encode(array("status" => $status, "response" => $response));
        echo $response;
    }

    public function excluirSetores($dados)
    {

        try {
            $this->codigo = !empty($dados['cdSetor']) ? filter_input(INPUT_POST, 'cdSetor', FILTER_SANITIZE_NUMBER_INT) : 0;

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Setores;
            $cad->setCodigo($this->codigo);
            $cad->excluirSetores();
            if ($cad->getResult() == true) {
                $status = 'excluido';
                $response = '';
            } else {
                $status = 'erro';
                $response = $cad->getMessage();
            }
        } catch (Exception $th) {
            $status = 'erro';
            $response = $th->getMessage();
        }

        $response = json_encode(array("status" => $status, "response" => $response));
        echo $response;
    }
}
