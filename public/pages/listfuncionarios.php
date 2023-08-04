<?php
$nomePagina = "Funcionários - Listagem";
include("./header_semantic_main.php");
include("./header.php");
$users = new \App\Controllers\Login();
$dados = $users->listUsuarios();
?>

<body>
    <div class="ui container">
        </br>
        <table id="myTable" class="ui red celled table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Usuário no sistema</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $dadoPessoa) { ?>
                    <tr>
                        <td><?= $dadoPessoa['CD_USUARIO'] ?></td>
                        <td><?= $dadoPessoa['NM_PESSOA'] ?></td>
                        <td><?= $dadoPessoa['USUARIO'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    
                    <th>Código...</th>
                    <th>Nome...</th>
                    <th>Usuário...</th>
                    
                </tr>
            </tfoot>
        </table>
    </div>
</body>

<script>
    $(document).ready(function() {
        var table = $('#myTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json',
            },
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var title = $(column.header()).text();

                    var input = $('<input class="ui input" type="text" placeholder="' + title + '" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change', function() {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
            }
        });
    });
</script>

<?php
include("./footer_menu.php");
?>