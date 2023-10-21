<?php
$nomePagina = "Funcionários - Cadastro";
include_once("./header_semantic_main.php");
include_once("./header.php");
include_once("./footer_menu.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cdFuncionario'])) {
  $cdFuncionario = $_POST['cdFuncionario'];
  echo "<script>var codigoFuncionario = " . json_encode($cdFuncionario) . ";</script>";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['cdFuncionario'])) {
  echo "ERRO AO CARREGAR PÁGINA";
  exit;
}
?>
<link rel="stylesheet" type="text/css" href="./../css/listsetores.css" media="screen" />

<style>
  .ui.slider.checkbox {
    padding-right: 50px;
  }
</style>


<body>

<div class="ui page dimmer" id="operacaoSucesso">
    <div class="center">
      <h2 class="ui inverted icon header">
        <i class="green check icon"></i>
        Sucesso!
        <div class="sub header">Operação efetuada com êxito.</div>
      </h2>
    </div>
</div>


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
        <input type="hidden" id="cdFuncionario" name="cdFuncionario">
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
              <option value="">⠀⠀⠀</option>
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

  <script src="../js/pages/formfuncionarios.js"></script>