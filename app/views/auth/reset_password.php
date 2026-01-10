<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Bibliotec_KJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/BibliotecKJ01/public/css/Login_usuario.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/alert.php'; ?>
    <div class="book-container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="book open">
            <div class="page page-login" style="display: block;">
                <form id="reset-form" action="<?= BASE_URL ?>index.php?controller=PasswordReset&action=updatePassword" method="POST">
                    <h2>Restablecer Contraseña</h2>
                    <!-- Usamos la variable $token que nos pasa el controlador -->
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

                    <div class="input-group">
                        <label for="clave">Nueva Contraseña</label>
                        <input type="password" name="clave" id="clave" required minlength="8">
                    </div>

                    <div class="input-group">
                        <label for="clave_confirm">Confirmar Nueva Contraseña</label>
                        <input type="password" name="clave_confirm" id="clave_confirm" required minlength="8">
                    </div>

                    <button type="submit">Restablecer Contraseña</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    // Validación de formulario en el cliente
    document.getElementById('reset-form').addEventListener('submit', function(event) {
        const clave = document.getElementById('clave').value;
        const claveConfirm = document.getElementById('clave_confirm').value;
        const alertContainer = document.querySelector('body');
        let existingAlert = document.querySelector('.alert-custom');
        if(existingAlert) existingAlert.remove();

        if (clave.length < 8) {
            event.preventDefault();
            const alertHtml = `<div class='alert alert-danger alert-custom' role='alert'><div><strong>Error:</strong> La contraseña debe tener al menos 8 caracteres.</div></div>`;
            alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
            return;
        }

        if (clave !== claveConfirm) {
            event.preventDefault();
            const alertHtml = `<div class='alert alert-danger alert-custom' role='alert'><div><strong>Error:</strong> Las contraseñas no coinciden.</div></div>`;
            alertContainer.insertAdjacentHTML('afterbegin', alertHtml);
            return;
        }
    });
    </script>
</body>
</html>
