 <!-- nav.php (fragmento) -->
 <header class="text-center">
    <div class="header">
  <img src="../../../public/img/Logos/L1.jpg" alt="Logo_biblioteckj" class="logo">
  <h1 class="titulo">Bibliotec_KJ</h1>
</div>
  </header>

<nav class="navbar" style="font-family:'Merriweather', serif">
  <ul class="menu">
    <li><a href="/">Inicio</a></li>
    <li><a href="/">Librería</a></li>
    <li class="despliegue">
      <a href="#">Género</a>
      <div class="despliegue-menu">
        <a href="#">Comedia</a>
        <a href="#">Drama</a>
        <a href="#">Acción</a>
        <a href="#">Romance</a>
        <a href="#">Biología</a>
        <a href="#">Musical</a>
        <a href="#">Biografía</a>
        <a href="#">Historia</a>
        <a href="#">Matemáticas</a>
        <a href="#">Ciencias Sociales</a>
        <a href="#">Ciencias Naturales</a>
        <a href="#">Informática</a>
        <a href="#">Artística</a>
      </div>
    </li>
    <li class="despliegue">
      <a href="#">Iniciar Sesión</a>
      <div class="despliegue-menu">
        <a href="">Usuario</a>
        <a href="">Administrador</a>
      </div>
    </li>
  </ul>
</nav>

<style>
  /* body */
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    margin: 0;
    font-family: 'Merriweather', serif;
    background-color: #f5f3f0;
}

/* Encabezado */
.header {
    position: relative;
    text-align: center;
    padding: 20px 0;
    background: linear-gradient(180deg,#8b6f57,#7d5a50);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Logo fijo a la izquierda */
.logo {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    width: 90px;
    height: 90px;
    border-radius: 20%;
    object-fit: cover;
}

/* Título centrado */
.titulo {
    font-family: 'Merriweather', serif;
    font-size: 45px;
    font-weight: bold;
    color: #f5f3f0;
    margin: 0;
}



/* Barra de navegación en horizontal */
.navbar {
    background: #161212;
    padding: 10px 0;
    justify-content: center;   /* centra el menú horizontalmente */
}

.menu {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 30px;
    margin: 0;
    padding: 0;
}

.menu li {
    position: relative;
}

.menu a {
    text-decoration: none;
    color: #f5f3f0;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background 0.3s, color 0.3s;
}

.menu a:hover {
    background: #7d5a50;
    color: #fff;
}

/* Estilos para desplegables */
.despliegue-menu {
    display: none;
    position: absolute;
    background: #7d5a50;
    min-width: 180px;
    top: 38px;
    left: 0;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    flex-direction: column;
    z-index: 1000;
}

.despliegue-menu a {
    padding: 10px;
    color: #fff;
    display: block;
    transition: background 0.3s;
}

.despliegue-menu a:hover {
    background: #4a3f35;
}

.despliegue:hover .despliegue-menu {
    display: flex;
}
</style>
</body>
</html>
