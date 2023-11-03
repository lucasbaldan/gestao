var ptBR_calendar = {
  days: ["D", "S", "T", "Q", "Q", "S", "S"],
  dayNamesShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"],
  months: [
    "Janeiro",
    "Fevereiro",
    "Março",
    "Abril",
    "Maio",
    "Junho",
    "Julho",
    "Agosto",
    "Setembro",
    "Outubro",
    "Novembro",
    "Dezembro",
  ],
  monthsShort: [
    "Jan",
    "Fev",
    "Mar",
    "Abr",
    "Mai",
    "Jun",
    "Jul",
    "Ago",
    "Set",
    "Out",
    "Nov",
    "Dez",
  ],
};

function uiCalendar(idInput) {
 var inputCalendario = $('#'+idInput).calendar({
    text: ptBR_calendar,
    type: "date",
    formatter: {
      date: "DD/MM/YYYY",
    },
  });
  inputCalendario.calendar('clear');
  return inputCalendario;
}

//inputCalendario.calendar('set date', '2023-03-01');
