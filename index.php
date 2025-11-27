<?php

// ===============================
// CONFIGURACIÓN GLOBAL
// ===============================
define('BASE_URL', '/BibliotecKJ01'); 
define('APP_PATH', __DIR__ . '/app');

// ===============================
// AUTOLOAD BÁSICO
// ===============================
spl_autoload_register(function ($class) {

    $paths = [
        APP_PATH . "/controllers/$class.php",
        APP_PATH . "/models/$class.php",
        __DIR__ . "/config/$class.php",
        APP_PATH . "/core/$class.php"
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Helpers
require_once APP_PATH . '/core/helpers.php';

// ===============================
// CAPTURA DE PARÁMETROS DE RUTA
// ===============================
$controllerName = $_GET['controller'] ?? 'Libro';
$action = $_GET['action'] ?? 'index';

// Normalizar nombre de clase
$controllerClass = ucfirst($controllerName) . 'Controller';

// Archivo del controlador
$controllerFile = APP_PATH . "/controllers/{$controllerClass}.php";

// ===============================
// VALIDAR CONTROLADOR
// ===============================
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "<h2>❌ Controlador no encontrado: $controllerClass</h2>";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "<h2>❌ La clase del controlador no existe: $controllerClass</h2>";
    exit;
}

// Crear instancia del controlador
$controller = new $controllerClass();

// ===============================
// VALIDAR MÉTODO
// ===============================
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "<h2>❌ Acción no encontrada: $action</h2>";
    exit;
}

// ===============================
// PREPARAR PARÁMETROS
// ===============================
$params = $_REQUEST;
unset($params['controller'], $params['action']);

// ===============================
// EJECUTAR ACCIÓN
// ===============================
call_user_func_array([$controller, $action], $params);
