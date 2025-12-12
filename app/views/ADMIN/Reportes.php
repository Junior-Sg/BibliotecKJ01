<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/ADM/Reportes.css">
</head>
<body>
<?php require_once 'app/views/layouts/NavADM.php'; ?>
<div class="main-content">
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center report-title">Panel de Reportes y Estadísticas</h1>
            </div>
        </div>

        <div class="row mb-5 justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card text-center kpi-card">
                    <div class="card-body">
                        <h5 class="card-title">Libros Prestados Actualmente</h5>
                        <p class="card-text display-4"><?php echo $librosPrestadosActualmente; ?></p>
                        <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_prestamos_actuales" class="btn">Exportar a Excel</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    <h5 class="alert-heading">Cómo funcionan las gráficas:</h5>
                    <ul class="mb-0">
                        <li><strong>Nuevos Usuarios por Mes:</strong> Muestra el historial de registros de usuarios. Al pasar de mes, la gráfica se actualiza automáticamente agregando el nuevo período. Los datos históricos permanecen visibles, permitiéndote ver la tendencia completa.</li>
                        <li><strong>Préstamos por Mes:</strong> Registra todos los préstamos realizados en cada período. La gráfica crece con cada nuevo mes, mostrando una línea de tiempo de la actividad de préstamos.</li>
                        <li><strong>Reservas por Mes:</strong> Similar a la anterior, acumula las reservas por período. Al cambiar de mes, se agregan nuevas barras sin eliminar las anteriores.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Generar Reporte PDF Mensual</h5>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="mesSelector" class="form-label">Seleccionar Mes:</label>
                                <select id="mesSelector" class="form-select">
                                    <option value="">-- Seleccionar mes --</option>
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="anioSelector" class="form-label">Seleccionar Año:</label>
                                <select id="anioSelector" class="form-select">
                                    <option value="">-- Seleccionar año --</option>
                                    <option value="2024">2024</option>
                                    <option value="2025" selected>2025</option>
                                    <option value="2026">2026</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button id="btnGenerarPdf" class="btn btn-primary w-100">Generar PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Nuevos Usuarios por Mes</h5>
                        <canvas id="nuevosUsuariosChart"></canvas>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_nuevos_usuarios" class="btn btn-primary">Exportar Datos</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Préstamos por Mes</h5>
                        <canvas id="prestamosPorMesChart"></canvas>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_prestamos_mes" class="btn btn-primary">Exportar Datos</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Reservas por Mes</h5>
                        <canvas id="reservasPorMesChart"></canvas>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_reservas_mes" class="btn btn-primary">Exportar Datos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthNames = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];

        const nuevosUsuariosData = <?php echo json_encode($nuevosUsuariosPorMes); ?>;
        const prestamosPorMesData = <?php echo json_encode($prestamosPorMes); ?>;
        const reservasPorMesData = <?php echo json_encode($reservasPorMes); ?>;

        const processData = (data) => {
            const labels = data.map(item => `${monthNames[item.mes - 1]} ${item.anio}`);
            const values = data.map(item => item.total);
            return { labels, values };
        };

        const nuevosUsuariosChartData = processData(nuevosUsuariosData);
        const prestamosPorMesChartData = processData(prestamosPorMesData);
        const reservasPorMesChartData = processData(reservasPorMesData);

        // --- Chart Configurations ---
        const defaultChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                },
                x: {
                    ticks: {
                        autoSkip: true,
                        maxRotation: 0,
                        minRotation: 0
                    }
                }
            }
        };

        // Chart 1: Nuevos Usuarios (Bar Chart) - Tonalidad café
        const ctxNuevosUsuarios = document.getElementById('nuevosUsuariosChart').getContext('2d');
        new Chart(ctxNuevosUsuarios, {
            type: 'bar',
            data: {
                labels: nuevosUsuariosChartData.labels,
                datasets: [{
                    label: 'Nuevos Usuarios',
                    data: nuevosUsuariosChartData.values,
                    backgroundColor: '#8b6f47',
                    borderColor: '#5a3417',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: defaultChartOptions
        });

        // Chart 2: Préstamos por Mes (Bar Chart - ahora visible) - Tonalidad café más oscura
        const ctxPrestamosPorMes = document.getElementById('prestamosPorMesChart').getContext('2d');
        new Chart(ctxPrestamosPorMes, {
            type: 'bar',
            data: {
                labels: prestamosPorMesChartData.labels,
                datasets: [{
                    label: 'Préstamos',
                    data: prestamosPorMesChartData.values,
                    backgroundColor: '#5a3417',
                    borderColor: '#3d2817',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: defaultChartOptions
        });
        
        // Chart 3: Reservas por Mes (Bar Chart) - Tonalidad café gris
        const ctxReservasPorMes = document.getElementById('reservasPorMesChart').getContext('2d');
        new Chart(ctxReservasPorMes, {
            type: 'bar',
            data: {
                labels: reservasPorMesChartData.labels,
                datasets: [{
                    label: 'Reservas',
                    data: reservasPorMesChartData.values,
                    backgroundColor: '#a89968',
                    borderColor: '#7a6c4d',
                    borderWidth: 2,
                    borderRadius: 4
                }]
            },
            options: defaultChartOptions
        });

        // Manejador para generar PDF
        document.getElementById('btnGenerarPdf').addEventListener('click', function() {
            const mes = document.getElementById('mesSelector').value;
            const anio = document.getElementById('anioSelector').value;

            if (!mes || !anio) {
                alert('Por favor selecciona mes y año');
                return;
            }

            const url = '<?php echo BASE_URL; ?>?c=Reportes&a=generarReportePdf&mes=' + mes + '&anio=' + anio;
            window.location.href = url;
        });
    });
</script>


</div>
</body>
</html>

