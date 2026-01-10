<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préstamos Retrasados - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/ADM/GestionGlobal.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/ADM/Reportes.css">
</head>
<body>
    <?php include __DIR__ . '/../layouts/NavADM.php'; ?>

    <main class="main-content">
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold mb-2">Préstamos Retrasados</h1>
                    <p class="text-muted mb-0">Total: <strong><?= $retrasadosCount ?? 0 ?></strong> préstamo(s) retrasado(s)</p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>index.php?controller=Reportes&action=exportar_retrasados" class="btn btn-primary">
                        <i class="bi bi-file-earmark-excel"></i> Exportar a Excel
                    </a>
                </div>
            </div>

            <div class="card shadow border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Préstamo</th>
                                    <th>Libro</th>
                                    <th>Usuario</th>
                                    <th>Documento</th>
                                    <th>Fecha Préstamo</th>
                                    <th>Fecha Límite</th>
                                    <th>Días Retrasado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($retrasados)): ?>
                                    <?php foreach ($retrasados as $r): ?>
                                        <?php 
                                            $fechaLimite = new DateTime($r['fecha_devolucion']);
                                            $hoy = new DateTime();
                                            $diasRetrasado = $hoy->diff($fechaLimite)->days;
                                        ?>
                                        <tr>
                                            <td><strong><?= htmlspecialchars($r['id_prestamo']) ?></strong></td>
                                            <td><?= htmlspecialchars($r['titulo_libro']) ?></td>
                                            <td><?= htmlspecialchars($r['nombre_usuario']) ?></td>
                                            <td><?= htmlspecialchars($r['numero_documento'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['fecha_prestamo']))) ?></td>
                                            <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['fecha_devolucion']))) ?></td>
                                            <td>
                                                <span class="badge bg-danger"><?= $diasRetrasado ?> días</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Retrasado</span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            <i class="bi bi-check-circle"></i> No hay préstamos retrasados.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="<?php echo BASE_URL; ?>index.php?controller=Inicio&action=index" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
