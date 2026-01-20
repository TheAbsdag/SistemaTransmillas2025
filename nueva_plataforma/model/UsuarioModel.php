<?php
require_once "../config/database.php";

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerUsuarios($filtroRol = '', $filtroEstado = '') {
        $sql = "SELECT u.*, r.rol_nombre FROM usuarios u
                INNER JOIN roles r ON r.idroles = u.roles_idroles
                WHERE idusuarios != 1";

        if ($filtroRol !== '') {
            $sql .= " AND idroles = '" . $this->db->real_escape_string($filtroRol) . "'";
        }

        if ($filtroEstado !== '') {
            $sql .= " AND usu_estado = '" . $this->db->real_escape_string($filtroEstado) . "'";
        }

        $sql .= " ORDER BY usu_nombre ASC";
        
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
    public function listarDispositivos($idUsuario) {
        $sql = "SELECT id, device_name, device_type, last_login, ip_last, authorized
                FROM user_devices
                WHERE user_id = ? AND active = 1
                ORDER BY created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function autorizarDispositivo($id) {
        $stmt = $this->db->prepare("UPDATE user_devices SET authorized = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // public function bloquearDispositivo($id) {
    //     $stmt = $this->db->prepare("UPDATE user_devices SET active = 0 WHERE id = ?");
    //     $stmt->bind_param("i", $id);
    //     $stmt->execute();
    // }
}
