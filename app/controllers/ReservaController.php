
<?php

require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../../config/Conexion.php';

class ReservaController
{
    private $reservaModel;
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
        $this->reservaModel = new Reserva($this->db);
    }

    public function gestion() {
        require_once __DIR__ . '/../views/ADMIN/GestionReservas.php';
    }

    public function listarReservas() {
        header('Content-Type: application/json');
        $reservas = $this->reservaModel->obtenerReservasActivas();
        echo json_encode(['success' => true, 'data' => $reservas]);
        exit;
    }

    public function registrarReservaAdmin() {
        header('Content-Type: application/json');
        $idUsuario = $_POST['id_usuario'] ?? null;
        $idLibro = $_POST['id_libro'] ?? null;

        if (!$idUsuario || !$idLibro) {
            echo json_encode(['success' => false, 'message' => 'ID de usuario y de libro son requeridos.']);
            exit;
        }

        // Verificar el límite de reservas por usuario
        $reservasActivas = $this->reservaModel->contarReservasActivasPorUsuario((int)$idUsuario);
        if ($reservasActivas >= 3) {
            echo json_encode(['success' => false, 'message' => 'El usuario ya tiene 3 reservas activas.']);
            exit;
        }

        $resultado = $this->reservaModel->crearReserva((int)$idUsuario, (int)$idLibro);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Reserva registrada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la reserva.']);
        }
        exit;
    }

    public function convertirReservaAPrestamo() {
        header('Content-Type: application/json');
        $idReserva = $_POST['id_reserva'] ?? null;
        $idUsuario = $_POST['id_usuario'] ?? null;
        $idLibro = $_POST['id_libro'] ?? null;

        if (!$idReserva || !$idUsuario || !$idLibro) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos para generar el préstamo.']);
            exit;
        }

        $prestamoModelo = new PrestamoModelo($this->db);

        // Verificar el límite de préstamos por usuario
        $prestamosActivos = $prestamoModelo->contarPrestamosActivosPorUsuario((int)$idUsuario);
        if ($prestamosActivos >= 3) {
            echo json_encode(['success' => false, 'message' => 'El usuario ya tiene 3 préstamos activos. No se puede realizar un nuevo préstamo.']);
            exit;
        }
        
        $fechaPrestamo = date('Y-m-d H:i:s');
        $fechaDevolucion = date('Y-m-d', strtotime('+7 days'));

        $prestamoOk = $prestamoModelo->registrarPrestamo((int)$idUsuario, (int)$idLibro, $fechaPrestamo, $fechaDevolucion);

        if ($prestamoOk) {
            $reservaOk = $this->reservaModel->marcarComoPrestado((int)$idReserva);
            if ($reservaOk) {
                echo json_encode(['success' => true, 'message' => 'Préstamo generado y reserva actualizada.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Préstamo generado, pero hubo un error al actualizar el estado de la reserva.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al generar el préstamo. Verifique la disponibilidad del libro.']);
        }
        exit;
    }

    // --- Métodos públicos existentes ---

    public function nueva()
    {
        session_start();

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?controller=Usuario&action=login");
            exit;
        }

        $idLibro = (int)($_GET['id_libro'] ?? 0);
        if ($idLibro <= 0) {
            die("ID de libro inválido.");
        }

        require_once __DIR__ . '/../models/Libro.php';
        $libroModel = new Libro($this->db);
        $libro = $libroModel->obtenerPorId($idLibro);

        require __DIR__ . '/../views/reservas/reserva.php';
    }

    public function guardar()
    {
        session_start();

        if (!isset($_SESSION['id_usuario'])) {
            header("Location: index.php?controller=Usuario&action=login");
            exit;
        }

        $idLibro = (int)($_POST['id_libro'] ?? 0);
        $idUsuario = $_SESSION['id_usuario'];

        if ($idLibro <= 0) {
            die("ID de libro inválido.");
        }

        // Verificar el límite de reservas por usuario
        $reservasActivas = $this->reservaModel->contarReservasActivasPorUsuario($idUsuario);
        if ($reservasActivas >= 3) {
            $mensaje = 'Ya tiene 3 reservas activas. No puede realizar más reservas.';
            // Asumiendo que la vista de libros puede mostrar un mensaje de error.
            header('Location: index.php?controller=libro&action=listar&msg_error=' . urlencode($mensaje));
            exit;
        }
        
        $this->reservaModel->crearReserva($idUsuario, $idLibro);

        header("Location: index.php?controller=Reserva&action=confirmacion");
    }

    public function confirmacion()
    {
        require __DIR__ . '/../views/reservas/confirmacion.php';
    }
}

