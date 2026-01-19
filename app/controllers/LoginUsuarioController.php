<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class LoginUsuarioController extends BaseController {

    private $model;
    private $db;

    public function __construct() {
        parent::__construct(); // Call the parent constructor
        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }

    /**
     * Muestra la página de login/registro.
     */
    public function index() {
        // Redirige al inicio si el usuario ya está logueado
        // (Aunque BaseController ya hace una redirección similar, esta es más específica por rol)
        if ($this->isLoggedIn()) {
            $this->redirigirPorRol($_SESSION['rol'] ?? null);
        }
        require_once __DIR__ . '/../views/auth/Login_usuario.php';
    }

    /**
     * Procesa la petición de login.
     */
    public function login() {
        $correo = trim($_POST["correo"] ?? "");
        $clave  = trim($_POST["clave"] ?? "");

        if (empty($correo) || empty($clave)) {
            $this->redirect('LoginUsuario', 'index', '&error=Datos incompletos');
            exit;
        }

        // Validación de formato de correo electrónico
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('LoginUsuario', 'index', '&error=Formato de correo electrónico inválido');
            exit;
        }

        $data = $this->model->login($correo, $clave);

        if (!$data) {
            $this->redirect('LoginUsuario', 'index', '&error=Correo o contraseña incorrectos');
            exit;
        }

        $_SESSION["id_usuario"] = $data["id_usuario"];
        $_SESSION["nombre"]     = $data["nombre"];
        $_SESSION["correo"]     = $data["correo"];
        $_SESSION["avatar_emoji"] = $data["avatar_emoji"];

        $rol = $this->model->obtenerRol($data["id_usuario"]);
        $_SESSION["rol"] = $rol;

        $this->redirigirPorRol($rol);
    }
    
    /**
     * Redirige al usuario según su rol.
     */
    private function redirigirPorRol($rol) {
        switch ($rol) {
            case 1: // Admin
                $this->redirect('Inicio', 'index');
                break;
            case 2: // Cliente
                $this->redirect('Libro', 'index');
                break;
            default: // Rol no reconocido o sin rol
                $this->redirect('LoginUsuario', 'index', '&error=Rol no asignado');
        }
    }

    // Override del método redirect para añadir parámetros extra si es necesario
    protected function redirect($controller, $action = 'index', $params = '') {
        $baseUrl = rtrim(BASE_URL, '/');
        header("Location: {$baseUrl}/index.php?controller={$controller}&action={$action}{$params}");
        exit;
    }
}
