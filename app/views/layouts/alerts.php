<?php
// /app/views/layouts/alerts.php

$alert = '';

// Función auxiliar para devolver SVG según tipo
function alert_icon_svg($type) {
  $icons = [
    'success' => '<svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12z M10.97 5.97a.75.75 0 0 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L4.72 9.28a.75.75 0 0 1 1.06-1.06L7 9.94l3.97-3.97z"/></svg>',
    'danger' => '<svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6 0 0 0 0 12zM8 4a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 8 4zm0 8a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>',
    'info' => '<svg width="20" height="20" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0-1A6 6 0 1 0 8 2a6 6-0 0 0 0 12z M9 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM8 8a.75.75 0 0 1 .75.75v2.5a.75.75 0 0 1-1.5 0v-2.5A.75.75 0 0 1 8 8z"/></svg>'
  ];
  return $icons[$type] ?? $icons['info'];
}

$message_text = null;
$message_type = null;

if (!empty($_GET['msg_success'])) {
    $message_text = htmlspecialchars($_GET['msg_success']);
    $message_type = 'success';
} elseif (!empty($_GET['msg_error'])) {
    $message_text = htmlspecialchars($_GET['msg_error']);
    $message_type = 'danger';
} elseif (!empty($_GET['msg_info'])) {
    $message_text = htmlspecialchars($_GET['msg_info']);
    $message_type = 'info';
}

if ($message_text && $message_type) {
    $icon = alert_icon_svg($message_type);
    $alert = "<div class='alert-container'><div class='alert alert-{$message_type} alert-custom' role='alert'><span class='alert-icon'>{$icon}</span><div>{$message_text}</div></div></div>";
}

echo $alert;

?>
<style>
.alert-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999; /* Encima de la mayoría de los elementos de Bootstrap */
    min-width: 250px;
    max-width: 350px; /* Added to control max width */
}

.alert-custom {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    opacity: 1;
    transition: opacity 0.5s ease-out, transform 0.5s ease-out;
}

.alert-custom.fade-out {
    opacity: 0;
    transform: translateY(-20px);
}

.alert-custom .alert-icon {
    margin-right: 12px;
}

.alert-custom .close-small {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    line-height: 1;
    color: inherit;
    opacity: 0.7;
    margin-left: 15px;
    padding: 0;
}
.alert-custom .close-small:hover {
    opacity: 1;
}

.alert-success.alert-custom { background-color: #d1e7dd; color: #0f5132; }
.alert-danger.alert-custom { background-color: #f8d7da; color: #842029; }
.alert-info.alert-custom { background-color: #cff4fc; color: #055160; }

</style>
<script>
// Auto-cerrar alertas personalizadas después de 5 segundos
(function(){
  // Asegurarse que el script no se ejecute múltiples veces si se incluye en varios sitios
  if (window.alertsInitialized) return;
  window.alertsInitialized = true;

  const handleAlerts = () => {
    const alerts = document.querySelectorAll('.alert-custom');
    if (!alerts.length) return;

    alerts.forEach(alert => {
      // Añadir botón de cerrar si no existe
      if (!alert.querySelector('.close-small')) {
          const btn = document.createElement('button');
          btn.className = 'close-small';
          btn.innerHTML = '&times;';
          btn.addEventListener('click', () => {
            alert.classList.add('fade-out');
            setTimeout(() => alert.remove(), 500);
          });
          alert.appendChild(btn);
      }

      // Auto-ocultar
      setTimeout(() => {
        alert.classList.add('fade-out');
        setTimeout(() => { try { alert.remove(); } catch(e){} }, 500);
      }, 5000);
    });

    // Limpiar parámetros de la URL para que la alerta no reaparezca al recargar
    try {
      if (window.history && history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('msg_success');
        url.searchParams.delete('msg_error');
        url.searchParams.delete('msg_info');
        history.replaceState(null, '', url.toString());
      }
    } catch (e) {
      console.error("No se pudo limpiar la URL:", e);
    }
  };

  // Ejecutar al cargar el DOM
  document.addEventListener('DOMContentLoaded', handleAlerts);

})();
</script>
