<!-- footer.php (completo) -->
<footer class="site-footer" role="contentinfo">
  <!-- Onda superior que se funde con el contenido -->
  <div class="wave-top" aria-hidden="true">
    <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
      <defs>
        <linearGradient id="ftrGrad" x1="0" x2="1" y1="0" y2="0">
          <stop offset="0%"  stop-color="#BC430D"/>
          <stop offset="50%" stop-color="#F09410"/>
          <stop offset="100%" stop-color="#BC430D"/>
        </linearGradient>
      </defs>
      <path d="M0,0 L0,40 C240,10 480,70 720,30 C960,-10 1200,50 1440,20 L1440,0 Z" fill="url(#ftrGrad)"></path>
    </svg>
  </div>

  <div class="footer-inner container">
    <!-- Columna marca - Izquierda -->
    <div class="f-col f-col-left">
      <div class="f-brand">
        <img src="<?= rtrim(BASE_URL, '/') ?>/public/img/Logos/L1.jpg" alt="Logo BibliotecKJ" class="f-logo" />
        <span class="f-name">BIBLIOTEC.KJ</span>
      </div>
      <br>
      <p class="f-text">Gestión de inventarios, préstamos y reservas en un solo lugar.</p>
    </div>

    <!-- Columna explorar - Centro -->
    <div class="f-col f-col-center">
      <h6 class="f-title">Explorar</h6>
      <ul class="f-links">
        <li><a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=catalogo">Catálogo</a></li>
        <li><a href="<?= rtrim(BASE_URL, '/') ?>/index.php?controller=Libro&action=index">Géneros</a></li>
        <li><a class='text-center' style='display:inline-block; margin:12px 0;' href='javascript:void(0)' onclick='abrirCreditos()'>Ver Creditos</a>
            <?php include __DIR__ . '/../Creditos/Creditos.php'; ?></li>
    </div>

    <!-- Columna soporte - Derecha -->
    <div class="f-col f-col-right">
      <h6 class="f-title">Soporte</h6>
      <ul class="f-links">
        <li><a href="mailto:soporte@biblioteckj.com">bibli0teckj01@gmail.com</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom container">
    <small>© <?= date('Y') ?> BibliotecKJ · Desarrollado por Kasandra Cifuentes y Junior Santamaria</small>
    <div class="social">
      <a class="social-btn" href="#" aria-label="Instagram" title="Instagram">
        <!-- Ícono IG -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/></svg>
      </a>
      <a class="social-btn" href="#" aria-label="X" title="X">
        <!-- Ícono X -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 4l16 16M20 4L4 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      </a>
      <a class="social-btn" href="#" aria-label="Facebook" title="Facebook">
        <!-- Ícono Fb -->
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M14 8h2V5h-2c-2.2 0-4 1.8-4 4v3H8v3h2v6h3v-6h2l1-3h-3V9c0-.6.4-1 1-1z" fill="currentColor"/></svg>
      </a>
    </div>
  </div>

</footer>

<style>
/* ====== Tokens de color y elevación (alineados con el header) ====== */
:root{
  --bg-1:#F0D0C7; --bg-2:#FEEAF0;
  --brand:#BC430D; --accent:#F09410; --ink:#241705; --surface:#FFFFFF;
  --dark-1:#2B1D17; --dark-2:#3A2822;
  --r-lg:14px; --r-xl:18px; --r-pill:999px;
  --sh-1:0 8px 24px rgba(36,23,5,.12);
  --sh-2:0 14px 40px rgba(36,23,5,.18);
}

