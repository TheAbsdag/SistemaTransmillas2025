<?php
require_once 'config/database.php';
require_once 'model/DetallePrimasModel.php';

class DetallePrimasController {
    public function detalle() {
        $db = (new Database())->connect();
        $model = new DetallePrimasModel($db);

        $params = [
            'sede' => $_POST['param35'] ?? '',
            'cedula' => $_POST['param33'] ?? '',
            'mes' => $_POST['param34'] ?? '',
            'periodo' => $_POST['param36'] ?? '',
        ];

        $datos = $model->obtenerDetallePrimas($params);
        require 'view/primas/detalle.php';
    }
    
    private function obtenerSedes($db) {
        $sedes = [];
        $sql = "SELECT id_sedes, nombre_sede FROM sedes WHERE estado = 'Activo' ORDER BY nombre_sede ASC";
        $result = $db->query($sql);
        while ($row = $result->fetch_assoc()) {
            $sedes[] = $row;
        }
        return $sedes;
    }
}
?>
