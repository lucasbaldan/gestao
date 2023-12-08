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

    public function __construct($dados)
    {
        $this->codigo = !empty($dados['CODIGO']) ? $dados['CODIGO'] : null;
        $this->nome = !empty($dados['NOME_FUNCAO']) ? $dados['NOME_FUNCAO'] : null;
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
            $conn = \App\Conn\Conn::getConn(true);
            $read = new \App\Conn\Read($conn);
            $read->ExeRead("FUNCOES", "WHERE CD_FUNCAO = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["NM_FUNCAO" => $this->nome];
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("FUNCOES", $dadosupdate, "WHERE CD_FUNCAO =:C", "C=$this->codigo");

                $atualizado = !empty($update->getResult());
                if ($atualizado) {
                    $this->Result = true;
                    $update->Commit();
                } else {
                    throw new Exception($update->getMessage(), 500);
                }
            } else {
                throw new Exception("ERRO AO ENCONTRAR REGISTRO PARA ATUALIZAÇÃO NA BASE DE DADOS.", 500);
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
                throw new Exception($insert->getMessage(), 500);
            } else {
                $insert->Commit();
                $this->Result = true;
            }
        } catch (Exception $th) {
            $insert->Rollback();
            $this->Result = false;
            $this->Message = $insert->getMessage();
        }
    }

    public function excluir()
    {

        try {
            $conn = \App\Conn\Conn::getConn(true);
            $delete = new \App\Conn\Delete($conn);
            $delete->ExeDelete("FUNCOES", "WHERE CD_FUNCAO=:C", "C=$this->codigo");

            if (!$delete->getResult()[0]) {
                throw new Exception($delete->getResult()[1]);
            }
            $delete->Commit(); 
            $this->Result = true;
        } catch (Exception $th) {
            $delete->Rollback();
            $this->Result = false;
            $this->Message = $th->getMessage();
        }
    }
}
