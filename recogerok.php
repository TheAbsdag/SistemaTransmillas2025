<?php
require("login_autentica.php"); 
$id_nombre=$_SESSION['usuario_nombre'];
$id_usuario=$_SESSION['usuario_id'];
$nivel_acceso=$_SESSION['usuario_rol'];
$id_sedes=$_SESSION['usu_idsede'];
$precioinicialkilos=$_SESSION['precioinicial'];

$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
//echo $param1;

if(isset($_REQUEST["dir"])) {$dir=$_REQUEST["dir"]; } else { $dir=""; } 
 $sqlcambio=$_POST["cambios"];
 $cambios= $sqlcambio[0];

if($param1=='RECOGIDO'){
	$imprimir='Recogida';

$kilos=$param10+$param20;
if($param27!=''){
	$planilla=$param27;
}else{
	 $sql="SELECT `idconfac`, `idconsecutivo`, `idresolucion`, `prefijo`,inner_sedes FROM `conf_fac`  inner join ciudades on idciudad=inner_sedes WHERE idciudades='$param13'";	
	$DB1->Execute($sql);
	$rw1=mysqli_fetch_array($DB1->Consulta_ID);	

	$planilla="$rw1[3]$rw1[1]";
	$idconsecutivo=$rw1[1]+1;


	if($idconsecutivo>=10){
		$sql2="UPDATE conf_fac inner join ciudades on idciudad=inner_sedes  SET `idconsecutivo`=$idconsecutivo  WHERE idciudades='$param13'";	
		$DB->Execute($sql2);
	}else{
		$planilla="";
	}
	
}

if($param25==''){
	$param25=$planilla;
}
	$cond="";
	$cond1="";
	$valortotal=0; 
	if($param12=='')
	{
		$param12=0;
	}
	if($param20=='')
	{
		$param20=0;
	}

	if($param8==1){  // dejar como pendiente por cobrar
		$cond=",`ser_peso`='$param10', `ser_pendientecobrar`='$param12'";

	} else if($param8==2){   //creditos y valor del credito 
		//$cond=",ser_pendientecobrar=2,`ser_valorpendiente`='$param10'";
		$cond=",ser_pendientecobrar=2 ,`ser_peso`='$param10'";
		$param12=2;
	}else if($param8==3){   //pendientes x cobar.
		$cond=",`ser_peso`='$param10'";
		//$cond=",ser_pendientecobrar=3,`ser_valorpendiente`='$param10'";
	}
	else {

		$cond="";
		//$param18="";
	}

	if($param17>0 and $param12==1){
		$cond=",ser_pendientecobrar=4,`ser_valorpendiente`='$param17'";
		$param12=4;

	}else if($param17>0){
		$cond=",ser_pendientecobrar=5,`ser_valorpendiente`='$param17'";
		$param12=5;
	}
	


	$kilos=$param10;
	$idprecios=0;
	if($kilos>0){
		$sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;"; 
		$DB->Execute($sqlprecios);
		$confipre=mysqli_fetch_row($DB->Consulta_ID); 
		$idprecios=$confipre[0];
		
	}
	
	if($idprecios==0 or $idprecios==''){
		$idprecios=1;
	}

	 $sql32="Select gui_tiposervicio from guias WHERE `gui_idservicio`='$id_param2'"; 
	$DB->Execute($sql32);
	$rw6=mysqli_fetch_row($DB->Consulta_ID); 

	$sql33="SELECT tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`='$rw6[0]'"; 
	$DB->Execute($sql33);
	$rw7=mysqli_fetch_row($DB->Consulta_ID); 

	if($param34=='1000'){
		$cond1="";
		$valortotal=str_replace(".","", $param11);
	}	
	elseif($param8!=2){ //  tipodepago diferente a 2

		if($rw7[0]>=1){ //preciones generales
		
			if($rw7[0]!=''){
				  if($param10>$precioinicialkilos){  
					$varor1=$rw2[0];  
					$valor2=($param10+$param20-$precioinicialkilos)*$rw7[1];  
					$valortotal=$varor1+$valor2;  
				}else { 
					$varor1=$rw2[0];  
					$valor2=$param20*$rw7[1];  
					$valortotal=$varor1+$valor2;  
				  }

			}else{
				$valortotal=0;
			}
		}
		elseif($param11>=1 and $kilos>0){  //si configuraron precios y la opcion es contado
			
			 $sql3="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios 
			 WHERE con_tipo='normal'  and  `pre_idciudadori`='$param13'  and `pre_idciudaddes`='$param9'  and pre_tiposervicio='$rw6[0]' and con_idprecios='$idprecios'";
				$DB->Execute($sql3);
				$rw2=mysqli_fetch_row($DB->Consulta_ID); 
					$kilos=$param10+$param20;
					if($param10>$precioinicialkilos){  
						$varor1=$rw2[1];  
						$valor2=($param10+$param20-$precioinicialkilos)*$rw2[2];  
						$valortotal=$varor1+$valor2;  
					}else { 
						$varor1=$rw2[1];  
						$valor2=$param20*$rw2[2];  
						$valortotal=$varor1+$valor2;  
					  }
		}else{

			$kilos=0;
			$valortotal=0;	
		}		
	
		$cond1=",ser_valor='$valortotal', `ser_piezas`='$param2'";

		
	}elseif($param8==2){  //diferente de tiposervicionormal y credito.

			$kilos=0;
			$valortotal=0;
			$cond1=",ser_valor='$valortotal', `ser_piezas`='$param2'";
	}
	

		 $param14=str_replace(".","", $param54);
		
		// prestamos...
		if($param16!=''){
			$param16=str_replace(".","", $param16);
		}else{
			$param16=0;
		}
		
		$param6=str_replace(".","", $param6);
		if($param16!=''){
			 $sql="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param16' and `pre_final`>='$param16'";
			$DB->Execute($sql);
			$porprestamo=$DB->recogedato(0);
			$dosporcentaje=explode(" ",$porprestamo); 
	
			if(@$dosporcentaje[1]=='%'){
				$porprestamo=($param16*@$dosporcentaje[0])/100;
			}
			
		}else {
			$porprestamo=0;
		}

		$pordeclarado=(intval($param6)*1)/100;

	$sql21="DELETE FROM `cuentaspromotor` WHERE  cue_idservicio=$id_param2";
	$DB1->Execute($sql21);	
	
	if($nivel_acceso==1){
		$fechatiempo=$param28;
		$sql5="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$param18' and  (usu_estado=1 or usu_filtro=1)";
		$DB->Execute($sql5);
		$id_nombre=$DB->recogedato(1);
		
		//$param18=$id_usuario;
	} else if($nivel_acceso!=3 and $nivel_acceso!=1){

		$param18=$id_usuario;

	}

	$pagos=explode('|',$param30);
	$tipopago=$pagos[0];
	$cuenta=$pagos[1];
	$namepago=$pagos[2];
	if($param8==1){
		$estadop='Contado';
	}elseif($param8==3){
		$estadop='Al Cobro';

	}

		 $sql2="INSERT INTO `cuentaspromotor`(`cue_idservicio`,`cue_idoperador`,`cue_abono`, `cue_porprestamo`, `cue_prestamo`,
	 `cue_vrdeclarado`,`cue_pordeclarado`,  `cue_valorflete`,  `cue_tipopago`, `cue_pendientecobrar`,  `cue_fecha`,`cue_valpagar`,cue_estado, `cue_idciudadori`, `cue_idciudaddes`, `cue_tipoevento`, `cue_numeroguia`, `cue_fecharecogida`,`cue_transferencia`,cue_kilostotal) 
	VALUES ('$id_param2','$param18','$param14',$porprestamo,'$param16','$param6','$pordeclarado','$valortotal','$param15','$param12','$fechatiempo','$param17','4','$param13','$param9','$param8','$planilla','$fechatiempo','$namepago',$kilos)";
	$DB1->Execute($sql2);

	 $sql3="UPDATE `guias` SET `gui_recogio`='$id_nombre',`gui_fecharecogio`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
		$DB->Execute($sql3);
	if($param29==''){ $param29=0;}

	

	if($param8==1 and $tipopago>1){


		

		if ($_FILES['param40']['error'] === UPLOAD_ERR_OK) {
		$img_transaccion = $_FILES['param40']['name'];
		
		$rutaArchivo = 'img_transacciones/' . $img_transaccion;

		if (move_uploaded_file($_FILES['param40']['tmp_name'], $rutaArchivo)) {
			echo 'Imagen guardada correctamente';
		} else {
			echo 'Error al guardar la imagen';
			$img_transaccion = "";
		}
		} else {
			$img_transaccion = "";
		}

		$sql51="DELETE FROM `pagoscuentas` WHERE  pag_idservicio=$id_param2";
		$DB1->Execute($sql51);	

		$sql5="INSERT INTO `pagoscuentas`(`pag_tipopago`,`pag_cuenta`, `pag_valor`, `pag_idoperario`, `pag_idservicio`,`pag_guia`, `pag_estado`, `pag_fecha`,`pag_img_transaccion`) VALUES ('$tipopago','$cuenta','$param11','$id_usuario','$id_param2','$planilla','$estadop','$fechatiempo','$img_transaccion')";
		$DB1->Execute($sql5);
	}
