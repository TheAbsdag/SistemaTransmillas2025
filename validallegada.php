<?php
require("login_autentica.php"); //coneccion base de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();
$id_nombre = $_SESSION['usuario_nombre'];

// función para medir tiempos
function logTime($label, $start) {
    $elapsed = microtime(true) - $start;
    $logfile = __DIR__ . "/tiempos_queries.log"; // se guarda junto al script
    file_put_contents(
        $logfile,
        "[" . date("Y-m-d H:i:s") . "] $label tardó: " . number_format($elapsed, 4) . " segundos\n",
        FILE_APPEND
    );
}

//Obtenemos los datos de los input
$cond = "";
$valores = $_POST["valores"];
$ciudaddes = $_POST["ciudaddes"];
$ciudado = $_POST["ciudado"];
$gnpiezas = explode(" ", $valores);
$guia = $gnpiezas[0];
$pieza = $gnpiezas[1];

// --- Consulta 1 ---
$start = microtime(true);
$sql = "SELECT ser_piezas,idservicios,ser_estado,ser_desvaliguia,ser_ciudadentrega 
        FROM servicios 
        INNER JOIN ciudades ON idciudades=ser_ciudadentrega 
        WHERE ser_consecutivo='$guia' AND inner_sedes='$ciudaddes' 
        LIMIT 1";
$DB1->Execute($sql);
$rw1 = mysqli_fetch_row($DB1->Consulta_ID);
logTime("Consulta 1 (servicios + ciudades)", $start);

$idser = $rw1[1];
$piezasg = $rw1[0];
$estado = $rw1[2];
$descricion = $rw1[3];

if ($idser == '') {
    // --- Insert malpistoleada ---
    $start = microtime(true);
    $sql = "INSERT INTO malpistoleada (numeroguiamal, mal_idsedeori, mal_idciudaddes, mal_fecha, mal_enviada) 
            VALUES ('$guia',$ciudado,'$ciudaddes','$fechaactual','1')";
    $DB1->Execute($sql);
    logTime("Insert malpistoleada", $start);

    $datos = array("resultado" => "1");
} else {
    // --- Consulta piezasguia ---
    $start = microtime(true);
    $sqlg = "SELECT numeropieza FROM piezasguia WHERE numeroguia='$guia' AND numeropieza='$pieza' LIMIT 1";
    $DB->Execute($sqlg);
    $rwg = mysqli_fetch_row($DB->Consulta_ID);
    logTime("Consulta piezasguia", $start);

    if ($rwg[0] == '') {
        // --- Insert piezasguia ---
        $start = microtime(true);
        $sql = "INSERT INTO piezasguia (numeroguia, numeropieza) VALUES ('$guia',$pieza)";
        $DB1->Execute($sql);
        logTime("Insert piezasguia", $start);
    }

    if ($estado == 7) {
        $llego = 'SI';
        $inser = 1;
        $estadog = 8;
        $descr = 'Validada con Pistola';

        if ($piezasg > 1) {
            // --- Update pieza llegada ---
            $start = microtime(true);
            $sql = "UPDATE piezasguia SET guiallega=1 WHERE numeroguia='$guia' AND numeropieza='$pieza'";
            $DB1->Execute($sql);
            logTime("Update pieza llegada", $start);

            // --- Conteo piezasguia ---
            $start = microtime(true);
            $sql = "SELECT count(numeropieza) FROM piezasguia WHERE numeroguia='$guia' AND guiallega=1";
            $DB->Execute($sql);
            $rw2 = mysqli_fetch_row($DB->Consulta_ID);
            logTime("Conteo piezasguia", $start);

            if ($rw2[0] != $piezasg) {
                $inser = 0;
                // --- Update servicios fecha ---
                $start = microtime(true);
                $sql2 = "UPDATE servicios SET ser_fechaguia='$fechatiempo' WHERE idservicios='$idser'";
                $DB->Execute($sql2);
                logTime("Update servicios fecha", $start);
            }
        } else {
            // --- Update llegada todas piezas ---
            $start = microtime(true);
            $sql4 = "UPDATE piezasguia SET guiallega=1 WHERE numeroguia='$guia'";
            $DB1->Execute($sql4);
            logTime("Update llegada todas piezas", $start);
        }

        if ($inser == 1) {
            // --- Update cuentaspromotor ---
            $start = microtime(true);
            $sql1 = "UPDATE cuentaspromotor SET cue_fecha='$fechatiempo', cue_estado='$estadog' WHERE cue_idservicio=$idser";
            $DB1->Execute($sql1);
            logTime("Update cuentaspromotor", $start);

            // --- Update servicios ---
            $start = microtime(true);
            $sql2 = "UPDATE servicios 
                     SET ser_idusuarioregistro='$id_usuario',
                         ser_fechaguia='$fechatiempo',
                         ser_estado='$estadog',
                         ser_desvaliguia='$descr',
                         ser_llego='$llego'
                     WHERE idservicios='$idser'";
            $DB->Execute($sql2);
            logTime("Update servicios", $start);

            // --- Update guias ---
            $start = microtime(true);
            $sql3 = "UPDATE guias 
                     SET gui_validasede='$id_nombre', gui_fechavalidasede='$fechatiempo' 
                     WHERE gui_idservicio='$idser'";
            $DB->Execute($sql3);
            logTime("Update guias", $start);
        }

        $datos = array(
            "resultado" => "2",
            "idGuia" => "$guia",
            "paquete" => "",
            "piezas" => "$pieza"
        );
    } else if ($estado == 8) {
        $datos = array("resultado" => "4");
    } else {
        $datos = array("resultado" => "3");
    }
}

// Devolvemos JSON
header('Content-Type: application/json');
echo json_encode($datos, JSON_FORCE_OBJECT);