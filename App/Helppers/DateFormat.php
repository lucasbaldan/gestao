<?php

namespace App\Helppers;

use Exception;

class DateFormat {

    public static function ConverteBRtoDB($date) {
        try{

        return date("Y-m-d", strtotime(str_replace('/', '-', $date)));

        } catch(Exception $th){
            throw new Exception("Erro ao processar Datas da Exceção", 500);
        }

    }
}