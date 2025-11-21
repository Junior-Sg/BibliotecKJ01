<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliotec_KJ - Acceso</title>
  <link rel="stylesheet" href="../../../public/css/Login_usuario.css">
</head>
<body>
  <div class="book">
    <!-- PORTADA -->
    <div class="cover" id="cover">
      <div class="cover-content">
        <h1>Bibliotec_KJ</h1>
        <p>Tu mundo de conocimiento</p>
        <button id="openBook">Ver libro</button>
      </div>
    </div>

    <!-- LOGIN -->
    <div class="page page-login" id="loginPage">
      <form action="../../Controllers/LoginUsuarioController.php" method="POST">
        <h2>Inicio de Sesión</h2>
        <div class="input-group">
          <label for="usuario">Usuario</label>
          <input type="text" name="usuario" id="usuario" required>
        </div>
        <div class="input-group">
          <label for="clave">Contraseña</label>
          <input type="password" name="clave" id="clave" required>
        </div>
        <button type="submit">Ingresar</button>

        <div class="extra">
          <a href="#" id="openRegister">¿No tienes cuenta? Regístrate</a>
        </div>
        <div class="back">
          <a href="#" id="closeBook">← Cerrar libro</a>
        </div>
      </form>
    </div>

    <!-- REGISTRO -->
    <div class="page page-register" id="registerPage">
      <form action="../../Controllers/RegistrarUsuarioController.php" method="POST">
        <h2>Registro de Usuario</h2>
        <div class="input-group">
          <label for="nombre">Nombre Completo</label>
          <input type="text" name="nombre" id="nombre" required>
        </div>
        <div class="input-group">
          <label for="correo">Correo</label>
          <input type="email" name="correo" id="correo" required>
        </div>
        <div class="input-group">
          <label for="usuario_reg">Usuario</label>
          <input type="text" name="usuario_reg" id="usuario_reg" required>
        </div>
        <div class="input-group">
          <label for="clave_reg">Contraseña</label>
          <input type="password" name="clave_reg" id="clave_reg" required>
        </div>
        <button type="submit">Registrar</button>

        <div class="extra">
          <a href="#" id="backToLogin">← Volver al inicio</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const book = document.querySelector(".book");
    const openBook = document.getElementById("openBook");
    const openRegister = document.getElementById("openRegister");
    const backToLogin = document.getElementById("backToLogin");
    const closeBook = document.getElementById("closeBook");

    // abrir libro
    openBook.addEventListener("click", () => {
      book.classList.add("open");
      book.classList.remove("flip");
    });

    // pasar a registro
    openRegister.addEventListener("click", e => {
      e.preventDefault();
      book.classList.add("flip");
    });

    // volver al login
    backToLogin.addEventListener("click", e => {
      e.preventDefault();
      book.classList.remove("flip");
    });

    // cerrar libro (volver a portada)
    closeBook.addEventListener("click", e => {
      e.preventDefault();
      book.classList.remove("open");
      book.classList.remove("flip");
    });
  </script>
</body>
</html>
