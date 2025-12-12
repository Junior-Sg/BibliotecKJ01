<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Libro.php';

class LibroController
{
    private $Libro;

    public function __construct()
    {
        $this->Libro = new Libro((new Conexion())->conectar());
    }

    /* ============================================================
       CATALOGO DE LIBRERIA
    ============================================================ */
    public function catalogo()
    {
        $libros = $this->Libro->obtenerTodos(0, 20);
        require __DIR__ . "/../views/libros/catalogo.php";
    }

    /* ============================================================
       CATALOGO PRINCIPAL
    ============================================================ */
    public function index()
    {
        // Listas para filtros, la vista espera arrays con estos nombres
        $generosRes = $this->Libro->obtenerGenerosTodos();
        $autoresRes = $this->Libro->obtenerAutoresTodos();

        $generos = []; // para la vista
        if ($generosRes && $generosRes->num_rows) {
            while ($r = $generosRes->fetch_assoc()) {
                $generos[] = $r;
            }
        }
        $autores = []; // para la vista
        if ($autoresRes && $autoresRes->num_rows) {
            while ($r = $autoresRes->fetch_assoc()) {
                $autores[] = $r;
            }
        }

        // Manejo de filtros
        $filterGenero = !empty($_GET['id']) ? (int)$_GET['id'] : (!empty($_GET['genre_id']) ? (int)$_GET['genre_id'] : 0);
        $filterGeneros = isset($_GET['generos']) && $_GET['generos'] ? array_filter(array_map('intval', explode(',', $_GET['generos']))) : [];
        $filterAutor = isset($_GET['author_id']) ? (int)$_GET['author_id'] : 0;
        $filterAutores = isset($_GET['autores']) && $_GET['autores'] ? array_filter(array_map('intval', explode(',', $_GET['autores']))) : [];
        $q = trim($_GET['q'] ?? '');

        $is_filtered = !empty($filterGeneros) || !empty($filterAutores) || $filterGenero > 0 || $filterAutor > 0 || $q !== '';

        $libros = null;
        $topByGenero = [];

        if ($is_filtered) {
            if (!empty($filterGeneros) || !empty($filterAutores)) {
                $libros = $this->Libro->filtrarLibros($filterGeneros, $filterAutores);
            } elseif ($filterGenero > 0) {
                $libros = $this->Libro->obtenerPorGeneroId($filterGenero);
            } elseif ($filterAutor > 0) {
                $libros = $this->Libro->obtenerPorAutor($filterAutor);
            } elseif ($q !== '') {
                $libros = $this->Libro->buscarGeneral($q);
            }
        } else {
            // Showcase por género (hasta 4), solo si no hay filtros activos
            foreach ($generos as $g) {
                $topByGenero[$g['id_genero']] = [
                    'nombre' => $g['nombre'],
                    'libros' => $this->Libro->obtenerPorGeneroLimit((int)$g['id_genero'], 4)
                ];
            }
        }

        // Pasar a la vista variables con nombres consistentes
        require __DIR__ . "/../views/libros/libros.php";
    }

    /* ============================================================
       DETALLE HTML (NO JSON)
    ============================================================ */
    public function detalle($idLibro)
    {
        $libro = $this->Libro->obtenerPorId($idLibro);
        $autores = $this->Libro->obtenerAutores($idLibro);
        $generos = $this->Libro->obtenerGeneros($idLibro);
        $disponibilidad = $this->Libro->obtenerDisponibilidad($idLibro);

        require __DIR__ . "/../views/libros/detalle_full.php";
    }

    /* ============================================================
       BUSQUEDA (TITULO O AUTOR)
    ============================================================ */
    public function buscar()
    {
        $texto = $_GET["q"] ?? "";

        $libros = $this->Libro->buscarGeneral($texto);
        $generos = $this->Libro->obtenerGenerosTodos();
        $autores = $this->Libro->obtenerAutoresTodos();
        $totalLibros = $libros ? $libros->num_rows : 0;

        require __DIR__ . "/../views/libros/libros.php";
    }

    /* ============================================================
       DETALLE PARA MODAL EN FORMATO JSON
    ============================================================ */
    public function detalleJson()
    {
        header('Content-Type: application/json; charset=utf-8');

        $idLibro = (int)($_GET["id"] ?? 0);

        if ($idLibro <= 0) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido']);
            return;
        }

        // Obtener libro como array
        $libro = $this->Libro->obtenerPorId($idLibro);
        if (!$libro) {
            echo json_encode(['ok' => false, 'error' => 'Libro no encontrado']);
            return;
        }

        // Autores
        $autoresRes = $this->Libro->obtenerAutores($idLibro);
        $autores = [];
        if ($autoresRes) {
            while ($row = $autoresRes->fetch_assoc()) {
                $autores[] = $row["nombre"];
            }
        }

        // Géneros
        $generosRes = $this->Libro->obtenerGeneros($idLibro);
        $generos = [];
        if ($generosRes) {
            while ($row = $generosRes->fetch_assoc()) {
                $generos[] = $row["nombre"];
            }
        }

        // Disponibilidad
        $disp = $this->Libro->obtenerDisponibilidad($idLibro);
        $cantidad = 0;
        if ($disp) {
            $d = is_array($disp) ? $disp : $disp->fetch_assoc();
            $cantidad = $d["cantidad_disponible"] ?? 0;
        }

        echo json_encode([
            "ok" => true,
            "data" => [
                "id_libro"        => $idLibro,
                "titulo"          => $libro["titulo"] ?? "",
                "editorial"       => $libro["editorial"] ?? "",
                "año_publicacion" => $libro["año_publicacion"] ?? "",
                "Estante"         => $libro["Estante"] ?? "",
                "Imagen"          => $libro["Imagen"] ?? "",
                "autores"         => $autores,
                "generos"         => $generos,
                "disponibilidad"  => (int)$cantidad,
                "sinopsis"        => $libro["sinopsis"] ?? $libro["sipnosis"] ?? "Sin sinopsis disponible."
            ]
        ]);
    }

    /* ============================================================
       BOTÓN "VER MÁS" (AJAX)
    ============================================================ */
    public function cargarMas()
    {
        $offset = (int)($_GET["offset"] ?? 0);

        // Método correcto con OFFSET y LIMIT
        $masLibros = $this->Libro->obtenerTodos($offset);

        require __DIR__ . "/../views/libros/masLibros.php";
    }
}
?>
