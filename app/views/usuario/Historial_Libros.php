<div class="col-12 mt-4">
    <h4>Libros en Préstamo</h4>
    <div id="prestamos-activos">
        <?php if (!empty($prestamosActivos)): ?>
        <table class="table">
            <thead><tr><th>Libro</th><th>Fecha Devolución</th><th>Estado</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach($prestamosActivos as $p): ?>
                <tr id="prestamo-<?= $p['id_prestamo'] ?>">
                    <td>
                        <img src="<?= base_url('public/img/Libros/' . ($p['Imagen'] ?? 'default.jpg')) ?>"
                            style="width:56px;height:70px;object-fit:cover;margin-right:8px"
                            onerror="this.src='<?= base_url('public/img/Libros/default.jpg') ?>'">
                        <?= htmlspecialchars($p['titulo']) ?>
                    </td>
                    <td><?= htmlspecialchars($p['fecha_devolucion']) ?></td>
                    <td>
                        <span class="badge <?= $p['estado'] === 'retrasado' ? 'bg-danger' : 'bg-warning text-dark' ?>">
                            <?= ucfirst($p['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-success btn-solicitar-aplazamiento" 
                                data-id-prestamo="<?= $p['id_prestamo'] ?>"
                                data-titulo="<?= htmlspecialchars($p['titulo']) ?>">
                            <i class="bi bi-calendar-plus"></i> Solicitar aplazamiento
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="small">No tienes libros en préstamo actualmente.</p>
        <?php endif; ?>
    </div>
</div>

<div class="col-12 mt-4">
    <h4>Historial de Libros Leídos</h4>
    <div id="historial-lectura">
        <?php if (!empty($historialLectura)): ?>
        <table class="table">
            <thead><tr><th>Libro</th><th>Fecha Devolución</th><th>Acción</th></tr></thead>
            <tbody>
                <?php foreach($historialLectura as $h): ?>
                <tr>
                    <td>
                        <img src="<?= base_url('public/img/Libros/' . ($h['Imagen'] ?? 'default.jpg')) ?>"
                            style="width:56px;height:70px;object-fit:cover;margin-right:8px"
                            onerror="this.src='<?= base_url('public/img/Libros/default.jpg') ?>'">
                        <?= htmlspecialchars($h['titulo']) ?>
                    </td>
                    <td><?= htmlspecialchars($h['fecha_devolucion']) ?></td>
                    <td>
                        <?php $isFav = in_array((int)$h['id_libro'], $favoritos_ids ?? []); ?>
                        <?php if ($isFav): ?>
                            <button class="btn btn-sm btn-outline-success" disabled>✓ Favorito</button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-success btn-add-favorito"
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
        <?php else: ?>
            <p class="small">Aún no has devuelto ningún libro.</p>
        <?php endif; ?>
    </div>
</div>
