<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Gestión de Devoluciones</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/ADM/GestionGlobal.css">
</head>
<body>

<?php
require_once __DIR__ . '/../layouts/NavADM.php';
?>

<main class="main-content">
    <div class="container mt-4">
        <div id="dynamic-alert-wrapper"></div>
        <h2 class="display-1 mb-4" style="color: #44290e;">Gestión de Devoluciones</h2>

        <div class="mb-4">
            <input type="text" id="filtroCedula" class="form-control" placeholder="Filtrar por número de cédula...">
        </div>

        <div class="row" id="lista-prestamos">
            <?php if (!empty($prestamos)): ?>
                <?php foreach ($prestamos as $prestamo): ?>
                    <div class="col-md-3 mb-4 prestamo-card" data-cedula="<?= htmlspecialchars($prestamo['numero_documento']) ?>" id="prestamo-card-<?= htmlspecialchars($prestamo['id_prestamo']) ?>">
                        <div class="card h-100">
                            <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($prestamo['libro_imagen'] ?? 'default.png') ?>" class="card-img-top" alt="Imagen del libro">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($prestamo['libro_titulo']) ?></h5>
                                <p class="card-text">
                                    <strong>Usuario:</strong> <?= htmlspecialchars($prestamo['usuario_nombre']) ?> (C.C: <?= htmlspecialchars($prestamo['numero_documento']) ?>)<br>
                                    <strong>Fecha Préstamo:</strong> <?= htmlspecialchars($prestamo['fecha_prestamo']) ?><br>
                                    <strong>Fecha Devolución:</strong> <?= htmlspecialchars($prestamo['fecha_devolucion']) ?><br>
                                    <strong>Estado:</strong> <span class="badge bg-<?= $prestamo['estado'] == 'retrasado' ? 'danger' : 'success' ?>"><?= htmlspecialchars($prestamo['estado']) ?></span>
                                </p>
                                <button type="button" class="btn btn-primary" style="background-color: #44290e; border-color: #44290e;" onclick="prepararDevolucion(<?= htmlspecialchars(json_encode($prestamo)) ?>)">
                                    Registrar Devolución
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No hay préstamos activos para gestionar.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Modal para Confirmar Devolución -->
<div class="modal fade" id="devolucionModal" tabindex="-1" aria-labelledby="devolucionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: rgba(68,41,14,0.85); color: #fff;">
                <h5 class="modal-title" id="devolucionModalLabel">Confirmar Devolución</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea registrar la devolución del siguiente libro?</p>
                <div id="infoPrestamoDevolver">
                    <!-- Info will be injected here -->
                </div>
            </div>
            <div class="modal-footer">
                <form id="formDevolverPrestamo" onsubmit="realizarDevolucion(event)">
                    <input type="hidden" name="id_prestamo" id="id_prestamo_devolver">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" style="background-color: #44290e; border-color: #44290e;">Confirmar Devolución</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const devolucionModal = new bootstrap.Modal(document.getElementById('devolucionModal'));

    function showAlert(message, type = 'success') {
        const wrapper = document.getElementById('dynamic-alert-wrapper');
        if (!wrapper) return;
        const alertEl = document.createElement('div');
        alertEl.className = `alert alert-${type} alert-dismissible fade show`;
        alertEl.role = 'alert';
        alertEl.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        wrapper.appendChild(alertEl);
        setTimeout(() => {
            const alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
            if (alertInstance) alertInstance.close();
        }, 4000);
    }

    function prepararDevolucion(prestamo) {
        document.getElementById('id_prestamo_devolver').value = prestamo.id_prestamo;
        const infoDiv = document.getElementById('infoPrestamoDevolver');
        infoDiv.innerHTML = `
            <p><strong>Libro:</strong> ${prestamo.libro_titulo}</p>
            <p><strong>Usuario:</strong> ${prestamo.usuario_nombre}</p>
        `;
        devolucionModal.show();
    }

    function realizarDevolucion(event) {
        event.preventDefault();
        const form = event.target;
        const idPrestamo = form.id_prestamo.value;
        
        const formData = new FormData();
        formData.append('id_prestamo', idPrestamo);

        fetch('index.php?controller=Prestamo&action=registrarDevolucion', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            devolucionModal.hide();
            if (data.success) {
                // Remove card from view
                const card = document.getElementById('prestamo-card-' + idPrestamo);
                if (card) {
                    card.remove();
                }
                showAlert(data.message || 'Devolución registrada correctamente.');

                // Check if there are any cards left
                const remainingCards = document.querySelectorAll('.prestamo-card');
                if (remainingCards.length === 0) {
                    const row = document.getElementById('lista-prestamos');
                    row.innerHTML = '<div class="col-12"><div class="alert alert-info">No hay préstamos activos para gestionar.</div></div>';
                }
            } else {
                showAlert(data.message || 'Error al registrar la devolución.', 'danger');
            }
        })
        .catch(error => {
            devolucionModal.hide();
            console.error('Error en la solicitud:', error);
            showAlert('Ocurrió un error de red. Por favor, inténtelo de nuevo.', 'danger');
        });
    }

    document.getElementById('filtroCedula').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.prestamo-card').forEach(card => {
            let cedula = card.dataset.cedula.toLowerCase();
            if (cedula.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
