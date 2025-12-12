<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/InventarioModelo.php';

class InventarioController extends BaseController {

    private $modelo;

    public function __construct() {
        parent::__construct();
        if (!$this->isAdmin()) {
            $this->redirect('LoginUsuario', 'index');
        }
        $this->modelo = new InventarioModelo();
    }

    public function index() {
        // 1. El controlador se encarga de recoger y preparar TODA la información de la petición.
        $filters = [
            'estante' => $_GET['estante'] ?? '',
            'autor' => $_GET['autor'] ?? '',
            'genero' => $_GET['genero'] ?? '',
            'editorial' => $_GET['editorial'] ?? ''
        ];
        $msg = $_GET['msg'] ?? null;
        $error = $_GET['error'] ?? null;

        // 2. El controlador pide al modelo los datos necesarios.
        $libros = $this->modelo->obtenerLibrosFiltrados($filters);
        
        $editorialesResult = $this->modelo->obtenerEditoriales();
        $editoriales = []; // Un array simple, más fácil de usar en la vista.
        if ($editorialesResult && $editorialesResult->num_rows > 0) {
            $editoriales = $editorialesResult->fetch_all(MYSQLI_ASSOC);
        }

        // 3. El controlador carga la vista. 
        // Las variables ($libros, $editoriales, $filters, $msg, $error) se pasan implícitamente.
        require_once __DIR__ . '/../views/ADMIN/GestionInventario.php';
    }

    public function registrarLibro() {
        $this->procesarFormularioLibro();
    }

    public function actualizarLibro() {
        $idLibro = intval($_POST['id_libro'] ?? 0);
        $this->procesarFormularioLibro($idLibro);
    }

    private function procesarFormularioLibro($idLibro = null) {
        error_log("POST data: " . print_r($_POST, true));

        $titulo = trim($_POST['titulo'] ?? '');
        $estante = trim($_POST['estante'] ?? '');
        $anio = intval($_POST['anio_publicacion'] ?? 0);
        $editorialNombre = trim($_POST['editorial'] ?? '');
        $cantidad = intval($_POST['cantidad_total'] ?? 0);
        $autoresStr = trim($_POST['autores'] ?? '');
        $generosStr = trim($_POST['generos'] ?? '');
        $sipnosis = trim($_POST['sipnosis'] ?? '');

        if (empty($titulo) || empty($editorialNombre) || empty($autoresStr) || empty($generosStr) || $anio <= 0) {
            $this->redirigirConError('Todos los campos son obligatorios.');
        }

        // Gestionar Editorial
        $editorial = $this->modelo->getEditorialByName($editorialNombre);
        $idEditorial = $editorial ? $editorial['id_editorial'] : $this->modelo->insertarEditorial($editorialNombre);

        // Gestionar Autores
        $autoresIds = $this->procesarNombres($autoresStr, 'getAutorByName', 'insertarAutor');

        // Gestionar Géneros
        $generosIds = $this->procesarNombres($generosStr, 'getGeneroByName', 'insertarGenero');

        // Gestionar Imagen
        $nombreImagen = null; // Por defecto, no se cambia la imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $dirDestino = __DIR__ . '/../../public/img/libros/';
            if (!is_dir($dirDestino)) {
                mkdir($dirDestino, 0777, true);
            }

            // Si es una actualización, intentar borrar la imagen anterior
            if ($idLibro) {
                $imagenAntigua = $this->modelo->obtenerImagenLibro($idLibro);
                if ($imagenAntigua && file_exists($dirDestino . $imagenAntigua)) {
                    @unlink($dirDestino . $imagenAntigua); // Usar @ para suprimir errores si el archivo no existe
                }
            }
            
            // Generar un nombre único para la nueva imagen
            $nombreImagen = time() . '_' . basename($_FILES['imagen']['name']);
            move_uploaded_file($_FILES['imagen']['tmp_name'], $dirDestino . $nombreImagen);
        }

        if ($idLibro) { // Actualizar
            $this->modelo->actualizarLibro($idLibro, $titulo, $estante, $anio, $idEditorial, $cantidad, $sipnosis, $nombreImagen);
            $this->modelo->reemplazarLibroAutores($idLibro, $autoresIds);
            $this->modelo->reemplazarLibroGeneros($idLibro, $generosIds);
            $this->redirigirConExito('Libro actualizado correctamente.');
        } else { // Registrar
            $nuevoIdLibro = $this->modelo->insertarLibro($titulo, $estante, $anio, $idEditorial, $cantidad, $nombreImagen, $sipnosis);
            $this->modelo->reemplazarLibroAutores($nuevoIdLibro, $autoresIds);
            $this->modelo->reemplazarLibroGeneros($nuevoIdLibro, $generosIds);
            $this->redirigirConExito('Libro registrado correctamente.');
        }
    }

    public function eliminarLibro() {
        $idLibro = intval($_POST['id_libro'] ?? 0);
        if ($idLibro <= 0) {
            $this->redirigirConError('ID de libro inválido.');
        }

        $ok = $this->modelo->eliminarLibro($idLibro);
        if ($ok) {
            $this->redirigirConExito('Libro eliminado correctamente.');
        } else {
            $this->redirigirConError('No se pudo eliminar el libro.');
        }
    }

    private function procesarNombres($string, $getter, $setter) {
        $nombres = array_map('trim', explode(',', $string));
        $ids = [];
        foreach ($nombres as $nombre) {
            if (empty($nombre)) continue;
            $item = $this->modelo->$getter($nombre);
            $ids[] = $item ? $item[array_keys($item)[0]] : $this->modelo->$setter($nombre);
        }
        return $ids;
    }

    private function redirigirConExito($mensaje) {
        header('Location: index.php?controller=Inventario&action=index&msg_success=' . urlencode($mensaje));
        exit;
    }

    private function redirigirConError($mensaje) {
        header('Location: index.php?controller=Inventario&action=index&msg_error=' . urlencode($mensaje));
        exit;
    }
}