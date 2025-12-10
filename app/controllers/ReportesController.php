<?php
require_once 'app/models/ReportesModelo.php';

class ReportesController {
    public function index() {
        $reportesModelo = new ReportesModelo();

        $librosPrestadosActualmente = $reportesModelo->contarLibrosPrestadosActualmente();
        $nuevosUsuariosPorMes = $reportesModelo->contarNuevosUsuariosPorMes();
        $prestamosPorMes = $reportesModelo->contarPrestamosPorMes();
        $reservasPorMes = $reportesModelo->contarReservasPorMes();

        // Cargar la vista de reportes
        require_once 'app/views/ADMIN/Reportes.php';
    }

    private function outputCsv($filename, $headers, $data) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
    }

    public function exportar_prestamos_actuales() {
        $reportesModelo = new ReportesModelo();
        $total = $reportesModelo->contarLibrosPrestadosActualmente();
        $this->outputCsv('prestamos_actuales.csv', ['Total Libros Prestados Actualmente'], [[$total]]);
    }

    public function exportar_nuevos_usuarios() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->contarNuevosUsuariosPorMes();
        $this->outputCsv('nuevos_usuarios_por_mes.csv', ['Año', 'Mes', 'Total'], $datos);
    }

    public function exportar_prestamos_mes() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->contarPrestamosPorMes();
        $this->outputCsv('prestamos_por_mes.csv', ['Año', 'Mes', 'Total'], $datos);
    }

    public function exportar_reservas_mes() {
        $reportesModelo = new ReportesModelo();
        $datos = $reportesModelo->contarReservasPorMes();
        $this->outputCsv('reservas_por_mes.csv', ['Año', 'Mes', 'Total'], $datos);
    }
}
?>