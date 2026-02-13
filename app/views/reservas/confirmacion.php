<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reserva Confirmada - BibliotecKJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/public/css/perfil/perfil.css">
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<!-- Toast de Reserva Exitosa -->
<div class="position-fixed p-3" style="z-index: 2100; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div id="reservaExitosaToast" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true" style="background: linear-gradient(135deg, #2C5282 0%, #1A365D 100%); box-shadow: 0 12px 32px rgba(44, 82, 130, 0.35); min-width: 380px;">
        <div class="p-4">
            <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
                <div class="icon-wrapper" style="width: 50px; height: 50px; background-color: rgba(72, 187, 120, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="bi bi-check-lg" style="font-size: 1.5rem; color: #48BB78;"></i>
                </div>
                <div style="flex: 1;">
                    <h6 class="text-white mb-1" style="font-size: 1.1rem;">¡Reserva Exitosa!</h6>
                    <p class="text-white-50 mb-0" style="font-size: 0.95rem;">Tu reserva se ha realizado correctamente. Te enviaremos una notificación cuando esté lista para recoger.</p>
                </div>
            </div>
            <div class="d-flex gap-2" style="margin-top: 16px;">
                <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=catalogo" class="btn btn-sm text-white flex-grow-1" style="background-color: #48BB78; border: none; font-weight: 500;">Ver Catálogo</a>
                <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Usuario&action=perfil#historial" class="btn btn-sm text-white flex-grow-1" style="background-color: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); font-weight: 500;">Mi Historial</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar toast automáticamente
    const toastEl = document.getElementById('reservaExitosaToast');
    const toast = new bootstrap.Toast(toastEl, {
        autohide: false,
        delay: 5000
    });
    toast.show();
    
    // Acción del toast - redirigir al catálogo
    toastEl.querySelector('.btn-outline-success, a[href*="catalogo"]')?.addEventListener('click', function() {
        toast.hide();
    });
});
</script>
</body>
</html>
