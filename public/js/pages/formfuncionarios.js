var editando = false;
$(document).ready(function () {

  carregardadosSetores();
  //carregardadosFuncoes();

  $("#tabnav .item").tab();

  $("#select-almoco").dropdown();

  var dadosTabelaFuncional = [];

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
  $("#select-setor").select2({
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
        $("#select-setor").val(data[0].CD_SETOR).trigger("change.select2");
      }, 150);
    },
    error: function (xhr, status, error) {
      // Lide com erros, se necessário
      alert("Erro ao carregar dados do funcionário: " + error);
    },
  });
}

function carregarDadosFuncionaisFuncionario(idFuncionario) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: "./../../App/Controllers/Funcionarios.php",
      method: "POST",
      data: {
        cdFuncionario: idFuncionario,
        funcao: "listFuncionalJSON",
      },
      dataType: "json",
      success: function (data) {
        resolve(data.response); // Resolvendo a Promise com os dados recebidos
      },
      error: function (xhr, status, error) {
        reject(
          "Erro ao carregar Vínculos funcionais! Tente novamente mais tarde"
        ); // Rejeitando a Promise em caso de erro
      },
    });
  });
}