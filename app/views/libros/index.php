<?php $libros = $libros ?? null; ?>

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

  <?php include __DIR__ . '/../layouts/navbar.php'; ?>

  <?php $q = $_GET['q'] ?? ''; ?>

  <!-- HERO con búsqueda -->
  <section class="hero">
    <div class="hero-inner">
      <h2>Bienvenido a BibliotecKJ</h2>
      <p class="lead">El sistema digital que te permite explorar, registrar y solicitar libros de forma fácil, rápida y moderna.</p>

      <div style="margin-bottom:14px">
        <a href="/catalogo" class="btn" style="background:#8b6f57;color:#fff;border-radius:30px;padding:10px 18px;">Explorar Libros</a>
      </div>

      <!-- BUSCADOR -->
      <form method="get" action="/" class="search-box" role="search" aria-label="Buscar libros">
        <input type="text" name="q" class="form-control" placeholder="Buscar libro por título, autor o género..." value="<?= htmlspecialchars($q) ?>">
        <button class="btn" type="submit">Buscar</button>
      </form>
    </div>
  </section>

<!-- Catálogo -->
<section class="catalog container">
  <h3 class="text-center mb-4" style="font-family:'Merriweather', serif;">Catálogo de Libros</h3>

  <?php if (!$libros || $libros->num_rows === 0): ?>
    <div class="no-results text-center">No se encontraron libros. Prueba otra búsqueda o vuelve más tarde.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php while ($book = $libros->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-md-4">
          <div class="book-card p-3 h-100">
            <a href="index.php?controller=Libro&action=detalle&id=<?= (int)$book['id_libro'] ?>">
              <?php
                $cover = !empty($book['Imagen']) ? 'public/img/libros/' . $book['Imagen'] : 'public/img/libros/default.jpg';
              ?>
              <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($book['titulo']) ?>" class="book-cover w-100" style="height:280px; object-fit:cover;">
            </a>
            <div class="p-3">
              <div class="book-title text-center fw-bold"><?= htmlspecialchars($book['titulo']) ?></div>
              <div class="text-muted text-center"><?= htmlspecialchars($book['editorial']) ?></div>
              <div class="text-center">Año: <?= htmlspecialchars($book['año_publicacion']) ?></div>
              <div class="text-center">Estante: <?= htmlspecialchars($book['Estante']) ?></div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</section>


  <?php include __DIR__ . '/../layouts/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>