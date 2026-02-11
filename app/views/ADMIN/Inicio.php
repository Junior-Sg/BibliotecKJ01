<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bibliotec_KJ</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>public/css/ADM/Inicio.css">
</head>
<body>
        <?php include __DIR__ . '/../layouts/NavADM.php'; ?>

        <main class="main-content">
            <div class="container mt-4">
                <h1 class="fw-bold mb-2">Inicio</h1>
                <?php if (!empty($_SESSION['nombre'])): ?>
                    <p class="text-muted mb-4">Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <?php endif; ?>

                <div class="row">
                    <div class="col-lg-9">
                        <div class="row g-4 mb-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="tarjeta-dashboard kpi-navy">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-book fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0"><?= $totalLibros ?? 0 ?></h3>
                                            <small class="opacity-75">Libros Totales</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="tarjeta-dashboard kpi-teal">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-journal-arrow-up fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0"><?= $prestamosActivos ?? 0 ?></h3>
                                            <small class="opacity-75">Préstamos Activos</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="tarjeta-dashboard kpi-amber">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people fs-1 me-3"></i>
                                        <div>
                                            <h3 class="mb-0"><?= $totalUsuarios ?? 0 ?></h3>
                                            <small class="opacity-75">Usuarios Registrados</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow border-0">
                            <div class="card-body">
                                <h4 class="fw-bold mb-3">Últimos préstamos</h4>
                                <div class="table-responsive">
                                    <table class="table tabla-prestamos align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Usuario</th>
                                                <th>Libro</th>
                                                <th>Fecha Préstamo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($ultimosPrestamos)): ?>
                                                <?php foreach ($ultimosPrestamos as $prestamo): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($prestamo['nombre_usuario']) ?></td>
                                                        <td><?= htmlspecialchars($prestamo['titulo_libro']) ?></td>
                                                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($prestamo['fecha_prestamo']))) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center text-muted">No hay préstamos recientes.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <aside class="col-lg-3">
                        <div class="retrasados-card shadow-sm mb-4">
                            <div class="card-body text-center">
                                <h5 class="card-title">Préstamos Retrasados</h5>
                                <div class="retrasados-count"><?= $retrasadosCount ?? 0 ?></div>
                                <p class="text-muted small">Pendientes de devolución</p>

                                <div class="list-group list-group-flush mt-3">
                                    <?php if (!empty($retrasados)): ?>
                                        <?php foreach ($retrasados as $r): ?>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <strong><?= htmlspecialchars($r['titulo_libro']) ?></strong>
                                                <div class="small text-muted"><?= htmlspecialchars($r['nombre_usuario']) ?> • <?= htmlspecialchars(date('d/m/Y', strtotime($r['fecha_devolucion'] ?? $r['fecha_limite'] ?? ''))) ?></div>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-muted small">No hay préstamos retrasados.</div>
                                    <?php endif; ?>
                                </div>

                                <a href="<?= BASE_URL ?>index.php?controller=Reportes&action=retrasados" class="btn btn-outline-light btn-sm mt-3">Ver todos</a>
                            </div>
                        </div>

                        <!-- Tarjeta de Solicitudes de Aplazamiento -->
                        <div class="retrasados-card shadow-sm mb-4">
                            <div class="card-body text-center">
                                <h5 class="card-title" style="color: #241705;">📋 Solicitudes de Aplazamiento</h5>
                                <div class="retrasados-count"><?= count($solicitudesAplazamiento ?? []) ?></div>
                                <p class="text-muted small">Pendientes de revisión</p>

                                <div class="list-group list-group-flush mt-3">
                                    <?php if (!empty($solicitudesAplazamiento)): ?>
                                        <?php foreach (array_slice($solicitudesAplazamiento, 0, 5) as $solicitud): ?>
                                            <button type="button" class="btn-detalle-solicitud list-group-item list-group-item-action text-start"
                                                 data-solicitud="<?= htmlspecialchars(json_encode($solicitud), ENT_QUOTES, 'UTF-8') ?>"
                                                 style="background: rgba(212, 132, 28, 0.08); border-left: 4px solid #D4841C !important; cursor: pointer;">
                                                <strong style="color: #241705;"><?= htmlspecialchars($solicitud['titulo_libro']) ?></strong>
                                                <div class="small" style="color: #666;">
                                                    👤 <?= htmlspecialchars($solicitud['nombre_usuario']) ?> • 
                                                    ⏱️ +<?= $solicitud['dias_solicitados'] ?> días
                                                </div>
                                            </button>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-muted small">✓ No hay solicitudes pendientes.</div>
                                    <?php endif; ?>
                                </div>

                                <?php if (count($solicitudesAplazamiento ?? []) > 5): ?>
                                    <a href="<?= BASE_URL ?>index.php?controller=Reportes&action=aplazamientos" class="btn btn-sm mt-3" style="background: #D4841C; color: white; border: none;">Ver todas (<?= count($solicitudesAplazamiento) ?>)</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </aside>
                </div>

            </div> <!-- /.container -->
        </main>

