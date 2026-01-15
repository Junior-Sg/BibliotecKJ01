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
            background: url('<?= BASE_URL ?>public/img/Backgrounds/bg1.jpg') no-repeat center center fixed;
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
            max-width: 900px;
            height: 500px;
            border-radius: 14px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            background-size: contain;   /* 👈 CAMBIO CLAVE */
            background-repeat: no-repeat;
            background-color: #8b6f57;  /* relleno elegante */
            background-position: center;
            transition: background-image 0.5s ease-in-out;
        }
        
        .carrusel-box::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.35); /* oscurece la imagen */
            z-index: 1;
        }
        
        #carrusel-texto {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            color: #fff;
            font-size: 22px;
            text-align: center;
            max-width: 80%;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 10px;
            line-height: 1.4;
        }
        
        .carrusel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 48px;
            color: #fff;
            cursor: pointer;
            user-select: none;
            z-index: 4;
            text-shadow: 0 2px 6px rgba(0,0,0,0.8);
            background: rgba(0,0,0,0.3);
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.3s ease;
        }
        
        .carrusel-arrow:hover {
            background: rgba(0,0,0,0.5);
        }
        
        .carrusel-left  { left: 20px; }
        .carrusel-right { right: 20px; }

        .carrusel-dots {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 4;
            display: flex;
            gap: 8px;
        }

        .carrusel-dots span {
            font-size: 18px;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.6);
            transition: color 0.3s ease;
        }
        
        .carrusel-dots span.active {
            color: #fff;
            font-size: 24px;
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
            <span onclick="goToSlide(3)">•</span>
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

    <!-- SECCIÓN: LIBROS FAVORITOS -->
    <div class="text-center mt-5">
        <h2 class="section-title">LIBROS FAVORITOS DE LA COMUNIDAD</h2>
    </div>

    <div class="row row-cols-1 row-cols-md-4 g-4 mb-5" id="lista-favoritos">
         <?php foreach ($favoritos as $index => $l): ?>
            <div class="col libro-item <?= $index >= 4 ? 'hidden' : '' ?>">
                <div class="mini-card mx-auto">
                    <img src="<?= BASE_URL ?>/public/img/Libros/<?= $l['Imagen'] ?>" alt="<?= htmlspecialchars($l['titulo']) ?>">
                    <p class="mt-2 small fw-bold"><?= htmlspecialchars($l['titulo']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if (count($favoritos) > 4): ?>
    <div class="text-center mt-2 mb-5">
        <button id="ver-mas-favoritos" class="btn btn-outline-light">Ver más</button>
    </div>
    <?php endif; ?>

</div>

<!-- FOOTER -->
<?php include __DIR__ . '../../layouts/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ---- CARRUSEL AUTOMÁTICO ----
const slides = [
    { img: "<?= BASE_URL ?>public/img/carrucel/P1.png" },
    { img: "<?= BASE_URL ?>public/img/carrucel/P2.png" },
    { img: "<?= BASE_URL ?>public/img/carrucel/P3.png" },
    { img: "<?= BASE_URL ?>public/img/carrucel/P4.png" }
];

let index = 0;
const carruselTexto = document.getElementById("carrusel-texto");
const carruselBox = document.querySelector(".carrusel-box");
const carruselDots = document.querySelectorAll(".carrusel-dots span");

function updateCarrusel() {
    carruselTexto.textContent = slides[index].text;

    // ✅ Corrección: actualizar directamente el fondo
    carruselBox.style.backgroundImage = `url('${slides[index].img}')`;

    // Actualiza los puntos del carrusel
    carruselDots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

function cambiarSlide(dir) {
    index = (index + dir + slides.length) % slides.length;
    updateCarrusel();
}

function goToSlide(i) {
    index = i;
    updateCarrusel();
}

// Auto cada 5 segundos
setInterval(() => cambiarSlide(1), 5000);

// Inicializar la primera imagen al cargar
document.addEventListener('DOMContentLoaded', function() {
    updateCarrusel();
});

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

// Para libros favoritos
const btnVerMasFavoritos = document.getElementById('ver-mas-favoritos');
if (btnVerMasFavoritos) {
    btnVerMasFavoritos.addEventListener('click', function() {
        const itemsOcultos = document.querySelectorAll('#lista-favoritos .libro-item.hidden');
        const itemsAMostrar = Array.from(itemsOcultos).slice(0, 4);
        
        itemsAMostrar.forEach(item => item.classList.remove('hidden'));

        if (document.querySelectorAll('#lista-favoritos .libro-item.hidden').length === 0) {
            this.style.display = 'none';
        }
    });
}
</script>

</body>
</html>
