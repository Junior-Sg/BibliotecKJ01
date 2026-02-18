<?php
require_once __DIR__ . '/../../config/Conexion.php';

class Libro
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    /* ============================================================
       OBTENER TODOS LOS LIBROS
    ============================================================ */
    public function obtenerTodos($offset = 0, $limit = 20)
    {
        $sql = "SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion,
                       l.Imagen, l.cantidad_total,
                       e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LIMIT ?, ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $offset, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    /* ============================================================
       OBTENER TODOS LOS AUTORES
    ============================================================ */
    public function obtenerAutoresTodos()
    {
        $sql = "SELECT * FROM autor ORDER BY nombre ASC";
        return $this->conexion->query($sql);
    }

    /* ============================================================
       OBTENER LIBRO POR ID
    ============================================================ */
    public function obtenerPorId($idLibro)
    {
        $sql = "SELECT l.*, e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                WHERE l.id_libro = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idLibro);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* ============================================================
       OBTENER AUTORES DE UN LIBRO
    ============================================================ */
    public function obtenerAutores($idLibro)
    {
        $sql = "SELECT a.nombre
                FROM autor a
                INNER JOIN libro_autor la ON a.id_autor = la.id_autor
                WHERE la.id_libro = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idLibro);
        $stmt->execute();
        return $stmt->get_result();
    }

    /* ============================================================
       OBTENER GÉNEROS DE UN LIBRO
    ============================================================ */
    public function obtenerGeneros($idLibro)
    {
        $sql = "SELECT g.nombre
                FROM genero g
                INNER JOIN libro_genero lg ON g.id_genero = lg.id_genero
                WHERE lg.id_libro = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idLibro);
        $stmt->execute();
        return $stmt->get_result();
    }

    /* ============================================================
       OBTENER TODOS LOS GÉNEROS
    ============================================================ */
    public function obtenerGenerosTodos()
    {
        $sql = "SELECT * FROM genero ORDER BY nombre ASC";
        return $this->conexion->query($sql);
    }

    /* ============================================================
       OBTENER GÉNERO POR ID
    ============================================================ */
    public function obtenerGeneroPorId($idGenero)
    {
        $sql = "SELECT * FROM genero WHERE id_genero = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idGenero);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /* ============================================================
       OBTENER DISPONIBILIDAD DE UN LIBRO
    ============================================================ */
    public function obtenerDisponibilidad(int $idLibro) {
        $stmt = $this->conexion->prepare("SELECT d.cantidad_disponible, d.id_estado, e.nombre AS estado_nombre FROM disponibilidad d LEFT JOIN estado e ON d.id_estado = e.id_estado WHERE d.id_libro = ?");
        $stmt->bind_param("i", $idLibro);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /* ============================================================
       BÚSQUEDA GENERAL
    ============================================================ */
    public function buscarGeneral(string $texto, int $offset = 0, int $limit = 20) {
        $q = "%{$texto}%";
        $stmt = $this->conexion->prepare("SELECT DISTINCT l.id_libro, l.titulo, l.sipnosis AS sinopsis, l.Imagen, e.nombre AS editorial FROM libro l LEFT JOIN editorial e ON l.id_editorial = e.id_editorial LEFT JOIN libro_autor la ON la.id_libro = l.id_libro LEFT JOIN autor a ON a.id_autor = la.id_autor WHERE l.titulo LIKE ? OR a.nombre LIKE ? OR l.sipnosis LIKE ? ORDER BY l.titulo ASC LIMIT ? OFFSET ?");
        $stmt->bind_param("sssii", $q, $q, $q, $limit, $offset);
        $stmt->execute();
        return $stmt->get_result();
    }

    /* ============================================================
       OBTENER LIBROS POR NOMBRE DEL GÉNERO
    ============================================================ */
    public function obtenerPorGenero($nombreGenero)
    {
        $sql = "SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion,
                       l.Imagen, l.cantidad_total,
                       e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                INNER JOIN libro_genero lg ON l.id_libro = lg.id_libro
                INNER JOIN genero g ON lg.id_genero = g.id_genero
                WHERE g.nombre = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $nombreGenero);
        $stmt->execute();
        return $stmt->get_result();
    }

    /*METODO FILTRAR*/

    public function filtrarLibros($generos = [], $autores = [], int $offset = 0, int $limit = 20) {

    $sql = "SELECT DISTINCT l.*
            FROM libro l
            LEFT JOIN libro_genero lg ON l.id_libro = lg.id_libro
            LEFT JOIN libro_autor la ON l.id_libro = la.id_libro
            WHERE 1=1";

    if (!empty($generos)) {
        $generos = array_map('intval', $generos);
        $sql .= " AND lg.id_genero IN (" . implode(',', $generos) . ")";
    }

    if (!empty($autores)) {
        $autores = array_map('intval', $autores);
        $sql .= " AND la.id_autor IN (" . implode(',', $autores) . ")";
    }

    $sql .= " ORDER BY l.titulo ASC LIMIT " . intval($limit) . " OFFSET " . intval($offset);

    return $this->conexion->query($sql);
}

    public function obtenerPorGeneroLimit($idGenero, $limit = 4) {
    $idGenero = intval($idGenero);

    $sql = "SELECT l.*
            FROM libro l
            JOIN libro_genero lg ON l.id_libro = lg.id_libro
            WHERE lg.id_genero = $idGenero
            ORDER BY l.titulo ASC
            LIMIT $limit";

    return $this->conexion->query($sql);
}

    public function obtenerPorGeneroId(int $idGenero) {
    $stmt = $this->conexion->prepare("SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion, l.Imagen, l.cantidad_total, e.nombre AS editorial FROM libro l LEFT JOIN editorial e ON l.id_editorial = e.id_editorial JOIN libro_genero lg ON l.id_libro = lg.id_libro WHERE lg.id_genero = ? ORDER BY l.titulo ASC");
    $stmt->bind_param("i", $idGenero);
    $stmt->execute();
    return $stmt->get_result();
}

    public function obtenerPorAutor(int $idAutor) {
    $stmt = $this->conexion->prepare("SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion, l.Imagen, l.cantidad_total, e.nombre AS editorial FROM libro l LEFT JOIN editorial e ON l.id_editorial = e.id_editorial JOIN libro_autor la ON l.id_libro = la.id_libro WHERE la.id_autor = ? ORDER BY l.titulo ASC");
    $stmt->bind_param("i", $idAutor);
    $stmt->execute();
    return $stmt->get_result();
}

    public function contarTotalLibros() {
        $sql = "SELECT COUNT(id_libro) as total FROM libro";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }
}
