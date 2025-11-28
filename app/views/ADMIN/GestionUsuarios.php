<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Usuario </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/BibliotecKJ01/public/css/ADM/GestionUS.css">
    <style>
    .floating-alerts{
      position: fixed;
      top: 16px;
      left: 260px; /* deja espacio para la sidebar */
      right: 16px;
      z-index: 3050; /* mayor que la sidebar (1020) */
      display: flex;
      flex-direction: column;
      gap: 8px;
      align-items: flex-end;
      pointer-events: none; /* permitir clicks a elementos debajo salvo en alertas */
    }
    .floating-alerts .alert{
      pointer-events: auto; /* permitir interactuar con el botón cerrar */
      max-width: 600px;
      width: 100%;
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
    @media (max-width: 768px){
      .floating-alerts{ left: 16px; right: 16px; }
    }
    </style>
</head>
<body>
<?php include __DIR__ . '/../layouts/NavADM.php'; ?>
<?php include __DIR__ . '/../layouts/alerts.php'; ?>
<main class="main-content">
    <div class="container mt-4">

        <div class="titulo-banda">
           <h1 class="text-center mb-4 fw-bold display-4"> <i class="bi bi-journal-bookmark-fill"></i> Gestión de Usuarios </h1>
        </div>

    

    <!-- Botón abrir modal crear -->
    <button class="btn btn-nuevo mb-3" data-bs-toggle="modal" data-bs-target="#modalCrear">
      + Nuevo Usuario
    </button>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
          <th>Correo</th>
          <th>Teléfono</th>
          <th>Tipo Doc</th>
          <th>Número Cédula</th>
          <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($usuarios)): foreach($usuarios as $row): ?>
                <tr>
                    <td><?= $row['id_usuario']; ?></td>
                    <td><?= $row['nombre']; ?></td>
                    <td><?= $row['correo']; ?></td>
                    <td><?= $row['telefono']; ?></td>
                    <td><?= $row['tipo_documento']; ?></td>
                    <td><?= $row['numero_documento']; ?></td>
                    <td><?= $row['rol']; ?></td>

                    <td>
                        <!-- Botón Editar -->
                        <button 
                            class="btn btn-warning btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditar"
                            onclick="cargarDatosEditar(
                              '<?= $row['id_usuario']; ?>',
                              '<?= addslashes($row['nombre']); ?>',
                              '<?= addslashes($row['correo']); ?>',
                              '<?= addslashes($row['telefono']); ?>',
                              '<?= addslashes($row['tipo_documento']); ?>',
                              '<?= addslashes($row['numero_documento']); ?>',
                              '<?= $row['rol']; ?>'
                            )"
                        >Editar</button>

                        <!-- Botón Eliminar -->
                        <button 
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEliminar"
                            onclick="document.getElementById('idEliminar').value = '<?= $row['id_usuario']; ?>'"
                        >Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="8" class="text-center">No se encontraron usuarios.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>



<!-- MODAL CREAR USUARIO      -->


<div class="modal fade" id="modalCrear" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="index.php?controller=Usuarios&action=guardar" method="POST">
        <div class="modal-body">

          <label>Nombre:</label>
          <input type="text" name="nombre" class="form-control" required>

          <label>Correo:</label>
          <input type="email" name="correo" class="form-control" required>

          <label>Teléfono:</label>
          <input type="text" name="telefono" class="form-control" required maxlength="10" pattern="\d{7,10}" oninput="this.value = this.value.replace(/\D/g,'').slice(0,10);">

              <label>Tipo documento:</label>
              <select name="tipo_documento" class="form-control" required>
              <option value="CC">CC</option>
              <option value="TI">TI</option>
              <option value="CE">CE</option>
            </select>

              <label>Número de documento:</label>
              <input type="text" name="numero_documento" class="form-control" required pattern="\d+" oninput="this.value = this.value.replace(/\D/g,'');">

            <label>Rol:</label>
            <select name="rol" class="form-control" required>
              <option value="1">Administrador</option>
              <option value="2">Cliente</option>
            </select>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>

      </form>
    </div>
  </div>
</div>




<!-- ==============     MODAL EDITAR USUARIO     ========= -->


<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-warning">
        <h5 class="modal-title">Editar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="index.php?controller=Usuarios&action=actualizar" method="POST">
        <div class="modal-body">

          <input type="hidden" name="id_usuario" id="edit_id">

          <label>Nombre:</label>
          <input type="text" name="nombre" id="edit_nombre" class="form-control">

          <label>Correo:</label>
          <input type="email" name="correo" id="edit_correo" class="form-control">

          <label>Teléfono:</label>
          <input type="text" name="telefono" id="edit_telefono" class="form-control" maxlength="10" pattern="\d{7,10}" oninput="this.value = this.value.replace(/\D/g,'').slice(0,10);">

              <label>Tipo documento:</label>
              <select name="tipo_documento" id="edit_tipo" class="form-control">
              <option value="CC">CC</option>
              <option value="TI">TI</option>
              <option value="CE">CE</option>
            </select>

              <label>Número de documento:</label>
              <input type="text" name="numero_documento" id="edit_numero" class="form-control" pattern="\d+" oninput="this.value = this.value.replace(/\D/g,'');">

            <label>Rol:</label>
            <select name="rol" id="edit_rol" class="form-control">
              <option value="1">Administrador</option>
              <option value="2">Cliente</option>
            </select>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>

      </form>
    </div>
  </div>
</div>



<!-- ===================================================== -->
<!-- ==============     MODAL ELIMINAR USUARIO     ======= -->
<!-- ===================================================== -->

<div class="modal fade" id="modalEliminar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Eliminar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="index.php?controller=Usuarios&action=eliminar" method="POST">
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar este usuario?</p>

          <input type="hidden" name="id" id="idEliminar">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>

    </div>
  </div>
</div>
</body>


<script>
function cargarDatosEditar(id, nombre, correo, telefono, tipo_documento, numero_documento, rol) {
  document.getElementById("edit_id").value = id;
  document.getElementById("edit_nombre").value = nombre;
  document.getElementById("edit_correo").value = correo;
  document.getElementById("edit_telefono").value = telefono;
  document.getElementById("edit_tipo").value = tipo_documento;
  document.getElementById("edit_numero").value = numero_documento;
  document.getElementById("edit_rol").value = rol;
}
</script>



<!-- Bootstrap JS (bundle incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Mover modales al body para evitar problemas de stacking context
document.addEventListener('DOMContentLoaded', function() {
  var modals = document.querySelectorAll('.modal');
  modals.forEach(function(modal){
    modal.addEventListener('show.bs.modal', function () {
      document.body.appendChild(modal);
    });
  });
});
</script>
</main>
</body>
</html>
