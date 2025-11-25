<?php

class Inicio {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTotalLibros() {
        $consulta = "SELECT COUNT(*) AS total FROM libro";
        return $this->conexion->query($consulta)->fetch_assoc()['total'];
    }

    public function obtenerPrestamosActivos() {
        $consulta = "SELECT COUNT(*) AS total FROM prestamo WHERE estado = 'activo'";
        return $this->conexion->query($consulta)->fetch_assoc()['total'];
    }

    public function obtenerTotalUsuarios() {
        $consulta = "SELECT COUNT(*) AS total FROM usuario";
        return $this->conexion->query($consulta)->fetch_assoc()['total'];
    }

    public function obtenerUltimosPrestamos() {
        $consulta = "
            SELECT 
                usuario.nombre AS usuario, 
                libro.titulo AS libro
            FROM prestamo
            INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario
            INNER JOIN libro ON prestamo.id_libro = libro.id_libro
            ORDER BY prestamo.id_prestamo DESC
            LIMIT 5
        ";
        return $this->conexion->query($consulta);
    }
}
