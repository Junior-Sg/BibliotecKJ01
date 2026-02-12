<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController extends BaseController {

    private $model;
    private $db;

    public function __construct() {
        parent::__construct();
        if (!$this->isAdmin()) {
            $this->redirect('LoginUsuario', 'index');
        }

        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }

    public function index() {
        // 1. Controller gets all request data
        $msg = $_GET['msg'] ?? null;
        $error = $_GET['error'] ?? null;

        // 2. Controller gets data from model and prepares it for the view
        $usuariosResult = $this->model->getAllUsuarios();
        $usuarios = [];
        if ($usuariosResult && $usuariosResult->num_rows > 0) {
            $usuarios = $usuariosResult->fetch_all(MYSQLI_ASSOC);
        }

        // 3. Controller loads the view, passing the data
        require_once __DIR__ . '/../views/ADMIN/GestionUsuarios.php';
    }

    public function guardar() {
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $tipo_documento = trim($_POST['tipo_documento'] ?? '');
        $numero_documento = trim($_POST['numero_documento'] ?? '');
        $rol = intval($_POST['rol'] ?? 2);
        $clave = trim($_POST['clave'] ?? '');

        if (empty($nombre) || empty($correo) || empty($clave)) {
            $this->redirigirConError('Datos incompletos, nombre, correo y clave son requeridos.');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->redirigirConError('Correo inválido');
        }

        $id = $this->model->crearDesdeAdmin($nombre, $correo, $clave, $telefono, $tipo_documento, $numero_documento);

        if (!$id) {
            $this->redirigirConError('No se pudo crear el usuario: ' . ($this->db->error ?? 'Error desconocido'));
        }

        $this->model->asignarRol($id, $rol);
        $this->redirigirConExito('Usuario creado correctamente');
    }

    public function actualizar() {
        $id = intval($_POST['id_usuario'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $tipo_documento = trim($_POST['tipo_documento'] ?? '');
        $numero_documento = trim($_POST['numero_documento'] ?? '');
        $rol = intval($_POST['rol'] ?? 2);
        $clave = trim($_POST['clave'] ?? '');

        if ($id <= 0) {
            $this->redirigirConError('ID de usuario inválido');
        }

        $ok = $this->model->actualizarUsuario($id, $nombre, $correo, $telefono, $tipo_documento, $numero_documento);
        if (!$ok) {
            $this->redirigirConError('No se pudo actualizar el usuario');
        }

        // Si se proveyó una clave nueva, actualizarla
        if (!empty($clave)) {
            $this->model->actualizarClave($id, $clave);
        }

        $this->model->actualizarRol($id, $rol);
        $this->redirigirConExito('Usuario actualizado correctamente');
    }

    public function eliminar() {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->redirigirConError('ID de usuario inválido');
        }

        // Verificar si el usuario existe antes de intentar eliminar
        $existsRes = $this->db->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
        if ($existsRes) {
            $existsRes->bind_param('i', $id);
            $existsRes->execute();
            $r = $existsRes->get_result();
            if ($r->num_rows === 0) {
                $this->redirigirConError('El usuario no existe');
            }
        }

        $ok = $this->model->eliminarUsuario($id);
        if ($ok) {
            $this->redirigirConExito('Usuario eliminado correctamente');
        } else {
            $this->redirigirConError('No se pudo eliminar el usuario: ' . ($this->db->error ?? 'Error desconocido'));
        }
    }

    // Funciones de ayuda para no repetir código
    private function redirigirConExito($mensaje) {
        header('Location: index.php?controller=Usuarios&action=index&msg_success=' . urlencode($mensaje));
        exit;
    }

    private function redirigirConError($mensaje) {
        header('Location: index.php?controller=Usuarios&action=index&msg_error=' . urlencode($mensaje));
        exit;
    }
}


