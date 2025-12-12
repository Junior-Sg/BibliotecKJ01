<?php
require_once 'app/models/ReportesModelo.php';
require_once 'app/helpers/ExcelExporter.php';
require_once 'app/helpers/PdfExporter.php';
require_once 'app/helpers/Mailer.php';

class ReportesController {
    public function index() {
        $reportesModelo = new ReportesModelo();

        $librosPrestadosActualmente = $reportesModelo->contarLibrosPrestadosActualmente();
        $nuevosUsuariosPorMes = $reportesModelo->contarNuevosUsuariosPorMes();
        $prestamosPorMes = $reportesModelo->contarPrestamosPorMes();
        $reservasPorMes = $reportesModelo->contarReservasPorMes();

        require_once 'app/views/ADMIN/Reportes.php';
    }

    public function exportar_prestamos_actuales() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->obtenerLibrosPrestadosDetallado();
        $headers = ['ID Préstamo', 'Usuario', 'Documento', 'Libro', 'Fecha Préstamo', 'Fecha Devolución', 'Estado'];
        
        $excelData = [$headers];
        foreach ($datos as $row) {
            $excelData[] = [
                $row['id_prestamo'],
                $row['usuario'],
                $row['numero_documento'],
                $row['libro'],
                $row['fecha_prestamo'],
                $row['fecha_devolucion'] ?? 'Pendiente',
                $row['estado'] ?? 'Activo'
            ];
        }
        
