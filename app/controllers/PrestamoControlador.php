<?php

class PrestamoControlador {

    private $libroModelo;
    private $prestamoModelo;

    public function __construct() {
        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
    }

    // Mostrar formulario para crear préstamo
    public function vistaCrearPrestamo() {
        $libros = $this->libroModelo->obtenerLibrosDisponibles();
        require_once "Views/Prestamo/CrearPrestamo.php";
    }

    // Registrar préstamo
    public function registrarPrestamo() {

        $idUsuario = $_POST['id_usuario'];
        $idLibro = $_POST['id_libro'];
        $fechaPrestamo = date("Y-m-d");
        $fechaDevolucion = $_POST['fecha_devolucion'];

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
