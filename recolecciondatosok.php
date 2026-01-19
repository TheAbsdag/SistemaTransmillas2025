<?php

require("login_autentica.php"); 
include("declara.php");
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$id_sedes=$_SESSION['usu_idsede'];
$precioinicialkilos=$_SESSION['precioinicial'];


@$variableunica=$_REQUEST["variableunica"];

 $sql="SELECT `ser_guiare` FROM `servicios` WHERE ser_idregistro='$variableunica'; ";		
$DB1->Execute($sql);
$idregistro=$DB1->recogedato(0); 

if($idregistro==''){  


@$id_usuario=$_REQUEST["id_usuario"];



//@$precio=$_REQUEST["precio"];
//@$pordeclarado=$_REQUEST["pordeclarado"];
//@$porprestamo=$_REQUEST["porprestamo"];
@$precio=valorguia($DB,$param4,$param11,$param34,$param28,$param26,$param27,$precioinicialkilos);
@$porprestamo=porprestamo($DB,$param16);
$param17=str_replace(".","", $param17);
$param18=str_replace(".","", $param18);
@$pordeclarado=(intval($param18)*1)/100;

$pagos=explode('|',$param30);
 $tipopago=$pagos[0];
$cuenta=$pagos[1];
 $namepago=$pagos[2];

if($param11!=$param4){
 $sql3="SELECT inner_sedes FROM `ciudades` where idciudades in ($param11,$param4)";
 $DB1->Execute($sql3);
 $ver=0;
while($rw3 = mysqli_fetch_row($DB1->Consulta_ID)) {
	$sedes[$ver]=$rw3[0];
	$ver++;
	}
if($sedes[0]==$sedes[1]){   $estado=6; } else { $estado=6; }
	if($param28=='2'){ $param78=2; } else { $param78=0; }
} else {
	
	$estado=6; 
}	
if($nivel_acceso==3){  $estado=4;  $param15="Recogida Operador"; }	
 
$param10=$param10."&".$param101."&".$param21."&".$param22."&".$param24."&"; 
$param5=$param5."&".$param51."&".$param19."&".$param20."&".$param23."&";  
	

$param5 = str_replace('&0&','&&', $param5);
$param10 = str_replace('&0&','&&', $param10);

if($id_param==0 and $id_param==0){
echo "aki 1";

	 $sql1="INSERT INTO `clientes`( `cli_iddocumento`,  `cli_email`, `cli_clasificacion`, `cli_retorno`,`cli_tipo`, `cli_fecharegistro`) 
	VALUES ('$param1','$param3','$param78',$param25,2,'$fechatiempo')";
	$idexec=$DB1->Executeid($sql1);

	$sql="INSERT INTO `clientesdir`(`cli_nombre`, `cli_telefono`,`cli_idciudad`, `cli_direccion`,  `cli_idclientes`, `cli_principal`) 
	VALUES ('$param6','$param2','$param4','$param5','$idexec',1)";
	$idcli=$DB1->Executeid($sql);

}else {
$idcli=$_REQUEST['id_param2'];	
 	 $sql1="UPDATE `clientes` SET  `cli_iddocumento`='$param1',`cli_email`='$param3', `cli_clasificacion`='$param78',
	`cli_tipo`='2', `cli_fecharegistro`='$fechatiempo',`cli_retorno`=$param25  WHERE `idclientes`='$id_param'";
	$DB->Execute($sql1);

	 $sql="UPDATE `clientesdir` SET  `cli_nombre`='$param6', `cli_telefono`='$param2',`cli_idciudad`='$param4', `cli_direccion`='$param5',
	  `cli_idclientes`='$id_param', `cli_principal`=1 where `idclientesdir`='".$_REQUEST['id_param2']."'";
	$DB->Execute($sql);
	
		$idexec=$id_param;

}

echo "aki 2";
	 $sql2="INSERT INTO `clientesservicios` (`cli_nombre`, `cli_telefono`,`cli_idciudad`, `cli_direccion`,  `cli_idclientes`, `cli_principal`) 
	VALUES ('$param6','$param2','$param4','$param5','$idexec',1)";
	$idcli2=$DB->Executeid($sql2);

if($param8!=''){

	 $sql3="SELECT idclientes From clientes inner join clientesdir on cli_idclientes=idclientes where cli_telefono='$param8'";
	$DB->Execute($sql3);
	$valorinser=$DB->recogedato(0);
	if($valorinser<=0){
		echo "aki 3";
		 $sql1="INSERT INTO `clientes`(`cli_tipo`, `cli_iddocumento`,  `cli_email`,`cli_fecharegistro`) 
		VALUES (0,'$param7','','$fechatiempo')";
		$idexec=$DB1->Executeid($sql1);

		 $sql5="INSERT INTO `clientesdir`(`cli_nombre`, `cli_telefono`,`cli_idciudad`, `cli_direccion`,  `cli_idclientes`, `cli_principal`) 
		VALUES ('$param9','$param8','$param11','$param10','$idexec',0)";
		$DB1->Execute($sql5);


	}else {
		
		 $sql1="UPDATE `clientes` SET  `cli_tipo`='0', `cli_fecharegistro`='$fechatiempo', `cli_iddocumento`='$param7'  WHERE `idclientes`='$valorinser'";
		$DB->Execute($sql1);

		 $sql="UPDATE `clientesdir` SET  `cli_nombre`='$param9', `cli_telefono`='$param8',`cli_idciudad`='$param11', 
		 `cli_direccion`='$param10',   `cli_principal`=0 where cli_idclientes='$valorinser' ";
		$DB->Execute($sql);
		
	}
}

 	$sql="SELECT `idconfac`, `idconsecutivo`, `idresolucion`, `prefijo` FROM `conf_fac` inner join ciudades on idciudad=inner_sedes WHERE idciudades='$param4'";	
	$DB1->Execute($sql);
	$rw1=mysqli_fetch_array($DB1->Consulta_ID);	
	$planilla="$rw1[3]$rw1[1]";
	$idconsecutivo=$rw1[1]+1;
	if($idconsecutivo>=10){
  		 $sql2="UPDATE `conf_fac` c inner join ciudades cc on c.idciudad=cc.inner_sedes SET c.`idconsecutivo`=$idconsecutivo   WHERE cc.idciudades='$param4'";	
		 $DB->Execute($sql2);
	}else{
		$planilla="";
	}

	if($param16==''){
		$param16=$planilla;
	}
 
 $param17=str_replace(".","", $param17);
 if($param112==''){  $param112=0; }

 $sql33="Select tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`='$param34'"; 
 $DB->Execute($sql33);
 $rw7=mysqli_fetch_row($DB->Consulta_ID); 

 if($param28==2){
	$precio=0;
		$sqlc="SELECT rel_nom_credito,idcreditos FROM `rel_sercre`  inner join creditos on cre_nombre=rel_nom_credito where rel_nom_credito='$param113' ";
  $DB->Execute($sqlc);
  $rw21=mysqli_fetch_row($DB->Consulta_ID); 
   $creditouser=$rw21[0];
   $idcredito=$rw21[1];

   if($rw7[0]>=10){

	@$preciokilo=$rw7[0];
	@$precioadicional=$rw7[1];

   }else{

	$kilos=$param26;
	$sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;"; 
	$DB->Execute($sqlprecios);
	$confipre=mysqli_fetch_row($DB->Consulta_ID); 
	$idprecios=$confipre[0];
	if($idprecios==0 or $idprecios==''){
		$idprecios=1;
	}

	$sql3="SELECT `pre_preciokilo`,`con_precios` FROM `precios_credito`  inner join `configuracionkilos` on con_idprecioskilos=idprecioscredito  WHERE   con_tipo='Credito'  and   `pre_idciudadori`='$param4'  and `pre_idciudades`='$param11' and pre_tiposervicio='$param34' and pre_idcredito='$idcredito' and con_idprecios='$idprecios' and pre_estado=1";
	$DB->Execute($sql3);
	$rw2=mysqli_fetch_row($DB->Consulta_ID);  

	@$preciokilo=$rw2[0];
	@$precioadicional=$rw2[1];
   }
  
  $kilosvolumen=$param26+$param27;
    if($param26>$precioinicialkilos){
  
	  @$precio1=($kilosvolumen-$precioinicialkilos)*$precioadicional;
	  @$precio=$preciokilo+$precio1;
  
  }else {
	@$precio1=$param27*$precioadicional;
	@$precio=$preciokilo+$precio1; 
  }
	  
}

if($precio=='' or $precio==null){
	$precio=0;
  }


  if($param34=='1000'){
	  
	$precio=str_replace(".","", $param111);

  }else{
	$precio=str_replace(".","", $param114);
  }
  
if($param27==''){
	$param27=0;
}
// para guardar imagen del paquete o servicio
if (is_uploaded_file($_FILES['param91']['tmp_name'])){
	// $imagen1 = md5(date("Y-m-d-H-i-s").$param3).".jpg";
	$nombreArchivo1 = $_FILES["param91"]["name"];
	$foto = date("Y-m-d-H-i-s").$nombreArchivo1;
   
	move_uploaded_file($_FILES['param91']['tmp_name'], "./imgServicios/".$foto);
 }else{
	 $foto = "";
 }
 if($nivel_acceso==3){ 
	echo "aki 4";

	$sql1="INSERT INTO `servicios` (`ser_iddocumento`,`ser_telefonocontacto`, `ser_destinatario`, `ser_direccioncontacto`,`ser_ciudadentrega`, `ser_tipopaquete`, `ser_paquetedescripcion`, `ser_fechaentrega`,`ser_prioridad`, 
	`ser_guiare`, `ser_valorabono`, `ser_valorseguro`,  `ser_fecharegistro`,`ser_peso`,ser_volumen,ser_idverificado,ser_idresponsable,ser_valor,`ser_estado`,ser_visto,ser_consecutivo,ser_pendientecobrar,ser_fechafinal,ser_clasificacion,ser_idverificadopeso,ser_piezas,ser_descripcion,ser_verificado,ser_tipopaq,ser_idregistro,ser_devolverreci,ser_fechaasignacion,ser_img_recog) 
   VALUES  ('$param7','$param8','$param9','$param10','$param11','$param12','$param13','$param14','$param15','$param16','$param17','$param18','$fechatiempo','$param26',$param27,$id_usuario,$id_usuario,$precio,$estado,0,'$planilla',$param112,'$fechatiempo','$param28',0,'$param29','$param31','$param32','$param33','$variableunica','$param25','$fechatiempo','$foto')";
   $idser=$DB->Executeid($sql1);

 }else {
	echo "aki 5";
  $sql1="INSERT INTO `servicios` (`ser_iddocumento`,`ser_telefonocontacto`, `ser_destinatario`, `ser_direccioncontacto`,`ser_ciudadentrega`, `ser_tipopaquete`, `ser_paquetedescripcion`, `ser_fechaentrega`,`ser_prioridad`, 
	`ser_guiare`, `ser_valorabono`, `ser_valorseguro`,  `ser_fecharegistro`,`ser_peso`,ser_volumen,ser_idverificado,ser_idresponsable,ser_valor,`ser_estado`,ser_visto,ser_consecutivo,ser_pendientecobrar,ser_fechafinal,ser_clasificacion,ser_idverificadopeso,ser_piezas,ser_descripcion,ser_verificado,ser_tipopaq,ser_idregistro,ser_devolverreci,ser_img_recog) 
   VALUES  ('$param7','$param8','$param9','$param10','$param11','$param12','$param13','$param14','$param15','$param16','$param17','$param18','$fechatiempo','$param26',$param27,$id_usuario,$id_usuario,$precio,$estado,0,'$planilla',$param112,'$fechatiempo','$param28',0,'$param29','$param31','$param32','$param33','$variableunica','$param25','$foto')";
   $idser=$DB->Executeid($sql1);
 }
 echo "aki 6";
  $sql2="INSERT INTO `rel_sercli`(`ser_idclientes`, `ser_idservicio`, `ser_fechaingreso`) VALUES ($idcli2,$idser,'$fechatiempo')";
  $DB1->Execute($sql2);
  echo "aki 7";
  //echo "joseee111";
    $sql3="INSERT INTO `guias`(`gui_idservicio`,`gui_idusuario`,`gui_usucreado`, `gui_fechacreacion`,`gui_recogio`, `gui_fecharecogio`,gui_tiposervicio,gui_usuvalida,gui_fechavalidacion,gui_usurecogida,gui_fecharecogida) 
		VALUES ($idser,'$id_usuario','$id_nombre','$fechatiempo','$id_nombre','$fechatiempo','$param34','$id_nombre','$fechatiempo','$id_nombre','$fechatiempo')";
	$DB->Executeid($sql3); 
  
	if($param17>0){
		$sql33="INSERT INTO `abonosguias`(`abo_fecha`, `abo_valor`, `abo_idservicio`, `abo_iduser`, `abo_idsede`, `abo_estado`)  VALUES ('$fechatiempo','$param17','$idser','$id_usuario','$id_sedes','abono')";
		$DB->Executeid($sql33);
	}

  	$param18=str_replace(".","", $param18);
	//$param16=str_replace(".","", $param16);


	$sql31="DELETE FROM `rel_sercre` WHERE  idservicio=$idser";
	$DB1->Execute($sql31);	
	echo "aki 8";

	$sql5="SELECT `cre_nombre` FROM `creditos` WHERE `idcreditos`=$param113";
	$DB1->Execute($sql5);
	$nombrecredito=$DB1->recogedato(0);

	$sql32="INSERT INTO rel_sercre (`idservicio`, `rel_nom_credito`) VALUES ($idser,'$nombrecredito')";
	$DB1->Execute($sql32);
	//echo "joseee";
	echo "aki 9";
   $sql2="INSERT INTO `cuentaspromotor`(`cue_idservicio`,`cue_idoperador`,`cue_abono`, `cue_porprestamo`, `cue_prestamo`,
	 `cue_vrdeclarado`,`cue_pordeclarado`,  `cue_valorflete`,  `cue_tipopago`,  `cue_fecha`,`cue_valpagar`,cue_estado, `cue_idciudadori`, `cue_idciudaddes`, `cue_tipoevento`, `cue_numeroguia`, `cue_fecharecogida`, `cue_pendientecobrar`,cue_transferencia) 
	VALUES ('$idser','$id_usuario','$param17','$porprestamo','0','$param18','$pordeclarado','$precio','$param15','$fechatiempo','$param26','4','$param4','$param11','$param28','$planilla','$fechatiempo','$param112','$namepago')";
	$DB->Execute($sql2);

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


	$res = guardarLinkServicio($DB, $idser, "Recogida", $idconsecutivo,$param26,$param27,$param18,$precio);
	$linkGuia = $res['url'];	

	if($tipopago>1){

		$sql5="INSERT INTO `pagoscuentas`(`pag_tipopago`,`pag_cuenta`, `pag_valor`, `pag_idoperario`, `pag_idservicio`,`pag_guia`, `pag_estado`, `pag_fecha`,pag_img_transaccion) 
		VALUES ('$tipopago','$cuenta','$precio','$id_usuario','$idser','$planilla','Contado','$fechatiempo','$img_transaccion')";
		$DB1->Execute($sql5);
	}
		 // Preparar la consulta SQL para insertar los datos en la tabla firma_clientes
		 echo$sql8 = "INSERT INTO firma_clientes (id_guia, tipo_firma, nombre, numero_documento,correo_electronico, telefono,enviar_whatsapp,foto) 
		 VALUES ('$idser', 'Recogida', '$param92', '', '', '$param93', '','')";
		 $idfirma=$DB->Executeid($sql8); 

		 $sql12 = "SELECT idservicios,ser_estado,cli_telefono,ser_consecutivo FROM `serviciosdia` WHERE  idservicios = '$idser'";			
			$DB1->Execute($sql12);
			$rw12 = mysqli_fetch_array($DB1->Consulta_ID);
			
			
			if ($rw12 === false) {
			}else {

					$numguia=$planilla;
					$telefono=$rw12[2];
					$idservicio=$idser;
					
					enviarAlertaWhat($numguia,$telefono,2,$idservicio);
					// enviarAlertaWhat($numguia,"3160490959",2,$idservicio);

				
			}
	$DB->cerrarconsulta();
	$DB1->cerrarconsulta();
	//$guia!=''

	// if($nivel_acceso!=3){
	// //header ("Location: inicio1.php?param15='$param15'");
	// 	header ("Location: imprimirfactura.php?param15='reenviar'&id_param=$idser");
	// } else {
		
	// 	$pagina2="configuracion.php?idmen=163";
	// 	header ("Location: ticketfactura.php?pagina2=$pagina2&id_param=$idser");
	// }

	if($nivel_acceso!=3){
		$pagina2="imprimirfactura.php";
		header ("Location: nueva_plataforma/view/recogerEntregar/firmar.php?para=$idser&accion=guardarFirmaRecogida&de=R");

		// header ("Location: firmadigital.php?pagina2=$pagina2&param15='reenviar'&id_param=$idser&idfirma=$idfirma&idguia=$planilla&imprimir=Recogida&p=$tipopago");
	} else {
		
		$pagina2="configuracion.php";
		// header ("Location: firmadigital.php?pagina2=$pagina2&id_param=$idser&idfirma=$idfirma&idguia=$planilla&imprimir=Recogida&p=$tipopago");
		header ("Location: nueva_plataforma/view/recogerEntregar/firmar.php?para=$idser&accion=guardarFirmaRecogida&de=R");
	
	}

 }else {

	header ("Location: inicio.php");
}


