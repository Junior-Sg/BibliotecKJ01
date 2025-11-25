<?php
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

$db = (new Conexion())->conectar();
$model = new Usuario($db);

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$clave  = trim($_POST["clave"] ?? "");
$telefono = trim($_POST["telefono"] ?? "");
$tipo_documento = trim($_POST["tipo_documento"] ?? "");
$numero_documento = trim($_POST["numero_documento"] ?? "");

if (empty($nombre) || empty($correo) || empty($clave) || empty($tipo_documento) || empty($numero_documento)) {
    header("Location: ../views/auth/Login_usuario.php?error=Datos incompletos");
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../views/auth/Login_usuario.php?error=Correo inválido");
    exit;
}

// Validar que el nombre contenga solo letras y espacios (soporte Unicode)
if (!preg_match('/^[\p{L}\s]+$/u', $nombre)) {
    header("Location: ../views/auth/Login_usuario.php?error=El nombre solo puede contener letras y espacios");
    exit;
}

// Validar tipo de documento
$allowedTipos = ['CC','TI','CE'];
if (!in_array($tipo_documento, $allowedTipos)) {
    header("Location: ../views/auth/Login_usuario.php?error=Tipo de documento inválido");
    exit;
}

// Validar numero_documento (solo dígitos)
if (!ctype_digit($numero_documento)) {
    header("Location: ../views/auth/Login_usuario.php?error=Número de documento inválido");
    exit;
}

// Validar teléfono (opcional pero si viene, que tenga formato simple)
if (!empty($telefono) && !preg_match('/^[0-9+\-\s]{7,20}$/', $telefono)) {
    header("Location: ../views/auth/Login_usuario.php?error=Teléfono inválido");
    exit;
}

$id_usuario = $model->registrar($nombre, $correo, $clave, $telefono, $tipo_documento, $numero_documento);

if (!$id_usuario) {
    // Intenta obtener detalle del error de la conexión (solo para desarrollo)
    $dbError = $db->error ?? '';
    $msg = 'No se pudo registrar';
    if (!empty($dbError)) $msg .= ': ' . $dbError;
    header("Location: ../views/auth/Login_usuario.php?error=" . urlencode($msg));
    exit;
}

// ASIGNAR ROL 2 (cliente)
$model->asignarRol($id_usuario, 2);

header("Location: ../views/auth/Login_usuario.php?msg=Registro exitoso");
exit;
