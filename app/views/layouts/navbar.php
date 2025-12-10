<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuarioAutenticado = isset($_SESSION['id_usuario']);
$nombreUsuario = $_SESSION['nombre'] ?? '';
$correoUsuario = $_SESSION['correo'] ?? '';

// Elegir emoji de avatar (persistir en sesión)
if ($usuarioAutenticado && empty($_SESSION['avatar_emoji'])) {
    $emojis = ['😀','😃','😄','😁','😆','😊','😎','🤓','🫠','🙂','🙃','🤩','🥳','🧐','🤠','🧑‍🎓','🧑‍💻','👩‍🏫'];
    $_SESSION['avatar_emoji'] = $emojis[array_rand($emojis)];
}
$avatarEmoji = $_SESSION['avatar_emoji'] ?? '👤';

// Ruta de archivo avatar personalizado
$avatarFilePath = $usuarioAutenticado ? __DIR__ . '/../../../public/img/avatars/avatar_' . intval($_SESSION['id_usuario']) . '.jpg' : null;
$avatarFileUrl  = $usuarioAutenticado && file_exists($avatarFilePath) ? BASE_URL . "/public/img/avatars/avatar_" . intval($_SESSION['id_usuario']) . ".jpg" : null;
?>

<!-- HEADER: logo (izq) - titulo (centro) - usuario (der) -->
<header class="site-header">
    <div class="header-inner">
        <div class="logo-wrap">
            <a href="<?= BASE_URL ?>index.php?controller=Libro&action=index" aria-label="Inicio">
                <img src="<?= BASE_URL ?>/public/img/Logos/L1.jpg" alt="Logo BibliotecKJ" class="logo">
            </a>
        </div>

        <div class="titulo-wrap">
            <h1 class="titulo">BIBLIOTEC.KJ</h1>
        </div>

        <div class="user-wrap">
<?php if ($usuarioAutenticado): ?>
    <div class="user-wrap">
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Usuario&action=perfil" class="user-link" aria-label="Perfil de usuario">
            <?php if ($avatarFileUrl): ?>
                <img src="<?= $avatarFileUrl ?>" alt="Avatar" class="avatar">
            <?php else: ?>
                <span class="avatar-emoji"><?= htmlspecialchars($avatarEmoji) ?></span>
            <?php endif; ?>
            <span class="user-name"><?= htmlspecialchars($nombreUsuario ?: 'Usuario') ?></span>
        </a>
        <!-- Logout separado -->
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Auth&action=logout" class="btn-logout">Cerrar Sesión</a>
    </div>
<?php else: ?>
    <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=LoginUsuario&action=form" class="btn-login">Iniciar Sesión</a>
<?php endif; ?>
        </div>
    </div>
</header>

<nav class="navbar-custom">
    <ul class="menu">
        <li><a href="<?= BASE_URL ?>index.php?controller=InicioPagina&action=index">Inicio</a></li>
        <li><a href="index.php?controller=Libro&action=index">Librería</a></li>

        <li class="despliegue">
            <a href="#" class="trigger">Género</a>
            <ul class="despliegue-menu">
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Comedia">Comedia</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Drama">Drama</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Acción">Acción</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Romance">Romance</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Biología">Biología</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Musical">Musical</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Biografía">Biografía</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Historia">Historia</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Matemáticas">Matemáticas</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Ciencias Sociales">Ciencias Sociales</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Ciencias Naturales">Ciencias Naturales</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Informática">Informática</a></li>
                <li><a href="<?= BASE_URL ?>index.php?controller=Libro&action=index&genero=Artística">Artística</a></li>
            </ul>
        </li>
    </ul>
</nav>

