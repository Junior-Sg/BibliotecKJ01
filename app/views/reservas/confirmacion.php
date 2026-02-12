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
        :root {
            --bg-light: #f7f5f2;
            --bg-secondary: #e8dcd0;
            --primary-brown: #6B4F4B;
            --accent-gold: #c89c5d;
            --dark-wood: #3d2817;
            --text-dark: #3d2817;
            --white: #ffffff;
            --border-color: #d4c4b0;
        }
        body {
            background: linear-gradient(180deg, var(--bg-secondary), var(--bg-light));
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
        }
        .confirm-box {
            max-width: 600px;
            margin: 60px auto;
            text-align: center;
            padding: 40px;
            border-radius: 16px;
            background: var(--white);
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(61, 40, 23, 0.1);
        }
        .icon-wrapper {
            font-size: 48px;
            margin: 0 auto 20px auto;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fdfaf5;
            border: 2px solid var(--accent-gold);
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(200, 156, 93, 0.2);
        }
        h1 { color: var(--primary-brown); font-weight: 700; }
        .btn-primary {
            background-color: var(--accent-gold); border-color: var(--accent-gold); font-weight: 600; padding: 10px 24px;
        }
        .btn-primary:hover { background-color: #b48b51; border-color: #b48b51; }
        .btn-outline-secondary {
            color: var(--primary-brown); border-color: var(--primary-brown); font-weight: 600; padding: 10px 24px;
        }
        .btn-outline-secondary:hover { background-color: var(--primary-brown); color: var(--white); }
    </style>
</head>
<body>
<?php include __DIR__ . '/../layouts/navbar.php'; ?>
<div class="container">
    <div class="confirm-box">
        <div class="icon-wrapper">✅</div>
        <h1 class="h4 mb-2">Reserva registrada</h1>
        <p class="text-muted mb-3">Tu reserva se ha creado correctamente. Te enviaremos notificaciones cuando cambie su estado.</p>

        <div class="d-flex justify-content-center gap-2">
            <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=index" class="btn btn-primary">Volver al catálogo</a>
            <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Usuario&action=perfil#historial" class="btn btn-outline-secondary">Ver mi historial</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>