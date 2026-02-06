<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliotec_KJ - Acceso</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/BibliotecKJ01/public/css/login_usuario.css">

</head>
<body>

    <?php
    $alert = '';
    // Función auxiliar para devolver SVG según tipo
    function alert_icon_svg($type) {
      if ($type === 'success') {
        return '<svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 10.2L3.2 7.4a.8.8 0 10-1.1 1.1l3 3a.8.8 0 001.1 0l6-6a.8.8 0 10-1.1-1.1L6 10.2z" fill="#3b2b21"/></svg>';
      }
      if ($type === 'danger') {
        return '<svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 1.333a6.667 6.667 0 100 13.334A6.667 6.667 0 008 1.333zm0 9.334a.8.8 0 110 1.6.8.8 0 010-1.6zM7.2 3.6h1.6v5.333H7.2V3.6z" fill="#3b2b21"/></svg>';
      }
      // info
      return '<svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 1.333a6.667 6.667 0 100 13.334A6.667 6.667 0 008 1.333zm0 9.334a.667.667 0 110 1.334A.667.667 0 018 10.667zM7.333 5.333h1.334v2.667H7.333V5.333z" fill="#3b2b21"/></svg>';
    }

    if (!empty($_GET['msg'])) {
      $text = htmlspecialchars($_GET['msg']);
      if (strpos(strtolower($text), 'registro') !== false) {
        $icon = alert_icon_svg('success');
        $alert = "<div class='alert alert-success alert-custom' role='alert'><span class='alert-icon'>" . $icon . "</span><div><strong>$text.</strong> Ya puedes iniciar sesión.</div></div>";
      } else {
        $icon = alert_icon_svg('info');
        $alert = "<div class='alert alert-info alert-custom' role='alert'><span class='alert-icon'>" . $icon . "</span><div>" . $text . "</div></div>";
      }
    } elseif (!empty($_GET['message'])) {
      $text = htmlspecialchars($_GET['message']);
      $icon = alert_icon_svg('info');
      $alert = "<div class='alert alert-info alert-custom' role='alert'><span class='alert-icon'>" . $icon . "</span><div>" . $text . "</div></div>";
    } elseif (!empty($_GET['error'])) {
      $text = htmlspecialchars($_GET['error']);
      $icon = alert_icon_svg('danger');
      $alert = "<div class='alert alert-danger alert-custom' role='alert'><span class='alert-icon'>" . $icon . "</span><div><strong>Error:</strong> " . $text . "</div></div>";
    }

    echo $alert;
    ?>

  <div class="book-container">
    <div class="book">

      <!-- PORTADA -->
      <div class="cover" id="cover">
        <div class="cover-content">
          <div class="logo">
            <img src="/BibliotecKJ01/public/img/Logos/L1.jpg" alt="Logo Bibliotec_KJ">
          </div>
          <h1>Bibliotec_KJ</h1>
          <p>Tu mundo de conocimiento</p>
          <button id="openBook">Iniciar sesión</button>
          <a href="<?= BASE_URL ?>index.php?controller=InicioPagina&action=index" class="btn btn-secondary" id="backToHome">Volver</a>
        </div>
      </div>

      <!-- LOGIN -->
      <div class="page page-login" id="loginPage">
        <form action="<?= BASE_URL ?>index.php?controller=LoginUsuario&action=login" method="POST">
          <h2>Inicio de Sesión</h2>

          <div class="input-group">
            <label for="correo">Correo</label>
            <!-- CAMBIO: usuario → correo -->
            <input type="email" name="correo" id="correo" required>
          </div>

          <div class="input-group">
            <label for="clave">Contraseña</label>
            <input type="password" name="clave" id="clave" required>
          </div>

          <button type="submit">Ingresar</button>

          <div class="extra">
                      <a href="#" id="openRegister">¿No tienes cuenta? Regístrate</a>
          </div>

          <div class="extra">
            <a href="<?= BASE_URL ?>index.php?controller=PasswordReset&action=request">¿Olvidaste tu contraseña?</a>
          </div>

          <div class="back">
            <a href="#" id="closeBook">← Cerrar libro</a>
          </div>
        </form>
      </div>

      <!-- REGISTRO -->
      <div class="page page-register" id="registerPage">
        <form action="<?= BASE_URL ?>index.php?controller=RegistrarUsuario&action=registrar" method="POST">
          <h2>Registro de Usuario</h2>

          <div class="input-group">
            <label for="nombre">Nombre Completo</label>
            <input type="text" name="nombre" id="nombre" required
                   pattern="[A-Za-zÀ-ÿ\s]+"
                   title="Solo letras y espacios"
                   maxlength="100"
                   oninput="this.value = this.value.replace(/[^A-Za-zÀ-ÿ\s]/g, '');">
          </div>

          <div class="input-group">
            <label for="correoReg">Correo</label>
            <!-- CAMBIO: correo → correo -->
            <input type="email" name="correo" id="correoReg" required>
          </div>

          <div class="input-group">
            <label for="clave">Contraseña</label>
            <!-- CAMBIO: clave_reg → clave -->
            <input type="password" name="clave" id="clave_reg" required>
          </div>

          <div class="input-group">
            <label for="telefono">Teléfono</label>
            <input type="tel" name="telefono" id="telefono" pattern="[0-9]{10}" title="Solo 10 dígitos numéricos" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
          </div>

          <div class="input-group">
            <label for="tipo_documento">Tipo de documento</label>
            <select name="tipo_documento" id="tipo_documento" required>
              <option value="">-- Seleccione --</option>
              <option value="CC">CC</option>
              <option value="TI">TI</option>
              <option value="CE">CE</option>
            </select>
          </div>

          <div class="input-group">
            <label for="numero_documento">Número de documento</label>
            <input type="text" name="numero_documento" id="numero_documento" pattern="[0-9]+" title="Solo números" maxlength="30" required>
          </div>

          <button type="submit">Registrar</button>

          <div class="extra">
            <a href="<?= BASE_URL ?>index.php?controller=LoginUsuario&action=login" id="backToLogin">← Volver al inicio</a>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script>
    const book = document.querySelector(".book");
    const openBook = document.getElementById("openBook");
    const openRegister = document.getElementById("openRegister");
    const backToLogin = document.getElementById("backToLogin");
    const closeBook = document.getElementById("closeBook");

    openBook.addEventListener("click", () => {
      book.classList.add("open");
      book.classList.remove("flip");
    });

    openRegister.addEventListener("click", e => {
      e.preventDefault();
      book.classList.add("flip");
    });

    backToLogin.addEventListener("click", e => {
      e.preventDefault();
      book.classList.remove("flip");
    });

    closeBook.addEventListener("click", e => {
      e.preventDefault();
      book.classList.remove("open");
      book.classList.remove("flip");
    });
  </script>

  <script>
    // Auto-cerrar alertas personalizadas después de 5 segundos
    (function(){
      const alerts = document.querySelectorAll('.alert-custom');
      if (!alerts.length) return;
      alerts.forEach(alert => {
        // Añadir botón de cerrar rápido
        const btn = document.createElement('button');
        btn.className = 'close-small';
        btn.innerHTML = '&times;';
        btn.addEventListener('click', () => {
          alert.classList.add('fade-out');
          setTimeout(() => alert.remove(), 500);
        });
        alert.appendChild(btn);

        // Auto hide
        setTimeout(() => {
          alert.classList.add('fade-out');
          setTimeout(() => { try { alert.remove(); } catch(e){} }, 500);
        }, 5000);
      });

      // Quitar parámetros de la URL para que la alerta no reaparezca al recargar
      try {
        if (window.history && history.replaceState) {
          const cleanUrl = window.location.pathname + window.location.hash;
          history.replaceState(null, '', cleanUrl);
        }
      } catch (e) {
       
      }
    })();
  </script>

</body>
</html>
