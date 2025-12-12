<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/LibroModelo.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../helpers/Mailer.php';

class PrestamoController {

    private $libroModelo;
    private $prestamoModelo;
    private $usuarioModelo;

    public function __construct() {
        $this->libroModelo = new LibroModelo();
        $this->prestamoModelo = new PrestamoModelo();
        $this->usuarioModelo = new Usuario((new Conexion())->conectar());
    }

    /**
     * Muestra el formulario para crear un nuevo préstamo.
     * Carga los libros disponibles para pasarlos a la vista.
     */
    public function vistaCrearPrestamo() {
        // Cargar los libros disponibles desde el modelo
        $libros = $this->libroModelo->obtenerLibrosDisponibles();
        // Cargar la vista y pasarle los datos
        require_once __DIR__ . '/../views/ADMIN/CrearPrestamo.php';
    }

    public function vistaDevoluciones() {
        $this->prestamoModelo->actualizarEstadosDePrestamosRetrasados();
        $prestamos = $this->prestamoModelo->obtenerPrestamosActivos();
        require_once __DIR__ . '/../views/ADMIN/GestionDevoluciones.php';
    }

    /**
     * Procesa los datos del formulario para registrar un nuevo préstamo.
     */
    public function registrarPrestamo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recoger y sanear los datos del formulario
            $idUsuario = filter_input(INPUT_POST, 'id_usuario', FILTER_VALIDATE_INT);
            $idLibro = filter_input(INPUT_POST, 'id_libro', FILTER_VALIDATE_INT);
            $fechaDevolucion = $_POST['fecha_devolucion'] ?? null;
            $fechaPrestamo = date('Y-m-d H:i:s'); // Fecha y hora actual

            // Validar que los datos esenciales no estén vacíos
            if (!$idUsuario || !$idLibro || empty($fechaDevolucion)) {
                header('Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=' . urlencode('Error al registrar el préstamo.'));
                exit;
            }

            // Verificar el límite de préstamos por usuario
            $prestamosActivos = $this->prestamoModelo->contarPrestamosActivosPorUsuario($idUsuario);
            if ($prestamosActivos >= 3) {
                $mensaje = 'El usuario ya tiene 3 préstamos activos. No puede realizar más préstamos hasta que devuelva al menos un libro.';
                header('Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=' . urlencode($mensaje));
                exit;
            }

