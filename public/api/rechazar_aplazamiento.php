<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../../app/models/PrestamoModelo.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar que sea admin
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado. Solo administradores pueden rechazar solicitudes.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $db = (new Conexion())->conectar();
    $prestamoModelo = new PrestamoModelo($db);
    
    // Recoger datos
    $idSolicitud = filter_input(INPUT_POST, 'id_solicitud', FILTER_VALIDATE_INT);
    
    // Validar datos
    if (!$idSolicitud) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID de solicitud inválido']);
        exit;
    }
    
    // Ejecutar el rechazo (sin nota_admin)
    if ($prestamoModelo->rechazarAplazamiento($idSolicitud)) {
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud de aplazamiento rechazada'
        ]);
    } else {
        $detalle = '';
        if (method_exists($prestamoModelo, 'getLastError')) {
            $detalle = $prestamoModelo->getLastError();
        }
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error al rechazar la solicitud',
            'error' => $detalle
        ]);
    }
    
} catch (Exception $e) {
    error_log("Error en rechazar_aplazamiento.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al procesar la solicitud'
    ]);
}
?>
