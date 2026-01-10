<?php
class BaseController {

        protected array $publicRoutes = [
        'LoginUsuario/index', // Vista de inicio de sesión
        'LoginUsuario/login', // Procesamiento del formulario de inicio de sesión
        'RegistrarUsuario/registrar', // Procesamiento del formulario de registro
        'InicioPagina/index' // Página de inicio pública
    ];

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $currentController = $_GET['controller'] ?? $_GET['Controller'] ?? $_GET['c'] ?? 'InicioPagina';
        $currentAction = $_GET['action'] ?? $_GET['Action'] ?? $_GET['a'] ?? 'index';
        $currentRoute = $currentController . '/' . $currentAction;

        if (!$this->isLoggedIn() && !in_array($currentRoute, $this->publicRoutes)) {
            // Si no está logueado y la ruta no es pública, redirigir al login
            $this->redirect('LoginUsuario', 'index');
        } else if ($this->isLoggedIn() && !in_array($currentRoute, $this->publicRoutes)) {
            // Si está logueado y la ruta no es pública (es decir, es una ruta protegida), prevenir caching
            $this->preventCaching();
        }
    }

    protected function isLoggedIn() {
        return isset($_SESSION['id_usuario']);
    }

    protected function isAdmin() {
        return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
    }

    protected function redirect($controller, $action = 'index', $params = '') {
        $baseUrl = rtrim(BASE_URL, '/');
        header("Location: {$baseUrl}/index.php?controller={$controller}&action={$action}{$params}");
        exit;
    }

    protected function preventCaching() {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    }
}
