<?php

class Usuario {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // =========================
    // REGISTRAR USUARIO
    // =========================
    public function registrar($nombre, $correo, $clave, $telefono = null, $tipo_documento = null, $numero_documento = null) {

        $sql = "INSERT INTO usuario (nombre, correo, contraseña, telefono, tipo_documento, numero_documento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            error_log("Error preparar registrar: " . $this->conexion->error);
            return false;
        }

        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        // Asegurarse de enviar cadenas (null -> empty string)
        $telefonoVal = $telefono ?? '';
        $tipoDocVal = $tipo_documento ?? '';
        $numDocVal = $numero_documento ?? '';

        $stmt->bind_param("ssssss", $nombre, $correo, $claveHash, $telefonoVal, $tipoDocVal, $numDocVal);

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

    // Crear usuario desde el panel admin (inserta nombre, correo, contraseña, teléfono, tipo y número de documento)
    public function crearDesdeAdmin($nombre, $correo, $clave, $telefono = null, $tipo_documento = null, $numero_documento = null) {
        $sql = "INSERT INTO usuario (nombre, correo, contraseña, telefono, tipo_documento, numero_documento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar crearDesdeAdmin: " . $this->conexion->error);
            return false;
        }

        $claveHash = password_hash($clave, PASSWORD_BCRYPT);
        $telefonoVal = $telefono ?? '';
        $tipoVal = $tipo_documento ?? '';
        $numVal = $numero_documento ?? '';

        $stmt->bind_param("ssssss", $nombre, $correo, $claveHash, $telefonoVal, $tipoVal, $numVal);
        if (!$stmt->execute()) {
            error_log("Error ejecutar crearDesdeAdmin: " . $stmt->error);
            return false;
        }

        return $this->conexion->insert_id;
    }

    // Obtener todos los usuarios con su rol (si existe)
    public function getAllUsuarios() {
        $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.telefono, u.tipo_documento, u.numero_documento, IFNULL(r.id_rol, 0) as rol
                FROM usuario u
                LEFT JOIN rol_user r ON u.id_usuario = r.id_usuario
                ORDER BY u.id_usuario DESC";
        $res = $this->conexion->query($sql);
        return $res;
    }

    // Obtener usuario por id
    public function getUsuarioById($id) {
        $sql = "SELECT id_usuario, nombre, correo, telefono FROM usuario WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    // Obtener usuario por numero de documento
    public function getUsuarioByNumeroDocumento($numero_documento) {
        $sql = "SELECT id_usuario, nombre, correo, telefono, tipo_documento, numero_documento FROM usuario WHERE numero_documento = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return null;
        $stmt->bind_param("s", $numero_documento);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }
        return null;
    }

    // Actualizar usuario (no actualiza contraseña)
    public function actualizarUsuario($id, $nombre, $correo, $telefono, $tipo_documento = null, $numero_documento = null) {
        $sql = "UPDATE usuario SET nombre = ?, correo = ?, telefono = ?, tipo_documento = ?, numero_documento = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $tipoVal = $tipo_documento ?? '';
        $numVal = $numero_documento ?? '';
        $stmt->bind_param("sssssi", $nombre, $correo, $telefono, $tipoVal, $numVal, $id);
        return $stmt->execute();
    }

    // Eliminar usuario y su rol
    public function eliminarUsuario($id) {
        // eliminar rol_user
        $stmt = $this->conexion->prepare("DELETE FROM rol_user WHERE id_usuario = ?");
        if ($stmt) { $stmt->bind_param("i", $id); $stmt->execute(); }
        // eliminar usuario
        $stmt2 = $this->conexion->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        if (!$stmt2) return false;
        $stmt2->bind_param("i", $id);
        return $stmt2->execute();
    }

    // Actualizar rol (insertar o actualizar simple: eliminar e insertar)
    public function actualizarRol($id_usuario, $rol) {
        // eliminar existentes
        $stmt = $this->conexion->prepare("DELETE FROM rol_user WHERE id_usuario = ?");
        if ($stmt) { $stmt->bind_param("i", $id_usuario); $stmt->execute(); }
        $stmt2 = $this->conexion->prepare("INSERT INTO rol_user (id_usuario, id_rol) VALUES (?, ?)");
        if (!$stmt2) return false;
        $stmt2->bind_param("ii", $id_usuario, $rol);
        return $stmt2->execute();
    }

    public function contarTotalUsuarios() {
        $sql = "SELECT COUNT(id_usuario) as total FROM usuario";
        $resultado = $this->conexion->query($sql);
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    // Alias para mantener consistencia con otros modelos
    public function obtenerPorId($id) {
        return $this->getUsuarioById($id);
    }

}

?>
