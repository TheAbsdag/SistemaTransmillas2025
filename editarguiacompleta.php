<?php
require("login_autentica.php");
$id_nombre=$_SESSION['usuario_nombre'];
@$id_usuario=$_REQUEST["id_usuario"];
@$dir=$_REQUEST["dir"];
@$estado=$_REQUEST["estado"];
$precioinicialkilos=$_SESSION['precioinicial'];

$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();

$DB1 = new DB_mssql;
$DB1->conectar();

// $_REQUEST["param99"];
 $cond="";

 $kilos=$param26;
 $sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;";
 $DB->Execute($sqlprecios);
 $confipre=mysqli_fetch_row($DB->Consulta_ID);
 $idprecios=$confipre[0];
 if($idprecios==0 or $idprecios==''){
	$idprecios=1;
}

 if($dir=='devoluciones.php'){

	$sql3="UPDATE `guias` SET `gui_userdevolucion`='$id_nombre',`gui_fechadevolucion`='$fechatiempo' WHERE `gui_idservicio`='$id_param'";
	$DB->Execute($sql3);

	 $sql="UPDATE `servicios` SET ser_idverificadopeso=1,ser_llego='SI',`ser_estado`='8' WHERE `idservicios`=$id_param";

}

$param5=$param5."&".$param51."&".$param19."&".$param20."&".$param23."&";
$param5 = str_replace('&0&','&&', $param5);


 	 $sql11="UPDATE `clientesservicios` SET  `cli_nombre`='$param6', `cli_telefono`='$param2',`cli_idciudad`='$param4',
	 `cli_direccion`='$param5',  `cli_idclientes`='$id_param', `cli_principal`='1' where `idclientesdir`='".$_REQUEST['id_param0']."'";
	$DB->Execute($sql11);

$param10=$param10."&".$param101."&".$param21."&".$param22."&".$param24."&";
$param10 = str_replace('&0&','&&', $param10);

	$sql3="UPDATE `guias` SET `gui_useredita`='$id_nombre',`gui_fechaedita`='$fechatiempo' WHERE `gui_idservicio`='".$_REQUEST['id_param2']."'";
	$DB->Executeid($sql3);


		$param177=str_replace(".","", $param17);
		$param166=str_replace(".","", $param16);
		$param188=str_replace(".","", $param18);

