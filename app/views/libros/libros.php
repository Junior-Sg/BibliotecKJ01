<?php
// app/views/libros/libros.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/libros/index.css">

<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

</head>

<body>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<?php include __DIR__ . '/busqueda.php'; ?>

<div class="catalogo-wrapper">
    <h1 class="text-center mb-4">Catálogo de Libros</h1>

<!-- FILTROS (botones dropdown) -->
<div class="container mb-4">
    <!-- Contenedor para mensajes de alerta de filtros -->
    <div id="filtro-mensaje-container" class="d-flex justify-content-center">
        <div id="filtro-mensaje" class="alert alert-warning alert-dismissible fade show d-none" role="alert" style="max-width: 500px;">
            <span id="filtro-mensaje-texto"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="d-flex justify-content-center gap-3 flex-wrap">

        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">Géneros</button>
            <div class="dropdown-menu p-3" style="max-height:300px; overflow:auto;">
                <small class="text-muted">Selecciona hasta 5</small><br>
                <?php if (empty($generosList)): ?>
                    <small class="text-muted">No hay géneros.</small>
                <?php else: foreach ($generosList as $g):
                    $isChecked = in_array($g['id_genero'], $activeGeneros);
                    ?>
                    <label class="d-block">
                        <input type="checkbox" class="filtro-genero" value="<?= (int)$g['id_genero'] ?>" <?= $isChecked ? 'checked' : '' ?>> <?= htmlspecialchars($g['nombre']) ?>
                    </label>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle" data-bs-toggle="dropdown">Autores</button>
            <div class="dropdown-menu p-3" style="max-height:300px; overflow:auto;">
                <small class="text-muted">Selecciona hasta 5</small><br>
                <?php if (empty($autoresList)): ?>
                    <small class="text-muted">No hay autores.</small>
                <?php else: foreach ($autoresList as $a):
                    $isChecked = in_array($a['id_autor'], $activeAutores);
                    ?>
                    <label class="d-block">
                        <input type="checkbox" class="filtro-autor" value="<?= (int)$a['id_autor'] ?>" <?= $isChecked ? 'checked' : '' ?>> <?= htmlspecialchars($a['nombre']) ?>
                    </label>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <button id="btnAplicarFiltros" class="btn btn-dark">Aplicar filtros</button>
    </div>

    <!-- Filtros Activos -->
    <?php if (!empty($activeGeneros) || !empty($activeAutores)): ?>
    <div class="d-flex justify-content-center align-items-center gap-2 mt-3 flex-wrap">
        <span class="fw-bold small">Filtros activos:</span>
        <?php foreach ($activeGeneros as $id): ?>
            <?php if (isset($generosMap[$id])): ?>
                <span class="badge bg-secondary fw-normal"><?= htmlspecialchars($generosMap[$id]) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php foreach ($activeAutores as $id): ?>
            <?php if (isset($autoresMap[$id])): ?>
                <span class="badge bg-secondary fw-normal"><?= htmlspecialchars($autoresMap[$id]) ?></span>
            <?php endif; ?>
        <?php endforeach; ?>

        <a href="index.php?controller=Libro&action=index" class="btn btn-sm btn-outline-danger">
            Limpiar filtros
        </a>
    </div>
    <?php endif; ?>
</div>

<div class="container mt-4">
    <?php if (!empty($results)):?>
        <?php if ($singleGenreId > 0 && isset($generosMap[$singleGenreId])): ?>
            <h3 class="mt-4 mb-3">Género: <?= htmlspecialchars($generosMap[$singleGenreId]) ?></h3>
        <?php elseif (!empty($_GET['q'])): ?>
            <h3 class="mt-4 mb-3">Resultados para "<?= htmlspecialchars($_GET['q']) ?>"</h3>
        <?php else: ?>
            <h3 class="mt-4 mb-3">Resultados de la Búsqueda</h3>
        <?php endif; ?>
        <div class="catalogo-grid">
            <?php foreach ($results as $book): ?>
                <div class="book-card">
                    <?php $img = !empty($book['Imagen']) ? rtrim(BASE_URL, '/') . '/public/img/Libros/' . $book['Imagen'] : rtrim(BASE_URL, '/') . '/public/img/Libros/default.jpg'; ?>
                    <img src="<?= htmlspecialchars($img) ?>" class="book-cover" alt="<?= htmlspecialchars($book['titulo']) ?>">
                    <div class="book-info">
                        <h5><?= htmlspecialchars($book['titulo']) ?></h5>
                        <button class="btn btn-light btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$book['id_libro'] ?>">Ver detalle</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($singleGenreId > 0): ?>
        <div class="text-center mt-4">
            <button onclick="history.back()" class="btn btn-dark">‹ Volver</button>
        </div>
        <?php endif; ?>
    <?php elseif ($is_filtered): // Filtro aplicado pero sin resultados ?>
    <div class="text-center py-5">
        <p>No se encontraron libros con los criterios seleccionados.</p>
    </div>
    <?php elseif (!empty($topByGenero)): // Vista por defecto (showcase) ?>
        <?php foreach ($topByGenero as $idGenero => $gdata): ?>
            <?php
                $gNombre = $gdata['nombre'] ?? ('Género ' . (int)$idGenero);
                $librosArray = $gdata['libros'] ?? [];
            ?>
            <h3 class="mt-4"><?= htmlspecialchars($gNombre) ?></h3>
            <div class="catalogo-grid">
                <?php if (!empty($librosArray)): ?>
                    <?php foreach ($librosArray as $book): ?>
                        <div class="book-card">
                            <?php $img = !empty($book['Imagen']) ? rtrim(BASE_URL, '/') . '/public/img/Libros/' . $book['Imagen'] : rtrim(BASE_URL, '/') . '/public/img/Libros/default.jpg'; ?>
                            <img src="<?= htmlspecialchars($img) ?>" class="book-cover" alt="<?= htmlspecialchars($book['titulo']) ?>">
                            <div class="book-info">
                                <h5><?= htmlspecialchars($book['titulo']) ?></h5>
                                <button class="btn btn-light btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$book['id_libro'] ?>">Ver detalle</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12"><p class="text-muted">No hay libros en esta sección.</p></div>
                <?php endif; ?>
            </div>
            <div><a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=catalogoGenero&id=<?= (int)$idGenero ?>" class="btn btn-outline-dark mt-2">Ver más de este género</a></div>
        <?php endforeach; ?>
    <?php else: // No hay nada que mostrar ?>
    <div class="text-center py-5">
        <p>No hay libros para mostrar en el catálogo.</p>
    </div>
    <?php endif; ?>
