<?php
// partial: detalle.php (modal detalle)
?>
<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle del libro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-4">
            <img id="det_imagen" src="" alt="Portada" class="img-fluid rounded" style="object-fit:cover;">
          </div>
          <div class="col-md-8">
            <h4 id="det_titulo" class="mb-2"></h4>
            <p id="det_sinopsis" class="text-muted"></p>
            <p class="mb-1"><strong>Autores:</strong> <span id="det_autores"></span></p>
            <p class="mb-1"><strong>Géneros:</strong> <span id="det_generos"></span></p>
            <p class="mb-1"><strong>Editorial:</strong> <span id="det_editorial"></span></p>
            <p class="mb-1"><strong>Año:</strong> <span id="det_anio"></span></p>
            <p class="mb-1"><strong>Ubicación:</strong> <span id="det_estante"></span></p>
            <p class="mb-1"><strong>Disponibles:</strong> <span id="det_disp"></span></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button id="btnReservar" type="button" class="btn btn-success">Reservar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>