<?php

class MinhaExcecao extends Exception {
    public function __construct($mensagem, $codigoHTTP) {
        parent::__construct($mensagem, $codigoHTTP);
    }
}