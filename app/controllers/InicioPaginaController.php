<?php
// filepath: c:\xampp\htdocs\BibliotecKJ01\app\controllers\InicioController.php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../core/helpers.php';

class InicioPaginaController
{
    private $libroModel;

    public function __construct()
    {
        $this->libroModel = new LibroModelo();
    }

    public function index()
    {
        // obtener datos básicos para la vista InicioPagina.php
        $res = $this->libroModel->obtenerTodosLosLibrosParaCatalogo();

        $masreservados = [];
        $nuevos = [];

        if ($res && $res !== false) {
            // convertir resultado a array para manipular (no cambiar vista)
            $rows = [];
            while ($r = $res->fetch_assoc()) $rows[] = $r;

            // Simple heurística temporal:
            // - tomar primeros 8 como 'nuevos'
            // - tomar primeros 8 también como 'más reservados' (ajustar modelo luego)
            $nuevos = array_slice($rows, 0, 8);
            $masreservados = array_slice($rows, 0, 8);
        }

        render_view('inicio/InicioPagina', [
            'masreservados' => $masreservados,
            'nuevos' => $nuevos
        ]);
    }
}