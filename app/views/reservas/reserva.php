<!-- app/views/reservas/reserva.php -->
<?php include __DIR__ . '/../../layouts/navbar.php'; ?>

<div class="container py-4">
  <h2>Reservar libro</h2>
  <p>Estás reservando: <strong><?= htmlspecialchars($libro['titulo']) ?></strong></p>

  <form method="post" action="index.php?controller=Reserva&action=guardar">
    <input type="hidden" name="id_libro" value="<?= (int)$libro['id_libro'] ?>">
    <button type="submit" class="btn btn-success">Confirmar reserva</button>
    <a href="index.php?controller=Libro&action=index" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>
