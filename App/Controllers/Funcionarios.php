<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Funcionario = new Funcionarios;
    $Funcionario->$method($_POST);
}

class Funcionarios
{
    private $codigo;
    private $nome;
    private $setor;

    public function list()
    {

        try {
            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listar();
            return $lista;
        } catch (Exception $th) {
            return false;
        }
    }

    public function listJSON($dados)
    {
        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            $pegalista = new \App\Models\Funcionarios;
            $lista = $pegalista->listar($this->codigo);
            echo json_encode($lista);
        } catch (Exception $th) {
            return json_encode(array('error' => "Erro ao executar operação."));
        }
    }

    public function controlar($dados)
    {

        $infoFuncionario = json_decode($dados['dados'], true);
        $vinculosFuncionais = $infoFuncionario['vinculosFuncionais'];

        $this->codigo = isset($infoFuncionario['cdFuncionario']) ? $infoFuncionario['cdFuncionario'] : '';
        $this->nome = isset($infoFuncionario['nmFuncionario']) ? $infoFuncionario['nmFuncionario'] : '';
        $this->setor = isset($infoFuncionario['setorFuncionario']) ? $infoFuncionario['setorFuncionario'] : '';

        $dadosFuncionais = [];
        $vinculosFuncionaiscontador = 0;
        foreach ($vinculosFuncionais as $vinculoFuncional) {
        $vinculosFuncionaiscontador += 1;  
        $matricula = isset($vinculoFuncional['Matrícula']) ? $vinculoFuncional['Matrícula'] : '';
        $dataInicio = isset($vinculoFuncional["Data Início"]) ? $vinculoFuncional["Data Início"] : '';
        $dataFinal = isset($vinculoFuncional["Data Final"]) ? $vinculoFuncional["Data Final"] : '';
        $almoco = isset($vinculoFuncional["Almoço?"]) ? $vinculoFuncional["Almoço?"] : '';
        $idFuncao = isset($vinculoFuncional["idFunção"]) ? $vinculoFuncional["idFunção"] : '';
        $descHorario = isset($vinculoFuncional["Descrição do horário"]) ? $vinculoFuncional["Descrição do horário"] : '';

        $dadosFuncionais[] = $matricula;
        $dadosFuncionais[] = $dataInicio;
        $dadosFuncionais[] = $dataFinal;
        $dadosFuncionais[] = $almoco;
        $dadosFuncionais[] = $idFuncao;
        $dadosFuncionais[] = $descHorario;
        $dadosFuncionais[] = $vinculosFuncionaiscontador;

    }
    
    echo json_encode($dadosFuncionais);

        

        // try {

        //     if (empty($this->codigo) && empty($this->nome)) {
        //         throw new Exception("Campos Usuário e Senha não podem ser nulos!");
        //     }

        //     if (empty($this->codigo)) {

        //         $cad = new \App\Models\Funcionarios;
        //         $cad->setNome($this->nome);
        //         $cad->inserir();
        //         if ($cad->getResult() == true) {
        //             echo 'inserido';
        //         } else {
        //             echo 'erro';
        //         }
        //     } else {

        //         $cad = new \App\Models\Funcionarios;
        //         $cad->setCodigo($this->codigo);
        //         $cad->setNome($this->nome);
        //         $cad->alterar();
        //         if ($cad->getResult() == true) {
        //             echo 'alterado';
        //         } else {
        //             echo 'erro';
        //         }
        //     }
        // } catch (Exception $th) {
        //     echo 'erro operação';
        // }
    }

    public function excluir($dados)
    {

        try {
            $this->codigo = isset($dados['cdFuncionario']) ? $dados['cdFuncionario'] : '';

            if (empty($this->codigo)) {
                throw new Exception("Erro");
            }

            $cad = new \App\Models\Funcionarios;
            $cad->setCodigo($this->codigo);
            $cad->excluir();
            if ($cad->getResult() == true) {
                echo 'excluido';
            } else {
                echo 'erro';
            }
        } catch (Exception $th) {
            echo 'erro';
        }
    }
}
