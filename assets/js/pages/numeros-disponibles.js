document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');

    searchInput.addEventListener('input', function() {
        const searchQuery = searchInput.value;
        fetchNumerosDisponibles(searchQuery);
    });

    function fetchNumerosDisponibles(searchQuery) {
        fetch(`../backend/searchNumerosDisponibles.php?search=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                const numerosDisponiblesTable = document.getElementById('numeros-disponibles-table');
                const paginationLinks = document.getElementById('pagination-links');

                // Verificar si data.numerosDisponibles está definido
                if (!data.numerosDisponibles) {
                    numerosDisponiblesTable.innerHTML = '<p>No se encontraron resultados.</p>';
                    paginationLinks.innerHTML = '';
                    return;
                }

                // Actualizar la tabla de números disponibles
                let tableHTML = `
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Número</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.numerosDisponibles.forEach(numero => {
                    let badgeClass = '';
                    if (numero.estado === 'Disponible') {
                        badgeClass = 'bg-success';
                    } else if (numero.estado === 'Vendido') {
                        badgeClass = 'bg-danger';
                    } else if (numero.estado === 'Reservado') {
                        badgeClass = 'bg-warning text-dark';
                    } else {
                        badgeClass = 'bg-secondary';
                    }

                    tableHTML += `
                        <tr>
                            <td><span class="number">${numero.numero}</span></td>
                            <td><span class="badge ${badgeClass}">${numero.estado}</span></td>
                            <td>
                                ${numero.estado !== 'Vendido' ? ` 
                                    <button type="button" class="btn btn-outline-danger cambiar-estado-btn" 
                                            data-numero="${numero.numero}" 
                                            data-estado="${numero.estado}"> 
                                        <i class="ti ti-toggle-left"></i>
                                    </button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });

                tableHTML += '</tbody></table>';
                numerosDisponiblesTable.innerHTML = tableHTML;

                // Actualizar los enlaces de paginación
                paginationLinks.innerHTML = data.pagination || '';

                // Reasignar eventos a los nuevos botones
                assignButtonEvents();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function assignButtonEvents() {
        document.querySelectorAll('.cambiar-estado-btn').forEach(button => {
            button.addEventListener('click', function() {
                const numero = this.getAttribute('data-numero');
                const estadoActual = this.getAttribute('data-estado');
                const nuevoEstado = estadoActual === 'Disponible' ? 'Reservado' : 'Disponible'; // Cambia el estado

                // Usando SweetAlert2 en lugar de confirm
                Swal.fire({
                    title: `¿Estás seguro de cambiar el estado del número ${numero}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('../backend/actualizarEstadoNumero.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `numero=${numero}&estado=${nuevoEstado}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Toastify({
                                    text: "Estado actualizado",
                                    className: "success",
                                    style: {
                                        background: "#00b09b",
                                    },
                                    gravity: "top", // `top` or `bottom`
                                    position: "center", // `left`, `center` or `right`
                                }).showToast();
                                fetchNumerosDisponibles(searchInput.value); // Actualizar la tabla
                            } else {
                                Swal.fire(
                                    'Error',
                                    'Hubo un error al actualizar el estado.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error updating state:', error);
                            Swal.fire(
                                'Error',
                                'Hubo un error al actualizar el estado.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    }

    // Inicializar eventos en los botones existentes
    assignButtonEvents();
});
