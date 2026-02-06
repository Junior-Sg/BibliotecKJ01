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
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/libros/Iniciopagina.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;500&display=swap" rel="stylesheet">

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
