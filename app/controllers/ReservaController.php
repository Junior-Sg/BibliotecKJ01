<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../../config/Conexion.php';

class ReservaController extends BaseController
{
    private $reservaModel;
    private $db;

    public function __construct() {
        parent::__construct();
        $this->db = (new Conexion())->conectar();
        $this->reservaModel = new Reserva($this->db);
    }

    public function gestion() {
        if (!$this->isAdmin()) {
            $this->redirect('LoginUsuario', 'index');
        }
        require_once __DIR__ . '/../views/ADMIN/GestionReservas.php';
    }

    public function listarReservas() {
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
            exit;
        }
        header('Content-Type: application/json');
        $reservas = $this->reservaModel->obtenerReservasActivas();
        echo json_encode(['success' => true, 'data' => $reservas]);
        exit;
    }

    public function registrarReservaAdmin() {
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
            exit;
        }
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
        if (!$this->isAdmin()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Acceso denegado.']);
            exit;
        }
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
        if (!$this->isLoggedIn()) {
            $this->redirect('LoginUsuario', 'index');
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
        if (!$this->isLoggedIn()) {
            $this->redirect('LoginUsuario', 'index');
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

    public function guardarAjax()
    {
        header('Content-Type: application/json; charset=utf-8');
        if (!$this->isLoggedIn()) {
            echo json_encode(['ok' => false, 'error' => 'No autenticado']);
            exit;
        }

        $idLibro = (int)($_POST['id_libro'] ?? 0);
        $idUsuario = (int)$_SESSION['id_usuario'];

        if ($idLibro <= 0) {
            echo json_encode(['ok' => false, 'error' => 'ID de libro inválido']);
            exit;
        }

        // Límite de reservas por usuario (misma regla que en guardar())
        $reservasActivas = $this->reservaModel->contarReservasActivasPorUsuario($idUsuario);
        if ($reservasActivas >= 3) {
            echo json_encode(['ok' => false, 'error' => 'Límite de reservas alcanzado (3)']);
            exit;
        }

        $ok = $this->reservaModel->crearReserva($idUsuario, $idLibro);
        if ($ok) {
            echo json_encode(['ok' => true]);
        } else {
            echo json_encode(['ok' => false, 'error' => 'Error al crear la reserva']);
        }
        exit;
    }
}

