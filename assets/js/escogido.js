const numerosSeleccionados = [];
let paginaActual = 1;
const numerosPorPagina = 50;

document.addEventListener('DOMContentLoaded', () => {
    // Función para renderizar los números
    function renderizarNumeros(numeros) {
        const container = document.getElementById('numeros-container');
        container.innerHTML = ''; // Limpiar contenedor

        numeros.forEach(num => {
            const numeroElement = document.createElement('button');
            numeroElement.classList.add('btn', 'btn-light', 'number');
            numeroElement.textContent = num.numero;

            // Verificar si el número ya está seleccionado
            if (numerosSeleccionados.includes(num.numero)) {
                numeroElement.classList.add('selected'); // Si está seleccionado, agregar clase 'selected'
            }

            numeroElement.addEventListener('click', () => seleccionarNumero(numeroElement, num.numero));
            container.appendChild(numeroElement);
        });
    }

    // Función para seleccionar o deseleccionar un número
    function seleccionarNumero(numeroElement, numero) {
        const index = numerosSeleccionados.indexOf(numero);
        if (index === -1) {
            // Si el número no está seleccionado, lo agregamos
            numerosSeleccionados.push(numero);
            numeroElement.classList.add('selected'); // Añadir clase 'selected'
        } else {
            // Si el número ya está seleccionado, lo deseleccionamos
            numerosSeleccionados.splice(index, 1);
            numeroElement.classList.remove('selected'); // Eliminar clase 'selected'
        }

        actualizarNumerosSeleccionados(); // Actualizar los números seleccionados en el input
    }

    // Actualiza el input con los números seleccionados
    function actualizarNumerosSeleccionados() {
        const input = document.getElementById('numeros-seleccionados');
        input.value = numerosSeleccionados.join(', '); // Actualiza el campo de texto con los números seleccionados
    }

    // Función para cargar números desde el backend
    function cargarNumeros(pagina) {
        const searchTerm = document.getElementById('search').value; // Obtener término de búsqueda
        const url = '/backend/searchNumerosDisponibles.php?page=' + pagina + '&search=' + searchTerm;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                renderizarNumeros(data.numerosDisponibles);
                controlarPaginacion(data.pagination);
            })
            .catch(error => console.error('Error al cargar los números:', error));
    }

    // Función para controlar la paginación
    function controlarPaginacion(paginacion) {
        document.getElementById('prevPage').disabled = paginaActual === 1;
        document.getElementById('nextPage').disabled = paginaActual === paginacion.totalPages;
    }

    // Evento para ir a la página siguiente
    document.getElementById('nextPage').addEventListener('click', () => {
        paginaActual++;
        cargarNumeros(paginaActual);
    });

    // Evento para ir a la página anterior
    document.getElementById('prevPage').addEventListener('click', () => {
        paginaActual--;
        cargarNumeros(paginaActual);
    });

    // Filtrado por búsqueda
    document.getElementById('search').addEventListener('input', () => {
        paginaActual = 1; // Reiniciar a la primera página al realizar una búsqueda
        cargarNumeros(paginaActual);
    });

    // Cargar la primera página de números al inicio
    cargarNumeros(paginaActual);
});
