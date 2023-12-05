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
    private $idFuncionario;
    private $Message;
    private $Result;

    public function __construct($dados)
    {
        $this->codigo = !empty($dados['CODIGO']) ? $dados['CODIGO'] : '';
        $this->matricula = !empty($dados['MATRICULA']) ? $dados['MATRICULA'] : '';
        $this->dataInicio = !empty($dados['DATAINICIAL']) ? $dados['DATAINICIAL'] : '';
        $this->dataFinal = !empty($dados['DATAFINAL']) ? $dados['DATAFINAL'] : '';
        $this->almoco = !empty($dados['ALMOCO']) ? $dados['ALMOCO'] : 0;
        $this->idFuncao = !empty($dados['IDFUNCAO']) ? $dados['IDFUNCAO'] : '';
        $this->descHorario = !empty($dados['DESCHORARIO']) ? $dados['DESCHORARIO'] : '';
        $this->diasSemana = !empty($dados['SEMANA']) ? $dados['SEMANA'] : '';
        $this->idFuncionario = !empty($dados['FUNCIONARIO']) ? $dados['FUNCIONARIO'] : '';
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

    public static function listarFuncional($cdFuncionario = null, $cdVinculoFuncional = null)
    {
        try {
            // $botoesTabela = "<button class='small ui icon blue button'><i class='icon pencil alternate'></i></button>        <button class='small ui icon red button'><i class='icon trash alternate outline'></i></button>";
            $read = new \App\Conn\Read();

            $limit = 100;
            $parseString = "LIMIT=$limit";
            $query = "SELECT F.CD_VINCULO_FUNCIONAL, F.MATRICULA,";

            if ($cdVinculoFuncional) {
                $query .= "F.DATA_INICIAL, F.DATA_FINAL, F.ALMOCO, F.DESC_HR_TRABALHO, F.SEG, F.TER, F.QUA, F.QUI, F.SEX, ";
            } else {
                $query .= " DATE_FORMAT(F.DATA_INICIAL, '%d/%m/%Y') AS DATA_INICIAL, 
                DATE_FORMAT(F.DATA_FINAL, '%d/%m/%Y') AS DATA_FINAL, 
                F.ALMOCO, 
                F.DESC_HR_TRABALHO, 
                 
                CONCAT(
                   CASE WHEN F.SEG = 1 THEN 'SEGUNDA </br> ' ELSE '' END,
                   CASE WHEN F.TER = 1 THEN 'TERÇA </br>' ELSE '' END,
                   CASE WHEN F.QUA = 1 THEN 'QUARTA </br> ' ELSE '' END,
                   CASE WHEN F.QUI = 1 THEN 'QUINTA </br> ' ELSE '' END,
                   CASE WHEN F.SEX = 1 THEN 'SEXTA </br> ' ELSE '' END) 
                   AS DIASSEMANA,";
            }


            $query .= "
                            FUN.CD_FUNCAO,
                            FUN.NM_FUNCAO
                            FROM VINCULOS_FUNCIONAIS_FUNCIONARIOS F
                            INNER JOIN FUNCOES FUN ON (FUN.CD_FUNCAO = F.CD_FUNCAO)
                            WHERE F.CD_VINCULO_FUNCIONAL IS NOT NULL ";

            if ($cdFuncionario) {
                $query .= " AND F.CD_FUNCIONARIO =:CF ";
                $parseString .= "&CF=$cdFuncionario";
            }
            if ($cdVinculoFuncional) {
                $query .= " AND F.CD_VINCULO_FUNCIONAL =:CD ";
                $parseString .= "&CD=$cdVinculoFuncional";
            }

            $query .= "LIMIT :LIMIT";

            $read->FullRead($query, $parseString);
            return $read->getResult();
            //return self::estruturarOBJ($read->getResult());
        } catch (Exception $th) {
            throw new Exception($th->getMessage(), 500);
        }
    }


    public function inserir()
    {
        $conn = \App\Conn\Conn::getConn(true);
        $insert = new \App\Conn\Insert($conn);

        try {
            $dadosinsert = [
                "MATRICULA" => $this->matricula,
                "DATA_INICIAL" => $this->dataInicio,
                "DATA_FINAL" => $this->dataFinal,
                "ALMOCO" => $this->almoco,
                "DESC_HR_TRABALHO" => $this->descHorario,
                "CD_FUNCAO" => $this->idFuncao,
                "CD_FUNCIONARIO" => $this->idFuncionario,
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
            $insert->Commit();
        } catch (Exception $th) {
            $insert->Rollback();
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
                    throw new Exception("Erro ao alterar Vínculos Funcionais " . $update->getMessage(), 500);
                }
            } else {
                throw new Exception("Ops! Parece que esse registro não existe mais na base de dados!", 500);
            }
        } catch (Exception $th) {
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function excluir()
    {
        $conn = \App\Conn\Conn::getConn(true);
        $delete = new \App\Conn\Delete($conn);

        try {
            $delete->ExeDelete("VINCULOS_FUNCIONAIS_FUNCIONARIOS", "WHERE CD_VINCULO_FUNCIONAL=:C", "C=$this->codigo");

            if (!$delete->getResult()[0]) {
                throw new Exception($delete->getResult()[1], 500);
            }
            $delete->Commit();
            $this->Result = true;
        } catch (Exception $th) {
            $delete->Rollback();
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

    // private static function estruturarOBJ($objSQL)
    // {

    //     $arrayObj = [];

    //     foreach ($objSQL as $obj) {
    //         $ObjvinculoFuncioanal = new VinculosFuncionais();
    //         $ObjvinculoFuncioanal->setMatricula($obj["MATRICULA"]);
    //         $ObjvinculoFuncioanal->setDataInicio($obj["DATA_INICIAL"]);
    //         $ObjvinculoFuncioanal->setDataFinal($obj["DATA_FINAL"]);
    //         $ObjvinculoFuncioanal->setAlmoco($obj["ALMOCO"]);
    //         $ObjvinculoFuncioanal->setFuncao($obj["CD_FUNCAO"]);
    //         $ObjvinculoFuncioanal->setDescHorario($obj["DESC_HR_TRABALHO"]);
    //         $ObjvinculoFuncioanal->setCodigo(["CD_VINCULO_FUNCIONAL"]);
    //         //$this->diasSemana = $obj;

    //         $arrayObj[] = $ObjvinculoFuncioanal;
    //         $ObjvinculoFuncioanal = null;
    //     }
    //     return $arrayObj;
    // }
}
