document.addEventListener('DOMContentLoaded', function() {
  fetch('https://nixcol.com/backend/getTopClientes.php')
    .then(response => response.json())
    .then(data => {
      const seriesData = data.map(item => item.total_numeros);
      const categoriesData = data.map(item => item.celular);

      var optionsClientes = {
        series: [{
          data: seriesData,
          name: 'Números'
        }],
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            borderRadiusApplication: 'end',
            horizontal: true,
          }
        },
        colors: ['#000'],
        dataLabels: {
          enabled: true
        },
        xaxis: {
          categories: categoriesData,
        },
      };

      var chartClientes = new ApexCharts(document.querySelector("#clientes"), optionsClientes);
      chartClientes.render();
    })
    .catch(error => console.error('Error fetching data:', error));

  fetch('https://nixcol.com/backend/getTopCiudades.php')
    .then(response => response.json())
    .then(data => {
      const seriesData = data.map(item => parseFloat(item.total_dinero));
      const categoriesData = data.map(item => item.ciudad);

      var optionsCiudades = {
        series: [{
          name: 'Dinero',
          data: seriesData
        }],
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            borderRadiusApplication: 'end',
            horizontal: true,
          }
        },
        colors: ['#000'],
        dataLabels: {
          enabled: true
        },
        xaxis: {
          categories: categoriesData,
        },
        yaxis: {
          title: {
            formatter: function (value) {
              return '$' + value.toLocaleString('es-ES');
            }
          }
        },
        tooltip: {
          y: {
            formatter: function (value) {
              return '$' + value.toLocaleString('es-ES');
            }
          }
        }
      };

      var chartCiudades = new ApexCharts(document.querySelector("#ciudades"), optionsCiudades);
      chartCiudades.render();
    })
    .catch(error => console.error('Error fetching data:', error));
});