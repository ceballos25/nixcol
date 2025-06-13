document.addEventListener('DOMContentLoaded', function () {
  
  window.confirmarEnvio = function(id, celular, nombre, correo) {
    const nombreDecodificado = decodeURIComponent(nombre).replace(/\+/g, ' ');
    const celularDecodificado = decodeURIComponent(celular);
    const correoDecodificado = decodeURIComponent(correo); // Decodificar el correo
  
    Swal.fire({
      title: `¿Estás seguro de enviar esta confirmación?`,
      text: `Cliente: ${nombreDecodificado}`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, enviar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Redirigir a la URL con los parámetros, incluyendo el correo
        window.location.href = `ver_venta.php?id=${encodeURIComponent(id)}&celular=${encodeURIComponent(celularDecodificado)}&nombre=${encodeURIComponent(nombreDecodificado)}&correo=${encodeURIComponent(correoDecodificado)}`;
      }
    });
  };
  

// Función de confirmación con SweetAlert2 para anular
window.confirmarAnulacion = function(id, celular, nombre, correo) {
  const nombreDecodificado = decodeURIComponent(nombre).replace(/\+/g, ' ');
  const celularDecodificado = decodeURIComponent(celular);
  const correoDecodificado = decodeURIComponent(correo);

  Swal.fire({
    title: `¿Está seguro de anular esta venta?`,
    text: `Cliente: ${nombreDecodificado}`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, anular',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      // Redirigir a la página de anulación con todos los parámetros necesarios
      window.location.href = `anular_venta.php?id=${encodeURIComponent(id)}&celular=${encodeURIComponent(celularDecodificado)}&nombre=${encodeURIComponent(nombreDecodificado)}&correo=${encodeURIComponent(correoDecodificado)}`;
    }
  });
};


  // Agregar la función para buscar ventas en tiempo real
  const searchInput = document.getElementById('search');

  searchInput.addEventListener('input', function () {
    const searchQuery = searchInput.value;
    fetchVentas(searchQuery);
  });

  function fetchVentas(searchQuery) {
    fetch(`../backend/searchVentas.php?search=${encodeURIComponent(searchQuery)}`)
      .then(response => response.json())
      .then(data => {
        const ventasTable = document.getElementById('ventas-table');
        const paginationLinks = document.getElementById('pagination-links');

        // Función para formatear el valor como moneda colombiana
        function formatCurrency(value) {
          return '$' + value.toLocaleString('es-CO', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          });
        }

        // Actualizar la tabla de ventas
        let tableHTML = `
          <table class="table table-striped table-hover table-sm">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Cliente</th>
                <th scope="col">Celular</th>
                <th scope="col">Correo</th>
                <th scope="col">Números</th>
                <th scope="col">Total Pago</th>
                <th scope="col">Tipo</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Fecha</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
        `;

        data.ventas.forEach(venta => {
          const fecha = new Date(venta.fecha);
          const fechaFormateada = fecha.toLocaleString('es-ES', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
          });

          let badgeClass = '';
          if (venta.tipo === 'PW') {
            badgeClass = 'bg-primary';
          } else if (venta.tipo === 'VM') {
            badgeClass = 'bg-secondary';
          }

          tableHTML += `
            <tr>
              <td>${venta.id}</td>
              <td>${venta.cliente_nombre}</td>
              <td>${venta.cliente_celular}</td>
              <td>${venta.cliente_correo}</td>
              <td>${venta.total_numeros}</td>
              <td>${formatCurrency(venta.total_pago)}</td>
              <td><span class="badge ${badgeClass}">${venta.tipo}</span></td>
            <td>
                <span class="badge ${venta.vendedor === 'VF' ? 'bg-danger' : 'bg-success'}">
                    ${venta.vendedor}
                </span>
            </td>
              <td>${fechaFormateada}</td>
              <td>
                <!-- Botón con confirmación de envío -->
                <button type="button" class="btn btn-outline-primary mx-1" onclick="confirmarEnvio(
                    '${venta.id}', 
                    '${venta.cliente_celular}', 
                    '${venta.cliente_nombre}', 
                    '${venta.cliente_correo}'
                  )">
                  <i class="ti ti-send"></i>
                </button>
              </td>

            <td>
              <!-- Botón con confirmación de anulación -->
              <button type="button" class="btn btn-outline-danger mx-1" onclick="confirmarAnulacion('${venta.id}', '${venta.cliente_celular}', '${venta.cliente_nombre}', '${venta.cliente_correo}')">
                <i class="ti ti-ban"></i>
              </button>
            </td>
            </tr>
          `;
        });

        tableHTML += '</tbody></table>';
        ventasTable.innerHTML = tableHTML;

        // Actualizar los enlaces de paginación
        paginationLinks.innerHTML = data.pagination;
      })
      .catch(error => console.error('Error fetching data:', error));
  }
});
