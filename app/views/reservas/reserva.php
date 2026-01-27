<?php
// partial: reserva.php (modal confirmación + toast login)
?>
<!-- Modal Confirmación de Reserva -->
<div class="modal fade" id="confirmReservaModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body d-flex gap-3">
        <img id="confirm_img" src="" alt="Portada" style="width:90px; height:120px; object-fit:cover;">
        <div>
          <h6 id="confirm_title" class="mb-1"></h6>
          <p id="confirm_author" class="mb-1 text-muted small"></p>
          <p id="confirm_editorial" class="mb-0 small text-muted"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button id="confirmReservaBtn" type="button" class="btn btn-primary">Confirmar reserva</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Toast login (cuando no está autenticado) -->
<div class="position-fixed p-3" style="z-index: 2100; top: 50%; left: 50%; transform: translate(-50%, -50%);">
  <div id="loginToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #2C5282 0%, #1A365D 100%); box-shadow: 0 12px 32px rgba(44, 82, 130, 0.35); min-width: 380px;">
    <div class="p-4">
      <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
        <i class="bi bi-lock-fill" style="font-size: 1.75rem; color: #60A5FA; flex-shrink: 0; margin-top: 2px;"></i>
        <div style="flex: 1;">
          <h6 class="text-white mb-1" style="font-size: 1.1rem;">Inicia sesión para reservar</h6>
          <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Accede a tu cuenta para reservar libros de nuestra biblioteca.</p>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Cerrar" style="margin-top: -4px;"></button>
      </div>
      <div class="d-flex gap-2" style="margin-top: 16px;">
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=LoginUsuario&action=index" class="btn btn-sm text-white flex-grow-1" style="background-color: #60A5FA; border: none; font-weight: 500; transition: background-color 0.2s;">Iniciar sesión</a>
        <button type="button" class="btn btn-sm text-white flex-grow-1" data-bs-dismiss="toast" style="background-color: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); font-weight: 500;">Cerrar</button>
      </div>
    </div>
  </div>
</div>

