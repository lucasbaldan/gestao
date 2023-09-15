<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use DateTime;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Funcionario = new Funcionarios;
    $Funcionario->$method($_POST);
}

class Funcionarios
{
    private $codigo;
    private $nome;
    private $setor;
    private $vinculosFuncionais;

    public function list()
    {

        try {
            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listar();
            return $lista;
        } catch (Exception $th) {
            return false;
        }
    }

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listar($this->codigo);
            echo json_encode($lista);
        } catch (Exception $th) {
            return json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function listFuncionalJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listarFuncional($this->codigo);

            foreach ($lista as &$item) {
                $item['ALMOCO'] = $item['ALMOCO'] == 1 ? "Sim" : "Não";
                $item['DATA_FINAL'] = isset($item['DATA_FINAL']) ? $item['DATA_FINAL'] : "-";

                $diasSelecionados = array();

                $diasDaSemana = array(
                    "SEG" => 0,
                    "TER" => 0,
                    "QUA" => 0,
                    "QUI" => 0,
                    "SEX" => 0
                );

                foreach ($diasDaSemana as $dia => &$valor) {
                    if ($item[$dia] == 1) {
                        $valor = 1;
                        $diasSelecionados[] = $dia; // Adicione o nome do dia ao array
                    }
                    unset($item[$dia]);
                }

                $item["DIASSEMANA"] = $diasSelecionados;
            }



            echo json_encode($lista);
        } catch (Exception $th) {
            return json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function controlar($dados)
    {

        try {

            $infoFuncionario = json_decode($dados['dados'], true);
            $this->vinculosFuncionais = isset($infoFuncionario['vinculosFuncionais']) ? $infoFuncionario['vinculosFuncionais'] : '';
            $this->codigo = isset($infoFuncionario['cdFuncionario']) ? $infoFuncionario['cdFuncionario'] : '';
            $this->nome = isset($infoFuncionario['nmFuncionario']) ? $infoFuncionario['nmFuncionario'] : '';
            $this->setor = isset($infoFuncionario['setorFuncionario']) ? $infoFuncionario['setorFuncionario'] : '';

            if (empty($this->nome) || empty($this->setor)) {
                throw new Exception("OS CAMPOS NOME OU SETOR NÃO PODEM SER SALVOS COM INFORMAÇÕES NULAS");
            }

            $conn = \App\Conn\Conn::getConn(true);

            if (empty($this->codigo)) {
                $insert = new \App\Conn\Insert($conn);


                $cad = new \App\Models\Funcionarios;
                $cad->setNome($this->nome);
                $cad->setSetor($this->setor);
                $cad->inserirFuncionario($insert);
                $resultadoInsertFuncionario = $cad->getResult();
                if ($resultadoInsertFuncionario == false) {
                    $insert->Rollback();
                    throw new Exception("ERRO AO CADASTRAR FUNCIONÁRIO");
                } else {

                    $idFuncionario = $insert->getLastInsert();
                    try {
                        $this->controlarVinculosFuncionais($conn, $cad, $this->vinculosFuncionais, $idFuncionario, $insert);
                    } catch (Exception $th) {
                        echo 'erro';
                    }

                    $insert->Commit();
                    echo 'inserido';
                }
            } else {
                $update = new \App\Conn\Update($conn);

                $cad = new \App\Models\Funcionarios;
                $cad->setCodigo($this->codigo);
                $cad->setNome($this->nome);
                $cad->setSetor($this->setor);
                $cad->alterarFuncionario($update);
                $resultadoUpdateFuncionario = $cad->getResult();
                if ($resultadoUpdateFuncionario == false) {
                    $update->Rollback();
                    throw new Exception("ERRO AO CADASTRAR FUNCIONÁRIO");
                } else {

                    try {
                        $this->controlarVinculosFuncionais($conn, $cad, $this->vinculosFuncionais, $this->codigo, null, $update);
                        $update->Commit();
                        echo 'alterado';
                    } catch (Exception $th) {
                        $update->Rollback();
                        echo 'erro';
                    }
                }
            }
        } catch (Exception $th) {

            echo 'erro';
        }

    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Funcionarios;
            $cad->setCodigo($this->codigo);
            $cad->excluir();
            if ($cad->getResult() == true) {
                echo 'excluido';
            } else {
                echo 'erro';
            }
        } catch (Exception $th) {
            echo 'erro';
        }
    }

    //MÉTODOS AUXILIARES

    public function controlarVinculosFuncionais($conn, $cad, $vinculosFuncionais, $idFuncionario, $insert = null, $update = null)
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

                if(!($codigoFuncional && $idFuncao == "EXC")){
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
                    $resultadoInsertVinculosFuncionais = $cad->getResult();

                    if ($resultadoInsertVinculosFuncionais == false) {
                        $insert->Rollback();
                        throw new Exception("ERRO AO CADASTRAR VÍNCULOS FUNCIONAIS");
                    }
                }
                if ($codigoFuncional && $idFuncao != "EXC") {
                    $cad->alterarVinculosFuncionais($update);
                    $resultadoUpdateVinculosFuncionais = $cad->getResult();

                    if ($resultadoUpdateVinculosFuncionais == false) {
                        $update->Rollback();
                        throw new Exception("ERRO AO ALTERAR VÍNCULOS FUNCIONAIS");
                    }
                }
                if ($codigoFuncional && $idFuncao == "EXC") {
                    $cad->excluirVinculosFuncionais($delete);
                    $resultadoDeleteVinculosFuncionais = $cad->getResult();

                    if ($resultadoDeleteVinculosFuncionais == false) {
                        $delete->Rollback();
                        throw new Exception("ERRO AO DELETAR VÍNCULOS FUNCIONAIS");
                    }
                }
            }
        } catch (Exception $th) {
            echo 'erro';
            exit;
        }
    }
}
