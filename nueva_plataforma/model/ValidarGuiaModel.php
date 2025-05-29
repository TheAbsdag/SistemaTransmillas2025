<?php
require_once "../config/database.php";

class ValidarGuiaModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function obtenerGuiasXValidar($fecha, $sedeDest, $sedeOrigen, $param1, $param2) {
        $fechaCond = "AND ser_fechaguia LIKE '$fecha%'";
        $destCond = $sedeDest ? "AND ser_ciudadentrega IN (SELECT idciudad FROM ciudades WHERE idsedes = '$sedeDest')" : '';
        $origenCond = $sedeOrigen ? "AND cli_idciudad IN (SELECT idciudad FROM ciudades WHERE idsedes = '$sedeOrigen')" : '';
        $filtroCond = ($param1 && $param2) ? "AND $param1 LIKE '%$param2%'" : '';

        $sql = "SELECT idservicios, ser_consecutivo, ser_tipopaquete, ser_piezas, numeropieza, ser_fechaguia, ser_paquetedescripcion
                FROM serviciosdia 
                INNER JOIN piezasguia ON ser_consecutivo = numeroguia
                WHERE ser_estado IN ('6','7') AND guiallega = 0 
                $fechaCond $destCond $origenCond $filtroCond
                ORDER BY ser_fechaguia DESC";
                
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}