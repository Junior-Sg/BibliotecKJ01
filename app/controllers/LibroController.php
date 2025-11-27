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
                "sinopsis" => $libro['sinopsis'] ?? "Sin sinopsis disponible."
            ]
        ]);
    }
}

?>

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

    // catalogo principal
    public function index()
    {
        $selectedGenero = $_GET["genero"] ?? null;

        if ($selectedGenero) {
            $libros = $this->Libro->obtenerGeneros($selectedGenero);
        } else {
            $libros = $this->Libro->obtenerTodos();
        }

        // lista de géneros disponible para la vista
        $generos = $this->Libro->obtenerGenerosTodos();

        require __DIR__ . "/../views/libros/index.php";
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

        require __DIR__ . "/../views/libros/index.php";
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
                "sinopsis" => $libro['sinopsis'] ?? "Sin sinopsis disponible."
            ]
        ]);
    }
}

?>

