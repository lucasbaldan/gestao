<?php

namespace App\Models;

use Exception;

class Login
{
    private $login_usuario;
    private $senha_usuario;

    public function __construct($login = null, $senha = null)
    {
        $this->login_usuario = $login;
        $this->senha_usuario = $senha;
    }

    public function verificaLoginBanco()
    {
        try {
            $read = new \App\Conn\Read();
            $read->FullRead("SELECT *
        FROM USUARIOS U
        WHERE U.USUARIO = :L AND U.SENHA = :S", "L=$this->login_usuario&S=$this->senha_usuario");
            return $read->getResult();
        } catch (Exception $th) {
            return "Erro ao fazer consulta no banco de dados!";
        }
    }
    public function listUsuario()
    {
        try {
            $read = new \App\Conn\Read();
            $read->FullRead("SELECT U.CD_USUARIO, U.USUARIO, U.SENHA, P.NM_PESSOA 
        FROM USUARIOS U
        INNER JOIN PESSOAS P ON (U.CD_PESSOA = P.CD_PESSOA)");
            return $read->getResult();
        } catch (Exception $th) {
            header("Location: /gestao/public/pages/generalError.php");
        }
    }
}
