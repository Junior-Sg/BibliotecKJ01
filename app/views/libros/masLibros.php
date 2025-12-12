<?php foreach ($masLibros as $book): ?>
    <a href="index.php?controller=Libro&action=detalle&id=<?= $book['id_libro'] ?>" class="book-card">
        <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($book['Imagen']) ?>" alt="Portada del libro <?= htmlspecialchars($book['titulo']) ?>" class="book-cover">
        <div class="book-info">
            <h5><?= htmlspecialchars($book['titulo']) ?></h5>
        </div>
    </a>
<?php endforeach; ?>