</div>

</div>

<?php if (file_exists(__DIR__ . '/detalle.php')) include __DIR__ . '/detalle.php'; ?>
<?php if (file_exists(__DIR__ . '/../reservas/reserva.php')) include __DIR__ . '/../reservas/reserva.php'; ?>
<?php if (file_exists(__DIR__ . '/../layouts/footer.php')) include __DIR__ . '/../layouts/footer.php'; ?>


<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/detalle.js"></script>
<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/reserva.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
window.BASE_URL = "<?= rtrim(BASE_URL, '/') ?>";
window.USER_LOGGED = <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>;

    (function(){
    const mensajeContainer = document.getElementById("filtro-mensaje");
    const mensajeTexto = document.getElementById("filtro-mensaje-texto");
    const btn = document.getElementById("btnAplicarFiltros");
    if (!btn) return;

    function mostrarMensaje(texto) {
        if (!mensajeContainer || !mensajeTexto) return;
        mensajeTexto.textContent = texto;
        mensajeContainer.classList.remove('d-none');
    }

    btn.addEventListener("click", () => {
      const generos = [...document.querySelectorAll(".filtro-genero:checked")].map(g => g.value);
      const autores = [...document.querySelectorAll(".filtro-autor:checked")].map(a => a.value);

      if (generos.length > 5 || autores.length > 5) {
        mostrarMensaje("Solo puedes seleccionar hasta 5 opciones por categoría.");
        return;
      }

      const url = `index.php?controller=Libro&action=index&generos=${generos.join(',')}&autores=${autores.join(',')}`;
      window.location.href = url;
    });

    // Limitar checkboxes a 5 por grupo
    function limitarCheckbox(clase) {
        const items = document.querySelectorAll(clase);
        items.forEach(chk => {
        chk.addEventListener('change', () => {
            const seleccionados = [...items].filter(c => c.checked).length;
            if (seleccionados > 5) {
            chk.checked = false;
            mostrarMensaje("No puedes seleccionar más de 5 filtros por categoría.");
        }
        });
      });
    }
    limitarCheckbox(".filtro-genero");
    limitarCheckbox(".filtro-autor");
})();

    document.addEventListener('DOMContentLoaded', function() {
    // Leer el ID de la URL
    const urlParams = new URLSearchParams(window.location.search);
    // CORRECCIÓN: Priorizar 'openModal' si existe (para redirección desde inicio)
    let idLibro = urlParams.get('openModal');
    // Si no hay openModal, usar 'id' solo si NO es la acción catalogoGenero (donde id es el género)
    if (!idLibro && urlParams.get('action') !== 'catalogoGenero') {
        idLibro = urlParams.get('id');
    }

    if (idLibro) {
        // Buscar el botón o tarjeta de ese libro en el catálogo
        // Asumiendo que tus libros en el catálogo tienen un atributo data-id
        // CORRECCIÓN: Usamos el selector exacto que tienen tus botones
        const libroElemento = document.querySelector(`button[data-bs-target="#modalDetalle"][data-id="${idLibro}"]`);
        
        if (libroElemento) {
            // Simular click para abrir el modal
            libroElemento.click();
        } else {
            // Si el libro no está visible (filtrado o sin stock), intentamos forzar la apertura
            console.log("El libro no está en la lista visual. Intentando abrir modal manualmente...");
            
            // Creamos un botón temporal invisible para disparar el evento que detalle.js escucha
            // Esto funcionará si detalle.js usa delegación de eventos (document.addEventListener)
            // Si no, necesitarás una función específica para cargar datos.
            const tempBtn = document.createElement('button');
            tempBtn.setAttribute('data-bs-toggle', 'modal');
            tempBtn.setAttribute('data-bs-target', '#modalDetalle');
            tempBtn.className = 'btn btn-light btn-sm mt-2'; // Agregamos las clases que usa detalle.js
            tempBtn.setAttribute('data-id', idLibro);
            tempBtn.style.display = 'none';
            document.body.appendChild(tempBtn);
            
            // Disparamos el click
            tempBtn.click();
            
            // Limpiamos
            setTimeout(() => tempBtn.remove(), 1000);
        }
    }
});

</script>
</body>
</html>