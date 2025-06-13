document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
  
    searchInput.addEventListener('input', function() {
      const searchQuery = searchInput.value;
      fetchRespaldo(searchQuery);
    });
  
    function fetchRespaldo(searchQuery) {
      fetch(`../backend/searchRespaldo.php?search=${encodeURIComponent(searchQuery)}`)
        .then(response => response.json())
        .then(data => {
          const respaldoTable = document.getElementById('respaldo-table');
          const paginationLinks = document.getElementById('pagination-links');
  
          // Actualizar la tabla de respaldo
          let tableHTML = `
            <table class="table table-striped table-hover table-sm">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Cliente</th>
                  <th scope="col">Celular</th>
                  <th scope="col">Correo</th>
                  <th scope="col">Depto</th>
                  <th scope="col">Ciudad</th>
                  <th scope="col">Oportunidades</th>
                  <th scope="col">Pago</th>
                  <th scope="col">ID Transacción</th>
                  <th scope="col">Fecha</th>
                </tr>
              </thead>
              <tbody>
          `;
  
          data.respaldo.forEach(registro => {
            tableHTML += `
              <tr>
                <td>${registro.id}</td>
                <td>${registro.cliente}</td>
                <td>${registro.celular}</td>
                <td>${registro.correo}</td>
                <td>${registro.departamento}</td>
                <td>${registro.ciudad}</td>
                <td>${registro.oportunidades}</td>
                <td>${registro.pago}</td>
                <td>${registro.id_transaccion}</td>
                <td>${registro.fecha}</td>
              </tr>
            `;
          });
  
          tableHTML += '</tbody></table>';
          respaldoTable.innerHTML = tableHTML;
  
          // Actualizar los enlaces de paginación
          paginationLinks.innerHTML = data.pagination;
        })
        .catch(error => console.error('Error fetching data:', error));
    }
  });