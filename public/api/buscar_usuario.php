<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../../app/models/Usuario.php';

$numero = trim($_GET['numero_documento'] ?? '');
if ($numero === '') {
    echo json_encode(['ok' => false, 'error' => 'Falta numero_documento']);
    exit;
}

$db = (new Conexion())->conectar();
$usuarioModel = new Usuario($db);
$u = $usuarioModel->getUsuarioByNumeroDocumento($numero);
if ($u) {
    echo json_encode(['ok' => true, 'usuario' => $u]);
} else {
    echo json_encode(['ok' => false, 'error' => 'No encontrado']);
}

?>