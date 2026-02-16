<?php

/**
 * SessionGuard se encarga de proteger las rutas que requieren autenticación.
 *
 * - Inicia la sesión de forma segura.
 * - Envía cabeceras para prevenir el almacenamiento en caché del navegador.
 * - Verifica si existe una sesión de usuario activa.
 * - Si no hay sesión, redirige a la página de login y termina la ejecución.
 */
class SessionGuard {

    /**
     * Verifica la sesión del usuario. Si no está autenticado, lo redirige.
     *
     * @param string $role El rol requerido para acceder a la página (ej. 'admin', 'usuario').
     *                     Por ahora, solo verificamos si hay una sesión activa.
     */
    public static function check($role = 'admin') {
        // Iniciar la sesión si no está ya iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Enviar cabeceras para deshabilitar el caché del navegador
        // Esto evita que se pueda volver a una página privada con el botón "atrás" tras cerrar sesión.
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: 0");

        // 2. Verificar si el usuario está autenticado
        // Asumimos que guardas el ID del usuario en $_SESSION['id_usuario'] al hacer login.
        if (!isset($_SESSION['id_usuario'])) {
            // 3. Si no hay sesión, redirigir al login
            header('Location: ' . BASE_URL . 'index.php?c=LoginUsuario&a=index');
            exit(); // Detener la ejecución del script para evitar que se muestre contenido protegido.
        }
    }
}