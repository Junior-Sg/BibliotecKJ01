<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class LoginUsuarioController {

    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }

    /**
     * Muestra la página de login/registro.
     */
    public function index() {
        // Redirige al inicio si el usuario ya está logueado
        if (isset($_SESSION['id_usuario'])) {
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
            header("Location: " . BASE_URL . "LoginUsuario?error=Datos incompletos");
            exit;
        }

        $data = $this->model->login($correo, $clave);

        if (!$data) {
            header("Location: " . BASE_URL . "LoginUsuario?error=Correo o contraseña incorrectos");
            exit;
        }

        $_SESSION["id_usuario"] = $data["id_usuario"];
        $_SESSION["nombre"]     = $data["nombre"];
        $_SESSION["correo"]     = $data["correo"];

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
                header("Location: /BibliotecKJ01/index.php?c=Inicio&a=index");
                break;
            case 2: // Cliente - Asumiendo que tendrá su propio controlador a futuro
                // De momento, podría redirigir a una página genérica o de vuelta al login con un mensaje.
                // Por ahora, para mantener consistencia, redirigimos a una acción de logout si el destino no está claro.
                header("Location: /BibliotecKJ01/index.php?c=Logout&a=index&error=No_disponible_para_clientes");
                break;
            default: // Rol no reconocido o sin rol
                header("Location: /BibliotecKJ01/index.php?c=LoginUsuario&a=index&error=Rol no asignado");
        }
        exit;
    }
}
