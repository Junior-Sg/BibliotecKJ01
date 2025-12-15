<?php
// Test para registrar una devolución
$_POST['id_prestamo'] = 1; // Un ID de préstamo válido para la prueba

require_once __DIR__ . '/../app/controllers/PrestamoController.php';

$controller = new PrestamoController();
$controller->registrarDevolucion();
