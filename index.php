<?php

// Iniciar sesión
session_start();

// Conexión a la base de datos
require_once "./config/conexion.php";

// Obtener el controlador y la acción desde la URL
$controlador = $_GET["controller"] ?? "Libro";
$accion = $_GET["action"] ?? "index";

// Crear el nombre completo del controlador
$nombreControlador = $controlador . "Controller";

// Ruta del archivo del controlador
$rutaControlador = "./app/controllers/" . $nombreControlador . ".php";

// Verificar si el archivo del controlador existe
if (!file_exists($rutaControlador)) {
    die("Controlador no encontrado: " . $nombreControlador);
}

// Cargar controlador
require_once $rutaControlador;

// Crear instancia del controlador
$instancia = new $nombreControlador();

// Ejecutar acción
if (method_exists($instancia, $accion)) {

    // Si hay ID, lo pasamos
    if (isset($_GET["id"])) {
        $instancia->$accion($_GET["id"]);
    } else {
        $instancia->$accion();
    }

} else {
    echo "Acción no encontrada: " . $accion;
}

?>
