<?php
// ...existing code...
if (!function_exists('render_view')) {
    /**
     * Renderiza una vista de app/views/<ruta>.php con $data disponible como variables.
     * Uso: render_view('inicio/InicioPagina', ['nuevos'=>$nuevos]);
     */
    function render_view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Mensaje simple para desarrollo
            echo "View not found: " . htmlspecialchars($viewFile, ENT_QUOTES, 'UTF-8');
        }
    }
}

// helper opcional para construir URLs usando BASE_URL (si está definido)
if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $base = defined('BASE_URL') ? BASE_URL : '';
        return rtrim($base, '/') . '/' . ltrim($path, '/');
    }
}
// ...existing code...