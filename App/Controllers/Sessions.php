<?php

namespace App\Controllers;

use Exception;

require __DIR__ . '/../../vendor/autoload.php';


class Sessions {
    private $infoUsuario;
    private $Result;

    public function setInfoUSuario($dados){
        $this->infoUsuario = [
            "NOME" => $dados[0]
        ];
    }

    public function getResult(){
        return $this->Result;
    }

    public function gerarSessao(){
        try {
            ini_set('session.cookie_lifetime', 1800);
            session_start();
    
            if (!isset($_SESSION['logado'])) {
    
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
}