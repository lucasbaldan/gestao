<script>
    $(document).ready(function() {
        
        $("#enviar").click(function() {
        var dados = [];

        // Obter os títulos das colunas
        var colunas = tabela.columns().header().toArray().map(function(th) {
          return $(th).text();
        });

        // Loop através de todas as linhas da tabela e coletar os dados
        tabela.rows().every(function() {
          var rowData = this.data();
          var obj = {};
          colunas.forEach(function(coluna, index) {
            obj[coluna] = rowData[index];
          });
          dados.push(obj);
        });
  </script>