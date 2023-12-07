var table;
$(document).ready(function () {
   table = new DataTable("#myTable", {
    ajax: {
      url: "./../../App/Controllers/Setores.php",
      type: "POST",
      data: {
        funcao: "dataTable",
      },
      error: function () {
        window.location.href = "generalError.php";
      },
    },
    processing: true,
    serverSide: true,
  });
  // var table = $("#myTable").DataTable({
  //   processing: true,
  //   ajax: {
  //     type: "POST",
  //     url: "./../../App/Controllers/Setores.php",
  //     data: {
  //       funcao: "listSetoresJSON",
  //     },
  //     dataSrc: "response",
  //     error: function () {
  //       window.location.href = "generalError.php";
  //     },
  //   },
  //   columns: [
  //     { data: "CD_SETOR" },
  //     { data: "NOME" },
  //     {
  //       render: function (data, type, row) {
  //         var editarBtn =
  //           "<button class='ui mini icon button blue' onclick='editarRegistro(" +
  //           row.CD_SETOR +
  //           ")'><i class='pencil alternate icon'></i></button>";
  //         var excluirBtn =
  //           "<button class='ui mini icon button red' onclick='excluirRegistro(" +
  //           row.CD_SETOR +
  //           ")'><i class='trash alternate icon'></i></button>";
  //         return editarBtn + excluirBtn;
  //       },
  //     },
  //   ],

  //   language: {
  //     url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
  //   },
  //   order: [[0, "desc"]],
  //   columnDefs: [
  //     {
  //       targets: "_all",
  //       className: "dt-center",
  //     },
  //   ],
  //   initComplete: function () {
  //     var api = this.api();

  //     api.columns().every(function () {
  //       var column = this;
  //       var title = $(column.header()).text();

  //       var input;

  //       if (column.index() === 0) {
  //         input = $(
  //           '<div class="ui fluid input focus"><input type="number" placeholder="Procurar..."></div>'
  //         );
  //       } else if (column.index() === 1) {
  //         input = $(
  //           '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
  //         );
  //       } else if (column.index() === 2) {
  //       } else {
  //         input = $(
  //           '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
  //         );
  //       }

  //       $(column.header())
  //         .empty()
  //         .append($("<h4>" + title + "</h4>"))
  //         .append(input);

  //       // Adicione um ouvinte de eventos para atualizar a pesquisa ao digitar ou alterar
  //       input.find("input").on("keyup change", function () {
  //         if (column.search() !== this.value) {
  //           column.search(this.value).draw();
  //         }
  //       });

  //       input.on("click", function (e) {
  //         e.stopPropagation();
  //       });
  //     });
  //   },
  // });

  $("#form-CAD-setor").form({
    onSuccess: function (event, fields) {
      event.preventDefault(); // Impede o envio padrão do formulário

      if (
        $("#nameSetor").val().trim() === "" ||
        $("#nameSetor").val().trim().length < 3
      ) {
        $("#preencherNome").show();
        return false;
      }

      var formData = $("#form-CAD-setor").serialize();

      // Envia a requisição AJAX
      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Setores.php",
        data: formData,
        beforeSend: function () {
          $("#cadSubmit").addClass("loading disabled");
          $("#fechaModalCAD").addClass("disabled");
        },
        success: function (response) {
          // Manipula a resposta recebida
          // Exemplo: exibe a resposta em um alerta
          //response = JSON.parse(response);

          if (response.status === true) {
            $("#myTable").DataTable().clear().draw();

            setTimeout(function () {
              $("#CADmodal").modal("hide");
              $("#cadSubmit").removeClass("loading disabled");
              $("#fechaModalCAD").removeClass("disabled");
              toastSucesso();
              $("#myTable").DataTable().ajax.reload();
            }, 1000);
          }
        },
        error: function (jqXHR) {
          var response = JSON.parse(jqXHR.responseText);
          if (jqXHR.status === 500) {
            toastErro(
              response.response + "</br><b>Tente novamente mais tarde!<b>"
            );
          } else if (jqXHR.status === 400) {
            toastAtencao(response.response);
          } else {
            window.location.href = "generalError.php";
          }
          $("#cadSubmit").removeClass("loading disabled");
          $("#fechaModalCAD").removeClass("disabled");
        },
      });
    },
  });

  $("#CAD").click(function () {
    $("#preencherNome").hide();
    $("#nameSetor").val("");
    $("#cdSetor").val("");
    $("#CADmodal").modal({ closable: false }).modal("show");
  });

  $("#fechaModalCAD").click(function () {
    $("#preencherNome").hide();
    $("#nameSetor").val("");
    $("#cdSetor").val("");
    $("#CADmodal").modal("hide");
  });
});

function editarRegistro(idSetor) {
  $("#dimmerCarregando").dimmer({ closable: false }).addClass("active");
  $("#cdSetor").val("");
  $("#nameSetor").val("");
  $("#preencherNome").hide();

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Setores.php",
    data: {
      cdSetor: idSetor,
      funcao: "listSetoresJSON",
    },
    success: function (data) {
      //console.log(data);
      if (data.status == true) {
        var setor = data.response[0];

        $("#nameSetor").val(setor.NOME);
        $("#cdSetor").val(setor.CD_SETOR);

        $("#dimmerCarregando")
          .dimmer({ closable: false })
          .removeClass("active");
        $("#CADmodal").modal({ closable: false }).modal("show");
      } else {
        window.location.href = "generalError.php";
      }
    },
    error: function (xhr, status, error) {
      window.location.href = "generalError.php";
    },
  });
}

function excluirRegistro(idSetor) {
  $("#confirmacaoExclusao")
    .modal({
      closable: false,
      onApprove: function () {
        confirmadoExclusao(idSetor);
        return false;
      },
    })
    .modal("show");

  // Função de callback para executar o Ajax após a confirmação
  function confirmadoExclusao(idSetor) {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Setores.php",
      data: {
        cdSetor: idSetor,
        funcao: "excluirSetores",
      },
      beforeSend: function () {
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $("#fechaModalEXC").addClass("disabled");
      },
      success: function (response) {
        if (response.status === true) {
          setTimeout(function () {
            toastSucesso();
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $("#fechaModalEXC").removeClass("disabled");
            $("#confirmacaoExclusao").modal("hide");
            table.draw();
          }, 1000);
        }
      },
      error: function (jqXHR) {
        var response = JSON.parse(jqXHR.responseText);
        if (jqXHR.status === 500) {
          toastErro(
            response.response + "</br><b>Tente novamente mais tarde!<b>"
          );
        } else if (jqXHR.status === 400) {
          toastAtencao(response.response);
        } else {
          window.location.href = "generalError.php";
        }
      },
    });
  }
}
