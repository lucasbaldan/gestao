<?php
include("header_semantic_main.php");
$Sessao = new \App\Controllers\Sessions();
if ($Sessao->verificaSessao()) {
   $Sessao->deslogar();
}
?>

<style type="text/css">
  body {
    background-color: #dee2e6;
  }

  body>.grid {
    height: 100%;
  }

  .image {
    margin-top: -100px;
  }

  .column {
    max-width: 460px;
  }

  .custom-font {
    font-size: 60px;
  }
</style>

<script src="./../js/pages/login.js"></script> 


<body>
  <div class="ui middle aligned center aligned grid">
    <div class="column">
      <h2 class="ui teal header">
        <div class="custom-font" style="padding-bottom: 25px;">
          <i class="edit outline icon"></i>
          <?= SYS_NAME ?>
        </div>
      </h2>
      <form class="ui large fluid form"> <!-- action="./../../App/Controllers/Login.php" method="POST" >-->
        <input type="hidden" name="funcao" value="efetuarLogin">
        <div class="ui stacked segment">
          <div class="field">
            <div class="ui left icon input">
              <i class="user icon"></i>
              <input type="text" name="user" placeholder="Usuário" value="admin">
            </div>
          </div>
          <div class="field">
            <div class="ui left icon input">
              <i class="lock icon"></i>
              <input type="password" name="password" placeholder="Senha" value="admin">
            </div>
          </div>
          <button class="ui fluid large teal submit button" type="submit">Acessar ⠀<i class="sign in icon"></i></button>

        </div>

        <div class="ui error message"></div>
        <div id="error-modal" class="ui tiny modal">
          <div class="header" style="background-color: red; color: white;">
            Erro de Autenticação
          </div>
          <div class="content">
            <p><b>Usuário e/ou senha incorretos!</b></p>
          </div>
          <div class="actions">
            <p>Clique fora da mensagem para tentar novamente!</p>
          </div>
        </div>

      </form>

      <div class="ui message">
        Versão - v.1.0
        </br>
        <hr>
        <?php echo date('Y') ?> - Lucas F. Baldan <i class="trademark icon"></i>

      </div>
    </div>
  </div>