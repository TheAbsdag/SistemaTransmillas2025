<?php
require("login_autentica.php");
include("declara.php");
$tabla=$_REQUEST["tabla"];
$id_param=$_REQUEST["id_param"];
if(isset($_REQUEST["condecion"])){ $condecion=$_REQUEST["condecion"]; } else { $condecion=""; } 
$conde=""; $bandera=1;
$tabla1=$tabla;
if($condecion=='general'){  $tabla='General'; }
elseif($condecion=='delete'){
	$tabla='delete';
}
switch($tabla)
{

	case "Elimina Archivo1":
	$valores[7]="DELETE FROM documentos WHERE iddocumentos='$id_param'  "; 
	$valores[8]=1; $valores[4]=$_SERVER["HTTP_REFERER"]; $id_param=""; $ir=1;
	break;
	case "Elimina Archivo2":
		echo$valores[7]="DELETE FROM imagenguias WHERE idimagenguias='$id_param'";
		$ruta = $_REQUEST["ruta"];
		if (strpos($ruta, 'ticketfacturacorreoimprimir') !== false) {
		
			$idFirma=$_REQUEST["idFirma"];
				$sel="DELETE FROM `firma_clientes` WHERE id_guia=$idFirma";

			$DB1->Execute($sel);
		}

		
		if (file_exists($ruta)) {
			unlink($ruta);
		} 
		$valores[8]=1; $valores[4]=$_SERVER["HTTP_REFERER"]; $id_param=""; $ir=1;
	break;
	case "Clientes":
	
	/* $sql1="DELETE FROM `clientesdir` WHERE `cli_idclientes`='$id_param'";
	$DB->Execute($sql1); */
	
	//$valores[7]="DELETE FROM `clientes` WHERE `idclientes`='$id_param'  "; 
	$valores[7]="DELETE FROM `clientesdir` WHERE `cli_idclientes`='$id_param' "; 
	$valores[8]=1; $valores[4]='clientes.php'; $id_param=""; $ir=1;
	
	break;
	case "referenciasfamiliares":
	$valores[7]="DELETE FROM referenciasfamiliares WHERE idrefenciasfamiliares='$id_param'  "; 
	$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datosfamiliares&idhojadevida=$condecion"; $id_param=""; $ir=3;
	$tabla=$tabla1;
	break;
	case "referenciaslaborales":
		$valores[7]="DELETE FROM referenciaslaborales WHERE idreferenciaslaborales='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datoslaborales&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "seguridadsocial":
		$valores[7]="DELETE FROM seguridadsocial WHERE idseguridadsocial='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datossalud&idhojadevida=$condecion"; $id_param=""; $ir=3;
	break;	
	case "memorandos":
		$valores[7]="DELETE FROM memorandos WHERE idmemorandos='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=memorandos&idhojadevida=$condecion"; $id_param=""; $ir=3;
	break;
	case "examenesmedicos":
		$valores[7]="DELETE FROM examenesmedicos WHERE idexamenesmedicos='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=examenesmedicos&idhojadevida=$condecion"; $id_param=""; $ir=3;
	break;
	case "elementostrabajo":
	$valores[7]="DELETE FROM elementostrabajo WHERE idelementostrabajo='$id_param'  "; 
	$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=dotacion&idhojadevida=$condecion"; $id_param=""; $ir=3;
	$tabla=$tabla1;
	break;	
	case "elementostrabajo":
		$valores[7]="DELETE FROM elementostrabajo WHERE idelementostrabajo='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=dotacion&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
		break;	
	case "referenciassalud":
		$valores[7]="DELETE FROM referenciassalud WHERE idrefenciassalud='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=saludafiliaciones&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "reportealarmas":
		$valores[7]="DELETE FROM reportealertas WHERE idreportealertas='$id_param'  "; 
		$valores[8]=1; $valores[4]="reportealertas.php"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "Incapacidades":
		$valores[7]="DELETE FROM incapacidades WHERE idincapacidades='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=Incapacidades&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "entregavehiculo":
		$valores[7]="DELETE FROM entregavehiculo WHERE identregavehiculo='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datosvehiculo&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "revisionvehiculo":
		echo$valores[7]="DELETE FROM revisionvehiculo WHERE idrevisionvehiculo='$id_param' "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datosvehiculo&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "referenciasestudio":
		$valores[7]="DELETE FROM referenciasestudio WHERE idreferenciasestudio='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevida.php?bandera=1&condecion=datosestudios&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "hojadevidacliente":
		$valores[7]="DELETE FROM hojadevidacliente WHERE idhojadevida='$id_param'  "; 
		$valores[8]=1; $valores[4]="hojadevidaclientes.php?bandera=1"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "paquetes":
		$valores[7]="DELETE FROM paquetes WHERE idpaquetes='$id_param'  "; 
		$valores[8]=1; $valores[4]="tablapaquetes.php?bandera=1"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "contactofacturacion":
		$valores[7]="DELETE FROM contactofacturacion WHERE idcontactofacturacion='$id_param'  "; 
		$valores[8]=1; $valores[4]="new_hojadevidacliente.php?bandera=1&condecion=datoscontacto&idhojadevida=$condecion"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "manifiestos":
		$valores[7]="DELETE FROM manifiestos_viajes WHERE idmani='$id_param'  "; 
		$valores[8]=1; $valores[4]="manifiesto.php"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "cotizaciones":
		$valores[7]="DELETE FROM cotozaciones WHERE cot_id='$id_param'  "; 
		$valores[8]=1; $valores[4]="cotizaciones.php"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "vehiculos":
		$valores[7]="DELETE FROM vehiculo_manif WHERE vehimid='$id_param'  "; 
		$valores[8]=1; $valores[4]="adm_manifiestos.php?funcion=Vehiculos"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "conductores":
		$valores[7]="DELETE FROM conductor_mani WHERE condid='$id_param'  "; 
		$valores[8]=1; $valores[4]="adm_manifiestos.php?funcion=Conductores"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	
	case "DochvTrans":
		$valores[7]="DELETE FROM documentosTransmillas WHERE doctid='$id_param'  "; 
		$valores[8]=1; $valores[4]="hojavidaTransmillas.php?funcion=Conductores"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "preres":
		$valores[7]="DELETE FROM preguntas_transmillas WHERE idpreres='$id_param'  "; 
		$valores[8]=1; $valores[4]="hojavidaTransmillas.php?funcion=Conductores"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "facturascreditos":
		$sel="UPDATE `servicios` SET `ser_numerofactura`=null where `ser_numerofactura`='$condecion'";
		$DB->Execute($sel);	

		$valores[7]="DELETE FROM facturascreditos WHERE idfacturascreditos='$id_param'  "; 
		$valores[8]=1; $valores[4]="informecreditos.php?bandera=1"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
    case "seguimiento_remesas":
		$valores[7]="DELETE FROM seguimiento_remesas WHERE idseguimientoremesas='$id_param'";
		$valores[8]=1; $valores[4]="remesasprogramadas.php"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "borraseguser":
		$partes = explode("_", $id_param);
		// Guardar en variables separadas
		$IdPreo = $partes[0];
		$IdSeg = $partes[1];

		echo$sql="DELETE FROM `pre-operacional` WHERE idpreoperacinal='$IdPreo'";
		$DB->Execute($sql);
		echo$sql1="DELETE FROM seguimiento_user WHERE idseguimiento_user='$IdSeg'";

		$valores[8]=1; $valores[4]="seguimientouser.php"; $id_param=""; $ir=3; $valores[7]=$sql1;
		$tabla=$tabla1;
	break;
	
	case "carpetahvt":
		$valores[7]="DELETE FROM carpetasTransmillas WHERE idcarpeta='$id_param'";
		$valores[8]=1; $valores[4]="hojavidaTransmillas.php"; $id_param=""; $ir=3;
		$tabla=$tabla1;
	break;
	case "General":
		$valores[7]="DELETE FROM $tabla1 WHERE id$tabla1='$id_param'  "; 
		$valores[8]=1; $valores[4]="adm_general.php"; $id_param=""; $ir=2;
		$tabla=$tabla1;
		break;
		case "delete":
			$valores[7]="DELETE FROM $tabla1 WHERE id$tabla1='$id_param'  "; 
			$valores[8]=1;  $id_param=""; $ir=1;
			$tabla=$tabla1;
			break;
			case "idnoticia":
			$valores[7]="DELETE FROM noticia WHERE idnoticia='$id_param'  "; 
			$valores[8]=1;  $id_param=""; $ir=1;
			$tabla=$tabla1;
			break;
	case "Abonos":
		$valores[7]="DELETE FROM abonosguias WHERE idabono='$id_param'  "; 

		$sql1="SELECT `idabono`, `abo_fecha`, `abo_valor`, `abo_idservicio`, `abo_iduser`, `abo_idsede`, `abo_estado` FROM `abonosguias` where idabono=$id_param";
		$DB1->Execute($sql1);
		$rw3=mysqli_fetch_array($DB1->Consulta_ID);	
		if($rw3[6]=='devolucion'){

			$sql3="update  `servicios` set ser_valorabono=ser_valorabono+$rw3[2] where idservicios='$rw3[3]'";
			$DB->Execute($sql3);
	
			$sql4="update  `cuentaspromotor` set cue_abono=cue_abono+$rw3[2] where cue_idservicio='$rw3[3]'";
			$DB->Execute($sql4);
			
		}else{
		$sql3="update  `servicios` set ser_valorabono=ser_valorabono-$rw3[2] where idservicios='$rw3[3]'";
		$DB->Execute($sql3);

		$sql4="update  `cuentaspromotor` set cue_abono=cue_abono-$rw3[2] where cue_idservicio='$rw3[3]'";
		$DB->Execute($sql4);
		}

		$valores[8]=1; $valores[4]="asignar_abonos.php"; $id_param=""; $ir=2;
		$tabla=$tabla1;
		break;
	default:
	
	$valores=$LT->devuelvecampos($tabla, 3, $id_param);
	//print_r($valores);
	break;
}
$condecion=str_replace("_zzz_","&",$condecion);
if($valores[8]==1) { if($DB->Execute($valores[7])){$bandera=2;} else {$bandera=4;} ; }
else {
	
	$bandera=$QL->delete($valores[0], $DB, $valores[3], $id_param, $tabla); 
}
if($ir==1){header("Location: ".$_SERVER["HTTP_REFERER"]); }
elseif($ir==3){
	header ("Location: $valores[4]");
}
else { header ("Location: $valores[4]?bandera=$bandera&condecion=$condecion&tabla=$tabla"); } 

?>