        // Preparar datos para exportar: separar encabezados y filas
        $datosExport = $excelData;
        array_shift($datosExport); // quitar fila de encabezados
        ExcelExporter::exportarConFormato('Prestamos_Actuales_' . date('Y-m-d_His') . '.xls', 'Reporte de Préstamos Actuales', $headers, $datosExport);
        exit;
    }

    public function exportar_nuevos_usuarios() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->obtenerNuevosUsuariosDetallado();
        $headers = ['ID Usuario', 'Nombre', 'Correo', 'Documento', 'Tipo Doc', 'Teléfono'];
        
        $excelData = [$headers];
        foreach ($datos as $row) {
            $excelData[] = [
                $row['id_usuario'],
                $row['nombre'],
                $row['correo'],
                $row['numero_documento'] ?? 'N/A',
                $row['tipo_documento'] ?? 'N/A',
                $row['telefono'] ?? 'N/A'
            ];
        }
        
        $datosExport = $excelData;
        array_shift($datosExport);
        ExcelExporter::exportarConFormato('Nuevos_Usuarios_' . date('Y-m-d_His') . '.xls', 'Reporte de Nuevos Usuarios', $headers, $datosExport);
        exit; 
    }

    public function exportar_prestamos_mes() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->obtenerPrestamosPorMesDetallado();
        $headers = ['ID Préstamo', 'Usuario', 'Documento', 'Libro', 'Fecha Préstamo', 'Fecha Devolución', 'Estado'];
        
        $excelData = [$headers];
        foreach ($datos as $row) {
            $excelData[] = [
                $row['id_prestamo'],
                $row['usuario'],
                $row['numero_documento'],
                $row['libro'],
                $row['fecha_prestamo'],
                $row['fecha_devolucion'] ?? 'Pendiente',
                $row['estado'] ?? 'Activo'
            ];
        }
        
        $datosExport = $excelData;
        array_shift($datosExport);
        ExcelExporter::exportarConFormato('Prestamos_Por_Mes_' . date('Y-m-d_His') . '.xls', 'Reporte de Préstamos por Mes', $headers, $datosExport);
        exit; 
    }

    public function exportar_reservas_mes() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->obtenerReservasDetallado();
        $headers = ['ID Reserva', 'Usuario', 'Documento', 'Libro', 'Fecha Reserva', 'Estado'];
        
        $excelData = [$headers];
        foreach ($datos as $row) {
            $excelData[] = [
                $row['id_reserva'],
                $row['usuario'],
                $row['numero_documento'],
                $row['libro'],
                $row['fecha_reserva'],
                $row['estado']
            ];
        }
        
        $datosExport = $excelData;
        array_shift($datosExport);
        ExcelExporter::exportarConFormato('Reservas_' . date('Y-m-d_His') . '.xls', 'Reporte de Reservas', $headers, $datosExport);
        exit; 
    }

    public function generarReportePdf() {
        if (!isset($_GET['mes']) || !isset($_GET['anio'])) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'error' => 'Mes y año requeridos']);
            return;
        }

        $mes = intval($_GET['mes']);
        $anio = intval($_GET['anio']);

        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->obtenerDatosReportePorMes($mes, $anio);

        PdfExporter::generarReporteMensual($mes, $anio, $datos);
    }

    /**
     * Vista completa de préstamos retrasados con opción de exportar a Excel
     */
    public function retrasados() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Verificar que sea admin
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
            header('Location: ' . BASE_URL . 'Login');
            exit;
        }

        require_once 'app/models/PrestamoModelo.php';
        $prestamoModelo = new PrestamoModelo();

        // Actualizar estados de préstamos retrasados
        $prestamoModelo->actualizarEstadosDePrestamosRetrasados();

        // Obtener todos los préstamos retrasados
        $retrasados = $prestamoModelo->obtenerPrestamosRetrasados(100); // Sin límite para la vista completa
        $retrasadosCount = $prestamoModelo->contarPrestamosRetrasados();

        // Cargar vista
        require_once 'app/views/ADMIN/Retrasados.php';
    }

    /**
     * Exportar préstamos retrasados a Excel
     */
    public function exportar_retrasados() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
            exit;
        }

        require_once 'app/models/PrestamoModelo.php';
        $prestamoModelo = new PrestamoModelo();

        $prestamoModelo->actualizarEstadosDePrestamosRetrasados();
        $datos = $prestamoModelo->obtenerPrestamosRetrasados(1000);

        $headers = ['ID Préstamo', 'Libro', 'Usuario', 'Documento', 'Fecha Préstamo', 'Fecha Límite', 'Días Retrasado', 'Estado'];
        
        $excelData = [];
        foreach ($datos as $row) {
            $fechaLimite = new DateTime($row['fecha_devolucion']);
            $hoy = new DateTime();
            $diasRetrasado = $hoy->diff($fechaLimite)->days;

            $excelData[] = [
                $row['id_prestamo'],
                $row['titulo_libro'],
                $row['nombre_usuario'],
                $row['numero_documento'] ?? 'N/A',
                $row['fecha_prestamo'],
                $row['fecha_devolucion'],
                $diasRetrasado,
                $row['estado']
            ];
        }

        ExcelExporter::exportarConFormato('Prestamos_Retrasados_' . date('Y-m-d_His') . '.xls', 'Reporte de Préstamos Retrasados', $headers, $excelData);
        exit;
    }

    /**
     * Enviar notificaciones de retraso a usuarios por correo
     * Útil para ejecutar periódicamente (ej: diariamente)
     */
    public function enviar_notificaciones_retrasos() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Verificar que sea admin
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        require_once 'app/models/PrestamoModelo.php';
        require_once 'app/models/Usuario.php';
        require_once 'config/Conexion.php';

        $prestamoModelo = new PrestamoModelo();
        $usuarioModelo = new Usuario((new Conexion())->conectar());
        $mailer = new Mailer();

        // Actualizar estados
        $prestamoModelo->actualizarEstadosDePrestamosRetrasados();

        // Obtener todos los préstamos retrasados
        $retrasados = $prestamoModelo->obtenerPrestamosRetrasados(1000);

        $enviados = 0;
        $errores = 0;

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
                } else {
                    $errores++;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => "Notificaciones enviadas: $enviados, Errores: $errores",
            'enviados' => $enviados,
            'errores' => $errores
        ]);
        exit;
    }

    /**
     * Enviar recordatorios 3 días antes de la fecha de devolución
     */
    public function enviar_recordatorio_vencimiento() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Verificar que sea admin
        if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] != 1) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        require_once 'app/models/PrestamoModelo.php';
        $prestamoModelo = new PrestamoModelo();
        $mailer = new Mailer();

        // Obtener préstamos que vencen en 3 días
        $prestamosProximos = $prestamoModelo->obtenerPrestamosVencenEn3Dias();

        $enviados = 0;
        $errores = 0;

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
                } else {
                    $errores++;
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => "Recordatorios enviados: $enviados, Errores: $errores",
            'total_proximos' => count($prestamosProximos),
            'enviados' => $enviados,
            'errores' => $errores
        ]);
        exit;
    }
}
?>
