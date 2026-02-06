document.addEventListener('DOMContentLoaded', () => {

  const baseUrl = window.BASE_URL;

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

    if (confirmModal) confirmModal.show();
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
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: form.toString()
      });

      const j = await resp.json();

      if (j.ok) {
        if (confirmModal) confirmModal.hide();

        // Cerrar modal de detalle si está abierto
        const bsDetalle = bootstrap.Modal.getInstance(document.getElementById('modalDetalle'));
        if (bsDetalle) bsDetalle.hide();

        // Redireccionar a confirmación
        window.location.href = `${baseUrl}/index.php?controller=Reserva&action=confirmacion`;
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
        document.getElementById('errorReservaMessage').textContent = 'Error al reservar. Inténtalo de nuevo.';
        errorModal.show();
      }
    } finally {
      confirmBtn.disabled = false;
      confirmBtn.textContent = 'Confirmar reserva';
    }
  });
});
