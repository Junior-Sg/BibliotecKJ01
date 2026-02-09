<div class="col-12 col-lg-8">
    <h4>Historial de reservas</h4>
    <div id="historial">
        <?php if ($reservas && $reservas->num_rows): ?>
        <table class="table">
            <thead><tr><th>Libro</th><th>Fecha</th><th>Estado</th><th>Acción</th></tr></thead>
            <tbody>
                <?php while($r = $reservas->fetch_assoc()): ?>
                <tr id="res-<?= $r['id_reserva'] ?>">
                    <td>
                        <img src="<?= base_url('public/img/Libros/' . $r['Imagen']) ?>" style="width:56px;height:70px;object-fit:cover;margin-right:8px">
                        <?= htmlspecialchars($r['titulo']) ?>
                    </td>
                    <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
                    <td class="estado"><?= htmlspecialchars($r['estado']) ?></td>
                    <td>
                        <?php if ($r['estado'] === 'pendiente'): ?>
                            <button class="btn btn-sm btn-danger btn-cancelar" data-id="<?= $r['id_reserva'] ?>">Cancelar</button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="small">No hay reservas</p>
        <?php endif; ?>
    </div>
</div>
