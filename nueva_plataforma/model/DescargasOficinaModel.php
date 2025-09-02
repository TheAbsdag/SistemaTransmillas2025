<?php
require_once "../config/database.php";

class DescargasOficinaModel{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerSerProgramados($filtroFecha = '', $filtroCiudad = '', $filtroOperador= '') {

        // Establecer zona horaria de Bogotá
        date_default_timezone_set('America/Bogota');
        // Si no se pasa una fecha, usa la fecha actual
        $conde1 ='';
        $conde2='';
        $hoy= date("Y-m-d");
        $conde ="and ser_fechafinal > '$hoy 00:00:00'";
        if($filtroOperador != ''){
            $conde1 = "and ser_idresponsable='$filtroOperador'";
        }
        if ($filtroCiudad != '') {
            $conde2=$filtroCiudad;
        }
        
        if ($filtroFecha != '') {
            $conde ="and ser_fechafinal > '$filtroFecha 00:00:00'";
        }

        
        
        $sql = "SELECT 
        `idservicios`,
        `cli_nombre`,
        `cli_direccion`,
        `ser_destinatario`,
        `ciu_nombre`,
        `ser_direccioncontacto`,
        `ser_paquetedescripcion`,
        `ser_piezas`,`usu_nombre`,
        `ser_clasificacion`,
        `ser_consecutivo`,
        `ser_pendientecobrar`,
        cli_idciudad,ser_estado,
        ser_guiare,
        ser_fechafinal
        FROM serviciosdia 
        inner join usuarios on idusuarios=ser_idresponsable  
        where   ser_idverificadopeso=0 
        $conde and ser_estado in (6,4) $conde1 $conde2
        ORDER BY idservicios,ser_fechafinal asc ";

        // ✅ Guardar consulta en log para depuración
        // $logPath = __DIR__ . '/log_consultas.txt'; // puedes cambiar el nombre/ruta si quieres
        // $logMessage = "[" . date("Y-m-d H:i:s") . "] SQL: $sql\n";
        // file_put_contents($logPath, $logMessage, FILE_APPEND);
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }



    public function obtenerCiudades($conde='') {


        $sql = "SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    public function sedes($idsede) {
        $sql = "SELECT idciudades, ciu_nombre 
                FROM ciudades 
                WHERE inner_sedes = '$idsede' 
                AND inner_estados = 1";

        $result = $this->db->query($sql);

        $valor = "(";
        $va = 0;

        if ($result) {
            while ($row = $result->fetch_array(MYSQLI_NUM)) {
                $valor .= $row[0] . ",";
                $va++;
            }
            $result->free();
        }

        if ($va > 0) {
            $valor = rtrim($valor, ",") . ")";
        } else {
            $valor = 0;
        }

        return $valor;
    }
    public function obtenerOperadores() {
        $sql = "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE  (usu_estado=1 or usu_filtro=1) AND roles_idroles IN (2,3,5,8) ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function buscarServicio($id) {
        // $sql = "SELECT `ser_peso`,
        // `ser_valor`,
        // `ser_pendientecobrar`,
        // `ser_clasificacion`,
        // ser_volumen,
        // ser_guiare,
        // ser_descripcion,
        // ser_ciudadentrega 
        // FROM `servicios` 
        // WHERE `idservicios`=$id";

        $sql = "SELECT  
            s.ser_peso,
            s.ser_valor,
            s.ser_pendientecobrar,
            s.ser_clasificacion,
            s.ser_volumen,
            s.ser_descripcion,
            s.ser_guiare,
            s.ser_ciudadentrega,
            s.ser_telefonocontacto,
            s.cli_telefono,
            s.cli_idciudad,
            i.idimagenguias,
            i.ima_ruta,
            i.ima_tipo,
            i.ima_fecha,
            s.ser_piezas,
            sv.ser_img_recog,
            sv.ser_img_entre
        FROM serviciosdia s
        LEFT JOIN imagenguias i 
            ON s.idservicios = i.ima_idservicio 
            AND i.ima_tipo = 'Recogida'
        LEFT JOIN servicios sv
            ON s.idservicios = sv.idservicios
        WHERE s.idservicios = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }


    public function enviarAlertaWhat($telefono, $tipo) {
        $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

        $data = array(
            "telefono" => $telefono,
            "tipo_alerta" => $tipo,

        );

        $data_json = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer MiSuperToken123'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            error_log("Error en alerta WhatsApp: $error");
        } else {
            $response_data = json_decode($response, true);
            error_log("WhatsApp enviado: " . print_r($response_data, true));
        }

        curl_close($curl);
    }
}
