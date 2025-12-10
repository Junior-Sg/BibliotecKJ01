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
                l.año_publicacion,
                e.nombre AS editorial,
                l.Estante,       -- Assuming 'Estante' is a column in the 'libro' table
                l.Imagen         -- Assuming 'Imagen' is a column in the 'libro' table
            FROM libro l
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            ORDER BY l.titulo ASC
        ";
        $res = $this->db->query($sql);

        $libros = [];
        if ($res) {
            // Check if there are results before fetching
            if ($res->num_rows > 0) {
                while ($fila = $res->fetch_assoc()) {
                    $libros[] = $fila;
                }
            }
        } else {
            // Log or handle the query error
            error_log("Error en la consulta obtenerTodosLosLibrosParaCatalogo: " . $this->db->error);
        }

        // Return the result set (mysqli_result object) for iteration in the view
        // The view expects a mysqli_result object or an array. Returning the result set
        // allows the view to use $res->fetch_assoc() directly.
        // If an array is preferred, the above loop is sufficient.
        // Given the view structure, it iterates using fetch_assoc(), so returning the result object is appropriate.
        return $res;
    }

    // Obtener libros realmente DISPONIBLES según la nueva tabla disponibilidad + estado
    public function obtenerLibrosDisponibles() {

        $sql = "
            SELECT 
                l.id_libro,
                l.titulo,
                l.año_publicacion,
                e.nombre AS editorial,
                l.Imagen,
                d.cantidad_disponible
            FROM libro l
            INNER JOIN disponibilidad d ON d.id_libro = l.id_libro
            INNER JOIN estado es ON es.id_estado = d.id_estado
            LEFT JOIN editorial e ON e.id_editorial = l.id_editorial
            WHERE d.cantidad_disponible > 0       -- debe haber unidades
              AND d.id_estado = 1                 -- 1 = disponible
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

    public function contarTotalLibros() {
        $sql = "SELECT COUNT(id_libro) as total FROM libro";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

}
?>
