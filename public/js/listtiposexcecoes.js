$(document).ready(function () {
  $(".ui.negative.message").hide();

  var table = $("#myTable").DataTable({
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
      this.api()
        .columns()
        .every(function () {
          var column = this;
          var title = $(column.header()).text();

          var input = $(
            "<h4>" +
              title +
              '</h4><input class="ui input" type="text" placeholder="' /* + title*/ +
              '" />'
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
          if (response === "inserido" || response === "alterado") {

            // Agendar a remoção da mensagem após 4 segundos
            setTimeout(function () {
              $("#CADmodal").modal("hide");
              $(".ui.positive.right.labeled.icon.button").removeClass("loading disabled");
              $(".ui.orange.basic.button").removeClass("disabled");
              toastSucesso();
            }, 500);

            $("#myTable").DataTable().ajax.reload();
          } else if (response === "erro") {
            $(".ui.negative.message").transition("fade in");
            $(".ui.positive.right.labeled.icon.button").removeClass(
              "loading disabled"
            );
            $(".ui.orange.basic.button").removeClass("disabled");

            setTimeout(function () {
              $(".ui.negative.message").transition("fade out");
            }, 1500);
          } else {
            window.location.href = "generalError.php";
          }
        },
        error: function () {
          $(".ui.negative.message").transition("fade in");
          alert(
            "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
          );
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
    .dimmer({ closable: false, interactive: false })
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
        .dimmer({ closable: false, duration: 5, interactive: false })
        .dimmer("hide");
      setTimeout(function () {
        $("#CADmodal").modal({ closable: false }).modal("show");
      }, 25);
    },
    error: function (xhr, status, error) {
      console.error(error); // Mostra o erro no console do navegador
      alert("Erro ao carregar os dados da Funcao.");
    },
  });
}

function excluirRegistro(idTipoExcecao) {
  $("#confirmacaoExclusao").modal("show");

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
        // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $(".ui.red.basic.cancel.button").addClass("disabled");
      },
      success: function (response) {
        if (response === "excluido") {

          // Agendar a remoção da mensagem após 4 segundos
          setTimeout(function () {
            $("#confirmacaoExclusao").modal("hide");
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $(".ui.red.basic.cancel.button").removeClass("disabled");
          }, 2000);
          $("#myTable").DataTable().ajax.reload();
        } else if (response === "erro") {
          $(".ui.negative.message").transition("fade in");
          $("#botaoconfirmaExclusao").removeClass("loading disabled");
          $(".ui.red.basic.cancel.button").removeClass("disabled");

          setTimeout(function () {
            $(".ui.negative.message").transition("fade out");
          }, 1500);
        } else {
          window.location.href = "generalError.php";
        }
      },
      error: function (xhr, status, error) {
        console.error(error);
        $(".ui.negative.message").transition("fade in");
        alert("Erro ao Executar operação, tente novamente mais tarde");
      },
    });
  }

  // Vincula a função de callback ao evento de clique do botão de confirmação
  $("#botaoconfirmaExclusao").on("click", confirmadoExclusao);
}

function toastSucesso() {
  $.toast({
    title: 'SUCESSO!',
    class: 'success',
    position: 'bottom right',
    displayTime: "20000",
    showProgress: "top",
    classProgress: "black",
    message: "Operação efetuada com êxito!",
  });
}
