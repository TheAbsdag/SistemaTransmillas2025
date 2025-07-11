<?php
require_once "../config/database.php";

class induccionesComunicados {
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

    // ✅ Nuevo método para insertar comunicado o inducción
    public function insertarComunicado($nombreDoc, $encargado, $usuario, $linkDoc, $archivoNombre, $estado, $fechaRegistro, $fechaUsuario, $fechaEncargado) {
    $sql = "INSERT INTO comunicados_inducciones (
        ci_nombre_documento,
        ci_encargado,
        ci_usuario,
        ci_link_documento,
        ci_ruta_archivo,
        ci_estado,
        ci_fecha_registro,
        ci_fecha_confirmacion_usuario,
        ci_fecha_confirmacion_encargado
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param(
        "sssssssss",
        $nombreDoc,
        $encargado,
        $usuario,
        $linkDoc,
        $archivoNombre,
        $estado,
        $fechaRegistro,
        $fechaUsuario,
        $fechaEncargado
    );

    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

    public function obtenerComunicados($estado = '') {
        $sql = "SELECT * FROM comunicados_inducciones WHERE 1";

        if ($estado !== '') {
            $sql .= " AND ci_estado = '" . $this->db->real_escape_string($estado) . "'";
        }

        $sql .= " ORDER BY ci_fecha_registro DESC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

}
