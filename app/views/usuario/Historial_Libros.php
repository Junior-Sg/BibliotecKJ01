<div class="col-12 mt-2 mb-3">
    <div class="historial-section">
        <h4><i class="bi bi-book"></i> Libros en Préstamo</h4>
        <div id="prestamos-activos" class="table-responsive-custom">
            <?php if (!empty($prestamosActivos)): ?>
            <div class="table-scroll">
            <table class="table">
                <thead><tr><th>Libro</th><th>Fecha Devolución</th><th>Estado</th><th>Acción</th></tr></thead>
                <tbody>
                    <?php foreach($prestamosActivos as $p): ?>
                    <tr id="prestamo-<?= $p['id_prestamo'] ?>">
                        <td>
                            <img src="<?= base_url('public/img/Libros/' . ($p['Imagen'] ?? 'default.jpg')) ?>"
                                 onerror="this.src='<?= base_url('public/img/Libros/default.jpg') ?>'">
                            <span class="fw-bold"><?= htmlspecialchars($p['titulo']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($p['fecha_devolucion']) ?></td>
                        <td>
                            <?php 
                            $estadoClass = $p['estado'] === 'retrasado' ? 'badge-retrasado' : 'badge-pendiente';
                            ?>
                            <span class="badge <?= $estadoClass ?>">
                                <?= ucfirst($p['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-success btn-solicitar-aplazamiento" 
                                    data-id-prestamo="<?= $p['id_prestamo'] ?>"
                                    data-titulo="<?= htmlspecialchars($p['titulo']) ?>">
                                <i class="bi bi-calendar-plus"></i> Aplazar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php else: ?>
                <p class="small text-muted text-center py-4">No tienes libros en préstamo actualmente</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="col-12 mt-2 mb-3">
    <div class="historial-section">
        <h4><i class="bi bi-clock-history"></i> Historial de Libros Leídos</h4>
        <div id="historial-lectura" class="table-responsive-custom">
            <?php if (!empty($historialLectura)): ?>
            <div class="table-scroll">
            <table class="table">
                <thead><tr><th>Libro</th><th>Fecha Devolución</th><th>Acción</th></tr></thead>
                <tbody>
                    <?php foreach($historialLectura as $h): ?>
                    <tr>
                        <td>
                            <img src="<?= base_url('public/img/Libros/' . ($h['Imagen'] ?? 'default.jpg')) ?>"
                                 onerror="this.src='<?= base_url('public/img/Libros/default.jpg') ?>'">
                            <span class="fw-bold"><?= htmlspecialchars($h['titulo']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($h['fecha_devolucion']) ?></td>
                        <td>
                            <?php $isFav = in_array((int)$h['id_libro'], $favoritos_ids ?? []); ?>
                            <?php if ($isFav): ?>
                                <button class="btn btn-sm btn-outline-success" disabled>
                                    <i class="bi bi-check"></i> Favorito
                                </button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-add-favorito"
                                        data-id-libro="<?= $h['id_libro'] ?>"
                                        data-titulo="<?= htmlspecialchars($h['titulo']) ?>"
                                        data-imagen="<?= htmlspecialchars($h['Imagen'] ?? 'default.jpg') ?>">
                                    <i class="bi bi-heart"></i> Favorito
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php else: ?>
                <p class="small text-muted text-center py-4">Aún no has devuelto ningún libro</p>
            <?php endif; ?>
        </div>
    </div>
</div>
