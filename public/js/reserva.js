document.addEventListener('DOMContentLoaded', () => {

  const baseUrl = window.BASE_URL;

  // Toast de confirmación de reserva exitosa
  function mostrarToastReservaExitosa(tituloLibro) {
    // Crear toast si no existe
    let toastEl = document.getElementById('reservaExitosaToast');
    
    // Mostrar toast
    const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
    toast.show();
  }

  const toastEl = document.getElementById('loginToast');
  const loginToast = toastEl ? new bootstrap.Toast(toastEl, { delay: 5000 }) : null;

  const confirmModalEl = document.getElementById('confirmReservaModal');
  const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;

  // Crear instancia global del modal de error para reutilizarla
  const errorModalEl = document.getElementById('errorReservaModal');
  const errorModal = errorModalEl ? new bootstrap.Modal(errorModalEl, { backdrop: true, keyboard: true }) : null;

  // Botón para intentar reservar
  const btnReservar = document.getElementById('btnReservar');
  btnReservar.addEventListener('click', () => {

    const libroActual = window.libroActual;
    if (!libroActual) return;

    const logged = window.USER_LOGGED; // lo definimos en el HTML

    if (!logged) {
      if (loginToast) loginToast.show();
      return;
    }

    // Rellenar modal de confirmación
    document.getElementById('confirm_img').src =
      libroActual.Imagen
        ? `${baseUrl}/public/img/Libros/${libroActual.Imagen}`
        : `${baseUrl}/public/img/Libros/default.jpg`;

    document.getElementById('confirm_title').textContent = libroActual.titulo || '';
    document.getElementById('confirm_author').textContent = (libroActual.autores || []).join(', ') || '';
    document.getElementById('confirm_editorial').textContent = libroActual.editorial || '';

    // Si el modal de detalle está abierto, ciérralo primero para evitar solapar aria-hidden
    const detalleEl = document.getElementById('modalDetalle');
    const detalleModal = detalleEl ? bootstrap.Modal.getInstance(detalleEl) : null;

    if (detalleModal && detalleEl.classList.contains('show')) {
      // Espera a que se cierre y luego muestra confirmación
      const handler = () => {
        detalleEl.removeEventListener('hidden.bs.modal', handler);
        document.activeElement?.blur(); // libera foco antes de nuevo modal
        if (confirmModal) confirmModal.show();
      };
      detalleEl.addEventListener('hidden.bs.modal', handler);
      detalleModal.hide();
    } else {
      document.activeElement?.blur();
      if (confirmModal) confirmModal.show();
    }
  });

  // Botón de confirmar reserva
  const confirmBtn = document.getElementById('confirmReservaBtn');
  confirmBtn.addEventListener('click', async () => {

    const libroActual = window.libroActual;
    if (!libroActual) return;

    confirmBtn.disabled = true;
    confirmBtn.textContent = 'Reservando...';

    try {
      const form = new URLSearchParams();
      form.append('id_libro', libroActual.id_libro);

      const resp = await fetch(`${baseUrl}/index.php?controller=Reserva&action=guardarAjax`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: form.toString()
      });

      const txt = await resp.text();
      let j;
      try {
        j = JSON.parse(txt);
      } catch (parseErr) {
        throw new Error('Respuesta no JSON del servidor: ' + txt.slice(0, 200));
      }

      if (j.ok) {
        if (confirmModal) confirmModal.hide();

        // Cerrar modal de detalle si está abierto
        const bsDetalle = bootstrap.Modal.getInstance(document.getElementById('modalDetalle'));
        if (bsDetalle) bsDetalle.hide();

        // Mostrar toast de confirmación en la misma página
        mostrarToastReservaExitosa(libroActual.titulo);
      } else {
        // Ocultar modal de confirmación y mostrar error
        if (confirmModal) confirmModal.hide();
        
        // Mostrar modal de error en lugar de alert
        if (errorModal) {
          document.getElementById('errorReservaMessage').textContent = j.error || 'Error desconocido';
          errorModal.show();
        }
      }

    } catch (e) {
      console.error(e);
      // Ocultar modal de confirmación y mostrar error
      if (confirmModal) confirmModal.hide();
      
      // Mostrar modal de error en lugar de alert
      if (errorModal) {
        document.getElementById('errorReservaMessage').textContent = e.message || 'Error al reservar. Inténtalo de nuevo.';
        errorModal.show();
      }
    } finally {
      confirmBtn.disabled = false;
      confirmBtn.textContent = 'Confirmar reserva';
    }
  });
});
