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
        $sql = "SELECT id_usuario, nombre, correo, contraseña, avatar_emoji FROM usuario WHERE correo = ? LIMIT 1";
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

    public function actualizarClave(int $id, string $clave)
    {
        $claveHash = password_hash($clave, PASSWORD_BCRYPT);
        $sql = "UPDATE usuario SET contraseña = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar actualizarClave: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("si", $claveHash, $id);
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
    public function actualizarPerfil(int $id, string $nombre, string $correo, string $telefono)
    {
        $sql = "UPDATE usuario SET nombre = ?, correo = ?, telefono = ? WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar actualizarPerfil: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("sssi", $nombre, $correo, $telefono, $id);
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
        // Ordenar por el id del favorito (más recientes primero). Usamos id_favorito
        // en vez de created_at porque la columna created_at puede no existir
        // en algunas instalaciones y provocaría un error en la consulta.
        $sql = "SELECT l.* FROM libro l INNER JOIN favorito f ON l.id_libro = f.id_libro WHERE f.id_usuario = ? ORDER BY f.id_favorito DESC";
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
        // Usar una inserción tolerante a duplicados para evitar condiciones de carrera.
        // ON DUPLICATE KEY UPDATE no cambia nada pero evita error por UNIQUE.
        $sql = "INSERT INTO favorito (id_usuario, id_libro) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_favorito = id_favorito";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar agregarFavorito: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("ii", $idUsuario, $idLibro);
        $exec = $stmt->execute();
        if ($exec) {
            return true;
        }

        // Si hubo error, registrar y devolver false
        error_log("Error ejecutar agregarFavorito: " . $stmt->error);
        return false;
    }

    /**
     * Devuelve un array simple con los ids de libro que el usuario marcó como favoritos.
     * @param int $idUsuario
     * @return int[]
     */
    public function obtenerFavoritosIds(int $idUsuario)
    {
        $sql = "SELECT id_libro FROM favorito WHERE id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error preparar obtenerFavoritosIds: " . $this->conexion->error);
            return [];
        }
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $ids = [];
        while ($row = $res->fetch_assoc()) {
            $ids[] = (int)$row['id_libro'];
        }
        return $ids;
    }
    
    public function eliminarFavorito($id_usuario, $id_libro) {
        $sql = "DELETE FROM favorito WHERE id_usuario = ? AND id_libro = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_usuario, $id_libro);
        return $stmt->execute();
        
        }

    // Historial reservas: asume tabla reserva (id_reserva, id_usuario, id_libro, fecha_reserva, estado)
    /**
     * Reservas activas del usuario (solo pendientes).
     * Si el administrador convierte en préstamo o la cancela, ya no aparecen aquí.
     */
    public function obtenerReservas(int $idUsuario)
    {
        $sql = "SELECT r.id_reserva, r.id_libro, r.fecha_reserva, r.estado, l.titulo, l.Imagen 
                FROM reserva r 
                LEFT JOIN libro l ON r.id_libro = l.id_libro
                WHERE r.id_usuario = ? AND r.estado = 'pendiente'
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
        $this->conexion->begin_transaction();

        try {
            // 1. Obtener datos de la reserva (título del libro) para la notificación
            $sqlSel = "SELECT r.id_libro, l.titulo
                       FROM reserva r
                       JOIN libro l ON r.id_libro = l.id_libro
                       WHERE r.id_reserva = ? AND r.id_usuario = ?";
            
            $stmtSel = $this->conexion->prepare($sqlSel);
            $stmtSel->bind_param("ii", $idReserva, $idUsuario);
            $stmtSel->execute();
            $res = $stmtSel->get_result();

            if ($res->num_rows === 0) {
                throw new Exception("Reserva no encontrada");
            }

            $data = $res->fetch_assoc();
            $tituloLibro = $data['titulo'];

            // 2. Eliminar la reserva (ya no debe aparecer en el historial)
            $sqlDel = "DELETE FROM reserva WHERE id_reserva = ? AND id_usuario = ? AND estado = 'pendiente'";
            $stmtDel = $this->conexion->prepare($sqlDel);
            $stmtDel->bind_param("ii", $idReserva, $idUsuario);
            $stmtDel->execute();

            // 3. Crear notificación
            require_once __DIR__ . '/NotificacionModelo.php';
            $notificacionModelo = new NotificacionModelo($this->conexion);
            $mensaje = "❌ Tu reserva del libro «{$tituloLibro}» ha sido cancelada correctamente.";
            $notificacionModelo->crearNotificacion($idUsuario, $mensaje);

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            error_log("Error al cancelar reserva: " . $e->getMessage());
            return false;
        }
    }

    // =================================
    // RECUPERACIÓN DE CONTRASEÑA
    // =================================

    /**
     * Busca un usuario por su dirección de correo electrónico.
     *
     * @param string $correo El correo electrónico del usuario.
     * @return array|null Los datos del usuario si se encuentra, o null si no.
     */
    public function findByEmail($correo) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE correo = ?");
        if (!$stmt) {
            error_log("Error al preparar la consulta para buscar por email: " . $this->conexion->error);
            return null;
        }
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }

    /**
     * Crea un token (código) de restablecimiento de contraseña para un usuario.
     * Asume una tabla `password_reset_tokens` con (id_usuario, token, expires_at).
     *
     * @param int $id_usuario ID del usuario.
     * @param string $token El código o token generado.
     * @return bool True si se creó, false en caso de error.
     */
    public function createPasswordResetToken(int $id_usuario, string $token) {
        // Eliminar tokens antiguos para este usuario para evitar duplicados
        $this->conexion->query("DELETE FROM password_reset_tokens WHERE id_usuario = $id_usuario");

        // El token expira en 15 minutos
        $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        
        $sql = "INSERT INTO password_reset_tokens (id_usuario, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar createPasswordResetToken: " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("iss", $id_usuario, $token, $expires_at);
        return $stmt->execute();
    }

    /**
     * Busca un token de restablecimiento y devuelve los datos asociados.
     *
     * @param string $token El token a buscar.
     * @return array|null Los datos del token si es válido, o null.
     */
    public function findByResetToken(string $token) {
        $sql = "SELECT * FROM password_reset_tokens WHERE token = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            error_log("Error al preparar findByResetToken: " . $this->conexion->error);
            return null;
        }
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Elimina un token de restablecimiento de contraseña después de su uso.
     *
     * @param string $token El token a eliminar.
     * @return bool True si se eliminó, false en caso de error.
     */
    public function deletePasswordResetToken(string $token) {
        $sql = "DELETE FROM password_reset_tokens WHERE token = ?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }


        // =================================
    // NOTIFICACIONES
    // =================================

    // Obtener todas las notificaciones de un usuario

    public function obtenerNotificaciones($id_usuario) {
        $sql = "SELECT * FROM notificaciones WHERE id_usuario = ? ORDER BY fecha_creacion DESC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result();
        
        }
        
    // Contar notificaciones sin leer para el badge de la campana
        
    public function contarNotificacionesSinLeer($id_usuario) {
        $sql = "SELECT COUNT(*) as total FROM notificaciones WHERE id_usuario = ? AND leido = 0";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res['total'] ?? 0;
        }
            
    // Marcar como leída
    public function marcarNotificacionLeida($id_notificacion, $id_usuario) {
        $sql = "UPDATE notificaciones SET leido = 1 WHERE id_notificacion = ? AND id_usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_notificacion, $id_usuario);
        return $stmt->execute();
        
        }
}

?>