<!-- Estilos para modal detalle -->
<style>
  #modalDetalle {
    z-index: 2000 !important;
  }

  #modalDetalle .modal-backdrop {
    z-index: 1999 !important;
  }
</style>

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

<!-- Modal Detalle de Solicitud de Aplazamiento -->
<div class="modal fade" id="modalSolicitudAplazamiento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: linear-gradient(180deg, #D4841C, #A9541A); color: white;">
        <h5 class="modal-title">📋 Solicitud de Aplazamiento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 p-3 rounded" style="background: #f8f6f3; border-left: 4px solid #D4841C;">
          <strong style="color: #241705;">Usuario:</strong>
          <p id="solicitudUsuario" style="margin: 5px 0 0 0; color: #333;"></p>
        </div>
        <div class="mb-3">
          <strong style="color: #241705;">Correo:</strong>
          <p id="solicitudCorreo" style="margin: 5px 0 0 0; color: #666;"></p>
        </div>
        <div class="mb-3 p-3 rounded" style="background: #f8f6f3;">
          <strong style="color: #241705;">📚 Libro:</strong>
          <p id="solicitudLibro" style="margin: 5px 0 0 0; color: #333; font-weight: 500;"></p>
        </div>
        <div class="mb-3">
          <strong style="color: #241705;">📅 Fecha devolución actual:</strong>
          <p id="solicitudFecha" style="margin: 5px 0 0 0; color: #666;"></p>
        </div>
        <div class="mb-3">
          <strong style="color: #241705;">⏱️ Días solicitados:</strong>
          <p id="solicitudDias" style="margin: 5px 0 0 0; color: #D4841C; font-weight: 700; font-size: 1.2rem;"></p>
        </div>
        <div class="mb-3">
          <strong style="color: #241705;">💬 Motivo:</strong>
          <p id="solicitudMotivo" style="font-style: italic; color: #666; margin: 5px 0 0 0;"></p>
        </div>
        <div class="mb-3">
          <strong style="color: #241705;">🕐 Fecha de solicitud:</strong>
          <p id="solicitudFechaSolicitud" style="margin: 5px 0 0 0; color: #999; font-size: 0.9rem;"></p>
        </div>
        <div id="solicitudError" class="alert alert-danger d-none"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger" id="btnRechazarAplazamiento">
          <i class="bi bi-x-circle"></i> Rechazar
        </button>
        <button type="button" class="btn btn-success" id="btnAprobarAplazamiento">
          <i class="bi bi-check-circle"></i> Aprobar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  let solicitudActualId = null;

  // Event listener para botones de detalle de solicitud
  // Se ejecuta cuando el DOM está listo
  function inicializarEventosSolicitudes() {
    console.log('Inicializando eventos de solicitudes...');
    const botones = document.querySelectorAll('.btn-detalle-solicitud');
    console.log('Botones encontrados:', botones.length);
    
    botones.forEach(btn => {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Botón clickeado');
        
        try {
          const solicitudJSON = this.dataset.solicitud;
          console.log('JSON recibido:', solicitudJSON);
          
          if (!solicitudJSON) {
            console.error('No hay datos de solicitud');
            return;
          }
          
          const solicitud = JSON.parse(solicitudJSON);
          console.log('Solicitud parseada:', solicitud);
          abrirDetalleSolicitud(solicitud);
        } catch(err) {
          console.error('Error al procesar solicitud:', err);
        }
      });
    });
  }

  // Si el documento ya está listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inicializarEventosSolicitudes);
  } else {
    inicializarEventosSolicitudes();
  }

  function abrirDetalleSolicitud(solicitud) {
    console.log('Abriendo detalle de solicitud:', solicitud);
    
    // Decodificar si es string (desde data-* attribute)
    if (typeof solicitud === 'string') {
      solicitud = JSON.parse(solicitud);
    }

    try {
      solicitudActualId = solicitud.id_solicitud;

      document.getElementById('solicitudUsuario').textContent = solicitud.nombre_usuario;
      document.getElementById('solicitudCorreo').textContent = solicitud.correo;
      document.getElementById('solicitudLibro').textContent = solicitud.titulo_libro;
      document.getElementById('solicitudFecha').textContent = solicitud.fecha_devolucion;
      document.getElementById('solicitudDias').textContent = '+' + solicitud.dias_solicitados + ' días';
      document.getElementById('solicitudMotivo').textContent = solicitud.motivo || '(Sin especificar)';
      document.getElementById('solicitudFechaSolicitud').textContent = new Date(solicitud.fecha_solicitud).toLocaleString('es-ES');
      document.getElementById('solicitudError').classList.add('d-none');

      const modalElement = document.getElementById('modalSolicitudAplazamiento');
      if (!modalElement) {
        console.error('Modal no encontrado');
        return;
      }
      
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
      console.log('Modal abierto exitosamente');
    } catch(err) {
      console.error('Error al abrir detalle:', err);
    }
  }

  document.getElementById('btnAprobarAplazamiento').addEventListener('click', function() {
    console.log('Botón aprobar clickeado, solicitud ID:', solicitudActualId);
    aprobarSolicitud(solicitudActualId);
  });

  document.getElementById('btnRechazarAplazamiento').addEventListener('click', function() {
    console.log('Botón rechazar clickeado, solicitud ID:', solicitudActualId);
    rechazarSolicitud(solicitudActualId);
  });

  function aprobarSolicitud(idSolicitud) {
    console.log('Aprobando solicitud:', idSolicitud);
    const btn = document.getElementById('btnAprobarAplazamiento');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('id_solicitud', idSolicitud);

    fetch('<?= BASE_URL ?>public/api/aprobar_aplazamiento.php', {
      method: 'POST',
      body: formData
    })
    .then(async r => {
      const contentType = r.headers.get('content-type');
      console.log('Response status:', r.status, 'Content-Type:', contentType);
      if (!contentType || !contentType.includes('application/json')) {
        const text = await r.text().catch(() => 'No body');
        throw new Error('Response is not JSON. Received: ' + contentType + ' - ' + text);
      }
      const data = await r.json();
      if (!r.ok) {
        const msg = data.message || ('HTTP ' + r.status);
        const detail = data.error ? (' - ' + data.error) : '';
        throw new Error(msg + detail);
      }
      return data;
    })
    .then(data => {
      console.log('Response data:', data);
      if (data.success) {
        alert(data.message || 'Solicitud aprobada exitosamente');
        location.reload();
      } else {
        document.getElementById('solicitudError').textContent = data.message || 'Error al aprobar';
        document.getElementById('solicitudError').classList.remove('d-none');
      }
    })
    .catch(err => {
      console.error('Error:', err);
      document.getElementById('solicitudError').textContent = '❌ Error: ' + err.message;
      document.getElementById('solicitudError').classList.remove('d-none');
    })
    .finally(() => {
      btn.disabled = false;
    });
  }

  function rechazarSolicitud(idSolicitud) {
    if (!confirm('¿Estás seguro de que deseas rechazar esta solicitud?')) return;

    console.log('Rechazando solicitud:', idSolicitud);
    const btn = document.getElementById('btnRechazarAplazamiento');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('id_solicitud', idSolicitud);

    fetch('<?= BASE_URL ?>public/api/rechazar_aplazamiento.php', {
      method: 'POST',
      body: formData
    })
    .then(async r => {
      const contentType = r.headers.get('content-type');
      console.log('Response status:', r.status, 'Content-Type:', contentType);
      if (!contentType || !contentType.includes('application/json')) {
        const text = await r.text().catch(() => 'No body');
        throw new Error('Response is not JSON. Received: ' + contentType + ' - ' + text);
      }
      const data = await r.json();
      if (!r.ok) {
        const msg = data.message || ('HTTP ' + r.status);
        const detail = data.error ? (' - ' + data.error) : '';
        throw new Error(msg + detail);
      }
      return data;
    })
    .then(data => {
      console.log('Response data:', data);
      if (data.success) {
        alert(data.message || 'Solicitud rechazada');
        location.reload();
      } else {
        document.getElementById('solicitudError').textContent = data.message || 'Error al rechazar';
        document.getElementById('solicitudError').classList.remove('d-none');
      }
    })
    .catch(err => {
      console.error('Error:', err);
      document.getElementById('solicitudError').textContent = '❌ Error: ' + err.message;
      document.getElementById('solicitudError').classList.remove('d-none');
    })
    .finally(() => {
      btn.disabled = false;
    });
  }
</script>

<!-- Bootstrap JS obligatorio para modales -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>