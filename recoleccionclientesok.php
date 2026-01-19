<?php
require("login_autentica.php"); 
$id_nombre=$_SESSION['usuario_nombre'];
//@$id_usuario=$_REQUEST["id_usuario"];
$id_usuario=$_SESSION['usuario_id'];
$id_sedes=$_SESSION['usu_idsede'];
$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$bandera=1;
$variableunica=date("Y").date("m").date("d").date("h").date("i").date("s").$id_usuario;

@$id_ciudad=$_REQUEST["id_ciudad"];
 $param41=$_REQUEST["param41"];
$param42=$_REQUEST["param42"];
$param43=$_REQUEST["param43"];
$param44=$_REQUEST["param44"];	

$param5=$param5."&".$param51."&".$param19."&".$param20."&".$param23."&";  
$param5 = str_replace('&0&','&&', $param5);

$param25=0;
error_reporting(E_ALL);

// Habilitar la visualización de errores
ini_set('display_errors', 1);

// Opcional: Habilitar la visualización de los errores que ocurren durante la inicialización de PHP
ini_set('display_startup_errors', 1);

	 $sql1="UPDATE `clientes` SET  `cli_iddocumento`='$param41',`cli_email`='$param3', `cli_clasificacion`='2',
	`cli_tipo`='2', `cli_fecharegistro`='$fechatiempo',`cli_retorno`=$param25  WHERE `idclientes`='$id_param'";
	$DB->Execute($sql1);

	 $sql="UPDATE `clientesdir` SET  `cli_nombre`='$param6', `cli_telefono`='$param42',`cli_idciudad`='$param43', 
	 `cli_direccion`='$param5',  `cli_idclientes`='$id_param', `cli_principal`=1 where `idclientesdir`='".$_REQUEST['id_param2']."'";
	$DB->Execute($sql);
	$idcli=$_REQUEST['id_param2'];	
	$idexec=$id_param;



	 $sql2="INSERT INTO `clientesservicios` (`cli_nombre`, `cli_telefono`,`cli_idciudad`, `cli_direccion`,  `cli_idclientes`, `cli_principal`) 
	VALUES ('$param6','$param42','$param43','$param5','$idexec',1)";
	$idcli2=$DB->Executeid($sql2);

$param17=str_replace(".","", $param17);
//$param10=$param10."&".$param101."&".$param21."&".$param22."&".$param24; 
$param10=$param10."&".$param101."&".$param21."&".$param22."&".$param24."&"; 
$param10 = str_replace('&0&','&&', $param10);
if($param8!=''){

	$sql3="SELECT idclientes From clientes inner join clientesdir on cli_idclientes=idclientes where cli_telefono='$param8'";
   $DB->Execute($sql3);
   $valorinser=$DB->recogedato(0);
   if($valorinser<=0){

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
		`cli_direccion`='$param10',   `cli_principal`=0  where `cli_idclientes`='$valorinser'";
	   $DB->Execute($sql);
	   
   }
}	

	$param28=2;


   $sql1="INSERT INTO `servicios`(`ser_iddocumento`,`ser_telefonocontacto`, `ser_destinatario`, `ser_direccioncontacto`,`ser_ciudadentrega`, `ser_tipopaquete`, `ser_paquetedescripcion`, `ser_fechaentrega`,`ser_prioridad`,  `ser_valorprestamo`, `ser_valorabono`, `ser_valorseguro`,  `ser_fecharegistro`,ser_clasificacion,ser_estado,ser_piezas,ser_idregistro,ser_codeCliente) 
 VALUES
 ('$param7','$param8','$param9','$param10','$param11','$param12','$param13','$param14','$param15','$param16','0','$param18','$fechatiempo','$param28','2','$param30','$variableunica','$param45')";
 $idser=$DB->Executeid($sql1);

   $sql2="INSERT INTO `rel_sercli`(`ser_idclientes`, `ser_idservicio`, `ser_fechaingreso`) VALUES ($idcli2,$idser,'$fechatiempo')";
	$DB1->Execute($sql2);
	
	 $sql32="INSERT INTO rel_sercre (`idservicio`, `rel_nom_credito`) VALUES ($idser,'$param44')";
	$DB1->Execute($sql32);
	$param27=0;
    $sql3="INSERT INTO `guias`(`gui_idservicio`, `gui_idusuario`,`gui_usucreado`, `gui_fechacreacion`,gui_tiposervicio,gui_usuvalida,gui_fechavalidacion)  VALUES ($idser,'$id_usuario','$id_nombre','$fechatiempo','$param27','$id_nombre','$fechatiempo') ";
	$DB->Executeid($sql3);


