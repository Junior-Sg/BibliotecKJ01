<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mi perfil</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/bootstrap.min.css">
    <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <style>
        .avatar-emoji { font-size:28px; display:inline-flex; width:64px; height:64px; align-items:center; justify-content:center; border-radius:50%; background:#f0f0f0; }
        .emoji-choice { cursor:pointer; padding:6px; font-size:22px; margin:4px; border-radius:8px; display:inline-block; }
        .emoji-choice.selected { box-shadow:0 0 0 3px rgba(100,150,255,0.25); }
    </style>
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container my-4">
    <?php if (!empty($_SESSION['flash_ok'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash_ok']; unset($_SESSION['flash_ok']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <h4>Mi perfil</h4>
            <div class="card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-emoji"><?= htmlspecialchars($usuario['avatar_emoji'] ?? '') ?></div>
                    <div>
                        <strong id="nombreUsuario"><?= htmlspecialchars($usuario['nombre'] ?? '') ?></strong><br>
                        <small id="correoUsuario"><?= htmlspecialchars($usuario['correo'] ?? '') ?></small>
                    </div>
                </div>

                <hr>
                <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modalEditar">Editar perfil</button>
                <button class="btn btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#modalEmoji">Elegir emoji</button>
            </div>

            <h5 class="mt-4">Libros favoritos</h5>
            <div id="favoritosList" class="d-flex flex-wrap gap-2">
                <?php if ($favoritos && $favoritos->num_rows): ?>
                    <?php while($f = $favoritos->fetch_assoc()): ?>
                        <div class="card p-2 mini-card" style="width:120px;">
                            <img src="<?= BASE_URL ?>public/img/Libros/<?= $f['Imagen'] ?>" style="height:100px;object-fit:cover;width:100%">
                            <div class="small mt-1"><?= htmlspecialchars($f['titulo']) ?></div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="small">No tienes favoritos aún</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-8">
            <h4>Historial de reservas</h4>
            <div id="historial">
                <?php if ($reservas && $reservas->num_rows): ?>
                <table class="table">
                    <thead><tr><th>Libro</th><th>Fecha</th><th>Estado</th><th>Acción</th></tr></thead>
                    <tbody>
                        <?php while($r = $reservas->fetch_assoc()): ?>
                        <tr id="res-<?= $r['id_reserva'] ?>">
                            <td>
                                <img src="<?= BASE_URL ?>public/img/Libros/<?= $r['Imagen'] ?>" style="width:56px;height:70px;object-fit:cover;margin-right:8px">
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
    </div>
</div>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditar" method="post" action="<?= BASE_URL ?>index.php?controller=Usuario&action=actualizar">
        <div class="modal-content">
            <div class="modal-header"><h5>Editar perfil</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-2"><label>Nombre</label>
                    <input class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>"></div>
                <div class="mb-2"><label>Correo</label>
                    <input class="form-control" name="correo" value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>"></div>
                <div class="mb-2"><label>Teléfono</label>
                    <input class="form-control" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal elegir emoji -->
<div class="modal fade" id="modalEmoji" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
        <div class="modal-header"><h5>Elegir emoji</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <?php $emojis = ['😀','😃','😄','😁','😆','😊','😎','🤓','🫠','🙂','🙃','🤩','🥳','🧐','🤠','🧑‍🎓','🧑‍💻','👩‍🏫']; ?>
            <div id="emojiContainer">
                <?php foreach($emojis as $e): ?>
                    <span class="emoji-choice <?= ($usuario['avatar_emoji'] ?? '') === $e ? 'selected' : '' ?>" data-emoji="<?= htmlspecialchars($e) ?>"><?= $e ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>public/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('click', function(e){
    if (e.target.matches('.btn-cancelar')) {
        const id = e.target.dataset.id;
        if (!confirm('¿Cancelar la reserva?')) return;

        fetch('index.php?controller=Usuario&action=cancelarReservaAjax', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'id_reserva=' + encodeURIComponent(id)
        }).then(r=>r.json()).then(data=>{
            if (data.ok) {
                const tr = document.getElementById('res-' + id);
                if (tr) tr.querySelector('.estado').textContent = 'cancelada';
                if (tr) tr.querySelector('.btn-cancelar')?.remove();
            } else {
                alert('No se pudo cancelar: ' + (data.error || 'error'));
            }
        });
    }
});

// Emoji AJAX
document.querySelectorAll('.emoji-choice').forEach(el=>{
    el.addEventListener('click', function(){
        const emoji = this.dataset.emoji;
        fetch('index.php?controller=Usuario&action=elegirEmoji', {
            method:'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'emoji=' + encodeURIComponent(emoji)
        }).then(r=>r.json()).then(j=>{
            if (j.ok) {
                // actualizar avatar en la UI
                document.querySelector('.avatar-emoji').textContent = emoji;
                // marcar seleccionado
                document.querySelectorAll('.emoji-choice').forEach(x=>x.classList.remove('selected'));
                this.classList.add('selected');
            } else {
                alert(j.error || 'Error al guardar emoji');
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <?php include __DIR__ . '../../layouts/footer.php'; ?>
</body>
</html>
