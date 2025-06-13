document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const editForm = document.getElementById('editForm');
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    const submitButton = editForm.querySelector('button[type="submit"]');
    const spinner = submitButton.querySelector('.spinner-border');

    searchInput.addEventListener('input', function() {
        const searchQuery = searchInput.value;
        fetchClientes(searchQuery);
    });

    function fetchClientes(searchQuery) {
        fetch(`../backend/searchClientes.php?search=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                const clientesTable = document.getElementById('clientes-table');
                const paginationLinks = document.getElementById('pagination-links');

                // Actualizar la tabla de clientes
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
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

                data.clientes.forEach(cliente => {
                    tableHTML += `
                        <tr>
                            <td>${cliente.id}</td>
                            <td>${cliente.nombre}</td>
                            <td>${cliente.celular}</td>
                            <td>${cliente.correo}</td>
                            <td>${cliente.departamento}</td>
                            <td>${cliente.ciudad}</td>
                            <td>
                                <button type="button" class="btn btn-outline-primary edit-btn" 
                                        data-id="${cliente.id}" 
                                        data-nombre="${cliente.nombre}" 
                                        data-celular="${cliente.celular}" 
                                        data-correo="${cliente.correo}" 
                                        data-departamento="${cliente.departamento}" 
                                        data-ciudad="${cliente.ciudad}" 
                                        data-bs-toggle="modal" data-bs-target="#editModal"> 
                                    <i class="ti ti-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger delete-btn" data-id="${cliente.id}"> 
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });

                tableHTML += '</tbody></table>';
                clientesTable.innerHTML = tableHTML;

                // Actualizar los enlaces de paginación
                paginationLinks.innerHTML = data.pagination;

                // Reasignar eventos a los nuevos botones
                assignButtonEvents();
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    function assignButtonEvents() {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const celular = this.getAttribute('data-celular');
                const correo = this.getAttribute('data-correo');
                const departamento = this.getAttribute('data-departamento');
                const ciudad = this.getAttribute('data-ciudad');

                document.getElementById('editId').value = id;
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editCelular').value = celular;
                document.getElementById('editCorreo').value = correo;
                // document.getElementById('editDepartamento').value = departamento;
                // document.getElementById('editCiudad').value = ciudad;

                editModal.show();
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('¿Estás seguro de que deseas eliminar este cliente?')) {
                    fetch(`../backend/eliminarCliente.php?id=${id}`, {
                        method: 'DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            fetchClientes(searchInput.value);
                            Toastify({
                                text: "Cliente eliminado exitosamente",
                                className: "success",
                                style: {
                                    background: "#00b09b",
                                },
                                gravity: "top", // `top` or `bottom`
                                position: "center", // `left`, `center` or `right`
                            }).showToast();
                        } else {
                            Toastify({
                                text: "No puedes eliminar un cliente con ventas asociadas.",
                                className: "error",
                                style: {
                                    background: "#ff5f6d",
                                },
                                gravity: "top", // `top` or `bottom`
                                position: "center", // `left`, `center` or `right`
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting data:', error);
                        Toastify({
                            text: "No puedes eliminar un cliente con ventas asociadas.",
                            className: "error",
                            style: {
                                background: "#ff5f6d",
                            },
                            gravity: "top", // `top` or `bottom`
                            position: "center", // `left`, `center` or `right`
                        }).showToast();
                    });
                }
            });
        });
    }

    editForm.addEventListener('submit', function(event) {
        event.preventDefault();
        spinner.classList.remove('d-none');
        submitButton.disabled = true;

        const formData = new FormData(editForm);
        fetch('../backend/actualizarCliente.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchClientes(searchInput.value);
                editModal.hide();
                Toastify({
                    text: "Cliente actualizado exitosamente",
                    className: "success",
                    style: {
                        background: "#00b09b"
                    },
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
            } else {
                Toastify({
                    text: "Error al actualizar el cliente",
                    className: "error",
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    },
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                }).showToast();
            }
        })
        .catch(error => {
            console.error('Error updating data:', error);
            Toastify({
                text: "Error al actualizar el cliente",
                className: "error",
                style: {
                    background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                },
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
            }).showToast();
        })
        .finally(() => {
            spinner.classList.add('d-none');
            submitButton.disabled = false;
        });
    });

    // Inicializar eventos en los botones existentes
    assignButtonEvents();
});