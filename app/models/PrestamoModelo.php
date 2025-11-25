<?php

class PrestamoModelo {

    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    public function registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion) {

        // Insertar el préstamo
        $sql = "INSERT INTO Prestamo (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, estado)
                VALUES (:id_usuario, :id_libro, :fecha_prestamo, :fecha_devolucion, 'Prestado')";

        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':id_usuario', $idUsuario);
        $consulta->bindParam(':id_libro', $idLibro);
        $consulta->bindParam(':fecha_prestamo', $fechaPrestamo);
        $consulta->bindParam(':fecha_devolucion', $fechaDevolucion);

        // Cambiar estado del libro a "Prestado"
        $sql2 = "UPDATE Libro SET estado = 'Prestado' WHERE id_libro = :id_libro";
        $update = $this->db->prepare($sql2);
        $update->bindParam(':id_libro', $idLibro);

        if ($consulta->execute() && $update->execute()) {
            return true;
        }
        return false;
    }
}