// para guardar imagen del paquete o servicio
	if (is_uploaded_file($_FILES['param87']['tmp_name'])){
		// $imagen1 = md5(date("Y-m-d-H-i-s").$param3).".jpg";
		$nombreArchivo1 = $_FILES["param87"]["name"];
		$foto = date("Y-m-d-H-i-s").$nombreArchivo1;
	   
		move_uploaded_file($_FILES['param87']['tmp_name'], "./imgServicios/".$foto);
	 }else{
		 $foto = "";
	 }
// agregar campo a base de datos 
	 $sql1="UPDATE `servicios` SET `ser_consecutivo`='$planilla',`ser_resolucion`='$rw1[2]',`ser_recogida`='$param1',  `ser_paquetedescripcion`='$param3', `ser_valorseguro`='$param6', `ser_horaentrega`='$param7', `ser_clasificacion`='$param8',`ser_fechafinal`='$fechatiempo',`ser_fechaasignacion`='$fechatiempo',`ser_estado`='4',ser_devolverreci='$param29',ser_tipopaq='$param21',ser_verificado='$param19',ser_volumen='$param20',ser_guiare='$param25',ser_descripcion='$param26',ser_idresponsable='$param18',ser_img_recog='$foto'  $cond1 $cond WHERE `idservicios`=$id_param2";
	if($nivel_acceso==3){
		
		// $dir="ticketfactura.php";
		// $pagina2="inicio.php";
		$dir="firmadigital.php";
		$pagina2="inicio.php";
		
	}else {
		// $dir="recogerpaquete.php";
		// $pagina2="recogerpaquete.php";
		$dir="firmadigital.php";
		$pagina2="recogerpaquete.php";	
		
	}

	 // Preparar la consulta SQL para insertar los datos en la tabla firma_clientes
	$sql8 = "INSERT INTO firma_clientes (id_guia, tipo_firma, nombre, numero_documento,correo_electronico, telefono,enviar_whatsapp,foto) 
	VALUES ('$id_param2', 'Recogida', '$param82', '$param83', '$param84', '$param85', '$param86','')";
	$idfirma=$DB->Executeid($sql8); 

	$sql7="UPDATE `seguimientoruta` SET `seg_guia`='$planilla',`seg_estado`='completado',`seg_fechafinalizo`='$fechatiempo' WHERE `seg_idservicio`='".$_REQUEST['id_param2']."' AND  seg_tipo='Recogida' and seg_estado!='Cambioruta' AND seg_fecha like '%$fechaactual%'";
	$DB->Executeid($sql7); 


	$sql12 = "SELECT idservicios,ser_estado,cli_telefono,ser_consecutivo FROM `serviciosdia` WHERE  idservicios = '$id_param2'";			
	$DB1->Execute($sql12);
	$rw12 = mysqli_fetch_array($DB1->Consulta_ID);
	
	
	if ($rw12 === false) {
	}else {

			$numguia=$planilla;
			$telefono=$rw12[2];
			$idservicio=$id_param2;
			
			 enviarAlertaWhat($numguia,$telefono,2,$idservicio);
			// enviarAlertaWhat($numguia,"3160490959",2,$idservicio);

		
	}
	if($param8==2){
		$estadop='Credito';
	
	}

}
else if($param1=='NO RECOGIDO'){
	
	if($nivel_acceso==3){
			
			$dir="asignaciones.php";
			$pagina2="asignaciones.php";
		}else {

			$dir="recogerpaquete.php";
			$pagina2="recogerpaquete.php";
			$cond3=",ser_idresponsable='$id_usuario'";
		}

		$sql2="DELETE FROM `cuentaspromotor` WHERE  cue_idservicio=$id_param2";
		$DB1->Execute($sql2);	
		
		 $sql3="UPDATE `guias` SET `gui_recogio`='$id_nombre',`gui_fecharecogio`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
			$DB->Execute($sql3);
			$descripcion=$id_nombre.": ".$param2;
			$sql1="UPDATE `servicios` SET ser_esatdollamando='',`ser_recogida`='$param1', `ser_motivo`='$descripcion',ser_descllamada='$descripcion',`ser_estado`='5',`ser_fechaasignacion`='$fechatiempo' $cond3 WHERE `idservicios`=$id_param2";
			
			$sql7="UPDATE `seguimientoruta` SET `seg_estado`='NO Recogida',seg_tipo='NO Recogida', `seg_fechafinalizo`='$fechatiempo',`seg_descripcion`='$descripcion' WHERE `seg_idservicio`='$id_param2' AND  seg_tipo='Recogida' and seg_estado!='Cambioruta' AND seg_fecha like '%$fechaactual%'";
			$DB->Executeid($sql7); 
		

			// $sql12 = "SELECT idservicios,ser_estado,	ser_telefonocontacto,ser_consecutivo FROM `servicios` WHERE  idservicios = '$id_param2'";			
			$sql12 = "SELECT idservicios,ser_estado,cli_telefono,ser_consecutivo,cli_direccion FROM serviciosdia   WHERE idservicios='$id_param2'";
			$DB1->Execute($sql12);
			$rw12 = mysqli_fetch_array($DB1->Consulta_ID);
			
			
			if ($rw12 === false) {
			}else {

					$numguia=$rw12[3];
					$telefono=$rw12[2];
					$idservicio=$id_param2;
					$direccion=$rw12[4];
					$texto=$direccion."  '".$descripcion;
					enviarAlertaWhat($numguia,$telefono,3,$texto);
					// enviarAlertaWhat($numguia,"3160490959",3,$texto);
					
			}
}
else if($param1=='EDITAR DATOS'){
	
 	$param5=$param5."&".$param51."&".$param19."&".$param20."&".$param23."&";  
$param5 = str_replace('&0&','&&', $param5);
	
	if($_REQUEST['encomiendas']==0){
	 $sql11="UPDATE `clientesservicios` SET  `cli_nombre`='$param6', `cli_telefono`='$param2',`cli_idciudad`='$param4', 
	 `cli_direccion`='$param5' where `idclientesdir`='".$_REQUEST['id_param0']."'";
	$DB->Execute($sql11);
	}

	$param10=$param10."&".$param101."&".$param21."&".$param22."&".$param24."&"; 
	$param10 = str_replace('&0&','&&', $param10);	
	 $sql1="UPDATE `servicios` SET `ser_telefonocontacto`='$param8',`ser_destinatario`='$param9',`ser_direccioncontacto`='$param10',`ser_ciudadentrega`='$param11' WHERE `idservicios`='".$_REQUEST['id_param2']."'";
	$DB->Execute($sql1);

    $sql3="UPDATE `guias` SET `gui_useredita`='$id_nombre',`gui_fechaedita`='$fechatiempo' WHERE `gui_idservicio`='".$_REQUEST['id_param2']."'";
	$DB->Executeid($sql3); 



	
	if($dir!=''){

	$pagina2=$dir;

	}else if($nivel_acceso==3){
		
		$dir="inicio.php";
		$pagina2="inicio.php";
		
	}else  if($id_param1=='recogidas') {
		$dir="recogidas.php";
		$pagina2="recogidas.php";
		
	}else  {
		$dir="recogerpaquete.php";
		$pagina2="recogerpaquete.php";
		
	}

		
}
else if($param1=='ENTREGADO'){

	$iduserentrega=$_REQUEST["iduserentrega"];
	$id_usuario2=$id_usuario;
	$id_nombre2=$id_nombre;
	$porcobrar=$_REQUEST["porcobrar"];
	$param30=$_REQUEST["param30"];
	$kilosvolumen=$_REQUEST["param20"];
	$imprimir='Entrega';

	if($param30!="0"){
		$pagos=explode('|',$param30);
		$tipopago=$pagos[0];
		$cuenta=$pagos[1];
		$namepago=$pagos[2];
	
	  $tranf=",cue_transferencia='$namepago' ";	
		
	}else{
		$tipopago=0;
		$tranf=""; 
	}
	

		$tipopagosguias=$param10;
		$sql6="SELECT `idporcentajespaquetes`, `por_porcentaje`,por_porcentajeempresa FROM `porcentajespaquetes`  WHERE por_idsede='$param9' and por_idsededestino='$param22' and por_tiposervicio='$tipopagosguias' and '$kilosvolumen'>=por_kilosgramosmin and '$kilosvolumen'<=por_kilogramosmaximo and (por_idpaquete='$param21' or por_idpaquete='') order by idporcentajespaquetes desc limit 1";
		$DB1->Execute($sql6);
		$rw5=mysqli_fetch_row($DB1->Consulta_ID);
		$porcentaje=$rw5[1];
		$porcentajeempresa=$rw5[2];
		$valorporcentaje=($param114*$porcentaje)/100;
		$valorporempresa=($param114*$porcentajeempresa)/100;
		$cond0=",cue_porcentaje='$porcentaje',cue_porempresa='$porcentajeempresa',cue_valorporcantaje='$valorporcentaje',cue_valorporempresa='$valorporempresa'";
		
		if (is_uploaded_file($_FILES['param87']['tmp_name'])){
			// $imagen1 = md5(date("Y-m-d-H-i-s").$param3).".jpg";
			$nombreArchivo1 = $_FILES["param87"]["name"];
			$foto = date("Y-m-d-H-i-s").$nombreArchivo1;
		   
			move_uploaded_file($_FILES['param87']['tmp_name'], "./imgServicios/".$foto);
		 }else{
			 $foto = "";
		 }

	if($porcobrar==0){

		if($iduserentrega==''){

			$iduserentrega=$id_usuario2;
		}
		if($nivel_acceso==1){

			$fechatiempo=$param28;
			$fechaactual=$param28;

			$sql5="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$iduserentrega'";
			$DB->Execute($sql5);
			$id_nombre2=$DB->recogedato(1);
			
			$id_usuario2=$iduserentrega;
		} 


		$sql2="UPDATE `cuentaspromotor` SET `cue_idoperentrega`='$id_usuario2', `cue_fecha`='$fechatiempo', cue_estado='10' $cond0 $tranf where cue_idservicio=$id_param2";
		$DB1->Execute($sql2);		

		$sql3="UPDATE `guias` SET `gui_userecomienda`='$id_nombre2',`gui_fechaentrega`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
		$DB->Execute($sql3);
		if($param19>=1){
			$param19=str_replace(".","", $param19);
			$sql4="INSERT INTO `abonosguias`(`abo_fecha`, `abo_valor`, `abo_idservicio`, `abo_iduser`, `abo_idsede`, `abo_estado`)  VALUES ('$fechatiempo','$param19','$id_param2','$id_usuario','$id_sedes','devolucion')";
			$DB->Execute($sql4);
		}


		$sql1="UPDATE `servicios` SET `ser_estentrega`='$param1',ser_fechafinal='$fechatiempo',ser_fechaguia='$fechatiempo',`ser_estado`='10',ser_img_entre='$foto' WHERE `idservicios`=$id_param2";
		if($tipopago>1 and $cambios==""){

			if ($_FILES['param40']['error'] === UPLOAD_ERR_OK) {
				$img_transaccion = $_FILES['param40']['name'];
				
				$rutaArchivo = 'img_transacciones/' . $img_transaccion;
		
				if (move_uploaded_file($_FILES['param40']['tmp_name'], $rutaArchivo)) {
					echo 'Imagen guardada correctamente';
				} else {
					echo 'Error al guardar la imagen';
					$img_transaccion = "";
				}
				} else {
					$img_transaccion = "";
				}
		




			$sql51="DELETE FROM `pagoscuentas` WHERE  pag_idservicio=$id_param2";
			$DB1->Execute($sql51);	

			$sql5="INSERT INTO `pagoscuentas`(`pag_tipopago`,`pag_cuenta`, `pag_valor`, `pag_idoperario`, `pag_idservicio`,`pag_guia`, `pag_estado`, `pag_fecha`,`pag_img_transaccion`) VALUES ('$tipopago','$cuenta','$param12','$id_usuario','$id_param2','$param11','Al cobro','$fechatiempo','$img_transaccion')";
			$DB1->Execute($sql5);
		}

		if($nivel_acceso==3){
			
			// $dir="ticketfactura.php";
			// $pagina2="inicio.php";
			$dir="firmadigital.php";
			$pagina2="inicio.php";
		}else {
				// $dir="entregas.php";
				// $pagina2="entregas.php";
				$dir="firmadigital.php";
				$pagina2="inicio.php";
			}
	
	}else{

		if($tipopago>1){

			if ($_FILES['param40']['error'] === UPLOAD_ERR_OK) {
				$img_transaccion = $_FILES['param40']['name'];
				
				$rutaArchivo = 'img_transacciones/' . $img_transaccion;
		
				if (move_uploaded_file($_FILES['param40']['tmp_name'], $rutaArchivo)) {
					echo 'Imagen guardada correctamente';
				} else {
					echo 'Error al guardar la imagen';
					$img_transaccion = "";
				}
				} else {
					$img_transaccion = "";
				}
		

			$sql51="DELETE FROM `pagoscuentas` WHERE  pag_idservicio=$id_param2";
			$DB1->Execute($sql51);	
		
			$sql5="INSERT INTO `pagoscuentas`(`pag_tipopago`,`pag_cuenta`, `pag_valor`, `pag_idoperario`, `pag_idservicio`,`pag_guia`, `pag_estado`, `pag_fecha`,`pag_img_transaccion`) 
			VALUES ('$tipopago','$cuenta','$param12','$id_usuario','$id_param2','$param11','Pendiente X cobrar','$fechatiempo','$img_transaccion')";
			$DB1->Execute($sql5);
		}

		$st=2; 
		$sql2="UPDATE servicios SET ser_pendientecobrar=$st WHERE idservicios='$id_param2' ";
		$DB1->Execute($sql2);
		$sql1="UPDATE `cuentaspromotor` SET `cue_pendientecobrar`='$st',cue_idoperpendiente='$id_usuario'  $tranf where cue_idservicio=$id_param2";
			
			if($nivel_acceso==3){
					
				$dir="pendientesmovil.php";
				$pagina2="pendientesmovil.php";
				
			}else {
				$dir="pendientes.php";
				$pagina2="pendientes.php";
				
			}

	}	

	$sql7="UPDATE `seguimientoruta` SET `seg_estado`='completado',`seg_fechafinalizo`='$fechatiempo',`seg_guia`='$param11' WHERE `seg_idservicio`='$id_param2'  AND  seg_tipo='Entrega' and seg_estado!='Cambioruta' AND seg_fecha like '%$fechaactual%'";
	$DB->Executeid($sql7); 
	
	// Preparar la consulta SQL para insertar los datos en la tabla firma_clientes
	$sql8 = "INSERT INTO firma_clientes (id_guia, tipo_firma, nombre, numero_documento,correo_electronico, telefono,enviar_whatsapp) 
	VALUES ('$id_param2', 'Entrega', '$param82', '$param83', '$param84', '$param85', '$param86')";
	$idfirma=$DB->Executeid($sql8);
	

	

	$sql12 = "SELECT idservicios,ser_estado,ser_telefonocontacto,ser_consecutivo,cli_telefono FROM `serviciosdia` WHERE  idservicios = '$id_param2'";			
	$DB1->Execute($sql12);
	$rw12 = mysqli_fetch_array($DB1->Consulta_ID);
	
	
	if ($rw12 === false) {
	}else {
		if ($rw1[1] == 3) {
			$sql2 = "SELECT cli_telefono FROM serviciosdia   WHERE idservicios='$idSer'";			
			$DB1->Execute($sql2);
			$rw2 = mysqli_fetch_array($DB1->Consulta_ID);
			if ($rw2 === false) {
			}else {
				$numguia="";
				$telefono=$rw2[0];//
				$idservicio=$idSer;
				$tipo = 'Recogida';
				enviarAlertaWhat($numguia,$telefono,1,$idservicio);
			}
		} else {
			$numguia=$rw12[3];
			$telefono=$rw12[2];
			$idservicio=$id_param2;
			$telefonoremi=$rw12[4];
			
			

				 enviarAlertaWhat($numguia,$telefono,6,$idservicio);
				 enviarAlertaWhat($numguia,$telefonoremi,6,$idservicio);

			
		}	
	}
	$planilla=$rw12[3];
	
	if($param30==1){
		$estadop='Contado';
	}elseif($param30==3){
		$estadop='Al Cobro';

	}elseif($param30==2){
		$estadop='Credito';

	}

	

}else if($param1=='NO ENTREGADO'){

	$porcobrar=$_REQUEST["porcobrar"];


	if($porcobrar==0){
			$sql2="UPDATE `cuentaspromotor` SET `cue_idoperentrega`='0', `cue_fecha`='$fechatiempo', cue_estado='11'  where cue_idservicio=$id_param2";
		$DB1->Execute($sql2);

		$sql3="UPDATE `guias` SET `gui_userecomienda`='$id_nombre',`gui_fechaentrega`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
		$DB->Execute($sql3);	
	$DB->Execute($sql3);	
		$DB->Execute($sql3);	

		if ($_FILES['param40']['error'] === UPLOAD_ERR_OK) {
			$img_evidencia = $_FILES['param40']['name'];
			
			$rutaArchivo = 'imgNoEntregas/' . $img_evidencia;
	
			if (move_uploaded_file($_FILES['param40']['tmp_name'], $rutaArchivo)) {
				echo 'Imagen guardada correctamente';
			} else {
				echo 'Error al guardar la imagen';
				$img_evidencia = "";
			}
			} else {
				$img_evidencia = "";
			}
			
			$hora1 = date('H:i:s');
			$fecha1= date('Y-m-d');

			$fechayhora=date("$fecha1 H:i:s");

			
		$sql1="UPDATE `servicios` SET `ser_estentrega`='$param1',ser_fechafinal='$fechatiempo',ser_fechaguia='$fechatiempo', `ser_descentrega`='$param2',`ser_estado`='11',ser_descllamada='No entregado',ser_esatdollamando='',ser_img_evidencia='$img_evidencia', ser_fecha_evidencia ='$fechayhora' WHERE `idservicios`=$id_param2";
		
		if($nivel_acceso==3){
			
			$dir="inicio.php";
			$pagina2="inicio.php";
			
		}else {
			$dir="entregas.php";
			$pagina2="entregas.php";
			
		}

}else{
	
	$st=1; 
	$sql2="UPDATE servicios SET ser_pendientecobrar=$st,`ser_descentrega`='$param2',ser_descllamada='No entregado',ser_esatdollamando='' WHERE idservicios='$id_param2' ";
	$DB1->Execute($sql2);

	$sql1="UPDATE `cuentaspromotor` SET `cue_pendientecobrar`='$st',cue_idoperpendiente='',cue_fechaasigno='' where cue_idservicio=$id_param2";
	

	if($nivel_acceso==3){
			
		$dir="pendientesmovil.php";
		$pagina2="pendientesmovil.php";
		
	}else {
		$dir="pendientes.php";
		$pagina2="pendientes.php";
		
	}

}


$sql7="UPDATE `seguimientoruta` SET `seg_estado`='NO entregado',`seg_fechafinalizo`='$fechatiempo',`seg_descripcion`='No entregado' WHERE `seg_idservicio`='$id_param2'  AND  seg_tipo='Entrega' and seg_estado!='Cambioruta' AND seg_fecha like '%$fechaactual%'";
$DB->Executeid($sql7); 


$sql12 = "SELECT idservicios,ser_estado,ser_telefonocontacto,ser_consecutivo FROM `servicios` WHERE  idservicios = '$id_param2'";			
$DB1->Execute($sql12);
$rw12 = mysqli_fetch_array($DB1->Consulta_ID);


if ($rw12 === false) {
}else {
	if ($rw1[1] == 3) {
		$sql2 = "SELECT cli_telefono FROM serviciosdia   WHERE idservicios='$idSer'";			
		$DB1->Execute($sql2);
		$rw2 = mysqli_fetch_array($DB1->Consulta_ID);
		if ($rw2 === false) {
		}else {
			$numguia="";
			$telefono=$rw2[0];//
			$idservicio=$idSer;
			$tipo = 'Recogida';
			enviarAlertaWhat($numguia,$telefono,1,$idservicio);
		}
	} else {
		$numguia=$rw12[3];
		$telefono=$rw12[2];
		$idservicio=$id_param2;
		
		 enviarAlertaWhat($numguia,$telefono,5,$idservicio);
		// enviarAlertaWhat($numguia,"3160490959",5,$idservicio);
	}	
}

}


