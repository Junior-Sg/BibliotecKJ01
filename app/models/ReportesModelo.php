<?php
require_once __DIR__ . '/../../config/Conexion.php';

class ReportesModelo {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    public function contarLibrosPrestadosActualmente() {
        $sql = "SELECT COUNT(*) as total FROM prestamo WHERE estado = 'activo'";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['total'];
    }

    public function contarNuevosUsuariosPorMes() {
        $sql = "SELECT YEAR(fecha_registro) as anio, MONTH(fecha_registro) as mes, COUNT(*) as total 
                FROM usuario 
                GROUP BY anio, mes 
                ORDER BY anio, mes";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function contarPrestamosPorMes() {
        $sql = "SELECT YEAR(fecha_prestamo) as anio, MONTH(fecha_prestamo) as mes, COUNT(*) as total 
                FROM prestamo 
                GROUP BY anio, mes 
                ORDER BY anio, mes";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function contarReservasPorMes() {
        $sql = "SELECT YEAR(fecha_reserva) as anio, MONTH(fecha_reserva) as mes, COUNT(*) as total 
                FROM reserva 
                GROUP BY anio, mes 
                ORDER BY anio, mes";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>