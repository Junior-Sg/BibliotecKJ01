<?php

require_once "./app/models/Libro.php";
require_once "./core/database/conexion.php"; // Ajusta si tu conexión está en otro archivo

class LibroController
{
    private $modeloLibro;

    public function __construct()
    {
        global $conexion; 
        $this->modeloLibro = new Libro($conexion);
    }

    public function index()
    {
        $libros = $this->modeloLibro->obtenerTodos();
        require_once "./app/views/libros/index.php";
    }

    public function detalle($idLibro)
    {
        $libro = $this->modeloLibro->obtenerPorId($idLibro);
        $autores = $this->modeloLibro->obtenerAutores($idLibro);
        $generos = $this->modeloLibro->obtenerGeneros($idLibro);
        $disponibilidad = $this->modeloLibro->obtenerDisponibilidad($idLibro);

        require_once "./app/views/libros/detalle.php";
    }

    public function buscar()
    {
        $texto = $_GET["texto"] ?? "";
        $resultados = $this->modeloLibro->buscarPorTitulo($texto);

        require_once "./app/views/libros/busqueda.php";
    }
}

?>
