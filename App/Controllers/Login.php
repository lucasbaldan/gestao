<?php

namespace App\Controllers;

require __DIR__ . '/../../vendor/autoload.php';

use Exception;


class Login
{
    private string $login;
    private string $senha;
    private $Return;
    private $Message;

    public function efetuarLogin($dados)
    {
        try {
            $this->login = isset($dados['user']) ? $dados['user'] : '';
            $this->senha = isset($dados['password']) ? $dados['password'] : '';

            if (!empty($this->login) && !empty($this->senha)) {
                $temLogin = new \App\Models\Login($this->login, $this->senha);
                $logou = $temLogin->verificaLoginBanco();
                if ($logou) {

                    $Sessao = new Sessions();
                    $Sessao->setInfoUSuario([$logou[0]['NM_PESSOA']]);
                    $Sessao->gerarSessao();
                    $result = $Sessao->getResult();
                    $result = $result == 1 ? 'login' : 'invalido';
                    echo $result;
                } else {
                    echo 'invalido';
                }
            } else {
                throw new Exception("Campos Usuário e Senha não podem ser nulos!");
            }
        } catch (Exception $th) {
            $this->Message = "Erro ao executar operação. " . $th->getMessage();
            return $this->Message;
        }
    }

    public function listUsuarios()
    {
        try {
            $pegalistaDeUsuarios = new \App\Models\Login;
            $listaDeUsuarios = $pegalistaDeUsuarios->listUsuario();
            return $listaDeUsuarios;
        } catch (Exception $th) {
            $this->Message = "Erro ao executar operação. ";
            return $this->Message;
        }
    }



    //UTILITARIOS
    public function getMessage()
    {
        return $this->Message;
    }

    public function getReturn()
    {
        return $this->Return;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao'])) {
    $method = $_POST['funcao'];
    $Login = new Login;
    $Login->$method($_POST);
} else {
}