if($cambios!=""){
	
	$DB->Execute($cambios);
}
$tabla="";
if ($DB->Execute($sql1))
		{
		$bandera=1;	
		}
	else{ $bandera=6; }

 	$DB->cerrarconsulta();
	$DB1->cerrarconsulta();
//pop_dis3($id_p,\"Recoger Paquete\")
//exit;
// header ("Location: $dir?pagina2=$pagina2&bandera=$bandera&tabla=$tabla&id_param=$id_param2&param34=$param34");


header ("Location: $dir?pagina2=$pagina2&bandera=$bandera&tabla=$tabla&id_param=$id_param2&param34=$param34&idfirma=$idfirma&idguia=$planilla&imprimir=$imprimir&p=$estadop");




function enviarAlertaWhat($numguia,$telefono,$tipo,$idservi){

	if (preg_match('/^\d{10}$/', $telefono)) {
		// echo "La variable tiene exactamente 10 números.";

			// URL de la API
		$url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

		// Datos que enviarás en la solicitud
		$data = array(
			"numero_guia" => "$numguia", // Número de guía
			"telefono" => "$telefono",  // Número de teléfono 3160490959
			// "telefono" => "3107781913",  // Número de teléfono 3160490959
			"tipo_alerta" => "$tipo",
			"id_guia" => "$idservi"
		);


		// Convertir los datos a formato JSON
		$data_json = json_encode($data);

		// Iniciar una sesión cURL
		$curl = curl_init();

		// Configurar las opciones cURL
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url, // URL de la API
			CURLOPT_RETURNTRANSFER => true, // Retorna el resultado como cadena
			CURLOPT_POST => true, // Indica que la solicitud será POST
			CURLOPT_POSTFIELDS => $data_json, // Los datos que se envían en la solicitud
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json', // Tipo de contenido
				'Authorization: Bearer MiSuperToken123' // Si la API requiere autenticación
			),
		));

		// Ejecutar la solicitud y obtener la respuesta
		$response = curl_exec($curl);

		// Manejar errores cURL
		if($response === false) {
			$error = curl_error($curl);
			echo "Error en la solicitud: $error";
		} else {
			// Decodificar la respuesta (si es JSON)
			$response_data = json_decode($response, true);
			
			// Mostrar la respuesta
			echo "Respuesta de la API: ";
			print_r($response_data);
		}

		// Cerrar la sesión cURL
		curl_close($curl);
	} else {
		echo "La variable no cumple con el formato.";
	}






}

?>

