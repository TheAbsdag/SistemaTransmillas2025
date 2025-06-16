<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require("login_autentica.php"); 
include("declara.php");


if (isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    // ELIMINA EL REGISTRO SEGÚN EL ID
    $query = "DELETE FROM doc_hoja_clientes WHERE id_p=$id";

    if ( mysqli_query($DB1, $query) ) {
        echo "OK";
    } else {
        echo "Error";
    }
} else {
    echo "Valor de id ausente.";
}

?>