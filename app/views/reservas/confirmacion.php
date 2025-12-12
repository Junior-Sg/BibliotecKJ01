<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reserva confirmada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= rtrim(BASE_URL, '/') ?>/public/css/bootstrap.min.css">
    <style>
        .confirm-box { max-width:720px; margin:48px auto; text-align:center; padding:32px; border-radius:12px; box-shadow:0 6px 20px rgba(0,0,0,0.06); background:#fff; }
        body { background:#f6f4f2; font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="container">
    <div class="confirm-box">
        <div style="font-size:56px; margin-bottom:14px">✅</div>
        <h1 class="h4 mb-2">Reserva registrada</h1>
        <p class="text-muted mb-3">Tu reserva se ha creado correctamente. Te enviaremos notificaciones cuando cambie su estado.</p>

        <div class="d-flex justify-content-center gap-2">
            <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=index" class="btn btn-primary">Volver al catálogo</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Usuario&action=perfil" class="btn btn-outline-secondary">Ver mis reservas</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>