<?php
require_once "../config/database.php";

class  CargosModel{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function contarCargos($search = '',$tipoContrato='') {
        $sql = "SELECT
            c.idcargo,
            c.car_Cargo,
            c.car_Recogida,
            c.car_ValorRecogida,
            c.car_tipoContrato
        FROM cargo c";

        $params = [];
        $types = "";
        if (!empty($tipoContrato)) {
            $sql .= " AND c.car_tipoContrato = ?";
            $params[] = $tipoContrato;
            $types .= "s";
        }


        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
    public function obtenerCargosPaginados($start, $length, $search = '',$tipoContrato='') {
        $sql = "SELECT
            c.idcargo,
            c.car_Cargo,
            c.car_Recogida,
            c.car_ValorRecogida,
            c.car_tipoContrato
        FROM cargo c";
        $params = [];
        $types = "";

        if (!empty($tipoContrato)) {
            $sql .= " AND c.car_tipoContrato = ?";
            $params[] = $tipoContrato;
            $types .= "s";
        }


        $sql .= " ORDER BY c.idcargo DESC LIMIT ?, ?";
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
    public function obtenerCreditos() {
        $sql = "SELECT `idcreditos`, `cre_nombre` FROM `creditos` ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    // public function buscarClientePorTelefono($telefono) {
    //     $sql = "SELECT * FROM clientesdir WHERE cli_telefono = ? LIMIT 1";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bind_param("s", $telefono); // "s" = string
    //     $stmt->execute();
    //     $result = $stmt->get_result();

    //     return $result ? $result->fetch_assoc() : null;
    // }
    public function buscarClientePorTelefono($telefono) {
        $sql = "SELECT 
                cd.*, 
                COUNT(c.idrelcrecli) AS total_creditos, 
                GROUP_CONCAT(c.idrelcrecli) AS creditos_asociados,
                GROUP_CONCAT(cr.cre_nombre SEPARATOR ', ') AS nombres_creditos
            FROM clientesdir cd
            LEFT JOIN rel_crecli c ON cd.idclientesdir = c.rel_idcliente
            LEFT JOIN creditos cr ON c.rel_idcredito = cr.idcreditos
            WHERE cd.cli_telefono = ?
            GROUP BY cd.idclientesdir;";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $telefono);
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
        
        // Obtener id del cliente actualizado
        $sqlId = "SELECT idclientesdir FROM clientesdir WHERE cli_telefono = ?";
        $stmtId = $this->db->prepare($sqlId);
        $stmtId->bind_param("s", $data['telefonos']);
        $stmtId->execute();
        $result = $stmtId->get_result();
        $row = $result->fetch_assoc();
        $idCliente = $row ? $row['idclientesdir'] : null;
        $stmtId->close();
        if (!empty($data['creditos_asignados']) && $idCliente) {
            $creditos = explode(",", $data['creditos_asignados']); // array de IDs

            $sqlInsert = "INSERT INTO rel_crecli (rel_idcredito, rel_idcliente) VALUES (?, ?)";
            $stmtInsert = $this->db->prepare($sqlInsert);

            foreach ($creditos as $idCredito) {
                $idCredito = trim($idCredito); // limpiar espacios
                if ($idCredito !== "") {
                    $stmtInsert->bind_param("ii", $idCredito, $idCliente);
                    $stmtInsert->execute();
                }
            }

            $stmtInsert->close();
        }

        return true; // Si todo salió bien
    }

    public function obtenerCargoYSalarios($idcargo) {

            // Datos del cargo
            $sqlCargo = "SELECT 
                            idcargo,
                            car_Cargo,
                            car_tipoContrato,
                            car_Recogida,
                            car_ValorRecogida
                        FROM cargo
                        WHERE idcargo = ?";

            $stmt = $this->db->prepare($sqlCargo);
            $stmt->bind_param("i", $idcargo);
            $stmt->execute();
            $cargo = $stmt->get_result()->fetch_assoc();

            // Salarios del cargo
            $sqlSalarios = "SELECT 
                                id_salCargo,
                                salario,
                                auxilio,
                                otros,
                                dias,
                                anio,
                                des_salud,
                                des_pension
                            FROM salarios_cargos
                            WHERE id_relCargo = ?
                            ORDER BY anio DESC";

            $stmt2 = $this->db->prepare($sqlSalarios);
            $stmt2->bind_param("i", $idcargo);
            $stmt2->execute();
            $salarios = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

            return [
                'cargo' => $cargo,
                'salarios' => $salarios
            ];
        }

        public function actualizarCargo($data) {

        $sql = "UPDATE cargo SET
                    car_Cargo = ?,
                    car_tipoContrato = ?,
                    car_Recogida = ?,
                    car_ValorRecogida = ?
                WHERE idcargo = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        return $stmt->bind_param(
            "sssii",
            $data['cargo'],
            $data['tipo_contrato'],
            $data['recogida'],
            $data['valor_recogida'],
            $data['idcargo']
        ) && $stmt->execute();
    }
    public function insertarSalarioCargo($data) {

        $sql = "INSERT INTO salarios_cargos
                (id_relCargo, salario, auxilio, otros, dias, anio,des_salud,des_pension)
                VALUES (?, ?, ?, ?, ?, ?,?,?)";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        return $stmt->bind_param(
            "iiiiiiii",
            $data['idcargo'],
            $data['salario'],
            $data['auxilio'],
            $data['otros'],
            $data['dias'],
            $data['anio'],
            $data['salud'],
            $data['pension']
        ) && $stmt->execute();
    }
    public function eliminarSalarioCargo($idSalario) {

        $sql = "DELETE FROM salarios_cargos WHERE id_salCargo = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        return $stmt->bind_param("i", $idSalario) && $stmt->execute();
    }

    public function cargoTieneSalarios($idcargo) {

        $sql = "SELECT COUNT(*) total
                FROM salarios_cargos
                WHERE id_relCargo = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idcargo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['total'] > 0;
    }
    public function eliminarCargo($idcargo) {

        $sql = "DELETE FROM cargo WHERE idcargo = ?";

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            return false;
        }

        return $stmt->bind_param("i", $idcargo) && $stmt->execute();
    }

    public function insertarCargo($data) {

        $this->db->begin_transaction();

        try {
            // 1️⃣ Insertar cargo
            $sqlCargo = "INSERT INTO cargo
                (car_Cargo, car_tipoContrato, car_Recogida, car_ValorRecogida)
                VALUES (?, ?, ?, ?)";

            $stmt = $this->db->prepare($sqlCargo);
            $stmt->bind_param(
                "sssi",
                $data['cargo'],
                $data['tipo_contrato'],
                $data['recogida'],
                $data['valor_recogida']
            );
            $stmt->execute();

            $idcargo = $this->db->insert_id;

            // 2️⃣ Insertar salario inicial
            $sqlSal = "INSERT INTO salarios_cargos
                (id_relCargo, salario, auxilio, otros, dias, anio,des_salud,des_pension)
                VALUES (?, ?, ?, ?, ?, ?,?,?)";

            $stmt2 = $this->db->prepare($sqlSal);
            $stmt2->bind_param(
                "iiiiiiii",
                $idcargo,
                $data['salario'],
                $data['auxilio'],
                $data['otros'],
                $data['salud'],
                $data['pension']
            );
            $stmt2->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }



 
    
}
