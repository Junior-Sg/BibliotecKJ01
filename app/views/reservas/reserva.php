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
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
  <div id="loginToast" class="toast align-items-center text-bg-warning border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Necesitas iniciar sesión para reservar. <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=LoginUsuario&action=index" class="fw-bold">Iniciar sesión</a>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>

