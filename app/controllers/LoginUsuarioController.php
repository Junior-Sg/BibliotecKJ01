<?php
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

$db = (new Conexion())->conectar();
$model = new Usuario($db);

$correo = trim($_POST["correo"] ?? "");
$clave  = trim($_POST["clave"] ?? "");

if (empty($correo) || empty($clave)) {
    header("Location: ../views/auth/Login_usuario.php?error=Datos incompletos");
    exit;
}

$data = $model->login($correo, $clave);

if (!$data) {
    header("Location: ../views/auth/Login_usuario.php?error=Correo o contraseña incorrectos");
    exit;
}

$_SESSION["id_usuario"] = $data["id_usuario"];
$_SESSION["nombre"]     = $data["nombre"];
$_SESSION["correo"]     = $data["correo"];

$rol = $model->obtenerRol($data["id_usuario"]);
$_SESSION["rol"] = $rol;

switch ($rol) {
    case 1:
        header("Location: ../views/ADMIN/InicioADM.php");
        break;
    case 2:
        header("Location: ../views/CLIENTE/InicioCliente.php");
        break;
    default:
        header("Location: ../views/auth/Login_usuario.php?error=Rol no asignado");
}
exit;
