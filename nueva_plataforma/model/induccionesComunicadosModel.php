<?php
require_once "../config/database.php";

class InduccionesComunicados {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    // Obtener registros
    public function obtenerComunicados($filtroEstado = '') {
        $sql = "SELECT * FROM comunicados_inducciones WHERE 1";

        if ($filtroEstado !== '') {
            $sql .= " AND ci_estado = '" . $this->db->real_escape_string($filtroEstado) . "'";
        }

        $sql .= " ORDER BY ci_fecha_registro DESC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // nuevo registro
    public function insertarComunicado($data) {
        $sql = "INSERT INTO comunicados_inducciones (
                    ci_nombre_documento,
                    ci_encargado,
                    ci_usuario,
                    ci_link_documento,
                    ci_ruta_archivo,
                    ci_estado,
                    ci_fecha_confirmacion_usuario,
                    ci_fecha_confirmacion_encargado
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssssssss",
            $data['nombre_documento'],
            $data['encargado'],
            $data['usuario'],
            $data['link_documento'],
            $data['ruta_archivo'],
            $data['estado'],
            $data['fecha_confirmacion_usuario'],
            $data['fecha_confirmacion_encargado']
        );
        return $stmt->execute();
    }

    // Actualizar estado
    public function actualizarCampo($id, $campo, $valor) {
        $permitidos = ['ci_estado', 'ci_fecha_confirmacion_usuario', 'ci_fecha_confirmacion_encargado'];
        if (!in_array($campo, $permitidos)) return false;

        $sql = "UPDATE comunicados_inducciones SET $campo = ? WHERE ci_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $valor, $id);
        return $stmt->execute();
    }

    // Eliminar
    public function eliminarComunicado($id) {
        $sql = "DELETE FROM comunicados_inducciones WHERE ci_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
