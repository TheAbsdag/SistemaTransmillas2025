<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("login_autentica.php"); 
include("declara.php");

if (isset($_POST['id']) && isset($_POST['version']) && isset($_POST['tabla'])) {
    $id = (int)$_POST['id'];
    $version = (int)$_POST['version'];
    $tabla = $_POST['tabla'];

    echo "ID: $id<br>";
    echo "Versión: $version<br>";
    echo "Tabla: $tabla<br>";

    $query = "DELETE FROM documentos WHERE doc_idviene = '$id' AND doc_tabla = '$tabla' AND doc_version = $version";
    echo "CONSULTA: $query<br>";

    $res = $DB1->Execute($query);

    if ($res) {
        echo "OK";
    } else {
        echo "Error al eliminar: " . $DB1->Error(); // o revisa qué método de error tiene tu clase
    }
} else {
    echo "Parámetros incompletos.";
}
