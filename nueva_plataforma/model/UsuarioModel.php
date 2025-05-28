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
file_put_contents("debug_sql.log", $sql . PHP_EOL, FILE_APPEND);
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
