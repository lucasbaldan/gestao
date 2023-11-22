
    var editando = false;

    $(document).ready( function() {

      carregardadosSetores();
      carregardadosFuncoes();

      $('#tabnav .item')
        .tab();

      $('#select-almoco').dropdown();

      var dadosTabelaFuncional = [];

      if (typeof codigoFuncionario !== 'undefined' && codigoFuncionario !== null) {
        carregarDadosGeraisFuncionario(codigoFuncionario);
        dadosTabelaFuncional = carregarDadosFuncionaisFuncionario(codigoFuncionario);
      }

      var table = $('#funcionalTable').DataTable({
        pageLength: 50,
        paging: false,
        processing: true,
        data: dadosTabelaFuncional,
        columns: [{
            data: 'MATRICULA'
          },
          {
            data: 'DATA_INICIAL'
          },
          {
            data: 'DATA_FINAL'
          },
          {
            data: 'ALMOCO'
          },
          {
            data: 'CD_FUNCAO'
          },
          {
            data: 'NM_FUNCAO'
          },
          {
            data: 'DIASSEMANA'
          },
          {
            data: 'DESC_HR_TRABALHO'
          },
          {
            data: 'ACOES'
          },
          {
            data: 'CD_VINCULO_FUNCIONAL'
          },
        ],
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json",
        },
        bFilter: false,
        columnDefs: [{
          targets: [4, 9],
          visible: false
        }],
        createdRow: function(row, data, dataIndex) {
          // Atribua um data-id independente com base em algum contador ou lógica
          $(row).attr('data-id', dataIndex);
        }

      });

      // Evento de clique para os botões de edição
      $('#funcionalTable').on('click', '.small.ui.icon.blue.button', function() {
        if (editando == true) {
          alert("OUTRO REGISTRO JÁ SENDO EDITADO, SALVE PARA EDITAR OUTRO!");
        } else {
          editando = true;
          var rowData = table.row($(this).closest('tr')).data();
          var rowId = $(this).closest('tr').attr('data-id');

          rowData['ACOES'] = "<div class='yellow tiny ui icon message' style='width: 150px;'><i class='tiny loading sync icon'></i>Editando</div>"

          table.row(rowId).data(rowData).draw();

          var dataInicioPura = rowData['DATA_INICIAL'].split('/');
          var dataInicio = dataInicioPura[2] + '-' + dataInicioPura[1] + '-' + dataInicioPura[0];

          if (rowData['DATA_FINAL'] != "-") {
            var dataTerminoPura = rowData['DATA_FINAL'].split('/');
            var dataTermino = dataTerminoPura[2] + '-' + dataTerminoPura[1] + '-' + dataTerminoPura[0];
          }

          // Preencher campos de entrada com os dados da linha selecionada
          $('#cdVinculoFuncional').val(rowData['CD_VINCULO_FUNCIONAL']);
          $('#matricula').val(rowData['MATRICULA']);
          $('#dataInicio').val(dataInicio);
          $('#dataTermino').val(dataTermino);
          $('#select-almoco').val(rowData['ALMOCO']).trigger("change");
          $('#select-funcao').val(rowData['CD_FUNCAO']).trigger("change");
          $('#descricaoHorario').val(rowData['DESC_HR_TRABALHO']);

          var diasTrabalho = rowData['DIASSEMANA'];
          console.log(rowData['DIASSEMANA']);

          // Marcar checkboxes correspondentes
          diasTrabalho.forEach(function(dia) {
            $('#' + dia).prop('checked', true);
          });

          // Armazenar o ID da linha sendo editada
          $('#addfuncional').attr('data-edit-id', rowId);
        }
      });

      $('#addfuncional').on('click', function() {
        var editId = $(this).attr('data-edit-id');

        if (editId) {
          var diasTrabalho = [];
          if ($('#SEG').is(':checked')) diasTrabalho.push('SEG');
          if ($('#TER').is(':checked')) diasTrabalho.push('TER');
          if ($('#QUA').is(':checked')) diasTrabalho.push('QUA');
          if ($('#QUI').is(':checked')) diasTrabalho.push('QUI');
          if ($('#SEX').is(':checked')) diasTrabalho.push('SEX');
          var diasTrabalhoTexto = diasTrabalho.join(', ');

          var dataInicioPura = $('#dataInicio').val().split('-');
          var dataInicio = dataInicioPura[2] + '/' + dataInicioPura[1] + '/' + dataInicioPura[0];

          if ($('#dataTermino').val()) {
            var dataTerminoPura = $('#dataTermino').val().split('-');
            var dataTermino = dataTerminoPura[2] + '/' + dataTerminoPura[1] + '/' + dataTerminoPura[0];
          } else {
            var dataTermino = "-";
          }

          // alert($('#cdVinculoFuncional').val());

          var updatedData = {
            MATRICULA: $('#matricula').val(),
            DATA_INICIAL: dataInicio,
            DATA_FINAL: dataTermino,
            ALMOCO: $('#select-almoco').val(),
            CD_FUNCAO: $('#select-funcao').val(),
            NM_FUNCAO: $('#select-funcao').find(':selected').text(),
            DIASSEMANA: diasTrabalho,
            DESC_HR_TRABALHO: $('#descricaoHorario').val(),
            ACOES: '<button class="small ui icon blue button"><i class="icon pencil alternate"></i></button>        <button class="small ui icon red button"><i class="icon trash alternate outline"></i></button>',
            CD_VINCULO_FUNCIONAL: $('#cdVinculoFuncional').val(),
          };

          table.row(editId).data(updatedData).draw();
          editando = false;

          // Remover o atributo de edição após atualizar os dados
          $(this).removeAttr('data-edit-id');
        } else {
          var diasTrabalho = [];
          if ($('#SEG').is(':checked')) diasTrabalho.push('SEG');
          if ($('#TER').is(':checked')) diasTrabalho.push('TER');
          if ($('#QUA').is(':checked')) diasTrabalho.push('QUA');
          if ($('#QUI').is(':checked')) diasTrabalho.push('QUI');
          if ($('#SEX').is(':checked')) diasTrabalho.push('SEX');
          var diasTrabalhoTexto = diasTrabalho.join(', ');

          if (table.rows().count() === 0) {
            var newId = 0
          } else {
            var newId = table.rows().count();
          }

          var dataInicioPura = $('#dataInicio').val().split('-');
          var dataInicio = dataInicioPura[2] + '/' + dataInicioPura[1] + '/' + dataInicioPura[0];

          if ($('#dataTermino').val()) {
            var dataTerminoPura = $('#dataTermino').val().split('-');
            var dataTermino = dataTerminoPura[2] + '/' + dataTerminoPura[1] + '/' + dataTerminoPura[0];
          } else {
            var dataTermino = "-";
          }

          var newRowData = {
            MATRICULA: $('#matricula').val(),
            DATA_INICIAL: dataInicio,
            DATA_FINAL: dataTermino,
            ALMOCO: $('#select-almoco').val(),
            CD_FUNCAO: $('#select-funcao').val(),
            NM_FUNCAO: $('#select-funcao').find(':selected').text(),
            DIASSEMANA: diasTrabalho,
            DESC_HR_TRABALHO: $('#descricaoHorario').val(),
            ACOES: '<button class="small ui icon blue button"><i class="icon pencil alternate"></i></button>        <button class="small ui icon red button"><i class="icon trash alternate outline"></i></button>',
            CD_VINCULO_FUNCIONAL: ""
          };

          var newRow = table.row.add(newRowData);
          $(newRow.node()).attr('data-id', newId);
          newRow.draw();

        }
        $('#matricula').val('');
        $('#dataInicio').val('');
        $('#dataTermino').val('');
        $('#select-almoco').val('S');
        $('#select-funcao').val('').val(null).trigger('change');
        $('#SEG').prop('checked', false);
        $('#TER').prop('checked', false);
        $('#QUA').prop('checked', false);
        $('#QUI').prop('checked', false);
        $('#SEX').prop('checked', false);
        $('#descricaoHorario').val('');
        $('#cdVinculoFuncional').val('');
      });


      $('#funcionalTable').on('click', '.small.ui.icon.red.button', function() {
        var row = $(this).closest('tr');
        var rowData = table.row(row).data();

        rowData['CD_FUNCAO'] = "EXC";
        rowData['ACOES'] = "<div class'ui red message'><i class='exclamation icon'></i>Exclusão será efetivada quando Salvar</div>";

        // Adiciona a classe 'error' à linha
        row.addClass('error');

        table.row(row).data(rowData).draw();
      });


      $('#salvarFunc').click(function() {
        var cdFuncionario = $('#cdFuncionario').val();
        var nomeFuncionario = $("#nomeFuncionario").val();
        var setor = $("#select-setor").val();
        var dadosTable = [];

        table.rows().every(function() {
          var dadosColuna = this.data();

          dadosTable.push(dadosColuna);
        });

        var dadosAjax = {
          cdFuncionario: cdFuncionario,
          nmFuncionario: nomeFuncionario,
          setorFuncionario: setor,
          vinculosFuncionais: dadosTable
        }
        //console.log(dadosAjax);

        $.ajax({
          url: "./../../App/Controllers/Funcionarios.php",
          type: "POST",
          data: {
            dados: JSON.stringify(dadosAjax),
            funcao: "controlar",
          },
          beforeSend: function(){
            $('#dimmerCarregando').addClass('active');
          },
          success: function(response) {
            console.log(response);

            if (response === 'inserido' || response === 'alterado') {
              setTimeout(function() {
                $('#dimmerCarregando').removeClass('active');
              $("#operacaoSucesso").addClass("active");
              }, 1000);
              setTimeout(function() {
                window.location.href = 'listfuncionarios.php';
              }, 3000);
            }

            else {
              $('#dimmerCarregando').removeClass('active');
              $(".ui.negative.message").transition("fade in");

              setTimeout(function() {
                $(".ui.negative.message").transition("fade out");
              }, 2000);
            }
          },
          error: function(xhr, status, error) {
            console.error("Erro na requisição AJAX:", error);
          }
        });
      });

    });

    window.onload = function() {
      $('#dimmerCarregando').removeClass('active');
    }

    async function carregardadosFuncoes(FuncaoSalvoNoBanco = null) {
      let options = [];

      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Funcoes.php",
        data: {
          funcao: "listJSON",
        },
        success: function(data) {
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
            allowClear: true
          });
        },
        error: function() {
          alert("Erro ao Carregar os funcionários. Tente novamente mais Tarde!");
        },
      });
    }

     function carregardadosSetores() {
      let options = [];

      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Setores.php",
        data: {
          funcao: "listSetoresJSON",
        },
        success: function(data) {
          const dadosSetores = JSON.parse(data);
          options = dadosSetores.map((item) => ({
            id: item.CD_SETOR.toString(),
            text: item.NOME,
          }));

          options.unshift({
            id: "",
            text: "",
          });

          $("#select-setor").select2({
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

    function carregarDadosGeraisFuncionario(idFuncionario) {

      $.ajax({
        url: "./../../App/Controllers/Funcionarios.php",
        method: 'POST',
        data: {
          cdFuncionario: idFuncionario,
          funcao: "listJSON"
        },
        dataType: 'json',
        success: function(data) {
            setTimeout(function() {
          $('#cdFuncionario').val(data[0].CD_FUNCIONARIO);
          $('#nomeFuncionario').val(data[0].NM_FUNCIONARIO);
            $('#select-setor').val(data[0].CD_SETOR).trigger("change.select2");
          }, 150);

        },
        error: function(xhr, status, error) {
          // Lide com erros, se necessário
          alert("Erro ao carregar dados do funcionário: " + error);
        }
      });
    }

    async function carregarDadosFuncionaisFuncionario(idFuncionario) {
      return new Promise(function(resolve, reject) {
        $.ajax({
          url: "./../../App/Controllers/Funcionarios.php",
          method: 'POST',
          data: {
            cdFuncionario: idFuncionario,
            funcao: "listFuncionalJSON"
          },
          dataType: 'json',
          success: function(data) {
            resolve(data); // Resolva a Promise com os dados
          },
          error: function(xhr, status, error) {
            reject(error); // Rejeite a Promise em caso de erro
          }
        });
      });
    }