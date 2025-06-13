document.addEventListener('DOMContentLoaded', () => {
  // --- Configuración de precios y paquetes ---
  const config = {
    pricePerOpportunity: 1500,
    opportunityPackages: {
      '5': { cantidad: 5, precio: 7500 },
      '10': { cantidad: 10, precio: 15000 },
      '20': { cantidad: 20, precio: 30000 },
      '30': { cantidad: 30, precio: 45000 },
      'otro': { cantidad: 5, precio: 7500 }
    }
  };

  // --- Elementos del DOM ---
  const $ = id => document.getElementById(id);
  const form = $('form-venta');
  const totalInput = $('total-oportunidades');
  const totalPayInput = $('total-pago');
  const selectInput = $('select-oportunidades');
  const buttonComprar = document.querySelectorAll('.btn-comprar');
  const closeModal = $('closeModal');
  const modalContainer = $('modalContainer');
  const celularInput = $('celular');
  const preloader = $('preloader');

  if (modalContainer) {
    modalContainer.style.display = 'none';
  }

// --- Limpia el prefijo +57 y elimina espacios del celular ---
if (celularInput) {
  celularInput.addEventListener('input', function () {
    this.value = this.value.replace(/^\+57\s?/, '').replace(/\s+/g, '');
  });
}
  // --- Lógica para la card personalizada (card diamante) ---
  const card = document.querySelector('.card-personalizado');
  if (card) {
    const cantidadInput = card.querySelector('#cantidad');
    const precioDiv = card.querySelector('.precio');
    const btnComprarCard = card.querySelector('.btn-comprar');

    // Actualiza el precio en tiempo real en la card
    cantidadInput.addEventListener('input', () => {
      const cantidad = parseInt(cantidadInput.value, 10);
      const total = (!isNaN(cantidad) && cantidad > 0) ? cantidad * config.pricePerOpportunity : 0;
      precioDiv.textContent = `$${total.toLocaleString('es-CO')}`;
    });

    // Al hacer click en COMPRAR, valida y abre el modal solo si es válido
    btnComprarCard.addEventListener('click', (e) => {
      e.stopPropagation();
      const cantidad = parseInt(cantidadInput.value, 10);
      if (isNaN(cantidad) || cantidad < 5) {
        Toastify({
          text: "Ingresa una cantidad válida (mínimo 5).",
          duration: 3000,
          gravity: "top",
          position: "center",
          backgroundColor: "#e74c3c",
          close: true,
          stopOnFocus: true
        }).showToast();
        return;
      }
      selectInput.value = 'otro';
      totalInput.removeAttribute('readonly');
      totalInput.value = cantidad;
      totalPayInput.value = `$${(cantidad * config.pricePerOpportunity).toLocaleString('es-CO')}`;
      modalContainer.classList.add('modal-overlay');
      modalContainer.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    });
  }

  // --- Lógica para abrir el modal desde otras cards (no la personalizada) ---
  buttonComprar.forEach(button => {
    button.addEventListener('click', function () {
      if (!button.closest('.card-personalizado')) {
        const paquete = button.getAttribute('data-paquete');
        setPackage(paquete || selectInput.value || '5');
        modalContainer.classList.add('modal-overlay');
        modalContainer.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    });
  });

  // --- Función para actualizar el paquete y el precio en el modal ---
  function setPackage(value) {
    const pack = config.opportunityPackages[value] || config.opportunityPackages['otro'];
    selectInput.value = value;
    if (value === 'otro') {
      totalInput.removeAttribute('readonly');
      if (!totalInput.value || isNaN(totalInput.value)) {
        totalInput.value = '';
        totalPayInput.value = '$0';
      } else {
        const qty = parseInt(totalInput.value) || 0;
        totalPayInput.value = `$${(qty * config.pricePerOpportunity).toLocaleString('es-CO')}`;
      }
      totalInput.focus();
    } else {
      totalInput.value = pack.cantidad;
      totalInput.setAttribute('readonly', true);
      totalPayInput.value = `$${pack.precio.toLocaleString('es-CO')}`;
    }
  }

  // --- Actualiza el precio en el modal cuando escriben cantidad en "otro" ---
  totalInput.addEventListener('input', () => {
    if (selectInput.value === 'otro') {
      const qty = parseInt(totalInput.value) || 0;
      totalPayInput.value = `$${(qty * config.pricePerOpportunity).toLocaleString('es-CO')}`;
    }
  });

  // --- Cambia el paquete seleccionado en el modal ---
  selectInput.addEventListener('change', () => setPackage(selectInput.value));
  setPackage(selectInput.value || '5');

  // --- Cerrar el modal con el botón de cerrar ---
  if (closeModal && modalContainer) {
    closeModal.addEventListener('click', () => {
      modalContainer.classList.remove('modal-overlay');
      modalContainer.style.display = 'none';
      document.body.style.overflow = '';
    });
  }

  // --- Validación de formulario antes de enviar ---
  if (form) {
    form.addEventListener('submit', event => {
      const requiredFields = [
        'celular', 'nombre', 'correo', 'usp-custom-departamento-de-residencia',
        'usp-custom-municipio-ciudad', 'select-oportunidades', 'total-oportunidades', 'total-pago'
      ];
      const isValid = requiredFields.every(id => {
        const field = $(id);
        return field && field.value.trim();
      }) && !isNaN(parseInt(totalInput.value, 10)) && parseInt(totalInput.value, 10) >= 5;

      if (!isValid) {
        event.preventDefault();
        Toastify({
          text: "Por favor, completa el formulario.",
          duration: 3000,
          gravity: "right",
          position: "center",
          backgroundColor: "#e74c3c",
          close: true,
          stopOnFocus: true
        }).showToast();
      } else {
        if (preloader) preloader.style.display = 'flex';
      }
    });
  } else {
    console.error("El formulario de pago no fue encontrado.");
  }
});