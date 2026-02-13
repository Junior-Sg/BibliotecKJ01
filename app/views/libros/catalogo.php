<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería - BibliotecKJ</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/libros/index.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    
</head>
<body>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<?php include __DIR__ . '/busqueda.php'; ?>

<div class="catalogo-wrapper">
    <h1 class="text-center mb-4">Libreria</h1>

    <div id="catalogo-grid" class="catalogo-grid">
        <?php if ($libros && $libros->num_rows > 0): ?>
            <?php while($libro = $libros->fetch_assoc()): ?>
                <div class="book-card" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$libro['id_libro'] ?>">
                    <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($libro['Imagen']) ?>" alt="Portada del libro <?= htmlspecialchars($libro['titulo']) ?>" class="book-cover">
                    <div class="book-info">
                        <h5><?= htmlspecialchars($libro['titulo']) ?></h5>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No hay libros disponibles en este momento.</p>
        <?php endif; ?>
    </div>
    <button id="btnVerMas" data-offset="20">Ver más</button>
</div>

<?php if (file_exists(__DIR__ . '/detalle.php')) include __DIR__ . '/detalle.php'; ?>
<?php if (file_exists(__DIR__ . '/../reservas/reserva.php')) include __DIR__ . '/../reservas/reserva.php'; ?>
<?php if (file_exists(__DIR__ . '/../layouts/footer.php')) include __DIR__ . '/../layouts/footer.php'; ?>

<script>
  window.BASE_URL = "<?= rtrim(BASE_URL, '/') ?>";
  window.USER_LOGGED = <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>;

  // Mostrar toast si viene de una reserva exitosa
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('reserva_ok')) {
      // Función para mostrar toast de reserva exitosa
      function mostrarToastReservaExitosa(tituloLibro) {
        let toastEl = document.getElementById('reservaExitosaToast');
        if (!toastEl) {
          const toastHTML = `
          <div class="position-fixed p-3" style="z-index: 2200; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div id="reservaExitosaToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #2C5282 0%, #1A365D 100%); box-shadow: 0 12px 32px rgba(44, 82, 130, 0.35); min-width: 380px;">
              <div class="p-4">
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 0;">
                  <div class="icon-wrapper" style="width: 50px; height: 50px; background-color: rgba(72, 187, 120, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check-lg" style="font-size: 1.5rem; color: #48BB78;"></i>
                  </div>
                  <div style="flex: 1;">
                    <h6 class="text-white mb-1" style="font-size: 1.1rem;">¡Reserva Exitosa!</h6>
                    <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Tu reserva se ha realizado correctamente. Te enviaremos una notificación cuando esté lista para recoger.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>`;
          document.body.insertAdjacentHTML('beforeend', toastHTML);
          toastEl = document.getElementById('reservaExitosaToast');
        }
        const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        toast.show();
        // Limpiar URL
        window.history.replaceState({}, document.title, window.location.pathname);
      }
      mostrarToastReservaExitosa();
    }

    const btnVerMas = document.getElementById('btnVerMas');
    const catalogoGrid = document.getElementById('catalogo-grid');

    btnVerMas.addEventListener('click', function() {
        let offset = parseInt(this.getAttribute('data-offset'));
        
        // Obtener filtros activos de la URL actual
        const urlParams = new URLSearchParams(window.location.search);
        const generos = urlParams.get('generos') || '';
        const autores = urlParams.get('autores') || '';
        const q = urlParams.get('q') || '';
        
        // Construir URL con filtros
        let url = `index.php?controller=Libro&action=cargarMas&offset=${offset}`;
        if (generos) url += `&generos=${generos}`;
        if (autores) url += `&autores=${autores}`;
        if (q) url += `&q=${encodeURIComponent(q)}`;
        
        fetch(url)
            .then(response => response.text())
            .then(html => {
                if (html.trim() !== "") {
                    catalogoGrid.insertAdjacentHTML('beforeend', html);
                    this.setAttribute('data-offset', offset + 20);
                } else {
                    btnVerMas.style.display = 'none';
                }
            })
            .catch(error => console.error('Error al cargar más libros:', error));
    });
});
</script>

<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/detalle.js"></script>
<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/reserva.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
