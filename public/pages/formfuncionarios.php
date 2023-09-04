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
      <div class="ui bottom attached tab segment active" data-tab="funcionario-geral">
        <h3>Cadastros Gerais do Funcionário</h3>
        <input type="hidden" id="cdFucionario" name="cdFuncionario">
        <div class="ui fluid label">
          Nome do Funcionário⠀⠀⠀
          <div class="ui fluid icon input">
            <input type="text" style="border-color: red;" id="nomeFuncionario">
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
      </div>
      <div class="ui bottom attached tab segment" data-tab="funcionario-funcionais">
        <h3>Cadastros Funcionais do Funcionário</h3>
        <input type="hidden" id="cdVinculoFuncional" name="cdVinculoFuncional">
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
              <option value="Sim">Sim</option>
              <option value="Não">Não</option>
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

        <table id="funcionalTable" class="ui blue celled table" style="min-width: 100%;">
          <thead>
            <tr>
              <th>Matrícula</th>
              <th>Data Início</th>
              <th>Data Final</th>
              <th>Almoço?</th>
              <th>idFunção</th>
              <th>Função</th>
              <th>Dias de Trabalho</th>
              <th>Descrição do horário</th>
              <th>Ações</th>
              <th>codigo</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
    </div>
    <hr>

    <div style="padding-bottom: 15px;">
      <button class="ui blue labeled icon button" id="salvarFunc">
        <i class="icon paper plane"></i>
        Salvar
      </button>
      <a href="./listfuncionarios.php" id="gopage"><button class="ui yellow labeled icon button">
          <i class="icon reply"></i>
          Cancelar
        </button></a>

    </div>
  </div>


  <script>
    var editando = false;

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
        bFilter: false,
        columnDefs: [{
          targets: 4,
          visible: false
        }],

      });

      // Evento de clique para os botões de edição
      $('#funcionalTable').on('click', '.small.ui.icon.blue.button', function() {
        if (editando == true) {
          alert("OUTRO REGISTRO JÁ SENDO EDITADO, SALVE PARA EDITAR OUTRO!");
        } else {
          editando = true;
          var rowData = table.row($(this).closest('tr')).data();
          var rowId = $(this).closest('tr').attr('data-id');

          rowData[8] = "<div class='yellow tiny ui icon message' style='width: 150px;'><i class='tiny loading sync icon'></i>Editando</div>"

          table.row(rowId).data(rowData).draw();

          var dataInicioPura = rowData[1].split('/');
          var dataInicio = dataInicioPura[2] + '-' + dataInicioPura[1] + '-' + dataInicioPura[0];

          if (rowData[2] != "-") {
            var dataTerminoPura = rowData[2].split('/');
            var dataTermino = dataTerminoPura[2] + '-' + dataTerminoPura[1] + '-' + dataTerminoPura[0];
          }

          // Preencher campos de entrada com os dados da linha selecionada
          $('#cdVinculoFuncional').val(rowData[9]);
          $('#matricula').val(rowData[0]);
          $('#dataInicio').val(dataInicio);
          $('#dataTermino').val(dataTermino);
          $('#select-almoco').val(rowData[3]).trigger("change");
          $('#select-funcao').val(rowData[4]).trigger("change");
          $('#descricaoHorario').val(rowData[7]);

          var diasTrabalho = rowData[6];

          // Marcar checkboxes correspondentes
          diasTrabalho.forEach(function(dia) {
            $('#' + dia).prop('checked', true);
          });

          // Armazenar o ID da linha sendo editada
          $('#addfuncional').attr('data-edit-id', rowId);
        }
      });

      $('#addfuncional').on('click', function() {
        var editId = $(this).attr('data-edit-id');

        if (editId) {
          var diasTrabalho = [];
          if ($('#SEG').is(':checked')) diasTrabalho.push('SEG');
          if ($('#TER').is(':checked')) diasTrabalho.push('TER');
          if ($('#QUA').is(':checked')) diasTrabalho.push('QUA');
          if ($('#QUI').is(':checked')) diasTrabalho.push('QUI');
          if ($('#SEX').is(':checked')) diasTrabalho.push('SEX');
          var diasTrabalhoTexto = diasTrabalho.join(', ');

          var dataInicioPura = $('#dataInicio').val().split('-');
          var dataInicio = dataInicioPura[2] + '/' + dataInicioPura[1] + '/' + dataInicioPura[0];

          if ($('#dataTermino').val()) {
            var dataTerminoPura = $('#dataTermino').val().split('-');
            var dataTermino = dataTerminoPura[2] + '/' + dataTerminoPura[1] + '/' + dataTerminoPura[0];
          } else {
            var dataTermino = "-";
          }

          alert($('#cdVinculoFuncional').val());

          var updatedData = [
            $('#matricula').val(),
            dataInicio,
            dataTermino,
            $('#select-almoco').val(),
            $('#select-funcao').val(),
            $('#select-funcao').find(':selected').text(),
            diasTrabalho,
            $('#descricaoHorario').val(),
            '<button class="small ui icon blue button"><i class="icon pencil alternate"></i></button>        <button class="small ui icon red button"><i class="icon trash alternate outline"></i></button>',
            $('#cdVinculoFuncional').val(),
          ];

          table.row(editId).data(updatedData).draw();
          editando = false;

          // Remover o atributo de edição após atualizar os dados
          $(this).removeAttr('data-edit-id');
        } else {
          var diasTrabalho = [];
          if ($('#SEG').is(':checked')) diasTrabalho.push('SEG');
          if ($('#TER').is(':checked')) diasTrabalho.push('TER');
          if ($('#QUA').is(':checked')) diasTrabalho.push('QUA');
          if ($('#QUI').is(':checked')) diasTrabalho.push('QUI');
          if ($('#SEX').is(':checked')) diasTrabalho.push('SEX');
          var diasTrabalhoTexto = diasTrabalho.join(', ');

          if (table.rows().count() === 0) {
            var newId = 0
          } else {
            var newId = table.rows().count();
          }

          var dataInicioPura = $('#dataInicio').val().split('-');
          var dataInicio = dataInicioPura[2] + '/' + dataInicioPura[1] + '/' + dataInicioPura[0];

          if ($('#dataTermino').val()) {
            var dataTerminoPura = $('#dataTermino').val().split('-');
            var dataTermino = dataTerminoPura[2] + '/' + dataTerminoPura[1] + '/' + dataTerminoPura[0];
          } else {
            var dataTermino = "-";
          }

          var newRowData = [
            $('#matricula').val(),
            dataInicio,
            dataTermino,
            $('#select-almoco').val(),
            $('#select-funcao').val(),
            $('#select-funcao').find(':selected').text(),
            diasTrabalho,
            $('#descricaoHorario').val(),
            '<button class="small ui icon blue button"><i class="icon pencil alternate"></i></button>        <button class="small ui icon red button"><i class="icon trash alternate outline"></i></button>',
            "-"
          ];

          var newRow = table.row.add(newRowData);
          $(newRow.node()).attr('data-id', newId);
          newRow.draw();

        }
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
        $('#cdVinculoFuncional').val('');
      });


      $('#funcionalTable').on('click', '.small.ui.icon.red.button', function() {
        var row = $(this).closest('tr');
        var rowData = table.row(row).data();

        rowData[4] = "EXC";
        rowData[8] = "<div class'ui red message'><i class='exclamation icon'></i>Exclusão será efetivada quando Salvar</div>";

        // Adiciona a classe 'error' à linha
        row.addClass('error');

        table.row(row).data(rowData).draw();
      });


      $('#salvarFunc').click(function() {
        var cdFuncionario = $('#cdFuncionario').val();
        var nomeFuncionario = $("#nomeFuncionario").val();
        var setor = $("#select-setor").val();
        var dadosTable = [];

        var colunas = table.columns().header().toArray().map(function(th) {
          return $(th).text();
        });

        table.rows().every(function() {
          var dadosColuna = this.data();
          var obj = {};
          colunas.forEach(function(coluna, index) {
            obj[coluna] = dadosColuna[index];
          });
          dadosTable.push(obj);
        });

        var dadosAjax = {
          cdFuncionario: cdFuncionario,
          nmFuncionario: nomeFuncionario,
          setorFuncionario: setor,
          vinculosFuncionais: dadosTable
        }

        $.ajax({
          url: "./../../App/Controllers/Funcionarios.php",
          type: "POST",
          data: {
            dados: JSON.stringify(dadosAjax),
            funcao: "controlar",
          },
          success: function(response) {
            console.log(response);
          },
          error: function(xhr, status, error) {
            console.error("Erro na requisição AJAX:", error);
          }
        });
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