<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuarioAutenticado = isset($_SESSION['id_usuario']);
$nombreUsuario = $_SESSION['nombre'] ?? '';
$correoUsuario = $_SESSION['correo'] ?? '';


$avatarEmoji = $_SESSION['avatar_emoji'] ?? '👤';

$avatarFilePath = $usuarioAutenticado ? __DIR__ . '/../../../public/img/avatars/avatar_' . intval($_SESSION['id_usuario']) . '.jpg' : null;
$avatarFileUrl  = ($usuarioAutenticado && file_exists($avatarFilePath))
    ? rtrim(BASE_URL, '/') . "/public/img/avatars/avatar_" . intval($_SESSION['id_usuario']) . ".jpg"
    : null;

// Para estado activo del menú (opcional)
$activeController = strtolower($_GET['controller'] ?? 'iniciopagina');
?>

<!-- ====== HEADER IMPACTANTE ====== -->
<header class="app-header" role="banner">
  <div class="app-header__inner">
    <!-- Marca -->
    <a class="brand" href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=InicioPagina&action=index" aria-label="Inicio">
      <img src="<?= rtrim(BASE_URL, '/') ?>/public/img/Logos/L1.jpg" alt="Logo BibliotecKJ" class="brand__logo" />
      <span class="brand__name">BIBLIOTEC_KJ</span>
    </a>

    <!-- Navegación -->
    <nav class="main-nav" role="navigation" aria-label="Navegación principal">
      <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainMenu" aria-label="Abrir menú">
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
        <span class="nav-toggle__bar"></span>
      </button>

      <ul id="mainMenu" class="menu">
        <li>
          <a class="menu__link <?= ($activeController === 'iniciopagina' ? 'is-active' : '') ?>"
             href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=InicioPagina&action=index">Inicio</a>
        </li>
        <li>
          <a class="menu__link <?= ($activeController === 'libro' && ($_GET['action'] ?? '') === 'index' ? 'is-active' : '') ?>"
             href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=index">Género</a>
        </li>
        <li>
          <a class="menu__link <?= ($activeController === 'libro' && ($_GET['action'] ?? '') === 'catalogo' ? 'is-active' : '') ?>"
             href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=catalogo">Librería</a>
        </li>
      </ul>
    </nav>

    <!-- Usuario -->
    <div class="user-area">
      <?php if ($usuarioAutenticado): ?>
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Usuario&action=perfil" class="user-link" aria-label="Perfil de usuario">
          <?php if ($avatarFileUrl): ?>
            <img src="<?= $avatarFileUrl ?>" alt="Avatar" class="avatar" />
          <?php else: ?>
            <span class="avatar-emoji"><?= htmlspecialchars($avatarEmoji) ?></span>
          <?php endif; ?>
          <span class="user-name"><?= htmlspecialchars($nombreUsuario ?: 'Usuario') ?></span>
        </a>
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Logout&action=index" class="btn btn--ghost">Cerrar sesión</a>
      <?php else: ?>
        <a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=LoginUsuario&action=index" class="btn btn--primary">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Onda separadora -->
  <div class="header-wave" aria-hidden="true">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
      <defs>
        <linearGradient id="hdrGrad" x1="0" x2="1" y1="0" y2="0">
          <stop offset="0%"  stop-color="#BC430D"/>
          <stop offset="50%" stop-color="#F09410"/>
          <stop offset="100%" stop-color="#BC430D"/>
        </linearGradient>
      </defs>
      <path d="M0,40 C240,10 480,70 720,30 C960,-5 1200,55 1440,24 L1440,80 L0,80 Z" fill="url(#hdrGrad)"></path>
    </svg>
  </div>
</header>



<!-- ====== Botón volver arriba ====== -->
<a href="#" id="scrollToTopBtn" title="Volver arriba" aria-label="Volver arriba">
  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
    <line x1="12" y1="19" x2="12" y2="5"></line>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</a>

<style>
/* ====== Tokens de diseño (paleta) ====== */
:root{
  
  
  --brand:#BC430D; --accent:#F09410; --ink:#241705; --surface:#FFFFFF;
  --dark-1:#2B1D17; --dark-2:#3A2822;

  --r-lg:14px; --r-xl:18px; --r-pill:999px;
  --sh-1:0 8px 24px rgba(36,23,5,.12);
  --sh-2:0 14px 40px rgba(36,23,5,.18);
}

/* Fondo general */
body{
  margin:0;
  font-family:'Poppins', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
  color: var(--ink);
  background:
    radial-gradient(1200px 600px at 50% -200px, rgba(188,67,13,.18), transparent 60%),
    linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 100%);
  letter-spacing:.01em;
}

/* ====== HEADER STICKY ====== */
.app-header{
  position: sticky; top:0; z-index:1200;
  background: linear-gradient(180deg, var(--dark-2), var(--dark-1));
  color:#fff;
  box-shadow: 0 1px 0 rgba(255,255,255,.06);
}
.app-header.scrolled{
  backdrop-filter: blur(10px) saturate(140%);
  box-shadow: var(--sh-2);
}

.app-header__inner{
  max-width:1180px; margin:0 auto; padding:12px 16px;
  display:flex; align-items:center; gap:12px;
}

