<?php
// Script temporal para mostrar la estructura de la tabla `favorito`.
// Úsalo desde el navegador: http://localhost/BibliotecKJ01/public/tools/show_favorito_table.php
// Elimina este archivo cuando termines.

require_once __DIR__ . '/../../config/Conexion.php';

header('Content-Type: text/plain; charset=utf-8');
 
try {
    $db = (new Conexion())->conectar();
} catch (Exception $e) {
    echo "Error conectando a la base de datos: " . $e->getMessage();
    exit;
}

$table = 'favorito';
$res = $db->query("SHOW CREATE TABLE `" . $db->real_escape_string($table) . "`");
if (!$res) {
    echo "Error al ejecutar SHOW CREATE TABLE: " . $db->error . "\n";
    exit;
}

$row = $res->fetch_assoc();
if (isset($row['Create Table'])) {
    echo $row['Create Table'];
} else {
    // Algunos motores devuelven la columna con otro nombre
    $vals = array_values($row);
    echo implode("\n", $vals);
}

echo "\n\n-- Si la tabla no existe, verás un error arriba.\n";

// NOTA: borra este archivo tras su uso por seguridad.

?>
