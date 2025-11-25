<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Libros</title>
</head>
<body>

<h1>Catálogo de Libros</h1>

<form action="index.php?controller=Libro&action=buscar" method="GET">
    <input type="text" name="texto" placeholder="Buscar libro..." required>
    <button type="submit">Buscar</button>
</form>

<hr>

<?php while ($fila = $libros->fetch_assoc()): ?>
    <div style="margin-bottom: 1rem;">
        <h3><?= $fila["titulo"] ?></h3>
        <p><strong>Editorial:</strong> <?= $fila["editorial"] ?></p>

        <a href="index.php?controller=Libro&action=detalle&id=<?= $fila["id_libro"] ?>">
            Ver detalles
        </a>
    </div>
<?php endwhile; ?>

</body>
</html>