var table;

$(document).ready(function () {
  table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/TiposExcecoes.php",
      data: {
        funcao: "listJSON",
      },
      dataSrc: "",
      error: function () {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_TIPO_EXCECAO" },
      { data: "NM_TIPO_EXCECAO" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<button class='ui mini icon button blue' onclick='editarRegistro(" +
            row.CD_TIPO_EXCECAO +
            ")'><i class='pencil alternate icon'></i></button>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_TIPO_EXCECAO +
            ")'><i class='trash alternate icon'></i></button>";
          return editarBtn + excluirBtn;
        },
      },
    ],

    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
    },
    order: [[0, "desc"]],
    columnDefs: [
      {
        targets: "_all",
        className: "dt-center",
      },
    ],
    initComplete: function () {
      var api = this.api();

      api.columns().every(function () {
        var column = this;
        var title = $(column.header()).text();

        var input;

        if (column.index() === 0) {

          input = $('<div class="ui fluid input focus"><input type="number" placeholder="Procurar..."></div>');
        } else if (column.index() === 1) {

          input = $('<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>');
        } else if (column.index() === 2) {
          
        } else {
          
          input = $('<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>');
        }


        $(column.header())
          .empty()
          .append($("<h4>" + title + "</h4>"))
          .append(input);

        // Adicione um ouvinte de eventos para atualizar a pesquisa ao digitar ou alterar
        input.find('input').on("keyup change", function () {
          if (column.search() !== this.value) {
            column.search(this.value).draw();
          }
        });

        input.on("click", function (e) {
          e.stopPropagation();
      });

      });
    },

  });

  $("#form-CAD-TipoExcecao").form({
    onSuccess: function (event, fields) {
      event.preventDefault();
      if (
        $("#nameTipoExcecao").val().trim() === "" ||
        $("#nameTipoExcecao").val().trim().length < 3
      ) {
        $("#preencherNome").show();
        return false;
      }

      var formData = $("#form-CAD-TipoExcecao").serialize();

      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/TiposExcecoes.php",
        data: formData,
        beforeSend: function () {
          // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
          $(".ui.positive.right.labeled.icon.button").addClass(
            "loading disabled"
          );
          $(".ui.orange.basic.button").addClass("disabled");
        },
        success: function (response) {
          response = JSON.parse(response);
          if (
            response.status === "inserido" ||
            response.status === "alterado"
          ) {
            // Agendar a remoção da mensagem após 4 segundos
            setTimeout(function () {
              $("#myTable").DataTable().clear().draw();
              $("#CADmodal").modal("hide");
              $(".ui.positive.right.labeled.icon.button").removeClass(
                "loading disabled"
              );
              $(".ui.orange.basic.button").removeClass("disabled");
              toastSucesso();
            }, 500);

            $("#myTable").DataTable().ajax.reload();
          } else if (response.status === "erro") {
            toastErro(response.response);
            $(".ui.positive.right.labeled.icon.button").removeClass(
              "loading disabled"
            );
            $(".ui.orange.basic.button").removeClass("disabled");
          } else {
            window.location.href = "generalError.php";
          }
        },
        error: function () {
          toastErro("Erro ao adicionar ou alterar Tipo de Exceções");
          $(".ui.red.basic.cancel.button").removeClass("disabled");
        },
      });
    },
  });

  $("#CAD").click(function () {
    $("#cdTipoExcecao").val("");
    $("#nameTipoExcecao").val("");
    $("#preencherNome").hide();
    $("#CADmodal").modal({ closable: false }).modal("show");
  });

  $(".ui.orange.basic.button").click(function () {
    $("#CADmodal").modal("hide");
    $("#cdTipoExcecao").val("");
    $("#nameTipoExcecao").val("");
  });
});

function editarRegistro(idTipoExcecao) {
  $(".ui.dimmer")
    .dimmer({ closable: false, interactive: false, duration: 5 })
    .dimmer("show");
  $("#cdTipoExcecao").val("");
  $("#nameTipoExcecao").val("");
  $("#preencherNome").hide();
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
      $(".ui.dimmer")
        .dimmer({ closable: false, interactive: false, duration: 5 })
        .dimmer("hide");
      setTimeout(function () {
        $("#CADmodal").modal({ closable: false }).modal("show");
      }, 60);
    },
    error: function (xhr, status, error) {
      console.error(error); // Mostra o erro no console do navegador
      alert("Erro ao carregar os dados da Funcao.");
    },
  });
}

function excluirRegistro(idTipoExcecao) {
  $("#confirmacaoExclusao").modal({ closable: false }).modal("show");

  // Função de callback para executar o Ajax após a confirmação
  function confirmadoExclusao() {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/TiposExcecoes.php",
      data: {
        cdTipoExcecao: idTipoExcecao,
        funcao: "excluir",
      },
      beforeSend: function () {
        alert(idTipoExcecao);
        // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $(".ui.red.basic.cancel.button").addClass("disabled");
      },
      success: function (response) {
        if (response === "excluido") {
          // Agendar a remoção da mensagem após 4 segundos
          setTimeout(function () {
            toastSucesso();
            $("#myTable").DataTable().clear().draw();
            $("#confirmacaoExclusao").modal("hide");
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $(".ui.red.basic.cancel.button").removeClass("disabled");
          }, 2000);
          $("#myTable").DataTable().ajax.reload();
        } else if (response === "erro") {
          toastErro();
          $("#botaoconfirmaExclusao").removeClass("loading disabled");
          $(".ui.red.basic.cancel.button").removeClass("disabled");
        } else {
          window.location.href = "generalError.php";
        }
        idTipoExcecao = null;
      },
      error: function (xhr, status, error) {
        console.error(error);
        toastErro();
        $("#botaoconfirmaExclusao").removeClass("loading disabled");
        $(".ui.red.basic.cancel.button").removeClass("disabled");
      },
    });
  }

  // Vincula a função de callback ao evento de clique do botão de confirmação
  $("#botaoconfirmaExclusao").on("click", confirmadoExclusao);
}

function toastSucesso() {
  $.toast({
    title: "SUCESSO!",
    class: "success",
    position: "bottom right",
    displayTime: "10000",
    showProgress: "top",
    classProgress: "black",
    message: "Operação efetuada com êxito!",
    showIcon: "check circle",
  });
}

function toastErro($mesagem) {
  $.toast({
    title: "ERRO!",
    class: "centered error",
    position: "bottom right",
    displayTime: "10000",
    showProgress: "top",
    classProgress: "black",
    message: $mesagem,
    showIcon: "skull crossbones",
  });
}
