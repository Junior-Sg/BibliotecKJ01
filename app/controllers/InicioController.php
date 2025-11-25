<?php

require_once "Models/Inicio.php";

class InicioController {

    public function index() {

        require_once "Config/database.php";

        $baseDatos = new Database();
        $conexion = $baseDatos->conectar();

        $inicio = new Inicio($conexion);

        $totalLibros = $inicio->obtenerTotalLibros();
        $prestamosActivos = $inicio->obtenerPrestamosActivos();
        $totalUsuarios = $inicio->obtenerTotalUsuarios();
        $ultimosPrestamos = $inicio->obtenerUltimosPrestamos();

        require_once "Views/Admin/inicio.php";
    }
}
