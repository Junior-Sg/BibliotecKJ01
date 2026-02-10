<?php
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/NotificacionModelo.php';
require_once __DIR__ . '/../helpers/Mailer.php';


class PrestamoModelo {

    private $db;
    private $lastError = '';

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

            // Obtener título del libro para la notificación
            $sqlLibro = "SELECT titulo FROM libro WHERE id_libro = ?";
            $stmtLibro = $this->db->prepare($sqlLibro);
            $stmtLibro->bind_param("i", $idLibro);
            $stmtLibro->execute();
            $tituloLibro = $stmtLibro->get_result()->fetch_assoc()['titulo'] ?? 'el libro';

            // 2. Insertar préstamo
            $sqlPrestamo = "INSERT INTO prestamo 
                           (id_usuario, id_libro, fecha_prestamo, fecha_devolucion, estado)
                           VALUES (?, ?, ?, ?, 'activo')";

            $stmtPre = $this->db->prepare($sqlPrestamo);
            $stmtPre->bind_param("iiss", $idUsuario, $idLibro, $fechaPrestamo, $fechaDevolucion);

            if (!$stmtPre->execute()) {
                throw new Exception("Error al registrar el préstamo: " . $stmtPre->error);
            }

            // 🔔 Crear notificación de préstamo creado
            $notificacionModelo = new NotificacionModelo($this->db);
            $mensaje = "📖 Tu préstamo del libro «{$tituloLibro}» fue registrado con éxito. "
                     . "Fecha de devolución: {$fechaDevolucion}.";
            $notificacionModelo->crearNotificacion($idUsuario, $mensaje);

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
            $sqlSel = "SELECT p.id_libro, p.estado, p.id_usuario, l.titulo
                    FROM prestamo p
                    JOIN libro l ON p.id_libro = l.id_libro
                    WHERE p.id_prestamo = ? FOR UPDATE";

            $stmt = $this->db->prepare($sqlSel);
            $stmt->bind_param("i", $idPrestamo);
            $stmt->execute();
            $data = $stmt->get_result();

            if ($data->num_rows == 0) {
                throw new Exception("Préstamo no encontrado");
            }

            $prestamo = $data->fetch_assoc();
            $idUsuario = (int) $prestamo['id_usuario'];

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

            // 3. Crear notificación
            $mensaje = "📘 Has devuelto el libro «{$prestamo['titulo']}». Gracias por devolverlo.";
            
            $notificacionModelo = new NotificacionModelo($this->db);
            $notificacionModelo->crearNotificacion($idUsuario, $mensaje);

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

    // Verifica si un usuario ya tiene un préstamo activo para un libro específico
    public function hasActiveLoan($idUsuario, $idLibro) {
        $sql = "SELECT COUNT(id_prestamo) as total FROM prestamo WHERE id_usuario = ? AND id_libro = ? AND estado IN ('activo', 'retrasado')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $idUsuario, $idLibro);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $fila = $resultado->fetch_assoc();
        return (intval($fila['total'] ?? 0) > 0);
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
      $stmt = $this->db->prepare($sql);
      $stmt->bind_param("i", $id_libro);
      $stmt->execute();
      return $stmt->get_result()->fetch_assoc();
    }

