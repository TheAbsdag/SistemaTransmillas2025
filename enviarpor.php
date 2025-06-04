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
$guia = $_REQUEST["guia"];
$pieza = $_REQUEST["pieza"];
$variable2 = $_POST["variable2"];


date_default_timezone_set("America/Bogota");
$date = date("Y-m-d H:i:s"); 


	$sql="SELECT `ser_piezas`,idservicios,ser_estado,ser_desvaliguia,ser_ciudadentrega,ser_idverificadopeso,ciu_nombre,sed_color,sed_nombre FROM  `servicios` INNER JOIN ciudades on idciudades=ser_ciudadentrega inner join  sedes on inner_sedes=idsedes WHERE ser_consecutivo='$guia'  ";		
	$DB1->Execute($sql);
	$rw1=mysqli_fetch_row($DB1->Consulta_ID);

    //Capturamos la informacion de la guia en las variables
	$idser=$rw1[1];
	$piezasg=$rw1[0];// cantidad de piezas 
	$estado=$rw1[2];
	$descricion=$rw1[3];
	$inser=1;
    $estadog=7;

    // se verifica si es una guia con mas de 1 pieza 
    if($piezasg>1){

        //Se agrega la nueva pieza
        $sql="INSERT INTO `piezasguia`(`numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) values ('$guia',$pieza,'$id_nombre','$date')";
        // $DB1->Execute($sql);
        $idpieza=$DB1->Executeid($sql); 

        //Se cuenta cuantas piezas hay de esa guia 
        $sql0="SELECT  count(numeropieza) from piezasguia where numeroguia='$guia' ";		
        $DB->Execute($sql0);
        $rw2=mysqli_fetch_row($DB->Consulta_ID);

        //Se verifica si el numero de piezas es igualal numero total de piezas que existen
        if($rw2[0]!=$piezasg){
            $inser=0;
            $sql2="UPDATE `servicios` SET  `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser' ";			
            $DB->Execute($sql2);
            $color=$rw1[7];

        }

    }else{
        //Si es solo una pieza 
        $sql4="INSERT INTO `piezasguia`( `numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) values ('$guia',$pieza,'$id_nombre','$date')";
        // $DB1->Execute($sql4);
        $idpieza=$DB1->Executeid($sql4); 
        $respuesta.=" ✅ Pieza Escaneada Correctamente. Esta es la unica pieza de la Guia $guia";
    }

    //Si se in sertaron ya todas la piezas 
    if($inser==1){

        $sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='7'  where cue_idservicio=$idser";
        $DB1->Execute($sql1);			
        
        $sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='7'
        WHERE `idservicios`='$idser' ";			
        $DB->Execute($sql2);
        
        $sql3="UPDATE `guias` SET `gui_ensede`='$id_nombre',`gui_fechaensede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
        $DB->Execute($sql3); 
        
        $color=$rw1[7];
        $respuesta.="✅ Pieza $guia Escaneada correctamente. Es la Ultima pieza de la Guia.";
    
    }else {
        $respuesta.="✅ Pieza  Escaneada correctamente.";
    }

    if ($tipoVehiculo=="Bus" or $tipoVehiculo=="Jurgon" ) {
        $por="Por";
    }else {
         $por="a";
    }
    if ($variable2=="") {
        $sql4="UPDATE `piezasguia` SET `transporta`='$tipoVehiculo' WHERE `idpiezasguia`='$idpieza' ";	
        $DB->Execute($sql4);
        $respuesta.=" Enviada $por : $tipoVehiculo.";
    }else {
        $sql4="UPDATE `piezasguia` SET `transporta`='$tipoVehiculo',`quien_escanea`='$variable2',`fecha_escanea`='$date' WHERE `idpiezasguia`='$idpieza' ";	
        $DB->Execute($sql4);
        $respuesta.=" Enviada $por : $tipoVehiculo.";
    }

   


?>

			
