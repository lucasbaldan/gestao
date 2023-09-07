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
            $vinculosFuncionais = $infoFuncionario['vinculosFuncionais'];

            $this->codigo = isset($infoFuncionario['cdFuncionario']) ? $infoFuncionario['cdFuncionario'] : '';
            $this->nome = isset($infoFuncionario['nmFuncionario']) ? $infoFuncionario['nmFuncionario'] : '';
            $this->setor = isset($infoFuncionario['setorFuncionario']) ? $infoFuncionario['setorFuncionario'] : '';

            if (empty($this->nome) || empty($this->setor)) {
                throw new Exception("OS CAMPOS NOME OU SETOR NÃO PODEM SER SALVOS COM INFORMAÇÕES NULAS");
            }

            if (empty($this->codigo)) {
                $conn = \App\Conn\Conn::getConn(true);
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

                    foreach ($vinculosFuncionais as $vinculoFuncional) {

                        $matricula = isset($vinculoFuncional['MATRICULA']) ? $vinculoFuncional['MATRICULA'] : '';
                        $dataInicio = isset($vinculoFuncional["DATA_INICIAL"]) ? $vinculoFuncional["DATA_INICIAL"] : '';
                        $dataFinal = isset($vinculoFuncional["DATA_FINAL"]) ? $vinculoFuncional["DATA_FINAL"] : '';
                        $almoco = isset($vinculoFuncional["ALMOCO"]) ? $vinculoFuncional["ALMOCO"] : '';
                        $almoco = $almoco == "Sim" ? 1 : 0;
                        $idFuncao = isset($vinculoFuncional["CD_FUNCAO"]) ? $vinculoFuncional["CD_FUNCAO"] : '';
                        $descHorario = isset($vinculoFuncional["DESC_HR_TRABALHO"]) ? $vinculoFuncional["DESC_HR_TRABALHO"] : '';
                        $diasTrabalhoSemana = isset($vinculoFuncional["DIASSEMANA"]) ? $vinculoFuncional["DIASSEMANA"] : '';

                        // DATA VALIDATIONS
                        $data_datetime = DateTime::createFromFormat('d/m/Y', $dataInicio);
                        $dataInicio = $data_datetime->format('Y-m-d');

                        if (!empty($dataFinal)) {
                            $data_datetime = DateTime::createFromFormat('d/m/Y', $dataFinal);
                            $dataFinal = $data_datetime->format('Y-m-d');
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

                        //echo json_encode($segunda);

                        $cad->setMatricula($matricula);
                        $cad->setDataInicio($dataInicio);
                        $cad->setDataFinal($dataFinal);
                        $cad->setAlmoco($almoco);
                        $cad->setFuncao($idFuncao);
                        $cad->setDescHorario($descHorario);

                        $cad->inserirVinculosFuncionais($insert, $idFuncionario);
                        $resultadoInsertVinculosFuncionais = $cad->getResult();

                        if ($resultadoInsertVinculosFuncionais == false) {
                            $insert->Rollback();
                            throw new Exception("ERRO AO CADASTRAR VINCULOS FUNCIONAIS");
                        }
                    }
                    $insert->Commit();
                    echo 'inserido';
                }
            }
        } catch (Exception $th) {

            echo 'erro';
        }

        //echo json_encode($dadosFuncionais);



        // try {

        //     if (empty($this->codigo) && empty($this->nome)) {
        //         throw new Exception("Campos Usuário e Senha não podem ser nulos!");
        //     }

        //     if (empty($this->codigo)) {

        //         $cad = new \App\Models\Funcionarios;
        //         $cad->setNome($this->nome);
        //         $cad->inserir();
        //         if ($cad->getResult() == true) {
        //             echo 'inserido';
        //         } else {
        //             echo 'erro';
        //         }
        //     } else {

        //         $cad = new \App\Models\Funcionarios;
        //         $cad->setCodigo($this->codigo);
        //         $cad->setNome($this->nome);
        //         $cad->alterar();
        //         if ($cad->getResult() == true) {
        //             echo 'alterado';
        //         } else {
        //             echo 'erro';
        //         }
        //     }
        // } catch (Exception $th) {
        //     echo 'erro operação';
        // }
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
}
