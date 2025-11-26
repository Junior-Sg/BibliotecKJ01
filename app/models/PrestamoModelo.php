<?php
require_once __DIR__ . '/../../config/Conexion.php';

class PrestamoModelo {

    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
    }

    // Registrar préstamo: insertar en tabla prestamo y decrementar cantidad_total del libro
    public function registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion) {
        $this->db->begin_transaction();
        try {
            $sql = "INSERT INTO prestamo (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, estado)
                    VALUES (?, ?, ?, ?, 'Prestado')";

            $stmt = $this->db->prepare($sql);
            if (!$stmt) throw new Exception('Error preparar insert prestamo: ' . $this->db->error);
            $stmt->bind_param('iiss', $idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);
            if (!$stmt->execute()) throw new Exception('Error ejecutar insert prestamo: ' . $stmt->error);

            // Decrementar cantidad_total si es mayor a 0 (o NULL -> dejar NULL)
            $sql2 = "UPDATE libro SET cantidad_total = CASE WHEN cantidad_total IS NULL THEN NULL WHEN cantidad_total > 0 THEN cantidad_total - 1 ELSE 0 END WHERE id_libro = ?";
            $stmt2 = $this->db->prepare($sql2);
            if (!$stmt2) throw new Exception('Error preparar update libro: ' . $this->db->error);
            $stmt2->bind_param('i', $idLibro);
            if (!$stmt2->execute()) throw new Exception('Error ejecutar update libro: ' . $stmt2->error);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            return false;
        }
    }
}
