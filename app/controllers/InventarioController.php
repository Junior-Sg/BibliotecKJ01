<?php
require_once __DIR__ . '/../models/InventarioModelo.php';

class InventarioController {

    private $modelo;

    public function __construct() {
        $this->modelo = new InventarioModelo();
    }

    public function registrarLibro() {

        $titulo     = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
        $estante    = isset($_POST['estante']) ? trim($_POST['estante']) : '';
        $anio       = isset($_POST['anio_publicacion']) ? trim($_POST['anio_publicacion']) : null;
        $editorialInput  = isset($_POST['editorial']) ? trim($_POST['editorial']) : '';
        $cantidad   = isset($_POST['cantidad_total']) ? intval($_POST['cantidad_total']) : 0;

        $autoresInput    = isset($_POST['autores']) ? $_POST['autores'] : '';
        $generosInput    = isset($_POST['generos']) ? $_POST['generos'] : '';

        /*======== SUBIR IMAGEN ========*/
        $nombreImg = "";

        if (!empty($_FILES["imagen"]["name"])) {

            $nombreImg = time() . "_" . basename($_FILES["imagen"]["name"]);
            $ruta = "../../public/img/libros/" . $nombreImg;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        }

        // === Editorial: puede venir como id o como nombre => resolver id ===
        $idEditorial = null;
        if ($editorialInput !== '') {
            if (ctype_digit($editorialInput)) {
                $idEditorial = intval($editorialInput);
            } else {
                $norm = trim($editorialInput);
                $found = $this->modelo->getEditorialByName($norm);
                if ($found && isset($found['id_editorial'])) {
                    $idEditorial = $found['id_editorial'];
                } else {
                    $idEditorial = $this->modelo->insertarEditorial($norm);
                }
            }
        }

        // === Autores: puede venir como array de ids o como cadena separada por comas ===
        $autoresIds = [];
        if (is_array($autoresInput)) {
            foreach ($autoresInput as $a) {
                if (ctype_digit(strval($a))) $autoresIds[] = intval($a);
            }
        } else {
            // cadena: "Autor1, Autor2"
            $parts = array_filter(array_map('trim', explode(',', $autoresInput)));
            foreach ($parts as $p) {
                if ($p === '') continue;
                $found = $this->modelo->getAutorByName($p);
                if ($found && isset($found['id_autor'])) {
                    $autoresIds[] = $found['id_autor'];
                } else {
                    $autoresIds[] = $this->modelo->insertarAutor($p);
                }
            }
        }

        // === Géneros: similar a autores ===
        $generosIds = [];
        if (is_array($generosInput)) {
            foreach ($generosInput as $g) {
                if (ctype_digit(strval($g))) $generosIds[] = intval($g);
            }
        } else {
            $parts = array_filter(array_map('trim', explode(',', $generosInput)));
            foreach ($parts as $p) {
                if ($p === '') continue;
                $found = $this->modelo->getGeneroByName($p);
                if ($found && isset($found['id_genero'])) {
                    $generosIds[] = $found['id_genero'];
                } else {
                    $generosIds[] = $this->modelo->insertarGenero($p);
                }
            }
        }

        $idLibro = $this->modelo->insertarLibro($titulo, $estante, $anio, $idEditorial, $cantidad, $nombreImg);

        // Insertar relaciones
        foreach ($autoresIds as $aId) {
            $this->modelo->insertarLibroAutor($idLibro, $aId);
        }
        foreach ($generosIds as $gId) {
            $this->modelo->insertarLibroGenero($idLibro, $gId);
        }

        header("Location: ../views/ADMIN/GestionInventario.php?msg=registrado");
    }

