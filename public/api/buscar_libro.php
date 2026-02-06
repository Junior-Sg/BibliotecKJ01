<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../../app/models/Libro.php';

$titulo = trim($_GET['titulo'] ?? '');
if (strlen($titulo) < 2) {
    echo json_encode(['success' => false, 'message' => 'El término de búsqueda debe tener al menos 2 caracteres.']);
    exit;
}

$db = (new Conexion())->conectar();
$libroModel = new Libro($db);

// Usar una versión segura de buscarGeneral
$sql = "SELECT DISTINCT l.id_libro, l.titulo, l.Imagen, e.nombre as editorial
        FROM libro l
        LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
        LEFT JOIN libro_autor la ON la.id_libro = l.id_libro
        LEFT JOIN autor a ON a.id_autor = la.id_autor
        WHERE l.titulo LIKE ? OR a.nombre LIKE ?";

$stmt = $db->prepare($sql);
$searchTerm = "%{$titulo}%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$resultado = $stmt->get_result();
$libros = $resultado->fetch_all(MYSQLI_ASSOC);

if ($libros) {
    echo json_encode(['success' => true, 'data' => $libros]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontraron libros.']);
}

?>
