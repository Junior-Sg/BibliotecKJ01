<?php
session_start();
require_once __DIR__ . '/../config/conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class LoginControlador {
    private $conexion;
    private $modeloUsuario;

    public function __construct() {
        $db = new Conexion();
        $this->conexion = $db->conectar();
        $this->modeloUsuario = new Usuario($this->conexion);
    }

    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $correo = trim($_POST['correo'] ?? '');
        $contraseña = $_POST['contraseña'] ?? '';

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->redirigirConMensaje('Correo inválido.', '/public/views/login.php');
        }

        $usuario = $this->modeloUsuario->buscarPorCorreo($correo);

        if (!$usuario || !password_verify($contraseña, $usuario['contraseña'])) {
            $this->redirigirConMensaje('Correo o contraseña incorrectos.', '/public/views/login.php');
        }

        session_regenerate_id(true);
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];

        header('Location: /index.php');
        exit;
    }

    private function redirigirConMensaje($mensaje, $ruta) {
        $_SESSION['mensaje'] = $mensaje;
        header("Location: $ruta");
        exit;
    }

    public function cerrarSesion() {
        session_unset();
        session_destroy();
        header('Location: /public/views/login.php');
        exit;
    }
}

$controlador = new LoginControlador();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador->autenticar();
}
