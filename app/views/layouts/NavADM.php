<!-- Barra lateral del admin -->
  <nav class="sidebar bg-dark text-white p-3">

    <div class="sidebar-brand text-center mb-4">
      <img src="/BibliotecKJ01/public/img/Logos/L1.jpg" class="logo-sidebar mb-2" alt="Logo">
      <h4 class="fw-bold">Bibliotec_KJ</h4>
    </div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Inicio&action=index"> <i class="bi bi-house">  Inicio</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Prestamo&action=vistaCrearPrestamo"><i class="bi bi-journal-check">  Prestamos</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Prestamo&action=vistaDevoluciones"><i class="bi bi-arrow-clockwise">  Devolución de libros</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Reserva&action=gestion"><i class="bi bi-calendar3">  Reservas</i></a>
      </li>

      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Usuarios&action=index"><i class="bi bi-people-fill">  Gestión de usuarios</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Inventario&action=index"><i class="bi bi-box-fill">  Gestionar Inventario</i> </a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Reportes&action=index"><i class="bi bi-clipboard2-data-fill">  Informes</i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="<?= BASE_URL ?>index.php?controller=Logout&action=index"><i class="bi bi-door-closed-fill">  Cerrar sesión</i></a>
    </ul>

  </nav>

<style>
  
/* ===== Sidebar fijo (tonos más oscuros) ===== */

/* ===== Sidebar fijo (más oscuro, contraste alto) ===== */
.sidebar {
  width: 240px;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  overflow-y: auto;
  z-index: 1020;
  padding-top: 24px;

  /* Override a Bootstrap .bg-dark para asegurar nuestro degradado */
  background: linear-gradient(180deg, #1F0A07 0%, #260C08 40%, #2B0B08 100%) !important;
  color: #fff !important;
  box-shadow: 7px 0 18px rgba(0, 0, 0, 0.38);
  border-right: 1px solid rgba(169, 84, 26, 0.22); /* borde cálido tostado */
}

/* Marca y logo */
.sidebar-brand {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 8px 0 18px 0;
}
.sidebar-brand h4 {
  color: #fff;
  font-weight: 800;
  letter-spacing: 0.3px;
  margin-top: 8px;
  text-shadow: 0 1px 2px rgba(0,0,0,0.35);
}
.logo-sidebar {
  width: 80px;
  height: auto;
  border-radius: 12px;
  display: block;
  margin: 0 auto;
  border: 2px solid rgba(255,255,255,0.28);
  box-shadow: 0 4px 10px rgba(0,0,0,0.4);
}

/* ===== Enlaces ===== */
.sidebar .nav-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 15px;
  border-radius: 10px;
  color: #f6f2ee !important;
  font-weight: 600;
  transition: background 0.24s ease, color 0.24s ease, transform 0.16s ease;
  position: relative;
}

/* Íconos un poco más visibles, sin saturar */
.sidebar .nav-link i {
  font-size: 1.16rem;
  color: #f0e9e2;
}

/* Hover (oscuro y sobrio) */
.sidebar .nav-link:hover {
  background: rgba(70, 36, 18, 0.45); /* café muy oscuro translúcido */
  color: #ffffff !important;
  transform: translateX(3px);
}

/* Estado activo con indicador lateral cálido y discreto */
.sidebar .nav-link.active {
  background: rgba(100, 52, 24, 0.55);
  color: #ffffff !important;
  font-weight: 700;
}
.sidebar .nav-link.active::before {
  content: '';
  position: absolute;
  left: -6px;
  top: 8px;
  bottom: 8px;
  width: 3px;
  border-radius: 2px;
  background: #A9541A; /* ámbar tostado */
  box-shadow: 0 0 8px rgba(169, 84, 26, 0.55);
}

/* ===== Scrollbar oscura ===== */
.sidebar::-webkit-scrollbar { width: 7px; }
.sidebar::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.22);
  border-radius: 4px;
}
.sidebar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.32); }
.sidebar::-webkit-scrollbar-track { background: transparent; }

/* ===== Ajuste para el contenido principal ===== */
.main-content {
  margin-left: 240px;
  padding: 24px;
}

</style>