/* Marca */
.brand{ display:flex; align-items:center; gap:10px; text-decoration:none; }
.brand__logo{ width:200px; height:200px; border-radius:10px; object-fit:cover; box-shadow: 0 0 0 2px rgba(255,255,255,.08);}
.brand__name{
  font-family:'Merriweather', serif; font-weight:800; letter-spacing:.08em;
  text-transform:uppercase; color:#fff; font-size: clamp(18px, 3vw, 24px);
}

/* Menú principal */
.main-nav{ margin-left:auto; }
.nav-toggle{ display:none; flex-direction:column; gap:5px; background:transparent; border:none; cursor:pointer; }
.nav-toggle__bar{ width:24px; height:2px; background:#fff; border-radius:2px; }

.menu{ display:flex; gap:22px; list-style:none; margin:0; padding:0; }
.menu__link{
  position:relative; display:inline-block; padding:8px 6px; color:#fff; text-decoration:none;
  font-weight:500; letter-spacing:.02em;
}
.menu__link::after{
  content:""; position:absolute; left:0; right:0; bottom:-6px; height:2px;
  background: linear-gradient(90deg, var(--accent), var(--brand));
  transform: scaleX(0); transform-origin:left; transition: transform .18s ease;
}
.menu__link:hover::after, .menu__link.is-active::after{ transform: scaleX(1); }

/* Usuario */
.user-area{ margin-left: 18px; display:flex; align-items:center; gap:10px; }
.user-link{ display:flex; align-items:center; gap:8px; color:#fff; text-decoration:none; }
.avatar{ width:40px; height:40px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,.18); background:#fff; }
.avatar-emoji{
  width:40px; height:40px; display:grid; place-items:center; border-radius:50%;
  font-size:18px; background: linear-gradient(180deg, #f0e9e0, #e6dfd6); border:2px solid rgba(255,255,255,.18);
}
.user-name{ font-weight:600; opacity:.95; }

/* Botones */
.btn{ display:inline-block; font-weight:700; text-decoration:none; border-radius: var(--r-pill);
  padding: 8px 14px; transition: transform .12s ease, box-shadow .2s ease, background .2s ease, color .2s ease; }
.btn--primary{
  background: linear-gradient(180deg, var(--accent), #D8790E); color:#fff; box-shadow: var(--sh-1);
}
.btn--primary:hover{ transform: translateY(-1px); background: linear-gradient(180deg, var(--brand), #8F2E0F); }
.btn--ghost{
  color:#fff; border:1.6px solid rgba(255,255,255,.35); background: transparent;
}
.btn--ghost:hover{ background: rgba(255,255,255,.10); }

/* Onda inferior del header */
.header-wave svg{ display:block; width:100%; height:80px; }

/* ====== Botón volver arriba ====== */
#scrollToTopBtn{
  display:none; position:fixed; bottom:22px; right:28px; z-index:1201;
  width:44px; height:44px; border:none; outline:none; cursor:pointer;
  background: var(--accent); color:#fff; border-radius:50%;
  box-shadow: var(--sh-1); transition: opacity .25s, transform .25s, background .2s;
  display:grid; place-items:center;
}
#scrollToTopBtn:hover{ background: var(--brand); transform: translateY(-2px) scale(1.05); }

/* ====== Responsive ====== */
@media (max-width: 980px){
  .nav-toggle{ display:flex; }
  .menu{
    position:absolute; right:16px; top:62px; width: min(92vw, 360px);
    flex-direction:column; gap:8px; padding:12px;
    background: rgba(46,31,25,.92); backdrop-filter: blur(12px);
    border:1px solid rgba(255,255,255,.08); border-radius: 14px; box-shadow: var(--sh-2);
    transform-origin: top right; transform: scale(.98); opacity:0; pointer-events:none;
    transition: transform .18s ease, opacity .18s ease;
  }
  .menu.is-open{ transform: scale(1); opacity:1; pointer-events:auto; }
}

@media (max-width: 600px){
  .brand__name{ display:none; } /* compacta marca en móviles */
  .user-name{ display:none; }
}
</style>

<script>
  (function(){
    const header = document.querySelector('.app-header');
    const toggle = document.getElementById('navToggle');
    const menu   = document.getElementById('mainMenu');
    const upBtn  = document.getElementById('scrollToTopBtn');

    // Estado sticky/compacto
    const onScroll = () => {
      (window.scrollY > 12) ? header.classList.add('scrolled') : header.classList.remove('scrolled');
      // Mostrar/ocultar botón subir
      if (document.documentElement.scrollTop > 100 || document.body.scrollTop > 100) {
        upBtn.style.display = 'grid';
      } else {
        upBtn.style.display = 'none';
      }
    };
    onScroll(); window.addEventListener('scroll', onScroll);

    // Toggle menú móvil
    if (toggle && menu){
      toggle.addEventListener('click', () => {
        const open = menu.classList.toggle('is-open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      });
      // Cerrar al elegir una opción
      menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded','false');
      }));
      // Cerrar al hacer click fuera
      document.addEventListener('click', e => {
        if (!e.target.closest('.main-nav')) { menu.classList.remove('is-open'); toggle.setAttribute('aria-expanded','false'); }
      });
    }

    // Acción del botón “volver arriba”
    upBtn.addEventListener('click', (e) => {
      e.preventDefault();
      window.scrollTo({ top:0, behavior:'smooth' });
    });
  })();
</script>
