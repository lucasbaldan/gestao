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
                    $this->Result = true;
                    //$this->Message = "Os dados do usuário go - $this->NomeLogin</strong> foram atualizados com sucesso";
                    $update->Commit();
                } else {
                    $this->Result = false;
                    //$this->Message = "Não foi possível atualizar os dados usuário <strong>$this->Codigo - $this->NomeLogin</strong>. <br><small>" . \App\Helppers\Formats::TratamentoMensagemErro($update->getError()) . "</small>";
                    $update->Rollback();
                }
            } else {
                throw new Exception("ERRO AO ENCONTRAR REGISTRO PARA ATUALIZAÇÃO NA BASE DE DADOS.");
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
            } else {
                $insert->Commit();
                $this->Result = true;
            }
        } catch (Exception $th) {
            $insert->Rollback();
            $this->Result = false;
        }
    }

    public function excluirSetores()
    {

        try {
            $conn = \App\Conn\Conn::getConn(true);
            $delete = new \App\Conn\Delete($conn);
            $delete->ExeDelete("SETORES", "WHERE CD_SETOR=:C", "C=$this->codigo");

            if ($delete->getRowCount() > 0) {
                $delete->Commit();
                $this->Result = true;
            } else {
                $delete->Rollback();
                $this->Result = false;
            }
        } catch (Exception $th) {
            $delete->Rollback();
            $this->Result = false;
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
