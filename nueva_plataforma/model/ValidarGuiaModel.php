<?php
require_once "../config/database.php";

class ValidarGuiaModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerGuiasXValidar($fecha, $sedeDestino, $sedeOrigen, $campo, $valor) {
        $fechaCond = "AND ser_fechaguia LIKE '" . $this->db->real_escape_string($fecha) . "%'";
        $destCond = $sedeDestino ? "AND ser_ciudadentrega IN (SELECT idciudad FROM ciudades WHERE idsedes = '" . $this->db->real_escape_string($sedeDestino) . "')" : '';
        $origenCond = $sedeOrigen ? "AND cli_idciudad IN (SELECT idciudad FROM ciudades WHERE idsedes = '" . $this->db->real_escape_string($sedeOrigen) . "')" : '';
        $filtroCond = ($campo && $valor) ? "AND $campo LIKE '%" . $this->db->real_escape_string($valor) . "%'" : '';

        $sql = "SELECT idservicios, ser_consecutivo, ser_tipopaquete, ser_paquetedescripcion, ser_piezas, ser_fechaguia
                FROM serviciosdia 
                INNER JOIN piezasguia ON ser_consecutivo = numeroguia
                WHERE ser_estado IN ('6','7') AND guiallega = 0 
                $fechaCond $destCond $origenCond $filtroCond
                ORDER BY ser_fechaguia DESC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}