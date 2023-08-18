<?php

namespace App;

use PDO;
use PDOException;

class Conn
{

    private string $host;
    private string $user;
    private string $db;
    private string $pass;

    private PDO $conn;

    public function __construct()
    {
        $this->host = 'localhost';
        $this->db = 'AP_PDO';
        $this->user = 'root';
        $this->pass = '';

        $this->Connect();
    }

    public function Connect()
    {
        try {
            $conn_string = sprintf("mysql:host=%s;dbname=%s", $this->host, $this->db);
            $this->conn = new PDO($conn_string, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $return = "CONEXÃO ESTABELECIDA COM SUCESSO";
        } catch (PDOException $th) {
            $return = "FALHA NA CONEXÃO COM O BANCO DE DADOS \n" . "MOTIVO: \n" . $th->getMessage();
        }
        return $return;
    }
}
