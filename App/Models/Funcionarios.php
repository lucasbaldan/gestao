<?php

namespace App\Models;

use Exception;

class Funcionarios
{
    private $codigo;
    private $nome;
    private $setor;
    private $matricula;
    private $dataInicio;
    private $dataFinal;
    private $almoco;
    private $idFuncao;
    private $descHorario;
    private $diasSemana;
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
    public function setSetor($setor)
    {
        $this->setor = $setor;
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




    public function listar($cdFuncionario = null)
    {
        try {
            $read = new \App\Conn\Read();
            if (empty($cdFuncionario)) {
                $read->FullRead("SELECT F.CD_FUNCIONARIO, F.NM_FUNCIONARIO
        FROM FUNCIONARIOS F");
            } else {
                $read->FullRead("SELECT F.CD_FUNCIONARIO, F.NM_FUNCIONARIO, F.CD_SETOR
        FROM FUNCIONARIOS F WHERE F.CD_FUNCIONARIO =:C", "C=$cdFuncionario");
            }
            return $read->getResult();
        } catch (Exception $th) {
            header("Location: /gestao/public/pages/generalError.php");
        }
    }

    public function listarFuncional($cdFuncionario = null)
    {
        try {
            $read = new \App\Conn\Read();
            $read->FullRead("SELECT F.CD_VINCULO_FUNCIONAL, F.MATRICULA, F.DATA_INICIAL, F.DATA_FINAL, F.ALMOCO, F.DESC_HR_TRABALHO, F.SEG, F.TER, F.QUA, F.QUI, F.SEX, F.CD_FUNCAO
                             FROM VINCULOS_FUNCIONAIS_FUNCIONARIOS F WHERE F.CD_FUNCIONARIO =:C", "C=$cdFuncionario");
            return $read->getResult();
        } catch (Exception $th) {
            header("Location: /gestao/public/pages/generalError.php");
        }
    }

    public function alterar()
    {

        try {
            $read = new \App\Conn\Read();
            $read->ExeRead("FUNCIONARIOS", "WHERE CD_FUNCIONARIO = :C", "C=$this->codigo");
            $dadosCadastro = $read->getResult()[0] ?? [];
            if ($dadosCadastro) {
                $dadosupdate = ["NM_FUNCIONARIO" => $this->nome];
                $conn = \App\Conn\Conn::getConn(true);
                $update = new \App\Conn\Update($conn);
                $update->ExeUpdate("FUNCIONARIOS", $dadosupdate, "WHERE CD_FUNCIONARIO =:C", "C=$this->codigo");

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

    public function inserirFuncionario($insert)
    {

        try {
            $dadosinsert = ["NM_FUNCIONARIO" => $this->nome, "CD_SETOR" => $this->setor];
            $insert->ExeInsert("FUNCIONARIOS", $dadosinsert);

            if (!$insert->getResult()) {
                $this->Result = false;
            } else {
                $this->Result = true;
            }
        } catch (Exception $th) {
            $insert->Rollback();
            $this->Result = false;
        }
    }

    public function inserirVinculosFuncionais($insert, $cdFuncionario)
    {

        try {
            $dadosinsert = [
                "MATRICULA" => $this->matricula,
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
                $this->Result = false;
            } else {
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
            $delete->ExeDelete("FUNCIONARIOS", "WHERE CD_FUNCIONARIO=:C", "C=$this->codigo");

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
