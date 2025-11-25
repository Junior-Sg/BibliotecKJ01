<?php

class Libro
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerTodos()
    {
        $sql = "SELECT l.id_libro, l.titulo, l.isbn, l.año_publicacion, l.cantidad_total,
                       e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial";

        return $this->conexion->query($sql);
    }

    public function obtenerPorId($idLibro)
    {
        $sql = "SELECT l.*, e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                WHERE l.id_libro = $idLibro";

        return $this->conexion->query($sql)->fetch_assoc();
    }

    public function obtenerAutores($idLibro)
    {
        $sql = "SELECT a.nombre
                FROM autor a
                INNER JOIN libro_autor la ON a.id_autor = la.id_autor
                WHERE la.id_libro = $idLibro";

        return $this->conexion->query($sql);
    }

    public function obtenerGeneros($idLibro)
    {
        $sql = "SELECT g.nombre
                FROM genero g
                INNER JOIN libro_genero lg ON g.id_genero = lg.id_genero
                WHERE lg.id_libro = $idLibro";

        return $this->conexion->query($sql);
    }

    public function obtenerDisponibilidad($idLibro)
    {
        $sql = "SELECT cantidad_disponible
                FROM disponibilidad
                WHERE id_libro = $idLibro";

        return $this->conexion->query($sql)->fetch_assoc();
    }

    public function buscarPorGenero($idGenero)
    {
        $sql = "SELECT l.id_libro, l.titulo, l.año_publicacion 
                FROM libro l
                INNER JOIN libro_genero lg ON l.id_libro = lg.id_libro
                WHERE lg.id_genero = $idGenero";

        return $this->conexion->query($sql);
    }

    public function buscarPorTitulo($texto)
    {
        $sql = "SELECT id_libro, titulo, año_publicacion 
                FROM libro
                WHERE titulo LIKE '%$texto%'";

        return $this->conexion->query($sql);
    }
}

?>
