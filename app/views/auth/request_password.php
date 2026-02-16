<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Bibliotec_KJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/Login_usuario.css">
</head>
<body>
    <?php include __DIR__ . '/../../helpers/alert.php'; ?>
    <div class="book-container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="book open">
            <div id="reset-form-container" class="page page-login" style="display: block;">
                <form id="request-reset-form" action="<?= BASE_URL ?>index.php?controller=PasswordReset&action=sendCode" method="POST">
                    <h2>Recuperar Contraseña</h2>
                    <p>Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

                    <div class="input-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" required>
                    </div>
                    
                    <button type="submit">Enviar Código</button>

                    <div class="extra">
                        <a href="<?= BASE_URL ?>index.php?controller=LoginUsuario&action=index">← Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    // Script para dar feedback visual al usuario al enviar el formulario
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('request-reset-form');
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Enviando...';
        });
    });
    </script>
</body>
</html>
