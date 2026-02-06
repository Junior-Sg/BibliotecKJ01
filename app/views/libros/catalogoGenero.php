<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Género - BibliotecKJ</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/libros/index.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    
</head>
<body>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<?php include __DIR__ . '/busqueda.php'; ?>

<div class="catalogo-wrapper">
    <h1 class="text-center mb-4">Género: <?= htmlspecialchars($generoNombre) ?></h1> 
    <div id="catalogo-grid" class="catalogo-grid">
        <?php if ($librosDelGenero && count($librosDelGenero) > 0): ?>
            <?php foreach($librosDelGenero as $libro): ?>
                <div class="book-card" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$libro['id_libro'] ?>">
                    <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($libro['Imagen']) ?>" alt="Portada del libro <?= htmlspecialchars($libro['titulo']) ?>" class="book-cover">
                    <div class="book-info">
                        <h5><?= htmlspecialchars($libro['titulo']) ?></h5>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No hay libros disponibles en este género.</p>
        <?php endif; ?>
    </div>
    <div class="text-center mt-4">
        <button onclick="history.back()" class="btn btn-dark">‹ Volver</button>
    </div>
</div>

<?php if (file_exists(__DIR__ . '/detalle.php')) include __DIR__ . '/detalle.php'; ?>
<?php if (file_exists(__DIR__ . '/../reservas/reserva.php')) include __DIR__ . '/../reservas/reserva.php'; ?>
<?php if (file_exists(__DIR__ . '/../layouts/footer.php')) include __DIR__ . '/../layouts/footer.php'; ?>

<script>
  window.BASE_URL = "<?= rtrim(BASE_URL, '/') ?>";
  window.USER_LOGGED = <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>;

  // Detectar si hay un libro para abrir automáticamente
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const openModalId = urlParams.get('openModal');
    
    if (openModalId) {
      // Buscar el elemento del libro con el ID especificado
      const bookElement = document.querySelector(`[data-id="${openModalId}"]`);
      
      if (bookElement) {
        // Trigger the click to open the modal
        bookElement.click();
        
        // Limpiar la URL para evitar re-abrir en refresh
        const cleanUrl = window.location.pathname + '?' + new URLSearchParams({
          controller: 'Libro',
          action: 'catalogoGenero',
          id: urlParams.get('id')
        }).toString();
        window.history.replaceState({}, document.title, cleanUrl);
      }
    }
  });
</script>

<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/detalle.js"></script>
<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/reserva.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>