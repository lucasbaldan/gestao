////// INICIA O JAVASRIPT DA PÁGINA
$(document).ready(function () {
  var table = $("#myTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/Excecoes.php",
      data: {
        funcao: "listJSON",
        GridFormat: true,
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
          $("#cadSubmit").addClass("loading disabled");
          $("#fechaModalCAD").addClass("disabled");
        },
        success: function (response) {
          if (
            response.status === "inserido" ||
            response.status === "alterado"
          ) {
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
            response.response.includes("Cadastrado")
              ? toastAtencao(response.response)
              : toastErro(response.response);
            $("#cadSubmit").removeClass("loading disabled");
            $("#fechaModalCAD").removeClass("disabled");
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
    $("#cdExcecao").val("");
    uiCalendar("dataExcecaoDiv");
    uiCalendar("dataFinalDiv");
    $("#search_to").empty();
    carregarDadosFuncionario();
    carregardadosTiposExcecoes();
    $("#select-tipoExcecao").val("").trigger("change");
    $("#CADmodal").modal({ closable: false }).modal("show");
  });

  $("#fechaModalCAD").click(function () {
    $("#CADmodal").modal("hide");
  });
});

function editarRegistro(idExcecao) {
  $("#dimmerCarregando").dimmer({ closable: false }).addClass("active");
  $("#search_to").empty();
  var inputDataExcecao = uiCalendar("dataExcecaoDiv");
  var inputDataFinal = uiCalendar("dataFinalDiv");

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Excecoes.php",
    data: {
      cdExcecao: idExcecao,
      funcao: "listJSON",
    },
    success: function (data) {
      console.log(data);
      var Excecao = JSON.parse(data)[0];

      $("#cdExcecao").val(Excecao.CD_EXCECAO);
      inputDataExcecao.calendar("set date", Excecao.DATA_INICIAL);
      inputDataFinal.calendar("set date", Excecao.DATA_FINAL);
      carregardadosTiposExcecoes(Excecao.CD_TIPO_EXCECAO);
      carregarDadosFuncionario(Excecao.CD_FUNCIONARIO);
      $("#dimmerCarregando").dimmer({ closable: false }).removeClass("active");
      $("#CADmodal").modal({ closable: false }).modal("show");
    },
    error: function (xhr, status, error) {
      console.error(error); // Mostra o erro no console do navegador
      alert("Erro ao carregar os dados da Funcao.");
    },
  });
}

function excluirRegistro(idExcecao) {
  $("#confirmacaoExclusao")
    .modal({
      closable: false,
      onApprove: function () {
        confirmadoExclusao(idExcecao);
        return false;
      },
    })
    .modal("show");

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
        $("#botaoconfirmaExclusao").addClass("loading disabled");
        $("#fechaModalEXC").addClass("disabled");
      },
      success: function (response) {
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
          response.response.includes("SQLSTATE[23000]")
            ? toastAtencao(
                "OPERAÇÃO NEGADA! <br> A ação compromete a integridade do banco de dados."
              )
            : toastErro(resposta.response);
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

// FUNÇÃO QUE PEGA OS DADOS DOS TIPOS DE EXCEÇÕES
function carregardadosTiposExcecoes(tipoExcecaoSalvoNoBanco = null) {

  if (tipoExcecaoSalvoNoBanco) {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/TiposExcecoes.php",
      data: {
        cdTipoExcecao: tipoExcecaoSalvoNoBanco,
        funcao: "listJSON",
      },
      success: function (data) {
        var tipoExcecao = JSON.parse(data)[0];
        var novaOpcao = document.createElement('option');
        novaOpcao.value = tipoExcecao.CD_TIPO_EXCECAO;
        novaOpcao.text = tipoExcecao.NM_TIPO_EXCECAO;
        var selectElement = document.getElementById('select-tipoExcecao');
        selectElement.appendChild(novaOpcao);
      },
      error: function (xhr, status, error) {
        console.error(error);
        alert("Erro ao Buscar opção Tipo de Exceção Salva. Tente novamente mais tarde!");
      },
    });
  }
 
  $("#select-tipoExcecao").select2({
    ajax: {
      url: "./../../App/Controllers/TiposExcecoes.php",
      type: "POST",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          funcao: "listJSON",
          stringPesquisa: params.term,
        };
      },
      processResults: function (data) {
        console.log(data);
        // Mapear os campos do JSON para os campos específicos do Select2
        var mappedData = data.map(function (item) {
          return {
            id: item.CD_TIPO_EXCECAO,
            text: item.NM_TIPO_EXCECAO,
          };
        });

        return {
          results: mappedData,
        };
      },
      cache: true,
    },
    //minimumInputLength: 1, // Pesquisa automática a partir do primeiro caractere
    tags: false,
    placeholder: "Selecione tipo Exceção",
    allowClear: true,
  });
}

function carregarDadosFuncionario(id = null) {
  $("#search").empty();
  $("#search_to").empty();
  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Funcionarios.php",
    data: {
      funcao: "listJSON",
      cdFuncionario: id,
    },
    beforeSend: function () {
      $("#dimmerCarregando").dimmer({ closable: false }).addClass("active");
    },
    success: function (data) {
      const dadosFuncionarios = JSON.parse(data);
      const selectFuncionarios = id == null ? $("#search") : $("#search_to");

      dadosFuncionarios.forEach((funcionario) => {
        const option = $("<option>")
          .val(funcionario.CD_FUNCIONARIO)
          .text(funcionario.NM_FUNCIONARIO);
        selectFuncionarios.append(option);
      });

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
      $("#dimmerCarregando").removeClass("active");
    },
    error: function () {
      alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
    },
  });
}