/* 		$sql="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param166' and `pre_final`>='$param166'";
		$DB->Execute($sql);
		$porprestamo=$DB->recogedato(0);
		$dosporcentaje=explode(" ",$porprestamo);

		if(@$dosporcentaje[1]=='%'){
			$porprestamo=($param166*@$dosporcentaje[0])/100;
		} */

		if($param37=='1000'){

			$param114=str_replace(".","", $param111);

		}
		else{

			@$precio=valorguia($DB,$param4,$param11,$param37,$param28,$param26,$param27,$precioinicialkilos);
			@$porprestamo=porprestamo($DB,$param166);
			$pordeclarado=(intval($param188)*1)/100;
			$param114=$precio;
		}





		if($param28==2){

		   echo$sql3="SELECT `pre_preciokilo`,`con_precios` FROM `precios_credito`  inner join `configuracionkilos` on con_idprecioskilos=idprecioscredito WHERE con_tipo='Credito' and `pre_idciudadori`='$param4'  and `pre_idciudades`='$param11' and pre_tiposervicio='$param37' and pre_idcredito='$param113' and con_idprecios='$idprecios' and pre_estado=1";
		  $DB->Execute($sql3);
		  $rw2=mysqli_fetch_row($DB->Consulta_ID);

		  @$preciokilo=$rw2[0];
		  @$precioadicional=$rw2[1];

		  $kilosvolumen=$param26+$param27;

		  if($param26>$precioinicialkilos){

			  @$precio1=($kilosvolumen-$precioinicialkilos)*$precioadicional;
			  @$param114=$preciokilo+$precio1;

		  }else {

			@$precio1=$param27*$precioadicional;
			@$param114=$preciokilo+$precio1;

		  }
		  if($param114==''){
			$param114=0;
		  }

		}


		$sql="SELECT idusuarios,gui_fecharecogio FROM `guias` inner join usuarios on gui_recogio=usu_nombre WHERE gui_idservicio='".$_REQUEST['id_param2']."'";
		$DB->Execute($sql);
		$rw21=mysqli_fetch_row($DB->Consulta_ID);
		$userid=$rw21[0];
		$fehcarecorrida=$rw21[1];

 		$sql="SELECT `cue_idservicio` FROM `cuentaspromotor` where cue_idservicio='".$_REQUEST['id_param2']."'";
		$DB->Execute($sql);
		$vercuentas=$DB->recogedato(0);
		if($vercuentas=="" and $param34!=''){


			$sql="SELECT idusuarios,gui_fecharecogio FROM `guias` inner join usuarios on gui_recogio=usu_nombre WHERE gui_idservicio='".$_REQUEST['id_param2']."'";
			$DB->Execute($sql);
			$rw21=mysqli_fetch_row($DB->Consulta_ID);
			$userid=$rw21[0];
			$fehcarecorrida=$rw21[1];


			$sql2="INSERT INTO `cuentaspromotor`(`cue_idservicio`,`cue_idoperador`,cue_numeroguia,cue_fecharecogida)
		   VALUES ('".$_REQUEST['id_param2']."','$userid','$param34','$fehcarecorrida')";
		   $DB1->Execute($sql2);
		}



		if($estado==10){

			//codigo para porcentajes de las empresas
			/* $tipopagosguias=$tipopago[$param28];
			$sql6="SELECT `idporcentajespaquetes`, `por_porcentaje`,por_porcentajeempresa FROM `porcentajespaquetes`  WHERE por_idsede='$param11' and por_idsededestino='$param4' and por_tiposervicio='$tipopagosguias' and '$kilosvolumen'>=por_kilosgramosmin and '$kilosvolumen'<=por_kilogramosmaximo and (por_idpaquete='$param37' or por_idpaquete='') order by idporcentajespaquetes desc limit 1";
			$DB1->Execute($sql6);
			$rw5=mysqli_fetch_row($DB1->Consulta_ID);
			$porcentaje=$rw5[1];
			$porcentajeempresa=$rw5[2];
			$valorporcentaje=($param114*$porcentaje)/100;
			$valorporempresa=($param114*$porcentajeempresa)/100;
			$cond0=",cue_porcentaje='$porcentaje',cue_porempresa='$porcentajeempresa',cue_valorporcantaje='$valorporcentaje',cue_valorporempresa='$valorporempresa'";
		 */

			$cond0="";

			$sql="SELECT idusuarios,gui_fechaentrega FROM `guias` inner join usuarios on gui_userecomienda=usu_nombre WHERE gui_idservicio='".$_REQUEST['id_param2']."'";
			$DB->Execute($sql);
			$rw22=mysqli_fetch_row($DB->Consulta_ID);
			$useridfinal=$rw22[0];
			$fehcaentrega=$rw22[1];

			$cond10=",cue_idoperentrega='$useridfinal',cue_fecha='$fehcaentrega'";

		}
	
		$imagetrans="SELECT `pag_img_transaccion` FROM `pagoscuentas` where pag_idservicio='".$_REQUEST['id_param2']."'";
		$DB1->Execute($imagetrans);
		$imgtrans=$DB1->recogedato(0);

		if ($_FILES['param120']['error'] === UPLOAD_ERR_OK) {
			$img_transaccion = $_FILES['param120']['name'];
			
			$rutaArchivo = 'img_transacciones/' . $img_transaccion;
	
			if (move_uploaded_file($_FILES['param120']['tmp_name'], $rutaArchivo)) {
				echo 'Imagen guardada correctamente';
			} else {
				echo 'Error al guardar la imagen';
				$img_transaccion = "";
			}
			} else {
				$img_transaccion = "$imgtrans";
			}

		$sql7="UPDATE `pagoscuentas` SET pag_img_transaccion='$img_transaccion' where pag_idservicio='".$_REQUEST['id_param2']."'";
		$DB1->Execute($sql7);

