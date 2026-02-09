<div class="container-fluid p-0">
    <div class="row">
        <div class="col-12">
            <div class="notificaciones-container">
                <h5 class="mb-4">Mis Notificaciones</h5>

                <?php if (!empty($notificaciones)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($notificaciones as $n): ?>
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-start <?= $n['leido'] == 0 ? 'bg-light border-start border-primary border-4' : '' ?>" 
                                id="notif-<?= $n['id_notificacion'] ?>">
                                
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold text-dark small">Aviso del Sistema</div>
                                    <p class="mb-1 text-secondary" style="font-size: 0.9rem;"><?= htmlspecialchars($n['mensaje']) ?></p>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-calendar3"></i> <?= date('d/m/Y H:i', strtotime($n['fecha_creacion'])) ?>
                                    </small>
                                </div>

                                <div class="d-flex flex-column gap-1 ms-2">
                                    <?php if ($n['leido'] == 0): ?>
                                        <button class="btn btn-sm btn-outline-primary rounded-pill" 
                                                onclick="marcarLeida(<?= $n['id_notificacion'] ?>)"
                                                style="font-size: 0.7rem; white-space: nowrap;">
                                            Marcar leída
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" 
                                            onclick="eliminarNotificacion(<?= $n['id_notificacion'] ?>)"
                                            style="font-size: 0.7rem; white-space: nowrap;" title="Eliminar">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div> <?php endforeach; ?>
                    </div> <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-3 text-muted">No tienes mensajes nuevos.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function eliminarNotificacion(id) {
    if(!confirm('¿Estás seguro de eliminar esta notificación?')) return;

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
</script>
