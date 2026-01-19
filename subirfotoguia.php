<?php 
require("login_autentica.php"); 
include("layout.php");
$fechaactual=date("Y-m-d");
?>
<head>

	</head>
<body onload="cambio_ajax2(0, 16, 'llega_sub1', 'param1', 1, <?php echo $param1;?>);">

<?php 

$FB->abre_form("form1","","post");
$FB->titulo_azul1(" Modulo de subir fotos Guias",9,0,5);  



 $conde2="";

//if($param3!='' and $param3!='0'){ $fechaactual=$param3;  } else { $param3=$fechaactual; }


if($param5=='' or $param5=='0') { $param5=$fechaactual; }
if($param3=='' or $param3=='0') { $param3=$fechaactual; }


if($param4!=''){ 
	
	$idcidades=ciudadesedes($param4,$DB);
	$cond3=" And cue_idciudadori in $idcidades "; 
	$cond4=" And cue_idciudaddes in $idcidades "; 

}
if(isset($_REQUEST["param4"])){ if($param4!=""){   $id_sedes=$param4; } } else {$param4=""; }


if($nivel_acceso==1 or $nivel_acceso==10 or $nivel_acceso==12 or $nivel_acceso==9){ $conde2=""; 	 } else { $conde2=" and idsedes=$id_sedes";  }
	
