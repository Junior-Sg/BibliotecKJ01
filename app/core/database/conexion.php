<?php

// Cargar configuración de la base de datos
$config = require __DIR__ . "/../../config/database.php";

// Crear conexión MySQLi
$conexion = new mysqli(
    $config['host'],
    $config['user'],
    $config['pass'],
    $config['dbname']
);

// Verificar errores de conexión
if ($conexion->connect_error) {
    die("❌ Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Establecer charset
$conexion->set_charset($config['charset']);

?>

