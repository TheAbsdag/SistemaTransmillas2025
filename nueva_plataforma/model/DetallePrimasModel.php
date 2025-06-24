<?php
class DetallePrimasModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function obtenerDetallePrimas($params) {
        $condiciones = " WHERE hoj_estado = 'Activo' AND hoj_tipocontrato = 'Empresa'";

        if (!empty($params['sede'])) {
            $condiciones .= " AND hoj_sede = " . intval($params['sede']);
        }

        if (!empty($params['cedula'])) {
            $cedula = $this->conn->real_escape_string($params['cedula']);
            $condiciones .= " AND hoj_cedula = '$cedula'";
        }

        $sql = "SELECT idhojadevida, hoj_nombre, hoj_apellido, hoj_cargo, hoj_tipocontrato, hoj_cedula, hoj_fechaingreso,
                    hoj_salario, hoj_auxilio, hoj_dias_laborados, hoj_descanso, hoj_no_trabajados,
                    hoj_incapacidad_empresa, hoj_vacaciones, hoj_licencias, hoj_dias_prima, hoj_total_prima,
                    hoj_confirmado, hoj_pagado, hoj_fechatermino
                FROM hojadevida
                $condiciones
                ORDER BY hoj_nombre ASC";

        $result = $this->conn->query($sql);
        $datos = [];

        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }

        return $datos;
    }
}
?>
