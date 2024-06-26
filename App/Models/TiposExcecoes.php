<?php

namespace App\Models;

use Exception;

class TiposExcecoes
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

    public function generalSearch($colunas = null, $cdTipoExcecao = null, $nmTipoExcecao = null, $pesquisaLIKE = null)
    {
        try {
            $read = new \App\Conn\Read();
            $colunas = $colunas ?? "*";

            //LEMBRAR DE COLOCAR ESPAÇO NO FINAL DAS STINGS DE QUERY PARA PODER SER MONTADO CORRETAMENTE NA HORA DE EXCUTAR
            $query = "SELECT ".$colunas." 
            FROM TIPO_EXCECOES T
            WHERE T.CD_TIPO_EXCECAO IS NOT NULL ";

            if ($cdTipoExcecao) {
                $query .= "AND T.CD_TIPO_EXCECAO = ".$cdTipoExcecao." ";
            }
            if ($nmTipoExcecao) {
                $query .= "AND T.NM_TIPO_EXCECAO = '".$nmTipoExcecao."' ";
            }
            if ($pesquisaLIKE) {
                $query .= "AND T.NM_TIPO_EXCECAO LIKE '%".$pesquisaLIKE."%' ";
            }

            $read->FullRead($query);

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
                $dadosupdate = ["NM_TIPO_EXCECAO" => $this->nome];
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
            $dadosinsert = ["NM_TIPO_EXCECAO" => $this->nome];
            $conn = \App\Conn\Conn::getConn(true);
            $insert = new \App\Conn\Insert($conn);
            $insert->ExeInsert("TIPO_EXCECOES", $dadosinsert);

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
            $delete->ExeDelete("TIPO_EXCECOES", "WHERE CD_TIPO_EXCECAO=:C", "C=$this->codigo");

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
