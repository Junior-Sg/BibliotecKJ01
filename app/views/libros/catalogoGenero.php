<h3 class="mt-4 mb-3">Género: <?= htmlspecialchars($generoNombre) ?></h3>

<div class="catalogo-grid">
<?php foreach ($librosDelGenero as $book): ?>
    <div class="book-card">
        <img src="<?= BASE_URL.'public/img/Libros/'.$book['Imagen'] ?>" class="book-cover">
        <div class="book-info">
            <h5><?= htmlspecialchars($book['titulo']) ?></h5>
            <button class="btn btn-light btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#modalDetalle"
                data-id="<?= $book['id_libro'] ?>">
                Ver detalle
            </button>
        </div>
    </div>
<?php endforeach; ?>
</div>
<div class="text-center mt-4">
    <button onclick="history.back()" class="btn btn-dark">‹ Volver</button>
</div>