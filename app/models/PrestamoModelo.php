<?php
require_once __DIR__ . '/../../config/Conexion.php';

class PrestamoModelo {

    private $db;

    public function __construct($db = null) {
        if ($db instanceof mysqli) {
            $this->db = $db;
        } else {
            $this->db = (new Conexion())->conectar();
        }
    }

    // Registrar préstamo con la nueva estructura
    public function registrarPrestamo($idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion) {

        $this->db->begin_transaction();

        try {

            // 1. Verificar disponibilidad REAL
            $sqlDisp = "SELECT id_disponibilidad, cantidad_disponible 
                        FROM disponibilidad 
                        WHERE id_libro = ? FOR UPDATE";

            $stmtDisp = $this->db->prepare($sqlDisp);
            $stmtDisp->bind_param("i", $idLibro);
            $stmtDisp->execute();
            $res = $stmtDisp->get_result();

            if ($res->num_rows == 0) {
                throw new Exception("No existe registro en disponibilidad para este libro");
            }

            $row = $res->fetch_assoc();
            $cantidad = intval($row["cantidad_disponible"]);

            if ($cantidad <= 0) {
                throw new Exception("No hay unidades disponibles");
            }

            // 2. Insertar préstamo
            $sqlPrestamo = "INSERT INTO prestamo 
                           (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, estado)
                           VALUES (?, ?, ?, ?, 'activo')";

            $stmtPre = $this->db->prepare($sqlPrestamo);
            $stmtPre->bind_param("iiss", $idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);

            if (!$stmtPre->execute()) {
                throw new Exception("Error al registrar el préstamo: " . $stmtPre->error);
            }

            // 3. Actualizar disponibilidad
            $nuevoValor = $cantidad - 1;
            $nuevoEstado = ($nuevoValor > 0) ? 1 : 2; // 1=disponible, 2=prestado 

            $sqlUpdate = "UPDATE disponibilidad 
                          SET cantidad_disponible = ?, id_estado = ?
                          WHERE id_libro = ?";

            $stmtUpd = $this->db->prepare($sqlUpdate);
            if ($stmtUpd === false) { // Verificar si la preparación falló
                throw new Exception("Error al preparar la actualización de disponibilidad: " . $this->db->error);
            }
            $stmtUpd->bind_param("iii", $nuevoValor, $nuevoEstado, $idLibro);
            if (!$stmtUpd->execute()) { // Verificar si la ejecución falló
                throw new Exception("Error al actualizar disponibilidad: " . $stmtUpd->error);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error en prestar: " . $e->getMessage());
            return false;
        }
    }


    // Registrar devolución
    public function registrarDevolucion($idPrestamo) {

        $this->db->begin_transaction();

        try {

            // 1. Buscar préstamo
            $sqlSel = "SELECT id_libro, estado 
                       FROM prestamo 
                       WHERE id_prestamo = ? FOR UPDATE";

            $stmt = $this->db->prepare($sqlSel);
            $stmt->bind_param("i", $idPrestamo);
            $stmt->execute();
            $data = $stmt->get_result();

            if ($data->num_rows == 0) {
                throw new Exception("Préstamo no encontrado");
            }

            $prestamo = $data->fetch_assoc();

            if ($prestamo["estado"] === "devuelto") {
                $this->db->commit();
                return true;
            }

            $idLibro = intval($prestamo["id_libro"]);

            // 2. Marcar préstamo como devuelto
            $sqlUpdPrestamo = "UPDATE prestamo SET estado='devuelto' WHERE id_prestamo=?";
            $stmtUpd = $this->db->prepare($sqlUpdPrestamo);
            $stmtUpd->bind_param("i", $idPrestamo);
            $stmtUpd->execute();

            // 3. Actualizar disponibilidad
            $sqlDisp = "SELECT cantidad_disponible FROM disponibilidad WHERE id_libro = ? FOR UPDATE";
            $stmtDisp = $this->db->prepare($sqlDisp);
            $stmtDisp->bind_param("i", $idLibro);
            $stmtDisp->execute();
            $res = $stmtDisp->get_result();
            $row = $res->fetch_assoc();

            $nueva = intval($row["cantidad_disponible"]) + 1;

            $sqlUpdate = "UPDATE disponibilidad 
                          SET cantidad_disponible=?, id_estado=1
                          WHERE id_libro=?";

            $stmtUpdDisp = $this->db->prepare($sqlUpdate);
            if ($stmtUpdDisp === false) {
                throw new Exception("Error al preparar la actualización de devolución: " . $this->db->error);
            }
            $stmtUpdDisp->bind_param("ii", $nueva, $idLibro);
            $stmtUpdDisp->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error en devolucion: " . $e->getMessage());
            return false;
        }
    }

    public function contarPrestamosActivos() {
        $sql = "SELECT COUNT(id_prestamo) as total FROM prestamo WHERE estado IN ('activo', 'retrasado')";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    public function contarPrestamosActivosPorUsuario($idUsuario) {
        $sql = "SELECT COUNT(id_prestamo) as total FROM prestamo WHERE id_usuario = ? AND estado IN ('activo', 'retrasado')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    public function obtenerUltimosPrestamos($limite = 5) {
        $sql = "SELECT 
                    p.id_prestamo,
                    u.nombre as nombre_usuario,
                    l.titulo as titulo_libro,
                    p.fecha_prestamo,
                    p.fecha_devolucion
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN libro l ON p.id_libro = l.id_libro
                ORDER BY p.fecha_prestamo DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

       public function obtenerLibroConDisponibilidad($id_libro)
   {
      $sql = "SELECT l.*, d.cantidad_disponible 
            FROM libro l
            INNER JOIN disponibilidad d ON d.id_libro = l.id_libro
            WHERE l.id_libro = ?";
      $stmt = $this->conn->prepare($sql);
      $stmt->bind_param("i", $id_libro);
      $stmt->execute();
      return $stmt->get_result()->fetch_assoc();
    }

       public function obtenerDisponibilidad($id_libro)
 {
    $sql = "SELECT cantidad_disponible FROM disponibilidad WHERE id_libro = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_libro);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
 }
   public function restarDisponibilidad($id_libro)
{
    $sql = "UPDATE disponibilidad 
            SET cantidad_disponible = cantidad_disponible - 1
            WHERE id_libro = ? AND cantidad_disponible > 0";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id_libro);
    return $stmt->execute();
}

    public function obtenerPrestamosActivos() {
        $sql = "SELECT 
                    p.id_prestamo,
                    l.titulo as libro_titulo,
                    l.Imagen as libro_imagen,
                    u.nombre as usuario_nombre,
                    u.numero_documento,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.estado
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.estado IN ('activo', 'retrasado')
                ORDER BY p.fecha_prestamo DESC";
        
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function eliminarPrestamo($idPrestamo) {
        $this->db->begin_transaction();

        try {
            // 1. Obtener el id_libro del préstamo antes de eliminarlo
            $sqlSelect = "SELECT id_libro FROM prestamo WHERE id_prestamo = ?";
            $stmtSelect = $this->db->prepare($sqlSelect);
            $stmtSelect->bind_param("i", $idPrestamo);
            $stmtSelect->execute();
            $resultado = $stmtSelect->get_result();

            if ($resultado->num_rows === 0) {
                throw new Exception("El préstamo no existe.");
            }

            $idLibro = $resultado->fetch_assoc()['id_libro'];

            // 2. Eliminar el préstamo
            $sqlDelete = "DELETE FROM prestamo WHERE id_prestamo = ?";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $idPrestamo);
            if (!$stmtDelete->execute()) {
                throw new Exception("Error al eliminar el préstamo.");
            }

            // 3. Incrementar la cantidad disponible del libro
            $sqlUpdate = "UPDATE disponibilidad SET cantidad_disponible = cantidad_disponible + 1, id_estado = 1 WHERE id_libro = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $idLibro);
            if (!$stmtUpdate->execute()) {
                throw new Exception("Error al actualizar la disponibilidad del libro.");
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error en eliminarPrestamo: " . $e->getMessage());
            return false;
        }
    }

    public function getPrestamoById($idPrestamo) {
        $sql = "SELECT id_prestamo, id_libro, id_usuario, fecha_prestamo, fecha_devolucion, estado 
                FROM prestamo 
                WHERE id_prestamo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idPrestamo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarPrestamo($idPrestamo, $fechaDevolucion, $estado) {
        $sql = "UPDATE prestamo SET fecha_devolucion = ?, estado = ? WHERE id_prestamo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $fechaDevolucion, $estado, $idPrestamo);
        return $stmt->execute();
    }

    public function actualizarEstadosDePrestamosRetrasados() {
        $sql = "UPDATE prestamo SET estado = 'retrasado' WHERE fecha_devolucion < CURDATE() AND estado = 'activo'";
        $this->db->query($sql);
    }

    public function obtenerHistorialDeLectura($idUsuario) {
        $sql = "SELECT 
                    p.id_prestamo,
                    p.fecha_devolucion,
                    l.id_libro,
                    l.titulo,
                    l.Imagen
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE p.id_usuario = ? AND p.estado = 'devuelto'
                ORDER BY p.fecha_devolucion DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
}
