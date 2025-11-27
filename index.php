<?php

// Define a constant for the base URL if your application is in a subfolder
// This should be adjusted based on your server configuration.
// For XAMPP with htdocs/BibliotecKJ01, it would be '/BibliotecKJ01/'.
// If your application is in the root (e.g., htdocs/), then define it as '/'.
define('BASE_URL', '/BibliotecKJ01/');

// 1. Obtener el controlador, la acción y los parámetros de la URL
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// Remove base URL and trim slashes
$requestUri = str_replace(BASE_URL, '', $requestUri);
$requestUri = trim($requestUri, '/');

$segments = explode('/', $requestUri);

// If the first segment is 'index.php', remove it or treat it as empty
if (isset($segments[0]) && strtolower($segments[0]) === 'index.php') {
    array_shift($segments); // Remove 'index.php'
}

$controladorNombre = array_shift($segments);
if (empty($controladorNombre)) {
    $controladorNombre = 'Inicio';
}
$accion = array_shift($segments) ?? 'index';           // Second segment is action, default to 'index'
$params = $segments; // Remaining segments are parameters

// 2. Formatear el nombre del archivo y de la clase del controlador
$controladorNombre = ucfirst(strtolower($controladorNombre)); // Ej: 'prestamo' -> 'Prestamo'
$ficheroControlador = __DIR__ . '/app/controllers/' . $controladorNombre . 'Controller.php';
$claseControlador = $controladorNombre . 'Controller';

// 3. Verificar si el archivo del controlador existe
if (!file_exists($ficheroControlador)) {
    // Optionally, redirect to a 404 page or default controller
    $errorMessage = "Error: No se pudo encontrar el controlador: " . $controladorNombre;
    header("Location: " . BASE_URL . "?error=" . urlencode($errorMessage));
    exit();
}

// 4. Cargar el controlador
require_once $ficheroControlador;

// 5. Verificar si la clase del controlador existe
if (!class_exists($claseControlador)) {
    $errorMessage = "Error: No se pudo encontrar la clase del controlador: " . $claseControlador;
    header("Location: " . BASE_URL . "?error=" . urlencode($errorMessage));
    exit();
}

// 6. Crear una instancia del controlador
$controlador = new $claseControlador();

// 7. Verificar si el método (acción) existe en el controlador
if (!method_exists($controlador, $accion)) {
    // Optionally, redirect to a 404 page or default action
    $errorMessage = "Error: La acción '" . $accion . "' no existe en el controlador '" . $claseControlador . "'.";
    header("Location: " . BASE_URL . "?error=" . urlencode($errorMessage));
    exit();
}

// 8. Llamar a la acción con los parámetros
call_user_func_array([$controlador, $accion], $params);
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
