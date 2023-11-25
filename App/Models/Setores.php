<?php

namespace App\Models;

use Exception;

class Setores
{
    private $codigo;
    private $nome;
    private $Message;
    private $Result;
    private $Content;

    public function setCodigo($cd)
    {
        $this->codigo = $cd;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    public function getMessage()
    {
        return $this->Message;
    }

    public function getResult()
    {
        return $this->Result;
    }
    public function getContent()
    {
        return $this->Content;
    }

    public function generalSearch($cdSetor = null, $nmSetor = null, $stringPesquisa = null)
    {
        try {
            $limiteSql = 100;
            $read = new \App\Conn\Read();
            $parseString = "LIMIT=$limiteSql";
            $sql = "SELECT * FROM
            SETORES S
            WHERE S.CD_SETOR IS NOT NULL ";

            if($cdSetor){
                $sql .= " AND S.CD_SETOR = :CD";
                $parseString .= "&CD=$cdSetor";
            }
            if($nmSetor){
                $sql .= "AND S.NOME = :NM";
                $parseString .= "&NM=$nmSetor";
            }
            if($stringPesquisa){
                $sql .= "AND S.NOME LIKE '%$stringPesquisa%'";
                //$parseString .= "&PESQ=$stringPesquisa"; 
            }

            $sql .= " LIMIT :LIMIT";

            $read->FullRead($sql, $parseString);
            
            $this->Content = $read->getResult();
            $this->Result = true;
        } catch (Exception $th) {
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function alterarSetor()
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("SETORES", "WHERE CD_SETOR = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["NOME" => $this->nome];
                $conn = \App\Conn\Conn::getConn(true);
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("SETORES", $dadosupdate, "WHERE CD_SETOR =:C", "C=$this->codigo");

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
                throw new Exception("O registro que foi solicitado alteração não foi encontrado na base de dados");
            }
        } catch (Exception $th) {
            $update->Rollback();
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function inserirSetor()
    {

        try {
            $dadosinsert = ["NOME" => $this->nome];
            $conn = \App\Conn\Conn::getConn(true);
            $insert = new \App\Conn\Insert($conn);
            $insert->ExeInsert("SETORES", $dadosinsert);

            if (!$insert->getResult()) {
                $insert->Rollback();
                $this->Result = false;
                $this->Message = $insert->getMessage();
            } else {
                $insert->Commit();
                $this->Result = true;
            }
        } catch (Exception $th) {
            $insert->Rollback();
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function excluirSetores()
    {

        try {
            $conn = \App\Conn\Conn::getConn(true);
            $delete = new \App\Conn\Delete($conn);
            $delete->ExeDelete("SETORES", "WHERE CD_SETOR=:C", "C=$this->codigo");

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
            $this->Message = $th->getMessage();
        }
    }
}
