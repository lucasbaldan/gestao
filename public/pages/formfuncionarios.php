<?php
$nomePagina = "Funcionários - Cadastro";
include("./header_semantic_main.php");
include("./header.php");
include("./footer_menu.php");
?>

<style>
  .ui.celled.striped.table th {
    text-align: center;
  }

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

          <table class="ui celled striped table">
            <thead>
              <tr header="Registros atuais">
                <th colspan="7" style="background-color: black; color: white;">
                  Registros atuais
                </th>
              </tr>
              <tr>
                <th style="background-color: gray; color: white;">Matrícula</th>
                <th style="background-color: gray; color: white;">Data Início</th>
                <th style="background-color: gray; color: white;">Data término</th>
                <th style="background-color: gray; color: white;">Almoço?</th>
                <th style="background-color: gray; color: white;">Função</th>
                <th style="background-color: gray; color: white;">Dias Trabalho</th>
                <th style="background-color: gray; color: white;">Descrição horário</th>
              </tr>
            </thead>
            <tbody>
              <th>Nenhum Registro Cadastrado</th>
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

    });

    const button = document.querySelector(".ui.right.labeled.icon.green.button");
    button.addEventListener("click", collectData);


    function collectData() {
      // Create an empty object to store the data
      const data = {};

      // Get the values of all the input fields
      const matricula = document.getElementById("matricula").value;
      const dataInicio = document.getElementById("dataInicio").value;
      const dataTermino = document.getElementById("dataTermino").value;
      const almoco = document.getElementById("select-almoco").value;
      const funcao = document.getElementById("select-funcao").value;
      const diasTrabalho = [];
      const descricaoHorario = document.getElementById("descricaoHorario").value;

      // Get the values of all the checkboxes
      const checkboxElements = document.querySelectorAll("input[type='checkbox']");
      for (const checkboxElement of checkboxElements) {
        if (checkboxElement.checked) {
          diasTrabalho.push(checkboxElement.name);
        }
      }

      // Add the data to the object
      data.matricula = matricula;
      data.dataInicio = dataInicio;
      data.dataTermino = dataTermino;
      data.almoco = almoco;
      data.funcao = funcao;
      data.diasTrabalho = diasTrabalho;
      data.descricaoHorario = descricaoHorario;

      // Present the data in the table
      const table = document.querySelector("table");
      table.querySelector("tbody").innerHTML = "";
      for (const key in data) {
        const tr = document.createElement("td");
        const td = document.createElement("tr");
        td.textContent = data[key];
        tr.appendChild(td);
        table.querySelector("tbody").appendChild(tr);
      }

      // Save the data in a variable
      const variableName = "data";
      window[variableName] = data;
    }


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