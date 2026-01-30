<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mi perfil</title>
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
                    <div id="perfilAvatarEmoji" class="avatar-emoji"><?= htmlspecialchars($usuario['avatar_emoji'] ?? '') ?></div>
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
                    <?php while($f = $favoritos->fetch_assoc()): // Guardamos el resultado para poder reiniciarlo si es necesario ?>
                        <div class="card p-2 mini-card" style="width:120px;">
                            <img src="<?= base_url('public/img/Libros/' . $f['Imagen']) ?>" style="height:100px;object-fit:cover;width:100%">
                            <div class="small mt-1"><?= htmlspecialchars($f['titulo']) ?></div>
                        </div>
                    <?php endwhile; 
                          $favoritos->data_seek(0); // Reiniciamos el puntero por si se usa después
                    ?>
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

            <h4 class="mt-5">Historial de Libros Leídos</h4>
            <div id="historial-lectura">
                <?php if (!empty($historialLectura)): ?>
                <table class="table">
                    <thead><tr><th>Libro</th><th>Fecha Devolución</th><th>Acción</th></tr></thead>
                    <tbody>
                        <?php foreach($historialLectura as $h): ?>
                        <tr>
                            <td>
                                <img src="<?= base_url('public/img/Libros/' . $h['Imagen']) ?>" style="width:56px;height:70px;object-fit:cover;margin-right:8px">
                                <?= htmlspecialchars($h['titulo']) ?>
                            </td>
                            <td><?= htmlspecialchars($h['fecha_devolucion']) ?></td>
                            <td>
                                <?php $isFav = in_array((int)$h['id_libro'], $favoritos_ids ?? []); ?>
                                <?php if ($isFav): ?>
                                    <button class="btn btn-sm btn-outline-success" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                        <path d="M8 1C6.346-1 3 1.2 3 4.5 3 7 6.2 9 8 11.5 9.8 9 13 7 13 4.5 13 1.2 9.654-1 8 1z"/>
                                        </svg>
                                        Favorito
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-success btn-add-favorito" data-id-libro="<?= $h['id_libro'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-1.114 2.175-.229 4.842 2.365 7.027l.175.176L8 14.348l4.06-4.092.175-.176c2.594-2.185 3.48-4.852 2.365-7.027C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"/>
                                        </svg>
                                        Favorito
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
        </div> <!-- Cierre de modal-content -->
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
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnGuardarEmoji">Guardar</button>
        </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<script>
document.addEventListener('click', function(e){
    // Boton cancelar reserva
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
        })
        .catch(error => alert('Error de red al intentar cancelar la reserva.'));
    }

    // Boton agregar favorito
    const favButton = e.target.closest('.btn-add-favorito');
    if (favButton) {
        const idLibro = favButton.dataset.idLibro;
        favButton.disabled = true;

        fetch('index.php?controller=Usuario&action=agregarFavoritoAjax', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'id_libro=' + encodeURIComponent(idLibro)
        })
        .then(r=>r.json())
        .then(data=>{
            if (data.ok) {
                favButton.innerHTML = '¡Añadido!';
                favButton.classList.remove('btn-success');
                favButton.classList.add('btn-outline-success');

                // Añadir dinámicamente a la lista de favoritos sin recargar
                const libroInfo = {
                    titulo: favButton.closest('tr').querySelector('td:first-child').innerText.trim(),
                    imagen: favButton.closest('tr').querySelector('img').src.split('/').pop()
                };
                
                const favList = document.getElementById('favoritosList');
                if (favList.querySelector('p.small')) {
                    favList.innerHTML = ''; // Limpiar el mensaje "No tienes favoritos"
                }
                
                const newFavCard = `
                    <div class="card p-2 mini-card" style="width:120px;">
                        <img src="<?= BASE_URL ?>public/img/Libros/${libroInfo.imagen}" style="height:100px;object-fit:cover;width:100%">
                        <div class="small mt-1">${libroInfo.titulo}</div>
                    </div>`;
                favList.insertAdjacentHTML('beforeend', newFavCard);
            } else {
                alert('No se pudo añadir a favoritos: ' + (data.error || 'error'));
                favButton.disabled = false;
            }
        })
        .catch(error => {
            alert('Error de red al intentar añadir a favoritos.');
            favButton.disabled = false;
        });
    }
});

// Emoji AJAX
const emojiChoices = document.querySelectorAll('.emoji-choice');
const btnGuardarEmoji = document.getElementById('btnGuardarEmoji');
const modalEmoji = new bootstrap.Modal(document.getElementById('modalEmoji'));

let selectedEmoji = null;

emojiChoices.forEach(el => {
    el.addEventListener('click', function() {
        // Quita la selección de otros emojis
        emojiChoices.forEach(x => x.classList.remove('selected'));
        // Marca el emoji actual como seleccionado
        this.classList.add('selected');
        // Guarda el emoji seleccionado en una variable
        selectedEmoji = this.dataset.emoji;
    });
});

btnGuardarEmoji.addEventListener('click', function() {
    if (!selectedEmoji) {
        alert('Por favor, elige un emoji.');
        return;
    }

    fetch('index.php?controller=Usuario&action=elegirEmoji', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'emoji=' + encodeURIComponent(selectedEmoji)
    }).then(r => r.json()).then(data => {
        if (data.ok) {
            // Actualizamos ambos emojis para una experiencia consistente
            document.getElementById('perfilAvatarEmoji').textContent = selectedEmoji;
            
            // Selector correcto para el emoji del navbar (usa .app-header)
            const navbarEmoji = document.querySelector('.app-header .avatar-emoji');
            if (navbarEmoji) navbarEmoji.textContent = selectedEmoji;

            modalEmoji.hide();
        } else {
            alert(data.error || 'Error al guardar el emoji');
        }
    }).catch(error => alert('Error de red al intentar guardar el emoji.'));
});
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
