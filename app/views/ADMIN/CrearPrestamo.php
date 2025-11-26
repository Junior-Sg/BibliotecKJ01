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
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../layouts/NavADM.php';
?>
<?php
// Fallback: si el controlador no proveyó $libros, cargarlos aquí
if (!isset($libros) || empty($libros)) {
    require_once __DIR__ . '/../../models/LibroModelo.php';
    $lm = new LibroModelo();
    $libros = $lm->obtenerLibrosDisponibles();
}

?>

<div class="floating-alerts" aria-live="polite" aria-atomic="true">
    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">Préstamo realizado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">Error al registrar el préstamo.
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
              <div class="col-md-8">
                  <input type="text" id="numero_documento" class="form-control" placeholder="Buscar por número de documento">
              </div>
              <div class="col-md-4">
                  <button id="btnBuscarUsuario" type="button" class="btn btn-outline-primary w-100">Buscar Usuario</button>
              </div>
          </form>

          <div id="usuarioResultado"></div>

          <form id="formRegistrarPrestamo" action="index.php?c=Prestamo&a=registrarPrestamo" method="POST">

              <div id="usuarioInputContainer">
                  <div class="mb-3">
                      <label class="form-label">Usuario (ID) — si no usó búsqueda</label>
                      <input type="number" id="id_usuario_manual" class="form-control" name="id_usuario" required>
                  </div>
              </div>

              <div class="mb-3">
                  <label class="form-label">Seleccionar Libro Disponible</label>
                  <?php
                  // Manejar distintos tipos de $libros (array o mysqli_result)
                  $optionsHtml = '';
                  if (is_array($libros)) {
                      foreach ($libros as $libro) {
                          $optionsHtml .= '<option value="' . intval($libro['id_libro']) . '">' . htmlspecialchars($libro['titulo']) . ' — ' . htmlspecialchars($libro['editorial'] ?? '') . '</option>';
                      }
                  } elseif (is_object($libros) && method_exists($libros, 'fetch_assoc')) {
                      // mysqli_result
                      while ($row = $libros->fetch_assoc()) {
                          $optionsHtml .= '<option value="' . intval($row['id_libro']) . '">' . htmlspecialchars($row['titulo']) . ' — ' . htmlspecialchars($row['editorial'] ?? '') . '</option>';
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

              <button class="btn btn-primary w-100">Registrar Préstamo</button>
          </form>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('btnBuscarUsuario');
    const input = document.getElementById('numero_documento');
    const resultDiv = document.getElementById('usuarioResultado');
    const idManual = document.getElementById('id_usuario_manual');
    const formReg = document.getElementById('formRegistrarPrestamo');

    btn.addEventListener('click', function(){
        const num = input.value.trim();
        if (!num) {
            resultDiv.innerHTML = '<div class="alert alert-warning">Ingrese número de documento.</div>';
            return;
        }
        resultDiv.innerHTML = '<div class="alert alert-secondary">Buscando...</div>';

        fetch('/BibliotecKJ01/public/api/buscar_usuario.php?numero_documento=' + encodeURIComponent(num))
            .then(r => r.json())
            .then(j => {
                if (j.ok) {
                    const u = j.usuario;
                    resultDiv.innerHTML = '<div class="alert alert-info">Usuario encontrado: <strong>' + (u.nombre ? u.nombre : '') + '</strong><div>ID: ' + u.id_usuario + ' — Documento: ' + (u.numero_documento?u.numero_documento:'') + '</div></div>';
                    // colocar hidden input con id_usuario; deshabilitar input manual
                    let hid = document.getElementById('id_usuario_hidden');
                    if (!hid) {
                        hid = document.createElement('input');
                        hid.type = 'hidden';
                        hid.name = 'id_usuario';
                        hid.id = 'id_usuario_hidden';
                        formReg.prepend(hid);
                    }
                    hid.value = parseInt(u.id_usuario);
                    // deshabilitar manual
                    if (idManual) { idManual.value = ''; idManual.disabled = true; }
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-warning">Usuario no encontrado.</div>';
                    // eliminar hidden si existe
                    const hid = document.getElementById('id_usuario_hidden');
                    if (hid) hid.remove();
                    if (idManual) idManual.disabled = false;
                }
            }).catch(err => {
                resultDiv.innerHTML = '<div class="alert alert-danger">Error al buscar usuario.</div>';
                console.error(err);
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
