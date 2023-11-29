var selectsetor; 
$(document).ready(function () {

  carregardadosFuncoes();
  carregardadosSetores();

  $("#tabnav .item").tab();

  $("#select-almoco").dropdown();

  if (typeof codigoFuncionario !== "undefined" && codigoFuncionario !== null) {
    carregarDadosGeraisFuncionario(codigoFuncionario);
  }


  $('#salvarFunc').click(function () {
    $('#form-CAD-funcionario').submit();
  });

  $("#form-CAD-funcionario").form({
    onSuccess: function (event, fields) {
      event.preventDefault();

      if (
        $("#nomeFuncionario").val().trim() === "" ||
        $("#nomeFuncionario").val().trim().length < 3
      ) {
        $("#preencherNome").show();
        return false;
      }

      var formData = $("#form-CAD-funcionario").serialize();

      // Envia a requisição AJAX
      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Funcionarios.php",
        data: formData,
        beforeSend: function () {
        },
        success: function (response) {
          console.log(response);
        },
        error: function () {
          alert(
            "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
          );
        },
      });
    },
  });


  var table = $("#funcionalTable").DataTable({
    processing: true,
    ajax: {
      type: "POST",
      url: "./../../App/Controllers/VinculosFuncionais.php",
      data: {
        funcao: "listJSON",
        cdFuncionario: codigoFuncionario,
      },
      dataSrc: "response",
      // success: function(data){
      //   console.log(data);
      // },
      error: function (xhr) {
        window.location.href = "generalError.php";
      },
    },
    columns: [
      { data: "CD_VINCULO_FUNCIONAL" },
      { data: "MATRICULA" },
      { data: "DATA_INICIAL" },
      { data: "DATA_FINAL" },
      { data: "ALMOCO" },
      { data: "NM_FUNCAO" },
      { data: "DIASSEMANA" },
      { data: "DESC_HR_TRABALHO" },
      {
        render: function (data, type, row) {
          var editarBtn =
            "<button class='ui mini icon button blue' onclick='editarRegistro(" +
            row.CD_VINCULO_FUNCIONAL +
            ")'><i class='pencil alternate icon'></i></button>";
          var excluirBtn =
            "<button class='ui mini icon button red' onclick='excluirRegistro(" +
            row.CD_VINCULO_FUNCIONAL +
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
  });

});

$("#addfuncional").click(function(){
  
});


async function carregardadosFuncoes(FuncaoSalvoNoBanco = null) {
  let options = [];

  $.ajax({
    type: "POST",
    url: "./../../App/Controllers/Funcoes.php",
    data: {
      funcao: "listJSON",
    },
    success: function (data) {
      const dadosFuncoes = JSON.parse(data);
      options = dadosFuncoes.map((item) => ({
        id: item.CD_FUNCAO.toString(),
        text: item.NM_FUNCAO,
      }));

      options.unshift({
        id: "",
        text: "",
      });

      $("#select-funcao").select2({
        data: options,
        placeholder: "Selecione uma função",
        allowClear: true,
      });
    },
    error: function () {
      alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
    },
  });
}

function carregardadosSetores() {
  selectsetor = $("#select-setor").select2({
    ajax: {
      url: "./../../App/Controllers/Setores.php",
      type: "POST",
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          funcao: "listSetoresJSON",
          stringPesquisa: params.term,
        };
      },
      processResults: function (data) {
        // Mapear os campos do JSON para os campos específicos do Select2
        var mappedData = (data.response).map(function (item) {
          return {
            id: item.CD_SETOR,
            text: item.NOME,
          };
        });

        return {
          results: mappedData,
        };
      },
      cache: true,
      error: function (jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        var response = JSON.parse(jqXHR.responseText);
        if (jqXHR.status === 400) {
          toastAtencao(response.response + " Tente novamente mais tarde!");
        } else {
          // window.location.href = "generalError.php";
        }
      }
    },
    //minimumInputLength: 1, // Pesquisa automática a partir do primeiro caractere
    tags: false,
    placeholder: "Selecione tipo Exceção",
    allowClear: true,
  });
}

function carregarDadosGeraisFuncionario(idFuncionario) {
  $.ajax({
    url: "./../../App/Controllers/Funcionarios.php",
    method: "POST",
    data: {
      cdFuncionario: idFuncionario,
      funcao: "listJSON",
    },
    dataType: "json",
    success: function (data) {
      data = data.response;
      setTimeout(function () {
        $("#cdFuncionario").val(data[0].CD_FUNCIONARIO);
        $("#nomeFuncionario").val(data[0].NM_FUNCIONARIO);
        selectsetor.val(data[0].CD_SETOR).trigger("change.select2");
      }, 300);
    },
    error: function (xhr, status, error) {
      // Lide com erros, se necessário
      alert("Erro ao carregar dados do funcionário: " + error);
    },
  });
}