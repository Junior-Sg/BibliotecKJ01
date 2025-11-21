<?php
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

$db = (new Conexion())->conectar();
$model = new Usuario($db);

$nombre = trim($_POST["nombre"] ?? "");
$correo = trim($_POST["correo"] ?? "");
$clave  = trim($_POST["clave"] ?? "");

if (empty($nombre) || empty($correo) || empty($clave)) {
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

$id_usuario = $model->registrar($nombre, $correo, $clave);

if (!$id_usuario) {
    header("Location: ../views/auth/Login_usuario.php?error=No se pudo registrar");
    exit;
}

// ASIGNAR ROL 2 (cliente)
$model->asignarRol($id_usuario, 2);

header("Location: ../views/auth/Login_usuario.php?msg=Registro exitoso");
exit;
