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
          <div class="ui large fluid label">
            Nome do Funcionário⠀⠀⠀
            <div class="ui large fluid icon input">
              <input type="text" style="border-color: red;">
              <i class="icon keyboard outline"></i>
            </div>
          </div>
        </div>
        <div class="ui bottom attached tab segment" data-tab="funcionario-funcionais">
          <h3>Cadastros Funcionais do Funcionário</h3>
          <div class="ui large fluid label">
            Data de início⠀⠀⠀
            <div class="ui large input">
              <input type="date" style="border-color: red;">
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>



  <script>
    $('#tabnav .item')
      .tab();
  </script>