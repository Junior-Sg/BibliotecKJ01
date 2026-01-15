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
        // Obtener libros más reservados
        $masreservados = $this->libroModel->obtenerLibrosMasReservados(8);

        // Obtener libros favoritos
        $favoritos = $this->libroModel->obtenerLibrosFavoritos(8);

        render_view('inicio/InicioPagina', [
            'masreservados' => $masreservados,
            'favoritos' => $favoritos
        ]);
    }
}