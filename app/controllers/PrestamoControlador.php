<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/Usuario.php';

class PrestamoControlador {

    private $libroModelo;
    private $prestamoModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
        $this->usuarioModelo = new Usuario((new Conexion())->conectar());
    }

    // Mostrar formulario para crear préstamo. Permite buscar usuario por numero_documento via GET
    public function vistaCrearPrestamo() {
        $numeroDocumento = trim($_GET['numero_documento'] ?? '');
        $usuario = null;
        if ($numeroDocumento !== '') {
            $usuario = $this->usuarioModelo->getUsuarioByNumeroDocumento($numeroDocumento);
        }

        $libros = $this->libroModelo->obtenerLibrosDisponibles();
        require_once __DIR__ . '/../views/ADMIN/CrearPrestamo.php';
    }

    // Registrar préstamo
    public function registrarPrestamo() {

        $idUsuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        $idLibro = isset($_POST['id_libro']) ? intval($_POST['id_libro']) : 0;
        $fechaPrestamo = date("Y-m-d");
        $fechaDevolucion = $_POST['fecha_devolucion'] ?? null;

        if ($idUsuario <= 0 || $idLibro <= 0 || empty($fechaDevolucion)) {
            header("Location: index.php?c=Prestamo&a=vistaCrearPrestamo&mensaje=error");
            return;
        }

        $resultado = $this->prestamoModelo->registrarPrestamo(
            $idUsuario,
            $idLibro,
            $fechaPrestamo,
            $fechaDevolucion
        );

        if ($resultado) {
            header("Location: index.php?c=Prestamo&a=vistaCrearPrestamo&mensaje=ok");
        } else {
            header("Location: index.php?c=Prestamo&a=vistaCrearPrestamo&mensaje=error");
        }
    }
}
