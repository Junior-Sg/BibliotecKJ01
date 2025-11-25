<?php

class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtenerPorId($idUsuario)
    {
        $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.telefono, 
                       u.tipo_documento, u.numero_documento, r.nombre AS rol
                FROM usuario u
                INNER JOIN rol_user ru ON u.id_usuario = ru.id_usuario
                INNER JOIN rol r ON ru.id_rol = r.id_rol
                WHERE u.id_usuario = $idUsuario";

        return $this->conexion->query($sql)->fetch_assoc();
    }

    public function actualizarUsuario($idUsuario, $nombre, $correo, $telefono, $tipoDocumento, $numeroDocumento)
    {
        $sql = "UPDATE usuario 
                SET nombre = '$nombre', 
                    correo = '$correo',
                    telefono = '$telefono',
                    tipo_documento = '$tipoDocumento',
                    numero_documento = '$numeroDocumento'
                WHERE id_usuario = $idUsuario";

        return $this->conexion->query($sql);
    }
}

?>
