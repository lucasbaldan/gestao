<?php
$nomePagina = "Relatório - Folha de Ponto";
include_once("./header_semantic_main.php");
include_once("./header.php");
include_once("./footer_menu.php");
?>
<style>
  .botaoGerar {
    margin-top: 5%;
  }

  .ui.fluid.dropdown {
    border: 1px solid red !important;
  }

  input {
    border: 1px solid red !important;
  }
</style>

<br>
<br>
<div class="ui container" style="border: 1px">
  <h4 class="ui dividing header">Selecione as opções para gerar o relatório</h4>
  <form action="../../App/ControllersRel/Rel_folha_ponto.php" method="POST">

    <input type="hidden" value="gerarRelatorio" name="metodo">
    <label for="" class="label">Opções</label>
    <select class="ui fluid dropdown" name="opcoesRelatorio" id="opcoesRelatorio">
      <option value=""></option>
      <option value="FUNCIONARIO">Por Funcionários</option>
      <option value="SETOR">Por Setor</option>
    </select>
    <br>

    <div id="select-Setor" style="width: 100%;">
      <label for="" class="label">Setor</label>
      <select class="ui fluid dropdown" name="setor" id="setor">
      </select>
      <br>
      <br>
    </div>

    <label for="" class="label">Mês</label>
    <br>
    <div class="ui input" id="mesRelatorioDIV">
      <input type="month" name="mesRelatorio" id="mesRelatorio">
    </div>
    <br>
    <br>

    <div class="ui fluid container">
      <select name="from[]" id="search" class="form-control" size="5" multiple="multiple" style="width: 100%;">
      </select>

      <div style="padding: 10px 10px 10px 0; display: flex; justify-content: center;">
        <button type="button" id="search_rightAll" class="ui small blue icon button"><i class="angle double down icon"></i></button>
        <button type="button" id="search_rightSelected" class="ui small blue icon button"><i class="angle down icon"></i></button>
        <button type="button" id="search_leftSelected" class="ui small red icon button"><i class="angle up icon"></i></button>
        <button type="button" id="search_leftAll" class="ui small red icon button"><i class="angle double up icon"></i></button>
      </div>

      <select name="to[]" id="search_to" class="form-control" size="5" multiple="multiple" style="width: 100%;"></select>
    </div>

    <div class="botaoGerar">
      <button class="ui fluid grey button" type="submit">
        <i class="file icon"></i>
        Gerar Relatório
      </button>
    </div>

  </form>
</div>

<script src="./../js/relatoriofolhaponto.js"></script>