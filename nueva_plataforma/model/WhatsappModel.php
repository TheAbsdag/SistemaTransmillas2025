<?php
require_once "../config/database.php";

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerMensajes($fecha_hora = '', $filtroEstado = '') {

        // Si no se pasa una fecha, usa la fecha actual
        if (empty($fecha_hora)) {
            $fecha_hora = date('Y-m-d'); // solo fecha (puedes ajustar a Y-m-d H:i:s si quieres precisión total)
        }
        
        $sql = "SELECT `id`, `fecha_hora`, `mensaje_recibido`,
        `mensaje_enviado`, `id_wa`, `timestamp_wa`,
        `telefono_wa`,id_servicio 
        FROM `registro` 
        where id>0   
        order by fecha_hora desc  ";

        if ($fecha_hora !== '') {
            $sql .= " AND fecha_hora LIKE '" . $this->db->real_escape_string($fecha_hora) . "%'";
        }

        // if ($filtroEstado !== '') {
        //     $sql .= " AND usu_estado = '" . $this->db->real_escape_string($filtroEstado) . "'";
        // }

        $sql .= "ORDER BY fecha_hora DESC";
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function actualizarCampo($id, $campo, $valor) {
        $permitidos = ['usu_filtro', 'usu_ver_nomina', 'usu_estado'];
        if (!in_array($campo, $permitidos)) return;

        $sql = "UPDATE usuarios SET $campo = ? WHERE idusuarios = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $valor, $id);
        $stmt->execute();
        $stmt->close();
    }
    public function eliminarUsuario($id) {
        $sql = "DELETE FROM usuarios WHERE idusuarios = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
