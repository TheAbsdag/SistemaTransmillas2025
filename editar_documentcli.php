<?php
require("login_autentica.php"); 
include("declara.php");

if (isset($_POST['iddoccliente'], $_POST['nombre'], $_POST['fecha'])) {
    $id = $_POST['iddoccliente'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];

    $sql_sel = "SELECT docl_documento FROM doc_hoja_clientes WHERE iddoccliente = $id";
    $res = $DB1->Execute($sql_sel);
    $row = mysqli_fetch_assoc($DB1->Consulta_ID);
    $archivo_anterior = $row['docl_documento'];

    $nombre_archivo = $archivo_anterior;

    if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
        $ruta_destino = "./img_docHVC/";
        $nombre_archivo = time() . "_" . basename($_FILES['documento']['name']);
        $ruta_completa = $ruta_destino . $nombre_archivo;

        if (move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_completa)) {
            
            if ($archivo_anterior && file_exists($ruta_destino . $archivo_anterior)) {
                unlink($ruta_destino . $archivo_anterior);
            }
        } else {
            echo "Error al subir el nuevo archivo.";
            exit;
        }
    }

    $sql1 = "UPDATE doc_hoja_clientes SET docl_nombre = '$nombre', docl_fecha_venc = '$fecha', docl_documento = '$nombre_archivo' WHERE iddoccliente = $id";
    $DB1->Execute($sql1);
    $DB1->cerrarconsulta();

    echo "Documento actualizado correctamente.";
} else {
    echo "Faltan datos requeridos.";
}
?>
