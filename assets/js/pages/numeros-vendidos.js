document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search');

  searchInput.addEventListener('input', function() {
    const searchQuery = searchInput.value;
    fetchNumerosVendidos(searchQuery);
  });

  function fetchNumerosVendidos(searchQuery) {
    fetch(`../backend/searchNumerosVendidos.php?search=${encodeURIComponent(searchQuery)}`)
      .then(response => response.json())
      .then(data => {
        const numerosVendidosTable = document.getElementById('numeros-vendidos-table');
        const paginationLinks = document.getElementById('pagination-links');

        // Actualizar la tabla de números vendidos
        let tableHTML = `
          <table class="table table-striped table-hover table-sm">
            <thead>
              <tr>
                <th scope="col">Venta</th>
                <th scope="col">Cliente</th>
                <th scope="col">Celular</th>
                <th scope="col">Ciudad</th>
                <th scope="col">Número</th>
                <th scope="col">Fecha Venta</th>
              </tr>
            </thead>
            <tbody>
        `;

        data.numerosVendidos.forEach(numeroVendido => {
          const fechaFormateada = new Date(numeroVendido.fecha_venta).toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
          });

          tableHTML += `
            <tr>
              <td>${numeroVendido.id_venta}</td>
              <td>${numeroVendido.cliente_nombre}</td>
              <td>${numeroVendido.celular}</td>
              <td>${numeroVendido.ciudad}</td>
              <td><span class="number">${numeroVendido.numero}</span></td>
              <td>${fechaFormateada}</td>
            </tr>
          `;
        });

        tableHTML += '</tbody></table>';
        numerosVendidosTable.innerHTML = tableHTML;

        // Actualizar los enlaces de paginación
        paginationLinks.innerHTML = data.pagination;
      })
      .catch(error => console.error('Error fetching data:', error));
  }
});