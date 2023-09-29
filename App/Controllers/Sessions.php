<?php

namespace App\Controllers;

use Exception;

require __DIR__ . '/../../vendor/autoload.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['funcao']) && isset($_POST['way'])) {
    $method = $_POST['funcao'];
    $Sessao = new Sessions;
    $Sessao->$method($_POST);
} else {
}

class Sessions
{
    private $infoUsuario;
    private $Result;

    public function setInfoUSuario($dados)
    {
        $this->infoUsuario = [
            "NOME" => $dados[0]
        ];
    }

    public function getInfoUsuario()
    {
        return $this->infoUsuario;
    }

    public function verificaSessao()
    {
        session_start();
        if (isset($_SESSION['logado'])) {
            if ($_SESSION['logado'] == true) {
                return true;
            }
            else{
                return false;
            }
        } else {
            return false;
        }
    }

    public function getResult()
    {
        return $this->Result;
    }

    public function gerarSessao()
    {
        try {

            if (!isset($_SESSION['logado'])) {
                ini_set('session.cookie_lifetime', 1800);
                session_start();

                $_SESSION['infoUsuario'] = $this->infoUsuario;
                $_SESSION['logado'] = true;
                $this->Result = 1;
            } else {
                $this->Result = 0;
            }
        } catch (Exception $e) {
            return 'Erro ao iniciar a sessão: ' . $e->getMessage();
        }
    }

    public function deslogar()
    {
        session_start();
        $_SESSION['logado'] = false;
        echo 'deslogado';
    }
}
