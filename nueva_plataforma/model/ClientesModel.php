<?php
require_once "../config/database.php";

class  ClientesModel{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function contarClientes($search = '', $fecha = '', $tipo = '') {
        $sql = "SELECT COUNT(*) AS total
                FROM clientesdir cd
                INNER JOIN clientes c ON cd.cli_idclientes = c.idclientes
                INNER JOIN ciudades ciu ON cd.cli_idciudad = ciu.idciudades
                WHERE c.idclientes > 0";

        $params = [];
        $types = "";

        if (!empty($search)) {
            if (is_numeric($search)) {
            $sql .= " AND (cd.cli_telefono LIKE ?)";
            $like = "%$search%";
            $params = [$like];
            $types = "s";

            }else {
            $sql .= " AND (cd.cli_nombre LIKE ? OR c.cli_iddocumento LIKE ? )";
            $like = "%$search%";
            $params = [$like, $like];
            $types = "ss";
            }

        }

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
    public function obtenerClientesPaginado($start, $length, $search = '', $fecha = '', $ciudad = '') {
        $sql = "SELECT 
            c.idclientes,
            c.cli_iddocumento,
            cd.cli_nombre,
            cd.cli_telefono,
            ciu.ciu_nombre,
            cd.cli_direccion,
            cd.cli_correo,
            c.cli_clasificacion,
            c.cli_valoraprobado
        FROM clientesdir cd INNER JOIN clientes c ON cd.cli_idclientes = c.idclientes INNER JOIN ciudades ciu ON cd.cli_idciudad = ciu.idciudades WHERE c.idclientes > 0";

        $params = [];
        $types = "";

        if (!empty($search)) {
            if (is_numeric($search)) {
            $sql .= " AND ( cd.cli_telefono LIKE ?)";
            $like = "%$search%";
            $params = [$like];
            $types = "s";

            }else {
            $sql .= " AND (cd.cli_nombre LIKE ? OR c.cli_iddocumento LIKE ? )";
            $like = "%$search%";
            $params = [$like, $like];
            $types = "ss";
            }

        }

        if ($ciudad!="") {
            $sql .= " AND cd.cli_idciudad = ?)";
            $params = [$ciudad];
            $types = "s";
        }

        $sql .= " ORDER BY c.idclientes DESC LIMIT ?, ?";
        $params[] = intval($start);
        $params[] = intval($length);
        $types .= "ii";

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }



    public function obtenerCiudades() {
        $sql = "SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function buscarClientePorTelefono($telefono) {
        $sql = "SELECT * FROM clientesdir WHERE cli_telefono = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $telefono); // "s" = string
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }
public function actualizarCliente($data) {
    $sql = "UPDATE clientesdir SET 
                
                cli_telefono = ?, 
                cli_whatsap = ?, 
                cli_idciudad = ?, 
                cli_nombre = ?, 
                cli_direccion = ?, 
                cli_correo = ?
            WHERE cli_telefono = ?";

    $stmt = $this->db->prepare($sql);

    if (!$stmt) {
        // Error al preparar la sentencia
        $error = "Error al preparar UPDATE: " . $this->db->error;
        error_log($error, 3, __DIR__ . '/error.log'); // Guarda en logs/error.log
        return false;
    }

    $direccion = $data['direccion'] . '&' . $data['restodireccion'] . '&' . $data['lugar_recogida'] . '&' . $data['barrio'];

    if (!$stmt->bind_param(
        "sssssss",
        
        $data['telefonos'],
        $data['whatsapp'],
        $data['ciudad'],
        $data['nombre_cliente'],
        $direccion,
        $data['email'],
        $data['telefonos']
    )) {
        // Error al bindear parámetros
        $error = "Error al bindear parámetros: " . $stmt->error;
        error_log($error, 3, __DIR__ . '/error.log');
        return false;
    }

    if (!$stmt->execute()) {
        // Error al ejecutar la sentencia
        $error = "Error al ejecutar UPDATE: " . $stmt->error;
        error_log($error, 3, __DIR__ . '/error.log');
        return false;
    }

    return true; // Si todo salió bien
}

    // Función para registrar logs
 
    
}
