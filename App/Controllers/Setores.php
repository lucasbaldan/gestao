<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

class Setores
{
    private $codigo;
    private string $nome;

    public function listSetores()
    {

        try {
            $pegalistaDeSetores = new \App\Models\Setores;
            $listaDeSetores = $pegalistaDeSetores->listarSetores();
            return $listaDeSetores;
        } catch (Exception $th) {
            return false;
        }
    }

    public function listSetoresJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdSetor']) ? $dados['cdSetor'] : '';

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
            $this->codigo = isset($dados['cdSetor']) ? $dados['cdSetor'] : '';
            $this->nome = isset($dados['nameSetor']) ? $dados['nameSetor'] : '';

            if (empty($this->codigo) && empty($this->nome)) {
                throw new Exception("Campos Usuário e Senha não podem ser nulos!");
            }

            if (empty($this->codigo)) {

                $cad = new \App\Models\Setores;
                $cad->setNome($this->nome);
                $cad->inserirSetor();
                if ($cad->getResult() == true) {
                    echo 'inserido';
                } else {
                    echo 'erro';
                }
            } else {

                $cad = new \App\Models\Setores;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);
                $cad->alterarSetor();
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

    public function excluirSetores($dados)
    {

        try {
            $this->codigo = isset($dados['cdSetor']) ? $dados['cdSetor'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Setores;
            $cad->setCodigo($this->codigo);
            $cad->excluirSetores();
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
    $Setor = new Setores;
    $Setor->$method($_POST);
}
