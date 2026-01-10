<?php foreach ($masLibros as $book): ?>
    <div class="book-card" role="button" tabindex="0" data-bs-toggle="modal" data-bs-target="#modalDetalle" data-id="<?= (int)$book['id_libro'] ?>">
        <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($book['Imagen']) ?>" alt="Portada del libro <?= htmlspecialchars($book['titulo']) ?>" class="book-cover">
        <div class="book-info">
            <h5><?= htmlspecialchars($book['titulo']) ?></h5>
        </div>
    </div>
<?php endforeach; ?>