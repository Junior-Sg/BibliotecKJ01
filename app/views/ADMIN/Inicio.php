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

        <div class="col-md-4">
            <div class="tarjeta-dashboard" style="background:#287bff;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-book fs-1 me-3"></i>
                    <div>
                        <h3 class="mb-0"><?= $totalLibros ?></h3>
                        <small class="opacity-75">Libros</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tarjeta-dashboard" style="background:#1ebc73;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-arrow-repeat fs-1 me-3"></i>
                    <div>
                        <!-- datos quemados despues cambiar -->
         <h3>24</h3>
                        <!-- <h3 class="mb-0"><?= $prestamosActivos ?></h3> -->
                        <small class="opacity-75">Préstamos activos</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="tarjeta-dashboard" style="background:#f79c1d;">
                <div class="d-flex align-items-center">
                    <i class="bi bi-people fs-1 me-3"></i>
                    <div>
                        <h3 class="mb-0"><?= $totalUsuarios ?></h3>
                        <small class="opacity-75">Usuarios registrados</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Últimos préstamos</h4>

            <table class="table tabla-prestamos">
                <thead class="table-light">
                    <tr>
                        <th>Usuario</th>
                        <th>Libro</th>
                        </div> <!-- /.container -->
                    </main>

                </body>
                </html>
                </tbody>
            </table>

        </div>
    </div>

</body>
            </div>
        </main>

</body>
</html>