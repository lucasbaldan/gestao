$(document).ready(function () {
  $(".ui.negative.message").hide();
  $(".ui.positive.message").hide();
  var table = $("#myTable").DataTable({
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
    },
    processing: true,
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
              '</h4><input class="ui input responsive-input" type="text" placeholder="' +
              title +
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
});

window.onload = function () {
  $("#dimmerCarregando").removeClass("active");
  
};

function excluirRegistro(idFuncionario) {
  $("#confirmacaoExclusao").modal("show");

  // Função de callback para executar o Ajax após a confirmação
  function confirmadoExclusao() {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcionarios.php",
      data: {
        cdFuncionario: idFuncionario,
        funcao: "excluir",
      },
      beforeSend: function () {
        // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
        $("#botaoconfirmaExclusao").addClass("loading");
      },
      success: function (response) {
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
