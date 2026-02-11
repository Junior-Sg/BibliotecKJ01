<?php
/**
 * TAREA AUTOMÁTICA: Detectar préstamos retrasados y notificar a usuarios
 * 
 * Este script debe ejecutarse automáticamente via CRON (ej: cada hora)
 * 
 * Ejemplo de línea cron:
 * 0 * * * * php /ruta/a/BibliotecKJ01/app/tasks/notificaciones_retrasados.php
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/notificaciones.log');

try {
    require_once __DIR__ . '/../../config/Conexion.php';
    require_once __DIR__ . '/../models/PrestamoModelo.php';
    require_once __DIR__ . '/../models/NotificacionModelo.php';
    require_once __DIR__ . '/../helpers/Mailer.php';
    
    $db = (new Conexion())->conectar();
    $prestamoModelo = new PrestamoModelo($db);
    $notificacionModelo = new NotificacionModelo($db);
    $mailer = new Mailer();
    
    // 1. Obtener préstamos activos cuya fecha de devolución ya pasó
    $sql = "SELECT 
                p.id_prestamo,
                p.id_usuario,
                p.fecha_devolucion,
                l.titulo,
                u.correo,
                u.nombre
            FROM prestamo p
            JOIN libro l ON p.id_libro = l.id_libro
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.estado = 'activo' 
            AND p.fecha_devolucion < NOW()
            AND NOT EXISTS (
                SELECT 1 FROM notificacion_retrasado 
                WHERE id_prestamo = p.id_prestamo 
                AND DATE(fecha_notificacion) = CURDATE()
            )";
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Error en prepare: ' . $db->error);
    }
    
    $stmt->execute();
    $resultado = $stmt->get_result();
    $prestamosRetrasados = $resultado->fetch_all(MYSQLI_ASSOC);
    
    $cantidadProcessada = 0;
    
    foreach ($prestamosRetrasados as $prestamo) {
        try {
            $db->begin_transaction();
            
            // 2. Actualizar estado a retrasado
            $sqlUpdate = "UPDATE prestamo SET estado = 'retrasado' WHERE id_prestamo = ?";
            $stmtUpdate = $db->prepare($sqlUpdate);
            $stmtUpdate->bind_param("i", $prestamo['id_prestamo']);
            
            if (!$stmtUpdate->execute()) {
                throw new Exception("Error al actualizar estado: " . $stmtUpdate->error);
            }
            
            // 3. Crear notificación en BD
            $mensajeNotif = "⚠️ ALERTA: Tu préstamo del libro «" . htmlspecialchars($prestamo['titulo']) . "» ha vencido. "
                           . "Fecha de vencimiento: " . date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) . ". "
                           . "Por favor, devuelve el libro cuanto antes.";
            
            $notificacionModelo->crearNotificacion((int)$prestamo['id_usuario'], $mensajeNotif);
            
            // 4. Crear registro en tabla de control (opcional, para no re-notificar mismo día)
            $sqlRegistro = "INSERT INTO notificacion_retrasado (id_prestamo, fecha_notificacion) VALUES (?, NOW())";
            $stmtRegistro = $db->prepare($sqlRegistro);
            $stmtRegistro->bind_param("i", $prestamo['id_prestamo']);
            $stmtRegistro->execute();
            
            // 5. Enviar email al usuario
            $asunto = "⚠️ Recordatorio: Tu préstamo ha vencido";
            $contenido = "
                <h2>Recordatorio Importante</h2>
                <p>Hola " . htmlspecialchars($prestamo['nombre']) . ",</p>
                
                <div style='background-color: #fff3cd; padding: 15px; border-left: 4px solid #ff9800; margin: 20px 0;'>
                    <p><strong>Tu préstamo ha vencido</strong></p>
                    <ul>
                        <li><strong>Libro:</strong> " . htmlspecialchars($prestamo['titulo']) . "</li>
                        <li><strong>Fecha de vencimiento:</strong> " . date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) . "</li>
                        <li><strong>Estado:</strong> <span style='color: red; font-weight: bold;'>RETRASADO</span></li>
                    </ul>
                </div>
                
                <p>Por favor, devuelve el libro <strong>lo antes posible</strong> para evitar sanciones o pérdida de privilegios de préstamo.</p>
                
                <p>Si ya devolviste el libro, ignora este mensaje. Si tienes preguntas, contacta con nosotros.</p>
                
                <p>¡Gracias!</p>
            ";
            
            if (!empty($prestamo['correo'])) {
                try {
                    $mailer->send($prestamo['correo'], $asunto, $contenido, true);
                    error_log("✓ Email retrasado enviado a: " . $prestamo['correo'] . " (Préstamo #" . $prestamo['id_prestamo'] . ")");
                } catch (Exception $e) {
                    error_log("✗ Error enviando email retrasado a: " . $prestamo['correo'] . " - " . $e->getMessage());
                }
            }
            
            $db->commit();
            $cantidadProcessada++;
            
        } catch (Exception $e) {
            $db->rollback();
            error_log("✗ Error procesando préstamo #" . $prestamo['id_prestamo'] . ": " . $e->getMessage());
            continue;
        }
    }
    
    // Log del resultado
    $mensaje = "✓ Tarea 'notificaciones_retrasados' completada. " . $cantidadProcessada . " préstamo(s) procesado(s).";
    error_log($mensaje);
    echo $mensaje . "\n";
    
} catch (Exception $e) {
    error_log("✗ CRÍTICO en notificaciones_retrasados.php: " . $e->getMessage());
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