/* ====== FOOTER ====== */
.site-footer{
  background:
    radial-gradient(900px 240px at 20% 0, rgba(188,67,13,.18), transparent 60%),
    linear-gradient(180deg, var(--dark-2), #1E140F);
  color:#fff;
  margin-top: 32px;
  border-top: 1px solid rgba(255,255,255,.08);
}
.wave-top svg{ display:block; width:100%; height:80px; }

.container{ max-width:1300px; }

/* Grid principal */
.footer-inner.container{
  padding: 20px 16px 8px;
  display:grid; 
  grid-template-columns: 1fr 1fr 1fr; 
  gap: 30px;
  align-items:flex-start;
}
.f-col-left{ text-align: left; }
.f-col-center{ text-align: center; }
.f-col-right{ text-align: right; }
@media (max-width: 992px){ 
  .footer-inner.container{ grid-template-columns: 1fr 1fr; } 
  .f-col-right{ grid-column: span 2; text-align: center; }
  .f-col-left, .f-col-center{ text-align: center; }
}
@media (max-width: 576px){ 
  .footer-inner.container{ grid-template-columns: 1fr; } 
  .f-col-right{ grid-column: span 1; }
}

/* Marca */
.f-brand{ display:flex; align-items:center; gap:20px; }
.f-logo{ width:100px; height:100px; border-radius:10px; object-fit:cover; box-shadow: 0 0 0 2px rgba(255,255,255,.08); }
.f-name{ font-family:'Merriweather', serif; font-weight:800; letter-spacing:.10em; text-transform:uppercase; }

/* Texto y enlaces */
.f-title{ font-family:'Merriweather', serif; font-size:20px; margin-bottom:20px; color:#FEEAF0; }
.f-text{ color: rgba(255,255,255,.86); max-width:420px; }
.f-links{ list-style:none; padding:0; margin:0; }
.f-links a{
  color: rgba(255,255,255,.86); text-decoration:none; display:inline-block; padding:6px 0;
  transition: color .15s ease;
}
.f-links a:hover{ color:#fff; text-decoration: underline; text-decoration-color: var(--accent); }

/* Línea inferior */
.footer-bottom.container{
  padding: 15px 16px 22px;
  display:flex; align-items:center; justify-content:space-between;
  border-top: 1px solid rgba(255,255,255,.08);
}
.footer-bottom small{ opacity:.9; }

/* Social */
.social{ display:flex; gap:10px; }
.social-btn{
  width:36px; height:36px; display:grid; place-items:center;
  border-radius:50%; background: rgba(255,255,255,.10); color:#fff; text-decoration:none;
  box-shadow: var(--sh-1); transition: background .2s ease, transform .12s ease;
}
.social-btn:hover{ background: rgba(255,255,255,.18); transform: translateY(-1px); }

/* Responsive fino */
@media (max-width: 576px){
  .footer-bottom.container{ flex-direction:column; gap:10px; text-align:center; }
}

/* Modal Creditos */
.modal-bibliotec{
  display:none; position:fixed; inset:0; z-index:1000;
  background:rgba(0,0,0,0.7); backdrop-filter:blur(5px);
  align-items:center; justify-content:center; padding:32px 16px;
}
.modal-content-bibliotec{
  background: #ffffffde; padding:25px; width:90%; max-width:720px;
  border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.3);
  position:relative; font-family:"Poppins", Arial, sans-serif;
  max-height:90vh; overflow:auto;
}
.close-modal{
  position:absolute; top:12px; right:16px; color:#aaa;
  font-size:28px; font-weight:700; cursor:pointer;
}
.close-modal:hover{ color:#d9534f; }
.modal-header h2{ color: #9b631b; border-bottom:2px solid #966600; padding-bottom:10px; }
.creditos-section h3{ color: #854d03; font-size:1.05rem; margin-top:15px; }
.creditos-section p{ line-height:1.6; color: #291c0c; margin-bottom:8px; }
.creditos-section a{ line-height:1.6; color: #9b6e33; margin-bottom:8px; }
.modal-footer{ margin-top:14px; text-align:right; color: #be6e04b2; font-size:0.9rem; }
@media (max-width:640px){
  .modal-content-bibliotec{ padding:18px; }
}

</style>

<script>
  // Mantiene la lógica anti caché tras logout
  window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  });

function abrirCreditos(){
  const modal = document.getElementById('modalCreditos');
  modal.style.display='flex';
  document.body.style.overflow='hidden';
}

function cerrarCreditos(){
  const modal = document.getElementById('modalCreditos');
  modal.style.display='none';
  document.body.style.overflow='';
}

window.onclick = function(e){
  const modal = document.getElementById('modalCreditos');
  if(e.target === modal){ cerrarCreditos(); }
};
</script>

</body>
</html>
