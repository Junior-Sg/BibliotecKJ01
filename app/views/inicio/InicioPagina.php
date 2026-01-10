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
            background: url('<?= BASE_URL ?>/public/img/Carrucel')
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

        /* TABS DE FILTROS */
        .nav-pills .nav-link {
            color: #8b6f57;
            background-color: #fff;
            border: 1px solid #8b6f57;
            margin: 0 5px;
        }
        .nav-pills .nav-link.active {
            color: #fff;
            background-color: #8b6f57;
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

        /* Ocultar elementos por defecto para JS */
        .libro-item.hidden {
            display: none;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<?php include __DIR__ . '../../layouts/navbar.php'; ?>

<?php include __DIR__ . '../../libros/busqueda.php'; ?>

<div class="container mt-4">

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

    <!-- SECCIÓN: LIBROS MÁS RESERVADOS -->
    <div class="text-center mt-5">
        <h2 class="section-title">LIBROS MÁS RESERVADOS</h2>
    </div>

    <!-- Filtros -->
    <ul class="nav nav-pills justify-content-center mb-4" id="reservados-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="hoy-tab" data-bs-toggle="pill" data-bs-target="#hoy" type="button" role="tab">Hoy</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="semanal-tab" data-bs-toggle="pill" data-bs-target="#semanal" type="button" role="tab">Semanal</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mensual-tab" data-bs-toggle="pill" data-bs-target="#mensual" type="button" role="tab">Mensual</button>
        </li>
    </ul>

    <!-- Contenido Libros Reservados -->
    <div class="tab-content" id="reservados-content">
        <!-- Para simplificar, usamos los mismos datos. En una app real, aquí irían datos distintos por pestaña -->
        <div class="tab-pane fade show active" id="hoy" role="tabpanel">
            <div class="row row-cols-1 row-cols-md-3 g-4" id="lista-reservados">
                <?php foreach ($masreservados as $index => $l): ?>
                    <div class="col libro-item <?= $index >= 3 ? 'hidden' : '' ?>">
                        <div class="mini-card mx-auto">
                            <img src="<?= BASE_URL ?>/public/img/Libros/<?= $l['Imagen'] ?>" alt="<?= htmlspecialchars($l['titulo']) ?>">
                            <p class="mt-2 small fw-bold"><?= htmlspecialchars($l['titulo']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($masreservados) > 3): ?>
            <div class="text-center mt-4">
                <button id="ver-mas-reservados" class="btn btn-outline-light">Ver más</button>
            </div>
            <?php endif; ?>
        </div>
        <!-- Aquí irían los otros tab-pane para Semanal y Mensual -->
    </div>

    <!-- SECCIÓN: LIBROS NUEVOS -->
    <div class="text-center mt-5">
        <h2 class="section-title">LIBROS NUEVOS EN LA BIBLIOTECA</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-4 mb-5" id="lista-nuevos">
         <?php foreach ($nuevos as $index => $l): ?>
            <div class="col libro-item <?= $index >= 4 ? 'hidden' : '' ?>">
                <div class="mini-card mx-auto">
                    <img src="<?= BASE_URL ?>/public/img/Libros/<?= $l['Imagen'] ?>" alt="<?= htmlspecialchars($l['titulo']) ?>">
                    <p class="mt-2 small fw-bold"><?= htmlspecialchars($l['titulo']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($nuevos) > 4): ?>
    <div class="text-center mt-2 mb-5">
        <button id="ver-mas-nuevos" class="btn btn-outline-light">Ver más</button>
    </div>
    <?php endif; ?>

</div>

<!-- FOOTER -->
<?php include __DIR__ . '../../layouts/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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

// ---- LÓGICA "VER MÁS" ----

// Para libros más reservados
const btnVerMasReservados = document.getElementById('ver-mas-reservados');
if (btnVerMasReservados) {
    btnVerMasReservados.addEventListener('click', function() {
        const itemsOcultos = document.querySelectorAll('#lista-reservados .libro-item.hidden');
        const itemsAMostrar = Array.from(itemsOcultos).slice(0, 3);
        
        itemsAMostrar.forEach(item => item.classList.remove('hidden'));

        // Si ya no hay más ítems ocultos, esconde el botón "Ver más"
        if (document.querySelectorAll('#lista-reservados .libro-item.hidden').length === 0) {
            this.style.display = 'none';
        }
    });
}

// Para libros nuevos
const btnVerMasNuevos = document.getElementById('ver-mas-nuevos');
if (btnVerMasNuevos) {
    btnVerMasNuevos.addEventListener('click', function() {
        const itemsOcultos = document.querySelectorAll('#lista-nuevos .libro-item.hidden');
        const itemsAMostrar = Array.from(itemsOcultos).slice(0, 4);
        
        itemsAMostrar.forEach(item => item.classList.remove('hidden'));

        if (document.querySelectorAll('#lista-nuevos .libro-item.hidden').length === 0) {
            this.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
