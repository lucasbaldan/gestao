<?php
$nomePagina = "Tipos de Exceções - Listagem";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
?>

<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" />

<body>
    </br>
    <div class="ui container">
        </br>
        <div style="float: right;">
            <div class="ui animated button green" id="CAD" tabindex="0">
                <div class="visible content"><i class="plus icon"></i></div>
                <div class="hidden content">
                    Novo
                </div>
            </div>
        </div>

        <table id="myTable" class="ui red celled table" style="height: 100%;">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
        </table>
        <div id="hiddenDiv" class="hidden">
            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">
                    <i class="exclamation triangle icon" style="color: #fff;"></i>
                </div>
                <p><b>Erro ao Efetuar Operação!</b></p>
            </div>
        </div>
    </div>
</body>

<div class="ui modal" id="CADmodal">
    <div class="header" style="background-color: black; color:white;">
        Tipo de Exceções - Cadastro
    </div>

    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            <i class="check circle icon"></i>
        </div>
        <p>Operação efetuada com sucesso! <b>Aguarde, atualizando a tabela de registros</b></p>
    </div>

    <form class="ui form" method="POST" id="form-CAD-TipoExcecao">
        <input type="hidden" name="funcao" value="controlar">

        <div class="disabled field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%;">
            <label>Código</label>
            <input type="text" name="cdTipoExcecao" id="cdTipoExcecao" placeholder="Código gerado automaticamente ao inserir" readonly>
        </div>


        <div class="required field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%;">
            <label>Nome</label>
            <input type="text" name="nameTipoExcecao" id="nameTipoExcecao" placeholder="Nome do Tipo de Excecão de Trabalho...">
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
                Preencha o campo NOME com mais de 3 caracteres
            </div>
        </div>
        <div class="ui divider"></div>

        <div class="button-container">
            <div class="ui orange basic button">
                Fechar
            </div>
            <button type="submit" class="ui positive right labeled icon button">
                Salvar
                <i class="checkmark icon"></i>
            </button>
        </div>
    </form>
</div>

<div id="confirmacaoExclusao" class="ui modal">
    <div class="header" style="background-color: orange; color: white;">Confirmação</div>
    <div class="content">
        <div class="ui positive message">
            <i class="close icon"></i>
            <div class="header">
                <i class="check circle icon"></i>
            </div>
            <p>Operação efetuada com sucesso! <b>Aguarde, atualizando a tabela de registros</b></p>
        </div>
        <div class="ui icon message">
            <i class="attention icon"></i>
            <div class="content">
                <div class="header">Atenção</div>
                <p>Você tem certeza que deseja excluir este registro do sistema?</p>
            </div>
        </div>
    </div>
    <div class="actions">
        <div class="ui red basic cancel button">Cancelar</div>
        <div id="botaoconfirmaExclusao" class="ui green button">Concordo <i class="trash icon"></i></div>
    </div>
</div>

<script src="./../js/listtiposexcecoes.js"></script>