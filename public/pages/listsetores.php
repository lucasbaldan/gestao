<?php
$nomePagina = "Setores - Listagem";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
?>

<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" />

<body>
    </br>
    <div class="ui container">
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

            <table id="myTable" class="ui red celled table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
</body>

<div class="ui modal" id="CADmodal">
    <div class="header" style="background-color: black; color:white;">
        Setor - Cadastro
    </div>

    <form class="ui form" method="POST" id="form-CAD-setor">
        <input type="hidden" name="funcao" value="controlarSetores" required>

        <div class="disabled field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%;">
            <label>Código:</label>
            <input type="text" name="cdSetor" id="cdSetor" placeholder="Código do Setor gerado automaticamente ao inserir..." required readonly>
        </div>

        <div class="required field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%;">
            <label>Nome:</label>
        <div class="ui input">
            <input type="text" name="nameSetor" id="nameSetor" placeholder="Nome do Setor...">
        </div>
        <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
                Preencha o campo NOME com mais de 3 caracteres
            </div>
        </div>
        <div class="ui divider"></div>
        <div class="button-container">
            <div id="fechaModalCAD" class="ui inverted red cancel button">
                Fechar
            </div>
            <button type="submit" class="ui positive right labeled icon button" id="cadSubmit">
                Salvar
                <i class="checkmark icon"></i>
            </button>
        </div>
    </form>
</div>

<div id="confirmacaoExclusao" class="ui modal">
    <div class="header" style="background-color: orange; color: white;">Confirmação</div>
    <div class="content">
        <div class="ui icon message">
            <i class="attention icon"></i>
            <div class="content">
                <div class="header">Atenção</div>
                <p>Você tem certeza que deseja excluir este registro do sistema?</p>
            </div>
        </div>
    </div>
    <div class="actions">
    <div id="fechaModalEXC" class="ui inverted red cancel button">Cancelar</div>
        <div id="botaoconfirmaExclusao" class="ui positive green button">Concordo <i class="trash icon"></i></div>
    </div>
</div>
<script src="./../js/default/toast.js"></script>
<script src="./../js/pages/listsetores.js"></script>