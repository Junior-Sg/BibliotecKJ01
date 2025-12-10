<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/ADM/GestionGlobal.css">
</head>
<body>

<?php
require_once __DIR__ . '/../layouts/NavADM.php';
?>

<main class="main-content">
    <div class="container mt-4">
        <div aria-live="polite" aria-atomic="true">
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
        <div id="dynamic-alert-wrapper"></div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="display-1">Gestión de Préstamos</h2>
           
        </div>
       
         <h2 class="text-center mb-4 display-6">¡Busca el libro y realiza un prestamo!</h2>
         
     <button type="button" class="btn btn-primary" 
        data-bs-toggle="modal" data-bs-target="#verPrestamosModal">
         Ver Préstamos
     </button>

        <!-- Aquí irá la tabla o tarjetas de libros disponibles -->
        <div class="card shadow p-4 mb-4">
            <h3>Libros Disponibles</h3>
            <div class="mb-3">
                <input type="text" id="filtroLibro" class="form-control" placeholder="Buscar libro por título...">
            </div>
            <!-- Aquí se mostrará una tabla o tarjetas con la información de los libros y su disponibilidad. -->
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

    <!-- Modal para Editar Préstamo -->
    <div class="modal fade" id="editarPrestamoModal" tabindex="-1" aria-labelledby="editarPrestamoModalLabel" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPrestamoModalLabel">Editar Préstamo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarPrestamo">
                        <input type="hidden" name="id_prestamo" id="edit_id_prestamo">
                        <div class="mb-3">
                            <label for="edit_fecha_devolucion" class="form-label">Fecha de Devolución</label>
                            <input type="date" class="form-control" id="edit_fecha_devolucion" name="fecha_devolucion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_estado" class="form-label">Estado</label>
                            <select class="form-control" id="edit_estado" name="estado" required>
                                <option value="activo">Activo</option>
                                <option value="retrasado">Retrasado</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal para Ver Préstamos Activos -->
    <div class="modal fade" id="verPrestamosModal" tabindex="-1" aria-labelledby="verPrestamosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verPrestamosModalLabel">Préstamos Activos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Usuario</th>
                                <th>Fecha de Préstamo</th>
                                <th>Fecha de Devolución</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se cargarán los préstamos dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Auto-dismiss static alerts from PHP
    const staticAlerts = document.querySelectorAll('div[aria-live="polite"] .alert');
    staticAlerts.forEach(alertEl => {
        setTimeout(() => {
            const alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
            if (alertInstance) {
                alertInstance.close();
            }
        }, 4000);
    });

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

    const resetUserSearch = () => {
        resultDiv.innerHTML = '';
        hiddenUserId.value = '';
        inputDoc.value = '';
        inputDoc.disabled = false;
        btnLimpiar.classList.add('d-none');
        btnBuscar.disabled = false;
        spinner.classList.add('d-none');
        checkFormValidity();
    };

    const resetUI = () => {
        resetUserSearch();
        // Reset book selection in modal
        modalIdLibro.value = '';
        libroSeleccionadoInfo.innerHTML = 'No hay libro seleccionado.';
        checkFormValidity();
    };

    // Reset UI when modal is hidden
    document.getElementById('prestamoModal').addEventListener('hidden.bs.modal', function () {
        resetUI();
    });

    btnLimpiar.addEventListener('click', resetUserSearch);

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
                    btnLimpiar.classList.remove('d-none');
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

    // Set date and optionally clear book selection when modal is shown
    document.getElementById('prestamoModal').addEventListener('show.bs.modal', function (event) {
        // Set return date to 7 days from now by default
        const fechaDevolucionInput = document.getElementById('fecha_devolucion_modal');
        const futureDate = new Date();
        futureDate.setDate(futureDate.getDate() + 7);

        const yyyy = futureDate.getFullYear();
        let mm = futureDate.getMonth() + 1; // getMonth() is zero-based
        let dd = futureDate.getDate();
        if (dd < 10) dd = '0' + dd;
        if (mm < 10) mm = '0' + mm;
        fechaDevolucionInput.value = `${yyyy}-${mm}-${dd}`;

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
    const verPrestamosModal = document.getElementById('verPrestamosModal');
    verPrestamosModal.addEventListener('show.bs.modal', function () {
        const tbody = verPrestamosModal.querySelector('tbody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Cargando...</td></tr>';

        fetch('index.php?controller=Prestamo&action=verPrestamos')
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay préstamos activos.</td></tr>';
                    return;
                }

                data.forEach(prestamo => {
                    const row = `
                        <tr>
                            <td>${prestamo.libro_titulo}</td>
                            <td>${prestamo.usuario_nombre}</td>
                            <td>${prestamo.fecha_prestamo}</td>
                            <td>${prestamo.fecha_devolucion}</td>
                            <td>${prestamo.estado}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" onclick="editarPrestamo(${prestamo.id_prestamo})">Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="eliminarPrestamo(${prestamo.id_prestamo})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            })
            .catch(error => {
                console.error('Error al cargar los préstamos:', error);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center">Error al cargar los préstamos.</td></tr>';
            });
    });
});

function showAlert(message, type = 'success') {
    const wrapper = document.getElementById('dynamic-alert-wrapper');
    if (!wrapper) {
        console.error('Dynamic alert wrapper not found.');
        return;
    }
    const alertEl = document.createElement('div');
    alertEl.className = `alert alert-${type} alert-dismissible fade show`;
    alertEl.role = 'alert';
    alertEl.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    wrapper.appendChild(alertEl);

    setTimeout(() => {
        const alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
        if (alertInstance) {
            alertInstance.close();
        }
    }, 4000);
}

function editarPrestamo(id) {
    fetch(`index.php?controller=Prestamo&action=getPrestamoById&id_prestamo=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const prestamo = data.prestamo;
                document.getElementById('edit_id_prestamo').value = prestamo.id_prestamo;
                // Formatear la fecha para el input type="date"
                const fechaDevolucion = new Date(prestamo.fecha_devolucion).toISOString().split('T')[0];
                document.getElementById('edit_fecha_devolucion').value = fechaDevolucion;
                document.getElementById('edit_estado').value = prestamo.estado;
                
                const modal = new bootstrap.Modal(document.getElementById('editarPrestamoModal'));
                modal.show();
            } else {
                showAlert(data.message || 'Error al obtener los datos del préstamo.', 'danger');
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del préstamo:', error);
            showAlert('Ocurrió un error de red.', 'danger');
        });
}

document.getElementById('formEditarPrestamo').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('index.php?controller=Prestamo&action=actualizarPrestamo', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editarPrestamoModal'));
            if (modal) {
                modal.hide();
            }
            
            // Actualizar la tabla de préstamos sin recargar la página
            const idPrestamo = formData.get('id_prestamo');
            const row = document.querySelector(`button[onclick="editarPrestamo(${idPrestamo})"]`).closest('tr');
            if (row) {
                row.cells[3].textContent = formData.get('fecha_devolucion');
                row.cells[4].textContent = formData.get('estado');
            }
            showAlert(data.message || 'Préstamo actualizado correctamente.');

        } else {
            showAlert(data.message || 'Error al actualizar el préstamo.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error al actualizar el préstamo:', error);
        showAlert('Ocurrió un error de red.', 'danger');
    });
});

function eliminarPrestamo(id) {
    if (!confirm('¿Está seguro de que desea eliminar este préstamo?')) {
        return;
    }

    const formData = new FormData();
    formData.append('id_prestamo', id);

    fetch('index.php?controller=Prestamo&action=eliminarPrestamo', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Eliminar la fila de la tabla
            const row = document.querySelector(`button[onclick="eliminarPrestamo(${id})"]`).closest('tr');
            if (row) {
                row.remove();
            }
            showAlert(data.message || 'Préstamo eliminado correctamente.');
        } else {
            showAlert(data.message || 'Error al eliminar el préstamo.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error en la solicitud de eliminación:', error);
        showAlert('Ocurrió un error de red. Por favor, inténtelo de nuevo.', 'danger');
    });
}
</script>

</body>
</html>
