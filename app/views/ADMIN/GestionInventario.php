<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Tailwind CDN (solo para utilidades rápidas en entorno de desarrollo) -->
    <script src="https://cdn.tailwindcss.com"></script>
  <!-- css -->
    <link rel="stylesheet" href="/BibliotecKJ01/public/css/ADM/GestionInventario.css">
    
    <title>Inventario</title>
</head>
<body>
    
<?php
require_once __DIR__ . '/../../models/InventarioModelo.php';
$modelo = new InventarioModelo();

$libros      = $modelo->obtenerLibros();
$autores     = $modelo->obtenerAutores();
$generos     = $modelo->obtenerGeneros();
$editoriales = $modelo->obtenerEditoriales();
// Leer filtros desde GET
$filters = [];
$filters['estante'] = $_GET['estante'] ?? '';
$filters['editorial'] = $_GET['editorial'] ?? '';
$filters['autor'] = $_GET['autor'] ?? '';
$filters['genero'] = $_GET['genero'] ?? '';
$filters['anio_desde'] = $_GET['anio_desde'] ?? '';


$libros = $modelo->obtenerLibrosFiltrados($filters);
?>
<?php
require_once __DIR__ . '/../layouts/NavADM.php';

// Mensajes (alertas) si vienen en query string
$msg = $_GET['msg'] ?? $_GET['message'] ?? null;
$error = $_GET['error'] ?? null;
?>

