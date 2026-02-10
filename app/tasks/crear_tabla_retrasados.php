<?php
/**
 * Script para crear la tabla de control de notificaciones retrasadas
 * Ejecutar una sola vez desde el navegador o terminal
 */

require_once __DIR__ . '/../../../config/Conexion.php';

try {
    $db = (new Conexion())->conectar();
    
    $sql = "CREATE TABLE IF NOT EXISTS notificacion_retrasado (
        id_notif_retrasado INT AUTO_INCREMENT PRIMARY KEY,
        id_prestamo INT NOT NULL,
        fecha_notificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_prestamo) REFERENCES prestamo(id_prestamo) ON DELETE CASCADE,
        UNIQUE KEY unique_daily_notif (id_prestamo, DATE(fecha_notificacion))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    if (!$db->query($sql)) {
        throw new Exception("Error al crear tabla: " . $db->error);
    }
    
    // Crear índice
    $sqlIndex = "CREATE INDEX idx_prestamo_fecha ON notificacion_retrasado(id_prestamo, fecha_notificacion)";
    $db->query($sqlIndex); // No lanzar error si el índice ya existe
    
    die("✓ Tabla 'notificacion_retrasado' creada exitosamente.\n");
    
} catch (Exception $e) {
    die("✗ Error: " . $e->getMessage() . "\n");
}
?>
