<?php
$Sessao = new \App\Controllers\Sessions();
$dados = $Sessao->getInfoUsuario();
$nomeUsuario = $dados['NOME'];
if ($Sessao->verificaSessao() != true) {
   $Sessao->deslogar();
   header("Location: /gestao/public/pages/generalError.php");
   exit;
}

?>
<style>
  header {
    padding-top: 25px;
    /* Para evitar que o conteúdo seja sobreposto pelo cabeçalho fixo */
  }

  .item:hover i {
    filter: brightness(70%);
    transition: 1s;
  }

  .no-divider:before {
    content: none !important;
  }
</style>

<header>
  <div class="ui fixed inverted menu">
    <div class="ui container">
      <div class="left menu">
        <div class="header item centered"><i class="edit outline icon"></i><?= SYS_NAME ?></div>
      </div>
      <div class="middle menu">
        <div class="item no-divider"><?=$nomePagina?></div>
      </div>
      <div class="right menu">
        <div class="item right"><b>Usuário: <?=$nomeUsuario?> </b></div>
        <div class="item ui icon button" id="deslogarButton"><i class="sign out alternate icon"></i></a></div>
      </div>
    </div>
  </div>
</header>

<script>
  document.getElementById("deslogarButton").addEventListener("click", function() {
    $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Sessions.php",
        data: {
          funcao: "deslogar",
          way: "AJAX",
        },
        success: function(response) {
          if(response === 'deslogado'){
            window.location.href = "login.php";
          }
          else{
            window.location.href = "generalError.php";
          }

        },
        error: function() {
          alert("Erro ao Deslogar");
        },
      });
  });
</script>