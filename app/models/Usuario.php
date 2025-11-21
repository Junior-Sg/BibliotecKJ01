<?php
class Usuario {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function buscarPorCorreo($correo) {
        $sql = "SELECT * FROM usuario WHERE correo = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function actualizarContraseña($id_usuario, $nueva_contraseña) {
        $sql = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nueva_contraseña, $id_usuario);
        $stmt->execute();
    }
}
