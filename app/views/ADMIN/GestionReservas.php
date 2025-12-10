<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title>Gestión de Reservas</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/ADM/GestionGlobal.css">
</head>
<body>

<?php
require_once __DIR__ . '/../layouts/NavADM.php';
?>

<main class="main-content">
    <div class="container mt-5">
        <div id="dynamic-alert-wrapper"></div>
        
        <div class="titulo-banda d-flex justify-content-between align-items-center">
            <h2><i class="bi bi-calendar-check"></i> Gestión de Reservas</h2>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#reservaModal">
                <i class="bi bi-plus-circle"></i> Registrar Nueva Reserva
            </button>
        </div>

        <div class="filter-card">
            <input type="text" id="filtroCedula" class="form-control" placeholder="Filtrar por número de cédula del usuario...">
        </div>

        <div class="row" id="lista-reservas">
            <!-- Las tarjetas de reserva se cargarán aquí dinámicamente -->
        </div>
    </div>
</main>

<!-- Modal para Registrar Reserva -->
<div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservaModalLabel">Registrar Nueva Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarReserva" onsubmit="realizarReserva(event)">
                    <!-- Búsqueda de Usuario -->
                    <div class="mb-3">
                        <label class="form-label">Buscar Usuario por Documento</label>
                        <div class="input-group">
                            <input type="text" id="numero_documento_reserva" class="form-control" placeholder="Número de documento">
                            <button id="btnBuscarUsuarioReserva" type="button" class="btn btn-outline-secondary">Buscar</button>
                        </div>
                        <div id="usuarioResultadoReserva" class="mt-2"></div>
                    </div>

                    <!-- Búsqueda de Libro -->
                    <div class="mb-3">
                        <label class="form-label">Buscar Libro por Título</label>
                        <div class="input-group">
                             <input type="text" id="titulo_libro_reserva" class="form-control" placeholder="Título del libro">
                             <button id="btnBuscarLibroReserva" type="button" class="btn btn-outline-secondary">Buscar</button>
                        </div>
                        <div id="libroResultadoReserva" class="mt-2"></div>
                    </div>
                    
                    <input type="hidden" name="id_usuario" id="id_usuario_reserva" required>
                    <input type="hidden" name="id_libro" id="id_libro_reserva" required>

                    <div class="alert alert-info" id="seleccionInfo">
                        <p><strong>Usuario:</strong> <span id="info_usuario_nombre">No seleccionado</span></p>
                        <p><strong>Libro:</strong> <span id="info_libro_titulo">No seleccionado</span></p>
                    </div>

                    <button type="submit" id="btnRegistrarReserva" class="btn btn-primary w-100" disabled>Registrar Reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reservaModal = new bootstrap.Modal(document.getElementById('reservaModal'));
        const listaReservas = document.getElementById('lista-reservas');
        
        const idUsuarioInput = document.getElementById('id_usuario_reserva');
        const idLibroInput = document.getElementById('id_libro_reserva');
        const btnRegistrarReserva = document.getElementById('btnRegistrarReserva');

        const BASE_URL = '<?= BASE_URL ?>';

        // --- RENDER FUNCTIONS ---
        function renderReservas(reservas) {
            listaReservas.innerHTML = '';
            if (reservas.length === 0) {
                listaReservas.innerHTML = '<div class="col-12"><div class="alert alert-info">No hay reservas pendientes.</div></div>';
                return;
            }

            reservas.forEach(reserva => {
                const reservaData = JSON.stringify(reserva);
                const card = `
                    <div class="col-md-3 mb-4 reserva-card" id="reserva-card-${reserva.id_reserva}" data-cedula="${reserva.numero_documento}">
                        <div class="card h-100">
                            <img src="${BASE_URL}public/img/Libros/${reserva.libro_imagen || 'default.png'}" class="card-img-top" alt="Imagen del libro">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${reserva.libro_titulo}</h5>
                                <p class="card-text">
                                    <strong>Usuario:</strong> ${reserva.usuario_nombre}<br>
                                    <strong>C.C:</strong> ${reserva.numero_documento}<br>
                                    <strong>Fecha Reserva:</strong> ${reserva.fecha_reserva}<br>
                                </p>
                                <div class="mt-auto d-grid gap-2">
                                    <button class="btn btn-sm btn-success" onclick='generarPrestamo(${reservaData})'>
                                        <i class="bi bi-journal-arrow-up"></i> Generar Préstamo
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick='eliminarReserva(${reserva.id_reserva})'>
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                listaReservas.insertAdjacentHTML('beforeend', card);
            });
        }
        
        async function fetchAndRenderReservas() {
            try {
                const response = await fetch('index.php?controller=Reserva&action=listarReservas');
                const result = await response.json();
                if (result.success) {
                    renderReservas(result.data);
                } else {
                    showAlert(result.message || 'Error al cargar las reservas.', 'danger');
                }
            } catch (error) {
                console.error('Error fetching reservations:', error);
                showAlert('Error de red al cargar las reservas.', 'danger');
            }
        }

        window.generarPrestamo = async function(reserva) {
            if (!confirm(`¿Desea generar un préstamo para el libro "${reserva.libro_titulo}" al usuario "${reserva.usuario_nombre}"?`)) {
                return;
            }

            const formData = new FormData();
            formData.append('id_reserva', reserva.id_reserva);
            formData.append('id_usuario', reserva.id_usuario); // Asumiendo que el controlador lo necesita
            formData.append('id_libro', reserva.id_libro);

             try {
                const response = await fetch('index.php?controller=Reserva&action=convertirReservaAPrestamo', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    showAlert(result.message || 'Préstamo generado con éxito.');
                    // Eliminar la tarjeta de la vista
                    const card = document.getElementById(`reserva-card-${reserva.id_reserva}`);
                    if (card) {
                        card.remove();
                    }
                } else {
                    showAlert(result.message || 'Error al generar el préstamo.', 'danger');
                }
            } catch (error) {
                console.error('Error en generarPrestamo:', error);
                showAlert('Error de red al generar el préstamo.', 'danger');
            }
        }

        window.eliminarReserva = async function(idReserva) {
            if (!confirm(`¿Está seguro de que desea eliminar la reserva? Esta acción no se puede deshacer.`)) {
                return;
            }

            const formData = new FormData();
            formData.append('id_reserva', idReserva);

            try {
                const response = await fetch('index.php?controller=Reserva&action=eliminarReserva', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    showAlert(result.message || 'Reserva eliminada con éxito.');
                    // Eliminar la tarjeta de la vista
                    const card = document.getElementById(`reserva-card-${idReserva}`);
                    if (card) {
                        card.remove();
                    }
                } else {
                    showAlert(result.message || 'Error al eliminar la reserva.', 'danger');
                }
            } catch (error) {
                console.error('Error en eliminarReserva:', error);
                showAlert('Error de red al eliminar la reserva.', 'danger');
            }
        }


        // --- SEARCH FUNCTIONS ---
        document.getElementById('btnBuscarUsuarioReserva').addEventListener('click', async function() {
            const doc = document.getElementById('numero_documento_reserva').value;
            const resultDiv = document.getElementById('usuarioResultadoReserva');
            if (!doc) {
                resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese un documento.</div>';
                return;
            }
            try {
                const response = await fetch(`${BASE_URL}public/api/buscar_usuario.php?numero_documento=${doc}`);
                const result = await response.json();
                if (result.ok && result.usuario) {
                    idUsuarioInput.value = result.usuario.id_usuario;
                    document.getElementById('info_usuario_nombre').textContent = result.usuario.nombre;
                    resultDiv.innerHTML = `<div class="alert alert-success">Usuario: <strong>${result.usuario.nombre}</strong> seleccionado.</div>`;
                    checkFormValidity();
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">${result.error || 'Usuario no encontrado.'}</div>`;
                }
            } catch(e) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error en la búsqueda.</div>';
            }
        });

        document.getElementById('btnBuscarLibroReserva').addEventListener('click', async function() {
            const titulo = document.getElementById('titulo_libro_reserva').value;
            const resultDiv = document.getElementById('libroResultadoReserva');
            if (titulo.length < 2) {
                resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese al menos 2 caracteres.</div>';
                return;
            }
            try {
                const response = await fetch(`${BASE_URL}public/api/buscar_libro.php?titulo=${titulo}`);
                const result = await response.json();
                if (result.success && result.data.length > 0) {
                    let listHtml = '<ul class="list-group">';
                    result.data.forEach(libro => {
                        listHtml += `<li class="list-group-item list-group-item-action" style="cursor:pointer;" data-id="${libro.id_libro}" data-titulo="${libro.titulo}">${libro.titulo} - <i>${libro.editorial}</i></li>`;
                    });
                    listHtml += '</ul>';
                    resultDiv.innerHTML = listHtml;
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-danger">${result.message || 'No se encontraron libros.'}</div>`;
                }
            } catch(e) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error en la búsqueda.</div>';
            }
        });
        
        document.getElementById('libroResultadoReserva').addEventListener('click', function(e){
            if(e.target && e.target.matches('li.list-group-item')) {
                idLibroInput.value = e.target.dataset.id;
                document.getElementById('info_libro_titulo').textContent = e.target.dataset.titulo;
                document.getElementById('libroResultadoReserva').innerHTML = `<div class="alert alert-success">Libro: <strong>${e.target.dataset.titulo}</strong> seleccionado.</div>`;
                checkFormValidity();
            }
        });


        // --- FORM SUBMISSION ---
        window.realizarReserva = async function(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);

            try {
                const response = await fetch('index.php?controller=Reserva&action=registrarReservaAdmin', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.success) {
                    reservaModal.hide();
                    showAlert(result.message || 'Reserva creada con éxito.');
                    fetchAndRenderReservas(); // Refresh the list
                    form.reset();
                    resetModalState();
                } else {
                    showAlert(result.message || 'Error al crear la reserva.', 'danger');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                showAlert('Error de red al crear la reserva.', 'danger');
            }
        }
        
        // --- UTILITY FUNCTIONS ---
        function showAlert(message, type = 'success') {
            const wrapper = document.getElementById('dynamic-alert-wrapper');
            const alertEl = document.createElement('div');
            alertEl.className = `alert alert-${type} alert-dismissible fade show`;
            alertEl.role = 'alert';
            alertEl.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
            wrapper.appendChild(alertEl);
            setTimeout(() => {
                bootstrap.Alert.getOrCreateInstance(alertEl)?.close();
            }, 5000);
        }

        function checkFormValidity() {
            btnRegistrarReserva.disabled = !(idUsuarioInput.value && idLibroInput.value);
        }
        
        function resetModalState(){
            idUsuarioInput.value = '';
            idLibroInput.value = '';
            document.getElementById('info_usuario_nombre').textContent = 'No seleccionado';
            document.getElementById('info_libro_titulo').textContent = 'No seleccionado';
            document.getElementById('usuarioResultadoReserva').innerHTML = '';
            document.getElementById('libroResultadoReserva').innerHTML = '';
             document.getElementById('numero_documento_reserva').value = '';
            document.getElementById('titulo_libro_reserva').value = '';
            btnRegistrarReserva.disabled = true;
        }

        document.getElementById('reservaModal').addEventListener('hidden.bs.modal', resetModalState);

        // Initial load
        fetchAndRenderReservas();
    });
</script>

</body>
</html>
