<div class="favoritos-section">
    <div class="container-fluid p-0">
        <h4 class="mb-3"><i class="bi bi-heart-fill text-danger"></i> Mis Libros Favoritos</h4>
        
        <?php if ($favoritos && $favoritos->num_rows > 0): ?>
            <div class="favoritos-grid">
                <?php while($f = $favoritos->fetch_assoc()): ?>
                    <div class="favorito-card" id="fav-item-<?= $f['id_libro'] ?>">
                        <div class="favorito-card-inner">
                            <div class="favorito-img-container">
                                <img src="<?= rtrim(BASE_URL, '/') ?>/public/img/Libros/<?= htmlspecialchars($f['Imagen']) ?>" 
                                    alt="<?= htmlspecialchars($f['titulo']) ?>"
                                    onerror="this.src='<?= rtrim(BASE_URL, '/') ?>/public/img/Libros/default.jpg'">
                                <button class="btn-quitar-fav" onclick="quitarFavorito(<?= $f['id_libro'] ?>)" title="Quitar de favoritos">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="favorito-info">
                                <p class="favorito-titulo" title="<?= htmlspecialchars($f['titulo']) ?>"><?= htmlspecialchars($f['titulo']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-heart"></i>
                <p>Aún no tienes libros en tu lista de favoritos.</p>
                <a href="index.php?controller=Libro&action=catalogo" class="btn btn-explorar">Explorar catálogo</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* ============================================================
   ESTILOS FAVORITOS - DISEÑO MODERNO AMADERADO
   ============================================================ */
:root {
    --fav-primary-brown: #6B4F4B;
    --fav-accent-gold: #c89c5d;
    --fav-dark-wood: #3d2817;
    --fav-bg-light: #f7f5f2;
    --fav-cream: #fffaf3;
    --fav-border: #d4c4b0;
    --fav-shadow: 0 8px 24px rgba(61,40,23,.10);
    --fav-shadow-hover: 0 14px 36px rgba(61,40,23,.16);
}

.favoritos-section {
    background: linear-gradient(180deg, #fff, var(--fav-cream));
    border: 1px solid var(--fav-border);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: var(--fav-shadow);
}

.favoritos-section h4 {
    font-family: 'Merriweather', serif;
    color: var(--fav-dark-wood);
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--fav-accent-gold);
    display: inline-block;
}

.favoritos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
}

.favorito-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid var(--fav-border);
}

.favorito-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--fav-shadow-hover);
}

.favorito-card-inner {
    display: flex;
    flex-direction: column;
}

.favorito-img-container {
    position: relative;
    width: 100%;
    padding-top: 130%; /* Aspect ratio para libros */
    overflow: hidden;
}

.favorito-img-container img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.favorito-card:hover .favorito-img-container img {
    transform: scale(1.05);
}

.btn-quitar-fav {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255,255,255,0.95);
    border: none;
    color: var(--fav-primary-brown);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    z-index: 2;
}

.favorito-card:hover .btn-quitar-fav {
    opacity: 1;
}

.btn-quitar-fav:hover {
    background: #dc3545;
    color: #fff;
    transform: scale(1.1);
}

.favorito-info {
    padding: 12px;
    text-align: center;
}

.favorito-titulo {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--fav-dark-wood);
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.3;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--fav-primary-brown);
}

.empty-state i {
    font-size: 4rem;
    color: var(--fav-accent-gold);
    margin-bottom: 16px;
}

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 20px;
}

.btn-explorar {
    background: linear-gradient(180deg, var(--fav-accent-gold), #b48b51);
    color: #fff !important;
    border: none !important;
    border-radius: 999px;
    font-weight: 700;
    padding: 10px 24px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: var(--fav-shadow);
    transition: all 0.2s ease;
}

.btn-explorar:hover {
    transform: translateY(-2px);
    box-shadow: var(--fav-shadow-hover);
    background: linear-gradient(180deg, #caa166, #a77840);
}

/* Responsive */
@media (max-width: 768px) {
    .favoritos-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }
    
    .favorito-info {
        padding: 10px;
    }
    
    .favorito-titulo {
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .favoritos-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .favoritos-section {
        padding: 15px;
        border-radius: 12px;
    }
}
</style>
