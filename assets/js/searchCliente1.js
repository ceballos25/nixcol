document.addEventListener('DOMContentLoaded', () => {
    const celularInput = document.getElementById('celular');
    const departamentoSelect = document.getElementById('usp-custom-departamento-de-residencia');
    const ciudadSelect = document.getElementById('usp-custom-municipio-ciudad');
    const nombreInput = document.getElementById('nombre');
    const correoInput = document.getElementById('correo');
    const form = document.querySelector('form');  // Accedemos al formulario

    // Función para poner los campos como readonly
    const ponerCamposReadonly = (readonly) => {
        nombreInput.readOnly = readonly;
        correoInput.readOnly = readonly;
        departamentoSelect.readOnly = readonly;
        ciudadSelect.readOnly = readonly;
    };

    // Función para limpiar los campos
    const limpiarCampos = () => {
        nombreInput.value = '';
        correoInput.value = '';
        departamentoSelect.value = '';
        ciudadSelect.value = '';
    };

    // Función para manejar la respuesta de la búsqueda de cliente
    const manejarCliente = async (celular) => {
        try {
            const response = await fetch('../backend/searchCliente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `celular=${celular}`
            });
            const data = await response.json();

            if (data.success) {
                const cliente = data.cliente;
                nombreInput.value = cliente.nombre;
                correoInput.value = cliente.correo;
                departamentoSelect.value = cliente.departamento;

                // Poner los campos como readonly
                ponerCamposReadonly(true);

                // Desencadenar evento para cargar las ciudades
                const event = new Event('change');
                departamentoSelect.dispatchEvent(event);

                // Esperar que las opciones de ciudad se carguen
                setTimeout(() => {
                    ciudadSelect.value = cliente.ciudad;
                    ciudadSelect.readOnly = true;
                }, 500); // Esperar medio segundo para asegurar que las ciudades ya se han cargado

                // Marcar campos como válidos
                nombreInput.classList.add('is-valid');
                correoInput.classList.add('is-valid');
                departamentoSelect.classList.add('is-valid');
                ciudadSelect.classList.add('is-valid');
            } else {
                limpiarCampos();
                ponerCamposReadonly(false);
                ciudadSelect.readOnly = false;
                nombreInput.classList.remove('is-valid');
                correoInput.classList.remove('is-valid');
                departamentoSelect.classList.remove('is-valid');
                ciudadSelect.classList.remove('is-valid');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };


    // Escuchar cambios en el input de celular
    if (celularInput) {
        celularInput.addEventListener('change', () => {
            if (celularInput.value) {
                manejarCliente(celularInput.value);
            } else {
                limpiarCampos();
                ponerCamposReadonly(false);
                nombreInput.classList.remove('is-valid');
                correoInput.classList.remove('is-valid');
                departamentoSelect.classList.remove('is-valid');
                ciudadSelect.classList.remove('is-valid');
            }
        });

    }

    if (form) {
        // Validación del formulario con Bootstrap
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            // Añadir la clase de validación de Bootstrap
            form.classList.add('was-validated');
        });
    }
});
