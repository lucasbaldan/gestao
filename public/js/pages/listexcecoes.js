////// INICIA O JAVASRIPT DA PÁGINA
$(document).ready(function () {
  acionarCalendario();
  var table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/Excecoes.php",
      data: {
        funcao: "listJSON",
      },
      dataSrc: "",
      error: function () {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_EXCECAO" },
      { data: "NM_TIPO_EXCECAO" },
      { data: "DATA_INICIAL" },
      { data: "DATA_FINAL" },
      { data: "NM_FUNCIONARIO" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<button class='ui mini icon button blue' onclick='editarRegistro(" +
            row.CD_EXCECAO +
            ")'><i class='pencil alternate icon'></i></button>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_EXCECAO +
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
          input = $(
            '<div class="ui fluid input focus"><input type="date" placeholder=" "></div>'
          );
        } else if (column.index() === 3) {
          input = $(
            '<div class="ui fluid input focus"><input type="date" placeholder="Procurar..."></div>'
          );
        } else if (column.index() === 4) {
          input = $(
            '<div class="ui fluid input focus"><input type="text" placeholder="Procurar..."></div>'
          );
        } else {
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

  // CONTROLA O FORMULÁRIO DO CADASTRO

  $("#form-CAD-excecao").form({
    onSuccess: function (event, fields) {
      $("#search_to option").prop("selected", true); // que merda é essa?

      event.preventDefault(); // Impede o envio padrão do formulário

      // Obtém os dados do formulário
      var formData = $("#form-CAD-excecao").serialize();

      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Excecoes.php",
        data: formData,
        beforeSend: function () {
          $("#cadSumbit").addClass("loading disabled");
          $("#fechaModalCAD").addClass("loading disabled");
        },
        success: function (response) {
          response = JSON.parse(response);

          if (response === "inserido" || response === "alterado") {

            $("#myTable").DataTable().clear().draw();

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
    $("#select-tipoExcecao").val("").trigger("change");
    $("#dataExcecao").val("");
    $("#dataFinal").val("");
    $("#search_to").empty();
    carregarDadosFuncionario();
    $("#CADmodal").modal("show");
  });
});

function editarRegistro(idExcecao, idFuncionario, idTipoExcecao) {
  $("#search_to").empty();
  $("#dataExcecao").val("");
  $("#dataFinal").val("");
  carregardadosTiposExcecoes(idTipoExcecao);
  carregarDadosFuncionario(idFuncionario);
  $("#CADmodal").modal("show");

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Excecoes.php",
    data: {
      cdExcecao: idExcecao,
      funcao: "listJSON",
    },
    success: function (data) {
      var Excecao = JSON.parse(data)[0];

      $("#cdExcecao").val(Excecao.CD_EXCECAO);
      $("#dataExcecao").val(Excecao.DATA_INICIAL);
      $("#dataFinal").val(Excecao.DATA_FINAL);
    },
    error: function (xhr, status, error) {
      console.error(error); // Mostra o erro no console do navegador
      alert("Erro ao carregar os dados da Funcao.");
    },
  });
}

//EXCLUSÃO DE REGISTRO VIA GRID
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

// FUNÇÃO QUE PEGA OS DADOS DOS TIPOS DE EXCEÇÕES
async function carregardadosTiposExcecoes(tipoExcecaoSalvoNoBanco = null) {
  let options = [];

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/TiposExcecoes.php",
    data: {
      funcao: "listJSON",
    },
    success: function (data) {
      const dadosTipoExcecoes = JSON.parse(data);
      options = dadosTipoExcecoes.map((item) => ({
        id: item.CD_TIPO_EXCECAO.toString(),
        text: item.NM_TIPO_EXCECAO,
      }));

      options.unshift({
        id: "",
        text: "",
      });

      $("#select-tipoExcecao").select2({
        data: options,
        placeholder: "Selecione tipo Exceção",
        allowClear: true,
      });

      if (tipoExcecaoSalvoNoBanco) {
        $("#select-tipoExcecao").val(tipoExcecaoSalvoNoBanco).trigger("change");
      }
    },
    error: function () {
      alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
    },
  });
}

//PEGA OS FUNCIONÁRIOS PARA SEREM CARREGADOS NO MULTISELECT DO JQUERY DO MODAL DE CADASTRO DE EXCECÃO.
async function carregarDadosFuncionario(id = null) {
  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Funcionarios.php",
    data: {
      funcao: "listJSON",
      cdFuncionario: id,
    },
    success: function (data) {
      const dadosFuncionarios = JSON.parse(data);
      const selectFuncionarios = $("#search");
      selectFuncionarios.empty();

      dadosFuncionarios.forEach((funcionario) => {
        const option = $("<option>")
          .val(funcionario.CD_FUNCIONARIO)
          .text(funcionario.NM_FUNCIONARIO);
        selectFuncionarios.append(option);
      });
    },
    error: function () {
      alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
      location.reload();
    },
  });
}

// INICIALIZA O JQUERY CONTENDO AS FUNCIONALIDADES DO MULTISELECT
jQuery(document).ready(function ($) {
  $("#search").multiselect({
    submitAllLeft: false,
    submitAllRigh: true,
    search: {
      left: '<input type="text" class="form-control" placeholder="Procurar Funcionário..." />',
      right:
        '<input type="text" class="form-control" placeholder="Procurar Funcionário selecionado..." />',
    },
    fireSearch: function (value) {
      return value.length > 0;
    },
  });
});