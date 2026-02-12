<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Perfil - BibliotecKJ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Coloca el CSS propio después de Bootstrap para que no lo sobrescriba -->
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/public/css/perfil/perfil.css">

    <script>
        // Pasar la base URL a los archivos JS externos
        window.AppConfig = {
            baseUrl: "<?= rtrim(BASE_URL, '/') ?>"
        };
    </script>
</head>
<body>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<main class="container mt-4 mb-3">
    
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['flash_ok'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <ul class="nav nav-tabs custom-tabs" id="perfilTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#datos">
                <i class="bi bi-person-badge"></i> Mis Datos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#favoritos">
                <i class="bi bi-heart-fill"></i> Favoritos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#historial">
                <i class="bi bi-clock-history"></i> Historial
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link position-relative" data-bs-toggle="tab" href="#notificaciones">
                <i class="bi bi-bell"></i> Notificaciones
                <span id="badge-notif" class="badge rounded-pill bg-danger d-none">!</span>
            </a>
        </li>
    </ul>

    <div class="profile-content p-4 shadow-sm rounded-bottom bg-white">
        <div class="tab-content">
            
            <div class="tab-pane fade show active" id="datos" role="tabpanel">
                <?php include __DIR__ . '/datos.php'; ?>
            </div>

            <div class="tab-pane fade" id="favoritos" role="tabpanel">
                <?php include __DIR__ . '/Favoritos.php'; ?>
            </div>

            <div class="tab-pane fade" id="historial" role="tabpanel">
                <div class="row g-3">
                    <?php include __DIR__ . '/Historial_reservas.php'; ?>
                    <hr>
                    <?php include __DIR__ . '/Historial_Libros.php'; ?>
                </div>
            </div>

            <div class="tab-pane fade" id="notificaciones" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <?php include __DIR__ . '/Notificaciones.php'; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/Perfil.js"></script>

<script>
    
  // Evitar que el navegador muestre contenido obsoleto tras logout
  window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  });
</script>
</body>
</html>
