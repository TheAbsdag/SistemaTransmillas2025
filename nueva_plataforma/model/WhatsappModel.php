<?php
require_once "../config/database.php";

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerMensajes($filtroFecha = '', $filtroTipoMensaje = '') {

        // Establecer zona horaria de Bogotá
        date_default_timezone_set('America/Bogota');
        // Si no se pasa una fecha, usa la fecha actual

        
        $sql = "SELECT `id`, `fecha_hora`, `mensaje_recibido`,
        `mensaje_enviado`, `id_wa`, `timestamp_wa`,
        `telefono_wa`,id_servicio 
        FROM `registro` 
        where id>0   
        ";

        if ($filtroFecha !== '') {
            $sql .= "AND DATE(fecha_hora) = '" . $this->db->real_escape_string($filtroFecha) . "'";
        }else{
            $filtroFecha = date('Y-m-d'); // solo fecha (puedes ajustar a Y-m-d H:i:s si quieres precisión total)
            $sql .= "AND DATE(fecha_hora) = '" . $this->db->real_escape_string($filtroFecha) . "'";
        }

        if ($filtroTipoMensaje !== '') {

            if ($filtroTipoMensaje=="Alertas") {
                $sql .="AND tipo ='Alerta'";
            }else if ($filtroTipoMensaje=="ChatBot") {
                $sql .="AND CHAR_LENGTH(mensaje_enviado) > 2 ";
            }else if ($filtroTipoMensaje=="ServiciosHechos") {
                $sql .="AND mensaje_enviado like '%Hemos creado tu servicio%'";
            }else if ($filtroTipoMensaje=="Cotizaciones") {
                $sql .="AND mensaje_enviado like '%Cotización registrada%'";
            }
            
        }else {
            $sql .="AND CHAR_LENGTH(mensaje_enviado) > 2 ";
        }

        $sql .= "ORDER BY fecha_hora ASC";

        // ✅ Guardar consulta en log para depuración
        $logPath = __DIR__ . '/log_consultas.txt'; // puedes cambiar el nombre/ruta si quieres
        $logMessage = "[" . date("Y-m-d H:i:s") . "] SQL: $sql\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        
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
