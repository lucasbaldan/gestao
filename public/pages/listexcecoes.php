<?php
$nomePagina = "Exceções - Listagem";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
$pegaExcecao = new \App\Controllers\Excecoes();
$dados = $pegaExcecao->list();
?>

<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" />

<script src="./../js/listexcecoes.js"></script>

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

        <table id="myTable" class="ui red celled table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo de Excecão</th>
                    <th>Data</th>
                    <th>Data Final</th>
                    <th>Funcionário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $dadoExcecao) { ?>
                    <tr>
                        <td><?= $dadoExcecao['CD_EXCECAO'] ?></td>
                        <td><?= $dadoExcecao['NM_TIPO_EXCECAO'] ?></td>
                        <td><?= $dadoExcecao['DATA_INICIAL'] ?></td>
                        <td><?= $dadoExcecao['DATA_FINAL'] ?? "-" ?></td>
                        <td><?= $dadoExcecao['NM_PESSOA'] ?? "GERAL" ?></td>
                        <td><?= "<button class='ui mini icon button blue' onclick='editarRegistro(" . $dadoExcecao['CD_EXCECAO'] . ")'><i class='pencil alternate icon'></i></button>" ?>
                            <?= "<button class='ui mini icon button red' onclick='excluirRegistro(" . $dadoExcecao['CD_EXCECAO'] . ")'><i class='trash alternate icon'></i></button>" ?></td>
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

<div class="ui modal" id="CADmodal">
    <i class="close icon"></i>
    <div class="header">
        Exceção - Cadastro
    </div>

    <div class="ui positive message">
        <i class="close icon"></i>
        <div class="header">
            <i class="check circle icon"></i>
        </div>
        <p>Operação efetuada com sucesso! <b>Aguarde, atualizando a tabela de registros</b></p>
    </div>

    <form class="ui form" id="form-CAD-Excecao">
        <input type="hidden" name="funcao" value="controlar">
        <input type="hidden" name="cdExcecao" id="cdExcecao">
        <div class="content">
            <div class="field">
                <div class="ui grid">
                    <div class="three wide column middle aligned">
                        <label class="ui label large">Data inicial</label>
                    </div>
                    <div class="twelve wide column">
                        <div class="ui input">
                            <input type="date" name="dataInicial" id="dataInicial" placeholder="Data inicial...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="ui grid">
                    <div class="three wide column middle aligned">
                        <label class="ui label large">Data final</label>
                    </div>
                    <div class="twelve wide column">
                        <div class="ui input">
                            <input type="date" name="dataFinal" id="dataFinal" placeholder="Data final...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="field">
                <div class="ui grid">
                    <div class="three wide column middle aligned">
                        <label class="ui label large">Tipo de Exceção</label>
                    </div>
                    <div class="twelve wide column">
                        <div class="ui search">
                            <input type="text" class="prompt" placeholder="Tipo de Exceção..." id="tipoExcecao" name="tipoExcecao" onkeyup="carregar_tipoExecoes(this.value)" />
                            <div class="results"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </br></br>

        <div class="button-container">
            <div class="ui orange basic button">
                Fechar
            </div>
            <button type="submit" class="ui positive right labeled icon button">
                Cadastrar
                <i class="checkmark icon"></i>
            </button>
        </div>
        </br>
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