function guardarLinkServicio($DB, $id_param2, $imprimir, $idguia, $ser_peso, $ser_volumen, $ser_valorseguro, $ser_valor) {
    $guiaruta = "https://sistema.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$id_param2";
    $guiarutauser = "https://sistema.transmillas.com/ticketfacturacorreoimprimir.php?imprimir=$imprimir&id_param=$id_param2" .
                    "&peso={$ser_peso}&volumen={$ser_volumen}&seguro={$ser_valorseguro}&valorf={$ser_valor}";

    $sqls = "SELECT * FROM imagenguias WHERE ima_idservicio = $id_param2 AND ima_tipo LIKE '%$imprimir%'";
    $DB->Execute($sqls);
    $rws = mysqli_fetch_array($DB->Consulta_ID);

    if (!empty($rws[0])) {
        $sql_ins = "UPDATE imagenguias 
                    SET ima_ruta = '$guiaruta', 
                        ima_dir = '$guiarutauser', 
                        ima_nombre = '$idguia' 
                    WHERE ima_idservicio = $id_param2 
                    AND ima_tipo = '$imprimir'";
        $DB->Execute($sql_ins);
    } else {
        $sql_ins = "INSERT INTO imagenguias (ima_nombre, ima_ruta, ima_tipo, ima_fecha, ima_idservicio, ima_dir)
                    VALUES ('$idguia', '$guiaruta', '$imprimir', '" . date("Y-m-d") . "', '$id_param2', '$guiarutauser')";
        $DB->Execute($sql_ins);
    }

    return [
        "status" => "ok",
        "url" => $guiarutauser
    ];
}
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