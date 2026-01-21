<?php
// partial: detalle.php (modal detalle con diseño de libro abierto)
?>
<style>
  /* Estilos para el modal de libro abierto */
  #modalDetalle .modal-content {
    border: none;
    border-radius: 0;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }

  #modalDetalle .modal-body {
    padding: 0;
    background: linear-gradient(to right, #F5F1ED 0%, #FFFFFF 50%, #F5F1ED 100%);
    min-height: 500px;
  }

  .libro-abierto {
    display: flex;
    gap: 0;
    height: 100%;
    align-items: stretch;
    perspective: 1200px;
  }

  .libro-portada {
    flex: 0 0 45%;
    padding: 40px 30px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    background: linear-gradient(to right, #FFFFFF 0%, #F9F7F4 100%);
    position: relative;
    border-right: 2px solid #D4C4B1;
    box-shadow: inset 2px 0 8px rgba(0, 0, 0, 0.05);
  }

  .libro-portada::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: repeating-linear-gradient(90deg, transparent, transparent 2px, rgba(255, 255, 255, 0.01) 2px, rgba(255, 255, 255, 0.01) 4px);
    pointer-events: none;
  }

  .portada-imagen {
    width: 100%;
    max-width: 200px;
    height: 300px;
    object-fit: cover;
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
  }

  .portada-titulo {
    color: #FFFEFC;
    font-size: 1.1rem;
    font-weight: bold;
    text-align: center;
    line-height: 1.4;
    position: relative;
    z-index: 2;
  }

  .libro-contenido {
    flex: 1;
    padding: 40px 35px;
    overflow-y: auto;
    background: linear-gradient(to right, #FFFFFF 0%, #F9F7F4 100%);
    position: relative;
  }

  .libro-contenido::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 1px;
    background: linear-gradient(to bottom, rgba(139, 111, 87, 0.2) 0%, rgba(139, 111, 87, 0.05) 50%, rgba(139, 111, 87, 0.2) 100%);
  }

  .contenido-info {
    position: relative;
    z-index: 1;
  }

  .contenido-info h4 {
    color: #3B2416;
    font-size: 1.5rem;
    margin-bottom: 12px;
    font-weight: bold;
    border-bottom: 2px solid #FF9800;
    padding-bottom: 8px;
  }

  .contenido-info p {
    margin-bottom: 12px;
    color: #241705;
    line-height: 1.6;
    font-size: 0.95rem;
  }

  .info-item {
    display: flex;
    margin-bottom: 10px;
    align-items: flex-start;
  }

  .info-label {
    font-weight: 600;
    color: #3B2416;
    min-width: 110px;
    flex-shrink: 0;
  }

  .info-valor {
    color: #5A5A5A;
    flex: 1;
  }

  .sinopsis {
    background: rgba(255, 152, 0, 0.08);
    padding: 15px;
    border-left: 3px solid #FF9800;
    border-radius: 4px;
    margin-bottom: 20px;
    font-style: italic;
    color: #404040;
    line-height: 1.6;
  }

  #modalDetalle .modal-header {
    display: none;
  }

  #modalDetalle .modal-footer {
    background: linear-gradient(to right, #F5F1ED 0%, #FFFFFF 50%, #F5F1ED 100%);
    border-top: 1px solid #D4C4B1;
    gap: 10px;
    padding: 15px 35px;
  }

  #modalDetalle .modal-footer .btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 8px 24px;
  }

  #modalDetalle .btn-success {
    background: linear-gradient(135deg, #FF9800 0%, #F57C00 100%);
    border: none;
    color: white;
    transition: all 0.3s ease;
  }

  #modalDetalle .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(255, 152, 0, 0.4);
  }

  #modalDetalle .btn-secondary {
    background: #C4B5A0;
    border: none;
    color: white;
  }

  #modalDetalle .btn-secondary:hover {
    background: #B8A591;
  }

  @media (max-width: 768px) {
    .libro-abierto {
      flex-direction: column;
    }

    .libro-portada {
      flex: 0 0 auto;
      padding: 25px 20px;
      border-right: none;
      border-bottom: 8px solid #8B6F57;
    }

    .libro-contenido {
      padding: 25px 20px;
    }

    #modalDetalle .modal-body {
      min-height: auto;
    }
  }
</style>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="libro-abierto">
          <!-- Portada (lado izquierdo) -->
          <div class="libro-portada">
            <img id="det_imagen" src="" alt="Portada" class="portada-imagen">
            <div class="portada-titulo" id="det_titulo_portada"></div>
          </div>

          <!-- Contenido (lado derecho) -->
          <div class="libro-contenido">
            <div class="contenido-info">
              <h4 id="det_titulo"></h4>
              
              <div id="det_sinopsis" class="sinopsis"></div>

              <div class="info-item">
                <div class="info-label">Autores:</div>
                <div class="info-valor" id="det_autores"></div>
              </div>

              <div class="info-item">
                <div class="info-label">Géneros:</div>
                <div class="info-valor" id="det_generos"></div>
              </div>

              <div class="info-item">
                <div class="info-label">Editorial:</div>
                <div class="info-valor" id="det_editorial"></div>
              </div>

              <div class="info-item">
                <div class="info-label">Año:</div>
                <div class="info-valor" id="det_anio"></div>
              </div>

              <div class="info-item">
                <div class="info-label">Ubicación:</div>
                <div class="info-valor" id="det_estante"></div>
              </div>

              <div class="info-item">
                <div class="info-label">Disponibles:</div>
                <div class="info-valor" id="det_disp"></div>
              </div>
            </div>
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