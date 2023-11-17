<?php
$nomePagina = "Funcionários - Listagem";
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
            <a href="./formfuncionarios.php">
                <div class="ui animated button green" id="CAD" tabindex="0">
                    <div class="visible content"><i class="plus icon"></i></div>
                    <div class="hidden content">
                        Novo
                    </div>
                </div>
            </a>
        </div>

        <table id="myTable" class="ui red celled table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Setor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</body>

<div id="confirmacaoExclusao" class="ui modal">
    <div class="header" style="background-color: orange; color: white;">Confirmação</div>
    <div class="content">
        <div class="ui icon message">
            <i class="attention icon"></i>
            <div class="content">
                <div class="header">Atenção</div>
                <p>Você tem certeza que deseja excluir este registro do sistema?</p>
                <p style="color: red;"><strong>A AÇÃO ACARRETARÁ NA EXCLUSÃO DE TODOS OS VÍNCULOS FUNCIONAIS DO FUNCIONÁRIO</strong></p>
            </div>
        </div>
    </div>
    <div class="actions">
        <div id="fechaModalEXC" class="ui inverted red cancel button">Cancelar</div>
        <div id="botaoconfirmaExclusao" class="ui positive green button">Concordo <i class="trash icon"></i></div>
    </div>
</div>

<script src="./../js/default/toast.js"></script>
<script src="./../js/pages/listfuncionarios.js"></script>