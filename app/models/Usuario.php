<?php

class Usuario {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // =========================
    // REGISTRAR USUARIO
    // =========================
    public function registrar($nombre, $correo, $clave) {

        $sql = "INSERT INTO usuario (nombre, correo, contraseña) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            error_log("Error preparar registrar: " . $this->conexion->error);
            return false;
        }

        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        $stmt->bind_param("sss", $nombre, $correo, $claveHash);

        if (!$stmt->execute()) {
            error_log("Error ejecutar registrar: " . $stmt->error);
            return false;
        }

        return $this->conexion->insert_id;
    }

    // =========================
    // LOGIN
    // =========================
    public function login($correo, $clave) {

        $sql = "SELECT id_usuario, nombre, correo, contraseña 
                FROM usuario WHERE correo = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) return false;

        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 0) return false;

        $data = $resultado->fetch_assoc();

        if (password_verify($clave, $data["contraseña"])) {
            return $data;
        }

        return false;
    }

    // =========================
    // OBTENER ROL
    // =========================
    public function obtenerRol($id_usuario) {

        $sql = "SELECT id_rol FROM rol_user WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) return 0;

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();

        $res = $stmt->get_result();

        if ($res->num_rows === 0) return 0;

        $fila = $res->fetch_assoc();

        return intval($fila["id_rol"]);
    }

    // =========================
    // ASIGNAR ROL AUTOMÁTICO
    // =========================
    public function asignarRol($id_usuario, $rol = 2) {

        $sql = "INSERT INTO rol_user (id_usuario, id_rol) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) return false;

        $stmt->bind_param("ii", $id_usuario, $rol);

        return $stmt->execute();
    }
}
