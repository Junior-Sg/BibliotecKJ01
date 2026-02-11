<div class="container-fluid p-0">
    <div id="favoritosList" class="row g-3">
        <h5 class="mt-2 mb-3"><i class="bi bi-heart-fill text-danger"></i> Mis Libros Favoritos</h5>
        
        <?php if ($favoritos && $favoritos->num_rows > 0): ?>
            <?php while($f = $favoritos->fetch_assoc()): ?>
                <div class="col-6 col-md-3 col-lg-2 favorito-card" id="fav-item-<?= $f['id_libro'] ?>">
                    <div class="card h-100 shadow-sm border-0 position-relative">
                        <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle btn-eliminar-fav" 
                                onclick="quitarFavorito(<?= $f['id_libro'] ?>)" title="Quitar de favoritos">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        
                        <img src="<?= rtrim(BASE_URL, '/') ?>/public/img/Libros/<?= htmlspecialchars($f['Imagen']) ?>" 
                            class="card-img-top" style="height:150px; object-fit:cover;" 
                            onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/public/img/Libros/default.jpg'">
                        
                        <div class="card-body p-2 text-center">
                            <p class="card-text small fw-bold text-truncate mb-0"><?= htmlspecialchars($f['titulo']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-light text-center">
                    <p class="mb-0 text-muted">Aún no tienes libros en tu lista de favoritos.</p>
                    <a href="index.php?controller=Libro&action=catalogo" class="btn btn-sm btn-outline-primary mt-2">Explorar catálogo</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
