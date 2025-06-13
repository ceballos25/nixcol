
document.addEventListener('DOMContentLoaded', () => {
    const celularInput = document.getElementById('celular');
    const nombreInput = document.getElementById('nombre');
    const correoInput = document.getElementById('email');
    const departamentoSelect = document.getElementById('usp-custom-departamento-de-residencia');
    const ciudadSelect = document.getElementById('usp-custom-municipio-ciudad');


    const limpiarCampos = () => {
        nombreInput.value = '';
        correoInput.value = '';
        departamentoSelect.value = '';
        ciudadSelect.innerHTML = '<option selected disabled></option>';
    };

    const buscarCliente = async (celular) => {
        try {
            const res = await fetch('./backend/searchCliente.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `celular=${encodeURIComponent(celular)}`
            });
            const data = await res.json();

            if (data.success && data.cliente) {
                const cliente = data.cliente;

                nombreInput.value = cliente.nombre;
                correoInput.value = cliente.correo;
                departamentoSelect.value = cliente.departamento;

                // Disparar evento de cambio en departamento para cargar ciudades
                departamentoSelect.dispatchEvent(new Event('change'));

                // Esperar a que carguen ciudades si aplica
                setTimeout(() => {
                    ciudadSelect.value = cliente.ciudad;
                }, 500);

                Toastify({
                    text: "¡Ya eres cliente!",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#FF8C00"
                }).showToast();
            } else {
                limpiarCampos();
            }
        } catch (err) {
            console.error('Error al buscar cliente:', err);
        }
    };

    celularInput.addEventListener('input', () => {
        const celular = celularInput.value.trim();
        if (/^\d{10}$/.test(celular)) {
            buscarCliente(celular);
        }
    });

});

