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


    public function listar($cdExcecao = null)
    {
        try {
            $read = new \App\Conn\Read();
            if (empty($cdExcecao)) {
                $read->FullRead("SELECT E.CD_EXCECAO, DATE_FORMAT(E.DATA_INICIAL, '%d/%m/%Y') AS DATA_INICIAL, DATE_FORMAT(E.DATA_FINAL, '%d/%m/%Y') AS DATA_FINAL, F.NM_FUNCIONARIO, T.NM_TIPO_EXCECAO
        FROM EXCECOES E
        INNER JOIN TIPO_EXCECOES T ON (E.CD_TIPO_EXCECAO = T.CD_TIPO_EXCECAO)
        INNER JOIN FUNCIONARIOS F ON (E.CD_FUNCIONARIO = F.CD_FUNCIONARIO)");
            } else {
                $read->FullRead("SELECT E.CD_EXCECAO, E.DATA_INICIAL, E.DATA_FINAL, E.CD_FUNCIONARIO, E.CD_TIPO_EXCECAO
        FROM EXCECOES E WHERE E.CD_EXCECAO =:C", "C=$cdExcecao");
            }
            return $read->getResult();
        } catch (Exception $th) {
            header("Location: /gestao/public/pages/generalError.php");
        }
    }

    public function alterar()
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("TIPO_EXCECOES", "WHERE CD_TIPO_EXCECAO = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["NM_TIPO_EXCECAO" => $this->data];
                $conn = \App\Conn\Conn::getConn(true);
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("TIPO_EXCECOES", $dadosupdate, "WHERE CD_TIPO_EXCECAO =:C", "C=$this->codigo");

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

    public function inserir()
    {

        try {
            $dadosinsert = ["DATA_INICIAL" => $this->data, "DATA_FINAL" => $this->dataFinal, "CD_TIPO_EXCECAO" => $this->tpExcecao, "CD_FUNCIONARIO" => $this->cdfuncionario];
            $conn = \App\Conn\Conn::getConn(true);
            $insert = new \App\Conn\Insert($conn);
            $insert->ExeInsert("EXCECOES", $dadosinsert);

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

    public function excluir()
    {

        try {
            $conn = \App\Conn\Conn::getConn(true);
            $delete = new \App\Conn\Delete($conn);
            $delete->ExeDelete("EXCECOES", "WHERE CD_EXCECAO=:C", "C=$this->codigo");

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

}
