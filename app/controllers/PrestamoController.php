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
    public function crear() {
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
                header('Location: ' . BASE_URL . 'Prestamo/crear?mensaje=error');
                exit;
            }

            // Llamar al modelo para registrar el préstamo
            $resultado = $this->prestamoModelo->registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);

            // Redirigir según el resultado
            $mensaje = $resultado ? 'ok' : 'error';
            header('Location: ' . BASE_URL . 'Prestamo/crear?mensaje=' . $mensaje);
            exit;
        }
    }

    // Registrar devolución (por id de préstamo)
    public function registrarDevolucion() {
        $idPrestamo = isset($_POST['id_prestamo']) ? intval($_POST['id_prestamo']) : 0;
        if ($idPrestamo === 0) {
            header('Location: ' . BASE_URL . 'Prestamo/crear?mensaje=error');
            return;
        }

        $ok = $this->prestamoModelo->registrarDevolucion($idPrestamo);
        if ($ok) {
            header("Location: " . BASE_URL . "Prestamo/crear?mensaje=ok");
        } else {
            header("Location: /BibliotecKJ01/index.php?c=Prestamo&a=crear&mensaje=error");
        }
    }
}
