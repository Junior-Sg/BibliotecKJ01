<?php

class ExcelExporter {

    public static function exportarConFormato($nombreArchivo, $titulo, $headers, $datos) {
        
        // Colores coffee-tone
        $colorDark = '#5a3417';    // Marrón oscuro
        $colorMedium = '#8b6f47';  // Marrón medio
        $colorLight = '#f7f5f2';   // Crema muy clara
        $colorBorder = '#d4c4b0';  // Borde marrón claro
        $colorWhite = '#ffffff';   // Blanco
        
        // Construir HTML en una variable para poder calcular longitud y limpiar buffers
        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        $html .= '<head><meta charset="UTF-8"><title>' . htmlspecialchars($titulo) . '</title>';
        $html .= '<style>';
        $html .= 'table{border-collapse:collapse; font-family:Calibri,Arial; font-size:11pt;}';
        $html .= 'th, td{border:1px solid ' . $colorBorder . '; padding:8px; text-align:left;}';
        $html .= 'th{background:' . $colorDark . '; color:' . $colorWhite . '; font-weight:bold; font-size:12pt;}';
        $html .= 'tr.odd{background:' . $colorLight . ';}';
        $html .= 'tr.even{background:' . $colorWhite . ';}';
        $html .= '.titulo{background:' . $colorMedium . '; color:' . $colorWhite . '; font-weight:bold; font-size:14pt; padding:10px;}';
        $html .= '</style>';
        $html .= '</head>';
        $html .= '<body style="margin:10px;">';
        $html .= '<table cellpadding="0" cellspacing="0">';
        
        // Title
        if (!empty($titulo)) {
            $html .= '<tr><td colspan="' . count($headers) . '" class="titulo">' . htmlspecialchars($titulo) . '</td></tr>';
        }

        // Headers
        if (!empty($headers)) {
            $html .= '<tr>';
            foreach ($headers as $header) {
                $html .= '<th>' . htmlspecialchars($header) . '</th>';
            }
            $html .= '</tr>';
        }

        // Data con filas alternadas
        if (!empty($datos)) {
            $rowIndex = 0;
            foreach ($datos as $fila) {
                $rowClass = ($rowIndex % 2 === 0) ? 'even' : 'odd';
                $html .= '<tr class="' . $rowClass . '">';
                foreach ($fila as $celda) {
                    $html .= '<td>' . htmlspecialchars($celda ?? '') . '</td>';
                }
                $html .= '</tr>';
                $rowIndex++;
            }
        }

        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';

        // Limpiar buffers y enviar cabeceras seguras
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . basename($nombreArchivo, '.xlsx') . '.xls"');
        header('Content-Length: ' . strlen($html));
        header('Cache-Control: max-age=0, must-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        echo $html;
        exit;
    }
}