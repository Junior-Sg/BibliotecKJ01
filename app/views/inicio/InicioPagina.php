<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Biblioteca</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

    <style>
        body {
            background: url('<?= BASE_URL ?>/public/img/fondo-textura.jpg');
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
        }

        /* BUSCADOR */
        .buscador-wrap {
            margin: 20px auto;
            max-width: 700px;
            position: relative;
        }
        .buscador-input {
            width: 100%;
            padding: 14px 20px;
            border-radius: 40px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .buscador-btn {
            position: absolute;
            right: 6px;
            top: 6px;
            bottom: 6px;
            padding: 4px 18px;
            border-radius: 40px;
            background:#8b6f57;
            color:#fff;
            border:none;
        }

        /* CARRUSEL */
        .carrusel-box {
            background: #8b6f57;
            margin: 20px auto;
            padding: 35px 20px;
            max-width: 900px;
            border-radius: 14px;
            text-align: center;
            color: #fff;
            font-size: 26px;
            position: relative;
        }
        .carrusel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 40px;
            color: #fff;
            cursor: pointer;
            user-select: none;
        }
        .carrusel-left  { left: 20px; }
        .carrusel-right { right: 20px; }

        .carrusel-dots span {
            font-size: 32px;
            margin: 0 3px;
            cursor:pointer;
        }

        /* TITULOS DE SECCIÓN */
        .section-title {
            font-family: 'Merriweather', serif;
            font-size: 28px;
            color: #fff;
            background: #8b6f57;
            padding: 10px 20px;
            border-radius: 10px;
            display: inline-block;
            margin-bottom: 20px;
        }

        /* CARDS MINIATURE (como la imagen) */
        .mini-card {
            width: 160px;
            border-radius: 12px;
            background: #fff;
            padding: 8px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }
        .mini-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<?php include __DIR__ . '../../layouts/navbar.php'; ?>

<div class="container mt-4">

    <!-- BUSCADOR -->
    <div class="buscador-wrap">
        <form action="index.php" method="get">
            <input type="hidden" name="controller" value="Libro">
            <input type="hidden" name="action" value="buscar">
            <input class="buscador-input" type="text" name="q" placeholder="Buscar por título o autor...">
            <button class="buscador-btn">Buscar</button>
        </form>
    </div>

    <!-- CARRUSEL -->
    <div class="carrusel-box">
        <div id="carrusel-texto">INFO DE LA PÁGINA EN UN CARRUSEL</div>

        <div class="carrusel-arrow carrusel-left" onclick="cambiarSlide(-1)">❮</div>
        <div class="carrusel-arrow carrusel-right" onclick="cambiarSlide(1)">❯</div>

        <div class="carrusel-dots mt-3">
            <span onclick="goToSlide(0)">•</span>
            <span onclick="goToSlide(1)">•</span>
            <span onclick="goToSlide(2)">•</span>
        </div>
    </div>

    <!-- SECCIÓN: Más reservados -->
    <div class="text-center">
        <h2 class="section-title">LIBROS MÁS RESERVADOS</h2>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-4 mb-5">
        <?php foreach ($masreservados as $l): ?>
        <div class="mini-card">
            <img src="<?= BASE_URL ?>/public/img/Libros/<?= $l['Imagen'] ?>">
            <p class="mt-2 small"><?= htmlspecialchars($l['titulo']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- SECCIÓN: Libros nuevos -->
    <div class="text-center">
        <h2 class="section-title">LIBROS NUEVOS EN LA BIBLIOTECA</h2>
    </div>

    <div class="d-flex flex-wrap justify-content-center gap-4 mb-5">
        <?php foreach ($nuevos as $l): ?>
        <div class="mini-card">
            <img src="<?= BASE_URL ?>/public/img/Libros/<?= $l['Imagen'] ?>">
            <p class="mt-2 small"><?= htmlspecialchars($l['titulo']) ?></p>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- FOOTER -->
<?php include __DIR__ . '../../layouts/footer.php'; ?>

<script>
// ---- CARRUSEL AUTOMÁTICO ----
const textos = [
    "INFO DE LA PÁGINA EN UN CARRUSEL",
    "BIENVENIDO A LA BIBLIOTECA VIRTUAL",
    "RESERVA Y CONSULTA TUS LIBROS FAVORITOS"
];

let index = 0;
const carrusel = document.getElementById("carrusel-texto");

function cambiarSlide(dir) {
    index = (index + dir + textos.length) % textos.length;
    carrusel.textContent = textos[index];
}

function goToSlide(i) {
    index = i;
    carrusel.textContent = textos[index];
}

// Auto cada 5 segundos
setInterval(() => cambiarSlide(1), 5000);
</script>

</body>
</html>
