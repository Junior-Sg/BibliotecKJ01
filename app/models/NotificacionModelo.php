<?php

class NotificacionModelo
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    /**
     * Crear una nueva notificación
     */
    public function crearNotificacion(int $idUsuario, string $mensaje)
    {
        $sql = "INSERT INTO notificaciones (id_usuario, mensaje) VALUES (?, ?)";

        $stmt = $this->db->prepare($sql);
        // "is" significa: i = entero (integer), s = cadena (string)
        $stmt->bind_param("is", $idUsuario, $mensaje);

        return $stmt->execute();
    }

    /**
     * Obtener notificaciones de un usuario
     */
    public function obtenerPorUsuario(int $idUsuario, bool $soloNoLeidas = false): array
    {
        $sql = "SELECT * FROM notificaciones WHERE id_usuario = ?";

        if ($soloNoLeidas) {
            $sql .= " AND leido = 0";
        }

        $sql .= " ORDER BY fecha_creacion DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        
        // Retorna todos los registros como un array asociativo
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarComoLeida(int $idNotificacion, int $idUsuario = null): bool
    {
        if ($idUsuario !== null) {
            $sql = "UPDATE notificaciones SET leido = 1 WHERE id_notificacion = ? AND id_usuario = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ii", $idNotificacion, $idUsuario);
        } else {
            $sql = "UPDATE notificaciones SET leido = 1 WHERE id_notificacion = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $idNotificacion);
        }
        
        return $stmt->execute();
    }

    /**
     * Contar notificaciones no leídas
     */
    public function contarNoLeidas(int $idUsuario): int
    {
        $sql = "SELECT COUNT(*) AS total FROM notificaciones WHERE id_usuario = ? AND leido = 0";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();

        return (int) ($fila['total'] ?? 0);
    }

    /**
     * Eliminar una notificación
     */
    public function eliminarNotificacion(int $idNotificacion, int $idUsuario): bool
    {
        $sql = "DELETE FROM notificaciones WHERE id_notificacion = ? AND id_usuario = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idNotificacion, $idUsuario);
        return $stmt->execute();
    }
}
