<?php
require("login_autentica.php");
include("declara.php");
$idUserActual=$_SESSION['usuario_id'];
@$tabla=$_REQUEST["tablaruta"];
if(isset($_POST["condecion"])) {$condecion=$_POST["condecion"]; } else { $condecion=""; }  
if(isset($_POST["nivel"])) {$nivel=$_POST["nivel"]; } else { $nivel=""; } 
if(isset($_POST["id_param"])) {$id_param=$_POST["id_param"]; } else { $id_param=""; } 
 $tabla1=$tabla;
if($condecion=='general'){  $tabla='General'; }
$id_sedes=$_SESSION['usu_idsede'];
$id_nombre=$_SESSION['usuario_nombre'];
$param1;
switch($tabla)
{
	case "seguimientoruta":
		
		$datos=explode("|",$param1);
		$idseguimiento=$datos[0];
		$tipo=$datos[1];
		$direccion=$datos[2];

		$estado='En ruta';

		if($param1!="" and $param1!="0"){
			if($tipo=='opcionruta'){
				$sql = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,seg_fechaestado,seg_fechafinalizo) values('$fechatiempo','$idseguimiento','$direccion','$tipo','$direccion','$id_usuario','$fechatiempo','$fechatiempo')";
			}else{
				$sql = "UPDATE  `seguimientoruta`  SET seg_fechaestado='$fechatiempo',seg_estado='$estado' where idseguimientoruta='$idseguimiento'";

			}
		
			// $sql = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`) values('$fechatiempo','$idservicio','$direccion','$tipo','$estado','$id_usuario')";

		}else{
			$sql = "";
		}
		 $valores[7]=$sql; $valores[4]="inicio.php"; $valores[8]=1; 
	break;
	case "cambiarruta":
		$datos=explode("|",$param1);
		$idservicioanterior=$param3;

		$idseguimiento=$datos[0];
		$tipo=$datos[1];
		$direccion=$datos[2];

		$estado='En ruta';
		
		if($param1!="" and $param1!="0"){
		

			 $sqlcambio = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,seg_descripcion,seg_fechaestado,seg_fechafinalizo) select seg_fecha,seg_idservicio,seg_direccion,seg_tipo,'Cambioruta','$id_usuario','$param2',seg_fechaestado,'$fechatiempo' from seguimientoruta  where idseguimientoruta=$idservicioanterior";
			$DB->Execute($sqlcambio);

			 $sqlsqlupdate = "UPDATE seguimientoruta SET seg_estado='Asignada',seg_fechaestado='' where idseguimientoruta=$idservicioanterior ";
			 $DB->Execute($sqlsqlupdate);

		
			$QL->addDocumento1($_FILES["param4"], 1, "seguimientoruta", $idservicioanterior, "", $DB);
			if($tipo=='opcionruta'){
				$estado='completado';
				$sql = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,seg_fechaestado,seg_fechafinalizo) values('$fechatiempo','$idseguimiento','$direccion','$tipo','$direccion','$id_usuario','$fechatiempo','$fechatiempo')";
			}else{
				//$sql = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,seg_fechaestado) values('$fechatiempo','$idservicio','$direccion','$tipo','$direccion','$id_usuario','$fechatiempo')";
				$sql = "UPDATE  `seguimientoruta`  SET seg_fechaestado='$fechatiempo',seg_estado='$estado' where idseguimientoruta='$idseguimiento'";

			}
		}else{
			$sql = "";
		}
		 $valores[7]=$sql; $valores[4]="inicio.php"; $valores[8]=1; 
	break;
	default:
		$valores=$LT->devuelvecampos($tabla, 1, "");

	break;
}

if($valores[8]==1) {
	
	if($valores[7]==1){
		$bandera=1;
	}else{
		if($DB->Execute($valores[7])){$bandera=1;} else {$bandera=4;} ;
	}
	 
}
else {$bandera=$QL->insert($valores[0], $valores[1], $valores[6], $valores[5], $DB, $tabla, 1); //funcion insert 

 }

header ("Location: $valores[4]?bandera=$bandera&id_param=$id_param&condecion=$condecion&tabla=$tabla");
?>