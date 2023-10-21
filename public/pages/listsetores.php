<?php
$nomePagina = "Setores - Listagem";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
$sectors = new \App\Controllers\Setores();
$dados = $sectors->listSetores();
?>

<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen"/>

<script src="./../js/pages/listsetores.js"></script>

<body>
    </br>
    <div class="ui container">

        <div class="ui container">
        <div id="hiddenDiv" class="hidden">
            <div class="ui negative message">
                <i class="close icon"></i>
                <div class="header">
                    <i class="exclamation triangle icon" style="color: #fff;"></i>
                </div>
                <p><b>Erro ao Efetuar Operação!</b></p>
            </div>
        </div>
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
                    <?php foreach ($dados as $dadoSetor) { ?>
                        <tr>
                            <td><?= $dadoSetor['CD_SETOR'] ?></td>
                            <td><?= $dadoSetor['NOME'] ?></td>
                            <td><?= "<button class='ui mini icon button blue' onclick='editarRegistro(" . $dadoSetor['CD_SETOR'] . ")'><i class='pencil alternate icon'></i></button>" ?>
                                <?= "<button class='ui mini icon button red' onclick='excluirRegistro(" . $dadoSetor['CD_SETOR'] . ")'><i class='trash alternate icon'></i></button>" ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
</body>

<div class="ui modal" id="CADmodal">
    <i class="close icon"></i>
    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            <i class="check circle icon"></i>
        </div>
        <p>Operação efetuada com sucesso! <b>Aguarde, atualizando a tabela de registros</b></p>
    </div>
    <div class="header">
        Setor - Cadastro
    </div>

    <form class="ui form" method="POST" id="form-CAD-setor">
        <input type="hidden" name="funcao" value="controlarSetores">
        <input type="hidden" name="cdSetor" id="cdSetor">
        <div class="field">
            <div class="ui grid">
                <div class="three wide column middle aligned">
                    <label class="ui label large">Nome do Setor</label>
                </div>
                <div class="twelve wide column">
                    <div class="ui input">
                        <input type="text" name="nameSetor" id="nameSetor" placeholder="Nome do Setor...">
                    </div>
                </div>
            </div>
        </div>


        <div class="button-container">
            <div class="ui orange basic button">
                Fechar
            </div>
            <button type="submit" class="ui positive right labeled icon button">
                Cadastrar
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