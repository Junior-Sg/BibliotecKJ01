<?php
// app/helpers/alert.php

$alert = '';
$error = $error ?? $_GET['error'] ?? null;
$msg = $msg ?? $_GET['msg'] ?? null;

function alert_icon_svg($type) {
    $icons = [
        'danger' => '<svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1.333a6.667 6.667 0 100 13.334A6.667 6.667 0 008 1.333zm0 9.334a.8.8 0 110 1.6.8.8 0 010-1.6zM7.2 3.6h1.6v5.333H7.2V3.6z" fill="#3b2b21"/></svg>',
        'info'   => '<svg width="20" height="20" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8 1.333a6.667 6.667 0 100 13.334A6.667 6.667 0 008 1.333zm0 9.334a.667.667 0 110 1.334A.667.667 0 018 10.667zM7.333 5.333h1.334v2.667H7.333V5.333z" fill="#3b2b21"/></svg>',
    ];
    return $icons[$type] ?? '';
}

if (!empty($error)) {
    $text = htmlspecialchars($error);
    $icon = alert_icon_svg('danger');
    $alert = "<div class='alert alert-danger alert-custom' role='alert'><span class='alert-icon'>$icon</span><div><strong>Error:</strong> $text</div></div>";
} elseif (!empty($msg)) {
    $text = htmlspecialchars($msg);
    $icon = alert_icon_svg('info');
    $alert = "<div class='alert alert-info alert-custom' role='alert'><span class='alert-icon'>$icon</span><div>$text</div></div>";
}

echo $alert;
?>
<script>
// Script para auto-cerrar alertas y limpiar la URL
(function(){
  const alerts = document.querySelectorAll('.alert-custom');
  if (!alerts.length) return;
  
  alerts.forEach(alert => {
    setTimeout(() => {
      alert.style.transition = 'opacity 0.5s ease';
      alert.style.opacity = '0';
      setTimeout(() => { try { alert.remove(); } catch(e){} }, 500);
    }, 5000); // 5 segundos
  });

  // Limpiar la URL de parámetros 'error' y 'msg' para que no reaparezcan al recargar
  try {
    if (window.history && history.replaceState) {
        const url = new URL(window.location);
        url.searchParams.delete('error');
        url.searchParams.delete('msg');
        history.replaceState(null, '', url.toString());
    }
  } catch (e) {}
})();
</script>