<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $libro["titulo"] ?></title>
</head>
<body>

<h1><?= $libro["titulo"] ?></h1>

<p><strong>Año:</strong> <?= $libro["año_publicacion"] ?></p>
<p><strong>Editorial:</strong> <?= $libro["editorial"] ?></p>

<p><strong>Autores:</strong>
    <?php
    while ($a = $autores->fetch_assoc()) {
        echo $a["nombre"] . ", ";
    }
    ?>
</p>

<p><strong>Géneros:</strong>
    <?php
    while ($g = $generos->fetch_assoc()) {
        echo $g["nombre"] . ", ";
    }
    ?>
</p>

<p><strong>Disponibilidad:</strong> 
    <?= $disponibilidad["cantidad_disponible"] ?? 0 ?>
</p>

<a href="index.php">Volver al catálogo</a>

</body>
</html>
