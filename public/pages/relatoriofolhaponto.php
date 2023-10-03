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
<!-- <link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" /> -->


<br>
<br>
<div class="ui container" style="border: 1px">
  <h4 class="ui dividing header">Selecione as opções para gerar o relatório</h4>
  <form action="../../App/ControllersRel/Rel_folha_ponto.php" method="POST">

    <label for="" class="label">Mês</label>
    <br>
    <div class="ui input">
      <input type="month" name="mesRelatorio" id="mesRelatorio">
    </div>
    <br>
    <br>
    <input type="hidden" value="gerarRelatorio" name="metodo">
    <label for="" class="label">Opções</label>
    <select class="ui fluid dropdown" name="opcoesRelatorio" id="opcoesRelatorio">
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

<script>
  $(document).ready(function() {
    $('#opcoesRelatorio').dropdown();
    //$('#setor').dropdown();
    var dataInput = document.getElementById("mesRelatorio");
    var opcaoList = document.getElementById("opcoesRelatorio");
    var opcaoSetor = document.getElementById("select-Setor");

    opcaoSetor.style.display = 'none';

    opcaoList.addEventListener("change", function() {
      if (opcaoList.value == 'SETOR') {
        $('#search_to').empty();
        $('#search').empty();
        carregarDadosSetores();
        opcaoSetor.style.display = 'block';
      } else {
        opcaoSetor.style.display = 'none';
      }
    });

    dataInput.addEventListener("change", function() {
      if (opcaoList.value == 'FUNCIONARIO') {
        carregarDadosFuncionarios($('#mesRelatorio').val());
      }
    });
  });

  async function carregarDadosFuncionarios(mesRelatorio, cdSetor = null) {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcionarios.php",
      data: {
        funcao: "listRelFuncionario",
        mesRelatorio: mesRelatorio,
        setor: cdSetor
      },
      success: function(data) {
        console.log(data);
        const dadosFuncionarios = JSON.parse(data);
        const selectFuncionarios = $("#search");
        selectFuncionarios.empty();
        $('#search_to').empty();

        dadosFuncionarios.forEach((funcionario) => {
          const option = $("<option>")
            .val(funcionario.MATRICULA)
            .text(funcionario.OPCAO_FUNCIONARIO);
          selectFuncionarios.append(option);
        });
      },
      error: function() {
        alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        location.reload();
      },
    });
  }


  jQuery(document).ready(function($) {
    $("#search").multiselect({
      submitAllLeft: false,
      submitAllRigh: true,
      search: {
        left: '<input type="text" placeholder="Procurar Funcionário-Matrícula..." style="width: 100%; border-radius: 3px;" />',
        right: '<input type="text" class="" placeholder="Procurar Funcionário-Matrícula selecionado..." style="width: 100%; border-radius: 3px;"/>',
      },
      fireSearch: function(value) {
        return value.length > 0;
      },
    });
  });

  async function carregarDadosSetores() {
    let options = [];

    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Setores.php",
      data: {
        funcao: "listSetoresJSON",
      },
      success: function(data) {
        const dadosSetores = JSON.parse(data);
        options = dadosSetores.map((item) => ({
          id: item.CD_SETOR.toString(),
          text: item.NOME,
        }));

        options.unshift({
          id: "",
          text: "",
        });

        $("#setor").select2({
          data: options,
          placeholder: "Selecione tipo Exceção",
          allowClear: true
        });
      },
      error: function() {
        alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
      },
    });
  }
</script>