
<?php
require_once __DIR__ . '/../../config/Conexion.php';

class LibroModelo {

	private $db;

	public function __construct() {
		$this->db = (new Conexion())->conectar();
	}

	// Obtener libros disponibles (cantidad_total > 0 o NULL significa disponible)
	public function obtenerLibrosDisponibles() {
		$sql = "SELECT l.id_libro, l.titulo, l.isbn, l.año_publicacion, l.cantidad_total, e.nombre AS editorial
				FROM libro l
				LEFT JOIN editorial e ON l.id_editorial = e.id_editorial
				WHERE l.cantidad_total IS NULL OR l.cantidad_total > 0
				ORDER BY l.titulo ASC";

		$res = $this->db->query($sql);

		$libros = [];
		if ($res) {
			while ($fila = $res->fetch_assoc()) {
				$libros[] = $fila;
			}
		}

		return $libros;
	}

}

?>