<style>
    /*  ESTILOS GENERALES  */
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background-color: #f4f1ec;
    }

    /* --- Layout header + nav según mockup --- */
    .site-header { 
        background: linear-gradient(180deg, #8b6f57, #7d5a50); 
        padding: 14px 12px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }
    
    .header-inner { 
        max-width: 1200px; 
        margin: 0 auto; 
        display: flex; 
        align-items: center; 
        position: relative; 
    }
    
    .logo-wrap { 
        flex: 0 0 auto; 
    }
    
    .logo { 
        width: 72px; 
        height: 72px; 
        border-radius: 12px; 
        object-fit: cover; 
        display: block; 
    }
    
    .titulo-wrap { 
        position: absolute; 
        left: 50%; 
        transform: translateX(-50%); 
        text-align: center; 
        pointer-events: none; 
    }
    
    .titulo { 
        font-family: 'Merriweather', serif; 
        color: #fff; 
        font-size: 42px; 
        margin: 0; 
        letter-spacing: 2px;
        font-weight: bold;
    }

    /* user area right */
    .user-wrap { 
        margin-left: auto; 
        display: flex; 
        align-items: center; 
        gap: 10px;
        position: relative;
    }
    
    .user-link { 
        display: flex; 
        align-items: center; 
        gap: 8px; 
        text-decoration: none; 
        color: inherit; 
    }
    
    .avatar { 
        width: 56px; 
        height: 56px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 3px solid rgba(255,255,255,0.15); 
        background: #fff; 
    }
    
    .avatar-emoji { 
        width: 56px; 
        height: 56px; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50%; 
        font-size: 24px; 
        background: linear-gradient(180deg, #f0e9e0, #e6dfd6); 
        border: 3px solid rgba(255,255,255,0.15); 
    }
    
    .user-name { 
        color: #dfffa0; 
        font-weight: 700; 
        font-size: 14px; 
        text-shadow: 0 1px 0 rgba(0,0,0,0.25); 
    }

    /* Botón Cerrar Sesión (debajo del usuario) */
    .nav-right {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 6px;
        display: flex;
        align-items: center;
    }

    .btn-logout {
        background: transparent;
        color: #ffd6d6;
        border: 2px solid rgba(255,255,255,0.06);
        padding: 6px 12px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 700;
        font-size: 12px;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background: rgba(255,100,100,0.1);
        border-color: #ffd6d6;
    }

    /* Login button when not authenticated */
    .btn-login { 
        background: #8b6f57; 
        color: #fff; 
        padding: 10px 18px; 
        border-radius: 20px; 
        text-decoration: none; 
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-login:hover { 
        background: #7d5a50; 
        transform: scale(1.05);
    }

    /* NAVBAR */
    .navbar-custom {
        background: #2f2624;
        padding: 12px 0;
        box-shadow: 0 3px 6px rgba(0,0,0,0.2);
    }

    .menu {
        list-style: none;
        display: flex;
        justify-content: center;
        gap: 35px;
        margin: 0;
        padding: 0;
        align-items: center;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .menu li {
        position: relative;
        padding: 0;
    }

    .menu a {
        font-family: 'Merriweather', serif;
        text-decoration: none;
        color: #f3ebe2;
        font-size: 17px;
        font-weight: 500;
        padding: 8px 14px;
        border-radius: 6px;
        transition: 0.25s ease;
        display: inline-block;
    }

    .menu a:hover {
        background: #6a584b;
        color: #fff;
    }

    /* DESPLEGABLES */
    .despliegue .despliegue-menu {
        visibility: hidden;
        opacity: 0;
        transform: translateY(6px);
        pointer-events: none;

        position: absolute;
        top: calc(100% + 6px);
        left: 50%;
        transform-origin: top center;
        transform: translateX(-50%) translateY(6px);

        background: #6a584b;
        border-radius: 10px;
        min-width: 220px;
        padding: 8px 0;
        box-shadow: 0 8px 20px rgba(0,0,0,0.35);
        z-index: 2000;

        transition: opacity 200ms ease, transform 200ms ease, visibility 200ms;
    }

    .despliegue .despliegue-menu li {
        width: 100%;
        list-style: none;
    }

    .despliegue .despliegue-menu a {
        padding: 10px 18px;
        display: block;
        font-size: 15px;
        color: #fff;
    }

    .despliegue .despliegue-menu a:hover {
        background: #5f2905;
    }

    /* Mantener visible cuando el cursor esté sobre el li o sobre el propio submenu */
    .despliegue:hover > .despliegue-menu,
    .despliegue:focus-within > .despliegue-menu,
    .despliegue .despliegue-menu:hover {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }

    /* pequeña flecha opcional centrada */
    .despliegue .despliegue-menu::before {
        content: "";
        position: absolute;
        top: -6px;
        left: 50%;
        width: 12px;
        height: 12px;
        background: #6a584b;
        transform: translateX(-50%) rotate(45deg);
        z-index: -1;
    }

    /* animación */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* responsive */
    @media (max-width: 720px) {
        .menu {
            gap: 12px;
            flex-wrap: wrap;
        }
        
        .despliegue .despliegue-menu {
            left: 50%;
            min-width: 180px;
        }
        
        .user-name {
            display: none;
        }
        
        .avatar, .avatar-emoji {
            width: 44px;
            height: 44px;
            font-size: 18px;
        }

        .nav-right {
            position: static;
            margin-left: auto;
            margin-top: 0;
        }

        .btn-logout {
            font-size: 11px;
            padding: 4px 8px;
        }
    }
</style>

<script>
    /* Uso: hace que en pantallas táctiles se abra el submenu con click
       y evita que desaparezca al tocar el submenu inmediatamente. */
    (function(){
        const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
        if (!isTouch) return;

        document.querySelectorAll('.despliegue > .trigger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const li = this.parentElement;
                const open = li.classList.contains('open');
                // cerrar todos
                document.querySelectorAll('.despliegue.open').forEach(x => x.classList.remove('open'));
                if (!open) li.classList.add('open');
            });
        });

        // cerrar al tocar fuera
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.despliegue')) {
                document.querySelectorAll('.despliegue.open').forEach(x => x.classList.remove('open'));
            }
        });
    })();
</script>

<style>
    /* cuando la clase open está presente (aplica para touch) */
    .despliegue.open > .despliegue-menu {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) translateY(0);
    }
</style>
