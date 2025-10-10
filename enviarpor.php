<?php
require("login_autentica.php"); //coneccion bade de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();
$id_nombre=$_SESSION['usuario_nombre'];
$color="#B20F08";
//Obtenemos los datos de los input
$cond="";

$tipoVehiculo = $_POST["tipoVehiculo"];
$variable1  = $_POST["variable1"];
$variable2 = $_POST["variable2"];
date_default_timezone_set("America/Bogota");
$date = date("Y-m-d H:i:s"); 

if ($variable2=="") {
    echo$sql2="UPDATE `piezasguia` SET `transporta`='$tipoVehiculo' WHERE `idpiezasguia`='$variable1' ";	
    $DB->Execute($sql2);
}else {
    echo$sql2="UPDATE `piezasguia` SET `transporta`='$tipoVehiculo',`quien_escanea`='$variable2',`fecha_escanea`='$date' WHERE `idpiezasguia`='$variable1' ";	
    $DB->Execute($sql2);
}
    // $sql2="UPDATE `servicios` SET  `ser_transporta`='$tipoVehiculo',ser_quien_escanea='$variable2',ser_fecha_escanea='$date' WHERE `idservicios`='$variable1' ";			

   


?>

			
