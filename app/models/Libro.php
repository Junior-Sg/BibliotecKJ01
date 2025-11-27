<?php

class Libro
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Obtener todos los libros
    public function obtenerTodos()
    {
        $sql = "SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion,
                       l.Imagen, l.cantidad_total,
                       e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial";

        return $this->conexion->query($sql);
    }

    // Obtener libro por ID
    public function obtenerPorId($idLibro)
    {
        $sql = "SELECT l.*, e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                WHERE l.id_libro = $idLibro";

        return $this->conexion->query($sql)->fetch_assoc();
    }

    // Obtener autores del libro
    public function obtenerAutores($idLibro)
    {
        $sql = "SELECT a.nombre
                FROM autor a
                INNER JOIN libro_autor la ON a.id_autor = la.id_autor
                WHERE la.id_libro = $idLibro";

        return $this->conexion->query($sql);
    }

    // Obtener géneros del libro
    public function obtenerGeneros($idLibro)
    {
        $sql = "SELECT g.nombre
                FROM genero g
                INNER JOIN libro_genero lg ON g.id_genero = lg.id_genero
                WHERE lg.id_libro = $idLibro";

        return $this->conexion->query($sql);
    }

    // Obtener todos los géneros
    
    public function obtenerGenerosTodos()
    {
         $sql = "SELECT * FROM genero ORDER BY nombre ASC";
         return $this->conexion->query($sql);
        
    }

    // Obtener disponibilidad del libro
    public function obtenerDisponibilidad($idLibro)
    {
        $sql = "SELECT cantidad_disponible
                FROM disponibilidad
                WHERE id_libro = $idLibro";

        return $this->conexion->query($sql)->fetch_assoc();
    }

    // Búsqueda general: por título o autor
    public function buscarGeneral($texto)
    {
        $sql = "SELECT DISTINCT l.*, e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN libro_autor la ON la.id_libro = l.id_libro
                LEFT JOIN autor a ON a.id_autor = la.id_autor
                WHERE l.titulo LIKE '%$texto%'
                   OR a.nombre LIKE '%$texto%'";

        return $this->conexion->query($sql);
    }

    public function obtenerPorGenero($nombreGenero)
{
    $sql = "SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion,
                   l.Imagen, l.cantidad_total,
                   e.nombre AS editorial
            FROM libro l
            LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
            INNER JOIN libro_genero lg ON l.id_libro = lg.id_libro
            INNER JOIN genero g ON lg.id_genero = g.id_genero
            WHERE g.nombre = '$nombreGenero'";

    return $this->conexion->query($sql);
}
}

?>
