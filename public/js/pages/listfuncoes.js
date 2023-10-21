$(document).ready(function() {

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

  $("#form-CAD-funcao").form({
      onSuccess: function(event, fields) {
          event.preventDefault();

          if (
            $("#nameTipoExcecao").val().trim() === "" ||
            $("#nameTipoExcecao").val().trim().length < 3
          ) {
            $("#preencherFuncao").show();
            return false;
          }

          var formData = $("#form-CAD-funcao").serialize();

          // Envia a requisição AJAX
          $.ajax({
              type: "POST",
              url: "./../../App/Controllers/Funcoes.php",
              data: formData,
              beforeSend: function() {
                  // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
                  $(".ui.positive.right.labeled.icon.button").addClass("loading");
              },
              success: function(response) {
                  // Manipula a resposta recebida
                  //alert(response); // Exemplo: exibe a resposta em um alerta

                  // Se a validação for bem-sucedida, redirecione para outra página
                  if (response === "inserido" || response === "alterado") {
                      $(".ui.positive.message").transition("fade in");

                      $(".ui.positive.right.labeled.icon.button").removeClass("loading");

                      // Agendar a remoção da mensagem após 4 segundos
                      setTimeout(function() {
                          $(".ui.positive.message").transition("fade out");
                          $("#CADmodal").modal("hide");
                          location.reload();
                      }, 1500);
                  }
                  if (response === "erro") {
                      $("#CADmodal").modal("hide");
                      $(".ui.negative.message").transition("fade in");

                      setTimeout(function() {
                          location.reload();
                          $(".ui.negative.message").transition("fade out");
                      }, 1500);
                  }
              },
              error: function() {
                  alert(
                      "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
                  );
              },
              complete: function() {
                  // Remova a animação de "carregando" aqui, se necessário
              },
          });
      },
  });

  $("#CAD").click(function() {
      $("#CADmodal").modal("show");
      $("#nameFuncao").val("");
  });

  $(".ui.orange.basic.button").click(function() {
      $("#CADmodal").modal("hide");
  });
});

function editarRegistro(idFuncao) {
  $("#CADmodal").modal("show");
  $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcoes.php",
      data: {
          cdFuncao: idFuncao,
          funcao: "listJSON",
      },
      success: function(data) {
          var funcao = JSON.parse(data)[0];
        
          $("#nameFuncao").val(funcao.NM_FUNCAO);
          $("#cdFuncao").val(funcao.CD_FUNCAO);
      },
      error: function(xhr, status, error) {
          console.error(error); // Mostra o erro no console do navegador
          alert("Erro ao carregar os dados da Funcao.");
      },
  });
}

function excluirRegistro(idFuncao) {
  $("#confirmacaoExclusao").modal("show");

  // Função de callback para executar o Ajax após a confirmação
  function confirmadoExclusao() {
      $.ajax({
          type: "POST",
          url: "./../../App/Controllers/Funcoes.php",
          data: {
              cdFuncao: idFuncao,
              funcao: "excluir",
          },
          beforeSend: function() {
              // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
              $("#botaoconfirmaExclusao").addClass("loading");
          },
          success: function(response) {
              if (response === "excluido") {
                  $(".ui.positive.message").transition("fade in");

                  $(".ui.positive.right.labeled.icon.button").removeClass("loading");

                  // Agendar a remoção da mensagem após 4 segundos
                  setTimeout(function() {
                      $(".ui.positive.message").transition("fade out");
                      $("#CADmodal").modal("hide");
                      location.reload();
                  }, 2000);

              } else if (response === "erro") {
                  $("#confirmacaoExclusao").modal("hide");
                  $(".ui.negative.message").transition("fade in");

                  setTimeout(function() {
                      
                      $(".ui.negative.message").transition("fade out");
                      location.reload();
                    }, 1500);
              } else {
                  $("#confirmacaoExclusao").modal("hide");
                  $(".ui.negative.message").transition("fade in");

                  setTimeout(function() {
                      //location.reload();
                      $(".ui.negative.message").transition("fade out");
                      location.reload();
                  }, 1500);
              }
          },
          error: function(xhr, status, error) {
              console.error(error);
              alert("Erro ao Executar operação");
          },
      });
  }

  // Vincula a função de callback ao evento de clique do botão de confirmação
  $("#botaoconfirmaExclusao").on("click", confirmadoExclusao);
}