       public function obtenerDisponibilidad($id_libro)
 {
    $sql = "SELECT cantidad_disponible FROM disponibilidad WHERE id_libro = ?";
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_libro);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
 }
   public function restarDisponibilidad($id_libro)
{
    $sql = "UPDATE disponibilidad 
            SET cantidad_disponible = cantidad_disponible - 1
            WHERE id_libro = ? AND cantidad_disponible > 0";
    $stmt = $this->db->prepare($sql);
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

    /**
     * Cuenta los préstamos que están retrasados.
     * Considera tanto los ya marcados como los que ya vencieron y siguen 'activo'.
     */
    public function contarPrestamosRetrasados() {
        $sql = "SELECT COUNT(id_prestamo) as total FROM prestamo WHERE estado = 'retrasado' OR (estado = 'activo' AND fecha_devolucion < CURDATE())";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    /**
     * Obtiene una lista breve de préstamos retrasados con datos del libro y usuario.
     */
    public function obtenerPrestamosRetrasados($limite = 5) {
        $sql = "SELECT p.id_prestamo, l.titulo as titulo_libro, u.nombre as nombre_usuario, p.fecha_prestamo, p.fecha_devolucion, p.estado
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.estado = 'retrasado' OR (p.estado = 'activo' AND p.fecha_devolucion < CURDATE())
                ORDER BY p.fecha_devolucion ASC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $limite);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene préstamos que vencen en exactamente 3 días.
     * Útil para enviar recordatorio antes de que expire el plazo.
     */
    public function obtenerPrestamosVencenEn3Dias() {
        $sql = "SELECT p.id_prestamo, p.id_usuario, p.id_libro, l.titulo as titulo_libro, u.nombre as nombre_usuario, u.correo, p.fecha_devolucion
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                JOIN usuario u ON p.id_usuario = u.id_usuario
                WHERE p.estado = 'activo' 
                AND DATE(p.fecha_devolucion) = DATE_ADD(CURDATE(), INTERVAL 3 DAY)
                ORDER BY p.fecha_devolucion ASC";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function notificarPrestamosVencenEn3Dias() {

        require_once __DIR__ . '/NotificacionModelo.php';
        $notificacionModelo = new NotificacionModelo($this->db);
    
        $sql = "SELECT p.id_prestamo, p.id_usuario, p.fecha_devolucion,
                       l.titulo
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE p.estado = 'activo'
                AND p.notificado_3dias = 0
                AND DATE(p.fecha_devolucion) = DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
    
        $resultado = $this->db->query($sql);

        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
        
                $mensaje = "⏰ Recordatorio: el libro «{$row['titulo']}» vence en 3 días. "
                         . "Fecha límite: {$row['fecha_devolucion']}.";
        
                $notificacionModelo->crearNotificacion(
                    (int)$row['id_usuario'],
                    $mensaje
                );
        
                // Marcar como notificado
                $sqlUpd = "UPDATE prestamo SET notificado_3dias = 1 WHERE id_prestamo = ?";
                $stmt = $this->db->prepare($sqlUpd);
                $stmt->bind_param("i", $row['id_prestamo']);
                $stmt->execute();
            }
        }
    }

    // ========== MÉTODOS PARA APLAZAMIENTO DE PRÉSTAMOS ==========
    
    /**
     * Registra una solicitud de aplazamiento de entrega
     */
    public function registrarSolicitudAplazamiento($idPrestamo, $idUsuario, $diasSolicitados, $motivo = null) {
        $sql = "INSERT INTO solicitud_aplazamiento (id_prestamo, id_usuario, dias_solicitados, motivo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiis", $idPrestamo, $idUsuario, $diasSolicitados, $motivo);
        return $stmt->execute();
    }

    /**
     * Verifica si ya existe una solicitud de aplazamiento pendiente
     */
    public function tieneSolicitudPendiente($idPrestamo) {
        $sql = "SELECT id_solicitud FROM solicitud_aplazamiento 
                WHERE id_prestamo = ? AND estado = 'pendiente'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idPrestamo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0;
    }

    /**
     * Obtiene todas las solicitudes de aplazamiento pendientes
     */
    public function obtenerSolicitudesAplazamientoPendientes() {
        $sql = "SELECT 
                    sa.id_solicitud,
                    sa.id_prestamo,
                    sa.id_usuario,
                    u.nombre as nombre_usuario,
                    u.correo,
                    l.titulo as titulo_libro,
                    p.fecha_devolucion,
                    p.estado as estado_prestamo,
                    sa.dias_solicitados,
                    sa.motivo,
                    sa.fecha_solicitud
                FROM solicitud_aplazamiento sa
                JOIN usuario u ON sa.id_usuario = u.id_usuario
                JOIN prestamo p ON sa.id_prestamo = p.id_prestamo
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE sa.estado = 'pendiente'
                ORDER BY sa.fecha_solicitud DESC";
        $resultado = $this->db->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Aprueba una solicitud de aplazamiento y extiende la fecha de devolución
     */
    public function aprobarAplazamiento($idSolicitud, $notaAdmin = null) {
        $this->db->begin_transaction();
        
        try {
            // 1. Obtener datos de la solicitud
            $sql = "SELECT id_prestamo, dias_solicitados FROM solicitud_aplazamiento WHERE id_solicitud = ?";
            $stmt = $this->db->prepare($sql);
            if ($stmt === false) {
                throw new Exception('Error prepare solicitud_aplazamiento: ' . $this->db->error);
            }
            $stmt->bind_param("i", $idSolicitud);
            if (!$stmt->execute()) {
                throw new Exception('Error ejecutando consulta solicitud_aplazamiento: ' . $stmt->error);
            }
            $resultado = $stmt->get_result();

            if (!$resultado || $resultado->num_rows === 0) {
                throw new Exception("Solicitud no encontrada");
            }

            $solicitud = $resultado->fetch_assoc();
            $idPrestamo = (int) $solicitud['id_prestamo'];
            $diasSolicitados = (int) $solicitud['dias_solicitados'];
            
            // 2. Obtener la fecha actual de devolución del préstamo Y EMAIL DEL USUARIO
            $sqlPrestamo = "SELECT p.fecha_devolucion, p.id_usuario, p.id_libro, l.titulo, u.correo, u.nombre 
                           FROM prestamo p
                           JOIN libro l ON p.id_libro = l.id_libro
                           JOIN usuario u ON p.id_usuario = u.id_usuario
                           WHERE p.id_prestamo = ? FOR UPDATE";
            $stmtPrestamo = $this->db->prepare($sqlPrestamo);
            if ($stmtPrestamo === false) {
                throw new Exception('Error prepare prestamo: ' . $this->db->error);
            }
            $stmtPrestamo->bind_param("i", $idPrestamo);
            if (!$stmtPrestamo->execute()) {
                throw new Exception('Error ejecutando consulta prestamo: ' . $stmtPrestamo->error);
            }
            $resPrestamo = $stmtPrestamo->get_result();
            if (!$resPrestamo || $resPrestamo->num_rows === 0) {
                throw new Exception("Préstamo no encontrado");
            }
            $prestamo = $resPrestamo->fetch_assoc();

            // 3. Calcular nueva fecha de devolución usando DateTime (más robusto)
            $fechaActualDevolucion = $prestamo['fecha_devolucion'] ?? null;
            try {
                $dt = new DateTime($fechaActualDevolucion ?: 'now');
            } catch (Exception $e) {
                $dt = new DateTime('now');
            }
            $dt->modify('+' . $diasSolicitados . ' days');
            $nuevaFecha = $dt->format('Y-m-d');
            
            // 4. Actualizar la fecha de devolución del préstamo
            $sqlUpdate = "UPDATE prestamo SET fecha_devolucion = ? WHERE id_prestamo = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            if ($stmtUpdate === false) {
                throw new Exception('Error prepare update prestamo: ' . $this->db->error);
            }
            $stmtUpdate->bind_param("si", $nuevaFecha, $idPrestamo);

            if (!$stmtUpdate->execute()) {
                throw new Exception("Error al actualizar el préstamo: " . $stmtUpdate->error);
            }

                // Si el préstamo estaba retrasado, cambiar a activo
                $sqlEstado = "UPDATE prestamo SET estado = 'activo' WHERE id_prestamo = ? AND estado = 'retrasado'";
                $stmtEstado = $this->db->prepare($sqlEstado);
                if ($stmtEstado === false) {
                    throw new Exception('Error prepare update estado prestamo: ' . $this->db->error);
                }
                $stmtEstado->bind_param("i", $idPrestamo);
                if (!$stmtEstado->execute()) {
                    throw new Exception("Error al actualizar estado del préstamo: " . $stmtEstado->error);
                }
            
            // 5. Actualizar la solicitud como aprobada
            $ahora = date('Y-m-d H:i:s');
            $sqlAprobada = "UPDATE solicitud_aplazamiento SET estado = 'aprobado', fecha_respuesta = ? WHERE id_solicitud = ?";
            $stmtAprobada = $this->db->prepare($sqlAprobada);
            if ($stmtAprobada === false) {
                throw new Exception('Error prepare solicitud_aplazamiento update: ' . $this->db->error);
            }
            $stmtAprobada->bind_param("si", $ahora, $idSolicitud);

            if (!$stmtAprobada->execute()) {
                throw new Exception("Error al actualizar la solicitud: " . $stmtAprobada->error);
            }
            
            // 6. Crear notificación para el usuario y enviar email
            $notificacionModelo = new NotificacionModelo($this->db);
            $mensaje = "✅ Tu solicitud de aplazamiento ha sido APROBADA. "
                     . "Nueva fecha de devolución: {$nuevaFecha}.";
            // Intentar crear notificación; si falla, registrarlo pero no romper la transacción
            try {
                $notificacionModelo->crearNotificacion((int)$prestamo['id_usuario'], $mensaje);
            } catch (Exception $e) {
                error_log('Error creando notificación: ' . $e->getMessage());
            }
            
            // Enviar email si tenemos correo disponible
            if (!empty($prestamo['correo'])) {
                try {
                    $emailEnviado = $this->enviarEmailAplazamientoAprobado(
                        $prestamo['correo'],
                        $prestamo['nombre'],
                        $prestamo['titulo'],
                        $nuevaFecha,
                        $diasSolicitados
                    );
                    if ($emailEnviado) {
                        error_log('✓ Email de aplazamiento enviado a: ' . $prestamo['correo']);
                    } else {
                        error_log('✗ Fallo al enviar email de aplazamiento a: ' . $prestamo['correo']);
                    }
                } catch (Exception $e) {
                    error_log('✗ Exception enviando email de aplazamiento: ' . $e->getMessage());
                }
            } else {
                error_log('⚠ No hay correo disponible para usuario ID: ' . $prestamo['id_usuario']);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            $this->lastError = $e->getMessage();
            error_log("Error en aprobarAplazamiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna el último mensaje de error ocurrido en el modelo
     */
    public function getLastError() {
        return $this->lastError;
    }

    /**
     * Rechaza una solicitud de aplazamiento
     */
    public function rechazarAplazamiento($idSolicitud, $notaAdmin = null) {
        $this->db->begin_transaction();
        
        try {
            // 1. Obtener datos de la solicitud
            $sql = "SELECT id_usuario FROM solicitud_aplazamiento WHERE id_solicitud = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $idSolicitud);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows === 0) {
                throw new Exception("Solicitud no encontrada");
            }
            
            $solicitud = $resultado->fetch_assoc();
            
            // 2. Actualizar la solicitud como rechazada
            $ahora = date('Y-m-d H:i:s');
            $sqlRechazada = "UPDATE solicitud_aplazamiento SET estado = 'rechazado', fecha_respuesta = ? WHERE id_solicitud = ?";
            $stmtRechazada = $this->db->prepare($sqlRechazada);
            $stmtRechazada->bind_param("si", $ahora, $idSolicitud);
            
            if (!$stmtRechazada->execute()) {
                throw new Exception("Error al rechazar la solicitud");
            }
            
            // 3. Crear notificación para el usuario
            $notificacionModelo = new NotificacionModelo($this->db);
            $mensaje = "❌ Tu solicitud de aplazamiento ha sido RECHAZADA.";
            if ($notaAdmin) {
                $mensaje .= " Motivo: {$notaAdmin}";
            }
            $notificacionModelo->crearNotificacion($solicitud['id_usuario'], $mensaje);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            error_log("Error en rechazarAplazamiento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene los préstamos activos/retrasados de un usuario (para el historial)
     */
    public function obtenerPrestamosActivosPorUsuario($idUsuario) {
        $sql = "SELECT 
                    p.id_prestamo,
                    p.fecha_prestamo,
                    p.fecha_devolucion,
                    p.estado,
                    l.id_libro,
                    l.titulo,
                    l.Imagen
                FROM prestamo p
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE p.id_usuario = ? AND p.estado IN ('activo', 'retrasado')
                ORDER BY p.fecha_devolucion ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Envía email al usuario cuando se aprueba su solicitud de aplazamiento
     */
    private function enviarEmailAplazamientoAprobado($email, $nombre, $tituloLibro, $nuevaFecha, $diasSolicitados) {
        try {
            error_log('🔍 Iniciando envío de email de aplazamiento a: ' . $email);
            $mailer = new Mailer();
            $asunto = "📚 Tu aplazamiento de entrega ha sido aprobado";
            
            $contenido = "
                <h2>¡Buenas noticias, $nombre!</h2>
                <p>Tu solicitud de aplazamiento para la devolución del libro <strong>«$tituloLibro»</strong> ha sido <strong style='color: green;'>✅ APROBADA</strong>.</p>
                
                <div style='background-color: #f0f8ff; padding: 15px; border-left: 4px solid #4CAF50; margin: 20px 0;'>
                    <p><strong>📖 Detalles del aplazamiento:</strong></p>
                    <ul>
                        <li><strong>Libro:</strong> $tituloLibro</li>
                        <li><strong>Días adicionales:</strong> $diasSolicitados días</li>
                        <li><strong>Nueva fecha de devolución:</strong> " . date('d/m/Y', strtotime($nuevaFecha)) . "</li>
                    </ul>
                </div>
                
                <p>Por favor, recuerda devolver el libro antes de la nueva fecha. Si tienes problemas o necesitas otra prórroga, contáctanos.</p>
                <p>¡Gracias por usar BibliotecKJ!</p>
            ";
            
            $resultado = $mailer->send($email, $asunto, $contenido, true);
            
            if ($resultado) {
                error_log('✅ Email ENVIADO exitosamente a: ' . $email);
            } else {
                error_log('❌ Mailer reportó fallo al enviar email a: ' . $email);
            }
            
            return $resultado;
        } catch (Exception $e) {
            error_log('❌ Exception en enviarEmailAplazamientoAprobado: ' . $e->getMessage());
            return false;
        }
    }
}

