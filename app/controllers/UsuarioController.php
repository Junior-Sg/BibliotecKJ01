<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/NotificacionModelo.php';
require_once __DIR__ . '/../core/helpers.php';

class UsuarioController extends BaseController {
    private $model;
    private $db;

    public function __construct() {
        parent::__construct();
        if (!$this->isLoggedIn()) {
            $this->redirect('LoginUsuario', 'index');
        }

        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }

    public function perfil() {
        $id = (int)$_SESSION['id_usuario'];

        $prestamoModelo   = new PrestamoModelo($this->db);
        $historialLectura = $prestamoModelo->obtenerHistorialDeLectura($id);
        $prestamosActivos = $prestamoModelo->obtenerPrestamosActivosPorUsuario($id);

        $usuario         = $this->model->obtenerPorId($id);
        $favoritos       = $this->model->obtenerFavoritos($id);
        $reservas        = $this->model->obtenerReservas($id);
        $favoritosIds    = $this->model->obtenerFavoritosIds($id);
        
        $notificacionModelo = new NotificacionModelo($this->db);
        $notificaciones     = $notificacionModelo->obtenerPorUsuario($id);
        $totalSinLeer       = $notificacionModelo->contarNoLeidas($id);

        render_view('usuario/perfil', [
            'usuario'           => $usuario,
            'favoritos'         => $favoritos,
            'favoritos_ids'     => $favoritosIds,
            'reservas'          => $reservas,
            'historialLectura'  => $historialLectura,
            'prestamosActivos'  => $prestamosActivos,
            'notificaciones'    => $notificaciones,
            'totalSinLeer'      => $totalSinLeer
        ]);
    }

    public function actualizar()
    {
        $id       = (int)$_SESSION['id_usuario'];
        $nombre   = trim($_POST['nombre'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        if (empty($nombre) || empty($correo)) {
            $_SESSION['flash_error'] = "Nombre y correo son requeridos.";
            header("Location: " . (defined('BASE_URL') ? rtrim(BASE_URL, '/') : '') . "/index.php?controller=Usuario&action=perfil");
            exit;
        }

        $ok = $this->model->actualizarPerfil($id, $nombre, $correo, $telefono);

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
        
        $emoji = $_POST['emoji'] ?? '';
        $id    = (int)$_SESSION['id_usuario'];

        if (empty($emoji)) {
            echo json_encode(['ok'=>false,'error'=>'Emoji inválido']);
            exit;
        }

        $ok = $this->model->setAvatarEmoji($id, $emoji);
        if ($ok) {
            $_SESSION['avatar_emoji'] = $emoji;
            echo json_encode(['ok'=>true, 'emoji'=>$emoji]);
        } else {
            echo json_encode(['ok'=>false, 'error'=>'No se pudo guardar']);
        }
        exit;
    }

    public function cancelarReservaAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        
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
    
    public function agregarFavoritoAjax()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!isset($_SESSION['id_usuario'])) {
                echo json_encode(['ok' => false, 'error' => 'Sesión no válida']);
                exit;
            }

            $idLibro = (int)($_POST['id_libro'] ?? 0);
            if ($idLibro <= 0) {
                echo json_encode(['ok' => false, 'error' => 'ID de libro inválido']);
                exit;
            }

            $idUsuario = (int)$_SESSION['id_usuario'];
            $ok = $this->model->agregarFavorito($idUsuario, $idLibro);

            echo json_encode(['ok' => (bool)$ok]);
            exit;
        } catch (\Throwable $e) {
            error_log("agregarFavoritoAjax: " . $e->getMessage());
            echo json_encode(['ok' => false, 'error' => 'Error interno']);
            exit;
        }
    }
    
    public function eliminarFavoritoAjax() {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['ok' => false, 'error' => 'Sesión no iniciada']);
            exit;
        }
            
        $idLibro = (int)($_POST['id_libro'] ?? 0);
        $idUsuario = (int)$_SESSION['id_usuario'];
        
        if ($idLibro > 0) {
            $ok = $this->model->eliminarFavorito($idUsuario, $idLibro);
            echo json_encode(['ok' => (bool)$ok]);
        } else {
            echo json_encode(['ok' => false, 'error' => 'ID de libro inválido']);
        }
        exit;
    }
                
    public function marcarLeidaAjax() {
        header('Content-Type: application/json; charset=utf-8');
        $idNotif = (int)($_POST['id_notificacion'] ?? 0);
        $idUser  = (int)$_SESSION['id_usuario'];

        $notificacionModelo = new NotificacionModelo($this->db);
        $ok = $notificacionModelo->marcarComoLeida($idNotif, $idUser);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }

    public function eliminarNotificacionAjax() {
        header('Content-Type: application/json; charset=utf-8');
        $idNotif = (int)($_POST['id_notificacion'] ?? 0);
        $idUser  = (int)$_SESSION['id_usuario'];

        $notificacionModelo = new NotificacionModelo($this->db);
        $ok = $notificacionModelo->eliminarNotificacion($idNotif, $idUser);
        echo json_encode(['ok' => (bool)$ok]);
        exit;
    }
}

?>
