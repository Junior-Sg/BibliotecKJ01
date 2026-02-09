// Base URL que viene de perfil.php
const baseUrl = window.AppConfig ? window.AppConfig.baseUrl : '';

document.addEventListener('click', function (e) {
    // Cancelar reserva
    if (e.target.matches('.btn-cancelar')) {
        const id = e.target.dataset.id;
        if (!confirm('¿Cancelar la reserva?')) return;

        fetch(`${baseUrl}/index.php?controller=Usuario&action=cancelarReservaAjax`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_reserva=' + encodeURIComponent(id)
        })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    const tr = document.getElementById('res-' + id);
                    if (tr) tr.querySelector('.estado').textContent = 'cancelada';
                    if (tr) tr.querySelector('.btn-cancelar')?.remove();
                } else {
                    alert('No se pudo cancelar: ' + (data.error || 'error'));
                }
            })
            .catch(() => alert('Error de red al intentar cancelar la reserva.'));
    }

    // Añadir favorito (desde historial u otra tabla)
    const favButton = e.target.closest('.btn-add-favorito');
    if (favButton) {
        const idLibro = favButton.dataset.idLibro;
        const titulo = favButton.dataset.titulo ||
            favButton.closest('tr')?.querySelector('td:first-child')?.innerText.trim() ||
            'Sin título';
        const imagen = favButton.dataset.imagen ||
            favButton.closest('tr')?.querySelector('img')?.src.split('/').pop() ||
            'default.jpg';

        favButton.disabled = true;

        fetch(`${baseUrl}/index.php?controller=Usuario&action=agregarFavoritoAjax`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id_libro=' + encodeURIComponent(idLibro)
        })
            .then(async r => {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                const txt = await r.text();
                try { return JSON.parse(txt); }
                catch (e) { throw new Error('Respuesta no JSON: ' + txt.slice(0, 200)); }
            })
            .then(data => {
                if (data.ok) {
                    favButton.innerHTML = '✓ Favorito';
                    favButton.classList.remove('btn-success');
                    favButton.classList.add('btn-outline-success');

                    // Insertar tarjeta en la pestaña Favoritos
                    const favList = document.getElementById('favoritosList');
                    if (favList?.querySelector('p.small')) favList.innerHTML = '';
                    if (!document.getElementById(`fav-item-${idLibro}`)) {
                        const newFavCard = `
                        <div class="col-6 col-md-3 col-lg-2 favorito-card" id="fav-item-${idLibro}">
                            <div class="card h-100 shadow-sm border-0 position-relative">
                                <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle btn-eliminar-fav"
                                        onclick="quitarFavorito(${idLibro})" title="Quitar de favoritos">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <img src="${baseUrl}/public/img/Libros/${imagen}"
                                     class="card-img-top" style="height:150px; object-fit:cover;"
                                     onerror="this.src='${baseUrl}/public/img/Libros/default.jpg'">
                                <div class="card-body p-2 text-center">
                                    <p class="card-text small fw-bold text-truncate mb-0">${titulo}</p>
                                </div>
                            </div>
                        </div>`;
                        favList?.insertAdjacentHTML('beforeend', newFavCard);
                    }
                } else {
                    favButton.disabled = false;
                    alert('No se pudo añadir a favoritos: ' + (data.error || 'error'));
                }
            })
            .catch(err => {
                favButton.disabled = false;
                console.error(err);
                alert('Error al añadir a favoritos: ' + err.message);
            });
    }
});

// Emoji AJAX
const emojiChoices = document.querySelectorAll('.emoji-choice');
const btnGuardarEmoji = document.getElementById('btnGuardarEmoji');
const modalEmoji = new bootstrap.Modal(document.getElementById('modalEmoji'));

let selectedEmoji = null;

emojiChoices.forEach(el => {
    el.addEventListener('click', function () {
        emojiChoices.forEach(x => x.classList.remove('selected'));
        this.classList.add('selected');
        selectedEmoji = this.dataset.emoji;
    });
});

btnGuardarEmoji.addEventListener('click', function () {
    if (!selectedEmoji) {
        alert('Por favor, elige un emoji.');
        return;
    }

    fetch(`${baseUrl}/index.php?controller=Usuario&action=elegirEmoji`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'emoji=' + encodeURIComponent(selectedEmoji)
    }).then(r => r.json()).then(data => {
        if (data.ok) {
            document.getElementById('perfilAvatarEmoji').textContent = selectedEmoji;
            const navbarEmoji = document.querySelector('.app-header .avatar-emoji');
            if (navbarEmoji) navbarEmoji.textContent = selectedEmoji;
            modalEmoji.hide();
        } else {
            alert(data.error || 'Error al guardar el emoji');
        }
    }).catch(() => alert('Error de red al intentar guardar el emoji.'));
});

// Quitar favorito (botón en tarjetas)
function quitarFavorito(idLibro) {
    if (!confirm('¿Estás seguro de que deseas quitar este libro de tus favoritos?')) return;

    const formData = new FormData();
    formData.append('id_libro', idLibro);

    fetch(`${baseUrl}/index.php?controller=Usuario&action=eliminarFavoritoAjax`, {
        method: 'POST',
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                const elemento = document.getElementById(`fav-item-${idLibro}`);
                if (elemento) {
                    elemento.style.opacity = '0';
                    setTimeout(() => {
                        elemento.remove();
                        const list = document.getElementById('favoritosList');
                        if (list.querySelectorAll('.favorito-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            } else {
                alert('Error: ' + (data.error || 'No se pudo eliminar de favoritos'));
            }
        })
        .catch(() => alert('Hubo un error en la conexión.'));
}

// Marcar notificación como leída
function marcarLeida(idNotificacion) {
    const item = document.getElementById(`notif-${idNotificacion}`);
    const badge = document.querySelector('.nav-link .badge');

    const formData = new FormData();
    formData.append('id_notificacion', idNotificacion);

    fetch(`${baseUrl}/index.php?controller=Usuario&action=marcarLeidaAjax`, {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (!data.ok) return;

        // DOM updates juntos (menos reflow)
        if (item) {
            item.classList.remove('bg-light', 'border-primary', 'border-4');
            const btn = item.querySelector('button');
            if (btn) btn.remove();
        }

        if (badge) {
            const count = parseInt(badge.innerText, 10) - 1;
            if (count <= 0) {
                badge.remove();
            } else {
                badge.innerText = count;
            }
        }
    });
}

