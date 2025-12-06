<?php

class Reserva
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearReserva($idUsuario, $idLibro)
    {
        // Usar sentencias preparadas para seguridad
        $sql = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva, estado)
                VALUES (?, ?, CURDATE(), 'pendiente')";
        
        $stmt = $this->conexion->prepare($sql);
        if ($stmt === false) {
            // Manejar error de preparación
            return false;
        }

        $stmt->bind_param("ii", $idUsuario, $idLibro);
        return $stmt->execute();
    }

    public function obtenerReservasActivas() {
        $sql = "SELECT 
                    r.id_reserva,
                    r.fecha_reserva,
                    u.id_usuario,
                    u.nombre as usuario_nombre,
                    u.numero_documento,
                    l.id_libro,
                    l.titulo as libro_titulo,
                    l.Imagen as libro_imagen
                FROM reserva r
                JOIN usuario u ON r.id_usuario = u.id_usuario
                JOIN libro l ON r.id_libro = l.id_libro
                WHERE r.estado = 'pendiente'
                ORDER BY r.fecha_reserva ASC";
        
        $resultado = $this->conexion->query($sql);
        if ($resultado) {
            return $resultado->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function marcarComoPrestado($id_reserva) {
        $sql = "UPDATE reserva SET estado = 'prestado' WHERE id_reserva = ?";
        $stmt = $this->conexion->prepare($sql);
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("i", $id_reserva);
        return $stmt->execute();
    }
}
