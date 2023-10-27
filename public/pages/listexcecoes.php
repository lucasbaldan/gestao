<?php
$nomePagina = "Exceções - Listagem";
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
            </tbody>
        </table>
    </div>
</body>

<div class="ui modal" id="CADmodal">
    <div class="header" style="background-color: black; color:white;">
        Exceções de Trabalho - Cadastro
    </div>

    <form class="ui form" id="form-CAD-excecao">
        <input type="hidden" name="funcao" value="controlar" required readonly>
        <div class="content">

            <div class="disabled field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%;">
                <label>Código:</label>
                <input type="text" name="cdExcecao" id="cdExcecao" placeholder="Código da exceção gerao automaticamente ao inserir" required readonly>
            </div>


            <div class="two fields" style="margin-top: 10px; margin-left: 10%; margin-right: 10%">

                <div class="required field">
                    <label>Data</label>
                    <div class="ui calendar">
                        <div class="ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" name="dataExcecao" id="dataExcecao" placeholder="Data">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Data final</label>
                    <div class="ui calendar">
                        <div class="ui input left icon">
                            <i class="calendar icon"></i>
                            <input type="text" name="dataFinal" id="dataFinal" placeholder="Data final...">
                        </div>
                    </div>
                </div>

            </div>


                <div class="required field" style="margin-top: 10px; margin-left: 10%; margin-right: 10%">

                    <label>Tipo de Exceção...</label>

                    <select id="select-tipoExcecao" name="tipoExcecao" class="select2" required>
                    </select>
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

<script src="./../js/default/ptBR-calendar.js"></script>
<script src="./../js/local_componentes/multiselect.min.js"></script>
<script src="./../js/pages/listexcecoes.js"></script>