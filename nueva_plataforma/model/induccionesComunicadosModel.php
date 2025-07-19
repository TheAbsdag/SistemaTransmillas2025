<?php
require_once "../config/database.php";

class induccionesComunicados {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    //Obtener solo nombres de usuarios activos
    public function obtenerUsuarios($termino = '') {
    $sql = "SELECT usu_nombre FROM usuarios WHERE idusuarios != 1";

    if (!empty($termino)) {
        $termino = $this->db->real_escape_string($termino);
        $sql .= " AND usu_nombre LIKE '%$termino%'";
    }

    $sql .= " ORDER BY usu_nombre ASC";

    $result = $this->db->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    //Cargar roles
    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // Actualizar campos permitidos estado/revision encargado
    public function actualizarCampoCI($id, $campo, $valor) {
    $permitidos = ['ci_estado'];
    if (!in_array($campo, $permitidos)) return false;

    // Si el estado cambia a 'validado', actualizamos la fecha del encargado
    if ($campo === 'ci_estado' && $valor === 'validado') {
        $fechaHoy = date('Y-m-d');
        $sql = "UPDATE comunicados_inducciones 
                SET ci_estado = ?, ci_fecha_confirmacion_encargado = ? 
                WHERE ci_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssi", $valor, $fechaHoy, $id);
    } else {
        $sql = "UPDATE comunicados_inducciones SET $campo = ? WHERE ci_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("si", $valor, $id);
    }

    return $stmt->execute();
}

    //Eliminar comunicado
    public function eliminarComunicado($id) {
        $sql = "DELETE FROM comunicados_inducciones WHERE ci_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    //Insertar nuevo comunicado 
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

    // Obtener comunicados para la tabla
    public function obtenerComunicados($estado = '') {
        $sql = "SELECT * FROM comunicados_inducciones WHERE 1";

        if ($estado !== '') {
            $sql .= " AND ci_estado = '" . $this->db->real_escape_string($estado) . "'";
        }

        $sql .= " ORDER BY ci_fecha_registro DESC";

        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

   public function obtenerUsuariosPorSede($sedeId = '') {
    $sql = "SELECT idusuarios, usu_nombre 
            FROM usuarios 
            WHERE idusuarios != 1 
              AND usu_estado = 1 
              AND usu_filtro = 1";

    if (!empty($sedeId)) {
        $sedeId = $this->db->real_escape_string($sedeId);
        $sql .= " AND usu_idsede = '$sedeId'";
    }

    $sql .= " ORDER BY usu_nombre ASC";
    $result = $this->db->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


   public function obtenerEncargados() {
    $sql = "SELECT usu_nombre 
            FROM usuarios 
            WHERE roles_idroles IN (1, 12) 
              AND usu_estado = 1 
              AND usu_filtro = 1 
            ORDER BY usu_nombre ASC";
    
    $result = $this->db->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
}