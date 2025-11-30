<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Registrar Préstamo</title>
    <style>
        /* Estilo empresarial tonos cafés */
        body { background: #f7f5f2; }
        .card { border-left: 6px solid #44290e; background: #ffffff; }
        .btn-primary { background-color: #5a3417; border-color: #5a3417; }
        .btn-primary:hover { background-color: #7a4a24; border-color: #7a4a24; }
        .btn-outline-primary { color: #5a3417; border-color: #5a3417; }
        .btn-outline-primary:hover { background-color: rgba(90,52,23,0.06); }
        .modal-header { background: rgba(68,41,14,0.85); color: #fff; }
        .main-content { padding-left: 260px; }
        .card .form-label { color: #3b2a20; font-weight: 600; }
        .floating-alerts .alert { box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
        /* Custom styles for compact cards */
        .compact-card .card-img-top {
            height: 120px;
            object-fit: cover;
        }
        .compact-card .card-body {
            padding: 0.5rem; /* Equivalent to p-2 */
        }
        .compact-card .card-title {
            font-size: 0.875rem; /* text-sm */
        }
        .compact-card .card-text {
            font-size: 0.75rem; /* text-xs */
        }
        .btn-xs {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
</head>
<body>

<?php
require_once __DIR__ . '/../layouts/NavADM.php';
?>


<div class="floating-alerts" aria-live="polite" aria-atomic="true">
    <?php if(isset($_GET['msg_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert"><?= htmlspecialchars(urldecode($_GET['msg_success'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['msg_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= htmlspecialchars(urldecode($_GET['msg_error'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<main class="main-content">
    <div class="container mt-4">
        <h2 class="text-center mb-4 display-4">Gestión de Préstamos</h2>

        
        
        <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#prestamoModal">
            <i class="bi bi-plus-circle me-2"></i>Registrar Nuevo Préstamo
        </button>

        <!-- Aquí irá la tabla o tarjetas de libros disponibles -->
        <div class="card shadow p-4 mb-4">
            <h3>Libros Disponibles</h3>
            <div class="mb-3">
                <input type="text" id="filtroLibro" class="form-control" placeholder="Buscar libro por título...">
            </div>
            <p>Aquí se mostrará una tabla o tarjetas con la información de los libros y su disponibilidad.</p>
            <!-- Ejemplo de una tarjeta de libro. Esto se generaría dinámicamente -->
            <div id="listaLibros" class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (!empty($libros)): ?>
                <?php foreach ($libros as $libro): ?>
                    <div class="col-6 col-md-2 mb-3 libro-card">
                        <div class="card h-100 compact-card">
                            <img src="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($libro['Imagen'] ?? 'default.png') ?>" class="card-img-top" alt="Imagen del libro" style="height: 120px; object-fit: cover;">
                            <div class="card-body p-2">
                                <h6 class="card-title text-sm fw-bold mb-1"><?= htmlspecialchars($libro['titulo'] ?? 'Sin título') ?></h6>
                                <p class="card-text text-xs mb-1">Editorial: <?= htmlspecialchars($libro['editorial'] ?? 'N/A') ?></p>
                                <p class="card-text text-xs mb-2">Disp: <?= htmlspecialchars($libro['cantidad_disponible'] ?? 0) ?></p>
                                <button type="button" class="btn btn-sm btn-outline-primary seleccionar-libro-btn w-100" 
                                    data-id="<?= htmlspecialchars($libro['id_libro']) ?>" 
                                    data-titulo="<?= htmlspecialchars($libro['titulo']) ?>"
                                    data-editorial="<?= htmlspecialchars($libro['editorial'] ?? 'N/A') ?>"
                                    data-imagen="<?= BASE_URL ?>public/img/Libros/<?= htmlspecialchars($libro['Imagen'] ?? 'default.png') ?>"
                                    data-cantidad="<?= htmlspecialchars($libro['cantidad_disponible'] ?? 0) ?>"
                                    data-bs-toggle="modal" data-bs-target="#prestamoModal">
                                    Seleccionar
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No hay libros disponibles en este momento para mostrar.</div>
                </div>
            <?php endif; ?>
            </div>
        </div>

        <!-- Modal para Registrar Préstamo -->
        <div class="modal fade" id="prestamoModal" tabindex="-1" aria-labelledby="prestamoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="prestamoModalLabel">Registrar Nuevo Préstamo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card shadow p-4">

                            <!-- Buscar usuario por número de documento (AJAX) -->
                            <form id="formBuscarUsuario" class="row g-2 mb-3" onsubmit="return false;">
                                <div class="col-md-7">
                                    <input type="text" id="numero_documento" class="form-control" placeholder="Buscar por número de documento">
                                </div>
                                <div class="col-md-3">
                                    <button id="btnBuscarUsuario" type="button" class="btn btn-outline-primary w-100">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        Buscar
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <button id="btnLimpiarBusqueda" type="button" class="btn btn-outline-secondary w-100 d-none">Limpiar</button>
                                </div>
                            </form>

                            <div id="usuarioResultado"></div>

                            <form id="formRegistrarPrestamo" action="index.php?controller=Prestamo&action=registrarPrestamo" method="POST">
                                <input type="hidden" name="id_usuario" id="id_usuario" required>
                                <input type="hidden" name="id_libro" id="modal_id_libro" required>

                                <div class="mb-3">
                                    <label class="form-label">Libro Seleccionado</label>
                                    <div id="libroSeleccionadoInfo" class="alert alert-info">
                                        No hay libro seleccionado.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Devolución</label>
                                    <input type="date" class="form-control" name="fecha_devolucion" id="fecha_devolucion_modal" required>
                                </div>

                                <button type="submit" id="btnRegistrarPrestamo" class="btn btn-primary w-100" disabled>Registrar Préstamo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div> <!-- Closes .container mt-4 -->
    </main>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const prestamoModal = new bootstrap.Modal(document.getElementById('prestamoModal'));

    const btnBuscar = document.getElementById('btnBuscarUsuario');
    const btnLimpiar = document.getElementById('btnLimpiarBusqueda');
    const btnRegistrar = document.getElementById('btnRegistrarPrestamo');
    const spinner = btnBuscar.querySelector('.spinner-border');
    
    const inputDoc = document.getElementById('numero_documento');
    const resultDiv = document.getElementById('usuarioResultado');
    const hiddenUserId = document.getElementById('id_usuario');
    const modalIdLibro = document.getElementById('modal_id_libro');
    const libroSeleccionadoInfo = document.getElementById('libroSeleccionadoInfo');

    const checkFormValidity = () => {
        btnRegistrar.disabled = !(hiddenUserId.value && modalIdLibro.value);
    };

    const resetUI = () => {
        resultDiv.innerHTML = '';
        hiddenUserId.value = '';
        inputDoc.value = '';
        inputDoc.disabled = false;
        btnLimpiar.classList.add('d-none');
        btnBuscar.disabled = false;
        spinner.classList.add('d-none');
        
        // Reset book selection in modal
        modalIdLibro.value = '';
        libroSeleccionadoInfo.innerHTML = 'No hay libro seleccionado.';
        checkFormValidity();
    };

    // Reset UI when modal is hidden
    document.getElementById('prestamoModal').addEventListener('hidden.bs.modal', function () {
        resetUI();
    });

    btnLimpiar.addEventListener('click', resetUI);

    btnBuscar.addEventListener('click', function(){
        const num = inputDoc.value.trim();
        if (!num) {
            resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese número de documento.</div>';
            return;
        }

        btnBuscar.disabled = true;
        spinner.classList.remove('d-none');
        resultDiv.innerHTML = '<div class="alert alert-secondary">Buscando...</div>';

        fetch('/BibliotecKJ01/public/api/buscar_usuario.php?numero_documento=' + encodeURIComponent(num))
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor.');
                }
                return response.json();
            })
            .then(data => {
                if (data.ok && data.usuario) {
                    const u = data.usuario;
                    resultDiv.innerHTML = `<div class="alert alert-success">Usuario encontrado: <strong>${u.nombre || ''}</strong><div>ID: ${u.id_usuario} — Documento: ${u.numero_documento || ''}</div></div>`;
                    
                    hiddenUserId.value = u.id_usuario;
                    inputDoc.disabled = true;
                    btnLimpiar.classList.add('d-none');
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-warning">${data.mensaje || 'Usuario no encontrado.'}</div>`;
                    hiddenUserId.value = '';
                }
            }).catch(err => {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar el usuario. Revise la consola para más detalles.</div>';
                console.error(err);
            }).finally(() => {
                btnBuscar.disabled = false;
                spinner.classList.add('d-none');
                checkFormValidity();
            });
    });

    // Handle book selection from cards
    document.querySelectorAll('.seleccionar-libro-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const titulo = this.dataset.titulo;
            const editorial = this.dataset.editorial;
            const imagen = this.dataset.imagen;
            const cantidad = this.dataset.cantidad;

            modalIdLibro.value = id;
            libroSeleccionadoInfo.innerHTML = `
                <div><strong>${titulo}</strong></div>
                <div>Editorial: ${editorial}</div>
                <div>Disponible: ${cantidad}</div>
                <img src="${imagen}" alt="${titulo}" style="max-height: 100px; margin-top: 10px;">
            `;
            checkFormValidity();
            // Open the modal if it's not already open (e.g., if clicked directly from card)
            prestamoModal.show();
        });
    });

    // Clear book selection when the modal is opened via the main button
    document.getElementById('prestamoModal').addEventListener('show.bs.modal', function (event) {
        // Only clear book selection if the trigger is not a book selection button
        if (!event.relatedTarget || !event.relatedTarget.classList.contains('seleccionar-libro-btn')) {
            modalIdLibro.value = '';
            libroSeleccionadoInfo.innerHTML = 'No hay libro seleccionado.';
            checkFormValidity();
        }
    });

    // Filtro de libros por título
    document.getElementById('filtroLibro').addEventListener('keyup', function() {
        let searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.libro-card').forEach(card => {
            let title = card.querySelector('.card-title').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.floating-alerts .alert');
    if (!alerts || alerts.length === 0) return;
    setTimeout(() => {
        alerts.forEach(a => {
            try { const bsAlert = new bootstrap.Alert(a); bsAlert.close(); } catch (e) { a.remove(); }
        });
        if (window.location.search && window.history && window.history.replaceState) {
            const url = window.location.protocol + '//' + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, url);
        }
    }, 3000);
});
</script>

</body>
</html>
