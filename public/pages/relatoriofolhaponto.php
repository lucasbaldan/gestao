<?php
$nomePagina = "Relatório - Folha de Ponto";
include_once("./header_semantic_main.php");
include_once("./header.php");
include_once("./footer_menu.php");

?>
<style>
  .botaoGerar{
    margin-top: 5%;
  }
  .ui.fluid.dropdown {
      border: 1px solid red !important;
    }
</style>
<!-- <link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" /> -->


<br>
<br>
<div class="ui container" style="border: 1px">
<h4 class="ui dividing header">Selecione as opções para gerar o relatório</h4>
  <form action="../../App/ControllersRel/Rel_folha_ponto.php" method="POST">
    <input type="hidden" value="gerarRelatorio" name="metodo">
    <label for="" class="label">Opções</label>
    <select class="ui fluid dropdown" name="opcoesRelatorio" id="opcoesRelatorio">
    <option value=""> </option>
      <option value="SETOR">Por Setor</option>
      <option value="FUNCIONARIO">Por Funcionário</option>
    </select>
    <br>

    <label for="" class="label">Funcionário</label>
    <select class="ui fluid dropdown" name="idFuncionario" id="opcoesRelatorioFuncionario"> 
      <option value="10">LUACAS FAÉ BALDAN</option>
      <option value="2">leandro</option>
    </select>
<br>

    <label for="" class="label">Mês</label>
    <br>
    <div class="ui input">
    <input type="month" name="mesRelatorio" id="mesRelatorio">
    </div>

    <div class="botaoGerar">
    <button class="ui fluid grey button" type="submit">
      <i class="file icon"></i>
      Gerar Relatório
    </button>
    </div>

  </form>
</div>

<script>
  $(document).ready(function() {
  $('#opcoesRelatorio').dropdown();
  $('#opcoesRelatorioFuncionario').dropdown();
});
</script>