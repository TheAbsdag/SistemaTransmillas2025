<?php
require("login_autentica.php"); //coneccion bade de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();
$id_nombre=$_SESSION['usuario_nombre'];

//Obtenemos los datos de los input
$cond="";
$guia = $_REQUEST["guia"];
$pieza = $_REQUEST["pieza"];
$ciudado = $_POST["ciudado"];
$idser = $_POST["idser"];
$tipoVehiculo = $_POST["tipoVehiculo"];
$id_nombre = $_POST["id_nombre"];
$piezasg = $_POST["piezasg"];
$inser=1;
date_default_timezone_set('America/Bogota');
$fechatiempo = date("Y-m-d H:i:s"); 

try {
    $inser = 1;

    if ($tipoVehiculo=="Bus" or $tipoVehiculo=="Jurgon") {
        if ($piezasg > 1) {

            $sql = "INSERT INTO `piezasguia`(`numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) 
                    VALUES ('$guia',$pieza,'$id_nombre','$date')";
            $idpieza = $DB1->Executeid($sql);

            if (!$idpieza) {
                echo "Error al insertar en piezasguia";
                exit;
            }

            $sql0 = "SELECT count(numeropieza) FROM piezasguia WHERE numeroguia='$guia'";		
            $ok = $DB->Execute($sql0);

            if (!$ok) {
                echo "Error al consultar piezasguia";
                exit;
            }

            $rw2 = mysqli_fetch_row($DB->Consulta_ID);

            if ($rw2[0] != $piezasg) {
                $inser = 0;
                $sql2 = "UPDATE `servicios` SET `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser'";			
                if (!$DB->Execute($sql2)) {
                    echo "Error al actualizar fecha en servicios";
                    exit;
                }
            }
            

        } else {
            $sql4 = "INSERT INTO `piezasguia`
                     (`numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) 
                     VALUES ('$guia',$pieza,'$id_nombre','$date')";
            $idpieza = $DB1->Executeid($sql4);

            if (!$idpieza) {
                echo "Error al insertar pieza única";
                exit;
            }
        }

        // echo$inser;
            if ($inser == 1) {
                $sql1 = "UPDATE `cuentaspromotor` 
                         SET `cue_fecha`='$fechatiempo', cue_estado='7'  
                         WHERE cue_idservicio=$idser";
                if (!$DB1->Execute($sql1)) {
                    echo "Error al actualizar cuentaspromotor";
                    exit;
                }

                $sql2 = "UPDATE `servicios` 
                         SET `ser_idusuarioregistro`='$id_usuario',
                             `ser_fechaguia`='$fechatiempo',
                             ser_estado='7'
                         WHERE `idservicios`='$idser'";			
                if (!$DB1->Execute($sql2)) {
                    echo "Error al actualizar servicios";
                    exit;
                }

                $sql3 = "UPDATE `guias` 
                         SET `gui_ensede`='$id_nombre',
                             `gui_fechaensede`='$fechatiempo' 
                         WHERE `gui_idservicio`='$idser'";
                if (!$DB1->Execute($sql3)) {
                    echo "Error al actualizar guias";
                    exit;
                }
            }

        $sql5 = "UPDATE `piezasguia` 
                 SET `transporta`='$tipoVehiculo',
                     `quien_escanea`='$id_nombre',
                     `fecha_escanea`='$fechatiempo' 
                 WHERE `idpiezasguia`='$idpieza'";	
        if (!$DB1->Execute($sql5)) {
            echo "Error al actualizar piezasguia";
            exit;
        }

    } 

    $sql6 = "UPDATE `servicios` 
             SET `ser_transporta`='$tipoVehiculo',
                 ser_quien_escanea='$id_nombre',
                 ser_fecha_escanea='$fechatiempo' 
             WHERE `idservicios`='$idser'";			
    if (!$DB1->Execute($sql6)) {
        echo "Error al actualizar transporta en servicios";
        exit;
    }

    echo "OK";

} catch (Exception $e) {
    echo "Excepción atrapada: " . $e->getMessage();
}

$DB->cerrarconsulta();
$DB1->cerrarconsulta();

