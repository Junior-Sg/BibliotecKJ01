document.addEventListener('DOMContentLoaded', () => {

  const baseUrl = window.BASE_URL;

  // Toast de confirmación de reserva exitosa
  function mostrarToastReservaExitosa(tituloLibro) {
    // Crear toast si no existe
    let toastEl = document.getElementById('reservaExitosaToast');
    if (!toastEl) {
      const toastHTML = `
      <div class="position-fixed p-3" style="z-index: 2200; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <div id="reservaExitosaToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #2C5282 0%, #1A365D 100%); box-shadow: 0 12px 32px rgba(44, 82, 130, 0.35); min-width: 380px;">
          <div class="p-4">
            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 0;">
              <div class="icon-wrapper" style="width: 50px; height: 50px; background-color: rgba(72, 187, 120, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="bi bi-check-lg" style="font-size: 1.5rem; color: #48BB78;"></i>
              </div>
              <div style="flex: 1;">
                <h6 class="text-white mb-1" style="font-size: 1.1rem;">¡Reserva Exitosa!</h6>
                <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Tu reserva del libro "${tituloLibro}" se ha realizado correctamente. Te enviaremos una notificación cuando esté lista para recoger.</p>
              </div>
            </div>
          </div>
        </div>
      </div>`;
      document.body.insertAdjacentHTML('beforeend', toastHTML);
      toastEl = document.getElementById('reservaExitosaToast');
    }
    
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
