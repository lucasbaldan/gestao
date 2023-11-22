$(document).ready(function () {
  var table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/Funcionarios.php",
      data: {
        funcao: "listJSON",
        GridFormat: true,
      },
      dataSrc: "response",
      error: function (xhr) {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_FUNCIONARIO" },
      { data: "NM_FUNCIONARIO" },
      { data: "NOME" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<form action='./formfuncionarios.php' method='POST'><input type='hidden' name='cdFuncionario' value='"+row.CD_FUNCIONARIO+"' readonly required><button class='ui mini icon button blue' type='submit'><i class='pencil alternate icon'></i></button></form>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_FUNCIONARIO +
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
            '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
          );
        } else if (column.index() === 1) {
          input = $(
            '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
          );
        } else if (column.index() === 3) {
          '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
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
});

window.onload = function () {
  $("#dimmerCarregando").removeClass("active");
  
};

function excluirRegistro(idFuncionario) {
  $("#confirmacaoExclusao")
    .modal({
      closable: false,
      onApprove: function () {
        confirmadoExclusao(idFuncionario);
        return false;
      },
    })
    .modal("show");

  function confirmadoExclusao() {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcionarios.php",
      data: {
        cdFuncionario: idFuncionario,
        funcao: "excluir",
      },
      beforeSend: function () {
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $("#fechaModalEXC").addClass("disabled");
      },
      success: function (response) {
        if (response.status === true) {
          $("#myTable").DataTable().clear().draw();
          setTimeout(function () {
            toastSucesso();
            $("#botaoconfirmaExclusao").removeClass("loading disabled");
            $("#fechaModalEXC").removeClass("disabled");
            $("#confirmacaoExclusao").modal("hide");
            $("#myTable").DataTable().ajax.reload();
          }, 500);
        } else {
          window.location.href = "generalError.php";
        }
      },
      error: function (jqXHR) {
        var response = JSON.parse(jqXHR.responseText);
        if (jqXHR.status === 500) {
          response.response.includes("Integrity")
            ? toastAtencao(
                "OPERAÇÃO NEGADA! <br> A ação compromete a integridade do banco de dados."
              )
            :
          toastErro(response.response + " Tente novamente mais tarde!");
        } else if (jqXHR.status === 400) {
           toastAtencao(response.response);
        }

        $("#botaoconfirmaExclusao").removeClass("loading disabled");
        $("#fechaModalEXC").removeClass("disabled");
      },
    });
  }

  function editarRegistro(idFuncionario){

  }
}
