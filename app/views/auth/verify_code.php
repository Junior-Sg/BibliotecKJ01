<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código - Bibliotec_KJ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/BibliotecKJ01/public/css/Login_usuario.css">
</head>
<body>
    <?php include __DIR__ . '/../partials/alert.php'; ?>
    <div class="book-container" style="display: flex; justify-content: center; align-items: center; min-height: 100vh;">
        <div class="book open">
            <div class="page page-login" style="display: block;">
                <form action="<?= BASE_URL ?>index.php?controller=PasswordReset&action=verifyCode" method="POST">
                    <h2>Verificar Código</h2>
                    <p>Ingresa el código de 6 dígitos que enviamos a tu correo electrónico.</p>
                    
                    <!-- Campo oculto para enviar el correo -->
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email ?? $_GET['email'] ?? '') ?>">

                    <div class="input-group">
                        <label for="code">Código de Verificación</label>
                        <input type="text" name="code" id="code" required pattern="\d{6}" title="Debe ser un código de 6 dígitos" maxlength="6" inputmode="numeric">
                    </div>

                    <button type="submit">Verificar y Continuar</button>

                    <div class="extra">
                        <a href="<?= BASE_URL ?>index.php?controller=PasswordReset&action=request">← Volver a solicitar código</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
