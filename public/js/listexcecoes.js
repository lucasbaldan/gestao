$(document).ready(function () {

    $(".ui.negative.message").hide();
    $(".ui.positive.message").hide();
    var table = $("#myTable").DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
        },
        order: [
            [0, "desc"]
        ],
        columnDefs: [{
            targets: "_all",
            className: "dt-center",
        },
            // {
            //     targets: 0, // Índice da coluna (neste exemplo, a primeira coluna)
            //     width: '10px' // Defina o tamanho desejado em pixels
            // },
            // {
            //     targets: 2, // Índice da coluna (neste exemplo, a terceira coluna)
            //     width: '90px' // Defina o tamanho desejado em pixels
            // },
        ],
        initComplete: function () {
            this.api()
                .columns()
                .every(function () {
                    var column = this;
                    var title = $(column.header()).text();

                    var input = $(
                        "<h4>" +
                        title +
                        '</h4><input class="ui input responsive-input" type="text" placeholder="' + title +
                        '..." />'
                    )
                        .appendTo($(column.header()).empty())
                        .on("keyup change", function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
        },
    });

    $("#form-CAD-Excecao").form({
        fields: {
            user: {
                identifier: "nameTipoExcecao",
                rules: [{
                    type: "empty",
                    prompt: ".",
                },],
            },
        },
        onSuccess: function (event, fields) {
            event.preventDefault(); // Impede o envio padrão do formulário

            // Obtém os dados do formulário
            var formData = $("#form-CAD-TipoExcecao").serialize();

            // Envia a requisição AJAX
            $.ajax({
                type: "POST",
                url: "./../../App/Controllers/TiposExcecoes.php",
                data: formData,
                beforeSend: function () {
                    // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
                    $(".ui.positive.right.labeled.icon.button").addClass("loading");
                },
                success: function (response) {
                    // Manipula a resposta recebida
                    //alert(response); // Exemplo: exibe a resposta em um alerta

                    // Se a validação for bem-sucedida, redirecione para outra página
                    if (response === "inserido" || response === "alterado") {
                        $(".ui.positive.message").transition("fade in");

                        $(".ui.positive.right.labeled.icon.button").removeClass("loading");

                        // Agendar a remoção da mensagem após 4 segundos
                        setTimeout(function () {
                            $(".ui.positive.message").transition("fade out");
                            $("#CADmodal").modal("hide");
                            location.reload();
                        }, 1500);
                    }
                    if (response === "erro") {
                        $("#CADmodal").modal("hide");
                        $(".ui.negative.message").transition("fade in");

                        setTimeout(function () {
                            location.reload();
                            $(".ui.negative.message").transition("fade out");
                        }, 1500);
                    }
                },
                error: function () {
                    alert(
                        "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
                    );
                },
                complete: function () {
                    // Remova a animação de "carregando" aqui, se necessário
                },
            });
        },
    });

    $("#CAD").click(function () {
        $("#CADmodal").modal("show");
        $("#nameTipoExcecao").val("");
    });

    $(".ui.orange.basic.button").click(function () {
        $("#CADmodal").modal("hide");
    });
});

function editarRegistro(idTipoExcecao) {
    $("#CADmodal").modal("show");
    $.ajax({
        type: "POST",
        url: "./../../App/Controllers/TiposExcecoes.php",
        data: {
            cdTipoExcecao: idTipoExcecao,
            funcao: "listJSON",
        },
        success: function (data) {
            var tipoExcecao = JSON.parse(data)[0];

            $("#nameTipoExcecao").val(tipoExcecao.NM_TIPO_EXCECAO);
            $("#cdTipoExcecao").val(tipoExcecao.CD_TIPO_EXCECAO);
        },
        error: function (xhr, status, error) {
            console.error(error); // Mostra o erro no console do navegador
            alert("Erro ao carregar os dados da Funcao.");
        },
    });
}

function excluirRegistro(idExcecao) {
    $("#confirmacaoExclusao").modal("show");

    // Função de callback para executar o Ajax após a confirmação
    function confirmadoExclusao() {
        $.ajax({
            type: "POST",
            url: "./../../App/Controllers/Excecoes.php",
            data: {
                cdExcecao: idExcecao,
                funcao: "excluir",
            },
            beforeSend: function () {
                // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
                $("#botaoconfirmaExclusao").addClass("loading");
            },
            success: function (response) {
                alert(response);
                if (response === "excluido") {
                    $(".ui.positive.message").transition("fade in");

                    $(".ui.positive.right.labeled.icon.button").removeClass("loading");

                    // Agendar a remoção da mensagem após 4 segundos
                    setTimeout(function () {
                        $(".ui.positive.message").transition("fade out");
                        $("#CADmodal").modal("hide");
                        location.reload();
                    }, 2000);

                } else if (response === "erro") {
                    $("#confirmacaoExclusao").modal("hide");
                    $(".ui.negative.message").transition("fade in");

                    setTimeout(function () {

                        $(".ui.negative.message").transition("fade out");
                        location.reload();
                    }, 1500);
                } else {
                    $("#confirmacaoExclusao").modal("hide");
                    $(".ui.negative.message").transition("fade in");

                    setTimeout(function () {
                        //location.reload();
                        $(".ui.negative.message").transition("fade out");
                        location.reload();
                    }, 1500);
                }
            },
            error: function (xhr, status, error) {
                console.error(error);
                alert("Erro ao Executar operação");
            },
        });
    }

    // Vincula a função de callback ao evento de clique do botão de confirmação
    $("#botaoconfirmaExclusao").on("click", confirmadoExclusao);
}

async function carregar_tipoExecoes(nmTipoExcecao){

    if(nmTipoExcecao.length >= 1){

    setTimeout(function () {
        $.ajax({
            type: "POST",
            url: "./../../App/Controllers/TiposExcecoes.php",
            data: {
                nmTipoExcecao: nmTipoExcecao,
                funcao: "listJSON",
            },
            success: function (data) {
                var tipoExcecao = JSON.parse(data);

                var tiposExcecoesInput = [];

                for (var i = 0; i < tipoExcecao.length; i++) {
                    var nome = tipoExcecao[i].NM_TIPO_EXCECAO;
                    tiposExcecoesInput.push(nome);
                }

                var content = tiposExcecoesInput.map(function (nome) {
                    return {
                        title: nome
                    };
                });

                $('.ui.search').search({
                    source: content,
                    fullTextSource: false
                });
    
            },
            error: function (xhr, status, error) {
                console.error(error); // Mostra o erro no console do navegador
                alert("Erro ao carregar os dados da Funcao.");
            },
        }); 
    }, 2000);
}
}