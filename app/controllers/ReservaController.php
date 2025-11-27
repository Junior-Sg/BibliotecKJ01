<?php

class ReservaController
{
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

        // Aquí puedes traer datos del libro si quieres mostrarlo
        require_once __DIR__ . '/../models/Libro.php';
        $conexion = new Conexion();
        $libroModel = new Libro($conexion->conectar());
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
        require_once __DIR__ . '/../models/Reserva.php';
        $conexion = new Conexion();
        $reservaModel = new Reserva($conexion->conectar());
        $reservaModel->crearReserva($idUsuario, $idLibro);
        $reservaModel->crearReserva($idUsuario, $idLibro);

        header("Location: index.php?controller=Reserva&action=confirmacion");
    }

    public function confirmacion()
    {
        require __DIR__ . '/../views/reservas/confirmacion.php';
    }
}

