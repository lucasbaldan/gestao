<?php
include("header_semantic_main.php");
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
        <div class="item right"><b>Usuário: LUCAS FAÉ BALDAN </b></div>
        <div class="item ui icon button"><a href="login.php"><i class="sign out alternate icon"></i></a></div>
      </div>
    </div>
  </div>
</header>