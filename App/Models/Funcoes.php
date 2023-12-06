<?php

namespace App\Models;

use Exception;

class Funcoes
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
    public function getContent(){
        return $this->Content;
    }

    public function listar($cdFuncao = null, $stringPesquisa = null)
    {
        try {
            $limit = 100;
            $read = new \App\Conn\Read();

            $parseString = "LIMIT=$limit";
            $query = "SELECT * FROM FUNCOES F
                      WHERE F.CD_FUNCAO IS NOT NULL ";

            if($cdFuncao){
                $query .=" AND F.CD_FUNCAO = :CD";
                $parseString .="&CD=$cdFuncao";
            }
            if($stringPesquisa){
                $query .= " AND F.NM_FUNCAO LIKE '%$stringPesquisa%' ";
            }

            $query .= " LIMIT :LIMIT";

            $read->FullRead($query, $parseString);
            $this->Content = $read->getResult();
            $this->Result = true;
        } catch (Exception $th) {
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }

    public function alterar()
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("FUNCOES", "WHERE CD_FUNCAO = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["NM_FUNCAO" => $this->nome];
                $conn = \App\Conn\Conn::getConn(true);
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("FUNCOES", $dadosupdate, "WHERE CD_FUNCAO =:C", "C=$this->codigo");

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
            $dadosinsert = ["NM_FUNCAO" => $this->nome];
            $conn = \App\Conn\Conn::getConn(true);
            $insert = new \App\Conn\Insert($conn);
            $insert->ExeInsert("FUNCOES", $dadosinsert);

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
            $delete->ExeDelete("FUNCOES", "WHERE CD_FUNCAO=:C", "C=$this->codigo");

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
            $this->Message = 'Erro ao executar operação!';
        }
    }
}
