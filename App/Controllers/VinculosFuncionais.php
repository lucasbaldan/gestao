<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use DateTime;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $vinculosFuncionais = new VinculosFuncionais;
    $vinculosFuncionais->$method($_POST);
} else {
    header("Location: /gestao/public/pages/generalError.php");
    exit;
    die;
}

class VinculosFuncionais
{
    private $codigo;
    private $matricula;
    private $dataInicio;
    private $dataFinal;
    private $almoco;
    private $idFuncao;
    private $descHorario;
    private $codigoFuncionario;
    private $diasTrabalho;
    private $cdFuncionario;

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdVinculoFuncional']) ? $dados['cdVinculoFuncional'] : null;
            $this->codigoFuncionario = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : null;

            $lista = \App\Models\VinculosFuncionais::listarFuncional($this->codigoFuncionario, $this->codigo);
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

    public function controlar($dados)
    {
        try {

            $this->codigo = !empty($dados['cdVinculoFuncional']) ? $dados['cdVinculoFuncional'] : '';
            $this->matricula = isset($dados['matricula']) ? $dados['matricula'] : '';
            $this->dataInicio = isset($dados["dataAdmissao"]) ? $dados["dataAdmissao"] : '';
            $this->dataFinal = isset($dados["dataDemissao"]) ? $dados["dataDemissao"] : '';
            $this->almoco = isset($dados["almoco"]) ? $dados["almoco"] : '';
            //$this->almoco = $this->almoco == "Sim" ? 1 : 0;
            $this->idFuncao = isset($dados["idFuncao"]) ? $dados["idFuncao"] : '';
            $this->descHorario = isset($dados["descHorario"]) ? $dados["descHorario"] : '';
            $segunda = isset($dados["SEG"]) ? 1 : 0;
            $terca = isset($dados["TER"]) ? 1 : 0;
            $quarta = isset($dados["QUA"]) ? 1 : 0;
            $quinta = isset($dados["QUI"]) ? 1 : 0;
            $sexta = isset($dados["SEX"]) ? 1 : 0;
            $this->diasTrabalho = [$segunda, $terca, $quarta, $quinta, $sexta];
            $this->cdFuncionario = isset($dados["cdFuncionario"]) ? $dados["cdFuncionario"] : '';

            $dados = [
                "CODIGO" =>  $this->codigo,
                "MATRICULA" =>  $this->matricula,
                "DATAINICIAL" => $this->dataInicio,
                "DATAFINAL" =>  $this->dataFinal,
                "ALMOCO" => $this->almoco,
                "IDFUNCAO" => $this->idFuncao,
                "DESCHORARIO" => $this->descHorario,
                "SEMANA" => $this->diasTrabalho,
                "FUNCIONARIO" => $this->cdFuncionario
            ];

            // VALIDAÇÕES SERVER SIDEE
            $cad = new \App\Models\VinculosFuncionais($dados);

            if (empty($this->codigo)) {
                $cad->inserir();
            } else {
                $cad->alterar();
            }

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

    public function excluir($dados)
    {
        try {
            $this->codigo = isset($dados['cdVinculoFuncional']) ? $dados['cdVinculoFuncional'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro ao processar código nulo na ação desejada! Tente novamente mais tarde!");
            }
            $delete = new \App\Models\VinculosFuncionais($dados = ["CODIGO" => $this->codigo]);
            $delete->excluir();

            if (!$delete->getResult()) {
                throw new Exception("Erro ao excluir vinculo Funcional, Motivo " . $delete->getMessage(), 500);
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
