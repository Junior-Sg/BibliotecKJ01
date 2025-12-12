<?php

class Usuario
{
    private $conexion;

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // =========================
    // REGISTRAR USUARIO
    // =========================
    public function registrar($nombre, $correo, $clave, $telefono = null, $tipo_documento = null, $numero_documento = null)
    {
        $sql = "INSERT INTO usuario (nombre, correo, contraseña, telefono, tipo_documento, numero_documento) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar registrar: " . $this->conexion->error);
            return false;
        }

        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        $telefonoVal = $telefono ?? '';
        $tipoDocVal = $tipo_documento ?? '';
        $numDocVal = $numero_documento ?? '';

        $stmt->bind_param("ssssss", $nombre, $correo, $claveHash, $telefonoVal, $tipoDocVal, $numDocVal);

        try {
            if ($stmt->execute()) {
                return $this->conexion->insert_id;
            } else {
                error_log("Error ejecutar registrar: " . $stmt->error);
                return false;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 'duplicate_entry';
            } else {
                error_log("Error ejecutar registrar: " . $e->getMessage());
                return false;
            }
        }
    }

    // =========================
    // LOGIN
    // =========================
    public function login($correo, $clave)
    {
        $sql = "SELECT id_usuario, nombre, correo, contraseña FROM usuario WHERE correo = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar login: " . $this->conexion->error);
            return false;
        }

        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if (!$resultado || $resultado->num_rows === 0) {
            return false;
        }

        $data = $resultado->fetch_assoc();
        if (!$data || !isset($data["contraseña"])) return false;

        if (password_verify($clave, $data["contraseña"])) {
            return $data;
        }

        return false;
    }

    // =========================
    // OBTENER ROL
    // =========================
    public function obtenerRol($id_usuario)
    {
        $sql = "SELECT id_rol FROM rol_user WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar obtenerRol: " . $this->conexion->error);
            return 0;
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows === 0) return 0;
        $fila = $res->fetch_assoc();

        return intval($fila["id_rol"] ?? 0);
    }

    // =========================
    // ASIGNAR ROL
    // =========================
    public function asignarRol($id_usuario, $rol = 2)
    {
        $sql = "INSERT INTO rol_user (id_usuario, id_rol) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar asignarRol: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("ii", $id_usuario, $rol);
        return $stmt->execute();
    }

    // =========================
    // CREAR DESDE ADMIN
    // =========================
    public function crearDesdeAdmin($nombre, $correo, $clave, $telefono = null, $tipo_documento = null, $numero_documento = null)
    {
        // reusa lógica de registrar (podríamos llamar registrar directamente)
        return $this->registrar($nombre, $correo, $clave, $telefono, $tipo_documento, $numero_documento);
    }

    // =========================
    // LISTAR / BUSCAR USUARIOS
    // =========================
    public function getAllUsuarios()
    {
        $sql = "SELECT u.id_usuario, u.nombre, u.correo, u.telefono, u.tipo_documento, u.numero_documento, IFNULL(r.id_rol, 0) as rol
                FROM usuario u
                LEFT JOIN rol_user r ON u.id_usuario = r.id_usuario
                ORDER BY u.id_usuario DESC";
        $res = $this->conexion->query($sql);
        if ($res === false) {
            error_log("Error en getAllUsuarios: " . $this->conexion->error);
            return false;
        }
        return $res;
    }

    // Obtener usuario por id (compatibilidad con llamadas anteriores)
    public function getUsuarioById($id)
    {
        return $this->obtenerPorId((int)$id);
    }

    // Obtener usuario por número de documento
    public function getUsuarioByNumeroDocumento($numero_documento)
    {
        $sql = "SELECT id_usuario, nombre, correo, telefono, tipo_documento, numero_documento FROM usuario WHERE numero_documento = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar getUsuarioByNumeroDocumento: " . $this->conexion->error);
            return null;
        }
        $stmt->bind_param("s", $numero_documento);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }
        return null;
    }

    // =========================
    // ACTUALIZAR / ELIMINAR
    // =========================
    public function actualizarUsuario($id, $nombre, $correo, $telefono, $tipo_documento = null, $numero_documento = null)
    {
        $sql = "UPDATE usuario SET nombre = ?, correo = ?, telefono = ?, tipo_documento = ?, numero_documento = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar actualizarUsuario: " . $this->conexion->error);
            return false;
        }
        $tipoVal = $tipo_documento ?? '';
        $numVal = $numero_documento ?? '';
        $stmt->bind_param("sssssi", $nombre, $correo, $telefono, $tipoVal, $numVal, $id);
        return $stmt->execute();
    }

    public function eliminarUsuario($id)
    {
        // eliminar rol_user
        $stmt = $this->conexion->prepare("DELETE FROM rol_user WHERE id_usuario = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
        // eliminar usuario
        $stmt2 = $this->conexion->prepare("DELETE FROM usuario WHERE id_usuario = ?");
        if (!$stmt2) {
            error_log("Error preparar eliminarUsuario: " . $this->conexion->error);
            return false;
        }
        $stmt2->bind_param("i", $id);
        $res = $stmt2->execute();
        $stmt2->close();
        return $res;
    }

    // =========================
    // ACTUALIZAR ROL (reemplaza)
    // =========================
    public function actualizarRol($id_usuario, $rol)
    {
        // eliminar existentes
        $stmt = $this->conexion->prepare("DELETE FROM rol_user WHERE id_usuario = ?");
        if ($stmt) { $stmt->bind_param("i", $id_usuario); $stmt->execute(); $stmt->close(); }

        $stmt2 = $this->conexion->prepare("INSERT INTO rol_user (id_usuario, id_rol) VALUES (?, ?)");
        if (!$stmt2) {
            error_log("Error preparar actualizarRol: " . $this->conexion->error);
            return false;
        }
        $stmt2->bind_param("ii", $id_usuario, $rol);
        $res = $stmt2->execute();
        $stmt2->close();
        return $res;
    }

    public function contarTotalUsuarios()
    {
        $sql = "SELECT COUNT(id_usuario) as total FROM usuario";
        $resultado = $this->conexion->query($sql);
        if (!$resultado) return 0;
        $fila = $resultado->fetch_assoc();
        return $fila['total'] ?? 0;
    }

    // Alias para mantener consistencia con otros modelos
    public function obtenerPorId($id) {
        return $this->getUsuarioById($id);
    }

    // =========================
    // PERFIL DEL USUARIO
    // =========================

    // Obtener por id (incluye más campos del perfil)
    public function obtenerPorId(int $id)
    {
        $sql = "SELECT id_usuario, nombre, correo, telefono, avatar_emoji FROM usuario WHERE id_usuario = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar obtenerPorId: " . $this->conexion->error);
            return null;
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows === 0) return null;
        return $res->fetch_assoc();
    }
    
    //  método actualizarPerfil 
    public function actualizarPerfil(int $id, string $nombre, string $correo, string $telefono, string $avatar_emoji)
    {
        $sql = "UPDATE usuario SET nombre = ?, correo = ?, telefono = ?, avatar_emoji = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar actualizarPerfil: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("ssssi", $nombre, $correo, $telefono, $avatar_emoji, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function setAvatarEmoji(int $id, string $emoji)
    {
        $sql = "UPDATE usuario SET avatar_emoji = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar setAvatarEmoji: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("si", $emoji, $id);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    // =========================
    // FAVORITOS / RESERVAS
    // =========================

    // Favoritos: asume tabla favorito (id_usuario, id_libro)
    public function obtenerFavoritos(int $idUsuario)
    {
        $sql = "SELECT l.* FROM libro l INNER JOIN favorito f ON l.id_libro = f.id_libro WHERE f.id_usuario = ? ORDER BY f.created_at DESC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar obtenerFavoritos: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function agregarFavorito(int $idUsuario, int $idLibro)
    {
        // 1. Verificar si ya existe
        $sqlCheck = "SELECT id_favorito FROM favorito WHERE id_usuario = ? AND id_libro = ?";
        $stmtCheck = $this->conexion->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $idUsuario, $idLibro);
        $stmtCheck->execute();
        $resCheck = $stmtCheck->get_result();
        if ($resCheck->num_rows > 0) {
            return true; // Ya es favorito, no es un error
        }

        // 2. Insertar si no existe
        $sql = "INSERT INTO favorito (id_usuario, id_libro) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar agregarFavorito: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("ii", $idUsuario, $idLibro);
        return $stmt->execute();
    }

    // Historial reservas: asume tabla reserva (id_reserva, id_usuario, id_libro, fecha_reserva, estado)
    public function obtenerReservas(int $idUsuario)
    {
        $sql = "SELECT r.id_reserva, r.id_libro, r.fecha_reserva, r.estado, l.titulo, l.Imagen 
                FROM reserva r 
                LEFT JOIN libro l ON r.id_libro = l.id_libro
                WHERE r.id_usuario = ?
                ORDER BY r.fecha_reserva DESC";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar obtenerReservas: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function cancelarReserva(int $idReserva, int $idUsuario)
    {
        $sql = "UPDATE reserva SET estado = 'cancelada' WHERE id_reserva = ? AND id_usuario = ? AND estado = 'pendiente'";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar cancelarReserva: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("ii", $idReserva, $idUsuario);
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }
}
