<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/helpers.php';

class UsuarioController {
    private $model;
    private $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }
    

// método perfil
public function perfil() {
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: ' . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . '/index.php?controller=LoginUsuario&action=form');
        exit;
    }
    $id = (int)$_SESSION['id_usuario'];

    // Obtener datos necesarios para la vista
    $usuario   = $this->model->obtenerPorId($id);
    $favoritos = $this->model->obtenerFavoritos($id);   // mysqli_result o false
    $reservas  = $this->model->obtenerReservas($id);    // mysqli_result o false

    render_view('usuario/perfil', [
        'usuario'   => $usuario,
        'favoritos' => $favoritos,
        'reservas'  => $reservas
    ]);
}

// método actualizar perfil
public function actualizar()
{
    if (!isset($_SESSION['id_usuario'])) {
        http_response_code(403);
        echo "Acceso no autorizado";
        exit;
    }

    $id = (int)$_SESSION['id_usuario'];
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');

    // validaciones básicas
    if (empty($nombre) || empty($correo)) {
        $_SESSION['flash_error'] = "Nombre y correo son requeridos.";
        header("Location: " . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . "/index.php?controller=Usuario&action=perfil");
        exit;
    }

    $ok = $this->model->actualizarPerfil($id, $nombre, $correo, $telefono, $direccion);

    if ($ok) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['correo'] = $correo;
        $_SESSION['flash_ok'] = "Perfil actualizado.";
    } else {
        $_SESSION['flash_error'] = "No se pudo actualizar perfil.";
    }

    header("Location: " . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . "/index.php?controller=Usuario&action=perfil");
    exit;
}

    public function elegirEmoji()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['ok'=>false,'error'=>'No autenticado']);
            exit;
        }
        $emoji = $_POST['emoji'] ?? '';
        $id = (int)$_SESSION['id_usuario'];
        if (empty($emoji)) {
            echo json_encode(['ok'=>false,'error'=>'Emoji inválido']);
            exit;
        }

        $ok = $this->model->setAvatarEmoji($id, $emoji);
        if ($ok) {
            $_SESSION['avatar_emoji'] = $emoji;
            echo json_encode(['ok'=>true, 'emoji'=>$emoji]);
            exit;
        } else {
            echo json_encode(['ok'=>false, 'error'=>'No se pudo guardar']);
            exit;
        }
    }

    // ruta AJAX para cancelar reserva desde perfil
    public function cancelarReservaAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['ok'=>false,'error'=>'No autenticado']);
            exit;
        }
        $idReserva = (int)($_POST['id_reserva'] ?? 0);
        if ($idReserva <= 0) {
            echo json_encode(['ok'=>false,'error'=>'Reserva inválida']);
            exit;
        }
        $idUsuario = (int)$_SESSION['id_usuario'];
        $ok = $this->model->cancelarReserva($idReserva, $idUsuario);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }
}