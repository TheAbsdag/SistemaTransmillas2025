<?php 
require("login_autentica.php"); 

$id_sede     = $_SESSION['usu_idsede'];
$id_usuario  = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['usuario_nombre'];

$DB  = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();

$fecha_actual = date('Y-m-d H:i:s');

$estado     = $_POST['estado_actualizacion'];
$id_contacto = $_POST['id_contacto']; 

$sql = "UPDATE contactofacturacion SET actualizacion_datos = '$estado' WHERE idcontactofacturacion = '$id_contacto'";

if ($DB1->Execute($sql)) {
    echo json_encode([
        "status" => "ok"
    ]);
} else {
    echo json_encode([
        "status" => "error"
    ]);
}
?>
