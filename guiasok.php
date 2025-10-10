<?php
require("login_autentica.php"); 
$id_ciudad= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
@$tipoguia=$_REQUEST["tipoguia"];
@$registros=$_REQUEST["registros"];
$id_nombre=$_SESSION['usuario_nombre'];
$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();



if($tipoguia=='sedes'){
	
	
	for($b=1;$b<=$registros;$b++)
	{
	  $inser=1;
	  @$valor=$_REQUEST["asignar_$b"];
	
		if($valor==1){

			 $idser=$_REQUEST["servicio_$b"];
			
			$piezasg=$_REQUEST["piezasg_$b"];
			$pieza=$_REQUEST["pieza_$b"];
			$guia=$_REQUEST["guia_$b"];
			//echo $pieza."<br>";
			if($piezasg>1){

				$sql="INSERT INTO `piezasguia`(`numeroguia`, `numeropieza`) values ('$guia',$pieza)";
				$DB1->Execute($sql);

				$sql="SELECT  count(numeropieza) from piezasguia where numeroguia='$guia' ";		
				$DB->Execute($sql);
				$rw2=mysqli_fetch_row($DB->Consulta_ID);

				if($rw2[0]!=$piezasg){
					$inser=0;
					$sql2="UPDATE `servicios` SET  `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser' ";			
					$DB->Execute($sql2);
				}

			}else{

				 $sql4="INSERT INTO `piezasguia`( `numeroguia`, `numeropieza`) values ('$guia',1)";
				$DB1->Execute($sql4);
			}

			if($inser==1){
			
				$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='7'  where cue_idservicio=$idser";
				$DB1->Execute($sql1);			
				
				$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='7'
				WHERE `idservicios`='$idser' ";			
				$DB->Execute($sql2);
				
				$sql3="UPDATE `guias` SET `gui_ensede`='$id_nombre',`gui_fechaensede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
				$DB->Execute($sql3); 
			}

		}
		
	}

	header ("Location: guiassede.php?bandera=1");
	
} else if($tipoguia=='operador'){
	
	// $idsProcesados = []; // Aquí guardaremos los IDs que vamos a devolver
	// $sql6="SELECT usu_celular FROM usuarios where `idusuarios`='$param31' ";	
	// // $DB1->Execute($sql6);
	// // $rw3=mysqli_fetch_row($DB1->Consulta_ID);
	// $telefono=$rw3[0];
	// $tipo="20";

	// $cont=0;
	// for($b=1;$b<=$registros;$b++)
	// {
	// 	$cont++;
	//  @$valor=$_REQUEST["asignar_$b"];
	 
	// 	if($valor==1){
			
	// 		$idser=$_REQUEST["servicio_$b"];
	// 		$idsProcesados[] = $idser;
	// 		$direccion="Entrega ".$_REQUEST["direccion_$b"];
	// 		$planilla=$_REQUEST["guia_$b"];
			
	// 		$sql1="UPDATE `cuentaspromotor` SET `cue_idoperentrega`='$param31', `cue_fecha`='$fechatiempo', cue_estado='9'  where cue_idservicio=$idser";
	// 		// $DB1->Execute($sql1);	
			
	// 	   $sql2="UPDATE `servicios` SET  ser_idusuarioguia='$param31',`ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='9',ser_visto=0
	// 		WHERE `idservicios`='$idser' ";
	// 		// $DB->Execute($sql2);
			
	// 		 $sql3="UPDATE `guias` SET `gui_encomienda`='$id_nombre',`gui_fechaencomienda`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
	// 		// $DB->Execute($sql3); 

	// 		 $sqlse = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,`seg_guia`) values ('$fechatiempo','$idser','$direccion','Entrega','Asignada','$param31','$planilla')";
	// 		// $DB1->Execute($sqlse);	
			

	// 		// enviarAlertaWhat("","3125215864",$tipo,$idser);

	// 	}
	
	// }	

	// $DB->cerrarconsulta();
	// $DB1->cerrarconsulta();

	// //  enviarAlertaWhat("",$telefono,$tipo,$cont);
	
	//  header('Content-Type: application/json');
	//  echo json_encode([
	// 	 'status' => 'success',
	// 	 'ids' => $idsProcesados
	//  ]);
	//  exit;
	// 

	$sql6="SELECT usu_celular FROM usuarios where `idusuarios`='$param31' ";	
	$DB1->Execute($sql6);
	$rw3=mysqli_fetch_row($DB1->Consulta_ID);
	$telefono=$rw3[0];
	$tipo="20";
	$cont=0;
	for($b=1;$b<=$registros;$b++)
	{
	 @$valor=$_REQUEST["asignar_$b"];
	 
		if($valor==1){
			$cont++;
			$idser=$_REQUEST["servicio_$b"];
			$direccion="Entrega ".$_REQUEST["direccion_$b"];
			$planilla=$_REQUEST["guia_$b"];
			
			$sql1="UPDATE `cuentaspromotor` SET `cue_idoperentrega`='$param31', `cue_fecha`='$fechatiempo', cue_estado='9'  where cue_idservicio=$idser";
			$DB1->Execute($sql1);	
			
		   $sql2="UPDATE `servicios` SET  ser_idusuarioguia='$param31',`ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='9',ser_visto=0
			WHERE `idservicios`='$idser' ";
			$DB->Execute($sql2);
			
			 $sql3="UPDATE `guias` SET `gui_encomienda`='$id_nombre',`gui_fechaencomienda`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
			$DB->Execute($sql3); 

			 $sqlse = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`,`seg_guia`) values ('$fechatiempo','$idser','$direccion','Entrega','Asignada','$param31','$planilla')";
			$DB1->Execute($sqlse);	
			

			 

			 
			// enviarAlertaWhat("","3125215864",$tipo,$idser);

		}
	
	}
		enviarAlertaWhat("0",$telefono,$tipo,$cont);
		header ("Location: guias.php?bandera=1&param31=$param31");

	
}else if($tipoguia=='validar'){
	
	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$llego=$_REQUEST["llego"];
	$piezasg=$_REQUEST["piezasg"];
	$guia=$_REQUEST["guia"];

	$inser=1;
	if($llego=='SI'){
		$estadog=8;
	}else if($llego=='NO'){
		$estadog=12;
	}else if($llego=='Incompleto'){
		$estadog=13;
	}else if($llego=='Perdida'){
		$estadog=16;
	}else if($llego=='Incautada'){
		$estadog=17;
	}
	

	if($piezasg>1){

		$sql="UPDATE  `piezasguia` SET guiallega=1  WHERE numeroguia='$guia'";
		$DB1->Execute($sql);

		$sql="SELECT  count(numeropieza) from piezasguia where numeroguia='$guia' and guiallega=1  ";		
		$DB->Execute($sql);
		$rw2=mysqli_fetch_row($DB->Consulta_ID);
			
		if($rw2[0]!=$piezasg){
			$inser=0;
			$sql2="UPDATE `servicios` SET  `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser' ";			
			$DB->Execute($sql2);
		}

	}else{

	   	$sql4="UPDATE  `piezasguia` SET guiallega=1  WHERE numeroguia='$guia'";
		$DB1->Execute($sql4);
	}

	if($inser==1){

		  $sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='$estadog'  where cue_idservicio=$idser";
		$DB1->Execute($sql1);	
		
		 $sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='$estadog',ser_desvaliguia='$descr',ser_llego='$llego'
		WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);
		
		 $sql3="UPDATE `guias` SET `gui_validasede`='$id_nombre',`gui_fechavalidasede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
		$DB->Execute($sql3); 
	}

}else if($tipoguia=='validardevuelta'){
	
	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$llego=$_REQUEST["llego"];
	$piezasg=$_REQUEST["piezasg"];
	$guia=$_REQUEST["guia"];

	echo$inser=1;
	// if($llego=='SI'){
	// 	$estadog=8;
	// }else if($llego=='NO'){
	// 	$estadog=12;
	// }else if($llego=='Incompleto'){
	// 	$estadog=13;
	// }else if($llego=='Perdida'){
	// 	$estadog=16;
	// }else if($llego=='Incautada'){
	// 	$estadog=17;
	// }
	
	if($llego=='devueltaremitente'){
		$estadog=7;
	}else if($llego=='devueltaBodega'){
		$estadog=7;
	}

	
	// if($piezasg>1){

	// 	$sql="UPDATE  `piezasguia` SET guiallega=1  WHERE numeroguia='$guia'";
	// 	$DB1->Execute($sql);

	// 	$sql="SELECT  count(numeropieza) from piezasguia where numeroguia='$guia' and guiallega=1  ";		
	// 	$DB->Execute($sql);
	// 	$rw2=mysqli_fetch_row($DB->Consulta_ID);
			
	// 	if($rw2[0]!=$piezasg){
	// 		$inser=0;
	// 		$sql2="UPDATE `servicios` SET  `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser' ";			
	// 		$DB->Execute($sql2);
	// 	}

	// }else{

	   	$sql7="UPDATE  `piezasguia` SET guiallega=0  WHERE numeroguia='$guia'";
		$DB1->Execute($sql7);
	$sql6="SELECT cli_idciudad FROM serviciosdia where `idservicios`='$idser' ";	
	$DB1->Execute($sql6);
	$rw3=mysqli_fetch_row($DB1->Consulta_ID);
	$ciudadorigen=$rw3[0];

	// $sql1="UPDATE `servicios` SET `ser_telefonocontacto`='$param8',`ser_destinatario`='$param9',`ser_direccioncontacto`='$param10',`ser_ciudadentrega`='$param11' WHERE `idservicios`='".$_REQUEST['id_param2']."'";
	// $DB->Execute($sql1);

	$sql4="SELECT `idservicios`,`ser_fecharegistro`,ser_guiare,cue_pordeclarado,ser_valor,ser_numerofactura,ser_estado
	FROM servicios inner join cuentaspromotor on cue_idservicio=idservicios where $cond1 ";
	$DB->Execute($sql4);
	$datos=mysqli_fetch_array($DB->Consulta_ID,MYSQLI_ASSOC);// }


	if($inser==1){

		$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='$estadog'  where cue_idservicio=$idser";
		$DB1->Execute($sql1);	
		
		if ($llego=='devueltaremitente') {
			# code...

		$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='$estadog',ser_llego='$llego',`ser_ciudadentrega`='$ciudadorigen'
		WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);

		$sql3="UPDATE `guias` SET `gui_usudevuelveorigen`='$id_nombre',`gui_fechadevuelveorigen`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
		$DB->Execute($sql3); 

		}elseif($llego=='devueltaBodega') {
		$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='$estadog',ser_llego='$llego',`ser_ciudadentrega`=1
		WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);


		$sql5="UPDATE `guias` SET `gui_usuabodega`='$id_usuario',`gui_fechaabodega`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
		$DB->Execute($sql5); 

		}
		
		
		 $sql3="UPDATE `guias` SET `gui_validasede`='$id_nombre',`gui_fechavalidasede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
		$DB->Execute($sql3); 
	}

}else if($tipoguia=='validarfaltantes'){
	
	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];


		  $sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo' where cue_idservicio=$idser";
		$DB1->Execute($sql1);	
		
		 $sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_desvaliguia='$descr',ser_llego='$llego'
		WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);
		
		 $sql3="UPDATE `guias` SET `gui_validasede`='$id_nombre',`gui_fechavalidasede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
		$DB->Execute($sql3); 
	


}else if($tipoguia=='agregarguia'){

	$idservicio=$_REQUEST["idservicio"];
	$sql4="INSERT INTO `tempfactura`(`id`) values ($idservicio)";
	$DB1->Execute($sql4);

	$sql2="SELECT * FROM `tempfactura` ";	
	$DB->Execute($sql2);
	$rw1=mysqli_fetch_row($DB->Consulta_ID);
	echo "jose".$rw1[0];

}else if($tipoguia=='buscarfactura'){

	$idfactura=$_REQUEST["idfactura"];

   $cond1="  fac_numeroref='$idfactura' ";
    $sql4="SELECT count(*) FROM facturascreditos  where $cond1 ";
	$DB->Execute($sql4);
	$rw1=mysqli_fetch_row($DB->Consulta_ID);
	
	if($rw1[0]>0){
		echo "duplicada";
	}else{
		echo "ok";
	}
}else if($tipoguia=='buscarguia'){

	$idservicio=$_REQUEST["idservicio"];
	$tipo=$_REQUEST["tipo"];

   $cond1=" $tipo='$idservicio' ";
    $sql4="SELECT `idservicios`,`ser_fecharegistro`,ser_guiare,cue_pordeclarado,ser_valor,ser_numerofactura,ser_estado,ser_manifiesto,ser_pendientecobrar	FROM servicios inner join cuentaspromotor on cue_idservicio=idservicios where $cond1 ";
	$DB->Execute($sql4);
	$datos=mysqli_fetch_array($DB->Consulta_ID,MYSQLI_ASSOC);
	//Seteamos el header de "content-type" como "JSON" para que jQuery lo reconozca como tal
	header('Content-Type: application/json');
	//Devolvemos el array pasado a JSON como objeto
	echo json_encode($datos, JSON_FORCE_OBJECT);


}
else if($tipoguia=='validarsede'){
	
	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$llego=$_REQUEST["llego"];
	$estado="";

	if($llego=='SI'){

		$estado=" ,ser_estentrega='NO ENTREGADO EN SEDE'";

	}else if($llego=='NO'){

		$estado=" ,ser_estentrega='NO EN SEDE'";
	}
		if($descr=='Validado Con Pistola'){

			$condicionwhere=" ser_guiare='$idser'";
		}else{
			$condicionwhere=" `idservicios`='$idser'";
		}
 		 $sql1="UPDATE `servicios` SET  `ser_descentrega`='$descr',ser_fechafinal='$fechatiempo',`ser_idasignacion`='$id_usuario'  $estado WHERE $condicionwhere ";
		$DB1->Execute($sql1);		

}
else if($tipoguia=='cancelar'){
	
	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$llego=$_REQUEST["llego"];
	if($llego=="SI"){
		
	$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='100',cue_idoperador=0,cue_fecharecogida='00:00:00'  where cue_idservicio=$idser";
	$DB1->Execute($sql1);	
		
	$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechafinal`='$fechatiempo',ser_estado=100,ser_desvaliguia='$descr',`ser_idusuarioregistro`='$id_usuario' 	WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);
	
		

	}else if($llego=="NO"){
		
		$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='2',cue_idoperador=0,cue_fecharecogida='00:00:00'   where cue_idservicio=$idser";
		$DB1->Execute($sql1);	
	
		$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fecharegistro`='$fechatiempo',ser_estado='2',ser_desvaliguia='',`ser_idusuarioregistro`='' 	WHERE `idservicios`='$idser' ";
		$DB->Execute($sql2);
		echo "Servicio Activo";
	}
	
} else if($tipoguia=='incompletas'){

	for($b=1;$b<=$registros;$b++)
	{
	  @$valor=$_REQUEST["asignar_$b"];
	
		if($valor==1){
			$idser=$_REQUEST["servicio_$b"];
			$guia=$_REQUEST["guia_$b"];
		
			$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='7'  where cue_idservicio=$idser";
			$DB1->Execute($sql1);			
			
		  $sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='7'
			WHERE `idservicios`='$idser' ";			
			$DB->Execute($sql2);
			
			 $sql3="UPDATE `guias` SET `gui_userdevolucion`='$id_nombre',`gui_fechadevolucion`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
			$DB->Execute($sql3); 

			$sql4="UPDATE `piezasguia` SET `guiallega`='0' WHERE numeroguia='$guia'";
			$DB->Execute($sql4); 
		}
		
	}

	header ("Location: guiasincompletas.php?bandera=1");
	
}else if($tipoguia=='validaprecios'){

	$nomcredito=$_REQUEST["nomcredito"];
	$tipocredito=$_REQUEST["tipocredito"];
	$ciudadori=$_REQUEST["ciudadori"];
	$ciudaddes=$_REQUEST["ciudaddes"];

	 $sql2="SELECT idprecioscredito,idcreditos FROM `precios_credito`  inner join creditos on idcreditos=pre_idcredito where cre_nombre='$nomcredito'  and pre_idciudadori='$ciudadori' and pre_idciudades='$ciudaddes' and pre_tiposervicio='$tipocredito'";	
	$DB1->Execute($sql2);
	$rw1=mysqli_fetch_row($DB1->Consulta_ID);
	$gatoscomfirmar=$rw1[1];
	if($gatoscomfirmar>0){
		echo "1";
	}else {
		echo "2";
	}
	//$datos=array("resultado"  => "3");

}else if($tipoguia=='validarrepetidas'){

	$telremitente=$_REQUEST["telremitente"];
	$teldestino=$_REQUEST["teldestino"];
	$ciudadori=$_REQUEST["ciudadori"];
	$ciudaddes=$_REQUEST["ciudaddes"];

	 $sql2="SELECT idservicios FROM serviciosdia where cli_telefono='$telremitente' and ser_telefonocontacto='$teldestino' and cli_idciudad='$ciudadori' and ser_ciudadentrega='$ciudaddes' and ser_clasificacion!=2 and ser_fecharegistro like '$fechaactual%' and ser_estado!=100";	
	$DB1->Execute($sql2);
	$rw3=mysqli_fetch_row($DB1->Consulta_ID);
	$idservicio=$rw3[0];
	if($idservicio>0){
		echo $idservicio;
	}else {
		echo "0";
	}
	
	//$datos=array("resultado"  => "3");

}
else if($tipoguia=='cuentasoperador'){

	$sql="SELECT cue_estado, cue_idoperador,cue_idoperentrega,cue_fecha,cue_fecharecogida FROM `cuentaspromotor` where  cue_numeroguia='$registros' ";		
	$DB->Execute($sql);
	$rw2=mysqli_fetch_row($DB->Consulta_ID);
		
	if($rw2[0]>=9){
			$sql="UPDATE cuentaspromotor SET cue_validadoentrega=1 WHERE cue_numeroguia='$registros' ";
			$DB1->Execute($sql);
	}else{

			$sql="UPDATE cuentaspromotor SET cue_validado=1 WHERE cue_numeroguia='$registros' ";
			$DB1->Execute($sql);
	}

	 $fechar=substr($rw2[4], 0,10);
	 $fechae=substr($rw2[3], 0,10);

	if($rw2[1]==$rw2[2] and $fechar==$fechae){  
		$sql="UPDATE cuentaspromotor SET cue_validado=1 WHERE cue_numeroguia='$registros' ";
		$DB1->Execute($sql);
	}

}else if($tipoguia=='elementos'){

	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$llego=$_REQUEST["llego"];
		
	$sql1="UPDATE `elementostrabajo` SET  `ele_fecharetiro`='$fechatiempo',`ele_entregado`='$llego',`ele_userverifico`='$id_nombre',`ele_etregadescripcion`='$descr'  where idelementostrabajo=$idser";
	$DB1->Execute($sql1);	
			

}
else if($tipoguia=='validartelefono'){

	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];
	$estado=$_REQUEST["llego"];
		if($estado=='0'){
			$estado='Sin validar';
		}
	$sql1="UPDATE `telefonospagina` SET  `tel_fecha`='$fechatiempo',`tel_estado`='$estado',`tel_usuario`='$id_nombre',`tel_descripcion`='$descr'  where idtelefonospagina=$idser";
	$DB1->Execute($sql1);	

}
else if($tipoguia=='devolver'){

	$idser=$_REQUEST["servicio"];
	$descr=$_REQUEST["descripcion"];

		$sql2="UPDATE `servicios` SET ser_estado='21',ser_esatdollamando='',ser_descllamada='$descr',ser_esatdollamando='Colgado' WHERE `idservicios`='$idser' ";			
		$DB->Execute($sql2);

}
	
	
	$DB->cerrarconsulta();
	$DB1->cerrarconsulta();

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