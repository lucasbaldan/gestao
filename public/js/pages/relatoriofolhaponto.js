$(document).ready(function() {
    $('#opcoesRelatorio').dropdown();
    //$('#setor').dropdown();
    var dataInput = document.getElementById("mesRelatorio");
    var opcaoList = document.getElementById("opcoesRelatorio");
    var opcaoSetor = document.getElementById("select-Setor");

    $('#mesRelatorioDIV').addClass('disabled');
    opcaoSetor.style.display = 'none';

    opcaoList.addEventListener("change", function() {
      if (opcaoList.value == 'SETOR') {
        $('#mesRelatorioDIV').addClass('disabled');
        $('#mesRelatorio').val('');
        $('#search_to').empty();
        $('#search').empty();
        carregarDadosSetores();
        opcaoSetor.style.display = 'block';
        opcaoSetor.style.width = 'auto';
      } else if (opcaoList.value == 'FUNCIONARIO') {
        $('#mesRelatorioDIV').removeClass('disabled');
        $('#mesRelatorio').val('');
        opcaoSetor.style.display = 'none';
        $("#setor").select2('destroy');
        $('#setor').val(null).trigger('change');
      }
    });

    $('#setor').on("change", function() {
      $('#mesRelatorio').val('');
      $('#mesRelatorioDIV').removeClass('disabled');
      $('#search_to').empty();
        $('#search').empty();
    });



    dataInput.addEventListener("change", function() {
      if (opcaoList.value == 'FUNCIONARIO') {
        carregarDadosFuncionarios($('#mesRelatorio').val());
      } else if (opcaoList.value == 'SETOR' && ($('#setor').val() !== '' || $('#setor').val() !== null)) {
        carregarDadosFuncionarios($('#mesRelatorio').val(), $('#setor').val());
      }
    });
  });

  async function carregarDadosFuncionarios(mesRelatorio, cdSetor = null) {
    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Funcionarios.php",
      data: {
        funcao: "listRelFuncionario",
        mesRelatorio: mesRelatorio,
        cdSetor: cdSetor
      },
      success: function(data) {
        const dadosFuncionarios = data.response;
        const selectFuncionarios = $("#search");
        selectFuncionarios.empty();
        $('#search_to').empty();

        dadosFuncionarios.forEach((funcionario) => {
          const option = $("<option>")
            .val(funcionario.MATRICULA)
            .text(funcionario.OPCAO_FUNCIONARIO);
          selectFuncionarios.append(option);
        });
      },
      error: function() {
        alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        location.reload();
      },
    });
  }


  jQuery(document).ready(function($) {
    $("#search").multiselect({
      submitAllLeft: false,
      submitAllRigh: true,
      search: {
        left: '<input type="text" placeholder="Procurar Funcionário-Matrícula..." style="width: 100%; border-radius: 3px;" />',
        right: '<input type="text" class="" placeholder="Procurar Funcionário-Matrícula selecionado..." style="width: 100%; border-radius: 3px;"/>',
      },
      fireSearch: function(value) {
        return value.length > 0;
      },
    });
  });

  async function carregarDadosSetores() {
    let options = [];

    $.ajax({
      type: "POST",
      url: "./../../App/Controllers/Setores.php",
      data: {
        funcao: "listSetoresJSON",
      },
      success: function(data) {
        const dadosSetores = data.response;
        options = dadosSetores.map((item) => ({
          id: item.CD_SETOR.toString(),
          text: item.NOME,
        }));

        options.unshift({
          id: "",
          text: "",
        });

        $("#setor").select2({
          data: options,
          placeholder: "Selecione um Setor",
          allowClear: true
        });
      },
      error: function() {
        alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
      },
    });
  }