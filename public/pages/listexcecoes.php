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
                        <td><?= $dadoExcecao['NM_FUNCIONARIO'] ?? "GERAL" ?></td>
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
    </br>

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
                    <div class="one wide column"></div>
                    <div class="two wide column left aligned">
                        <label class="ui label large">Data inicial</label>
                    </div>
                    <div class="four wide column">
                        <div class="ui input">
                            <input type="date" name="dataExcecao" id="dataExcecao" placeholder="Data">
                        </div>
                    </div>
                    <div class="one wide column"></div>
                    <div class="two wide column left aligned">
                        <label class="ui label large">Data final</label>
                    </div>
                    <div class="four wide column">
                        <div class="ui input">
                            <input type="date" name="dataFinal" id="dataFinal" placeholder="Data final...">
                        </div>
                    </div>
                </div>
            </div>



            <div class="field">
                <div class="ui grid">
                    <div class="one wide column"></div>
                    <div class="two wide column left aligned">
                        <label class="ui label large">Tipo de Exceção...</label>
                    </div>
                    <div class="eleven wide column">
                        <div class="ui selection">
                            <select id="select-tipoExcecao" placeholder="Escolha Tipo Exceção" autocomplete="off">
                                <option value="">Select a person...</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="field">
                <div class="ui grid">
                    <div class="one wide column"></div>
                    <div class="three wide column middle aligned">
                        <label class="ui label large">Funcionários</label>
                    </div>
                    <div class="ten wide column">

                        <div class="row">

                            <select name="from[]" id="search" class="form-control" size="5" multiple="multiple">
                            </select>

                            <div style="padding: 10px 10px 10px 0; display: flex; justify-content: center;">
                                <button type="button" id="search_rightAll" class="ui small blue icon button"><i class="angle double down icon"></i></button>
                                <button type="button" id="search_rightSelected" class="ui small blue icon button"><i class="angle down icon"></i></button>
                                <button type="button" id="search_leftSelected" class="ui small red icon button"><i class="angle up icon"></i></button>
                                <button type="button" id="search_leftAll" class="ui small red icon button"><i class="angle double up icon"></i></button>
                            </div>

                            <div class="col-xs-5">
                                <select name="to[]" id="search_to" class="form-control" size="5" multiple="multiple"></select>
                            </div>
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