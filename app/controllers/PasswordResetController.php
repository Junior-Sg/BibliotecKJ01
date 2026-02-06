<?php

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/Mailer.php';

// No heredar de BaseController para evitar la verificación de sesión
class PasswordResetController {

    private $model;
    private $db;
    private $mailer;

    // Replicar el método redirect que estaba en BaseController
    protected function redirect($controller, $action, $params = '') {
        $url = BASE_URL . "index.php?controller=$controller&action=$action" . $params;
        header("Location: $url");
        exit();
    }

    public function __construct() {
        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
        $this->mailer = new Mailer();
    }

    /**
     * Muestra el formulario para solicitar el restablecimiento de contraseña.
     */
    public function request() {
        require_once __DIR__ . '/../views/auth/request_password.php';
    }

    /**
     * Procesa la solicitud de restablecimiento de contraseña y envía un código.
     */
    public function sendCode() {
        $correo = trim($_POST["correo"] ?? "");

        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('PasswordReset', 'request', '&error=Correo electrónico inválido.');
        }

        $usuario = $this->model->findByEmail($correo);
        if ($usuario) {
            // Generar un código numérico de 6 dígitos
            $code = random_int(100000, 999999);
            $this->model->createPasswordResetToken($usuario['id_usuario'], $code);

            $subject = "Código de recuperación de contraseña - Bibliotec_KJ";
            $body = "Hola {$usuario['nombre']},<br><br>Has solicitado restablecer tu contraseña. Usa el siguiente código para continuar:<br><br><h2 style='text-align:center; letter-spacing: 4px;'>{$code}</h2><br><br>Este código expirará en 15 minutos.<br><br>Si no solicitaste esto, puedes ignorar este correo.<br><br>Gracias,<br>El equipo de Bibliotec_KJ";

            $resultado = $this->mailer->sendAsync($correo, $subject, $body);


            if ($resultado) {
                $this->redirect('PasswordReset', 'verifyForm', '&email=' . urlencode($correo) . '&msg=Se ha enviado un código a tu correo.');
            } else {
                // Si el correo no se pudo enviar, mostrar un error.
                $this->redirect('PasswordReset', 'request', '&error=No se pudo enviar el correo de recuperación. Por favor, inténtalo de nuevo.');
            }
        } else {
            $this->redirect('PasswordReset', 'request', '&error=No se encontró un usuario con ese correo electrónico.');
        }
    }

    /**
     * Muestra el formulario para ingresar el código de verificación.
     */
    public function verifyForm() {
        $email = $_GET['email'] ?? '';
        if (empty($email)) {
            $this->redirect('PasswordReset', 'request', '&error=Ha ocurrido un error inesperado.');
            exit;
        }
        // Pasar el correo a la vista para que el formulario de verificación sepa a quién verificar
        require_once __DIR__ . '/../views/auth/verify_code.php';
    }

    /**
     * Verifica el código y muestra el formulario para cambiar la contraseña si es válido.
     */
    public function verifyCode() {
        $email = $_POST['email'] ?? '';
        $code = $_POST['code'] ?? '';

        if (empty($email) || empty($code)) {
            $this->redirect('PasswordReset', 'request', '&error=Datos incompletos.');
            exit;
        }

        $user = $this->model->findByResetToken($code);
        $dbUser = $this->model->findByEmail($email);

        // Validar que el token (código) exista, no haya expirado y corresponda al usuario correcto.
        if ($user && $dbUser && $user['id_usuario'] == $dbUser['id_usuario'] && strtotime($user['expires_at']) > time()) {
            // El código es válido, mostrar el formulario para restablecer la contraseña.
            $token = $code; // Pasamos el código como 'token' a la vista
            require_once __DIR__ . '/../views/auth/reset_password.php';
        } else {
            // El código es inválido o ha expirado.
            // En lugar de redirigir, volvemos a cargar la vista con el mensaje de error.
            $error = 'El código es incorrecto o ha expirado.';
            // La vista verify_code.php necesita la variable $email para el campo oculto.
            require_once __DIR__ . '/../views/auth/verify_code.php';
        }
    }

    /**
     * Muestra el formulario para restablecer la contraseña.
     * Esta acción es necesaria para poder redirigir aquí si las contraseñas no coinciden.
     */
    public function reset() {
        // El token puede venir por GET (primer acceso) o por POST (si hay un error de validación)
        $token = $_REQUEST['token'] ?? '';
        if (empty($token)) {
            $this->redirect('LoginUsuario', 'index', '&error=Token no proporcionado.');
            exit;
        }

        // También pasamos el error si existe
        $error = $_GET['error'] ?? null;

        // Pasamos el token y el error a la vista para que el formulario los incluya
        // y se pueda enviar en la acción updatePassword.
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }


    /**
     * Actualiza la contraseña.
     */
    public function updatePassword() {
        $token = $_POST['token'] ?? '';
        $clave = $_POST['clave'] ?? '';
        $clave_confirm = $_POST['clave_confirm'] ?? '';

        if (empty($token) || empty($clave) || empty($clave_confirm)) {
            $this->redirect('LoginUsuario', 'index', '&error=Datos incompletos.');
            exit;
        }

        if (strlen($clave) < 8) {
            $this->redirect('PasswordReset', 'reset', "&token=$token&error=La contraseña debe tener al menos 8 caracteres.");
            exit;
        }

        if ($clave !== $clave_confirm) {
            $this->redirect('PasswordReset', 'reset', "&token=$token&error=Las contraseñas no coinciden.");
            exit;
        }

        $user = $this->model->findByResetToken($token);

        if ($user && strtotime($user['expires_at']) > time()) {
            $this->model->actualizarClave($user['id_usuario'], $clave);
            $this->model->deletePasswordResetToken($token);
            $this->redirect('LoginUsuario', 'index', '&msg=Tu contraseña ha sido actualizada.');
        } else {
            $this->redirect('LoginUsuario', 'index', '&error=El token es inválido o ha expirado.');
        }
    }
}
