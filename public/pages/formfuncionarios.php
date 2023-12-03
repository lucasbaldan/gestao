<?php
$nomePagina = "Funcionários - Cadastro";
include_once("./header_semantic_main.php");
include_once("./header.php");
include_once("./footer_menu.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cdFuncionario'])) {
  $cdFuncionario = $_POST['cdFuncionario'];
  echo "<script>var codigoFuncionario = " . $cdFuncionario . ";</script>";
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
    <a class="<?php if(!$cdFuncionario){echo "item disabled";} else{echo "item";}?>" data-tab="funcionario-funcionais">Vínculos Funcionais</a>
    </div>

    <div class="ui form">
      <div class="ui bottom attached tab segment active" data-tab="funcionario-geral">
        <form action="./../../App/Controllers/Funcionarios.php" method="POST" id="form-CAD-funcionario">
          <input type="hidden" name="funcao" value="controlar" readonly required>
        <h3>Cadastros Gerais do Funcionário</h3>

        <div class="disabled field">
          <label>Código</label>
          <input type="text" name="cdFuncionario" id="cdFuncionario" placeholder="Código gerado automaticamente ao inserir" readonly>
        </div>

        <div class="two fields">
          <div class="required field">
            <label>Nome:</label>
            <div class="ui input">
              <input type="text" name="nomeFuncionario" id="nomeFuncionario" placeholder="Nome do Funcionário...">
            </div>
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
              Preencha o campo NOME com mais de 3 caracteres
            </div>
          </div>

          <br>

          <div class="required field">
            <label>Setor:</label>
            <div class="ui input">
              <select id="select-setor" name="selectSetor" class="select2" style="border-color: red;" required></select>
            </div>
            <div class="ui pointing red basic label" id="preencherSetor" style="display: none;">
              Selecione o campo SETOR
            </div>
          </div>
        </div>
      </form>
      </div>


      <div class="ui bottom attached tab segment" data-tab="funcionario-funcionais">
        <h3>Cadastros Funcionais do Funcionário</h3>
        <form id="formVinculoFuncional">
        <input type="hidden" name="funcao" value="controlar" readonly required>
        <input type="hidden" id="cdVinculoFuncional" name="cdVinculoFuncional" readonly>
        <input type="hidden" id="cdFuncionario" name="cdFuncionario" value="<?php if($cdFuncionario){echo $cdFuncionario;} else echo "";?>" readonly>

        <div class="three fields">
          <div class="required field">
            <label>Matricula:</label>
            <div class="ui input">
              <input type="text" id="matricula" name="matricula" placeholder="">
            </div>
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
              Preencha o campo NOME com mais de 3 caracteres
            </div>
          </div>

          <div class="required field">
            <label>Data de Admissão:</label>
            <div class="ui input">
              <input type="date" id="dataInicio" name="dataAdmissao">
            </div>
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
              Preencha o campo NOME com mais de 3 caracteres
            </div>
          </div>

          <div class="field">
            <label>Data de Demissão:</label>
            <div class="ui input">
              <input type="date" id="dataTermino" name="dataDemissao">
            </div>
          </div>

        </div>

        <div class="two fields">

          <div class="required field">
            <label>Faz Horário de Almoço?</label>
            <select id="select-almoco" class="ui fluid dropdown" name="almoco">
              <option value="">⠀⠀⠀</option>
              <option value="1">Sim</option>
              <option value="0">Não</option>
            </select>
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
              Preencha o campo NOME com mais de 3 caracteres
            </div>
          </div>

          <div class="required field">
            <label>Função</label>
            <div class="ui input">
              <select id="select-funcao" name="idFuncao" style="width: 100%;">
              </select>
            </div>
            <div class="ui pointing red basic label" id="preencherNome" style="display: none;">
              Preencha o campo NOME com mais de 3 caracteres
            </div>
          </div>
        </div>

        <br>

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

        <div class="field">
            <label>Descrição do horário de Trabalho:</label>
            <div class="ui fluid icon input">
            <input type="text" id="descricaoHorario" name="descHorario">
            <i class="icon keyboard outline"></i>
            </div>
          </div>

        <br>
        <button class="ui right labeled icon green button" id="addfuncional" type="submit">
          <i class="plus square outline icon"></i>
          Adicionar / Alterar
        </button>
        </form>

        <table id="funcionalTable" class="ui blue celled table" style="min-width: 100%;">
          <thead>
            <tr>
              <th>Codigo</th>
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

    <div id="confirmacaoExclusao" class="ui modal">
    <div class="header" style="background-color: orange; color: white;">Confirmação</div>
    <div class="content">
        <div class="ui icon message">
            <i class="attention icon"></i>
            <div class="content">
                <div class="header">Atenção</div>
                <p>Você tem certeza que deseja excluir este registro do sistema?</p>
            </div>
        </div>
    </div>
    <div class="actions">
        <div id="fechaModalEXC" class="ui inverted red cancel button">Cancelar</div>
        <div id="botaoconfirmaExclusao" class="ui positive green button">Concordo <i class="trash icon"></i></div>
    </div>
</div>

  </div>

  <script src="../js/default/toast.js"></script>
  <script src="../js/pages/formfuncionarios.js"></script>