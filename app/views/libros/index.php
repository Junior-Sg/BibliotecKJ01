<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BibliotecKJ</title>

    <!-- barranav.css -->
    <link rel="stylesheet" href="../../../public/css/style_Layaout/barranav.css">

    <!-- index.css -->
    <link rel="stylesheet" href="../../../public/css/libros/index.css">

    <!-- css -->
     <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php include '../layouts/nav.php'; ?>
    
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

    <?php if (empty($books)): ?>
      <div class="no-results">No se encontraron libros. Prueba otra búsqueda o vuelve más tarde.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($books as $book): ?>
          <div class="col-12 col-sm-6 col-md-4">
            <div class="book-card p-3 h-100">
              <a href="/book.php?id=<?= (int)$book['id'] ?>">
                <?php
                  $cover = $book['cover_path'] ?? '/public/img/covers/default.jpg';
                ?>
                <img src="<?= htmlspecialchars($cover) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
              </a>
              <div class="p-3">
                <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                <div class="text-muted text-center"><?= htmlspecialchars($book['author']) ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

<?php include '../layouts/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>