<h1>Resultados para '<?= htmlspecialchars($texto) ?>'</h1>

<?php while ($row = $resultados->fetch_assoc()): ?>
    <div>
        <h3><?= $row["titulo"] ?></h3>
        <a href="index.php?controller=Libro&action=detalle&id=<?= $row["id_libro"] ?>">
            Ver detalles
        </a>
    </div>
<?php endwhile; ?>

<a href="index.php">Volver al catálogo</a>
