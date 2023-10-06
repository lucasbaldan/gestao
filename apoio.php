<thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dados as $dadoTipoExcecao) { ?>
                    <tr>
                        <td><?= $dadoTipoExcecao['CD_TIPO_EXCECAO'] ?></td>
                        <td><?= $dadoTipoExcecao['NM_TIPO_EXCECAO'] ?></td>
                        <td><?= "<button class='ui mini icon button blue' onclick='editarRegistro(" . $dadoTipoExcecao['CD_TIPO_EXCECAO'] . ")'><i class='pencil alternate icon'></i></button>" ?>
                            <?= "<button class='ui mini icon button red' onclick='excluirRegistro(" . $dadoTipoExcecao['CD_TIPO_EXCECAO'] . ")'><i class='trash alternate icon'></i></button>" ?></td>
                    </tr>
                <?php } ?>
            </tbody>?>


            <script>
              $(document).ready(function() {
    $('#tabela').DataTable({
        "ajax": {
            "url": "seu_arquivo.php", // Substitua pelo URL do seu arquivo de servidor que fornece os dados.
            "dataSrc": "" // Deixe em branco se os dados retornados já estiverem em um formato adequado.
        },
        "columns": [
            { "data": "coluna1" }, // Substitua "coluna1" pelo nome da sua primeira coluna de dados
            { "data": "coluna2" }, // Substitua "coluna2" pelo nome da sua segunda coluna de dados
            // Adicione mais colunas conforme necessário
        ]
    });
});
            </script>