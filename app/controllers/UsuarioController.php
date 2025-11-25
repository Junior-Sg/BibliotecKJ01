<?php

require_once "./app/models/Usuario.php";
require_once "./core/database/conexion.php";

class UsuarioController
{
    private $modeloUsuario;

    public function __construct()
    {
        global $conexion;
        $this->modeloUsuario = new Usuario($conexion);
    }

    public function perfil()
    {
        if (!isset($_SESSION["id_usuario"])) {
            header("Location: index.php?controller=Auth&action=login");
            exit();
        }

        $idUsuario = $_SESSION["id_usuario"];
        $usuario = $this->modeloUsuario->obtenerPorId($idUsuario);

        require "./app/views/usuario/perfil.php";
    }

    public function editar()
    {
        if (!isset($_SESSION["id_usuario"])) {
            header("Location: index.php?controller=Auth&action=login");
            exit();
        }

        $idUsuario = $_SESSION["id_usuario"];
        $usuario = $this->modeloUsuario->obtenerPorId($idUsuario);

        require "./app/views/usuario/editar.php";
    }

    public function actualizar()
    {
        if (!isset($_SESSION["id_usuario"])) {
            header("Location: index.php?controller=Auth&action=login");
            exit();
        }

        $idUsuario = $_SESSION["id_usuario"];

        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];
        $tipoDocumento = $_POST["tipo_documento"];
        $numeroDocumento = $_POST["numero_documento"];

        $this->modeloUsuario->actualizarUsuario(
            $idUsuario,
            $nombre,
            $correo,
            $telefono,
            $tipoDocumento,
            $numeroDocumento
        );

        header("Location: index.php?controller=Usuario&action=perfil");
    }
}

?>
