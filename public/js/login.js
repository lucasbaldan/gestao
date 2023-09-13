$(document).ready(function () {
  document.querySelector('[name="user"]').focus();

  $(".ui.form").form({
    fields: {
      user: {
        identifier: "user",
        rules: [
          {
            type: "empty",
            prompt: "Insira seu usuário",
          },
          {
            type: "minLength[4]",
            prompt: "O usuário deve ter no mínimo 4 caracteres",
          },
        ],
      },
      password: {
        identifier: "password",
        rules: [
          {
            type: "empty",
            prompt: "Insira sua senha",
          },
        ],
      },
    },
    onSuccess: function (event, fields) {
      event.preventDefault(); // Impede o envio padrão do formulário

      // Obtém os dados do formulário
      var formData = $(".ui.form").serialize();

      // Envia a requisição AJAX
      $.ajax({
        type: "POST",
        url: "./../../App/Controllers/Login.php",
        data: formData,
        beforeSend: function () {
          // Adicione uma animação ou mensagem de "carregando" aqui, se desejar
          $(".ui.fluid.large.teal.submit.button").addClass("loading");
          $('.ui.form input[name="user"], .ui.form input[name="password"]').prop('disabled', true);
        },
        success: function (response) {
          // Manipula a resposta recebida
          //alert(response); // Exemplo: exibe a resposta em um alerta

          // Se a validação for bem-sucedida, redirecione para outra página
          if (response === 'login') {
            window.location.href = "home.php";
          }
          if (response === 'invalido') {
            $("#error-modal").modal("show");
          } 
          if(response!== 'login' && response!=='invalido'){
                window.location.href = "generalError.php";
          }
        },
        error: function () {
          alert(
            "Ocorreu um erro ao processar a requisição. Tente novamente mais Tarde!"
          );
        },
        complete: function () {
          // Remova a animação de "carregando" aqui, se necessário
          $(".ui.fluid.large.teal.submit.button").removeClass("loading");
          $('.ui.form input[name="user"], .ui.form input[name="password"]').prop('disabled', false);
        },
      });
    },
  });

  // Configurando o comportamento do modal de erro de autenticação
  $("#error-modal").modal({
    closable: true, // Impede que o usuário feche o modal clicando fora dele
    onHide: function () {
      $(".ui.form").form("reset"); // Limpa os campos do formulário ao fechar o modal
    },
  });
});

window.onload = function() {
  $('#dimmerCarregando').removeClass('active');
}
