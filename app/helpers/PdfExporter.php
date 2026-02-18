<?php

class PdfExporter {

    /**
     * Generar reporte como HTML imprimible (compatible con navegador)
     */
    public static function generarReporteMensual($mes, $anio, $datos) {
        $html = self::generarHtmlReporte($mes, $anio, $datos);
        
        header("Content-Type: text/html; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"Reporte_" . self::nombreMes($mes) . "_" . $anio . ".html\"");
        
        echo $html;
        exit;
    }

    private static function generarHtmlReporte($mes, $anio, $datos) {
        $fechaGeneracion = date("d/m/Y H:i:s");
        $nombreMes = self::nombreMes($mes);
        
        $prestamosCount = isset($datos["prestamos_count"]) ? intval($datos["prestamos_count"]) : 0;
        $activosCount = isset($datos["activos_count"]) ? intval($datos["activos_count"]) : 0;
        $devueltosCount = isset($datos["devueltos_count"]) ? intval($datos["devueltos_count"]) : 0;
        $usuariosCount = isset($datos["usuarios_count"]) ? intval($datos["usuarios_count"]) : 0;
        $reservasCount = isset($datos["reservas_count"]) ? intval($datos["reservas_count"]) : 0;
        
        $html = "<!DOCTYPE html>
<html lang=\"es\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Reporte Mensual - " . $nombreMes . " " . $anio . "</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #333; line-height: 1.6; background: #fff; }
        .container { width: 100%; max-width: 900px; margin: 0 auto; padding: 40px 20px; }
        
        .header {
            text-align: center;
            border-bottom: 4px solid #5a3417;
            padding-bottom: 30px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #5a3417;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header .subtitle {
            color: #666;
            font-size: 18px;
            font-weight: bold;
        }
        
        .meta-info {
            background: #f7f5f2;
            padding: 15px 20px;
            border-left: 4px solid #5a3417;
            margin-bottom: 30px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
        }
        
        .meta-info p { font-size: 13px; }
        
        .section {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #5a3417;
            color: white;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background: #f7f5f2;
            border: 2px solid #d4c4b0;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
        }
        
        .stat-box .number {
            font-size: 36px;
            font-weight: bold;
            color: #5a3417;
            margin: 10px 0;
        }
        
        .stat-box .label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }
        
