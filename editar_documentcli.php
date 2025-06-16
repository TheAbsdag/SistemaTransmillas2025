<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require("login_autentica.php"); 
include("declara.php");

if (isset($_POST['iddoccliente'], $_POST['nombre'], $_POST['fecha'])) {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $id_anterior = $_POST['iddoccliente'];

    // Consultar hoja de vida asociada
    $sql_hoja = "SELECT docl_idhvc FROM doc_hoja_clientes WHERE iddoccliente = $id_anterior";
    $res_hoja = $DB1->Execute($sql_hoja);
    $row_hoja = mysqli_fetch_assoc($DB1->Consulta_ID);
    $idhojadevida = $row_hoja['docl_idhvc'];

    $nombre_archivo = "";

    if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
        $ruta_destino = "./img_docHVC/";
        $nombre_archivo = time() . "_" . basename($_FILES['documento']['name']);
        $ruta_completa = $ruta_destino . $nombre_archivo;

        if (!move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_completa)) {
            echo "Error al subir el nuevo archivo.";
            exit;
        }
    }

    // Insertar nuevo registro en lugar de actualizar
    $sql1 = "INSERT INTO doc_hoja_clientes (docl_nombre, docl_fecha_venc, docl_documento, docl_idhvc)
             VALUES ('$nombre', '$fecha', '$nombre_archivo', '$idhojadevida')";
    $DB1->Execute($sql1);
    $DB1->cerrarconsulta();

    echo "Documento agregado como nueva actualización.";
} else {
    echo "Faltan datos requeridos.";
}
?>
