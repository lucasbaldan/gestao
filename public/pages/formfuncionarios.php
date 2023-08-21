<?php
$nomePagina = "Funcionários - Cadastro";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
?>

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
      <form id="CAD-funcionario">
        <div class="ui bottom attached tab segment active" data-tab="funcionario-geral">
          <h3>Cadastros Gerais do Funcionário</h3>
          <div class="ui fluid label">
            Nome do Funcionário⠀⠀⠀
            <div class="ui fluid icon input">
              <input type="text" style="border-color: red;">
              <i class="icon keyboard outline"></i>
            </div>
          </div>
        </div>
        <div class="ui bottom attached tab segment" data-tab="funcionario-funcionais">
          <h3>Cadastros Funcionais do Funcionário</h3>
          <div class="ui label">
            Matrícula⠀⠀
            <div class="ui input">
              <input type="text" style="border-color: red;">
            </div>
          </div>
          <div class="ui label">
            Data de início⠀⠀
            <div class="ui input">
              <input type="date" style="border-color: red;">
            </div>
          </div>
          <div class="ui label">
            Data de Término⠀⠀
            <div class="ui input">
              <input type="date">
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
              <select id="select-funcaoExcecao" name="funcao-funcionario" class="select2" style="border-color: red;" required>
              </select>
            </div>
          </div>

          <br><br>

          <div class="ui large label">
            Dias de Trabalho - Semanal</div>
            <br><br>
            <div class="ui slider checkbox">
              <input type="checkbox" name="SEG">
              <label>Segunda</label>
            </div>
            <br>
            <br>
            <div class="ui slider checkbox">
              <input type="checkbox" name="TER">
              <label>Terça</label>
            </div>
            <br>
            <br>
            <div class="ui slider checkbox">
              <input type="checkbox" name="QUA">
              <label>Quarta</label>
            </div>
            <br>
            <br>
            <div class="ui slider checkbox">
              <input type="checkbox" name="QUI">
              <label>Quinta</label>
            </div>
            <br>
            <br>
            <div class="ui slider checkbox">
              <input type="checkbox" name="SEX">
              <label>Sexta</label>
            </div>

            <br><br>

            <div class="ui fluid label">
            Descrição do horário de Trabalho⠀⠀⠀
            <div class="ui fluid icon input">
              <input type="text" style="border-color: red;">
              <i class="icon keyboard outline"></i>
            </div>
          </div>
          

        </div>
      </form>
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

          $("#select-funcaoExcecao").select2({
            data: options,
            placeholder: "Selecione uma função",
            allowClear: true
          });

          if (tipoExcecaoSalvoNoBanco) {
            $("#select-tipoExcecao").val(tipoExcecaoSalvoNoBanco).trigger("change");
          }
        },
        error: function() {
          alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        },
      });
    }
  </script>