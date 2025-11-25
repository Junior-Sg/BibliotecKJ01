<?php

class LibroModelo {

    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }

    // Obtener libros disponibles (estado = 'Disponible')
    public function obtenerLibrosDisponibles() {
        $sql = "SELECT * FROM Libro WHERE estado = 'Disponible'";
        $consulta = $this->db->prepare($sql);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
