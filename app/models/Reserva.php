<?php

class Reserva
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function crearReserva($idUsuario, $idLibro)
    {
        $sql = "INSERT INTO reserva (id_usuario, id_libro, fecha_reserva, estado)
                VALUES ($idUsuario, $idLibro, CURDATE(), 'pendiente')";

        return $this->conexion->query($sql);
    }
}
