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

      <h2 class="text-center mb-4">Registrar Préstamo</h2>

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

              <div class="mb-3">
                  <label class="form-label">Seleccionar Libro Disponible</label>
                  <?php
                  // Manejar distintos tipos de $libros (array o mysqli_result)
                  $optionsHtml = '';
                  if (is_array($libros)) {
                      foreach ($libros as $libro) {
                          $optionsHtml .= '<option value="' . htmlspecialchars($libro['id_libro']) . '">' . htmlspecialchars($libro['titulo']) . ' — ' . htmlspecialchars($libro['editorial'] ?? 'N/A') . '</option>';
                      }
                  } elseif (is_object($libros) && method_exists($libros, 'fetch_assoc')) {
                      // mysqli_result
                      while ($row = $libros->fetch_assoc()) {
                          $optionsHtml .= '<option value="' . htmlspecialchars($row['id_libro']) . '">' . htmlspecialchars($row['titulo']) . ' — ' . htmlspecialchars($row['editorial'] ?? 'N/A') . '</option>';
                      }
                  }

                  if ($optionsHtml === '') {
                      echo '<div class="alert alert-warning">No hay libros disponibles en este momento.</div>';
                  } else {
                      echo '<select class="form-select" name="id_libro" required><option value="">Seleccione un libro</option>' . $optionsHtml . '</select>';
                  }
                  ?>
              </div>

              <div class="mb-3">
                  <label class="form-label">Fecha de Devolución</label>
                  <input type="date" class="form-control" name="fecha_devolucion" required>
              </div>

              <button type="submit" id="btnRegistrarPrestamo" class="btn btn-primary w-100" disabled>Registrar Préstamo</button>
          </form>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const btnBuscar = document.getElementById('btnBuscarUsuario');
    const btnLimpiar = document.getElementById('btnLimpiarBusqueda');
    const btnRegistrar = document.getElementById('btnRegistrarPrestamo');
    const spinner = btnBuscar.querySelector('.spinner-border');
    
    const inputDoc = document.getElementById('numero_documento');
    const resultDiv = document.getElementById('usuarioResultado');
    const hiddenUserId = document.getElementById('id_usuario');

    const resetUI = () => {
        resultDiv.innerHTML = '';
        hiddenUserId.value = '';
        inputDoc.value = '';
        inputDoc.disabled = false;
        btnRegistrar.disabled = true;
        btnLimpiar.classList.add('d-none');
        btnBuscar.disabled = false;
        spinner.classList.add('d-none');
    };

    btnLimpiar.addEventListener('click', resetUI);

    btnBuscar.addEventListener('click', function(){
        const num = inputDoc.value.trim();
        if (!num) {
            resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese número de documento.</div>';
            return;
        }

        // Deshabilitar botón y mostrar spinner
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
                    inputDoc.disabled = true; // Bloquear campo de búsqueda
                    btnRegistrar.disabled = false; // Habilitar botón de registro
                    btnLimpiar.classList.remove('d-none'); // Mostrar botón de limpiar
                } else {
                    resultDiv.innerHTML = `<div class="alert alert-warning">${data.mensaje || 'Usuario no encontrado.'}</div>`;
                    hiddenUserId.value = '';
                    btnRegistrar.disabled = true;
                }
            }).catch(err => {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error de conexión al buscar el usuario. Revise la consola para más detalles.</div>';
                console.error(err);
            }).finally(() => {
                // Habilitar botón y ocultar spinner
                btnBuscar.disabled = false;
                spinner.classList.add('d-none');
            });
    });
});
</script>

      </div>
    </div>
</main>

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
