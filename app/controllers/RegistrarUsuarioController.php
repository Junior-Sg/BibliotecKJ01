<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/../../config/Conexion.php';
require_once __DIR__ . '/../models/Usuario.php';

class RegistrarUsuarioController {

    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Conexion())->conectar();
        $this->model = new Usuario($this->db);
    }

    /**
     * Procesa la petición de registro de un nuevo usuario.
     */
    public function registrar() {
        $nombre = trim($_POST["nombre"] ?? "");
        $correo = trim($_POST["correo"] ?? "");
        $clave  = trim($_POST["clave"] ?? "");
        $telefono = trim($_POST["telefono"] ?? "");
        $tipo_documento = trim($_POST["tipo_documento"] ?? "");
        $numero_documento = trim($_POST["numero_documento"] ?? "");

        $redirect_url = "/BibliotecKJ01/index.php?c=LoginUsuario&a=index";

        if (empty($nombre) || empty($correo) || empty($clave) || empty($tipo_documento) || empty($numero_documento)) {
            header("Location: " . $redirect_url . "&error=Datos incompletos");
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            header("Location: " . $redirect_url . "&error=Correo inválido");
            exit;
        }
        
        if (!preg_match('/^[\p{L}\s]+$/u', $nombre)) {
            header("Location: " . $redirect_url . "&error=El nombre solo puede contener letras y espacios");
            exit;
        }

        $allowedTipos = ['CC','TI','CE'];
        if (!in_array($tipo_documento, $allowedTipos)) {
            header("Location: " . $redirect_url . "&error=Tipo de documento inválido");
            exit;
        }

        if (!ctype_digit($numero_documento)) {
            header("Location: " . $redirect_url . "&error=Número de documento inválido");
            exit;
        }

        if (!empty($telefono) && !preg_match('/^[0-9+\-\s]{7,20}$/', $telefono)) {
            header("Location: " . $redirect_url . "&error=Teléfono inválido");
            exit;
        }

        $id_usuario = $this->model->registrar($nombre, $correo, $clave, $telefono, $tipo_documento, $numero_documento);

        if (!$id_usuario) {
            $dbError = $this->db->error ?? '';
            $msg = 'No se pudo registrar';
            if (!empty($dbError) && defined('IS_DEVELOPMENT') && IS_DEVELOPMENT) { // Ocultar errores detallados en producción
                $msg .= ': ' . $dbError;
            }
            header("Location: " . $redirect_url . "&error=" . urlencode($msg));
            exit;
        }

        // Asignar rol de "Cliente" por defecto
        $this->model->asignarRol($id_usuario, 2); 

        header("Location: " . $redirect_url . "&msg=Registro exitoso");
        exit;
    }
}