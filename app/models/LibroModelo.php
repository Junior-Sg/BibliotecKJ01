<?php
require_once __DIR__ . '/../../config/Conexion.php';

class LibroModelo {

    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    public function obtenerTodosLosLibrosParaCatalogo() {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            ORDER BY l.titulo ASC
        ";
        $res = $this->db->query($sql);
        return $res;
    }

    public function obtenerLibrosDisponibles() {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Imagen,
                d.cantidad_disponible,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero
            FROM libro l
            INNER JOIN disponibilidad d ON d.id_libro = l.id_libro
            INNER JOIN estado es ON es.id_estado = d.id_estado
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            WHERE d.cantidad_disponible > 0 
              AND d.id_estado = 1
            ORDER BY l.titulo ASC
        ";

        $res = $this->db->query($sql);
        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        }
        return $libros;
    }

    public function obtenerPorGenero($nombreGenero) {
        $sql = "SELECT l.id_libro, l.titulo, l.Estante, l.año_publicacion,
                       l.Imagen, l.cantidad_total,
                       e.nombre AS editorial
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                INNER JOIN libro_genero lg ON l.id_libro = lg.id_libro
                INNER JOIN genero g ON lg.id_genero = g.id_genero
                WHERE g.nombre = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $nombreGenero);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function contarTotalLibros() {
        $sql = "SELECT COUNT(id_libro) as total FROM libro";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    public function obtenerLibrosMasReservados($limite = 8) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero,
                COUNT(r.id_reserva) AS total_reservas
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            LEFT JOIN reserva r ON r.id_libro = l.id_libro AND r.estado != 'cancelado' AND DATE(r.fecha_reserva) = CURDATE()
            GROUP BY l.id_libro, l.titulo, l.año_publicacion, e.nombre, l.Estante, l.Imagen
            HAVING total_reservas > 0
            ORDER BY total_reservas DESC, l.titulo ASC
            LIMIT " . intval($limite);
        
        $res = $this->db->query($sql);
        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosMasReservados: " . $this->db->error);
        }
        return $libros;
    }

    public function obtenerLibrosMasReservadosSemanal($limite = 8) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero,
                COUNT(r.id_reserva) AS total_reservas
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            LEFT JOIN reserva r ON r.id_libro = l.id_libro AND r.estado != 'cancelado' AND r.fecha_reserva >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
            GROUP BY l.id_libro, l.titulo, l.año_publicacion, e.nombre, l.Estante, l.Imagen
            HAVING total_reservas > 0
            ORDER BY total_reservas DESC, l.titulo ASC
            LIMIT " . intval($limite);
        
        $res = $this->db->query($sql);
        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosMasReservadosSemanal: " . $this->db->error);
        }
        return $libros;
    }

    public function obtenerLibrosMasReservadosMensual($limite = 8) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero,
                COUNT(r.id_reserva) AS total_reservas
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            LEFT JOIN reserva r ON r.id_libro = l.id_libro AND r.estado != 'cancelado' AND r.fecha_reserva >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
            GROUP BY l.id_libro, l.titulo, l.año_publicacion, e.nombre, l.Estante, l.Imagen
            HAVING total_reservas > 0
            ORDER BY total_reservas DESC, l.titulo ASC
            LIMIT " . intval($limite);
        
        $res = $this->db->query($sql);
        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosMasReservadosMensual: " . $this->db->error);
        }
        return $libros;
    }

    public function obtenerLibrosFavoritos($limite = 8) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero,
                COUNT(f.id_favorito) AS total_favoritos
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            LEFT JOIN favorito f ON f.id_libro = l.id_libro
            GROUP BY l.id_libro, l.titulo, l.año_publicacion, e.nombre, l.Estante, l.Imagen
            HAVING total_favoritos > 0
            ORDER BY total_favoritos DESC, l.titulo ASC
            LIMIT " . intval($limite);
        
        $res = $this->db->query($sql);
        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosFavoritos: " . $this->db->error);
        }
        return $libros;
    }

    public function obtenerLibrosNuevos($limite = 8) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.sipnosis AS sinopsis,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,
                l.Imagen,
                (SELECT id_genero FROM libro_genero lg WHERE lg.id_libro = l.id_libro LIMIT 1) AS id_genero
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            ORDER BY l.id_libro DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $res = $stmt->get_result();

        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosNuevos: " . $this->db->error);
        }
        return $libros;
    }

    /**
     * Cuenta los libros que no están disponibles (cantidad_disponible = 0)
     */
    public function contarLibrosNoDisponibles() {
        $sql = "
            SELECT COUNT(*) as total 
            FROM disponibilidad 
            WHERE cantidad_disponible = 0
        ";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    /**
     * Obtiene los libros que no están disponibles con sus detalles
     */
    public function obtenerLibrosNoDisponibles($limite = 10) {
        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.Imagen,
                d.cantidad_disponible,
                l.cantidad_total
            FROM disponibilidad d
            INNER JOIN libro l ON d.id_libro = l.id_libro
            WHERE d.cantidad_disponible = 0
            ORDER BY l.titulo ASC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $res = $stmt->get_result();

        $libros = [];
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $libros[] = $fila;
            }
        } else {
            error_log("Error en obtenerLibrosNoDisponibles: " . $this->db->error);
        }
        return $libros;
    }
}
?>