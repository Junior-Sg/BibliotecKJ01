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
                    if (tr) {
                        tr.remove();
                        const tbody = document.querySelector('#reservas-historial tbody');
                        if (tbody && tbody.children.length === 0) {
                            const cont = document.getElementById('reservas-historial');
                            if (cont) cont.innerHTML = '<p class="small">No hay reservas</p>';
                        }
                    }
                    // Recargar página para actualizar contadores y estados
                    setTimeout(() => location.reload(), 500);
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
                    
                    // Recargar página para actualizar todos los contadores
                    setTimeout(() => location.reload(), 500);
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

// ========== SOLICITUD DE APLAZAMIENTO ==========

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-solicitar-aplazamiento');
    if (btn) {
        const idPrestamo = btn.dataset.idPrestamo;
        const titulo = btn.dataset.titulo;
        abrirModalAplazamiento(idPrestamo, titulo);
    }
});

function abrirModalAplazamiento(idPrestamo, titulo) {
    // Crear modal dinámico
    let modal = document.getElementById('modalAplazamiento');
    if (!modal) {
        const modalHTML = `
        <div class="modal fade" id="modalAplazamiento" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(180deg, #D4841C, #A9541A); color: white;">
                        <h5 class="modal-title">📋 Solicitar aplazamiento de entrega</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3"><strong style="color: #241705;">📚 Libro:</strong> <span id="aplazamientoTitulo" style="color: #333;"></span></p>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #241705;"><strong>⏱️ Días adicionales solicitados</strong></label>
                            <select id="aplazamientoDias" class="form-select" required style="border-color: #D4841C;">
                                <option value="">-- Selecciona --</option>
                                <option value="3">3 días</option>
                                <option value="7">7 días</option>
                                <option value="14">14 días</option>
                                <option value="21">21 días (máximo)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="color: #241705;"><strong>💬 Motivo (opcional)</strong></label>
                            <textarea id="aplazamientoMotivo" class="form-control" rows="3" maxlength="500" placeholder="Cuéntanos por qué necesitas más tiempo..." style="border-color: #D4841C;"></textarea>
                            <small class="text-muted">Máximo 500 caracteres</small>
                        </div>
                        
                        <div id="aplazamientoError" class="alert alert-danger d-none" role="alert"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-outline-success" id="btnEnviarAplazamiento">
                            <i class="bi bi-send"></i> Enviar Solicitud
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
        
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        modal = document.getElementById('modalAplazamiento');
    }
    
    // Llenar datos
    document.getElementById('aplazamientoTitulo').textContent = titulo;
    document.getElementById('aplazamientoDias').value = '';
    document.getElementById('aplazamientoMotivo').value = '';
    document.getElementById('aplazamientoError').classList.add('d-none');
    
    // Guardar el ID para luego
    modal.dataset.idPrestamo = idPrestamo;
    
    // Mostrar modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Evento del botón enviar
    const btnEnviar = document.getElementById('btnEnviarAplazamiento');
    if (btnEnviar) {
        btnEnviar.onclick = function() {
            enviarSolicitudAplazamiento(idPrestamo);
        };
    }
}

function enviarSolicitudAplazamiento(idPrestamo) {
    const diasSolicitados = document.getElementById('aplazamientoDias').value;
    const motivo = document.getElementById('aplazamientoMotivo').value.trim();
    const errorDiv = document.getElementById('aplazamientoError');
    
    // Validar
    if (!diasSolicitados) {
        errorDiv.textContent = 'Por favor selecciona la cantidad de días';
        errorDiv.classList.remove('d-none');
        return;
    }
    
    // Desabilitar botón
    const btnEnviar = document.getElementById('btnEnviarAplazamiento');
    btnEnviar.disabled = true;
    const textoOriginal = btnEnviar.innerHTML;
    btnEnviar.innerHTML = '⏳ Enviando...';
    
    // Enviar petición
    const formData = new FormData();
    formData.append('id_prestamo', idPrestamo);
    formData.append('dias_solicitados', diasSolicitados);
    formData.append('motivo', motivo);
    
    const apiUrl = (window.AppConfig && window.AppConfig.baseUrl) 
        ? window.AppConfig.baseUrl + '/public/api/solicitar_aplazamiento.php'
        : window.BASE_URL + 'public/api/solicitar_aplazamiento.php';
    
    fetch(apiUrl, {
        method: 'POST',
        body: formData
    })
    .then(async r => {
        const contentType = r.headers.get('content-type');
        console.log('Response status:', r.status, 'Content-Type:', contentType);
        
        if (!contentType || !contentType.includes('application/json')) {
            const text = await r.text().catch(() => 'No body');
            throw new Error('La respuesta no es JSON. Recibido: ' + contentType + ' - ' + text);
        }
        
        // Parsear JSON sin depender de r.ok
        const data = await r.json();
        
        // Ahora checar el status
        if (!r.ok) {
            const msg = data.message || ('HTTP ' + r.status);
            throw new Error(msg);
        }
        
        return data;
    })
    .then(data => {
        if (data.success) {
            // Cerrar modal
            const modal = document.getElementById('modalAplazamiento');
            if (modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();
            }
            
            // Mostrar mensaje de éxito
            alert(data.message || 'Solicitud enviada correctamente');
            
            // Recargar para actualizar la tabla
            location.reload();
        } else {
            errorDiv.textContent = data.message || 'Error al enviar la solicitud';
            errorDiv.classList.remove('d-none');
        }
    })
    .catch(err => {
        console.error('Error en solicitud de aplazamiento:', err);
        errorDiv.textContent = '❌ Error: ' + err.message;
        errorDiv.classList.remove('d-none');
    })
    .finally(() => {
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = textoOriginal;
    });
}

function eliminarNotificacion(id) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta notificación?')) return;

    const formData = new FormData();
    formData.append('id_notificacion', id);

    fetch(window.AppConfig.baseUrl + '/index.php?controller=Usuario&action=eliminarNotificacionAjax', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.ok) {
            const el = document.getElementById('notif-' + id);
            if(el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        } else {
            alert('No se pudo eliminar la notificación.');
        }
    })
    .catch(e => console.error(e));
}
