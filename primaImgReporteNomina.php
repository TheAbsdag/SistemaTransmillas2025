<?php
require("login_autentica.php");
include("cabezote3.php");

$response = ['exito' => false, 'mensaje' => '', 'nombre_archivo' => ''];

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $directorioDestino = 'img_nomina/primas/';
    
    // Crear la carpeta si no existe
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0775, true);
    }

    // Nombre único
    $nombreArchivo = 'reporte_prima_' . date('Ymd_His') . '.png';
    $rutaFinal = $directorioDestino . $nombreArchivo;

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaFinal)) {
        $response['exito'] = true;
        $response['nombre_archivo'] = $nombreArchivo;
    } else {
        $response['mensaje'] = 'No se pudo mover el archivo.';
    }
} else {
    $response['mensaje'] = 'No se recibió ningún archivo válido.';
}

$ids = isset($_POST['ids']) ? json_decode($_POST['ids'], true) : [];
$fecha = $_POST['fecha'] ?? '';


// Puedes recorrer así
foreach ($ids as $id) {
    // Aquí haces la inserción en la base de datos
    // Por ejemplo:
    $sql="UPDATE `primas` SET `pri_img_compro`='$nombreArchivo' WHERE pri_fecha_fin like '%$fecha%' and pri_idusu = '$id' ";	
    $DB1->Execute($sql);
    // Ejecutas con prepare/bind, etc.
}



header('Content-Type: application/json');
echo json_encode($response);