if($nivel_acceso==1 or $nivel_acceso==9 or $nivel_acceso==10 ) {
	$FB->llena_texto("Fecha Inicinio:", 3, 10, $DB, "", "", "$param3", 1, 0);
	$FB->llena_texto("Fecha Final:", 5, 10, $DB, "", "", "$param5", 4, 0);
	$FB->llena_texto("Sede :",4,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio_ajax2(this.value, 16, \"llega_sub1\", \"param1\", 1, 0)", "$param4",1, 0);
	$FB->llena_texto("Operario:", 1, 4, $DB, "llega_sub1", "", "$param1",4,0);
	//$FB->llena_texto("Operario:",1,2, $DB, "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `roles_idroles` in (2,3,5) and  (usu_estado=1 or usu_filtro=1) ", "cambio3(this.value,param2.value,0,\"subirfotoguia.php\");", $param1, 1, 1);
	$FB->llena_texto("Estado:",2,8,$DB,$estado_foto,"",$param2,1,0);
	$FB->llena_texto("Cliente:",8, 280, $DB, "(SELECT `cre_nombre`,`cre_nombre` FROM `creditos` inner join `hojadevidacliente` on hoj_clientecredito =idcreditos where hoj_estado='Activo' )", "", "$param8",4,0);
	// $FB->llena_texto("Estado servicio:",10,8,$DB,$estado_servis,"",$param10,1,0);

	$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
	$conde3="";
}else{

	echo '<input type="hidden" name="param1" id="param1" value="'.$id_usuario.'">';
	echo '<input type="hidden" name="param5" id="param5" value="'.$fechaactual.'">';
	echo '<input type="hidden" name="param3" id="param3" value="'.$fechaactual.'">';
	$FB->llena_texto("Estado:",2,8,$DB,$estado_foto,"cambio3(param1.value,this.value,0,\"subirfotoguia.php\");",$param2,1,0);
}



$FB->cierra_form(); 
$FB->titulo_azul1("#",1,0,7); 
$FB->titulo_azul1("Fecha",1,0,0); 
$FB->titulo_azul1("# Guia",1,0,0); 
$FB->titulo_azul1("Tipo",1,0,0); 
if ($param8!="") {

	$FB->titulo_azul1("Recogida",1,0,0);
	$FB->titulo_azul1("Validada",1,0,0);
	$FB->titulo_azul1("Entrega",1,0,0);
	$FB->titulo_azul1("Validada",1,0,0);

}else{

	$FB->titulo_azul1("Ver Foto",1,0,0); 

	if($nivel_acceso==1 or $nivel_acceso==9){
		$FB->titulo_azul1("Validada",1,0,0); 
		
	}
	$FB->titulo_azul1("Eliminar",1,0,0); 
}









if($param2!='Subidas'){
	$param2='Faltantes';
}

//$fechaactual='2021-01-08';
$param5=$param5.' 23:59:59';
if($param1!='0' and $param1!=''){
	$cond1="And (cue_idoperador='$param1' and cue_fecharecogida >= '$param3'  and cue_fecharecogida <= '$param5' )";
	$cond2="And  (cue_idoperentrega='$param1' and cue_fecha >= '$param3' and  cue_fecha <= '$param5' )";
}else{
	if($nivel_acceso==1 or $nivel_acceso==9 or $nivel_acceso==10) {

	//	$cond1="And ( (cue_fecharecogida like '$fechaactual%') or (cue_fecha like '$fechaactual%'))";
		$cond1="And (cue_fecharecogida >= '$param3'  and cue_fecharecogida <= '$param5')";
	    $cond2="And (cue_fecha >= '$param3' and   cue_fecha <= '$param5')";
	}else{
		
		//$cond1="And ((cue_idoperador='$id_usuario' and cue_fecharecogida like '$fechaactual%') or (cue_idoperentrega='$id_usuario' and cue_fecha like '$fechaactual%'))";
		$cond1="And (cue_idoperador='$id_usuario' and cue_fecharecogida >= '$param3'  and cue_fecharecogida <= '$param5')";
		$cond2="And  (cue_idoperentrega='$id_usuario' and cue_fecha >= '$param3' and  cue_fecha <= '$param5')";
	}
}
/*     $sql="SELECT `idservicios`,ser_fechafinal,`ser_guiare`, ser_estado,ima_ruta,ima_idservicio,idimagenguias FROM servicios
 inner join usuarios on idusuarios=ser_idresponsable  left join imagenguias on idservicios=ima_idservicio   where ser_estado in (4,6,9,10) and ser_fechafinal like '$fechaactual%' $cond $cond1 ORDER BY ser_fecharegistro $asc ";
 */
$sql23="DELETE FROM rel_sercre WHERE idservicio='".$_REQUEST['id_param2']."'";
$DB1->Execute($sql23);
if ($param8!="") {

// 	if ($param10!="") {
		
// 		if ($param10=="Recogida") {
// 				$sql="SELECT 
// 			a.cue_idservicio,
// 			a.cue_fecharecogida,
// 			a.cue_fecha,
// 			a.cue_numeroguia,
// 			a.cue_estado,
// 			a.tipo
// 			FROM (
// 			SELECT 
// 				`cue_idservicio`,
// 				`cue_fecharecogida`,
// 				`cue_fecha`,
// 				`cue_numeroguia`,
// 				`cue_estado`,
// 				'Recogida' AS tipo 
// 			FROM `cuentaspromotor`
// 			WHERE `cue_estado` IN (4, 6, 7, 8, 9, 10) $cond1 $cond3
		
// 			) a
// 			LEFT JOIN `rel_sercre` b ON a.cue_idservicio = b.idservicio
// 			WHERE b.rel_nom_credito = '$param8'
// 			ORDER BY a.cue_fecharecogida $asc";
// 		}else if ($param10=="Entregas"){
			$sql="SELECT 
			a.cue_idservicio,
			a.cue_fecharecogida,
			a.cue_fecha,
			a.cue_numeroguia,
			a.cue_estado,
			a.tipo
			FROM (
		
			SELECT 
				`cue_idservicio`,
				`cue_fecharecogida`,
				`cue_fecha`,
				`cue_numeroguia`,
				`cue_estado`,
				'Entrega' AS tipo 
			FROM `cuentaspromotor`
			WHERE `cue_estado` IN (9, 10) $cond2 $cond4
			) a
			LEFT JOIN `rel_sercre` b ON a.cue_idservicio = b.idservicio
			WHERE b.rel_nom_credito = '$param8'
			ORDER BY a.cue_fecharecogida $asc";




// 		}
















// 	}else{
// 		$sql="SELECT 
// 		a.cue_idservicio,
// 		a.cue_fecharecogida,
// 		a.cue_fecha,
// 		a.cue_numeroguia,
// 		a.cue_estado,
// 		a.tipo
// 		FROM (
// 		SELECT 
// 			`cue_idservicio`,
// 			`cue_fecharecogida`,
// 			`cue_fecha`,
// 			`cue_numeroguia`,
// 			`cue_estado`,
// 			'Recogida' AS tipo 
// 		FROM `cuentaspromotor`
// 		WHERE `cue_estado` IN (4, 6, 7, 8, 9, 10) $cond1 $cond3
	
// 		UNION
	
// 		SELECT 
// 			`cue_idservicio`,
// 			`cue_fecharecogida`,
// 			`cue_fecha`,
// 			`cue_numeroguia`,
// 			`cue_estado`,
// 			'Entrega' AS tipo 
// 		FROM `cuentaspromotor`
// 		WHERE `cue_estado` IN (9, 10) $cond2 $cond4
// 	) a
// 	LEFT JOIN `rel_sercre` b ON a.cue_idservicio = b.idservicio
// 	WHERE b.rel_nom_credito = '$param8'
// 	ORDER BY a.cue_fecharecogida $asc";
//    }
}else{

	$sql="SELECT `cue_idservicio`,cue_fecharecogida,`cue_fecha`,`cue_numeroguia`, cue_estado,'Recogida' as tipo FROM cuentaspromotor
	where cue_estado in (4,6,7,8,9,10)   $cond1 $cond3
  union
  SELECT `cue_idservicio`,cue_fecharecogida,`cue_fecha`,`cue_numeroguia`, cue_estado,'Entrega' as tipo FROM cuentaspromotor
   where cue_estado in (9,10)  $cond2 $cond4 ORDER BY cue_fecharecogida $asc
   ";
}






$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
	
		$id_p=$rw1[0];
	


		if ($param8!="") {

			$va++;
			$p=$va%2;
			if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		//	if(is_null($rw2[0])){

				echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				$tipo=$rw1[5];
				if($tipo=="Recogida"){
					$fecha=$rw1[1];
				}else{
					$fecha=$rw1[2];
				}
				echo "<td>".$va."</td>
				<td>".$fecha."</td>
				<td>".$rw1[3]."</td>
				<td>---</td>
				
				";
				$image="SELECT `idimagenguias`, `ima_nombre`, `ima_ruta`, `ima_tipo`, `ima_fecha`, `ima_idservicio`,ima_validado FROM `imagenguias` WHERE ima_idservicio='$id_p' and ima_tipo='Recogida'";
				$DB1->Execute($image); 
				$rw2=mysqli_fetch_row($DB1->Consulta_ID);
				if( is_null($rw2[0])){
					$recogida="<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"Recogida\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia </a><br> <br><button onclick='abrirPopup(\"".$rw1[11]."\",\"$tipo\",$id_p)'>Ratificar</button>";
					$validarecogida="NO";
				}else {

							// Verificar fecha para imagen
							{
								$fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
								$fecha_image_entrada = strtotime($rw2[4]);
		
								if($fecha_image_entrada >= $fecha_inicio_mes) {
									$urlImagen = $rw2[2];
								} else {
									// $urlImagen = VISUALIZADOR_IMAGENES . '?tipo=guias&id=' . $rw2[0];
									$urlImagen = $rw2[2];

								}
							}

				// echo "<td align='center' >";
				// echo "<a href='{$urlImagen}' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a></td>";	



					// $recogida="<a href='{$urlImagen}' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a> <br>  <br> <a href='del_admin.php?id_param=$rw2[0]&tabla=Elimina Archivo2&ruta=$rw2[2]' title='Eliminar' 
					// onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a>";
					$recogida="<a href='https://sistema.transmillas.com/nueva_plataforma/controller/VerguiaController.php?guia=".$rw1[3]."R' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a> <br>  <br> <a href='del_admin.php?id_param=$rw2[0]&tabla=Elimina Archivo2&ruta=$rw2[2]' title='Eliminar' 
					onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a>";		

					$validarecogida="SI";



				}
				$imagee="SELECT `idimagenguias`, `ima_nombre`, `ima_ruta`, `ima_tipo`, `ima_fecha`, `ima_idservicio`,ima_validado FROM `imagenguias` WHERE ima_idservicio='$id_p' and ima_tipo='Entrega'";
				$DB1->Execute($imagee); 
				$rw2e=mysqli_fetch_row($DB1->Consulta_ID);
				if( is_null($rw2e[0])){
					$entrega="<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"Entrega\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia</a><br><button onclick='abrirPopup(\"".$rw1[3]."\",\"$tipo\",$id_p)'>Ratificar</button>";
					$validaentrega="NO";
				
				}else {
					{
						$fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
						$fecha_image_entrada = strtotime($rw2e[4]);

						if($fecha_image_entrada >= $fecha_inicio_mes) {
							$urlImagene = $rw2e[2];
						} else {
							// $urlImagene = VISUALIZADOR_IMAGENES . '?tipo=guias&id=' . $rw2e[0];
							$urlImagene = $rw2e[2];

						}
					}
					

		// echo "<td align='center' >";
		// echo "<a href='{$urlImagen}' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a></td>";	




					// $entrega="<a href='{$urlImagene}' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagene."' target='_blank'>✏️</a>  <br>  <br> <a href='del_admin.php?id_param=$rw2e[0]&tabla=Elimina Archivo2&ruta=$rw2e[2]' title='Eliminar' 
					// onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a>";
					$entrega="<a href='https://sistema.transmillas.com/nueva_plataforma/controller/VerguiaController.php?guia=".$rw1[3]."E' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagene."' target='_blank'>✏️</a>  <br>  <br> <a href='del_admin.php?id_param=$rw2e[0]&tabla=Elimina Archivo2&ruta=$rw2e[2]' title='Eliminar' 
					onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a>";
					$validaentrega="SI";


	




				}

				echo"<td>$recogida</td>";
				if ($validarecogida=="SI") {
					echo "<td><div id='campo$va'>"; if($rw2[6]=='SI') { $st="SI"; $colorfondo="#941727"; } else { $st="NO"; $colorfondo="#074f91"; } 
					echo "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'
					name='param14' id='param14'  onChange='cambio_ajax2(this.value, 81, \"campo$va\", \"$va\", 1, $id_p)'  required>";
						$LT->llenaselect_ar($st,$estado_rec);
					echo "</select></div></td>";
				}else {
					echo"<td>NO</td>";
				}
				
				echo"<td>$entrega</td>";
				if ($validaentrega=="SI") {
					echo "<td><div id='campo$va'>"; if($rw2e[6]=='SI') { $ste="SI"; $colorfondoe="#941727"; } else { $ste="NO"; $colorfondoe="#074f91"; } 
					echo "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondoe;color:#f9f9f9;font-size:18px'
					name='param14' id='param14'  onChange='cambio_ajax2(this.value, 81, \"campo$va\", \"$va\", 1, $id_p)'  required>";
						$LT->llenaselect_ar($ste,$estado_rec);
					echo "</select></div></td>";
				}else {
					echo"<td>NO</td>";
				}
				




		}else{

			$image="SELECT `idimagenguias`, `ima_nombre`, `ima_ruta`, `ima_tipo`, `ima_fecha`, `ima_idservicio`,ima_validado FROM `imagenguias` WHERE ima_idservicio='$id_p' and ima_tipo='$rw1[5]'";
			$DB1->Execute($image); 
			$rw2=mysqli_fetch_row($DB1->Consulta_ID);

			if($param2=='Faltantes' and is_null($rw2[0])){

				$va++;
				$p=$va%2;
			if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
			//	if(is_null($rw2[0])){

					echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
					$tipo=$rw1[5];
					if($tipo=="Recogida"){
						$fecha=$rw1[1];
					}else{
						$fecha=$rw1[2];
					}
					echo "<td>".$va."</td>
					<td>".$fecha."</td>
					<td>".$rw1[3]."</td>
					<td>".$tipo."</td>
					
					";
					$guiatipo=$rw1[3]."_$tipo";
					echo "<td align='center' >";
					echo "<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"$guiatipo\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia</a><br><button onclick='abrirPopup(\"".$rw1[3]."\",\"$tipo\",$id_p)'>Ratificar</button></td>";
					if($nivel_acceso==1 or $nivel_acceso==9){
						echo "<td>NO</td>";
					}
					echo "<td align='center' >SIn Imagen</td>";
					
					echo "</tr>"; 
			//	}

			}else if($param2=='Subidas' and !is_null($rw2[0])){

			$va++;
			$p=$va%2;
			if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
				echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				$tipo=$rw1[5];
				if($tipo=="Recogida"){
					$fecha=$rw1[1];
				}else{
					$fecha=$rw1[2];
				}
				echo "<td>".$va."</td>
				<td>".$fecha."</td>
				<td>".$rw1[3]."</td>
				<td>".$tipo."</td>
				";

							// Verificar fecha para imagen
							{
								$fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
								$fecha_image_entrada = strtotime($rw2[4]);
		
								if($fecha_image_entrada >= $fecha_inicio_mes) {
									$urlImagen = $rw2[2];
								} else {
									$urlImagen = VISUALIZADOR_IMAGENES . '?tipo=guias&id=' . $rw2[0];
								}
							}

				echo "<td align='center' >";
				echo "<a href='https://sistema.transmillas.com/nueva_plataforma/controller/VerguiaController.php?guia=".$rw1[3]."R' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a></td>";	

				if($nivel_acceso==1 or $nivel_acceso==9 or $nivel_acceso==10){
				
				echo "<td><div id='campo$va'>"; if($rw2[6]=='SI') { $st="SI"; $colorfondo="#941727"; } else { $st="NO"; $colorfondo="#074f91"; } 
				echo "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'
				name='param14' id='param14'  onChange='cambio_ajax2(this.value, 81, \"campo$va\", \"$va\", 1, $id_p)'  required>";
					$LT->llenaselect_ar($st,$estado_rec);
				echo "</select></div></td>";
				
				}
				echo "<td align='center' ><a href='del_admin.php?id_param=$rw2[0]&tabla=Elimina Archivo2&ruta=$rw2[2]' title='Eliminar' 
				onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a></td>"; 	
				echo "</tr>"; 	
			}else{











						if(is_null($rw2[0])){

							$va++;
							$p=$va%2;
						if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
						//	if(is_null($rw2[0])){
				
								echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
								$tipo=$rw1[5];
								if($tipo=="Recogida"){
									$fecha=$rw1[1];
								}else{
									$fecha=$rw1[2];
								}
								echo "<td>".$va."</td>
								<td>".$fecha."</td>
								<td>".$rw1[3]."</td>
								<td>".$tipo."</td>
								
								";
								$guiatipo=$rw1[3]."_$tipo";
								echo "<td align='center' >";
								echo "<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"$guiatipo\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia</a><br><button onclick='abrirPopup(\"".$rw1[3]."\",\"$tipo\",$id_p)'>Ratificar</button></td>";
								if($nivel_acceso==1 or $nivel_acceso==9){
									echo "<td>NO</td>";
								}
								echo "<td align='center' >SIn Imagen</td>";
								
								echo "</tr>"; 
						//	}
				
						}else if(!is_null($rw2[0])){
				
						$va++;
						$p=$va%2;
						if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
							echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
							$tipo=$rw1[5];
							if($tipo=="Recogida"){
								$fecha=$rw1[1];
							}else{
								$fecha=$rw1[2];
							}
							echo "<td>".$va."</td>
							<td>".$fecha."</td>
							<td>".$rw1[3]."</td>
							<td>".$tipo."</td>
							";
				
										// Verificar fecha para imagen
										{
											$fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
											$fecha_image_entrada = strtotime($rw2[4]);
					
											if($fecha_image_entrada >= $fecha_inicio_mes) {
												$urlImagen = $rw2[2];
											} else {
												$urlImagen = VISUALIZADOR_IMAGENES . '?tipo=guias&id=' . $rw2[0];
											}
										}
				
							echo "<td align='center' >";
							echo "<a href='https://sistema.transmillas.com/nueva_plataforma/controller/VerguiaController.php?guia=".$rw1[3]."E' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia</a><br><a href='https://sistema.transmillas.com/editarImg.php?img=".$urlImagen."' target='_blank'>✏️</a></td>";	
				
							if($nivel_acceso==1 or $nivel_acceso==9 or $nivel_acceso==10){
							
							echo "<td><div id='campo$va'>"; if($rw2[6]=='SI') { $st="SI"; $colorfondo="#941727"; } else { $st="NO"; $colorfondo="#074f91"; } 
							echo "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'
							name='param14' id='param14'  onChange='cambio_ajax2(this.value, 81, \"campo$va\", \"$va\", 1, $id_p)'  required>";
								$LT->llenaselect_ar($st,$estado_rec);
							echo "</select></div></td>";
							
							}
							echo "<td align='center' ><a href='del_admin.php?id_param=$rw2[0]&tabla=Elimina Archivo2&ruta=$rw2[2]' title='Eliminar' 
							onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'>Eliminar <i class='fa fa-trash-o'></i></a></td>"; 	
							echo "</tr>"; 	
						}

			}
	 	}
	}


include("footer.php");
?>
<script>
	function abrirPopup(idguia,imprimir,id_param) {
		event.preventDefault();

		window.open("ratificafirmadigital.php?idguia="+idguia+"&imprimir="+imprimir+"&id_param="+id_param+"", "popup", "width=600,height=400");
	}
</script>