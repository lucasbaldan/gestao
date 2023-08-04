<script>
    $(document).ready(function() {
        $('.ui.sidebar').sidebar('setting', 'dimPage', false);
        $('#menuButton').click(function() {
            $('.ui.sidebar').sidebar('toggle');
        });


        $('#gopage').click(function() {
            $('.ui.dimmer').dimmer("show")
        });
    });
</script>

<footer>
    <div class="ui bottom horizontal inverted sidebar labeled icon menu">
        <a href="./listfuncionarios.php" class="item" id="gopage">
            <i class="id badge icon"></i>
            Funcionários
        </a>
        <a href="./listsetores.php" class="item" id="gopage">
            <i class="icon building"></i>
            Setores
        </a>
        <a href="./listfuncoes.php" class="item" id="gopage">
            <i class="clipboard outline icon"></i>
            Funções
        </a>
        <a href="./listexcecoes.php" class="item" id="gopage">
            <i class="calendar alternate icon"></i>
            Excecões
        </a>
        <a href="./listtiposexcecoes.php" class="item" id="gopage">
            <i class="tag icon"></i>
            Tipos de Exceções
        </a>
    </div>

    <div class="pusher">
        <div class="ui page dimmer">
            <div class="center">
                <i class="ui text loader">Carregando...</i>
            </div>
        </div>
        <div class="ui" style="position: fixed; bottom: 0; left: 48%; right: 48%; z-index: 9999;">
            <button id="menuButton" class="ui icon button">
                <i class="loading compass outline icon"></i>
                <div class="vertical text">MENU</div>
            </button>
        </div>
    </div>
</footer>

</html>