$sql22="UPDATE `cuentaspromotor` SET  `cue_abono`='$param177', `cue_porprestamo`='$porprestamo', `cue_prestamo`='$param166',
	 `cue_vrdeclarado`='$param188',`cue_pordeclarado`='$pordeclarado',cue_tipopago='$param15',cue_tipoevento='$param28',cue_valorflete='$param114',cue_pendientecobrar='$param112',cue_idciudaddes='$param11',cue_idciudadori='$param4',cue_kilostotal='$kilosvolumen' $cond0 $cond10 where cue_idservicio='".$_REQUEST['id_param2']."'";
		$DB1->Execute($sql22);


		$nfactura="SELECT `ser_numerofactura` FROM `servicios` where `idservicios`='".$_REQUEST['id_param2']."'";
		$DB1->Execute($nfactura);
		$confactura=$DB1->recogedato(0);

		if ($confactura=="" or $confactura== null ) {
			$condPC=", ser_pendientecobrar='$param112'";
		}else {
			$condPC="";
		}


		$sql1="UPDATE `servicios` SET `ser_iddocumento`='$param7',`ser_telefonocontacto`='$param8',`ser_destinatario`='$param9',`ser_direccioncontacto`='$param10',`ser_ciudadentrega`='$param11',`ser_tipopaquete`='$param12',`ser_paquetedescripcion`='$param13',`ser_fechaentrega`='$param14',ser_prioridad='$param15',`ser_valorprestamo`='$param16',`ser_valorabono`='$param177',`ser_valorseguro`='$param18'
		,ser_guiare='$param34',ser_fecharegistro='$param35',ser_peso='$param26',ser_volumen='$param27',ser_piezas='$param30',ser_descripcion='$param31',ser_verificado='$param32',ser_tipopaq='$param33',ser_clasificacion='$param28',ser_valor='$param114',ser_valorpendiente='$param36' $cond $condPC WHERE `idservicios`='".$_REQUEST['id_param2']."'";
		$DB->Execute($sql1);

		$sql21="UPDATE guias  SET gui_tiposervicio='$param37'  where gui_idservicio='".$_REQUEST['id_param2']."'";
		$DB->Execute($sql21);

		if($param28==2){

			$sql23="DELETE FROM rel_sercre WHERE idservicio='".$_REQUEST['id_param2']."'";
			$DB1->Execute($sql23);

			$sql5="SELECT `cre_nombre` FROM `creditos` WHERE `idcreditos`=$param113";
			$DB1->Execute($sql5);
			$nombrecredito=$DB1->recogedato(0);

			$sql32="INSERT INTO rel_sercre (`idservicio`, `rel_nom_credito`) VALUES ('".$_REQUEST['id_param2']."','$nombrecredito')";
			$DB1->Execute($sql32);
		}

 if($dir=='devoluciones.php'){

	$sql3="UPDATE `guias` SET `gui_userdevolucion`='$id_nombre',`gui_fechadevolucion`='$fechatiempo' WHERE `gui_idservicio`='".$_REQUEST['id_param2']."'";
	$DB->Execute($sql3);


	 $sql="UPDATE `servicios` SET ser_idverificadopeso=1,ser_llego='SI',`ser_estado`='8' WHERE `idservicios`='".$_REQUEST['id_param2']."'";
	 $DB->Execute($sql);
}


	$DB->cerrarconsulta();
	$DB1->cerrarconsulta();
	$nivel_acceso=$_SESSION['usuario_rol'];
	// if($nivel_acceso==1){
	// 	echo$sql3;
	// 	// header ("Location:$dir?bandera=1&pru=$sql3");
	// }else{
header ("Location:$dir?bandera=1");
// }

?>