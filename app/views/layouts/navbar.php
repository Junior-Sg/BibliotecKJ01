<div class="header">

  <div class="header">
    <img src="/BibliotecKJ01/public/img/Logos/L1.jpg" alt="Logo_biblioteckj" class="logo">
    <h1 class="titulo">Bibliotec_KJ</h1>
  </div>

  <nav class="navbar-custom">
    <ul class="menu">
      <li><a href="/">Inicio</a></li>
      <li><a href="/">Librería</a></li>

      <li class="despliegue">
        <a href="#" class="trigger">Género</a>
        <ul class="despliegue-menu">
          <li><a href="/">Comedia</a></li>
          <li><a href="#">Drama</a></li>
          <li><a href="#">Acción</a></li>
          <li><a href="#">Romance</a></li>
          <li><a href="#">Biología</a></li>
          <li><a href="#">Musical</a></li>
          <li><a href="#">Biografía</a></li>
          <li><a href="#">Historia</a></li>
          <li><a href="#">Matemáticas</a></li>
          <li><a href="#">Ciencias Sociales</a></li>
          <li><a href="#">Ciencias Naturales</a></li>
          <li><a href="#">Informática</a></li>
          <li><a href="#">Artística</a></li>
        </ul>
      </li>

      <li class="despliegue">
        <a href="<?= BASE_URL ?>LoginUsuario" class="trigger">Iniciar Sesión</a>
      
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

    /*HEADER*/
    .header {
      position: relative;
      text-align: center;
      padding: 25px 0;
      background: linear-gradient(180deg, #3b2f2f, #6a584b);
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
    }

    .logo {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      width: 85px;
      height: 85px;
      border-radius: 12px;
      object-fit: cover;
    }

    .titulo {
      font-family: 'Merriweather', serif;
      font-size: 42px;
      font-weight: bold;
      letter-spacing: 2px;
      color: #f5f5f5;
      margin: 0;
    }

    /*NAVBAR*/
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

    /* DESPLEGABLES*/
    .despliegue .despliegue-menu {
      
      visibility: hidden;
      opacity: 0;
      transform: translateY(6px);
      pointer-events: none;

      position: absolute;
      top: calc(100% + 6px) 50%;
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
      background: #5f2905fd;
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
      transform: translateX(-50%);
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
      }
      .despliegue .despliegue-menu {
        left: 50%;
        min-width: 180px;
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
  </style>
