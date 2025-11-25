<?php
class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $clave = "";       
    private $bd = "bibliotec_kj";
    public $conexion;

    public function conectar() {
        // crear conexión mysqli orientada a objetos
        $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->bd);

        // comprobar errores
        if ($this->conexion->connect_errno) {
            //  compruba la conexion con la bd y muestra el error en el log
            error_log("Error MySQL conexión: " . $this->conexion->connect_error);
            die("Error de conexión a la base de datos.");
        }

        // forzar charset utf8mb4
        $this->conexion->set_charset("utf8mb4");

        return $this->conexion;
    }
}
