<?php

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Libro.php';

class LibroController
{
    private $Libro;

    public function __construct()
    {
        $this->Libro = new Libro((new Conexion())->conectar());
    }

    // 📌 Página principal del catálogo
    public function index()
    {
    $genero = $_GET["genero"] ?? null;

    if ($genero) {
        $libros = $this->Libro->obtenerGeneros($genero);
    } else {
        $libros = $this->Libro->obtenerTodos();
    }

    $genero = $this->Libro->obtenerGenerosTodos();

    require __DIR__ . "/../views/libros/index.php";
    }

    // 📌 Detalle de un libro
    public function detalle($idLibro)
    {
        $libros = $this->Libro->obtenerPorId($idLibro);
        $autores = $this->Libro->obtenerAutores($idLibro);
        $generos = $this->Libro->obtenerGeneros($idLibro);
        $disponibilidad = $this->Libro->obtenerDisponibilidad($idLibro);

        require "../views/libros/detalle.php";
    }

    // 📌 Búsqueda (Título o Autor)
    public function buscar()
    {
        $texto = $_GET["texto"] ?? "";

        // 🔥 Usa el método correcto del modelo
        $resultados = $this->Libro->buscarGeneral($texto);

        require "../views/libros/busqueda.php";
    }
}

?>

