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
        // Mantener compatibilidad: aceptar filtros opcionales
        return $this->obtenerLibrosFiltrados([]);
    }

    // Obtener libros con filtros opcionales:
    // $filters = [
    //   'estante' => 'A1',
    //   'editorial' => 'Planeta' (string) or id (int),
    //   'autor' => 'Gabriel',
    //   'genero' => 'Novela',
    //   'anio_desde' => 1990,
    //   'anio_hasta' => 2023
    // ]
    public function obtenerLibrosFiltrados($filters = []) {
        $sql = "SELECT l.*, 
                       e.nombre AS editorial,
                       GROUP_CONCAT(DISTINCT a.nombre) AS autores,
                       GROUP_CONCAT(DISTINCT g.nombre) AS generos
                FROM libro l
                LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
                LEFT JOIN libro_autor la ON la.id_libro = l.id_libro
                LEFT JOIN autor a ON a.id_autor = la.id_autor
                LEFT JOIN libro_genero lg ON lg.id_libro = l.id_libro
                LEFT JOIN genero g ON g.id_genero = lg.id_genero";

        $where = [];
        $types = '';
        $values = [];

        // estante exacto
        if (!empty($filters['estante'])) {
            $where[] = 'l.Estante = ?';
            $types .= 's';
            $values[] = $filters['estante'];
        }

        // editorial (id o nombre parcial)
        if (!empty($filters['editorial'])) {
            if (ctype_digit(strval($filters['editorial']))) {
                $where[] = 'l.id_editorial = ?';
                $types .= 'i';
                $values[] = intval($filters['editorial']);
            } else {
                $where[] = 'e.nombre LIKE ?';
                $types .= 's';
                $values[] = '%' . $filters['editorial'] . '%';
            }
        }

        // año rango
        if (!empty($filters['anio_desde']) && !empty($filters['anio_hasta'])) {
            $where[] = 'l.año_publicacion BETWEEN ? AND ?';
            $types .= 'ii';
            $values[] = intval($filters['anio_desde']);
            $values[] = intval($filters['anio_hasta']);
        } elseif (!empty($filters['anio_desde'])) {
            $where[] = 'l.año_publicacion >= ?';
            $types .= 'i';
            $values[] = intval($filters['anio_desde']);
        } elseif (!empty($filters['anio_hasta'])) {
            $where[] = 'l.año_publicacion <= ?';
            $types .= 'i';
            $values[] = intval($filters['anio_hasta']);
        }

        // autor (usar EXISTS para no romper agrupación)
        if (!empty($filters['autor'])) {
            $sql .= "\n LEFT JOIN libro_autor _la_filter ON _la_filter.id_libro = l.id_libro\n LEFT JOIN autor _a_filter ON _a_filter.id_autor = _la_filter.id_autor";
            $where[] = '_a_filter.nombre LIKE ?';
            $types .= 's';
            $values[] = '%' . $filters['autor'] . '%';
        }

        // genero (similar)
        if (!empty($filters['genero'])) {
            $sql .= "\n LEFT JOIN libro_genero _lg_filter ON _lg_filter.id_libro = l.id_libro\n LEFT JOIN genero _g_filter ON _g_filter.id_genero = _lg_filter.id_genero";
            $where[] = '_g_filter.nombre LIKE ?';
            $types .= 's';
            $values[] = '%' . $filters['genero'] . '%';
        }

        if (!empty($where)) {
            $sql .= "\n WHERE " . implode(' AND ', $where);
        }

        $sql .= "\n GROUP BY l.id_libro";

        // Si no hay parámetros, usar query directa (más simple y compatible)
        if (empty($values)) {
            $cleanSql = str_replace("\n", ' ', $sql);
            return $this->db->query($cleanSql);
        }

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            // fallback: ejecutar consulta con los valores interpolados de forma segura (no ideal)
            return $this->db->query(str_replace("\n", ' ', $sql));
        }

        // bind params dinamicamente
        $bind_names = [];
        $bind_names[] = $types;
        for ($i = 0; $i < count($values); $i++) {
            $bind_name = 'bind' . $i;
            $$bind_name = $values[$i];
            $bind_names[] = &$$bind_name;
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);

        $stmt->execute();

        // Si la extensión mysqlnd está presente, get_result() funciona
        if (method_exists($stmt, 'get_result')) {
            return $stmt->get_result();
        }

        // Si no está disponible, construir un resultado manualmente
        $meta = $stmt->result_metadata();
        if (!$meta) return false;
        $fields = [];
        $row = [];
        $bindVarsArray = [];
        while ($field = $meta->fetch_field()) {
            $fields[] = $field->name;
            $bindVarsArray[] = &$row[$field->name];
        }
        call_user_func_array([$stmt, 'bind_result'], $bindVarsArray);

        $results = [];
        while ($stmt->fetch()) {
            $r = [];
            foreach ($fields as $f) $r[$f] = $row[$f];
            $results[] = $r;
        }

        // Crear objeto similar a mysqli_result mínimo: usar ArrayIterator wrapper
        return new class($results) {
            private $data;
            private $pos = 0;
            public function __construct($arr) { $this->data = $arr; }
            public function fetch_assoc() { if (!isset($this->data[$this->pos])) return null; return $this->data[$this->pos++]; }
            public function fetch_all() { return $this->data; }
            public function num_rows() { return count($this->data); }
        };
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
