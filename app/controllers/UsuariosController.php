<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuariosController {

    private $model;
    private $db;

    public function __construct() {
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

        if (empty($nombre) || empty($correo)) {
            $this->redirigirConError('Datos incompletos');
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $this->redirigirConError('Correo inválido');
        }

        // Contraseña por defecto mínima
        $defaultPass = '123456';
        $id = $this->model->crearDesdeAdmin($nombre, $correo, $defaultPass, $telefono, $tipo_documento, $numero_documento);

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

        if ($id <= 0) {
            $this->redirigirConError('ID de usuario inválido');
        }

        $ok = $this->model->actualizarUsuario($id, $nombre, $correo, $telefono, $tipo_documento, $numero_documento);
        if (!$ok) {
            $this->redirigirConError('No se pudo actualizar el usuario');
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
        header('Location: ' . BASE_URL . 'Usuarios/index?msg=' . urlencode($mensaje));
        exit;
    }

    private function redirigirConError($mensaje) {
        header('Location: ' . BASE_URL . 'Usuarios/index?error=' . urlencode($mensaje));
        exit;
    }
}

// Esta parte es el "router" antiguo. Lo eliminamos para que solo el index.php principal controle todo.
/*
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$controller = new UsuariosController();

switch ($action) {
    case 'guardar':
        $controller->guardar();
        break;
    case 'actualizar':
        $controller->actualizar();
        break;
    case 'eliminar':
        $controller->eliminar();
        break;
    default:
        // Si no hay acción, muestra la lista de usuarios
        if (empty($action)) {
            $controller->index();
        } else {
            header('Location: /BibliotecKJ01/index.php?c=Usuarios&a=index&error=Accion_no_valida');
            exit;
        }
        break;
}
*/
