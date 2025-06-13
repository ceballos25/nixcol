document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');  // El formulario
    const submitButton = document.getElementById('submit-button');  // El botón
    const spinner = document.getElementById('spinner');  // El spinner

    // Evento para manejar el envío del formulario
    form.addEventListener('submit', (event) => {
        event.preventDefault();  // Prevenir el envío inmediato

        if (form.checkValidity()) {  // Validar si el formulario es válido
            // Añadir la clase de validación de Bootstrap
            form.classList.add('was-validated');

            // Mostrar el spinner y deshabilitar el botón
            submitButton.disabled = true;
            spinner.classList.remove('d-none');  // Mostrar el spinner

            // Aquí puedes enviar el formulario via AJAX o el método que prefieras
            // Por ejemplo, si es un envío normal, puedes usar submit() después de 2 segundos

            // Simulando un retraso en el envío para mostrar el spinner
            setTimeout(() => {
                form.submit();  // Enviar el formulario después de la validación
            }, 2000);  // Puedes ajustar este tiempo (2 segundos) según el tiempo que desees

        } else {
            // Si el formulario no es válido, aseguramos que no se envíe y el spinner no se muestra
            form.classList.add('was-validated');  // Aseguramos que se apliquen los estilos de error de Bootstrap
        }
    });
});
