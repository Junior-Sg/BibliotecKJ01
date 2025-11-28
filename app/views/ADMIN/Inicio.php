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

    <style>
    .tarjeta-dashboard {
        border-radius: 15px;
        color: white;
        padding: 20px;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .tarjeta-dashboard:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,.15);
    }

    .tabla-prestamos tbody tr:hover {
        background: #f7f7f7 !important;
        cursor: pointer;
    }
</style>
</head>
<body>
        <?php include __DIR__ . '/../layouts/NavADM.php'; ?>

        <main class="main-content">
            <div class="container mt-4">
                <h1 class="fw-bold mb-2">Inicio</h1>
                <?php if (!empty($_SESSION['nombre'])): ?>
                    <p class="text-muted mb-4">Bienvenido, <strong><?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') ?></strong></p>
                <?php endif; ?>
                
    <div class="row g-4 mb-4">
    <div class="col-lg-4 col-md-6">
        <div class="tarjeta-dashboard" style="background: linear-gradient(135deg, #287bff, #1a6ce8);">
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
        <div class="tarjeta-dashboard" style="background: linear-gradient(135deg, #20c997, #18a278);">
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
        <div class="tarjeta-dashboard" style="background: linear-gradient(135deg, #fd7e14, #d96d11);">
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

</div> <!-- /.container -->
</main>

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

</body>
</html>