<?php
case 'Prestamo':
    $controlador = new PrestamoControlador();
    if ($a == "vistaCrearPrestamo") {
        $controlador->vistaCrearPrestamo();
    } elseif ($a == "registrarPrestamo") {
        $controlador->registrarPrestamo();
    }
break;
