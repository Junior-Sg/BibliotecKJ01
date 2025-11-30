<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/Usuario.php';

class PrestamoController {

    private $libroModelo;
    private $prestamoModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
        $this->usuarioModelo = new Usuario((new Conexion())->conectar());
    }

    /**
     * Muestra el formulario para crear un nuevo préstamo.
     * Carga los libros disponibles para pasarlos a la vista.
     */
    public function vistaCrearPrestamo() {
        // Cargar los libros disponibles desde el modelo
        $libros = $this->libroModelo->obtenerLibrosDisponibles();
        // Cargar la vista y pasarle los datos
        require_once __DIR__ . '/../views/ADMIN/CrearPrestamo.php';
    }

    /**
     * Procesa los datos del formulario para registrar un nuevo préstamo.
     */
    public function registrarPrestamo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger y sanear los datos del formulario
            $idUsuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
            $idLibro = filter_input(INPUT_POST, 'id_libro', FILTER_VALIDATE_INT);
            $fechaDevolucion = $_POST['fecha_devolucion'] ?? null;
            $fechaPrestamo = date('Y-m-d H:i:s'); // Fecha y hora actual

            // Validar que los datos esenciales no estén vacíos
            if (!$idUsuario || !$idLibro || empty($fechaDevolucion)) {
                header('Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=' . urlencode('Error al registrar el préstamo.'));
                exit;
            }

            // Llamar al modelo para registrar el préstamo
            $resultado = $this->prestamoModelo->registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);

            $mensaje = $resultado ? 'Préstamo realizado correctamente.' : 'Error al registrar el préstamo.';
            $param = $resultado ? 'msg_success' : 'msg_error';
            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&$param=" . urlencode($mensaje));
            exit;
        }
    }

    // Registrar devolución (por id de préstamo)
    public function registrarDevolucion() {
        $idPrestamo = isset($_POST['id_prestamo']) ? intval($_POST['id_prestamo']) : 0;
        if ($idPrestamo === 0) {
            header('Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=' . urlencode('ID de préstamo inválido.'));
            return;
        }

        $ok = $this->prestamoModelo->registrarDevolucion($idPrestamo);
        if ($ok) {
            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_success=" . urlencode('Devolución registrada correctamente.'));
        } else {
            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=" . urlencode('No se pudo registrar la devolución.'));
        }
    }


}