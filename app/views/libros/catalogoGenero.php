<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Género: <?= htmlspecialchars($generoNombre) ?> - BibliotecKJ</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/libros/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    
    <script>
        // Configuración Global centralizada
        window.AppConfig = {
            baseUrl: "<?= rtrim(BASE_URL, '/') ?>",
            isLogged: <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>,
            openModalId: "<?= $_GET['openModal'] ?? '' ?>"
        };
    </script>
</head>
<body>

<?php 
    include __DIR__ . '/../layouts/navbar.php'; 
    include __DIR__ . '/busqueda.php'; 
?>

<div class="catalogo-wrapper container">
    <h1 class="text-center mb-4">Género: <span class="text-primary"><?= htmlspecialchars($generoNombre) ?></span></h1> 
    
    <div id="catalogo-grid" class="catalogo-grid">
        <?php if (!empty($librosDelGenero)): ?>
            <?php foreach($librosDelGenero as $libro): ?>
                <div class="book-card" 
                     role="button" 
                     tabindex="0" 
                     data-bs-toggle="modal" 
                     data-bs-target="#modalDetalle" 
                     data-id="<?= (int)$libro['id_libro'] ?>"
                     aria-label="Ver detalles de <?= htmlspecialchars($libro['titulo']) ?>">
                    
                    <div class="book-cover-wrapper">
                        <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($libro['Imagen']) ?>" 
                             alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>" 
                             class="book-cover"
                             loading="lazy">
                    </div>
                    
                    <div class="book-info">
                        <h5><?= htmlspecialchars($libro['titulo']) ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">No hay libros disponibles en este género actualmente.</div>
        <?php endif; ?>
    </div>

    <div class="text-center mt-5">
        <button onclick="history.back()" class="btn btn-outline-dark">
            <i class="bi bi-arrow-left"></i> Volver atrás
        </button>
    </div>
</div>

<?php 
    // Modales y Footer
    if (file_exists(__DIR__ . '/detalle.php')) include __DIR__ . '/detalle.php'; 
    if (file_exists(__DIR__ . '/../reservas/reserva.php')) include __DIR__ . '/../reservas/reserva.php'; 
    if (file_exists(__DIR__ . '/../layouts/footer.php')) include __DIR__ . '/../layouts/footer.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>public/js/detalle.js"></script>
<script src="<?= BASE_URL ?>public/js/reserva.js"></script>

<script>
/**
 * Lógica para manejar la apertura automática de modales vía URL
 */
document.addEventListener('DOMContentLoaded', () => {
    const { openModalId } = window.AppConfig;

    if (openModalId) {
        // En lugar de un timeout fijo, usamos una función que verifica si el elemento existe
        const intentarAbrirModal = (intentos = 0) => {
            const bookElement = document.querySelector(`.book-card[data-id="${openModalId}"]`);
            
            if (bookElement) {
                // Disparamos el modal de Bootstrap correctamente
                const modalEl = document.getElementById('modalDetalle');
                if (modalEl) {
                    bookElement.click();
                    limpiarUrl();
                }
            } else if (intentos < 10) { 
                // Si no existe (ej. por carga lenta), reintentamos un par de veces
                setTimeout(() => intentarAbrirModal(intentos + 1), 100);
            }
        };

        const limpiarUrl = () => {
            const url = new URL(window.location);
            url.searchParams.delete('openModal');
            window.history.replaceState({}, document.title, url.pathname + url.search);
        };

        intentarAbrirModal();
    }
});
</script>
</body>
</html>