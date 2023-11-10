<?php

namespace App\Models;

use Exception;

class Excecoes
{
    private $codigo;
    private $data;
    private $dataFinal;
    private $tpExcecao;
    private $cdfuncionario;
    private $Message;
    private $Result;

    public function setCodigo($cd)
    {
        $this->codigo = $cd;
    }
    public function setData($data)
    {
        $this->data = $data;
    }
    public function setDataFinal($datafinal)
    {
        $this->dataFinal = $datafinal ?? null;
    }
    public function setTipoExcecao($tipoExcecao)
    {
        $this->tpExcecao = $tipoExcecao;
    }
    public function setFuncionario($funcionario)
    {
        $this->cdfuncionario = $funcionario;
    }
    public function getMessage()
    {
        return $this->Message;
    }

    public function getResult()
    {
        return $this->Result;
    }


    public function generalSearch($colunas = null, $gridformat = false)
    {

        try {
            $read = new \App\Conn\Read();
            $colunas = $colunas ?? "*";
            $parseString = null;


            $query = $gridformat == true ? "SELECT E.CD_EXCECAO, DATE_FORMAT(E.DATA_INICIAL, '%d/%m/%Y') AS DATA_INICIAL, DATE_FORMAT(E.DATA_FINAL, '%d/%m/%Y') AS DATA_FINAL, F.NM_FUNCIONARIO, T.NM_TIPO_EXCECAO, F.CD_FUNCIONARIO, T.CD_TIPO_EXCECAO
                  FROM EXCECOES E
                  INNER JOIN TIPO_EXCECOES T ON (E.CD_TIPO_EXCECAO = T.CD_TIPO_EXCECAO)
                  INNER JOIN FUNCIONARIOS F ON (E.CD_FUNCIONARIO = F.CD_FUNCIONARIO)"
                :
                "SELECT " . $colunas . " 
             FROM EXCECOES E
             WHERE E.CD_EXCECAO IS NOT NULL ";

            if ($this->codigo) {
                $query .= "AND E.CD_EXCECAO = $this->codigo ";
            }

            $read->FullRead($query, $parseString);
            return $read->getResult();
        } catch (Exception $th) {
            throw new Exception($th->getMessage());
        }

        //DATE_FORMAT(E.DATA_INICIAL, '%d/%m/%Y') AS DATA_INICIAL, DATE_FORMAT(E.DATA_FINAL, '%d/%m/%Y') AS DATA_FINAL         
    }

    public function alterar()
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("EXCECOES", "WHERE CD_EXCECAO = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["DATA_INICIAL" => $this->data, "DATA_FINAL" => $this->dataFinal, "CD_TIPO_EXCECAO" => $this->tpExcecao];
                $conn = \App\Conn\Conn::getConn(true);
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("EXCECOES", $dadosupdate, "WHERE CD_EXCECAO =:C", "C=$this->codigo");

                $atualizado = !empty($update->getResult());
                if ($atualizado) {
                    $update->Commit();
                    $this->Result = true;
                } else {
                    $update->Rollback();
                    $this->Result = false;
                    $this->Message = $update->getMessage();
                }
            } else {
                throw new Exception("Parece que esse registro não existe mais na base de dados!");
            }
        } catch (Exception $th) {
            $update->Rollback();
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function inserir($insert)
    {

        try {
            $dadosinsert = ["DATA_INICIAL" => $this->data, "DATA_FINAL" => $this->dataFinal, "CD_TIPO_EXCECAO" => $this->tpExcecao, "CD_FUNCIONARIO" => $this->cdfuncionario];
            $insert->ExeInsert("EXCECOES", $dadosinsert);

            if (!$insert->getResult()) {
                //$insert->Rollback();
                $this->Result = false;
                $this->Message = $insert->getMessage();
            } else {
                //$insert->Commit();
                $this->Result = true;
            }
        } catch (Exception $th) {
            //$insert->Rollback();
            $this->Result = false;
            $this->Message = $insert->getMessage();
        }
    }

    public function excluir()
    {

        try {
            $conn = \App\Conn\Conn::getConn(true);
            $delete = new \App\Conn\Delete($conn);
            $delete->ExeDelete("EXCECOES", "WHERE CD_EXCECAO=:C", "C=$this->codigo");

            if ($delete->getResult()[0] == true) {
                $delete->Commit();
                $this->Result = true;
            } else {
                $delete->Rollback();
                $this->Result = false;
                $this->Message = $delete->getResult()[1];
            }
        } catch (Exception $th) {
            $delete->Rollback();
            $this->Result = false;
            $this->Message = $delete->getResult()[1];
        }
    }


    public function selectExcecoesRelatorio($mesRelatorio, $matricula)
    {
        try {
            $read = new \App\Conn\Read();
            $read->FullRead("SELECT E.DATA_INICIAL, E.DATA_FINAL, TE.NM_TIPO_EXCECAO
        FROM EXCECOES E 
        INNER JOIN FUNCIONARIOS F ON (E.CD_FUNCIONARIO = F.CD_FUNCIONARIO)
        INNER JOIN VINCULOS_FUNCIONAIS_FUNCIONARIOS V ON (V.CD_FUNCIONARIO = F.CD_FUNCIONARIO)
        INNER JOIN TIPO_EXCECOES TE ON (TE.CD_TIPO_EXCECAO = E.CD_TIPO_EXCECAO)
        WHERE V.MATRICULA =:M
        AND DATE_FORMAT(E.DATA_INICIAL, '%Y-%m') <= :MREL
        AND DATE_FORMAT(E.DATA_FINAL, '%Y-%m') >= :MREL 
        OR E.DATA_FINAL IS NULL", "M=$matricula&MREL=$mesRelatorio");

            return $read->getResult();
        } catch (Exception $th) {
            return 'erro';
        }
    }

    public static function verificaDuplicidade($cdFuncionario, $data, $dataFinal)
    {
        $read = new \App\Conn\Read();
        try {

            $read->FullRead("SELECT * FROM EXCECOES E
            WHERE E.CD_FUNCIONARIO = :F 
            AND ((:dataInicial BETWEEN E.DATA_INICIAL AND E.DATA_FINAL)
            OR ((:dataFinal BETWEEN E.DATA_INICIAL AND E.DATA_FINAL) OR :dataFinal IS NULL))", "F=$cdFuncionario&dataInicial=$data&dataFinal=$dataFinal");
            //"F=$cdFuncionario&dataInicial=$data&dataFinal=$dataFinal"

            return $read->getResult();
        } catch (Exception $th) {
            return $th->getMessage();
        }
    }
}
