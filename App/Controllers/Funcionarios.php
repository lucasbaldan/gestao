<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;
use DateTime;

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
    private $vinculosFuncionais;


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
            $infoFuncionario = json_decode($dados['dados'], true);

            $this->vinculosFuncionais = isset($infoFuncionario['vinculosFuncionais']) ? $infoFuncionario['vinculosFuncionais'] : '';
            $this->codigo = isset($infoFuncionario['cdFuncionario']) ? $infoFuncionario['cdFuncionario'] : '';
            $this->nome = isset($infoFuncionario['nmFuncionario']) ? $infoFuncionario['nmFuncionario'] : '';
            $this->setor = isset($infoFuncionario['setorFuncionario']) ? $infoFuncionario['setorFuncionario'] : '';

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
                try {
                    $this->controlarVinculosFuncionais($conn, $cad, $this->vinculosFuncionais, $idFuncionarioInserido, $op);
                } catch (Exception $th) {
                    throw new Exception($th->getMessage(), $th->getCode());
                }
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

                try {
                    $this->controlarVinculosFuncionais($conn, $cad, $this->vinculosFuncionais, $this->codigo, null, $op);
                } catch (Exception $th) {
                    throw new Exception($th->getMessage(), $th->getCode());
                }
            }
            $conn->commit();
            $status = true;
            $response = '';
            http_response_code(200);
        } catch (Exception $th) {
            $op->Rollback();
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
            $status = true;
            $response = $th->getMessage();
            http_response_code($th->getCode());
        }

        $response = json_encode(["status" => $status, "response" => $response]);
        header('Content-Type: application/json');
        echo $response;
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
