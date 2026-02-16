<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../../config/Conexion.php';

class InicioController extends BaseController {

    private $libroModelo;
    private $prestamoModelo;
    private $usuarioModelo;

    public function __construct() {
        parent::__construct(); // Llama al constructor de BaseController

        // Proteger el controlador para que solo los administradores puedan acceder
        if (!$this->isAdmin()) {
            // Si no es admin, redirigir a la página de login o a donde corresponda
            $this->redirect('LoginUsuario', 'index');
        }

        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
        $this->usuarioModelo = new Usuario((new Conexion())->conectar());
    }

    public function index() {
        // lógica de la dashboard del administrador
        $totalLibros = $this->libroModelo->contarTotalLibros();
        $prestamosActivos = $this->prestamoModelo->contarPrestamosActivos();
        $totalUsuarios = $this->usuarioModelo->contarTotalUsuarios();
        $ultimosPrestamos = $this->prestamoModelo->obtenerUltimosPrestamos(5);
            // Actualizar estados y obtener préstamos retrasados para mostrar en el panel
            $this->prestamoModelo->actualizarEstadosDePrestamosRetrasados();
            $retrasadosCount = $this->prestamoModelo->contarPrestamosRetrasados();
            $retrasados = $this->prestamoModelo->obtenerPrestamosRetrasados(5);
        
        // Obtener solicitudes de aplazamiento pendientes
        $solicitudesAplazamiento = $this->prestamoModelo->obtenerSolicitudesAplazamientoPendientes();

        // Cargar la vista del dashboard
        require_once __DIR__ . "/../views/ADMIN/Inicio.php";
    }
}
