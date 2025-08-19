<?php
require_once "../config/database.php";

class menu {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function obtenerMenu($filtroFecha = '', $filtroCliente = '') {

        // Establecer zona horaria de Bogotá
        date_default_timezone_set('America/Bogota');
        // Si no se pasa una fecha, usa la fecha actual

        
        $sql = "SELECT men_nombre,men_descripcion,men_url FROM `menu` WHERE men_principal=1";

        // ✅ Guardar consulta en log para depuración
        $logPath = __DIR__ . '/log_consultas.txt'; // puedes cambiar el nombre/ruta si quieres
        $logMessage = "[" . date("Y-m-d H:i:s") . "] SQL: $sql\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


}