<div class="floating-alerts" aria-live="polite" aria-atomic="true">
    <?php if ($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($error) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<main class="main-content">
    <div class="container mt-5">

   
  
  <div class="titulo-banda">
      <h1 class="text-center mb-4 fw-bold display-4">
         <i class="bi bi-journal-bookmark-fill"></i> Gestión de Inventario
     </h1>
  </div>



    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrar">
            ➕ Registrar Libro
        </button>
    </div>

    <!-- FILTROS -->
    <div class="filter-card mb-3">
    <form id="formFiltros" class="row g-2 mb-0 align-items-end" method="GET" action="">
        <div class="col-md-2">
            <input type="text" name="estante" class="form-control form-control-sm" placeholder="Estante" value="<?= htmlspecialchars($filters['estante']) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="autor" class="form-control form-control-sm" placeholder="Autor (nombre)" value="<?= htmlspecialchars($filters['autor']) ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="genero" class="form-control form-control-sm" placeholder="Género" value="<?= htmlspecialchars($filters['genero']) ?>">
        </div>
        <div class="col-md-2">
            <select name="editorial" class="form-control form-control-sm">
                <option value="">Todas editoriales</option>
                <?php
                $edRes2 = $modelo->obtenerEditoriales();
                while ($er = $edRes2->fetch_assoc()) {
                    $sel = ($filters['editorial'] == $er['id_editorial'] || $filters['editorial'] == $er['nombre']) ? 'selected' : '';
                    echo "<option value=\"" . htmlspecialchars($er['id_editorial']) . "\" $sel>" . htmlspecialchars($er['nombre']) . "</option>";
                }
                ?>
            </select>
        </div>
    
        <div class="col-md-12 d-flex gap-2 justify-content-end mt-1">
            <button type="submit" class="btn btn-primary">Filtrar</button>
            <button type="button" id="btnLimpiar" class="btn btn-secondary">Limpiar</button>
        </div>
    </form>
    </div>
    </form>

    <!-- TABLA DE LIBROS -->
    <div class="table-responsive shadow p-3 bg-white rounded">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Autores</th>
                    <th>Géneros</th>
                    <th>Editorial</th>
                    <th>Estante</th>
                    <th>Año</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($l = $libros->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?php if (!empty($l['Imagen'])) { ?>
                                <img src="../../../public/img/libros/<?= $l['Imagen'] ?>" 
                                     width="60" height="80" class="rounded shadow-sm">
                            <?php } else { ?>
                                <span class="text-muted">Sin imagen</span>
                            <?php } ?>
                        </td>
                        <td><?= $l['titulo'] ?></td>
                        <td><?= $l['autores'] ?></td>
                        <td><?= $l['generos'] ?></td>
                        <td><?= $l['editorial'] ?></td>
                        <td><?= $l['Estante'] ?></td>
                        <td><?= $l['año_publicacion'] ?></td>
                        <td><?= $l['cantidad_total'] ?></td>
                        <td>
                            <!-- EDITAR -->
                            <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $l['id_libro'] ?>">
                                ✏️ Editar
                            </button>

                            <!-- ELIMINAR -->
                            <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar<?= $l['id_libro'] ?>">
                                🗑️ Eliminar
                            </button>
                        </td>
                    </tr>

                    <!-- MODAL ELIMINAR -->
                    <div class="modal fade" id="modalEliminar<?= $l['id_libro'] ?>">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="../../controllers/InventarioController.php" method="POST">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">¿Eliminar libro?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <p class="fw-bold">¿Seguro que deseas eliminar <br> 
                                        <span class="text-danger"><?= $l['titulo'] ?></span>?</p>

                                        <input type="hidden" name="id_libro" value="<?= $l['id_libro'] ?>">
                                        <input type="hidden" name="accion" value="eliminarLibro">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                        <!-- MODAL EDITAR -->
                        <div class="modal fade" id="modalEditar<?= $l['id_libro'] ?>">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="../../controllers/InventarioController.php" method="POST" enctype="multipart/form-data">
                                        <div class="modal-header bg-warning">
                                            <h5 class="modal-title">Editar Libro</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <input type="hidden" name="accion" value="actualizarLibro">
                                            <input type="hidden" name="id_libro" value="<?= $l['id_libro'] ?>">

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Título:</label>
                                                    <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($l['titulo']) ?>" required>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Estante:</label>
                                                    <input type="text" class="form-control" name="estante" value="<?= htmlspecialchars($l['Estante']) ?>">
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label>Año:</label>
                                                    <input type="number" class="form-control" name="anio_publicacion" min="1000" max="2099" value="<?= htmlspecialchars($l['año_publicacion']) ?>" required>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label>Editorial:</label>
                                                    <input list="editorialesList" class="form-control" name="editorial" value="<?= htmlspecialchars($l['editorial']) ?>" required>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label>Cantidad Total:</label>
                                                    <input type="number" class="form-control" name="cantidad_total" value="<?= intval($l['cantidad_total']) ?>" required>
                                                </div>

                                                <div class="col-md-4 mb-3">
                                                    <label>Imagen del Libro (opcional):</label>
                                                    <input type="file" class="form-control" name="imagen" accept="image/*">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Autores (separados por coma):</label>
                                                    <input type="text" class="form-control" name="autores" value="<?= htmlspecialchars($l['autores']) ?>" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label>Géneros (separados por coma):</label>
                                                    <input type="text" class="form-control" name="generos" value="<?= htmlspecialchars($l['generos']) ?>" required>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-warning">Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<!-- ===========================
   MODAL REGISTRAR LIBRO
=========================== -->
<div class="modal fade" id="modalRegistrar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="../../controllers/InventarioController.php" 
                  method="POST" enctype="multipart/form-data">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar Libro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" name="accion" value="registrarLibro">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Título:</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Estante:</label>
                            <input type="text" class="form-control" name="estante">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>Año:</label>
                            <input type="number" class="form-control" name="anio_publicacion" min="1000" max="2099" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Editorial:</label>
                            <input list="editorialesList" class="form-control" name="editorial" placeholder="Escriba o seleccione una editorial" required>
                            <datalist id="editorialesList">
                                <?php
                                // volver a obtener editoriales si el cursor previo llegó al final
                                $edRes = $modelo->obtenerEditoriales();
                                while ($eRow = $edRes->fetch_assoc()) {
                                    echo "<option value=\"" . htmlspecialchars($eRow['nombre']) . "\">";
                                }
                                ?>
                            </datalist>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Cantidad Total:</label>
                            <input type="number" class="form-control" name="cantidad_total" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Imagen del Libro:</label>
                            <input type="file" class="form-control" name="imagen" accept="image/*">
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Autores (separados por coma):</label>
                            <input type="text" class="form-control" name="autores" placeholder="Autor1, Autor2" required>
                            <small class="text-muted">Puedes escribir nombres separados por comas; si existen se usarán, si no se crearán.</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Géneros (separados por coma):</label>
                            <input type="text" class="form-control" name="generos" placeholder="Género1, Género2" required>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>

            </form>
        </div>
    </div>
 </div>

</main>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.floating-alerts .alert');
    if (!alerts || alerts.length === 0) return;

    // Auto-close after 3 segundos
    setTimeout(() => {
        alerts.forEach(a => {
            try {
                const bsAlert = new bootstrap.Alert(a);
                bsAlert.close();
            } catch (e) {
                // fallback: remove element
                a.remove();
            }
        });

        // Limpiar query string para que no reaparezcan al recargar
        if (window.location.search && window.history && window.history.replaceState) {
            const url = window.location.protocol + '//' + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, url);
        }
    }, 3000);
});
</script>

<script>
// Evitar problemas de stacking-context moviendo los modales al final del body
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal){
        modal.addEventListener('show.bs.modal', function () {
            document.body.appendChild(modal);
        });
    });
});
</script>

<script>
// Limpiar filtros: redirige a la misma ruta sin query string
document.addEventListener('DOMContentLoaded', function(){
    var btn = document.getElementById('btnLimpiar');
    if (btn) {
        btn.addEventListener('click', function(){
            // redirigir al mismo path sin query
            var path = window.location.pathname;
            // si la app está en subdirectorio, mantenemos el pathname
            window.location.href = path;
        });
    }
});
</script>
