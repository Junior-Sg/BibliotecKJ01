<!-- Barra lateral del admin -->
  <nav class="sidebar bg-dark text-white p-3">

    <div class="sidebar-brand text-center mb-4">
      <img src="/BibliotecKJ01/public/img/Logos/L1.jpg" class="logo-sidebar mb-2" alt="Logo">
      <h4 class="fw-bold">Bibliotec_KJ</h4>
    </div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>Inicio/index"> <i class="bi bi-house">  Inicio</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>Prestamo/crear"><i class="bi bi-journal-check">  Prestamos</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="bi bi-arrow-clockwise">  Devolución de libros</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="bi bi-calendar3">  Reservas</i></a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>Usuarios/index"><i class="bi bi-people-fill">  Gestión de usuarios</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>Inventario/index"><i class="bi bi-box-fill">  Gestionar Inventario</i> </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="#"><i class="bi bi-clipboard2-data-fill">  Informes</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>Logout/index"><i class="bi bi-door-closed-fill">  Cerrar sesión</i></a>
    </ul>

  </nav>

<style>
  /* Sidebar fijo a la izquierda */
  .sidebar {
    width: 240px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    overflow-y: auto;
    z-index: 1020;
    padding-top: 24px;
  }

  /* Enlaces del sidebar */
  .sidebar .nav-link {
    padding: 10px 15px;
    border-radius: 8px;
    transition: background 0.2s;
  }

  .sidebar .nav-link:hover {
    background: #495057;
  }

  .sidebar-brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px 0 18px 0;
  }

  .logo-sidebar {
    width: 80px;
    height: auto;
    border-radius: 10px;
    display: block;
    margin: 0 auto;
  }

  /* Margen para el contenido principal cuando el sidebar está fijo */
  .main-content {
    margin-left: 240px;
    padding: 24px;
  }
</style>