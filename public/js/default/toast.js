 function toastSucesso() {
    $.toast({
      title: "SUCESSO!",
      class: "success",
      position: "top right",
      displayTime: "10000",
      showProgress: "top",
      classProgress: "black",
      message: "Operação efetuada com êxito!",
      showIcon: "check circle",
    });
  }
  
 function toastErro($mesagem) {
    $.toast({
      title: "ERRO!",
      class: "centered error",
      position: "top right",
      displayTime: "10000",
      showProgress: "top",
      classProgress: "black",
      message: $mesagem,
      showIcon: "skull crossbones",
    });
  }
  
 function toastAtencao($mesagem) {
    $.toast({
      title: "ATENÇÃO!",
      class: "centered warning",
      position: "top right",
      displayTime: "10000",
      showProgress: "top",
      classProgress: "black",
      message: $mesagem,
      showIcon: "exclamation triangle",
    });
  }
  