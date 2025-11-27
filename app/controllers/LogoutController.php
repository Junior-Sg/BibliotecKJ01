<?php

class LogoutController {

    public function index() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // 1. Vaciar todas las variables de sesión
        $_SESSION = array();

        // 2. Si se están usando cookies para la sesión, se recomienda eliminarlas también
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // 3. Destruir la sesión finalmente
        session_destroy();
        
        // Determinar el mensaje de redirección
        $message = "Sesión cerrada correctamente";
        if (isset($_GET['error'])) {
            $message = "Error: " . htmlspecialchars($_GET['error']);
        }

        // 4. Redirigir al login
        header("Location: /BibliotecKJ01/index.php?c=LoginUsuario&a=index&msg=" . urlencode($message));
        exit;
    }
}