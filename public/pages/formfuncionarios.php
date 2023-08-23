<?php
$nomePagina = "Funcionários - Cadastro";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
?>

<style>
  .ui.slider.checkbox {
    padding-right: 50px;
  }
</style>

<script src=""></script>


<body>
  <div class="ui container">
    <br>
    <br>
    <div class="ui top attached tabular menu" id="tabnav">
      <a class="item active" data-tab="funcionario-geral">Geral</a>
      <a class="item" data-tab="funcionario-funcionais">Vínculos Funcionais</a>
    </div>

    <div class="ui form">
      <!-- <form id="CAD-funcionario"> -->
      <div class="ui bottom attached tab segment active" data-tab="funcionario-geral">
        <h3>Cadastros Gerais do Funcionário</h3>
        <div class="ui fluid label">
          Nome do Funcionário⠀⠀⠀
          <div class="ui fluid icon input">
            <input type="text" style="border-color: red;">
            <i class="icon keyboard outline"></i>
          </div>
        </div>
        <br><br>
        <div class="ui fluid label">
          Setor⠀⠀⠀
          <div class="ui fluid input">
            <select id="select-setor" name="selectSetor" class="select2" style="border-color: red;" required></select>
          </div>
        </div>
        <!-- </form> -->
      </div>
      <div class="ui bottom attached tab segment" data-tab="funcionario-funcionais">
        <h3>Cadastros Funcionais do Funcionário</h3>
        <div class="ui label">
          Matrícula⠀⠀
          <div class="ui input">
            <input type="text" id="matricula" style="border-color: red;">
          </div>
        </div>
        <div class="ui label">
          Data de início⠀⠀
          <div class="ui input">
            <input type="date" id="dataInicio" style="border-color: red;">
          </div>
        </div>
        <div class="ui label">
          Data de Término⠀⠀
          <div class="ui input">
            <input type="date" id="dataTermino">
          </div>
        </div>

        <div class="ui label">
          Faz horário de almoço?⠀⠀
          <div class="ui input">
            <select id="select-almoco" name="almoco" class="select2" style="border-color: red;" required>
              <option value="S">Sim</option>
              <option value="N">Não</option>
            </select>
          </div>
        </div>

        <br><br>


        <div class="ui label">
          Funcão⠀⠀
          <div class="ui input">
            <select id="select-funcao" name="funcao-funcionario" class="select2" style="border-color: red;" required>
            </select>
          </div>
        </div>

        <br><br>

        <div class="ui large label">
          Dias de Trabalho - Semanal</div>
        <br><br>
        <div class="ui slider checkbox">
          <input type="checkbox" name="SEG" id="SEG">
          <label for="SEG">Segunda</label>
        </div>

        <div class="ui slider checkbox">
          <input type="checkbox" name="TER" id="TER">
          <label for="TER">Terça</label>
        </div>

        <div class="ui slider checkbox">
          <input type="checkbox" name="QUA" id="QUA">
          <label for="QUA">Quarta</label>
        </div>

        <div class="ui slider checkbox">
          <input type="checkbox" name="QUI" id="QUI">
          <label for="QUI">Quinta</label>
        </div>

        <div class="ui slider checkbox">
          <input type="checkbox" name="SEX" id="SEX">
          <label for="SEX">Sexta</label>
        </div>

        <br><br>

        <div class="ui fluid label">
          Descrição do horário de Trabalho⠀⠀⠀
          <div class="ui fluid icon input">
            <input type="text" id="descricaoHorario" style="border-color: red;">
            <i class="icon keyboard outline"></i>
          </div>
        </div>

        <br><br>
        <button class="ui right labeled icon green button" id="addfuncional">
          <i class="plus square outline icon"></i>
          Adicionar / Alterar
        </button>


        <table id="funcionalTable" class="ui blue celled table">
          <thead>
            <tr>
              <th>Matrícula</th>
              <th>Data Início</th>
              <th>Data Final</th>
              <th>Almoço?</th>
              <th>Função</th>
              <th>Dias de Trabalho</th>
              <th>Descrição do horário</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>

    </div>
  </div>



  <script>
    $(document).ready(function() {

      $('#tabnav .item')
        .tab();

      $("#select-almoco").select2({
        minimumResultsForSearch: -1
      });

      carregardadosFuncoes();
      carregardadosSetores();

      var table = $('#funcionalTable').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
        },
        "bFilter": false
      });

      $('#addfuncional').on('click', function() {
        var matricula = $('#matricula').val();
        var dataInicio = $('#dataInicio').val();
        var dataTermino = $('#dataTermino').val();
        var almoco = $('#select-almoco').val();
        var funcao = $('#select-funcao').val();
        var diasTrabalho = [];

        if ($('#SEG').is(':checked')) diasTrabalho.push('Segunda');
        if ($('#TER').is(':checked')) diasTrabalho.push('Terça');
        if ($('#QUA').is(':checked')) diasTrabalho.push('Quarta');
        if ($('#QUI').is(':checked')) diasTrabalho.push('Quinta');
        if ($('#SEX').is(':checked')) diasTrabalho.push('Sexta');

        var descricaoHorario = $('#descricaoHorario').val();

        // Adicionar a nova linha à tabela
        table.row.add([
          matricula,
          dataInicio,
          dataTermino,
          almoco,
          funcao,
          diasTrabalho.join(', '), // Transforma o array em uma string separada por vírgulas
          descricaoHorario,
          '<button class="ui icon red button">Remover</button>' // Botão para remover a linha
        ]).draw(false); // Redesenha a tabela sem recarregar os dados

        // Limpar campos após adicionar a linha
        $('#matricula').val('');
        $('#dataInicio').val('');
        $('#dataTermino').val('');
        $('#select-almoco').val('S');
        $('#select-funcao').val('').val(null).trigger('change');
        $('#SEG').prop('checked', false);
        $('#TER').prop('checked', false);
        $('#QUA').prop('checked', false);
        $('#QUI').prop('checked', false);
        $('#SEX').prop('checked', false);
        $('#descricaoHorario').val('');
      });

    });


    async function carregardadosFuncoes(FuncaoSalvoNoBanco = null) {
      let options = [];

      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Funcoes.php",
        data: {
          funcao: "listJSON",
        },
        success: function(data) {
          const dadosFuncoes = JSON.parse(data);
          options = dadosFuncoes.map((item) => ({
            id: item.CD_FUNCAO.toString(),
            text: item.NM_FUNCAO,
          }));

          options.unshift({
            id: "",
            text: "",
          });

          $("#select-funcao").select2({
            data: options,
            placeholder: "Selecione uma função",
            allowClear: true
          });
        },
        error: function() {
          alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        },
      });
    }

    async function carregardadosSetores(SetorSalvoNoBanco = null) {
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

          $("#select-setor").select2({
            data: options,
            placeholder: "Selecione um Setor",
            allowClear: true
          });

          if (SetorSalvoNoBanco) {
            $("#select-setor").val(tipoExcecaoSalvoNoBanco).trigger("change");
          }
        },
        error: function() {
          alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        },
      });
    }
  </script>