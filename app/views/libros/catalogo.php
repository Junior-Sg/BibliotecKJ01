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

document.addEventListener('DOMContentLoaded', function() {
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
