<?php
/**
 * Configuración de la aplicación
 * Maneja automáticamente la URL base según el entorno
 */

// Detectar automáticamente el protocolo
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
            (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) 
            ? 'https' : 'http';

// Obtener el host (dominio)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Obtener la ruta base automáticamente desde REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
// Eliminar parámetros de query string
$requestPath = strtok($requestUri, '?');
// Obtener el directorio base de la aplicación
$scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
// Normalizar barras
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName === '/') {
    $scriptName = '';
}

// Construir BASE_URL automáticamente
define('BASE_URL', $protocol . '://' . $host . $scriptName . '/');

// Rutas del proyecto
define('APP_PATH', __DIR__ . '/app');
define('PUBLIC_PATH', __DIR__ . '/public');

// Configuración de la aplicación
define('APP_NAME', 'Bibliotec_KJ');
define('APP_VERSION', '1.0.0');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', $protocol === 'https');
ini_set('session.use_strict_mode', 1);

// Configuración de zona horaria
date_default_timezone_set('America/Bogota');
