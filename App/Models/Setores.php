<?php

namespace App\Models;

use Exception;

class Setores
{
    private $codigo;
    private $nome;
    private $Message;
    private $Result;

    public function setCodigo($cd)
    {
        $this->codigo = $cd;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function listarSetores($cdSetor = null)
    {
        try {
            $read = new \App\Conn\Read();
            if (empty($cdSetor)) {
                $read->FullRead("SELECT S.CD_SETOR, S.NOME
        FROM SETORES S");
            } else {
                $read->FullRead("SELECT S.CD_SETOR, S.NOME
        FROM SETORES S WHERE S.CD_SETOR =:C", "C=$cdSetor");
            }
            return $read->getResult();
        } catch (Exception $th) {
            header("Location: /gestao/public/pages/generalError.php");
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

    public function getMessage()
    {
        return $this->Message;
    }

    public function getResult()
    {
        return $this->Result;
    }
}
