<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../../app/models/PrestamoModelo.php';
require_once __DIR__ . '/../../app/models/NotificacionModelo.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$idUsuario = (int)$_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

try {
    $db = (new Conexion())->conectar();
    $prestamoModelo = new PrestamoModelo($db);
    $notificacionModelo = new NotificacionModelo($db);
    
    // Recoger datos
    $idPrestamo = filter_input(INPUT_POST, 'id_prestamo', FILTER_VALIDATE_INT);
    $diasSolicitados = filter_input(INPUT_POST, 'dias_solicitados', FILTER_VALIDATE_INT);
    $motivo = filter_input(INPUT_POST, 'motivo', FILTER_SANITIZE_STRING) ?? null;
    
    // Validar datos
    if (!$idPrestamo || !$diasSolicitados || $diasSolicitados < 1 || $diasSolicitados > 30) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }
    
    // Verificar que el préstamo pertenece al usuario
    $sql = "SELECT id_usuario, estado FROM prestamo WHERE id_prestamo = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $idPrestamo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Préstamo no encontrado']);
        exit;
    }
    
    $prestamo = $resultado->fetch_assoc();
    
    if ((int)$prestamo['id_usuario'] !== $idUsuario) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No tienes permiso para esta acción']);
        exit;
    }
    
    if (!in_array($prestamo['estado'], ['activo', 'retrasado'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No se puede solicitar aplazamiento para este préstamo']);
        exit;
    }
    
    // Verificar si ya existe una solicitud pendiente
    if ($prestamoModelo->tieneSolicitudPendiente($idPrestamo)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Ya existe una solicitud pendiente para este préstamo']);
        exit;
    }
    
    // Registrar la solicitud
    $prestamoModelo->registrarSolicitudAplazamiento($idPrestamo, $idUsuario, $diasSolicitados, $motivo);
    
    // Obtener datos para la notificación del admin
    $sqlInfo = "SELECT u.nombre as nombre_usuario, l.titulo as titulo_libro, p.fecha_devolucion 
                FROM prestamo p
                JOIN usuario u ON p.id_usuario = u.id_usuario
                JOIN libro l ON p.id_libro = l.id_libro
                WHERE p.id_prestamo = ?";
    $stmtInfo = $db->prepare($sqlInfo);
    $stmtInfo->bind_param("i", $idPrestamo);
    $stmtInfo->execute();
    $info = $stmtInfo->get_result()->fetch_assoc();
    
    // Crear notificación para TODOS LOS ADMINS
    $sqlAdmins = "SELECT u.id_usuario FROM usuario u 
                  JOIN rol_user r ON u.id_usuario = r.id_usuario 
                  WHERE r.id_rol = 1";
    $resultAdmins = $db->query($sqlAdmins);
    
    $mensaje = "📋 Nueva solicitud de aplazamiento:\n"
             . "Usuario: {$info['nombre_usuario']}\n"
             . "Libro: {$info['titulo_libro']}\n"
             . "Días solicitados: {$diasSolicitados}\n"
             . "Fecha devolución actual: {$info['fecha_devolucion']}";
    
    if ($motivo) {
        $mensaje .= "\nMotivo: {$motivo}";
    }
    
    if ($resultAdmins && $resultAdmins->num_rows > 0) {
        while ($admin = $resultAdmins->fetch_assoc()) {
            $notificacionModelo->crearNotificacion($admin['id_usuario'], $mensaje);
        }
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Solicitud enviada correctamente. El administrador analizará tu solicitud pronto.'
    ]);
    
} catch (Exception $e) {
    error_log("Error en solicitar_aplazamiento.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al procesar la solicitud'
    ]);
}
?>
