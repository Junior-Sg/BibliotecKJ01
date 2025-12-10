<?php

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Libro.php';

class LibroController
{
    private $Libro;

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->Libro = new Libro((new Conexion())->conectar());
    }

    // catalogo principal
    public function index()
    {
        $selectedGenero = $_GET["genero"] ?? null;

        if ($selectedGenero) {
        $libros = $this->Libro->obtenerPorGenero($selectedGenero);
    } else {
    $libros = $this->Libro->obtenerTodos();
    }

        // lista de géneros disponible para la vista
        $generos = $this->Libro->obtenerGenerosTodos();

        // Debugging output
        echo "<!-- Debugging Info for LibroController::index() -->";
        echo "<!-- Selected Genre: " . htmlspecialchars($selectedGenero ?? 'None') . " -->";
        echo "<!-- Libros type: " . gettype($libros) . " -->";
        if (is_object($libros) && method_exists($libros, 'num_rows')) {
            echo "<!-- Libros num_rows: " . $libros->num_rows . " -->";
        } elseif ($libros === false) {
            // Avoid accessing an undefined property on the Libro model; provide a safe fallback message.
            $librosErr = error_get_last()['message'] ?? 'query failed';
            echo "<!-- Libros query failed: " . htmlspecialchars($librosErr) . " -->";
        }
        echo "<!-- Generos type: " . gettype($generos) . " -->";
        if (is_object($generos) && method_exists($generos, 'num_rows')) {
            echo "<!-- Generos num_rows: " . $generos->num_rows . " -->";
        } elseif ($generos === false) {
            // Avoid accessing an undefined property on the Libro model; provide a safe fallback message.
            $generosErr = error_get_last()['message'] ?? 'query failed';
            echo "<!-- Generos query failed: " . htmlspecialchars($generosErr) . " -->";
        }
        echo "<!-- End Debugging Info -->";

        require __DIR__ . "/../views/libros/libros.php";
    }

    // Detalle de un libro
    public function detalle($idLibro)
    {
        $libros = $this->Libro->obtenerPorId($idLibro);
        $autores = $this->Libro->obtenerAutores($idLibro);
        $generos = $this->Libro->obtenerGeneros($idLibro);
        $disponibilidad = $this->Libro->obtenerDisponibilidad($idLibro);

        require "../views/libros/detalle.php";
    }

    // Búsqueda (Título o Autor)
    public function buscar()
    {
        // usar el mismo nombre que el formulario (q)
        $texto = $_GET["q"] ?? "";
        $libros = $this->Libro->buscarGeneral($texto);
        $generos = $this->Libro->obtenerGenerosTodos();

        require __DIR__ . "/../views/libros/libros.php";
    }

    // Json para el modal detalle

    public function detalleJson()
    {
        header('Content-Type: application/json; charset=utf-8');
        $idLibro = (int)($_GET['id'] ?? 0);
        if ($idLibro <= 0) {
            echo json_encode(['ok' => false, 'error' => 'Libro no encontrado']);
            return;
        }

        //Autores

        $autoresRes = $this->Libro->obtenerAutores($idLibro);
        $autores = [];
        if ($autoresRes) {
            // soporta mysqli_result o array
            if (is_object($autoresRes) && method_exists($autoresRes, 'fetch_assoc')) {
                while ($row = $autoresRes->fetch_assoc()) {
                    $autores[] = $row['nombre'];
                }
            } elseif (is_array($autoresRes)) {
                foreach ($autoresRes as $row) {
                    $autores[] = $row['nombre'] ?? null;
                }
            }
        }

        //Géneros

        $generosRes = $this->Libro->obtenerGeneros($idLibro);
        $generos = [];
        if ($generosRes) {
            if (is_object($generosRes) && method_exists($generosRes, 'fetch_assoc')) {
                while ($row = $generosRes->fetch_assoc()) {
                    $generos[] = $row['nombre'];
                }
            } elseif (is_array($generosRes)) {
                foreach ($generosRes as $row) {
                    $generos[] = $row['nombre'] ?? null;
                }
            }
        }

        // Obtener datos del libro
        $libro = $this->Libro->obtenerPorId($idLibro);

        //Disponibilidad

        $disponibilidad = $this->Libro->obtenerDisponibilidad($idLibro);
        $cantidad = 0;
        if (is_array($disponibilidad)) {
            $cantidad = $disponibilidad['cantidad_disponible'] ?? 0;
        } elseif (is_object($disponibilidad) && method_exists($disponibilidad, 'fetch_assoc')) {
            $dispRow = $disponibilidad->fetch_assoc();
            $cantidad = $dispRow['cantidad_disponible'] ?? 0;
        }

        echo json_encode([
            'ok' => true,
            "data" => [
                "titulo" => $libro['titulo'] ?? '',
                "editorial" => $libro['editorial'] ?? "",
                "año_publicacion" => $libro['año_publicacion'] ?? "",
                "Estante" => $libro['Estante'] ?? "",
                "Imagen" => $libro['Imagen'] ?? "",
                "autores" => $autores,
                "generos" => $generos,
                "disponibilidad" => (int)$cantidad,
                "sipnosis" => $libro['sipnosis'] ?? "Sin sipnosis disponible."
            ]
        ]);
    }
}

?>


