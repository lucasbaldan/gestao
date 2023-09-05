<?php
$nomePagina = "Funcionários - Listagem";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
$pegaFuncionarios = new \App\Controllers\Funcionarios();
$dados = $pegaFuncionarios->list();
?>

<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" />

<script src="./../js/listfuncionarios.js"></script>

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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $dado) { ?>
                    <tr>
                        <td><?= $dado['CD_FUNCIONARIO'] ?></td>
                        <td><?= $dado['NM_FUNCIONARIO'] ?></td>
                        <td style="display: flex; justify-content: center;">
                            <form action="formfuncionarios.php" method="POST">
                                <input type="hidden" name="cdFuncionario" value="<?= $dado['CD_FUNCIONARIO'] ?>">
                                <button type="submit" class="ui mini icon button blue"><i class='pencil alternate icon'></i></button>
                            </form>
                            <?= "<button class='ui mini icon button red' onclick='excluirRegistro(" . $dado['CD_FUNCIONARIO'] . ")'><i class='trash alternate icon'></i></button>" ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
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