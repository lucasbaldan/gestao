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

    public function controlar($conn, $cad, $vinculosFuncionais, $idFuncionario, $insert = null, $update = null)
    {
        try {
            if (!$insert) {
                $insert = new \App\Conn\Insert($conn);
            }
            if (!$update) {
                $update = new \App\Conn\Update($conn);
            }
            $delete = new \App\Conn\Delete($conn);

            foreach ($vinculosFuncionais as $vinculoFuncional) {

                $codigoFuncional = isset($vinculoFuncional['CD_VINCULO_FUNCIONAL']) ? $vinculoFuncional['CD_VINCULO_FUNCIONAL'] : '';
                $matricula = isset($vinculoFuncional['MATRICULA']) ? $vinculoFuncional['MATRICULA'] : '';
                $dataInicio = isset($vinculoFuncional["DATA_INICIAL"]) ? $vinculoFuncional["DATA_INICIAL"] : '';
                $dataFinal = isset($vinculoFuncional["DATA_FINAL"]) ? $vinculoFuncional["DATA_FINAL"] : '';
                $almoco = isset($vinculoFuncional["ALMOCO"]) ? $vinculoFuncional["ALMOCO"] : '';
                $almoco = $almoco == "Sim" ? 1 : 0;
                $idFuncao = isset($vinculoFuncional["CD_FUNCAO"]) ? $vinculoFuncional["CD_FUNCAO"] : '';
                $descHorario = isset($vinculoFuncional["DESC_HR_TRABALHO"]) ? $vinculoFuncional["DESC_HR_TRABALHO"] : '';
                $diasTrabalhoSemana = isset($vinculoFuncional["DIASSEMANA"]) ? $vinculoFuncional["DIASSEMANA"] : '';

                // A EXCLUSÃO FOGE A REGRA DAS VALIDAÇÕES DOS DADOS

                if (!($codigoFuncional && $idFuncao == "EXC")) {
                    //DATA VALIDATIONS
                    if ($dataInicio == "undefined/undefined/" || $dataInicio == null) {
                        throw new Exception("DATA INICIAL NÃO PODE SER NULA");
                    }
                    if ($idFuncao == "" || $idFuncao == null) {
                        throw new Exception("INFORMAÇÃO DE FUNÇÃO INCORRETA");
                    }
                    if ($diasTrabalhoSemana == [] || $diasTrabalhoSemana == null) {
                        throw new Exception("INFORMAÇÃO DE DIAS TRABALHaDOS INCORRETA");
                    }

                    $data_datetime = DateTime::createFromFormat('d/m/Y', $dataInicio);
                    $dataInicio = $data_datetime->format('Y-m-d');

                    if ($dataFinal != "-") {
                        $data_datetime = DateTime::createFromFormat('d/m/Y', $dataFinal);
                        $dataFinal = $data_datetime->format('Y-m-d');
                    } else {
                        $dataFinal = null;
                    }

                    if (!empty($diasTrabalhoSemana)) {

                        $cad->setSemana($semana = [
                            in_array("SEG", $diasTrabalhoSemana) ? 1 : 0,
                            in_array("TER", $diasTrabalhoSemana) ? 1 : 0,
                            in_array("QUA", $diasTrabalhoSemana) ? 1 : 0,
                            in_array("QUI", $diasTrabalhoSemana) ? 1 : 0,
                            in_array("SEX", $diasTrabalhoSemana) ? 1 : 0
                        ]);
                    }
                }

                $cad->setCodigo($codigoFuncional);
                $cad->setMatricula($matricula);
                $cad->setDataInicio($dataInicio);
                $cad->setDataFinal($dataFinal);
                $cad->setAlmoco($almoco);
                $cad->setFuncao($idFuncao);
                $cad->setDescHorario($descHorario);

                if (!$codigoFuncional && $idFuncao != "EXC") {
                    $cad->inserirVinculosFuncionais($insert, $idFuncionario);
                   if(!$cad->getResult()){
                        throw new Exception("Erro ao inserir Vínculo Funcional ".$cad->getMessage(), 500);
                    }
                }
                if ($codigoFuncional && $idFuncao != "EXC") {
                    $cad->alterarVinculosFuncionais($update);
                    if (!$cad->getResult()) {
                        throw new Exception("Ero ao alterar Vínculo Funcional ".$cad->getMessage(), 500);
                    }
                }
                if ($codigoFuncional && $idFuncao == "EXC") {
                    $cad->excluirVinculosFuncionais($delete);
                    if (!$cad->getResult()) {
                        throw new Exception("Erro ao excluir vínculos Funcionais ".$cad->getMessage(), 500);
                    }
                }
            }
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }
}
