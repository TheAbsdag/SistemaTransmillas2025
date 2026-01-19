<?php
// require("login_autentica.php");
// $id_usuario=$_SESSION['usuario_id'];
// $id_nombre=$_SESSION['usuario_nombre'];
// $nivel_acceso=$_SESSION['usuario_rol'];
// $rol_nombre=$_SESSION['rol_nombre'];
// $id_ciudad=$_SESSION['usu_idsede'];
// $id_sedes=$_SESSION['usu_idsede'];

require("connection/conectarse.php");
require("connection/arrays.php");
require("connection/funciones.php");
require("connection/funciones_clases.php");
require("connection/sql_transact.php");
require("connection/llenatablas.php");
require("connection/PasswordHash.php");
require("definirvar.php");

$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$FB = new funciones_varias;
$QL = new sql_transact;
$LT = new llenatablas;

// if(isset($_REQUEST["param1"])) {$param1=$_REQUEST["param1"]; } else { $param1=""; } 
// if(isset($_REQUEST["cond"])) {$cond=$_REQUEST["cond"]; } else { $cond=""; } 
// if(isset($_REQUEST["para"])) {$para=$_REQUEST["para"]; } else { $para=""; } 
// if(isset($_REQUEST["nombre"])) {$nombre=$_REQUEST["nombre"]; } else { $nombre=""; } 

	$valortservicio=$_REQUEST["valortservicio"];
	$idcredito=$_REQUEST["cliente"];
	$sql33="SELECT tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`=$valortservicio"; 
	$DB->Execute($sql33);
	$rw7=mysqli_fetch_row($DB->Consulta_ID); 
	
	if($valortservicio==0 and $param1!=1){ // tipo servicio normal y no credito
	
		 $sql="SELECT `idprecios`, `pre_kilo`, `pre_adicional` FROM `precios` 
		where pre_idciudadori='$param2' and pre_idciudaddes='$param3' and pre_tiposervicio='$valortservicio'  AND pre_estado = 1  ";
		$DB->Execute($sql);
	   $rw = mysqli_fetch_row($DB->Consulta_ID); 
	   
	   @$preciokilo=$rw[1];
	   @$precioadicional=$rw[2];
	   //@$serciudad=$param11;
	}else if($rw7[0]>=10 and $param1!=1){ //carga especial no credito 
			

		@$preciokilo=$rw7[0];
		@$precioadicional=$rw7[1];
	
	}else
	//{
	// 	$sql="SELECT `idprecios`, `pre_kilo`, `pre_adicional` FROM `precios` 
	// 	where pre_idciudadori='$param2' and pre_idciudaddes='$param3' and pre_tiposervicio='$valortservicio' ";
	// 	$DB->Execute($sql);
	//    $rw = mysqli_fetch_row($DB->Consulta_ID); 
	   
	//    @$preciokilo=$rw[1];
	//    @$precioadicional=$rw[2];
	// }	   
	//    $kilosvolumen=$param7+$param8;	   
	//    if($param7>5){
	// 	   @$precio1=($kilosvolumen-5)*$precioadicional;
	// 	   @$precio=$preciokilo+$precio1;
	//    }else {
	// 	@$precio1=$param8*$precioadicional;
	// 	@$precio=$preciokilo+$precio1;
	//    }
	   
	//    $param4=str_replace(".","", $param4);
	//    $param5=str_replace(".","", $param5);
	//    $param6=str_replace(".","", $param6);
	   
	// 		$sql="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param4' and `pre_final`>='$param4'";
	// 		   $DB->Execute($sql);
	// 		   $porprestamo=$DB->recogedato(0);
		   
	// 		   $dosporcentaje=explode(" ",$porprestamo); 
	   
	// 		   if(@$dosporcentaje[1]=='%'){
	// 			   $porprestamo=($param4*@$dosporcentaje[0])/100;
	// 		   }
	   
	// 		   $pordeclarado=(intval($param6)*1)/100;
	   
	   
	// 	$valorapagar=$precio+$pordeclarado+$porprestamo;

	// 	echo '<table class="table table-hover"><tr bgcolor="#074F91" class="tittle3">
	// 	<td colspan="1" align="center">Minima(5k)</td>
	// 	<td colspan="1"  align="center">Kilo Adicional</td>
	// 	<td colspan="1"  align="center">%Seguro</td>
	// 	<td colspan="1"  align="center">%Prestamo</td>
	// 	<td colspan="1" align="center">Valor</td></tr>';
	// 	$color="#EFEFEF";
	// 	echo "<tr bgcolor='$color' class='text' style='background-color:$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' 	
	//  onmouseout='this.style.backgroundColor=\"$color\"'>";
	//  echo "<td>$preciokilo</td><td>$precioadicional</td><td>$pordeclarado</td><td>$porprestamo</td><td>".$valorapagar."</td></tr></table>";


	//	$FB->llena_texto("",111, 118, $DB, "", "", $valorapagar, 6, 0);
		   



	{
		$sql="SELECT `idprecios`, `pre_kilo`, `pre_adicional` FROM `precios` 
		where pre_idciudadori='$param2' and pre_idciudaddes='$param3' and pre_tiposervicio='$valortservicio'  AND pre_estado = 1 ";
		$DB->Execute($sql);
	   $rw = mysqli_fetch_row($DB->Consulta_ID); 
	   
	   @$preciokilo=$rw[1];
	   @$precioadicional=$rw[2];
	}	   
	   $kilosvolumen=$param7+$param8;	   
	   if($param7>5){
		   @$precio1=($kilosvolumen-5)*$precioadicional;
		   @$precio=$preciokilo+$precio1;
	   }else {
		@$precio1=$param8*$precioadicional;
		@$precio=$preciokilo+$precio1;	
	   }
	   
	   $param4=str_replace(".","", $param4);
	   $param5=str_replace(".","", $param5);
	   $param6=str_replace(".","", $param6);
	   
			$sql="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param4' and `pre_final`>='$param4'";
			   $DB->Execute($sql);
			   $porprestamo=$DB->recogedato(0);
		   
			   $dosporcentaje=explode(" ",$porprestamo); 
	   
			   if(@$dosporcentaje[1]=='%'){
				   $porprestamo=($param4*@$dosporcentaje[0])/100;
			   }
	   
			   $pordeclarado=(intval($param6)*1)/100;
	   
	   
		$valorapagar=$precio+$pordeclarado+$porprestamo;

		echo '<table class="table table-hover"><tr bgcolor="#074F91" class="tittle3">
		<td colspan="1" align="center">Minima(5k)</td>
		<td colspan="1"  align="center">Kilo Adicional</td>
		<td colspan="1"  align="center">%Seguro</td>
		<td colspan="1"  align="center">%Prestamo</td>
		<td colspan="1" align="center">Valor</td></tr>';
		$color="#EFEFEF";
		echo "<tr bgcolor='$color' class='text' style='background-color:$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' 	
	 onmouseout='this.style.backgroundColor=\"$color\"'>";
	 echo "<td>$preciokilo</td><td>$precioadicional</td><td>$pordeclarado</td><td>$porprestamo</td><td>".$valorapagar."</td></tr></table>";
