$(document).ready(function () {
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
          $("#fechaModalCAD").addClass("disabled");
        },
        success: function (response) {
          response = JSON.parse(response);
          if (
            response.status === "inserido" ||
            response.status === "alterado"
          ) {
            $("#myTable").DataTable().clear().draw();
            // Agendar a remoção da mensagem após 4 segundos
            setTimeout(function () {
              $("#CADmodal").modal("hide");
              $(".ui.positive.right.labeled.icon.button").removeClass(
                "loading disabled"
              );
              $("#fechaModalCAD").removeClass("disabled");
              toastSucesso();
              $("#myTable").DataTable().ajax.reload();
            }, 1000);

          } else if (response.status === "erro") {
            toastErro(response.response);
            $(".ui.positive.right.labeled.icon.button").removeClass(
              "loading disabled"
            );
            $("#fechaModalCAD").removeClass("disabled");
          } else {
            window.location.href = "generalError.php";
          }
        },
        error: function () {
          toastErro("Erro ao adicionar ou alterar Tipo de Exceções");
          $("#fechaModalCAD").removeClass("disabled");
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

  $("#fechaModalCAD").click(function () {
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
  $("#confirmacaoExclusao").modal({
    closable: false,
    onApprove: function() {
      confirmadoExclusao(idTipoExcecao);
      return false;
    },
  }).modal("show");
  // $("#confirmacaoExclusao").modal({ closable: false }).modal("show");
  // // Vincula a função de callback ao evento de clique do botão de confirmação
  // $("#botaoconfirmaExclusao").on("click", function() {
  //   // Chamar a função confirmadoExclusao passando o idTipoExcecao
  //   confirmadoExclusao(idTipoExcecao);
  // });

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
        $("#fechaModalEXC").addClass("disabled");
            },
      success: function (response) {
        if (response === "excluido") {
          $("#myTable").DataTable().clear().draw();
          setTimeout(function () {
            toastSucesso();
            $("#confirmacaoExclusao").modal("hide");
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $("fechaModalEXC").removeClass("disabled");
            $("#myTable").DataTable().ajax.reload();
          }, 2000);
        } else if (response === "erro" || response === "integridade") {
          response === "integridade" ? toastAtencao("OPERAÇÃO NEGADA! Essa ação compromete a integridade da base de dados") : toastErro();
          $("#botaoconfirmaExclusao").removeClass("loading disabled");
          $("#fechaModalEXC").removeClass("disabled");
          
        } else {
          window.location.href = "generalError.php";
        }

      },
      error: function (xhr, status, error) {
        console.error(error);
        toastErro();
        $("#botaoconfirmaExclusao").removeClass("loading disabled");
        $("#fechaModalEXC").removeClass("disabled");
      },
    });
  }
}