            // Llamar al modelo para registrar el préstamo
            $resultado = $this->prestamoModelo->registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);

            if ($resultado) {
                // Enviar notificación por correo (sin parar si hay error)
                try {
                    require_once __DIR__ . '/../models/Libro.php';
                    $libroModel = new Libro((new Conexion())->conectar());
                    $libro = $libroModel->obtenerPorId($idLibro);
                    $usuario = $this->usuarioModelo->obtenerPorId($idUsuario);
                    
                    if ($libro && $usuario && !empty($usuario['correo'])) {
                        $mailer = new Mailer();
                        $tituloLibro = htmlspecialchars($libro['titulo'] ?? 'Desconocido');
                        $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                        $usuarioCorreo = $usuario['correo'];
                        $fechaInicio = date('d/m/Y', strtotime($fechaPrestamo));
                        $fechaFin = date('d/m/Y', strtotime($fechaDevolucion));
                        
                        $contenido = "
                            Estimado(a) <strong>$usuarioNombre</strong><br><br>
                            Se ha registrado el préstamo del libro <strong>$tituloLibro</strong> exitosamente.<br><br>
                            <strong>Detalles del préstamo:</strong><br>
                            Fecha de préstamo: <strong>$fechaInicio</strong><br>
                            Fecha de entrega: <strong>$fechaFin</strong><br><br>
                            Por favor, devuelve el libro en la fecha indicada.<br>
                            Recuerda que pasada esta fecha incurrirás en sanciones por retraso.
                        ";
                        
                        $mailer->send($usuarioCorreo, "Préstamo Registrado", $contenido);
                    }
                } catch (Exception $e) {
                    // Log del error pero no interrumpir el flujo
                    error_log("Error al enviar notificación de préstamo: " . $e->getMessage());
                }
            }

            $mensaje = $resultado ? 'Préstamo realizado correctamente.' : 'Error al registrar el préstamo.';
            $param = $resultado ? 'msg_success' : 'msg_error';
            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&$param=" . urlencode($mensaje));
            exit;
        }
    }

    // Registrar devolución (por id de préstamo)
    public function registrarDevolucion() {
        $idPrestamo = isset($_POST['id_prestamo']) ? intval($_POST['id_prestamo']) : 0;
        if ($idPrestamo === 0) {
            header('Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=' . urlencode('ID de préstamo inválido.'));
            return;
        }
        // Obtener información del préstamo antes de marcar como devuelto
        $prestamoInfo = $this->prestamoModelo->getPrestamoById($idPrestamo);
        $idUsuario = $prestamoInfo['id_usuario'] ?? null;
        $idLibro = $prestamoInfo['id_libro'] ?? null;

        $ok = $this->prestamoModelo->registrarDevolucion($idPrestamo);
        if ($ok) {
            // Enviar notificación de devolución (no bloquear el flujo si falla)
            try {
                require_once __DIR__ . '/../models/Libro.php';
                $libroModel = new Libro((new Conexion())->conectar());
                $libro = $libroModel->obtenerPorId($idLibro);
                $usuario = $this->usuarioModelo->obtenerPorId($idUsuario);

                if ($libro && $usuario && !empty($usuario['correo'])) {
                    $mailer = new Mailer();
                    $tituloLibro = htmlspecialchars($libro['titulo'] ?? 'Desconocido');
                    $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                    $usuarioCorreo = $usuario['correo'];
                    $fechaDevolucion = date('d/m/Y');

                    $contenido = "Estimado(a) <strong>$usuarioNombre</strong><br><br>Hemos registrado la devolución del libro <strong>$tituloLibro</strong> el día <strong>$fechaDevolucion</strong>.<br><br>Gracias por utilizar la biblioteca.";

                    $mailer->send($usuarioCorreo, "Devolución registrada", $contenido);
                }
            } catch (Exception $e) {
                error_log("Error al enviar notificación de devolución: " . $e->getMessage());
            }

            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_success=" . urlencode('Devolución registrada correctamente.'));
        } else {
            header("Location: index.php?controller=Prestamo&action=vistaCrearPrestamo&msg_error=" . urlencode('No se pudo registrar la devolución.'));
        }
    }

    public function verPrestamos() {
        header('Content-Type: application/json');
        $this->prestamoModelo->actualizarEstadosDePrestamosRetrasados();
        $prestamos = $this->prestamoModelo->obtenerPrestamosActivos();
        echo json_encode($prestamos);
        exit;
    }

    public function eliminarPrestamo() {
        header('Content-Type: application/json');
        $idPrestamo = $_POST['id_prestamo'] ?? null;

        if (!$idPrestamo) {
            echo json_encode(['success' => false, 'message' => 'ID de préstamo no proporcionado.']);
            exit;
        }

        $resultado = $this->prestamoModelo->eliminarPrestamo((int)$idPrestamo);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Préstamo eliminado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el préstamo.']);
        }
        exit;
    }

    public function getPrestamoById() {
        header('Content-Type: application/json');
        $idPrestamo = $_GET['id_prestamo'] ?? null;

        if (!$idPrestamo) {
            echo json_encode(['success' => false, 'message' => 'ID de préstamo no proporcionado.']);
            exit;
        }

        $prestamo = $this->prestamoModelo->getPrestamoById((int)$idPrestamo);

        if ($prestamo) {
            echo json_encode(['success' => true, 'prestamo' => $prestamo]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Préstamo no encontrado.']);
        }
        exit;
    }

    public function actualizarPrestamo() {
        header('Content-Type: application/json');
        $idPrestamo = $_POST['id_prestamo'] ?? null;
        $fechaDevolucion = $_POST['fecha_devolucion'] ?? null;
        $estado = $_POST['estado'] ?? null;

        if (!$idPrestamo || !$fechaDevolucion || !$estado) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
            exit;
        }

        $resultado = $this->prestamoModelo->actualizarPrestamo((int)$idPrestamo, $fechaDevolucion, $estado);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Préstamo actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el préstamo.']);
        }
        exit;
    }
}