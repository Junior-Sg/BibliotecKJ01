<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería - BibliotecKJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
      :root {
        --cafe-oscuro: #3C2B0D;
        --cafe-medio:  #795C34;
        --cafe-claro:  #9A7B4F;
        --caramel:     #65350F;
        --bg-suave:    #f4ede3;
        
      }
      body { background: var(--bg-suave);
        --bg-1: #f6f0e3;
        --bg-2: #eddbc3;
       
       }

      .catalogo-wrapper {
        max-width: 1200px;
        margin: auto;
        padding: 25px 15px 60px;
        
      }
      h1 {
        font-family:'Merriweather', serif;
        color: var(--caramel);
        font-weight: 700;
        text-align: center;
        margin-bottom: 2rem;
      }
      .catalogo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill,minmax(220px,1fr));
        gap: 22px;
      }
      .book-card {
        background: var(--cafe-claro);
        color: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 12px rgba(0,0,0,.2);
        transition: transform .2s;
          cursor: pointer;
          text-decoration: none;
      }
      .book-card:hover {
        transform: scale(1.03);
      }
      .book-cover {
        width: 100%;
        height: 280px;
        object-fit: cover;
      }
      .book-info {
        padding: 12px 14px;
        text-align: center;
      }
      .book-info h5 {
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #fff;
      }
      #btnVerMas {
        display: block;
        margin: 35px auto;
        background: var(--caramel);
        border: none;
        padding: 12px 26px;
        border-radius: 10px;
        font-size: 16px;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,.25);
      }
    </style>
</head>
<body>

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<div class="catalogo-wrapper">
    <h1>Librería</h1>

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnVerMas = document.getElementById('btnVerMas');
    const catalogoGrid = document.getElementById('catalogo-grid');

    btnVerMas.addEventListener('click', function() {
        let offset = parseInt(this.getAttribute('data-offset'));
        
        fetch(`index.php?controller=Libro&action=cargarMas&offset=${offset}`)
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php if (file_exists(__DIR__ . '/detalle.php')) include __DIR__ . '/detalle.php'; ?>
<?php if (file_exists(__DIR__ . '/../reservas/reserva.php')) include __DIR__ . '/../reservas/reserva.php'; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>

<script>
  window.BASE_URL = "<?= rtrim(BASE_URL, '/') ?>";
  window.USER_LOGGED = <?= isset($_SESSION['id_usuario']) ? 'true' : 'false' ?>;
</script>
<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/detalle.js"></script>
<script src="<?= rtrim(BASE_URL, '/') ?>/public/js/reserva.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
