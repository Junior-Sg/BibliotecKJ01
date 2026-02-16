<?php
/**
 *
 * 
 * - Retrasos: http://localhost/BibliotecKJ01/index.php?controller=Reportes&action=enviar_notificaciones_retrasos
 * - Vencimientos próximos: http://localhost/BibliotecKJ01/index.php?controller=Reportes&action=enviar_recordatorio_vencimiento
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Bogota');

// Cargar la configuración y dependencias
require_once __DIR__ . '/../../config/config.php';

require_once APP_PATH . '/core/helpers.php';
require_once APP_PATH . '/../config/Conexion.php';
require_once APP_PATH . '/models/PrestamoModelo.php';
require_once APP_PATH . '/models/Usuario.php';
require_once APP_PATH . '/helpers/Mailer.php';

/**
 * Función principal para enviar notificaciones de retrasos
 */
function enviarNotificacionesRetrasos() {
    try {
        $prestamoModelo = new PrestamoModelo();
        $usuarioModelo = new Usuario((new Conexion())->conectar());
        $mailer = new Mailer();

        // Actualizar estados de préstamos retrasados
        $prestamoModelo->actualizarEstadosDePrestamosRetrasados();

        // Obtener todos los préstamos retrasados
        $retrasados = $prestamoModelo->obtenerPrestamosRetrasados(1000);

        $enviados = 0;
        $errores = 0;
        $detalles = [];

        foreach ($retrasados as $prestamo) {
            $usuario = $usuarioModelo->obtenerPorId($prestamo['id_usuario']);
            
            if ($usuario && !empty($usuario['correo'])) {
                $tituloLibro = htmlspecialchars($prestamo['titulo_libro']);
                $usuarioNombre = htmlspecialchars($usuario['nombre'] ?? 'Usuario');
                $usuarioCorreo = $usuario['correo'];
                
                $fechaLimite = new DateTime($prestamo['fecha_devolucion']);
                $hoy = new DateTime();
                $diasRetrasado = $hoy->diff($fechaLimite)->days;
                
                $contenido = "
                    Estimado(a) <strong>$usuarioNombre</strong><br><br>
                    <span style='color:#d32f2f;font-weight:bold;'>⚠️ AVISO DE RETRASO</span><br><br>
                    El libro <strong>$tituloLibro</strong> tiene <strong>$diasRetrasado días</strong> de retraso en su devolución.<br><br>
                    <strong>Información del préstamo:</strong><br>
                    Fecha de límite de devolución: <strong>" . date('d/m/Y', strtotime($prestamo['fecha_devolucion'])) . "</strong><br>
                    Estado: <strong style='color:#d32f2f;'>Retrasado</strong><br><br>
                    Por favor, devuelve el libro a la brevedad para evitar sanciones adicionales.<br>
                    Si ya realizaste la devolución, ignora este mensaje.
                ";
                
                if ($mailer->send($usuarioCorreo, "⚠️ Aviso de Retraso de Préstamo", $contenido)) {
                    $enviados++;
                    $detalles[] = "✓ Retraso: Notificación enviada a $usuarioNombre ($usuarioCorreo)";
                } else {
                    $errores++;
                    $detalles[] = "✗ Retraso: Error al enviar a $usuarioNombre ($usuarioCorreo)";
                }
            }
        }

        return [
            'type' => 'retrasos',
            'total_retrasados' => count($retrasados),
            'enviados' => $enviados,
            'errores' => $errores,
            'detalles' => $detalles
        ];

    } catch (Exception $e) {
        error_log("Error en enviarNotificacionesRetrasos: " . $e->getMessage());
        return ['type' => 'retrasos', 'success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Función para enviar recordatorios 3 días antes del vencimiento
 */
function enviarRecordatoriosVencimiento() {
    try {
        $prestamoModelo = new PrestamoModelo();
        $mailer = new Mailer();

        // Obtener préstamos que vencen en 3 días
        $prestamosProximos = $prestamoModelo->obtenerPrestamosVencenEn3Dias();

        $enviados = 0;
        $errores = 0;
        $detalles = [];

        foreach ($prestamosProximos as $prestamo) {
            if (!empty($prestamo['correo'])) {
                $tituloLibro = htmlspecialchars($prestamo['titulo_libro']);
                $usuarioNombre = htmlspecialchars($prestamo['nombre_usuario'] ?? 'Usuario');
                $usuarioCorreo = $prestamo['correo'];
                $fechaDevolucion = date('d/m/Y', strtotime($prestamo['fecha_devolucion']));
                
                $contenido = "
                    Estimado(a) <strong>$usuarioNombre</strong><br><br>
                    <span style='color:#ff9800;font-weight:bold;'>📋 RECORDATORIO DE DEVOLUCIÓN</span><br><br>
                    Le recordamos que el libro <strong>$tituloLibro</strong> vence en <strong>3 días</strong>.<br><br>
                    <strong>Detalles:</strong><br>
                    Fecha de entrega: <strong style='color:#ff9800;'>$fechaDevolucion</strong><br><br>
                    Por favor, asegúrate de devolverlo antes de esa fecha para evitar sanciones por retraso.<br>
                    Si ya lo devolviste, gracias y disculpa las molestias.
                ";
                
                if ($mailer->send($usuarioCorreo, "📋 Recordatorio: Entrega de libro en 3 días", $contenido)) {
                    $enviados++;
                    $detalles[] = "✓ Recordatorio: Enviado a $usuarioNombre ($usuarioCorreo) - Vence: $fechaDevolucion";
                } else {
                    $errores++;
                    $detalles[] = "✗ Recordatorio: Error al enviar a $usuarioNombre ($usuarioCorreo)";
                }
            }
        }

        return [
            'type' => 'recordatorios',
            'total_proximos' => count($prestamosProximos),
            'enviados' => $enviados,
            'errores' => $errores,
            'detalles' => $detalles
        ];

    } catch (Exception $e) {
        error_log("Error en enviarRecordatoriosVencimiento: " . $e->getMessage());
        return ['type' => 'recordatorios', 'success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Guardar registro de ejecución
 */
function registrarLog($logs) {
    $logPath = __DIR__ . '/../logs/notificaciones.log';
    $logDir = dirname($logPath);
    
    // Crear directorio si no existe
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $contenido = "=== EJECUCIÓN " . date('Y-m-d H:i:s') . " ===\n";
    foreach ($logs as $log) {
        $contenido .= json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }
    $contenido .= str_repeat("-", 70) . "\n\n";
    file_put_contents($logPath, $contenido, FILE_APPEND);
}

// Ejecutar ambas funciones
$logs = [];
$logs[] = enviarNotificacionesRetrasos();
$logs[] = enviarRecordatoriosVencimiento();

// Guardar logs
registrarLog($logs);

// Mostrar resultado
if (php_sapi_name() === 'cli') {
    // Ejecución desde línea de comandos
    echo "=== Notificaciones Diarias ===\n";
    foreach ($logs as $log) {
        echo "\n" . strtoupper($log['type']) . ":\n";
        if (isset($log['error'])) {
            echo "  Error: " . $log['error'] . "\n";
        } else {
            echo "  Total: " . ($log['total_retrasados'] ?? $log['total_proximos'] ?? 0) . "\n";
            echo "  Enviados: " . $log['enviados'] . "\n";
            echo "  Errores: " . $log['errores'] . "\n";
            if (!empty($log['detalles'])) {
                echo "  Detalles:\n";
                foreach ($log['detalles'] as $detalle) {
                    echo "    $detalle\n";
                }
            }
        }
    }
    echo "\n✓ Log guardado en: app/logs/notificaciones.log\n";
} else {
    // Ejecución web
    header('Content-Type: application/json');
    $resultado = [
        'timestamp' => date('Y-m-d H:i:s'),
        'ejecutadas' => $logs,
        'total_logs' => count($logs)
    ];
    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>