/* 	$sql31="Select idcuentaspromotor from cuentaspromotor where cue_idservicio=$idser";
	$DB->Execute($sql31);
	$cuepromotor=$DB->recogedato(0);
	if($cuepromotor>0 and $param28==2){
		$sqlc="UPDATE `cuentaspromotor` SET  `cue_tipoevento`='2' where  cue_idservicio=$idser ";
		$DB->Execute($sqlc);
		} */
		$DB->cerrarconsulta();
		$DB1->cerrarconsulta();

//hacer consulta para saber si es es el credito Croydon o Mauricio
if ($param44=="SWISSJUST LATINOAMERICA" or $param44=="CROYDON") {
	asignar($idser,$fechatiempo);

	// echo"SE EJECUTA";
}
		
		

function asignar($id_param2,$fechatiempo){
	$DB = new DB_mssql;
    $DB->conectar();
	$DB1 = new DB_mssql;
    $DB1->conectar();

    $idsede = $_REQUEST["idciudad"];



	$param2=$_SESSION['usuario_id'];
	// $fechatiempo=date("Y-m-d H:i:s");
	$id_nombre=$_SESSION['usuario_nombre'];
	$fechaactual=date("Y-m-d H:i:s");
	echo$asigna1="UPDATE `servicios` SET `ser_idresponsable`='$param2',`ser_fechaasignacion`='$fechatiempo',`ser_estado`='3',ser_visto=0 WHERE `idservicios`=$id_param2";
	
	$DB->Execute($asigna1);

	
   $asigna2="UPDATE `guias` SET `gui_usurecogida`='$id_nombre',`gui_fecharecogida`='$fechatiempo',gui_recogio='' WHERE `gui_idservicio`='$id_param2'";
	  $DB1->Executeid($asigna2); 
  
	//   $sqlr="SELECT idseguimientoruta FROM `seguimientoruta` where seg_idservicio='$id_param2' and  seg_tipo='Recogida' and seg_fecha like '%$fechaactual%'";
	//   $DB->Execute($sqlr); 
	//   $segui=mysqli_fetch_row($DB->Consulta_ID);	
	//   if($segui[0]>=0){
	// 	  $mensaje2='Reasignada por el Usuario:'.$id_nombre;
	// 	  $idseg=$segui[0];
	// 	  $sqlup = "UPDATE  `seguimientoruta`  set seg_estado='Reasignada',seg_fechafinalizo='$fechatiempo',seg_descripcion='$mensaje' where idseguimientoruta='$idseg'";
	// 	  $DB1->Execute($sqlup);	
  
	//   }
  
	// 	   $sqlse = "INSERT INTO `seguimientoruta`( `seg_fecha`, `seg_idservicio`, `seg_direccion`, `seg_tipo`, `seg_estado`,`seg_idusuario`) values ('$fechatiempo','$id_param2','$mensaje3','Recogida','Asignada','$param2')";
	// 	  $DB1->Execute($sqlse);	

		  $DB->cerrarconsulta();
		  $DB1->cerrarconsulta();
	  
		   recoger($id_param2,$fechatiempo);
}

