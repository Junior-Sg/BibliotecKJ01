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

// Convierte mysqli_result o objetos en arrays asociativos
function to_array_list($res) {
    if (is_object($res) && method_exists($res, 'fetch_all')) {
        return $res->fetch_all(MYSQLI_ASSOC);
    } elseif (is_array($res)) {
        return $res;
    } elseif (is_object($res) && method_exists($res, 'fetch_assoc')) {
        $out = [];
        while ($row = $res->fetch_assoc()) { $out[] = $row; }
        return $out;
    }
    return [];
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
    CATALOGO POR GENERO
    ============================================================ */
    public function catalogoGenero($id)
    {
        $id = $id ?? $_GET['id'] ?? 0;
        $generoRes = $this->Libro->obtenerGeneroPorId($id);
        $generoNombre = $generoRes ? $generoRes['nombre'] : 'Género desconocido';
        $librosRes = $this->Libro->obtenerPorGeneroId($id);
        $librosDelGenero = [];
        if ($librosRes && $librosRes->num_rows > 0) {
            while ($row = $librosRes->fetch_assoc()) {
                $librosDelGenero[] = $row;
            }
        }
        
        require __DIR__ . "/../views/libros/catalogoGenero.php";
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
        $totalLibros = 0;
        $generosList = $generos;
        $autoresList = $autores;

        // Active filters for checkboxes
        $activeGeneros = isset($_GET['generos']) && $_GET['generos'] ? explode(',', $_GET['generos']) : [];
        $activeAutores = isset($_GET['autores']) && $_GET['autores'] ? explode(',', $_GET['autores']) : [];
        $singleGenreId = !empty($_GET['id']) ? (int)$_GET['id'] : 0;

        $generosMap = array_column($generosList, 'nombre', 'id_genero');
        $autoresMap = array_column($autoresList, 'nombre', 'id_autor');

        $results = [];
    
        if ($is_filtered) {
            if (!empty($filterGeneros) || !empty($filterAutores)) {
                $librosRes = $this->Libro->filtrarLibros($filterGeneros, $filterAutores);
                if ($librosRes && $librosRes->num_rows > 0) {
                    while ($r = $librosRes->fetch_assoc()) {
                        $results[] = $r;
                    }
                    $totalLibros = count($results);
                }
            } elseif ($filterGenero > 0) {
                $librosRes = $this->Libro->obtenerPorGeneroId($filterGenero);
                if ($librosRes && $librosRes->num_rows > 0) {
                    while ($r = $librosRes->fetch_assoc()) {
                        $results[] = $r;
                    }
                    $totalLibros = count($results);
                }
            } elseif ($filterAutor > 0) {
                $librosRes = $this->Libro->obtenerPorAutor($filterAutor);
                if ($librosRes && $librosRes->num_rows > 0) {
                    while ($r = $librosRes->fetch_assoc()) {
                        $results[] = $r;
                    }
                    $totalLibros = count($results);
                }
            } elseif ($q !== '') {
                $librosRes = $this->Libro->buscarGeneral($q);
                if ($librosRes && $librosRes->num_rows > 0) {
                    while ($r = $librosRes->fetch_assoc()) {
                        $results[] = $r;
                    }
                    $totalLibros = count($results);
                }
            }

        } else {
            // Showcase por género (hasta 4), solo si no hay filtros activos
            foreach ($generos as $g) {
                $librosRes = $this->Libro->obtenerPorGeneroLimit((int)$g['id_genero'], 4);
                $librosArray = [];
                if ($librosRes && $librosRes->num_rows > 0) {
                    while ($row = $librosRes->fetch_assoc()) {
                        $librosArray[] = $row;
                    }
                }
                $topByGenero[$g['id_genero']] = [
                    'nombre' => $g['nombre'],
                    'libros' => $librosArray
                ];
            }
            $totalLibros = $this->Libro->contarTotalLibros();
        }
        
        // Pasar a la vista variables con nombres consistentes
        require __DIR__ . "/../views/libros/libros.php";
    }

    /* ============================================================
    DETALLE HTML (NO JSON)
    ============================================================ */
    public function detalle($id)
    {
        $libro = $this->Libro->obtenerPorId($id);
        $autores = $this->Libro->obtenerAutores($id);
        $generos = $this->Libro->obtenerGeneros($id);
        $disponibilidad = $this->Libro->obtenerDisponibilidad($id);
        

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $libro = $this->Libro->obtenerPorId($id); 
            $generos = $this->Libro->obtenerGeneros($id);
            $autores = $this->Libro->obtenerAutores($id);
            $disponibilidad = $this->Libro->obtenerDisponibilidad($id);
        }

        if ($libro) {
            $libro['autores'] = $autores;
            $libro['generos'] = $generos;
            $libro['disponibilidad'] = $disponibilidad;
            echo json_encode($libro);
        }
        
        require __DIR__ . "/../views/libros/detalle.php";
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

        // Variables para la vista libros.php
        $results = $this->to_array_list($libros);
        $is_filtered = true;
        $topByGenero = [];
        $generosList = $this->to_array_list($generos);
        $autoresList = $this->to_array_list($autores);
        $activeGeneros = [];
        $activeAutores = [];
        $singleGenreId = 0;
        $generosMap = array_column($generosList, 'nombre', 'id_genero');
        $autoresMap = array_column($autoresList, 'nombre', 'id_autor');

        require __DIR__ . "/../views/libros/libros.php";
    }

    /* ============================================================
       DETALLE PARA MODAL EN FORMATO JSON
    ============================================================ */
    public function detalleJson()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = (int)($_GET["id"] ?? 0);

        if ($id <= 0) {
            echo json_encode(['ok' => false, 'error' => 'ID inválido']);
            return;
        }

        // Obtener libro como array
        $libro = $this->Libro->obtenerPorId($id);
        if (!$libro) {
            echo json_encode(['ok' => false, 'error' => 'Libro no encontrado']);
            return;
        }

        // Autores
        $autoresRes = $this->Libro->obtenerAutores($id);
        $autores = [];
        if ($autoresRes) {
            while ($row = $autoresRes->fetch_assoc()) {
                $autores[] = $row["nombre"];
            }
        }

        // Géneros
        $generosRes = $this->Libro->obtenerGeneros($id);
        $generos = [];
        if ($generosRes) {
            while ($row = $generosRes->fetch_assoc()) {
                $generos[] = $row["nombre"];
            }
        }

        // Disponibilidad
        $disp = $this->Libro->obtenerDisponibilidad($id);
        $cantidad = 0;
        if ($disp) {
            $d = is_array($disp) ? $disp : $disp->fetch_assoc();
            $cantidad = $d["cantidad_disponible"] ?? 0;
        }

        echo json_encode([
            "ok" => true,
            "data" => [
                "id_libro"        => $id,
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
        
        // Recibir filtros desde la solicitud AJAX
        $filterGeneros = isset($_GET['generos']) && $_GET['generos'] ? array_filter(array_map('intval', explode(',', $_GET['generos']))) : [];
        $filterAutores = isset($_GET['autores']) && $_GET['autores'] ? array_filter(array_map('intval', explode(',', $_GET['autores']))) : [];
        $q = trim($_GET['q'] ?? '');
        
        // Determinar qué datos cargar según los filtros
        if (!empty($filterGeneros) || !empty($filterAutores)) {
            // Filtrar por géneros y/o autores
            $masLibros = $this->Libro->filtrarLibros($filterGeneros, $filterAutores, $offset);
        } elseif ($q !== '') {
            // Búsqueda de texto
            $masLibros = $this->Libro->buscarGeneral($q, $offset);
        } else {
            // Obtener todos sin filtros
            $masLibros = $this->Libro->obtenerTodos($offset);
        }
        
        // Convertir a array si es necesario
        if (is_object($masLibros) && method_exists($masLibros, 'fetch_assoc')) {
            $masLibros_array = [];
            while ($row = $masLibros->fetch_assoc()) {
                $masLibros_array[] = $row;
            }
            $masLibros = $masLibros_array;
        }

        require __DIR__ . "/../views/libros/masLibros.php";
    }
}
?>
