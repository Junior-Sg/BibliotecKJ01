<?php
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

$db = (new Conexion())->conectar();
$model = new Usuario($db);

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'guardar') {
    // Guardar nuevo usuario desde administrador
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_documento = trim($_POST['tipo_documento'] ?? '');
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $rol = intval($_POST['rol'] ?? 2);

    if (empty($nombre) || empty($correo)) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Datos incompletos'));
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Correo inválido'));
        exit;
    }

    // Validar teléfono: solo dígitos y máximo 10
    if (!empty($telefono)) {
        if (!ctype_digit($telefono) || strlen($telefono) > 10) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Teléfono inválido: solo números, máximo 10 dígitos'));
            exit;
        }
    }

    // Validar número de documento: solo dígitos (longitud variable)
    if (!empty($numero_documento)) {
        if (!ctype_digit($numero_documento)) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Número de documento inválido: solo números'));
            exit;
        }
    }

    // Validar tipo de documento
    $allowedTipos = ['CC','TI','CE'];
    if (!in_array($tipo_documento, $allowedTipos)) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Tipo de documento inválido'));
        exit;
    }

    // Contraseña por defecto mínima; se puede forzar cambio
    $defaultPass = '123456';
    $id = $model->crearDesdeAdmin($nombre, $correo, $defaultPass, $telefono, $tipo_documento, $numero_documento);

    if (!$id) {
        $dbError = $db->error ?? '';
        $msg = 'No se pudo crear usuario';
        if (!empty($dbError)) $msg .= ': ' . $dbError;
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode($msg));
        exit;
    }

    // Asignar rol
    $model->asignarRol($id, $rol);

    header('Location: ../views/ADMIN/GestionUsuarios.php?msg=' . urlencode('Usuario creado'));
    exit;

} elseif ($action === 'actualizar') {
    $id = intval($_POST['id_usuario'] ?? 0);
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_documento = trim($_POST['tipo_documento'] ?? '');
    $numero_documento = trim($_POST['numero_documento'] ?? '');
    $rol = intval($_POST['rol'] ?? 2);

    if ($id <= 0) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('ID inválido'));
        exit;
    }

    // Validar teléfono y documento como en crear
    if (!empty($telefono)) {
        if (!ctype_digit($telefono) || strlen($telefono) > 10) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Teléfono inválido: solo números, máximo 10 dígitos'));
            exit;
        }
    }
    if (!empty($numero_documento)) {
        if (!ctype_digit($numero_documento)) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Número de documento inválido: solo números'));
            exit;
        }
    }
    $allowedTipos = ['CC','TI','CE'];
    if (!in_array($tipo_documento, $allowedTipos)) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Tipo de documento inválido'));
        exit;
    }

    $ok = $model->actualizarUsuario($id, $nombre, $correo, $telefono, $tipo_documento, $numero_documento);
    if (!$ok) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('No se pudo actualizar'));
        exit;
    }

    // actualizar rol
    $model->actualizarRol($id, $rol);

    header('Location: ../views/ADMIN/GestionUsuarios.php?msg=' . urlencode('Usuario actualizado'));
    exit;

} elseif ($action === 'eliminar') {
    // aceptar id por POST o GET
    $id = 0;
    if (!empty($_POST['id'])) $id = intval($_POST['id']);
    elseif (!empty($_GET['id'])) $id = intval($_GET['id']);
    if ($id <= 0) {
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('ID inválido'));
        exit;
    }
    // Verificar existencia antes
    $existsRes = $db->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
    if ($existsRes) {
        $existsRes->bind_param('i', $id);
        $existsRes->execute();
        $r = $existsRes->get_result();
        if ($r->num_rows === 0) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('Usuario no encontrado'));
            exit;
        }
    }

    // Intentar eliminar y comprobar filas afectadas
    $ok = $model->eliminarUsuario($id);
    if (!$ok) {
        $dbError = $db->error ?? '';
        error_log("Error eliminar usuario (id={$id}): " . $dbError);
        $msg = 'No se pudo eliminar';
        if (!empty($dbError)) $msg .= ': ' . $dbError;
        header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode($msg));
        exit;
    }

    // Comprobar que ya no existe
    $check = $db->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
    if ($check) {
        $check->bind_param('i', $id);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows === 0) {
            header('Location: ../views/ADMIN/GestionUsuarios.php?msg=' . urlencode('Usuario eliminado'));
            exit;
        } else {
            header('Location: ../views/ADMIN/GestionUsuarios.php?error=' . urlencode('No se eliminó el usuario'));
            exit;
        }
    }

    header('Location: ../views/ADMIN/GestionUsuarios.php?msg=' . urlencode('Usuario eliminado'));
    exit;

} else {
    // Acción por defecto: mostrar vista (si se accede directamente)
    $usuarios = $model->getAllUsuarios();
    include __DIR__ . '/../views/ADMIN/GestionUsuarios.php';
}

?>
