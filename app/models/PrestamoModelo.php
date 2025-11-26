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

            // Actualizar tabla disponibilidad dentro de la misma transacción
            $res = $this->db->query("SELECT cantidad_actual FROM disponibilidad WHERE id_libro = " . intval($idLibro) . " FOR UPDATE");
            if ($res && $res->num_rows > 0) {
                $r = $res->fetch_assoc();
                $cant = $r['cantidad_actual'] === null ? null : intval($r['cantidad_actual']);
                if ($cant !== null) {
                    $nueva = max(0, $cant - 1);
                    $estado = $nueva > 0 ? 'disponible' : 'prestado';
                    $stmt3 = $this->db->prepare("UPDATE disponibilidad SET cantidad_actual = ?, estado = ?, fecha_actualizacion = NOW() WHERE id_libro = ?");
                    if (!$stmt3) throw new Exception('Error preparar update disponibilidad: ' . $this->db->error);
                    $stmt3->bind_param('isi', $nueva, $estado, $idLibro);
                    if (!$stmt3->execute()) throw new Exception('Error ejecutar update disponibilidad: ' . $stmt3->error);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log($e->getMessage());
            return false;
        }
    }

    // Registrar devolución: marcar préstamo como Devuelto, incrementar cantidad y disponibilidad
    public function registrarDevolucion($idPrestamo) {
        $this->db->begin_transaction();
        try {
            // obtener préstamo
            $stmt = $this->db->prepare("SELECT id_libro, estado FROM prestamo WHERE id_prestamo = ? FOR UPDATE");
            if (!$stmt) throw new Exception('Error preparar select prestamo: ' . $this->db->error);
            $stmt->bind_param('i', $idPrestamo);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res->num_rows === 0) throw new Exception('Préstamo no encontrado');
            $row = $res->fetch_assoc();
            if (strtolower($row['estado']) === 'devuelto') {
                // ya devuelto
                $this->db->commit();
                return true;
            }
            $idLibro = intval($row['id_libro']);

            // actualizar prestamo
            $upd = $this->db->prepare("UPDATE prestamo SET estado = 'Devuelto' WHERE id_prestamo = ?");
            if (!$upd) throw new Exception('Error preparar update prestamo: ' . $this->db->error);
            $upd->bind_param('i', $idPrestamo);
            if (!$upd->execute()) throw new Exception('Error ejecutar update prestamo: ' . $upd->error);

            // incrementar libro.cantidad_total
            $stmt2 = $this->db->prepare("UPDATE libro SET cantidad_total = CASE WHEN cantidad_total IS NULL THEN NULL ELSE cantidad_total + 1 END WHERE id_libro = ?");
            if (!$stmt2) throw new Exception('Error preparar update libro: ' . $this->db->error);
            $stmt2->bind_param('i', $idLibro);
            if (!$stmt2->execute()) throw new Exception('Error ejecutar update libro: ' . $stmt2->error);

            // actualizar disponibilidad
            $res2 = $this->db->query("SELECT cantidad_actual FROM disponibilidad WHERE id_libro = " . intval($idLibro) . " FOR UPDATE");
            if ($res2 && $res2->num_rows > 0) {
                $r2 = $res2->fetch_assoc();
                $cant = $r2['cantidad_actual'] === null ? null : intval($r2['cantidad_actual']);
                if ($cant !== null) {
                    $nueva = $cant + 1;
                    $estado = $nueva > 0 ? 'disponible' : 'prestado';
                    $stmt3 = $this->db->prepare("UPDATE disponibilidad SET cantidad_actual = ?, estado = ?, fecha_actualizacion = NOW() WHERE id_libro = ?");
                    if (!$stmt3) throw new Exception('Error preparar update disponibilidad: ' . $this->db->error);
                    $stmt3->bind_param('isi', $nueva, $estado, $idLibro);
                    if (!$stmt3->execute()) throw new Exception('Error ejecutar update disponibilidad: ' . $stmt3->error);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('registrarDevolucion error: ' . $e->getMessage());
            return false;
        }
    }
}
