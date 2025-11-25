<div class="container mt-4">

  <h2 class="text-center mb-4">Registrar Préstamo</h2>

  <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'ok'): ?>
      <div class="alert alert-success">Préstamo realizado correctamente.</div>
  <?php endif; ?>

  <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'error'): ?>
      <div class="alert alert-danger">Error al registrar el préstamo.</div>
  <?php endif; ?>

  <div class="card shadow p-4">

      <form action="index.php?c=Prestamo&a=registrarPrestamo" method="POST">

          <div class="mb-3">
              <label class="form-label">Usuario (ID)</label>
              <input type="number" class="form-control" name="id_usuario" required>
          </div>

          <div class="mb-3">
              <label class="form-label">Seleccionar Libro Disponible</label>
              <select class="form-select" name="id_libro" required>
                  <option value="">Seleccione un libro</option>
                  <?php foreach ($libros as $libro): ?>
                      <option value="<?= $libro['id_libro'] ?>">
                          <?= $libro['titulo'] ?> — <?= $libro['autor'] ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="mb-3">
              <label class="form-label">Fecha de Devolución</label>
              <input type="date" class="form-control" name="fecha_devolucion" required>
          </div>

          <button class="btn btn-primary w-100">Registrar Préstamo</button>
      </form>

  </div>
</div>
