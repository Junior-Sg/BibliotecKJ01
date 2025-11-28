<?php

require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/Conexion.php';

class InicioController {

    private $libroModelo;
    private $prestamoModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
        $this->usuarioModelo = new Usuario((new Conexion())->conectar());
    }

    public function index() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Check if the user is an admin (logged in and rol is 1)
        if (isset($_SESSION['id_usuario']) && $_SESSION['rol'] == 1) {
            // Admin dashboard logic
            $totalLibros = $this->libroModelo->contarTotalLibros();
            $prestamosActivos = $this->prestamoModelo->contarPrestamosActivos();
            $totalUsuarios = $this->usuarioModelo->contarTotalUsuarios();
            $ultimosPrestamos = $this->prestamoModelo->obtenerUltimosPrestamos(5);

            // Cargar la vista del dashboard
            require_once __DIR__ . "/../views/ADMIN/Inicio.php";
        } else {
            // Public catalog view logic for non-admins or guests
            // Fetch all books for the catalog view
            $libros = $this->libroModelo->obtenerTodosLosLibrosParaCatalogo();

            // Cargar la vista del catálogo público
            require_once __DIR__ . "/../views/libros/libros.php";
        }
    }
}
