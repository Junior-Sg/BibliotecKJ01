<?php
session_start();
if ($_SESSION["rol"] != 1) {
    header("Location: ../auth/Login_usuario.php?error=Acceso denegado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <title>Panel administrador</title>
</head>
<body>

    <?php include '../layouts/NavADM.php'; ?>

    <h1>Bienvenido ADMIN, <?= $_SESSION["nombre"] ?></h1>

</body>
</html>