        table thead {
            background: #5a3417;
            color: white;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-size: 13px;
            font-weight: bold;
            border-bottom: 2px solid #3d2817;
        }
        
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e8dcd0;
            font-size: 12px;
        }
        
        table tbody tr:nth-child(even) {
            background: #f7f5f2;
        }
        
        table tbody tr:hover {
            background: #e8dcd0;
        }
        
        .empty-message {
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            border: 1px dashed #ccc;
            border-radius: 4px;
            color: #999;
        }
        
        .footer {
            text-align: center;
            border-top: 2px solid #d4c4b0;
            padding-top: 20px;
            margin-top: 40px;
            color: #999;
            font-size: 11px;
        }
        
        @media print {
            body { background: white; }
            .container { padding: 20px; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <div class=\"header\">
            <h1>Reporte Mensual - Bibliotecario KJ</h1>
            <div class=\"subtitle\">" . $nombreMes . " de " . $anio . "</div>
        </div>
        
        <div class=\"meta-info\">
            <div><p><strong>Período:</strong> " . $nombreMes . " " . $anio . "</p></div>
            <div><p><strong>Generado:</strong> " . $fechaGeneracion . "</p></div>
        </div>
        
        <div class=\"section\">
            <div class=\"section-title\">Resumen de Préstamos</div>
            <div class=\"stats-grid\">
                <div class=\"stat-box\">
                    <div class=\"label\">Préstamos Totales</div>
                    <div class=\"number\">" . $prestamosCount . "</div>
                </div>
                <div class=\"stat-box\">
                    <div class=\"label\">Activos</div>
                    <div class=\"number\">" . $activosCount . "</div>
                </div>
                <div class=\"stat-box\">
                    <div class=\"label\">Devueltos</div>
                    <div class=\"number\">" . $devueltosCount . "</div>
                </div>
            </div>";
        
        if (!empty($datos["prestamos"])) {
            $html .= "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Préstamo</th>
                        <th>Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>";
            
            foreach ($datos["prestamos"] as $prestamo) {
                $estado = isset($prestamo["estado"]) ? htmlspecialchars($prestamo["estado"]) : "Activo";
                $devolucion = isset($prestamo["fecha_devolucion"]) ? htmlspecialchars($prestamo["fecha_devolucion"]) : "Pendiente";
                $html .= "<tr>
                    <td>" . htmlspecialchars($prestamo["id_prestamo"]) . "</td>
                    <td>" . htmlspecialchars($prestamo["usuario"]) . "</td>
                    <td>" . htmlspecialchars($prestamo["libro"]) . "</td>
                    <td>" . htmlspecialchars($prestamo["fecha_prestamo"]) . "</td>
                    <td>" . $devolucion . "</td>
                    <td>" . $estado . "</td>
                </tr>";
            }
            
            $html .= "</tbody></table>";
        } else {
            $html .= "<div class=\"empty-message\">No hay préstamos registrados en este período</div>";
        }
        
        $html .= "</div>
        
        <div class=\"section\">
            <div class=\"section-title\">Nuevos Usuarios Registrados</div>
            <div class=\"stat-box\" style=\"width: 100%; max-width: 300px; margin: 0 auto 20px;\">
                <div class=\"label\">Total</div>
                <div class=\"number\">" . $usuariosCount . "</div>
            </div>";
        
        if (!empty($datos["usuarios"])) {
            $html .= "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Documento</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody>";
            
            foreach ($datos["usuarios"] as $usuario) {
                $documento = isset($usuario["numero_documento"]) ? htmlspecialchars($usuario["numero_documento"]) : "N/A";
                $telefono = isset($usuario["telefono"]) ? htmlspecialchars($usuario["telefono"]) : "N/A";
                $html .= "<tr>
                    <td>" . htmlspecialchars($usuario["id_usuario"]) . "</td>
                    <td>" . htmlspecialchars($usuario["nombre"]) . "</td>
                    <td>" . htmlspecialchars($usuario["correo"]) . "</td>
                    <td>" . $documento . "</td>
                    <td>" . $telefono . "</td>
                </tr>";
            }
            
            $html .= "</tbody></table>";
        } else {
            $html .= "<div class=\"empty-message\">No hay nuevos usuarios en este período</div>";
        }
        
        $html .= "</div>
        
        <div class=\"section\">
            <div class=\"section-title\">Reservas</div>
            <div class=\"stat-box\" style=\"width: 100%; max-width: 300px; margin: 0 auto 20px;\">
                <div class=\"label\">Total</div>
                <div class=\"number\">" . $reservasCount . "</div>
            </div>";
        
        if (!empty($datos["reservas"])) {
            $html .= "<table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Libro</th>
                        <th>Fecha Reserva</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>";
            
            foreach ($datos["reservas"] as $reserva) {
                $html .= "<tr>
                    <td>" . htmlspecialchars($reserva["id_reserva"]) . "</td>
                    <td>" . htmlspecialchars($reserva["usuario"]) . "</td>
                    <td>" . htmlspecialchars($reserva["libro"]) . "</td>
                    <td>" . htmlspecialchars($reserva["fecha_reserva"]) . "</td>
                    <td>" . htmlspecialchars($reserva["estado"]) . "</td>
                </tr>";
            }
            
            $html .= "</tbody></table>";
        } else {
            $html .= "<div class=\"empty-message\">No hay reservas en este período</div>";
        }
        
        $html .= "</div>
        
        <div class=\"footer\">
            <p>Este reporte fue generado automáticamente por el sistema Bibliotecario KJ</p>
            <p>Para imprimir a PDF, use Ctrl+P y seleccione \"Guardar como PDF\"</p>
            <p style=\"margin-top: 20px;\">Confidencial - Uso interno únicamente</p>
        </div>
    </div>
</body>
</html>";
        
        return $html;
    }

    private static function nombreMes($mes) {
        $meses = [
            1 => "Enero", 2 => "Febrero", 3 => "Marzo",
            4 => "Abril", 5 => "Mayo", 6 => "Junio",
            7 => "Julio", 8 => "Agosto", 9 => "Septiembre",
            10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
        ];
        return $meses[$mes] ?? "Mes desconocido";
    }
}
?>