    public function actualizarLibro() {
        $idLibro = isset($_POST['id_libro']) ? intval($_POST['id_libro']) : 0;
        if ($idLibro <= 0) {
            header("Location: ../../views/ADMIN/GestionInventario.php?msg=error");
            return;
        }

        $titulo     = isset($_POST['titulo']) ? trim($_POST['titulo']) : '';
        $estante    = isset($_POST['estante']) ? trim($_POST['estante']) : '';
        $anio       = isset($_POST['anio_publicacion']) ? trim($_POST['anio_publicacion']) : null;
        $editorialInput  = isset($_POST['editorial']) ? trim($_POST['editorial']) : '';
        $cantidad   = isset($_POST['cantidad_total']) ? intval($_POST['cantidad_total']) : 0;

        $autoresInput    = isset($_POST['autores']) ? $_POST['autores'] : '';
        $generosInput    = isset($_POST['generos']) ? $_POST['generos'] : '';

        // manejar imagen si se sube
        $nombreImg = null;
        if (!empty($_FILES["imagen"]["name"])) {
            $nombreImg = time() . "_" . basename($_FILES["imagen"]["name"]);
            $ruta = "../../public/img/libros/" . $nombreImg;
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $ruta);
        }

        // resolver editorial
        $idEditorial = null;
        if ($editorialInput !== '') {
            if (ctype_digit($editorialInput)) {
                $idEditorial = intval($editorialInput);
            } else {
                $norm = trim($editorialInput);
                $found = $this->modelo->getEditorialByName($norm);
                if ($found && isset($found['id_editorial'])) {
                    $idEditorial = $found['id_editorial'];
                } else {
                    $idEditorial = $this->modelo->insertarEditorial($norm);
                }
            }
        }

        // autores
        $autoresIds = [];
        if (is_array($autoresInput)) {
            foreach ($autoresInput as $a) {
                if (ctype_digit(strval($a))) $autoresIds[] = intval($a);
            }
        } else {
            $parts = array_filter(array_map('trim', explode(',', $autoresInput)));
            foreach ($parts as $p) {
                if ($p === '') continue;
                $found = $this->modelo->getAutorByName($p);
                if ($found && isset($found['id_autor'])) {
                    $autoresIds[] = $found['id_autor'];
                } else {
                    $autoresIds[] = $this->modelo->insertarAutor($p);
                }
            }
        }

        // generos
        $generosIds = [];
        if (is_array($generosInput)) {
            foreach ($generosInput as $g) {
                if (ctype_digit(strval($g))) $generosIds[] = intval($g);
            }
        } else {
            $parts = array_filter(array_map('trim', explode(',', $generosInput)));
            foreach ($parts as $p) {
                if ($p === '') continue;
                $found = $this->modelo->getGeneroByName($p);
                if ($found && isset($found['id_genero'])) {
                    $generosIds[] = $found['id_genero'];
                } else {
                    $generosIds[] = $this->modelo->insertarGenero($p);
                }
            }
        }

        // actualizar libro
        $this->modelo->actualizarLibro($idLibro, $titulo, $estante, $anio, $idEditorial, $cantidad, $nombreImg);

        // reemplazar relaciones
        $this->modelo->reemplazarLibroAutores($idLibro, $autoresIds);
        $this->modelo->reemplazarLibroGeneros($idLibro, $generosIds);

        header("Location: ../views/ADMIN/GestionInventario.php?msg=actualizado");
    }

    public function eliminarLibro() {
        $id = $_POST['id_libro'];
        $this->modelo->eliminarLibro($id);

        header("Location: ../views/ADMIN/GestionInventario.php?msg=eliminado");
    }
}

// Dispatcher procedural para llamadas desde formularios
$action = $_POST['accion'] ?? $_GET['accion'] ?? '';
$controller = new InventarioController();

if ($action === 'registrarLibro') {
    $controller->registrarLibro();
    exit;
} elseif ($action === 'eliminarLibro') {
    $controller->eliminarLibro();
    exit;
} elseif ($action === 'actualizarLibro') {
    $controller->actualizarLibro();
    exit;
} else {
    // si se accede directamente, redirigir al listado
    header('Location: ../views/ADMIN/GestionInventario.php');
    exit;
}
