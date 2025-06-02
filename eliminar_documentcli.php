<?php
require("login_autentica.php"); 
include("declara.php");

if (isset($_POST['iddoccliente']) && is_numeric($_POST['iddoccliente'])) {
    $id = $_POST['iddoccliente'];
    $archivo = $_POST['archivo'];

    $ruta = "./img_docHVC/" . $archivo;
    if (!empty($archivo) && file_exists($ruta)) {
        unlink($ruta);
    }

    $sql = "DELETE FROM doc_hoja_clientes WHERE iddoccliente = $id";
    if ($DB->Execute($sql)) {
        echo "Documento eliminado correctamente.";
    } else {
        echo "Error al eliminar de la base de datos.";
    }
} else {
    echo "Datos inválidos.";
}
?>
