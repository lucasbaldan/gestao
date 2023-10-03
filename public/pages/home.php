<?php
$nomePagina = "Página Inicial";
include("./header_semantic_main.php");
include("./header.php");
?>

<body>
    <div class="ui middle aligned center aligned grid" style="position: fixed; bottom: 40%; left: 0; right: 0;">
        <div class="column">
            <h1 class="ui">BEM VINDO AO SISTEMA <?= SYS_NAME ?></h1>
            <h2>Utilize o menu abaixo para utilizar as funcionalidades do sistema</h2>
</br>
            <i class="huge angle double down icon"></i>
        </div>
    </div>
</body>

<?php
include("./footer_menu.php");
?>