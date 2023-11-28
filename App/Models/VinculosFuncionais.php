<?php

namespace App\Models;

use Exception;

class VinculosFuncionais
{
    private $codigo;
    private $matricula;
    private $dataInicio;
    private $dataFinal;
    private $almoco;
    private $idFuncao;
    private $descHorario;
    private $diasSemana;
    private $Message;
    private $Result;

    public function __construct($diasTrabalhoSemana = null, $descHorario = null, $idFuncao = null, $almoco = null, $matricula =  null, $dataInicio, $dataFinal = null, $codigo = null)
    {
        $this->matricula = $matricula;
        $this->dataInicio = $dataInicio;
        $this->dataFinal = $dataFinal;
        $this->almoco = $almoco;
        $this->idFuncao = $idFuncao;
        $this->descHorario = $descHorario;
        $this->diasSemana = $diasTrabalhoSemana;
        $this->codigo = $codigo;
    }

    public function setCodigo($cd)
    {
        $this->codigo = $cd;
    }
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }
    public function setDataInicio($dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }
    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }
    public function setAlmoco($almoco)
    {
        $this->almoco = $almoco;
    }
    public function setFuncao($idFuncao)
    {
        $this->idFuncao = $idFuncao;
    }
    public function setDescHorario($descHorario)
    {
        $this->descHorario = $descHorario;
    }
    public function setSemana($semana)
    {
        $this->diasSemana = $semana;
    }
    public function getMessage()
    {
        return $this->Message;
    }
    public function getResult()
    {
        return $this->Result;
    }

    public function listarTelaRelatorio($cdFuncionario = null, $dataSelect, $cdSetor = null)
    {
        try {
            $query = "SELECT DISTINCT V.MATRICULA, CONCAT(F.NM_FUNCIONARIO, ' - ', V.MATRICULA) AS OPCAO_FUNCIONARIO
            FROM VINCULOS_FUNCIONAIS_FUNCIONARIOS V
            INNER JOIN FUNCIONARIOS F ON (V.CD_FUNCIONARIO = F.CD_FUNCIONARIO)
            INNER JOIN SETORES S ON (F.CD_SETOR = S.CD_SETOR)
            AND DATE_FORMAT(DATA_INICIAL, '%Y-%m') <= :MREL
            AND (DATE_FORMAT(DATA_FINAL, '%Y-%m') >= :MREL OR DATA_FINAL IS NULL)";

            $params = "MREL=$dataSelect";

            if (!empty($cdSetor)) {
                $query .= " AND S.CD_SETOR = :S";
                $params .= "&S=$cdSetor";
            }


            $read = new \App\Conn\Read();
            $read->FullRead($query, $params);
            return $read->getResult();
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), 500);
        }
    }

    public function listarFuncional($cdFuncionario = null)
    {
        try {
            $botoesTabela = "<button class='small ui icon blue button'><i class='icon pencil alternate'></i></button>        <button class='small ui icon red button'><i class='icon trash alternate outline'></i></button>";
            $read = new \App\Conn\Read();
            $read->FullRead("SELECT F.CD_VINCULO_FUNCIONAL, F.MATRICULA, DATE_FORMAT(F.DATA_INICIAL, '%d/%m/%Y') AS DATA_INICIAL, DATE_FORMAT(F.DATA_FINAL, '%d/%m/%Y') AS DATA_FINAL, F.ALMOCO, F.DESC_HR_TRABALHO, F.SEG, F.TER, F.QUA, F.QUI, F.SEX, F.CD_FUNCAO, :DIV AS ACOES , FUN.NM_FUNCAO
                             FROM VINCULOS_FUNCIONAIS_FUNCIONARIOS F
                             INNER JOIN FUNCOES FUN ON (FUN.CD_FUNCAO = F.CD_FUNCAO) 
                             WHERE F.CD_FUNCIONARIO =:C", "C=$cdFuncionario&DIV=$botoesTabela");
            return $read->getResult();
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), 500);
        }
    }


    public function inserirVinculosFuncionais($insert, $cdFuncionario)
    {

        try {
            $dadosinsert = [
                "MATRICULA" => $this->matricula,
                "DATA_INICIAL" => $this->dataInicio,
                "DATA_FINAL" => $this->dataFinal,
                "ALMOCO" => $this->almoco,
                "DESC_HR_TRABALHO" => $this->descHorario,
                "CD_FUNCAO" => $this->idFuncao,
                "CD_FUNCIONARIO" => $cdFuncionario,
                "SEG" => $this->diasSemana[0],
                "TER" => $this->diasSemana[1],
                "QUA" => $this->diasSemana[2],
                "QUI" => $this->diasSemana[3],
                "SEX" => $this->diasSemana[4]
            ];
            $insert->ExeInsert("VINCULOS_FUNCIONAIS_FUNCIONARIOS", $dadosinsert);

            if (!$insert->getResult()) {
                throw new Exception("Erro ao inserir Vínculos Funcionais" . $insert->getMessage(), 500);
            }
            $this->Result = true;
        } catch (Exception $th) {
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function alterarVinculosFuncionais($update)
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("VINCULOS_FUNCIONAIS_FUNCIONARIOS", "WHERE CD_VINCULO_FUNCIONAL = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = [
                    "MATRICULA" => $this->matricula,
                    "DATA_INICIAL" => $this->dataInicio,
                    "DATA_FINAL" => $this->dataFinal,
                    "ALMOCO" => $this->almoco,
                    "DESC_HR_TRABALHO" => $this->descHorario,
                    "CD_FUNCAO" => $this->idFuncao,
                    "SEG" => $this->diasSemana[0],
                    "TER" => $this->diasSemana[1],
                    "QUA" => $this->diasSemana[2],
                    "QUI" => $this->diasSemana[3],
                    "SEX" => $this->diasSemana[4]
                ];

                $update->ExeUpdate("VINCULOS_FUNCIONAIS_FUNCIONARIOS", $dadosupdate, "WHERE CD_VINCULO_FUNCIONAL =:C", "C=$this->codigo");

                $atualizado = !empty($update->getResult());
                if ($atualizado) {
                    $this->Result = true;
                } else {
                    throw new Exception("Erro ao alterar Vínculos Funcionais " .$update->getMessage() , 500);
                }
            } else {
                throw new Exception("Ops! Parece que esse registro não existe mais na base de dados!", 500);
            }
        } catch (Exception $th) {
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function excluirVinculosFuncionais($delete)
    {

        try {

            $delete->ExeDelete("VINCULOS_FUNCIONAIS_FUNCIONARIOS", "WHERE CD_VINCULO_FUNCIONAL=:C", "C=$this->codigo");

            if (!$delete->getResult[0]) {
                throw new Exception("Erro ao excluir Vínculo Funcional", 500);
            }
            $this->Result = true;
        } catch (Exception $th) {
            $this->Message = $th->getMessage();
            $this->Result = false;
        }
    }

    public static function gerarRelatorio($mesRelatorio, $codigoFuncionario)
    {
        // $mesRelatorio = $_POST['mesRelatorio'];
        // //$mesRelatorio = date('m', $mesRelatorio);
        // $codigoFuncionario = $_POST['idFuncionario'];

        $read = new \App\Conn\Read();
        $read->FullRead("SELECT F.NM_FUNCIONARIO AS NOME, V.MATRICULA, V.DATA_INICIAL, V.DATA_FINAL, FU.NM_FUNCAO, V.DESC_HR_TRABALHO, V.SEG, V.TER, V.QUA, V.QUI, V.SEX, V.ALMOCO 
        FROM VINCULOS_FUNCIONAIS_FUNCIONARIOS V
        INNER JOIN FUNCIONARIOS F  ON (F.CD_FUNCIONARIO = V.CD_FUNCIONARIO)
        INNER JOIN FUNCOES FU ON (V.CD_FUNCAO = FU.CD_FUNCAO)
        WHERE V.MATRICULA =:C
        AND DATE_FORMAT(DATA_INICIAL, '%Y-%m') <= :MREL
        AND (DATE_FORMAT(DATA_FINAL, '%Y-%m') >= :MREL OR DATA_FINAL IS NULL)
        ORDER BY V.MATRICULA", "C=$codigoFuncionario&MREL=$mesRelatorio");
        return $read->getResult();
    }
}