
<?php
require("login_autentica.php"); 
$id_sedes= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$precioinicialkilos=$_SESSION['precioinicial'];

$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$QL = new sql_transact;

$dato=1;

$sql32="Select gui_tiposervicio from guias WHERE `gui_idservicio`='$id_param2'"; 
$DB->Execute($sql32);
$rw6=mysqli_fetch_row($DB->Consulta_ID); 

$kilos=$param1;
$sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;"; 
$DB->Execute($sqlprecios);
$confipre=mysqli_fetch_row($DB->Consulta_ID); 
$idprecios=$confipre[0];
if($idprecios==0 or $idprecios==''){
	$idprecios=1;
}

  $sql="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios 
  where con_tipo='normal'  and pre_idciudadori=$param5  and pre_idciudaddes=$param16 and pre_tiposervicio=$rw6[0] and con_idprecios='$idprecios' and pre_estado=1";
 $DB->Execute($sql);

$rw = mysqli_fetch_row($DB->Consulta_ID); 

@$preciokilo=$rw[1];
@$precioadicional=$rw[2];
 @$serciudad=$param16;

$llego="";
if(@$serciudad>0){

if($param5!=$serciudad){
  $sql3="SELECT inner_sedes FROM `ciudades` where idciudades in ($param5,$serciudad)";
 $DB1->Execute($sql3);
 $ver=0;
while($rw3 = mysqli_fetch_row($DB1->Consulta_ID)) {
	$sedes[$ver]=$rw3[0];
	$ver++;
	}
if($sedes[0]==$sedes[1]){  $estado=8; $llego=",ser_llego='SI'"; } else { $estado=6; $llego=""; }
} else {
	
	$estado=8; $llego=",ser_llego='SI'";
	
}

 @$caso=$_REQUEST["caso"];

if($caso==1){ 

//$param3=0; 
	if($nivel_acceso==3){
		$pagina='ticketfactura.php'; 
		$pagina2="pesarmovil.php";
	}else {
		$pagina='peso.php'; 
		$pagina2="peso.php";
	}
 $sql3="UPDATE `guias` SET `gui_usupeso`='$id_nombre',`gui_fechapeso`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
$DB->Execute($sql3); 

if($_FILES["param10"]!=''){
	$QL->addimagenguia($_FILES["param10"],$param6,'Recogida', $id_param2,$DB);
	//$QL->addDocumento1($_FILES["param10"], 1, "conciliacion", $id_param, "conciliacion", $DB);
}

} else {  
		$sql3="UPDATE `guias` SET `gui_usuvalpeso`='$id_nombre',`gui_fechavalpeso`='$fechatiempo' WHERE `gui_idservicio`='$id_param2'";
		$DB->Execute($sql3); 


		if (!empty($_FILES["param10"]["tmp_name"])) {
			// Carpeta de destino
			$carpetaDestino = __DIR__ . "/imgServicios/";

			// Si no existe la carpeta, la creamos
			if (!is_dir($carpetaDestino)) {
				mkdir($carpetaDestino, 0777, true);
			}

			// Nombre único para evitar sobreescribir
			$extension = pathinfo($_FILES["param10"]["name"], PATHINFO_EXTENSION);
			$nombreArchivo = uniqid("servicio_", true) . "." . strtolower($extension);
			$rutaFinal = $carpetaDestino . $nombreArchivo;

			// Detectar el tipo de imagen
			$tipo = mime_content_type($_FILES["param10"]["tmp_name"]);

			if ($tipo == "image/jpeg" || $tipo == "image/jpg") {
				$img = imagecreatefromjpeg($_FILES["param10"]["tmp_name"]);
				// Guardar con calidad reducida (ej: 75%)
				imagejpeg($img, $rutaFinal, 75);
			} elseif ($tipo == "image/png") {
				$img = imagecreatefrompng($_FILES["param10"]["tmp_name"]);
				// Reducir peso manteniendo transparencia
				imagepng($img, $rutaFinal, 7); // calidad 0–9 (7 ≈ buena compresión)
			} else {
				echo "Formato no soportado";
				exit;
			}

			// Liberar memoria
			imagedestroy($img);

			// 👉 Aquí ya tienes la imagen guardada en imgServicios/
			// Guarda el nombre en tu base de datos
			$nombreParaBD = $nombreArchivo;

			// Ejemplo: INSERT en BD

			$sqlImg="UPDATE `servicios` SET `ser_img_recog`='$nombreParaBD' WHERE `idservicios`='$id_param2'";
			$DB->Execute($sqlImg); 
			

			// echo "Imagen guardada correctamente como: " . $nombreParaBD;
		}
		$pagina2='ticketfacturatodos.php'; 
		$pagina='verificarpeso.php'; 

 }


 $kilosvolumen=$param1+$param4;
 $clasificacion=$_REQUEST["clasificacion"];

  $sql33="Select tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`='$rw6[0]'"; 
 $DB->Execute($sql33);
 $rw7=mysqli_fetch_row($DB->Consulta_ID); 

if($rw7[0]==0 and $clasificacion!=2){ //si no tiene precios en tiposervicio y es diferente de credito
	if($param1>$precioinicialkilos){
	    @$precio1=($param1+$param4-$precioinicialkilos)*$precioadicional;
		@$precio=$preciokilo+$precio1;
	}else {
		@$precio1=$param4*$precioadicional;
		@$precio=$preciokilo+$precio1;
	}

}else if($rw7[0]>=10 and $clasificacion!=2){ // guias con opcion de precios configurados precios definidos


	if($rw7[0]!=''){
		if($param1>$precioinicialkilos){
			@$precio1=($param1+$param4-$precioinicialkilos)*$rw7[1];
			@$precio=$rw7[0]+$precio1;
		}else{
			@$precio1=$param4*$rw7[1];
			@$precio=$rw7[0]+$precio1;	
		}
	}else{
			@$precio=0;
		}

}else if($clasificacion==2){

	 	$sqlc="SELECT rel_nom_credito,idcreditos FROM `rel_sercre` inner join creditos on cre_nombre=rel_nom_credito where idservicio=$id_param2 ";
		$DB->Execute($sqlc);
		$rw21=mysqli_fetch_row($DB->Consulta_ID); 
		 $creditouser=$rw21[0];
		 $idcredito=$rw21[1];

		date_default_timezone_set('America/Bogota'); // ajusta a tu país
		$fechaHoy = date('Y-m-d');

		// if ($hoy === '2026-01-01') {

		// 	$sql3="SELECT `pre_preciokilo`,`con_precios` 
		// 	FROM `precios_credito`  
		// 	inner join `configuracionkilos` on con_idprecioskilos=idprecioscredito  
		// 	WHERE   con_tipo='Credito'  
		// 	and   `pre_idciudadori`='$param5'  
		// 	and `pre_idciudades`='$param16' 
		// 	and pre_tiposervicio='$rw6[0]' 
		// 	and pre_idcredito='$idcredito' 
		// 	and con_idprecios='$idprecios'
		// 	AND ($fechaHoy BETWEEN pre_fecha_inicial 
		// 	AND pre_fecha_final ) AND pre_estado= 1";

		// }else {
			$sql3="SELECT `pre_preciokilo`,`con_precios` FROM `precios_credito`  inner join `configuracionkilos` on con_idprecioskilos=idprecioscredito  WHERE   con_tipo='Credito'  and   `pre_idciudadori`='$param5'  and `pre_idciudades`='$param16' and pre_tiposervicio='$rw6[0]' and pre_idcredito='$idcredito' and con_idprecios='$idprecios' and pre_estado=1 ";

		// }
		$DB->Execute($sql3);
		$rw2=mysqli_fetch_row($DB->Consulta_ID); 
	
		if($param1>$precioinicialkilos){
			@$precio1=($param1+$param4-$precioinicialkilos)*$rw2[1];
			@$precio=$rw2[0]+$precio1;
		}else{
			@$precio1=$param4*$rw2[1];
			@$precio=$rw2[0]+$precio1;
		}
			
}

if($rw6[0]!='1000'){

	$vprecio="`cue_valorflete`='$precio',";
	$vprecio2="ser_valor='$precio',";
}else{
	$vprecio='';
	$vprecio2='';
}


	$sql2="UPDATE `cuentaspromotor` SET  $vprecio cue_estado='$estado',cue_kilostotal='$kilosvolumen'  where cue_idservicio=$id_param2";
	$DB1->Execute($sql2);	

 	 $sql1="UPDATE `servicios` SET  $vprecio2  `ser_peso`='$param1',ser_descripcion='$param2',ser_idverificado='$id_usuario',ser_idverificadopeso='$param3',ser_volumen='$param4',`ser_estado`='$estado',ser_visto=0,ser_guiare='$param6',`ser_fechafinal`='$fechatiempo' $llego WHERE `idservicios`=$id_param2";

//}
$DB->Execute($sql1);

$sqlop="SELECT cue_idoperador FROM `cuentaspromotor` WHERE cue_idservicio='$id_param2'";
		$DB->Execute($sqlop);
		$rwop=mysqli_fetch_row($DB->Consulta_ID); 
		$rwop[0];


 

}else {
	$dato=7;
	if($caso==1){
		if($nivel_acceso==3){
			$pagina='ticketfactura.php'; 
			$pagina2="pesarmovil.php";
		}else {
			$pagina='peso.php'; 
			$pagina2="peso.php";
		}

	} else {
		
		$pagina='verificarpeso.php'; 
		$pagina2='verificarpeso.php'; 
		//exit;
	}
}	

$sql4 = "SELECT `idclientes`,`ser_telefonocontacto`,`cli_telefono`,ser_consecutivo FROM serviciosdia where idservicios='$id_param2'  ";
$DB->Execute($sql4);
 $rw1=mysqli_fetch_row($DB->Consulta_ID); 
 $telefono=$rw1[1];

 $telefono = preg_replace('/\D/', '', $telefono); // Elimina todo excepto números
 enviarAlertaWhat($rw1[3],$telefono,26,$id_param2);

 $DB->cerrarconsulta();
 $DB1->cerrarconsulta();
//   header ("Location: $pagina?pagina2=$pagina2&id_param=$id_param2&bandera=$dato&param3=$rwop[0]");

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
