<?php

require_once __DIR__ . '/../core/BaseController.php';

class LogoutController extends BaseController {

    public function index() {
        parent::__construct(); // Ensure BaseController's constructor is called to start session and prevent caching

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
        
        // 4. Redirigir a la página de inicio pública con un mensaje de éxito.
        $this->redirect('InicioPagina', 'index', '&msg=logout_success');
    }
}