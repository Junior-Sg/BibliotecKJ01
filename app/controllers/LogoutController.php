<?php
session_start();

// Vaciar todas las variables de sesión
$_SESSION = array();

// Si se están usando cookies para la sesión, eliminar la cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'],
        $params['secure'], $params['httponly']
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al formulario de login con un mensaje opcional
header("Location: ../views/auth/Login_usuario.php?message=Sesión cerrada");
exit;
