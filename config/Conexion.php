<?php
// config/Conexion.php

class Conexion {
    
    private $host    = "localhost";
    private $usuario = "edredone_biblioteckj";   
    private $clave   = "R6xaJYtzRPt5R8X";  
    private $bd      = "edredone_bibliotec_kj";  
    public  $conexion;

    public function conectar() {
     
        mysqli_report(MYSQLI_REPORT_OFF); 

     
        $this->conexion = new mysqli($this->host, $this->usuario, $this->clave, $this->bd);

    
        if ($this->conexion->connect_errno) {
            error_log("[DB] Error de conexión ({$this->conexion->connect_errno}): " . $this->conexion->connect_error);
        
            die("Error de conexión a la base de datos.");
        }

       
        if (!$this->conexion->set_charset("utf8mb4")) {
            error_log("[DB] No se pudo establecer charset utf8mb4: " . $this->conexion->error);
        }

        return $this->conexion;
    }
}
