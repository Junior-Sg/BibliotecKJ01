<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes - Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/ADM/Reportes.css">
</head>
<body>
<?php require_once 'app/views/layouts/NavADM.php'; ?>
<div class="main-content">
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center mb-4">Sección de Informes</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Libros Prestados Actualmente</h5>
                    <p class="card-text display-4"><?php echo $librosPrestadosActualmente; ?></p>
                    <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_prestamos_actuales" class="btn btn-success">Exportar a Excel</a>
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
                        <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_nuevos_usuarios" class="btn btn-primary">Exportar a Excel</a>
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
                        <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_prestamos_mes" class="btn btn-primary">Exportar a Excel</a>
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
                        <a href="<?php echo BASE_URL; ?>?c=Reportes&a=exportar_reservas_mes" class="btn btn-primary">Exportar a Excel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        // Data from PHP
        const nuevosUsuariosData = <?php echo json_encode($nuevosUsuariosPorMes); ?>;
        const prestamosPorMesData = <?php echo json_encode($prestamosPorMes); ?>;
        const reservasPorMesData = <?php echo json_encode($reservasPorMes); ?>;

        // Process data for charts
        const processData = (data) => {
            const labels = data.map(item => `${item.anio}-${monthNames[item.mes - 1]}`);
            const values = data.map(item => item.total);
            return { labels, values };
        };

        const nuevosUsuariosChartData = processData(nuevosUsuariosData);
        const prestamosPorMesChartData = processData(prestamosPorMesData);
        const reservasPorMesChartData = processData(reservasPorMesData);

        // Chart configurations
        const createChart = (ctx, label, labels, data) => {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        };

        // Create charts
        const ctxNuevosUsuarios = document.getElementById('nuevosUsuariosChart').getContext('2d');
        createChart(ctxNuevosUsuarios, 'Nuevos Usuarios', nuevosUsuariosChartData.labels, nuevosUsuariosChartData.values);

        const ctxPrestamosPorMes = document.getElementById('prestamosPorMesChart').getContext('2d');
        createChart(ctxPrestamosPorMes, 'Préstamos', prestamosPorMesChartData.labels, prestamosPorMesChartData.values);

        const ctxReservasPorMes = document.getElementById('reservasPorMesChart').getContext('2d');
        createChart(ctxReservasPorMes, 'Reservas', reservasPorMesChartData.labels, reservasPorMesChartData.values);
    });
</script>

<?php require_once 'app/views/layouts/footer.php'; ?>
</div>
</body>
</html>
