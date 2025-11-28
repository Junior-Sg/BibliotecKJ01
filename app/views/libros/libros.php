<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio_Catalogo</title>
    
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

  <style>
    /* Estilos locales para hero y cards (puedes moverlos a barranav.css) */
    .hero {
      background-image: url('/public/img/hero-books.jpg'); /* imagen de fondo */
      background-size: cover;
      background-position: center;
      padding: 80px 0;
      color: #2b2b2b;
      position: relative;
    }
    .hero::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,0.75); /* overlay claro */
    }
    .hero .hero-inner {
      position: relative;
      z-index: 2;
      max-width: 1000px;
      margin: 0 auto;
      text-align: center;
      padding: 20px;
    }
    .hero h2 { font-family: 'Merriweather', serif; font-size: 32px; margin-bottom: 8px; }
    .hero p.lead { margin-bottom: 18px; color: #4b4b4b; }

    /* barra búsqueda */
    .search-box { max-width: 640px; margin: 0 auto; display:flex; gap:8px; align-items:center; }
    .search-box .form-control { border-radius: 30px; padding: 12px 18px; }
    .search-box .btn { border-radius: 30px; padding: 10px 18px; background:#8b6f57; border:none; color:#fff; }

    /* catálogo */
    .catalog { padding: 40px 16px 80px; }
    .book-card {
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
      background: #fff;
      height: 100%;
    }
    .book-cover { width:100%; height: 420px; object-fit: cover; display:block; }
    .book-title { font-weight:600; margin-top:12px; text-align:center; color:#333; }
    .no-results { text-align:center; padding:40px 0; color:#666; }
  </style>

</head>
<body>

<?php include __DIR__ . '../../layouts/navbar.php'; ?>

<div class="container py-4">

  <!-- Buscador -->
  <form action="index.php" method="get" class="d-flex justify-content-center mb-3" role="search">
    <input type="hidden" name="controller" value="Libro">
    <input type="hidden" name="action" value="buscar">
    <input type="text" name="texto" class="form-control" style="max-width:600px" placeholder="Buscar por título o autor..." value="<?= htmlspecialchars($_GET['texto'] ?? '') ?>">
    <button type="submit" class="btn btn-primary ms-2">Buscar</button>
  </form>

  <!-- catalogo -->

  <h1 class="text-center mb-3" style="font-family:'Merriweather', serif;">Catálogo de Libros</h1>

  <!-- Filtros por género -->
  <div class="text-center mb-4">
    <a class="btn btn-outline-secondary btn-sm me-1" href="index.php?controller=Libro&action=libro">Todos</a>
    <?php if (!empty($generos) && $generos->num_rows > 0): ?>
      <?php while ($g = $generos->fetch_assoc()): ?>
        <a class="btn btn-outline-secondary btn-sm me-1" href="index.php?controller=Libro&action=libro&genero=<?= urlencode($g['nombre']) ?>"><?= htmlspecialchars($g['nombre']) ?></a>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>

  <!-- Grid catálogo -->
  <?php if (!$libros || $libros->num_rows === 0): ?>
    <p class="text-center text-muted">No se encontraron libros. Prueba otra búsqueda o vuelve más tarde.</p>
  <?php else: ?>
    <div class="row g-4">
      <?php while ($book = $libros->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100 shadow-sm">
            <?php $cover = !empty($book['Imagen']) ? BASE_URL . 'public/img/Libros/' . $book['Imagen'] : BASE_URL . 'public/img/ibros'; ?>
            <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($book['titulo']) ?>" class="card-img-top" style="height:260px;object-fit:cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title text-center"><?= htmlspecialchars($book['titulo']) ?></h5>
              <div class="text-muted text-center small mb-2"><?= htmlspecialchars($book['editorial']) ?></div>
              <div class="text-center small mb-1">Año: <?= htmlspecialchars($book['año_publicacion']) ?> · Estante: <?= htmlspecialchars($book['Estante']) ?></div>
              <button class="btn btn-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$book['id_libro'] ?>">
                Ver detalle
              </button>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

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

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalDetalle');
  let libroActual = null;

  modal.addEventListener('show.bs.modal', async (event) => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    try {
      const resp = await fetch(`index.php?controller=Libro&action=detalleJson&id=${id}`);
      const json = await resp.json();
      if (!json.ok) { throw new Error(json.error || 'Error al obtener detalle'); }
      const d = json.data;
      libroActual = d;

      const imgPath = d.Imagen ? `<?= BASE_URL ?>public/img/Libros/${d.Imagen}` : '<?= BASE_URL ?>public/img/Libros';
      document.getElementById('det_imagen').src = imgPath;
      document.getElementById('det_titulo').textContent = d.titulo;
      document.getElementById('det_sinopsis').textContent = d.sinopsis || 'Sin sinopsis disponible.';
      document.getElementById('det_autores').textContent = (d.autores || []).join(', ') || 'Desconocido';
      document.getElementById('det_generos').textContent = (d.generos || []).join(', ') || 'Sin género';
      document.getElementById('det_editorial').textContent = d.editorial || '';
      document.getElementById('det_anio').textContent = d.año_publicacion || '';
      document.getElementById('det_estante').textContent = d.Estante || '';
      document.getElementById('det_disp').textContent = d.disponibilidad ?? 0;
    } catch (e) {
      console.error(e);
      alert('No se pudo cargar el detalle del libro.');
    }
  });

  document.getElementById('btnReservar').addEventListener('click', () => {
    if (!libroActual) return;
    // Si hay sesión, enviamos a reserva; si no, al login.
    const logged = <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>;
    if (logged) {
      window.location.href = `index.php?controller=Reserva&action=nueva&id_libro=${libroActual.id_libro}`;
    } else {
      window.location.href = `index.php?controller=Usuario&action=login`;
    }
  });
});
</script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

  <?php include __DIR__ . '../../layouts/footer.php'; ?>
</body>
</html>