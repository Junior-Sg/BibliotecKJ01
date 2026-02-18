<?php
require_once __DIR__ . '/../../config/Conexion.php';

class ReportesModelo {
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    // Contar libros prestados actualmente (para tarjeta)
    public function contarLibrosPrestadosActualmente() {
        $sql = "SELECT COUNT(*) as total FROM prestamo WHERE estado != 'devuelto' AND estado != 'Devuelto'";
        $result = $this->db->query($sql);
        return $result->fetch_assoc()['total'];
    }

    // Obtener DETALLES de libros prestados actualmente (para Excel)
    public function obtenerLibrosPrestadosDetallado() {
        $sql = "SELECT p.id_prestamo, u.nombre AS usuario, u.numero_documento, l.titulo AS libro, 
                       l.id_libro, p.fecha_prestamo, p.fecha_devolucion, p.estado
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE p.estado != 'devuelto' AND p.estado != 'Devuelto'
                ORDER BY p.fecha_prestamo DESC";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Contar nuevos usuarios por mes (para grÃ¡fico)
    public function contarNuevosUsuariosPorMes() {
        $sql = "SELECT YEAR(NOW()) as anio, MONTH(NOW()) as mes, COUNT(*) as total 
                FROM usuario 
                WHERE YEAR(NOW()) = YEAR(NOW())
                GROUP BY MONTH(NOW())";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Obtener DETALLES de nuevos usuarios (para Excel)
    public function obtenerNuevosUsuariosDetallado($mes = null, $anio = null) {
        $where = "";
        if ($mes && $anio) {
            $where = "WHERE MONTH(u.fecha_registro) = $mes AND YEAR(u.fecha_registro) = $anio";
        }
        $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.numero_documento, u.tipo_documento, u.telefono
                FROM usuario u
                $where
                ORDER BY u.fecha_registro DESC";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Contar prÃ©stamos por mes (para grÃ¡fico)
    public function contarPrestamosPorMes() {
        $sql = "SELECT YEAR(fecha_prestamo) as anio, MONTH(fecha_prestamo) as mes, COUNT(*) as total 
                FROM prestamo 
                GROUP BY anio, mes 
                ORDER BY anio, mes";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Obtener DETALLES de prÃ©stamos (para Excel)
    public function obtenerPrestamosPorMesDetallado($mes = null, $anio = null) {
        $where = "";
        if ($mes && $anio) {
            $where = "WHERE MONTH(p.fecha_prestamo) = $mes AND YEAR(p.fecha_prestamo) = $anio";
        }
        $sql = "SELECT p.id_prestamo, u.nombre AS usuario, u.numero_documento, l.titulo AS libro,
                       p.fecha_prestamo, p.fecha_devolucion, p.estado
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN libro l ON p.id_libro = l.id_libro
                $where
                ORDER BY p.fecha_prestamo DESC";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Contar reservas por mes (para grÃ¡fico)
    public function contarReservasPorMes() {
        $sql = "SELECT YEAR(fecha_reserva) as anio, MONTH(fecha_reserva) as mes, COUNT(*) as total 
                FROM reserva 
                GROUP BY anio, mes 
                ORDER BY anio, mes";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    // Obtener DETALLES de reservas (para Excel)
    public function obtenerReservasDetallado($mes = null, $anio = null) {
        $where = "";
        if ($mes && $anio) {
            $where = "WHERE MONTH(r.fecha_reserva) = $mes AND YEAR(r.fecha_reserva) = $anio";
        }
        $sql = "SELECT r.id_reserva, u.nombre AS usuario, u.numero_documento, l.titulo AS libro,
                       r.fecha_reserva, r.estado
                FROM reserva r
                JOIN usuario u ON r.id_usuario = u.id_usuario
                JOIN libro l ON r.id_libro = l.id_libro
                $where
                ORDER BY r.fecha_reserva DESC";
        $result = $this->db->query($sql);
        $datos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos[] = $row;
            }
        }
        return $datos;
    }

    public function obtenerDatosReportePorMes($mes, $anio) {
        $datos = [];

        $sql = "SELECT p.id_prestamo, u.nombre AS usuario, u.numero_documento, l.titulo AS libro,
                       p.fecha_prestamo, p.fecha_devolucion, p.estado
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE MONTH(p.fecha_prestamo) = $mes AND YEAR(p.fecha_prestamo) = $anio
                ORDER BY p.fecha_prestamo DESC";
        $result = $this->db->query($sql);
        $datos['prestamos'] = [];
        $datos['prestamos_count'] = 0;
        $datos['activos_count'] = 0;
        $datos['devueltos_count'] = 0;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos['prestamos'][] = $row;
                $datos['prestamos_count']++;
                if ($row['estado'] === 'devuelto' || $row['estado'] === 'Devuelto') {
                    $datos['devueltos_count']++;
                } else {
                    $datos['activos_count']++;
                }
            }
        }

        $sql = "SELECT id_usuario, nombre, correo, numero_documento, telefono
                FROM usuario
                WHERE MONTH(fecha_registro) = $mes AND YEAR(fecha_registro) = $anio
                ORDER BY fecha_registro DESC";
        $result = $this->db->query($sql);
        $datos['usuarios'] = [];
        $datos['usuarios_count'] = 0;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos['usuarios'][] = $row;
                $datos['usuarios_count']++;
            }
        }

        $sql = "SELECT r.id_reserva, u.nombre AS usuario, u.numero_documento, l.titulo AS libro,
                       r.fecha_reserva, r.estado
                FROM reserva r
                JOIN usuario u ON r.id_usuario = u.id_usuario
                JOIN libro l ON r.id_libro = l.id_libro
                WHERE MONTH(r.fecha_reserva) = $mes AND YEAR(r.fecha_reserva) = $anio
                ORDER BY r.fecha_reserva DESC";
        $result = $this->db->query($sql);
        $datos['reservas'] = [];
        $datos['reservas_count'] = 0;
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $datos['reservas'][] = $row;
                $datos['reservas_count']++;
            }
        }

        return $datos;
    }
}
?>
