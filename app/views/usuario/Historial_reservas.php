<div class="col-12 col-lg-8">
    <div class="historial-section">
        <h4><i class="bi bi-calendar-check"></i> Historial de Reservas</h4>
        <div id="reservas-historial" class="table-responsive-custom">
            <?php if ($reservas && $reservas->num_rows): ?>
            <div class="table-scroll">
            <table class="table">
                <thead><tr><th>Libro</th><th>Fecha</th><th>Estado</th><th>Acción</th></tr></thead>
                <tbody>
                    <?php while($r = $reservas->fetch_assoc()): ?>
                    <tr id="res-<?= $r['id_reserva'] ?>">
                        <td>
                            <img src="<?= base_url('public/img/Libros/' . $r['Imagen']) ?>">
                            <span class="fw-bold"><?= htmlspecialchars($r['titulo']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
                        <td>
                            <?php 
                            $estadoClass = '';
                            if ($r['estado'] === 'pendiente') $estadoClass = 'badge-pendiente';
                            elseif ($r['estado'] === 'confirmada') $estadoClass = 'badge-confirmada';
                            elseif ($r['estado'] === 'cancelada') $estadoClass = 'badge-cancelada';
                            else $estadoClass = 'badge-activo';
                            ?>
                            <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($r['estado']) ?></span>
                        </td>
                        <td>
                            <?php if ($r['estado'] === 'pendiente'): ?>
                                <button class="btn btn-sm btn-cancelar" data-id="<?= $r['id_reserva'] ?>">Cancelar</button>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            </div>
            <?php else: ?>
                <p class="small text-muted text-center py-4">No hay reservas activas</p>
            <?php endif; ?>
        </div>
    </div>
</div>
