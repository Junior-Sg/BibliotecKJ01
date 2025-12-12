<?php

require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/PrestamoModelo.php';
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../helpers/Mailer.php';

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
            // Enviar notificación de reserva al usuario (no bloquear si falla)
            try {
                require_once __DIR__ . '/../models/Libro.php';
                require_once __DIR__ . '/../models/Usuario.php';

                $libroModel = new Libro($this->db);
                $usuarioModel = new Usuario($this->db);

                $libro = $libroModel->obtenerPorId((int)$idLibro);
                $usuario = $usuarioModel->obtenerPorId((int)$idUsuario);

                if ($libro && $usuario && !empty($usuario['correo'])) {
                    $mailer = new Mailer();
                    $tituloLibro = htmlspecialchars($libro['titulo'] ?? 'Desconocido');
                    $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                    $usuarioCorreo = $usuario['correo'];
                    $fechaReserva = date('d/m/Y');
                    $fechaLimite = date('d/m/Y', strtotime('+7 days'));

                    $contenido = "
                        Estimado(a) <strong>$usuarioNombre</strong><br><br>
                        Tu reserva del libro <strong>$tituloLibro</strong> fue registrada con éxito por el administrador.<br><br>
                        <strong>Detalles de la reserva:</strong><br>
                        Fecha de reserva: <strong>$fechaReserva</strong><br>
                        Disponible para recoger antes de: <strong>$fechaLimite</strong><br><br>
                        Por favor, acércate a la biblioteca para completar el proceso de préstamo.
                    ";

                    $mailer->send($usuarioCorreo, "Confirmación de Reserva", $contenido);
                }
            } catch (Exception $e) {
                error_log("Error al enviar notificación de reserva (admin): " . $e->getMessage());
            }

            echo json_encode(['success' => true, 'message' => 'Reserva registrada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al registrar la reserva.']);
        }
        exit;
    }

    public function convertirReservaAPrestamo() {
        header('Content-Type: application/json');
        try {
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
                    // Enviar notificación de préstamo confirmado (sin parar si hay error)
                    try {
                        require_once __DIR__ . '/../models/Libro.php';
                        require_once __DIR__ . '/../models/Usuario.php';
                        
                        $libroModel = new Libro($this->db);
                        $usuarioModel = new Usuario($this->db);
                        
                        $libro = $libroModel->obtenerPorId((int)$idLibro);
                        $usuario = $usuarioModel->obtenerPorId((int)$idUsuario);
                        
                        if ($libro && $usuario && !empty($usuario['correo'])) {
                            require_once __DIR__ . '/../helpers/Mailer.php';
                            $mailer = new Mailer();
                            $tituloLibro = htmlspecialchars($libro['titulo'] ?? 'Desconocido');
                            $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                            $usuarioCorreo = $usuario['correo'];
                            $fechaInicio = date('d/m/Y', strtotime($fechaPrestamo));
                            $fechaFin = date('d/m/Y', strtotime($fechaDevolucion));
                            
                            $contenido = "
                                Estimado(a) <strong>$usuarioNombre</strong><br><br>
                                Tu reserva ha sido confirmada y convertida en préstamo. ¡Disfruta del libro!<br><br>
                                <strong>Detalles del préstamo:</strong><br>
                                Libro: <strong>$tituloLibro</strong><br>
                                Fecha de préstamo: <strong>$fechaInicio</strong><br>
                                Fecha de entrega: <strong>$fechaFin</strong><br><br>
                                Por favor, devuelve el libro en la fecha indicada para evitar sanciones.
                            ";
                            
                            $mailer->send($usuarioCorreo, "Préstamo Confirmado", $contenido);
                        }
                    } catch (Exception $e) {
                        // Log del error pero no interrumpir el flujo
                        error_log("Error al enviar notificación de préstamo: " . $e->getMessage());
                    }
                    
                    echo json_encode(['success' => true, 'message' => 'Préstamo generado y reserva actualizada.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Préstamo generado, pero hubo un error al actualizar el estado de la reserva.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al generar el préstamo. Verifique la disponibilidad del libro.']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error al procesar la solicitud: ' . $e->getMessage()]);
        }
        exit;
    }

    public function eliminarReserva() {
        header('Content-Type: application/json');
        $idReserva = $_POST['id_reserva'] ?? null;

        if (!$idReserva) {
            echo json_encode(['success' => false, 'message' => 'ID de reserva no proporcionado.']);
            exit;
        }

        $resultado = $this->reservaModel->eliminarReserva((int)$idReserva);

        if ($resultado) {
            echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al eliminar la reserva.']);
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
            header('Location: index.php?controller=libro&action=listar&msg_error=' . urlencode($mensaje));
            exit;
        }
        
        // Crear la reserva
        if ($this->reservaModel->crearReserva($idUsuario, $idLibro)) {
            // Obtener datos para enviar el correo (sin parar si hay error)
            try {
                require_once __DIR__ . '/../models/Libro.php';
                require_once __DIR__ . '/../models/Usuario.php';
                
                $libroModel = new Libro($this->db);
                $usuarioModel = new Usuario($this->db);
                
                $libro = $libroModel->obtenerPorId($idLibro);
                $usuario = $usuarioModel->obtenerPorId($idUsuario);
                
                if ($libro && $usuario && !empty($usuario['correo'])) {
                    $mailer = new Mailer();
                    $tituloLibro = htmlspecialchars($libro['titulo'] ?? 'Desconocido');
                    $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                    $usuarioCorreo = $usuario['correo'];
                    $fechaReserva = date('d/m/Y');
                    $fechaLimite = date('d/m/Y', strtotime('+7 days'));
                    
                    $contenido = "
                        Estimado(a) <strong>$usuarioNombre</strong><br><br>
                        Tu reserva del libro <strong>$tituloLibro</strong> fue registrada con éxito.<br><br>
                        <strong>Detalles de la reserva:</strong><br>
                        Fecha de reserva: <strong>$fechaReserva</strong><br>
                        Disponible para recoger antes de: <strong>$fechaLimite</strong><br><br>
                        Por favor, acércate a la biblioteca para completar el proceso de préstamo.
                    ";
                    
                    $mailer->send($usuarioCorreo, "Confirmación de Reserva", $contenido);
                }
            } catch (Exception $e) {
                // Log del error pero no interrumpir el flujo
                error_log("Error al enviar notificación de reserva: " . $e->getMessage());
            }
        }

        header("Location: index.php?controller=Reserva&action=confirmacion");
    }

    public function confirmacion()
    {
        require __DIR__ . '/../views/reservas/confirmacion.php';
    }
}

