$(document).ready(function () {
  var table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/Funcoes.php",
      data: {
        funcao: "listJSON",
      },
      dataSrc: "",
      error: function () {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_FUNCAO" },
      { data: "NM_FUNCAO" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<button class='ui mini icon button blue' onclick='editarRegistro(" +
            row.CD_FUNCAO +
            ")'><i class='pencil alternate icon'></i></button>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_FUNCAO +
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
          input = $(
            '<div class="ui fluid input focus"><input type="number" placeholder="Procurar..."></div>'
          );
        } else if (column.index() === 1) {
          input = $(
            '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
          );
        } else if (column.index() === 2) {
        } else {
          input = $(
            '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
          );
        }

        $(column.header())
          .empty()
          .append($("<h4>" + title + "</h4>"))
          .append(input);

        // Adicione um ouvinte de eventos para atualizar a pesquisa ao digitar ou alterar
        input.find("input").on("keyup change", function () {
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

  $("#form-CAD-funcao").form({
    onSuccess: function (event, fields) {
      event.preventDefault();

      if (
        $("#nameFuncao").val().trim() === "" ||
        $("#nameFuncao").val().trim().length < 3
      ) {
        $("#preencherNome").show();
        return false;
      }

      var formData = $("#form-CAD-funcao").serialize();

      // Envia a requisição AJAX
      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Funcoes.php",
        data: formData,
        beforeSend: function () {
          $("#cadSubmit").addClass("loading disabled");
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
              $("#cadSubmit").removeClass(
                "loading disabled"
              );
              $("#fechaModalCAD").removeClass("disabled");
              toastSucesso();
              $("#myTable").DataTable().ajax.reload();
            }, 1000);

          } else if (response.status === "erro") {
            toastErro(response.response);
            $("#cadSubmit").removeClass(
              "loading disabled"
            );
            $("#fechaModalCAD").removeClass("disabled");
          } else {
            window.location.href = "generalError.php";
          }
        },
        error: function () {
          alert(
            "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
          );
        },
      });
    },
  });

  $("#CAD").click(function () {
    $("#preencherNome").hide();
    $("#nameFuncao").val("");
    $("#cdFuncao").val("");
    $("#CADmodal").modal({ closable: false }).modal("show");
  });

  $("#fechaModalCAD").click(function () {
    $("#preencherNome").hide();
    $("#nameFuncao").val("");
    $("#cdFuncao").val("");
    $("#CADmodal").modal("hide");
  });
});

function editarRegistro(idFuncao) {
  $(".ui.dimmer").dimmer({ closable: false, interactive: false, duration: 5 }).dimmer("show");
  $("#cdFuncao").val("");
  $("#nameTipoExcecao").val("");
  $("#preencherNome").hide();

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Funcoes.php",
    data: {
      cdFuncao: idFuncao,
      funcao: "listJSON",
    },
    success: function (data) {
      var funcao = JSON.parse(data)[0];

      $("#nameFuncao").val(funcao.NM_FUNCAO);
      $("#cdFuncao").val(funcao.CD_FUNCAO);
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

function excluirRegistro(idFuncao) {

  $("#confirmacaoExclusao").modal({
    closable: false,
    onApprove: function() {
      confirmadoExclusao(idFuncao);
      return false;
    },
  }).modal("show");

  function confirmadoExclusao() {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcoes.php",
      data: {
        cdFuncao: idFuncao,
        funcao: "excluir",
      },
      beforeSend: function () {
        // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $("#fechaModalEXC").addClass("disabled");
      },
      success: function (response) {

        response = JSON.parse(response);

        if (response.status === "excluido") {
          $("#myTable").DataTable().clear().draw();
          setTimeout(function () {
            toastSucesso();
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $("#fechaModalEXC").removeClass("disabled");
            $("#confirmacaoExclusao").modal("hide");
            $("#myTable").DataTable().ajax.reload();
          }, 2000);
        } else if (response.status === "erro") {
          response.response.includes("SQLSTATE[23000]") ? toastAtencao('OPERAÇÃO NEGADA! <br> A ação compromete a integridade do banco de dados.') : toastErro(resposta.response);
          $("#botaoconfirmaExclusao").removeClass("loading disabled");
          $("#fechaModalEXC").removeClass("disabled");
          
        } else {
          window.location.href = "generalError.php";
        }

      },
      error: function (xhr, status, error) {
        console.error(error);
        alert("Erro ao Executar operação");
      },
    });
  }
}