function recoger($idser,$fechatiempo){
	
	$DB = new DB_mssql;
    $DB->conectar();
	$DB1 = new DB_mssql;
    $DB1->conectar();
	$hora=date("H:m");
	$precioinicialkilos=$_SESSION['precioinicial'];
		$sql4="SELECT `ser_paquetedescripcion`,`ser_valorprestamo`,`ser_valorabono`,`ser_valorseguro`,ser_clasificacion,ser_ciudadentrega,ser_devolverreci,cli_idciudad,ser_prioridad,ser_idresponsable,ser_tipopaq,ser_volumen,ser_verificado,ser_piezas,ser_guiare,ser_consecutivo,gui_tiposervicio,ser_valor,ser_peso,ser_estado,ser_pendientecobrar 
		FROM  servicios inner join rel_sercli  on idservicios=ser_idservicio  inner join clientesservicios on idclientesdir=ser_idclientes  inner join guias on idservicios=gui_idservicio WHERE idservicios=$idser";
			$DB1->Execute($sql4);
			$rw=mysqli_fetch_row($DB1->Consulta_ID);  


			$sql12="SELECT cue_transferencia from cuentaspromotor where cue_idservicio=$idser";
			$DB->Execute($sql12);
			$servicio=mysqli_fetch_row($DB->Consulta_ID); 
   
		   if($rw[20]==1){
			   $pagot='PendienteXCobrar';
		   }elseif($servicio[0]=='' and $rw[4]==1){
			   $pagot='Efectivo';
		   }elseif($servicio[0]=='' and $rw[4]!=1){
			   if($rw[4]=='2'){
				   $pagot='Credito';
			   }elseif($rw[4]=='3'){
				   $pagot='Al cobro';
			   }elseif($rw[4]=='1'){
				   $pagot='Contado';
			   }else{
				   $pagot=$rw[4];
			   }
   
		   }
		   else{
   
			   $pagot=$servicio[0];
		   }
   
			$datosg="Peizas: $rw[13] | Peso: $rw[18] | Volumen: $rw[11] | Seguro: $rw[3] | Pago : $pagot";



			// $FB->titulo_azul1("Datos ",14,0, 5); 
			// if($nivel_acceso==1){
			// 	$FB->llena_texto("Fecha Recogida:", 28, 10, $DB, "", "", $fechaactual, 1,0);
			// }
			
			$param17=$rw[8];

			if($rw[13]==0){ $rw[13]=''; }
			if($rw[16]=='1000'){
				$param113=$rw[13];
				$param2=$rw[13];
			}else{
				$param2=$rw[13];
			}
			$param19=$rw[12];	

			$param21=$rw[10];
			$param3=$rw[0];
			$param4=$rw[1];
			
			if(@$rw[3]==''){
				$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
				$DB1->Execute($sql12);
				$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
				$seguro=$porcentaje['0'];
			} else{
				$seguro=$rw[3];
			}
			$param6=$seguro;

			$param7="$hora";
			$param29=$rw[6];
			$param25=$rw[14];	
			$param26="";

			$param44=str_replace(".","", $rw[1]);
			$param55=str_replace(".","", $rw[2]);
			$param66=str_replace(".","", $rw[3]);

			/* if($nivel_acceso==3){
				$FB->llena_texto("Abono:",14, 118, $DB, "", "", $rw[2], 1, 2);
			}else {

				$FB->llena_texto("Abono:",14, 118, $DB, "", "", $rw[2], 1, 0);
			} */

			$param14=$rw[2];

			
			if($rw[8]=='Compra'){
			$param17="0";
			}else
			{
				$param17= 0;
			}
			$tipofiltro=217;
			// if($nivel_acceso==3){
			// 	 $sqls1="SELECT idelementostrabajo FROM `elementostrabajo` inner join hojadevida on idhojadevida=ele_idhojavida inner join usuarios on hoj_cedula=usu_identificacion 
			// 	where ele_nombre like '%datafono%' and idusuarios='$id_usuario' ";
			// 	$DB->Execute($sqls1);
			// 	 $iddatafono=$DB->recogedato(0);
			// 	($iddatafono>=1)?$tipofiltro=217:$tipofiltro=213;
			// 	//echo $iddatafono;

			// }

			if($rw[16]=='1000'){
				$param35="$rw[17]"; 
				$param36="0"; 
			}else{

				/*   $sql3="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios  WHERE con_tipo='normal'  and  `pre_idciudadori`='$rw[7]'  and `pre_idciudaddes`='$rw[5]'  and pre_tiposervicio='$rw[16]'";
				$DB->Execute($sql3);
				$rw2=mysqli_fetch_row($DB->Consulta_ID);  */

				$param35=""; 
				$param36=""; 



				$sql6="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$rw[1]' and `pre_final`>='$rw[1]'";
				$DB->Execute($sql6);
				$porprestamo=$DB->recogedato(0);
			
				$dosporcentaje=explode(" ",$porprestamo); 
			
				if(@$dosporcentaje[1]=='%'){
					$porprestamo=($rw[1]*@$dosporcentaje[0])/100;
				}
			
			//	echo "<br>porprestamo::".$porprestamo;
			//	echo "<br>pordeclarado::".$pordeclarado=(intval($param66)*1)/100;

			}

			$param10="";
			$param20="";

			if($rw[16]=='1000'){
				$param112="$rw[17]";	
			}else{
				$param112="";	
			}

			if($rw[4]!=2){
				$param8="";

			}else{
				$param8="2";
			}

		
			$param9=$rw[5];
			// $FB->cierra_tabla();
			$param2=$rw[13];
			$param11= $rw[17];
			$param13= $rw[7];
			$param44= $param44;
			$param53= $precioinicialkilos;

			$param54= $rw[2]; //abono
			$param55= $param55;
			$param66=0;
			$param77= $porprestamo;
			$param15=$rw[8];
			$param16=$rw[1];
			$param18=$rw[9];
			$param27=$rw[15];
			$param34="$rw[16]";  // tipoidservicio





































































		$kilos=$param10+$param20;
		if($param27!=''){
			$planilla=$param27;
		}else{
			$sql7="SELECT `idconfac`, `idconsecutivo`, `idresolucion`, `prefijo`,inner_sedes FROM `conf_fac`  inner join ciudades on idciudad=inner_sedes WHERE idciudades='$param13'";	
			$DB1->Execute($sql7);
			$rw1=mysqli_fetch_array($DB1->Consulta_ID);	

			$planilla="$rw1[3]$rw1[1]";
			$idconsecutivo=$rw1[1]+1;


			if($idconsecutivo>=10){
				$sql2="UPDATE conf_fac inner join ciudades on idciudad=inner_sedes  SET `idconsecutivo`=$idconsecutivo  WHERE idciudades='$param13'";	
				$DB1->Execute($sql2);
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
			// if($param12=='')
			// {
				$param12=0;
			// }
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
				$DB1->Execute($sqlprecios);
				$confipre=mysqli_fetch_row($DB1->Consulta_ID); 
				$idprecios=$confipre[0];
				
			}
			
			if($idprecios==0 or $idprecios==''){
				$idprecios=1;
			}

			$sql32="Select gui_tiposervicio from guias WHERE `gui_idservicio`='$idser'"; 
			$DB1->Execute($sql32);
			$rw6=mysqli_fetch_row($DB1->Consulta_ID); 

			$sql33="SELECT tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`='$rw6[0]'"; 
			$DB1->Execute($sql33);
			$rw7=mysqli_fetch_row($DB1->Consulta_ID); 

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
					
					$sql8="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios 
					WHERE con_tipo='normal'  and  `pre_idciudadori`='$param13'  and `pre_idciudaddes`='$param9'  and pre_tiposervicio='$rw6[0]' and con_idprecios='$idprecios'  AND pre_estado = 1";
						$DB1->Execute($sql8);
						$rw2=mysqli_fetch_row($DB1->Consulta_ID); 
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
					$sql9="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param16' and `pre_final`>='$param16'";
					$DB1->Execute($sql9);
					$porprestamo=$DB1->recogedato(0);
					$dosporcentaje=explode(" ",$porprestamo); 
			
					if(@$dosporcentaje[1]=='%'){
						$porprestamo=($param16*@$dosporcentaje[0])/100;
					}
					
				}else {
					$porprestamo=0;
				}

				$pordeclarado=(intval($param6)*1)/100;

				$sql21="DELETE FROM `cuentaspromotor` WHERE  cue_idservicio=$idser";
			$DB1->Execute($sql21);	
			
			// if($nivel_acceso==1){
			// 	$fechatiempo=$param28;
			// 	$sql10="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$param18' and  (usu_estado=1 or usu_filtro=1)";
			// 	$DB1->Execute($sql10);
			// 	$id_nombre=$DB1->recogedato(1);
				
			// 	//$param18=$id_usuario;
			// } else if($nivel_acceso!=3 and $nivel_acceso!=1){

			// 	$param18=$id_usuario;

			// }

			$pagos=explode('|',$param30);
			$tipopago=$pagos[0];
			$cuenta=$pagos[1];
			$namepago=$pagos[2];
			if($param8==1){
				$estadop='Contado';
			}elseif($param8==3){
				$estadop='Al Cobro';

			}

			$sql11="INSERT INTO `cuentaspromotor`(`cue_idservicio`,`cue_idoperador`,`cue_abono`, `cue_porprestamo`, `cue_prestamo`,
			`cue_vrdeclarado`,`cue_pordeclarado`,  `cue_valorflete`,  `cue_tipopago`, `cue_pendientecobrar`,  `cue_fecha`,`cue_valpagar`,cue_estado, `cue_idciudadori`, `cue_idciudaddes`, `cue_tipoevento`, `cue_numeroguia`, `cue_fecharecogida`,`cue_transferencia`,cue_kilostotal) 
			VALUES ('$idser','$param18','$param14',$porprestamo,'$param16','$param6','$pordeclarado','$valortotal','$param15','$param12','$fechatiempo','$param17','4','$param13','$param9','$param8','$planilla','$fechatiempo','$namepago',$kilos)";
			$DB1->Execute($sql11);

			$sql12="UPDATE `guias` SET `gui_recogio`='$id_nombre',`gui_fecharecogio`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
				$DB1->Execute($sql12);
			if($param29==''){ $param29=0;}

			

			if($param8==1 and $tipopago>1){


				

				if ($_FILES['param40']['error'] === UPLOAD_ERR_OK) {
				$img_transaccion = $_FILES['param40']['name'];
				
				$rutaArchivo = 'img_transacciones/' . $img_transaccion;

				if (move_uploaded_file($_FILES['param40']['tmp_name'], $rutaArchivo)) {
					
				} else {
					
					$img_transaccion = "";
				}
				} else {
					$img_transaccion = "";
				}

				$sql51="DELETE FROM `pagoscuentas` WHERE  pag_idservicio=$idser";
				$DB1->Execute($sql51);	

				$sql13="INSERT INTO `pagoscuentas`(`pag_tipopago`,`pag_cuenta`, `pag_valor`, `pag_idoperario`, `pag_idservicio`,`pag_guia`, `pag_estado`, `pag_fecha`,`pag_img_transaccion`) VALUES ('$tipopago','$cuenta','$param11','$id_usuario','$idser','$planilla','$estadop','$fechatiempo','$img_transaccion')";
				$DB1->Execute($sql13);
			}
			
			// para guardar imagen del paquete o servicio
			// if (is_uploaded_file($_FILES['param91']['tmp_name'])){
			// 	// $imagen1 = md5(date("Y-m-d-H-i-s").$param3).".jpg";
			// 	$nombreArchivo1 = $_FILES["param91"]["name"];
			// 	$foto = date("Y-m-d-H-i-s").$nombreArchivo1;
			
			// 	move_uploaded_file($_FILES['param91']['tmp_name'], "./imgServicios/".$foto);
			// }else{
			// 	$foto = "";
			// }
			$sql14="UPDATE `servicios` SET `ser_consecutivo`='$planilla',`ser_resolucion`='$rw1[2]',`ser_recogida`='$param1',  `ser_paquetedescripcion`='$param3', `ser_valorseguro`='$param6', `ser_horaentrega`='$param7', `ser_clasificacion`='$param8',`ser_fechafinal`='$fechatiempo',`ser_fechaasignacion`='$fechatiempo',`ser_estado`='4',ser_devolverreci='$param29',ser_tipopaq='$param21',ser_verificado='$param19',ser_volumen='$param20',ser_guiare='$param25',ser_descripcion='$param26',ser_idresponsable='$param18'  $cond1 $cond WHERE `idservicios`=$idser";
			$DB1->Executeid($sql14);
				
				 // Preparar la consulta SQL para insertar los datos en la tabla firma_clientes
				//  echo$sql16 = "INSERT INTO firma_clientes (id_guia, tipo_firma, nombre, numero_documento,correo_electronico, telefono,enviar_whatsapp,foto) 
				//  VALUES ('$idser', 'Recogida', '$param92', '', '', '$param93', '','')";
				//  $idfirma=$DB->Executeid($sql16); 
			// if($nivel_acceso==3){
				
			// 	$dir="ticketfactura.php";
			// 	$pagina2="inicio.php";
				
			// }else {
			// 	$dir="recogerpaquete.php";
			// 	$pagina2="recogerpaquete.php";
				
			// }
			
			$sql15="UPDATE `seguimientoruta` SET `seg_guia`='$planilla',`seg_estado`='completado',`seg_fechafinalizo`='$fechatiempo' WHERE `seg_idservicio`='".$idser."' AND  seg_tipo='Recogida' and seg_estado!='Cambioruta' AND seg_fecha like '%$fechaactual%'";
			// $DB1->Executeid($sql15); 

			$DB1->Executeid($sql15);

	
			header("Location: recogidascliente.php?bandera=1");
			exit(); // Detenemos la ejecución del script después de la redirección
			// 
}


?>