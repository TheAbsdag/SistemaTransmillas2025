<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../model/RecogidasMovilModel.php';
$model = new RecogidasMovilModel();
$db = $model->getDB();

$res = $db->query("SELECT * FROM cola_procesos WHERE estado='pendiente' LIMIT 5");

while ($row = $res->fetch_assoc()) {

    $id = $row['id'];
    $tipo = $row['tipo'];
    $idservicio = $row['idservicio'];
    $datos = json_decode($row['datos'], true);

    $db->query("UPDATE cola_procesos SET estado='procesando' WHERE id=$id");

    try {

        if ($tipo == 'generar_link_guia') {
            $model->guardarLinkServicio(
                $idservicio,
                'Recogida',
                '',
                $datos['peso'],
                $datos['volumen'],
                $datos['seguro'],
                $datos['valor']
            );
        }

        if ($tipo == 'whatsapp_firma') {
            $model->reEnviarFirmaWhat($datos['telefono'], 44, $idservicio, $datos['link']);
        }

        if ($tipo == 'whatsapp_guia') {
            $model->enviarGuiaWhat($datos['telefono'], 42, $datos['guia']."R");
        }

        $db->query("UPDATE cola_procesos SET estado='listo', fecha_procesado=NOW() WHERE id=$id");

    } catch (Exception $e) {
        $db->query("UPDATE cola_procesos SET estado='error', intentos=intentos+1 WHERE id=$id");
    }
}