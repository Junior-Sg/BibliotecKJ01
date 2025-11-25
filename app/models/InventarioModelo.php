<?php
require_once __DIR__ . '/../../config/Conexion.php';

class InventarioModelo {

    private $db;

    public function __construct() {
        // usar la clase Conexion desde config
        $this->db = (new Conexion())->conectar();
    }

    /*============================
      LISTAR TODOS LOS LIBROS
    ============================*/
    public function obtenerLibros() {
        $sql = "SELECT l.*, 
                       e.nombre AS editorial,
                       GROUP_CONCAT(DISTINCT a.nombre) AS autores,
                       GROUP_CONCAT(DISTINCT g.nombre) AS generos
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN libro_autor la ON la.id_libro = l.id_libro
                LEFT JOIN autor a ON a.id_autor = la.id_autor
                LEFT JOIN libro_genero lg ON lg.id_libro = l.id_libro
                LEFT JOIN genero g ON g.id_genero = lg.id_genero
                GROUP BY l.id_libro";

        return $this->db->query($sql);
    }

    public function obtenerAutores() {
        return $this->db->query("SELECT * FROM autor");
    }

    public function obtenerGeneros() {
        return $this->db->query("SELECT * FROM genero");
    }

    public function obtenerEditoriales() {
        return $this->db->query("SELECT * FROM editorial");
    }

    /*============================
      REGISTRAR LIBRO
    ============================*/
    public function insertarLibro($titulo, $estante, $anio, $editorial, $cantidad, $imagen) {

        $sql = "INSERT INTO libro (titulo, Estante, año_publicacion, id_editorial, cantidad_total, Imagen)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssiss", $titulo, $estante, $anio, $editorial, $cantidad, $imagen);
        $stmt->execute();

        return $this->db->insert_id;
    }

    public function insertarLibroAutor($idLibro, $idAutor) {
        $sql = "INSERT INTO libro_autor (id_libro, id_autor) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idLibro, $idAutor);
        $stmt->execute();
    }

    public function insertarLibroGenero($idLibro, $idGenero) {
        $sql = "INSERT INTO libro_genero (id_libro, id_genero) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idLibro, $idGenero);
        $stmt->execute();
    }

    // Obtener editorial por nombre
    public function getEditorialByName($nombre) {
        $stmt = $this->db->prepare("SELECT id_editorial FROM editorial WHERE nombre = ?");
        if (!$stmt) return null;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    // Insertar editorial y devolver id
    public function insertarEditorial($nombre) {
        $stmt = $this->db->prepare("INSERT INTO editorial (nombre) VALUES (?)");
        if (!$stmt) return false;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // Obtener autor por nombre
    public function getAutorByName($nombre) {
        $stmt = $this->db->prepare("SELECT id_autor FROM autor WHERE nombre = ?");
        if (!$stmt) return null;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    // Insertar autor y devolver id
    public function insertarAutor($nombre) {
        $stmt = $this->db->prepare("INSERT INTO autor (nombre) VALUES (?)");
        if (!$stmt) return false;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // Obtener género por nombre
    public function getGeneroByName($nombre) {
        $stmt = $this->db->prepare("SELECT id_genero FROM genero WHERE nombre = ?");
        if (!$stmt) return null;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function insertarGenero($nombre) {
        $stmt = $this->db->prepare("INSERT INTO genero (nombre) VALUES (?)");
        if (!$stmt) return false;
        $stmt->bind_param('s', $nombre);
        $stmt->execute();
        return $this->db->insert_id;
    }

    // Actualizar libro (sin tocar relaciones)
    public function actualizarLibro($idLibro, $titulo, $estante, $anio, $idEditorial, $cantidad, $imagen = null) {
        $sql = "UPDATE libro SET titulo = ?, Estante = ?, año_publicacion = ?, id_editorial = ?, cantidad_total = ?";
        if ($imagen !== null) {
            $sql .= ", Imagen = ?";
        }
        $sql .= " WHERE id_libro = ?";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;

        if ($imagen !== null) {
            $stmt->bind_param('sssiisi', $titulo, $estante, $anio, $idEditorial, $cantidad, $imagen, $idLibro);
        } else {
            $stmt->bind_param('sssiii', $titulo, $estante, $anio, $idEditorial, $cantidad, $idLibro);
        }

        return $stmt->execute();
    }

    // Reemplazar autores del libro: borrar existentes e insertar los nuevos (por id)
    public function reemplazarLibroAutores($idLibro, $autoresIds) {
        $this->db->query("DELETE FROM libro_autor WHERE id_libro = " . intval($idLibro));
        foreach ($autoresIds as $idA) {
            $this->insertarLibroAutor($idLibro, $idA);
        }
    }

    // Reemplazar géneros del libro
    public function reemplazarLibroGeneros($idLibro, $generosIds) {
        $this->db->query("DELETE FROM libro_genero WHERE id_libro = " . intval($idLibro));
        foreach ($generosIds as $idG) {
            $this->insertarLibroGenero($idLibro, $idG);
        }
    }

    /*============================
      ELIMINAR LIBRO
    ============================*/
    public function eliminarLibro($idLibro) {
        $this->db->query("DELETE FROM libro_autor WHERE id_libro = $idLibro");
        $this->db->query("DELETE FROM libro_genero WHERE id_libro = $idLibro");
        return $this->db->query("DELETE FROM libro WHERE id_libro = $idLibro");
    }
}
