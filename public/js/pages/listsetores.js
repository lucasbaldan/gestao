$(document).ready(function () {
  var table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/Setores.php",
      data: {
        funcao: "listSetoresJSON",
      },
      dataSrc: "",
      error: function () {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_SETOR" },
      { data: "NOME" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<button class='ui mini icon button blue' onclick='editarRegistro(" +
            row.CD_SETOR +
            ")'><i class='pencil alternate icon'></i></button>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_SETOR +
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
          response = JSON.parse(response);

          if (
            response.status === "inserido" ||
            response.status === "alterado"
          ) {
            $("#myTable").DataTable().clear().draw();
            // Agendar a remoção da mensagem após 4 segundos
            setTimeout(function () {
              $("#CADmodal").modal("hide");
              $("#cadSubmit").removeClass("loading disabled");
              $("#fechaModalCAD").removeClass("disabled");
              toastSucesso();
              $("#myTable").DataTable().ajax.reload();
            }, 1000);
          }
          if (response.status === "erro") {
            response.response.includes("Cadastrado") ? toastAtencao(response.response) : toastErro(response.response);
            $("#cadSubmit").removeClass(
              "loading disabled"
            );
            $("#fechaModalCAD").removeClass("disabled");
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
    $(".ui.dimmer").dimmer({ closable: false, interactive: false, duration: 5 }).dimmer("show");
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
      var setor = JSON.parse(data)[0];

      $("#nameSetor").val(setor.NOME);
      $("#cdSetor").val(setor.CD_SETOR);

      $(".ui.dimmer")
        .dimmer({ closable: false, interactive: false, duration: 5 })
        .dimmer("hide");
      setTimeout(function () {
        $("#CADmodal").modal({ closable: false }).modal("show");
      }, 60);
    },
    error: function (xhr, status, error) {
      console.error(error); // Mostra o erro no console do navegador
      alert("Erro ao carregar os dados do setor.");
    },
  });
}

function excluirRegistro(idSetor) {
    $("#confirmacaoExclusao").modal({
        closable: false,
        onApprove: function() {
          confirmadoExclusao(idSetor);
          return false;
        },
      }).modal("show");

  // Função de callback para executar o Ajax após a confirmação
  function confirmadoExclusao() {
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
