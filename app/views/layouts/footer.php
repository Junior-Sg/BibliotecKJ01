<!-- footer.php (fragmento) -->
<footer class="site-footer">
  <div class="container-footer">
    <p class="copyRay" style="font-family:'Merriweather', serif">
      © 2025 BibliotecKJ | Desarrollado por Kasandra Cifuentes y Junior Santamaria
    </p>
    <img src="/BibliotecKJ01/public/img/Logos/L1.jpg" alt="Logo BibliotecKJ" class="footerLogo">
    <style>
    
    /* --- Footer --- */
.site-footer {
  background: linear-gradient(180deg,#8b6f57,#7d5a50);
  color: #fff;
  padding: 20px 0;
  margin-top: 40px;
}
.container-footer {
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  gap: 16px;
  justify-content: center; /* centra texto y logo */
  padding: 0 16px;
}
.footerLogo {
  width: 50px;
  height: 50px;
  border-radius: 20px;
  margin-left: 18px;
}

/* Responsive */
@media (max-width: 700px) {
  .titulo { font-size: 28px; }
  .navbar .menu { flex-wrap: wrap; gap: 10px; }
  .container-footer { flex-direction: column; gap: 8px; padding: 12px; text-align: center; }
}

    </style>
  </div>
</footer>

<script>
    // Este script previene que las páginas protegidas se muestren desde el caché del navegador 
    // después de que un usuario ha cerrado sesión.
    // El evento 'pageshow' se dispara cada vez que una página se muestra.
    // La propiedad 'persisted' es 'true' si la página fue cargada desde el caché de navegación (back-forward cache).
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Si la página se está mostrando desde el caché, forzamos una recarga completa
            // desde el servidor. El servidor entonces verificará la sesión y redirigirá 
            // al login si el usuario ya no está autenticado.
            window.location.reload();
        }
    });
</script>
