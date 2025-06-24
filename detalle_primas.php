
<style>
    table {
        position: relative;
    }

    thead tr {
        position: sticky;
        top: 0;
        background-color: #ffffff;
    }
</style>

<?php 
require("login_autentica.php");
include("cabezote3.php"); 

$asc="ASC";
$conde=" ";
$conde2=" ";
$conde3=" ";
$conde4=" ";
$conde5=" ";


if($param34!=''){ $fechaactual=$param34; }

if($param35!=''){ $id_sedes=$param35; 

	$conde4=" and hoj_sede=$id_sedes "; 	
}
if($param33!=''){ 
	        $cedula="SELECT `usu_identificacion` FROM `usuarios` WHERE `idusuarios`='$param33' ";
			$DB1->Execute($cedula); 
			$CedulaUser=$DB1->recogedato(0);
	
	$conde="and `hoj_cedula`= '$CedulaUser' ";  }
if($param32!='' and $param32!=0){ $conde1="and `seg_motivo`= '$param33' ";  }
	



$conde3=""; 
$ano=date('Y');
if($param34!=''){ $fechaactual=$param34." 00:00:00";  }
if($param36!=''){ $fechafinal=$param36." 23:59:59";  }


if($param36=='Primera'){
	$fechaactual=date($ano.'-01-01'.' 00:00:00');
	$fechafinal=date($ano.'-06-30'.' 23:59:59');
	$diasDeLaQuincena=15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-01');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-15');

}elseif($param36=='Segunda'){
	$fecha_aux = date('Y-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));
	$fechaactual=date($ano.'-07-01'.' 00:00:00');
	$fechafinal=date($ano.'-12-31 23:59:59');
	$diasDeLaQuincena=$fin-15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);
}elseif($param36=='Completo'){
	$fecha_aux = date('Y-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));

	$fechaactual=date($ano.'-'.$param34.'-01'.' 00:00:00');
	$fechafinal=date($ano.'-'.$param34.'-'.$fin.' 23:59:59');
	$diasDeLaQuincena=$fin-15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);



}

//cuantos dias tiene la quincena
echo'<input type="hidden" value="'.$fechaactual.'" id="fechaactual">';
echo'<input type="hidden" value="'.$fechafinal.'" id="fechafin">';




$fechas=$fechaactual."/".$fechafinal;





$FB->titulo_azul1("",1,0,7); 
$FB->titulo_azul1("Trabajador",1,0,0); 
$FB->titulo_azul1("Tipo Contrato",1,0,0); 
$FB->titulo_azul1("Cedula",1,0,0); 
$FB->titulo_azul1("Cargo",1,0,0); 
$FB->titulo_azul1("Salario por mes",1,0,0); 
$FB->titulo_azul1("Auxilio",1,0,0); 
$FB->titulo_azul1("Dias ",1,0,0); 
$FB->titulo_azul1("Descansó",1,0,0);
$FB->titulo_azul1("Dias No Trabajados",1,0,0); 
$FB->titulo_azul1("Dias de Incapacidad Empresa",1,'5%',0); 
$FB->titulo_azul1("Dias de vacaciones",1,'5%',0); 
$FB->titulo_azul1("Licencias y permisos",1,'5%',0); 
$FB->titulo_azul1("Total dias Prima",1,'5%',0);
$FB->titulo_azul1("Total prima",1,'5%',0);  
$FB->titulo_azul1("Comprobante",1,'5%',0); 
$FB->titulo_azul1("Desprendible de Prima",1,'5%',0); 
$FB->titulo_azul1("Confirmado",1,'5%',0);
$FB->titulo_azul1("Pagado",1,'5%',0);  
$FB->titulo_azul1("Inicio contrato",1,'5%',0); 
$FB->titulo_azul1("Termina contrato",1,'5%',0); 



	if($param34 == 2 and $param36=='Segunda'){
		if($fin==29){
			$diasParaSumar=1;

		}else{
			$diasParaSumar=2;
		}


	}else{

		$diasParaSumar=0;
	}


	$valorTotalDePrimas=0;
	$tablaPago="";
$sql="SELECT `idhojadevida`,  `hoj_nombre`, `hoj_apellido`,hoj_cargo,
 `hoj_tipocontrato`,`hoj_cedula`,`hoj_fechaingreso`, `sed_nombre`,
 `hoj_fechanacimiento`, `hoj_cedula`,`hoj_direccion`, `hoj_celular`,
 `hoj_estado`,hoj_sede,hoj_fechatermino,hoj_cuen,hoj_tcuenta,hoj_firma,
  hoj_estado,hoj_banco,hoj_fech_año_act FROM hojadevida
INNER JOIN sedes ON hoj_sede = idsedes
WHERE (idhojadevida > 0 AND hoj_estado = 'Activo' ) and hoj_tipocontrato='Empresa' $conde4 $conde
ORDER BY hoj_nombre ASC";
  $DB->Execute($sql); 
  $va=0; 
	  while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	  {

		//   $user="SELECT `idusuarios` FROM `usuarios` WHERE `usu_identificacion`='$rw1[5]' and usu_ver_nomina='1'";
		//   $DB1->Execute($user); 
		//   $idusuario=$DB1->recogedato(0);

		$idusuario = obtenerUsuarioConNomina($rw1[5]);

		if ($rw1[20]!="000-00-00"){
			$fechaIniciContrato=$rw1[20];
		}else {
			$fechaIniciContrato=$rw1[6];
		}
		
       	$fechaFinContrato=$rw1[14];


		$colorFila="";			
		$totaldevengado=0;
		$totaldeduccion=0;
		$fechafin=$fechafinal;
		$mostrar=true;
		if($fechaIniciContrato>=$fechaactual and $fechaIniciContrato<=$fechafinal){  
			$mesdeingreso=true;
			$fechaAhora=$fechaIniciContrato;		
		}else{
			$mesdeingreso=false;
			$fechaAhora=$fechaactual;
		}

		if ($fechaFinContrato==null){
			$mesdeFinal=false;
		}elseif($fechaFinContrato>=$fechaactual and $fechaFinContrato<=$fechafinal){          
			$mesdeFinal=true;
		}elseif($fechaFinContrato<$fechaactual){
			$mostrar=false;
			$mesdeFinal=false;
		}

		// Comprobar si tiene hora (espacio + al menos una hora tipo 00:00:00)
		if (strpos($fechaFinContrato, ' ') !== false and $fechaFinContrato!=null) {
			// Extraer solo la parte de la fecha
			$fechaFinContrato = explode(' ', $fechaFinContrato)[0];
		}
		
		$fechaInicia=$fechaIniciContrato;
		if ($mesdeingreso==true and $mesdeFinal==true) {
			$fechaInicia=$fechaIniciContrato;
			$fechaFinaliza=$fechaFinContrato;
			$colorFila="#E6B7BE";
		}elseif ($mesdeingreso==true) {
			$fechaInicia=$fechaIniciContrato;
			$fechaFinaliza=$fechafinal;
		}elseif ($mesdeFinal==true) {
			$fechaInicia=$fechaactual;
			$fechaFinaliza=$fechaFinContrato;
			$colorFila="#E6B7BE";
		}else {
			$fechaInicia=$fechaactual;
			$fechaFinaliza=$mesdeFinal;

		}

		if($idusuario>=1 and $mostrar==true){

			echo "<tr class='text' bgcolor='$colorFila' onmouseover='this.style.backgroundColor=\"$colorFila\"' onmouseout='this.style.backgroundColor=\"$colorFila\"'>";		
			echo "<td><input type='checkbox'  onchange='selecionado($idusuario)' class='checkbox' id='".$idusuario."s' value='$idusuario'></td>";
			echo "<td>".$rw1[1]."".$rw1[2]."</td>";
			echo "<td>".$rw1[4]."</td>";
			echo "<td>".$rw1[5]."</td>";

			$cargosaldo = obtenerDatosCargo($rw1[3]);
			// Ejemplo de acceso a los datos:
			$nombreCargo = $cargosaldo[1];
			$salario     = $cargosaldo[2];
			$auxilio     = $cargosaldo[3];
			echo "<td>".$nombreCargo."</td>";
			echo "<td>".$salario     ."</td>";//Salario Mes
			echo "<td>".$auxilio    ."</td>";//Auxilio
			echo "<td>".$fechaInicia    ."</td>";//Fecha inicio contrato
			echo "<td>".$fechaFinaliza    ."</td>";//Fecha final de contrato


		}







		if ($fechaFinContrato==null) {

			$mesiniciocontrato=date("m", strtotime($rw1[6]));
			$añoiniciocontrato=date("Y", strtotime($rw1[6]));

			// echo"MES ".$sigmesiniciocontrato=date("m", strtotime($mesiniciocontrato. " +1 month"));

			$priemrdiadestemes=date("Y-m-d H:i:s", strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-01'.' 00:00:00'."+1 month"));

			if($param36=='Completo'){
				$diaveinte=date("Y-m-d H:i:s",strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-30'.' 23:59:59'."+31 days"));
			
			}else{
			
				$diaveinte=date("Y-m-d H:i:s",strtotime($añoiniciocontrato.'-'.$mesiniciocontrato.'-15'.' 23:59:59'."+1 month"));
			}


			// if (($fechaactual>=$priemrdiadestemes and $fechafinal<=$diaveinte)) {
			// 	$color="#00bf19";
			// }else{

			// 	if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
			// }

			
			$terminaContrato="";
			$InicioContrato=$rw1[6];
			if($InicioContrato<=$fechafinal and $rw1[18] == 'Activo') {
				$activoEnNomina=true;
			}else{
				$activoEnNomina=false;
				
			}
			
			
		}else{
			
			
			$terminaContrato=$fechaFinContrato;
			$mesterminocontrato=date("m", strtotime($fechaFinContrato));
			$añoterminocontrato=date("Y", strtotime($fechaFinContrato));
			$diaterminocontrato=date("d", strtotime($fechaFinContrato));

			
			$priemrDiaQuinceTermina=date("Y-m-d H:i:s", strtotime($añoterminocontrato.'-'.$mesterminocontrato.'-01'.' 00:00:00'));
			// if ($fechaactual>= $priemrDiaQuinceTermina ) {
				
			// 	$color="#D35400";

			// }else{
				
			// 	if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}

			// }


			$nueva_fechaTerminaContrato = date("Y-m-d", strtotime($terminaContrato . "+30 days"));

			if ( $fechafinal<= $nueva_fechaTerminaContrato ) {
				$activoEnNomina=true;
				

			}else{
				$activoEnNomina=false;
				

			}

			
		}

if ($activoEnNomina) {
	
	$id_p=$rw1[0];

		  

		if(empty($idusuario)){
			

		}else{

$tablaPago.="<tr>";
$tablaPago.="<td>1</td>";

$tablaPago.="<td>$rw1[1]</td>";
$tablaPago.="<td>$rw1[2]</td>";
$tablaPago.="<td>$rw1[5]</td>";
if ($rw1[16]=="DAVIPLATA") {
	$tipoCuenta="DP";
}else if ($rw1[16]=="AHORROS") {
	$tipoCuenta="CA";
}
if ($rw1[19]=="DAVIVIENDA" or $rw1[19]=="DAVIPLATA") {
	$codigoBanco="51";
}

$tablaPago.="<td>$tipoCuenta</td>";
$tablaPago.="<td>$codigoBanco</td>";
$tablaPago.="<td>$rw1[15]</td>";

			// echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		 
			// echo "<td><input type='checkbox'  onchange='selecionado($idusuario)' class='checkbox' id='".$idusuario."s' value='$idusuario'></td>";
			// echo "
			// <td>".$rw1[1]." ".$rw1[2]."</td>";
			// echo "<td>".$rw1[4]."</td>";
			// echo "<td>".$rw1[5]."</td>";


			$va++; $p=$va%2;
		  
			$valordediastrabajados=0;
			$sql2="SELECT  `idcargo`, `car_Cargo`, `car_Salario`, `car_Auxilio`, `car_otros`,car_Recogida,car_ValorRecogida	 FROM `cargo` WHERE idcargo='$rw1[3]'";		
			$DB1->Execute($sql2);
			$cargosaldo=mysqli_fetch_row($DB1->Consulta_ID);
		  	if($idusuario>=1){
			// echo$slq3="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`>='$fechaactual' and `deu_fecha`<='$fechafinal' and deu_idpromotor='$idusuario' and deu_tipo in ('Prestamos','Descuadre')";
			$prestamo=0;
			$descuadre=0;
			$pago=0;
			$malenviados=0;
//Prestamos 
			$slq3="SELECT deu_tipo, deu_valor FROM `duedapromotor` WHERE  deu_idpromotor='$idusuario'  ";
			$DB1->Execute($slq3); 
			// $prestamostotal=mysqli_fetch_row($DB1->Consulta_ID);
			while($prestamostotal=mysqli_fetch_row($DB1->Consulta_ID))
			{
				if ($prestamostotal[0]=="Prestamos") {
					$prestamo =$prestamo+$prestamostotal[1];
				}elseif ($prestamostotal[0]=="Descuadre") {
					$descuadre =$descuadre+$prestamostotal[1];
				}elseif ($prestamostotal[0]=="Pagos") {
					$pago =$pago+$prestamostotal[1];
				}elseif ($prestamostotal[0]=="MalEnviados") {
					$malenviados =$malenviados+$prestamostotal[1];
				}

			}
 
			
			// echo"Pagado".$pago;
			$prestamoTotal= $prestamo+$descuadre+$malenviados;
        	$TotalDebe = $prestamoTotal-$pago;

//Pagos a la 15na, de prestamos 
			$pago1=0;
			$restaAOtros=0;
			$restaABasico=0;
			$descripcionBasico="";
			$descripcionOtros="";
			$slq7="SELECT deu_tipo, deu_valor,deu_pago_de,due_descripcion  FROM `duedapromotor` WHERE  deu_idpromotor='$idusuario' and deu_fecha>='$fechaAhora' and deu_fecha<='$fechafin' and deu_pago_de in ('Basico','otros') ";
			$DB1->Execute($slq7); 
			// $prestamostotal=mysqli_fetch_row($DB1->Consulta_ID);
			while($prestamostotal7=mysqli_fetch_row($DB1->Consulta_ID))
			{
				if ($prestamostotal7[0]=="Pagos") {
					$pago1 =$pago1+$prestamostotal7[1];

					if ($prestamostotal7[2]=="Basico") {
						$restaABasico=$restaABasico+$prestamostotal7[1];
						$descripcionBasico=$descripcionBasico.", $prestamostotal7[3]";
					}elseif($prestamostotal7[2]=="Otros"){

						$restaAOtros=$restaAOtros+$prestamostotal7[1];
						$descripcionOtros=$descripcionOtros.", $prestamostotal7[3]";
					}
				}

			}



			$TotalPagoQuincena = $pago1;
			$TotalPagoQuincena_formateado = number_format($TotalPagoQuincena, 0, ',', '.');			

//Dias no trabajados
			$diasnotrabajo=0;
			$notrabajo="SELECT seg_fechaingreso FROM `seguimiento_user`  where seg_motivo in ('Se devolvio','Sancionado','No trabajo') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'   and seg_idusuario='$idusuario' ";
			$DB1->Execute($notrabajo);
			// $rw2=mysqli_fetch_row($DB1->Consulta_ID);
			// $diasnotrabajo=$rw2[0];

			while($rw2=mysqli_fetch_row($DB1->Consulta_ID))
			{

				$diasnotrabajo=$diasnotrabajo+1;
				// $fechasNoTrabajo_array[] = $rw2[0];

			}
			// if($diasnotrabajo>2){

			// 	$diferencia = strtotime($fecha2) - strtotime($fecha1); 

			// }
			
			



//Dias de Descanso
			$descansopago="SELECT count(*) FROM `seguimiento_user`  where seg_motivo in('descanso','IngresoHoras') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($descansopago); 
			$rw6=mysqli_fetch_row($DB1->Consulta_ID);
			if(empty($rw6)){

				$diasDescanso=0;
			}else{

				$diasDescanso=$rw6[0];
			}
			
			$valorDeDiasDeDescanso=$diasvalor*$diasDescanso;
			$valorDeDiasDeDescanso_formateado = number_format($valorDeDiasDeDescanso, 0, ',', '.');

			
//Dias trabajados		
			$diassitrabajo=0;
			echo$sitrabajo="SELECT count(*) FROM `seguimiento_user`  where seg_motivo in('Ingreso','descanso no remunerado','Reposicion por falla') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($sitrabajo); 
			$rw4=mysqli_fetch_row($DB1->Consulta_ID);
			if(empty($rw4)){

				$diassitrabajo=0;
			}else{
				$diassitrabajoPrima=$rw4[0];
				if($fin==31 and $param36=='Segunda' or $fin==31 and $param36=='Completo' ){
					

						if ($rw4[0]<=0 or $mesdeingreso==true) {
							$dia31=0;
							
							# code...
						}else{
							if ($param36=='Segunda') {

							if (($rw4[0]+$diasDescanso)==16 ) {
								$dia31=1;
								// echo"es 16 dias";
								# code...
							}else{
	
								// echo"segunda quince con 16 dias";
								$dia31=0;
							}
							// echo"segunda quince con 16 dias";
							// $dia31=1;
							}else if($param36=='Completo') {

								if (($rw4[0]+$diasDescanso)==31) {
									$dia31=1;
									// echo"es 16 dias";
									# code...
								}else{

									// echo"segunda quince con 16 dias";
									$dia31=0;
								}

							}
						}
						if (($rw4[0]+$diasDescanso)==0) {
							$diasnotrabajo=0;
						}
						$diassitrabajo=$rw4[0]+$diasDescanso-($dia31+$diasnotrabajo);
						$diassitrabajoParaSumar=$rw4[0]+$diasDescanso-($dia31+$diasnotrabajo);
						$diassitrabajoConAuxilio=$rw4[0];
						$diassitrabajoParaMostrar=$rw4[0]+$diasDescanso-($dia31+$diasnotrabajo);

					// }

				}elseif($fin==29 and $param36=='Segunda' or $fin==29 and $param36=='Completo' ){
					

					if ($rw4[0]<=0 or $mesdeingreso==true){
						$dia29=0;
						
						# code...
					}else{

						
						$dia29=1;
					}
					
					$diassitrabajo=$rw4[0]+$diasDescanso+$dia29;
					$diassitrabajoParaSumar=$rw4[0]+$diasDescanso+$dia29;
					$diassitrabajoConAuxilio=$rw4[0]+$dia29;
					$diassitrabajoParaMostrar=$rw4[0]+$diasDescanso;
				// }

			}else{
				
					$diassitrabajo=$rw4[0]+$diasDescanso;
					$diassitrabajoParaSumar=$rw4[0]+$diasDescanso;
					$diassitrabajoConAuxilio=$rw4[0];
					$diassitrabajoParaMostrar=$rw4[0]+$diasDescanso;
				}
		

				
			}
			
			
			
			$diasvalor=($cargosaldo[2]/30);
			$valordediastrabajados=$diasvalor*$diassitrabajoParaSumar;
			$valordediastrabajados_formateado = number_format($valordediastrabajados, 0, ',', '.');
			
//Permisos y licencias
			$permisosLic=0;
			$nombreMotivo="";
			$valorPermisosLicBasico=0;
			$diasPerLicBas=0;
			$diasvalorporcentaje=0;
			$diasPerLicBasValor=0;
			$valorMitaddiasPerLi=0;
			$diasPerLicBasValortotal=0;
			$diasPerLicBasValortotalfinal=0;
			$valorPermisosLicSalud=0;
			$valorPermisosLicPension=0;
			$permisoLicencia="SELECT `seg_motivo`, `seg_descr`, `mot_salud`, `mot_pension`, `mot_auxtransporte`, `mot_porcbasico`, `mot_otrosDevengos`  FROM `seguimiento_user` INNER JOIN motivo_ingreso on mot_nombre=seg_motivo  where seg_motivo in('licencia de maternidad','LICENCIA POR LUTO','PERMISO NO REMUNERADO','PAGO DE INCAPACIDAD AL 66') and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($permisoLicencia); 
			;
			while($rw9=mysqli_fetch_row($DB1->Consulta_ID))
			{
				if(empty($rw9)){

					$permisosLic=0;
				}else{
				
					$permisosLic=$permisosLic+1;
					$nombreMotivo=$rw9[0];

					if ($rw9[2]=="si" or $rw9[2]=="SI" ) {
						$valorPermisosLicSalud=$valorPermisosLicSalud+1;
					}
					if ($rw9[3]=="si" or $rw9[3]=="SI") {
						$valorPermisosLicPension=$valorPermisosLicPension+1;
					}
					if($rw9[4]=="si" or $rw9[4]=="SI"){
						$valorPermisosLicAux=$valorPermisosLicAux+1;

					}
					if($rw9[6]=="si" or $rw9[6]=="SI"){
						$valorPermisosLicOtros=$valorPermisosLicOtros+1;
					}
					
					if($rw9[5]!="0"){
						$diasPerLicBas=$diasPerLicBas+1;
						
						


							$rw9[5];
							$valorporcentaje=($rw9[5]/100)*$diasvalor;	
							$diasPerLicBasValortotalfinal=$diasPerLicBasValortotalfinal+$valorporcentaje;

						
					}
					
					
				}


			}



			$diasPerLicBasValortotalfinal_formateado = number_format($diasPerLicBasValortotalfinal, 0, ',', '.');			

//Incapacidades		

			$incapacidad="SELECT count(*) FROM `seguimiento_user`  where seg_motivo ='Incapacidad' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($incapacidad); 
			$rw5=mysqli_fetch_row($DB1->Consulta_ID);
			if(empty($rw5)){

				    $diasincapacidad=0;

			}else{
				$diasincapacidad=$rw5[0];

			}

			if($diasincapacidad>=2){
				$valorDiasIncapadidad=($diasvalor)*(2*0.6667);

				$valorDiasIncapadidad_formateado = number_format($valorDiasIncapadidad, 0, ',', '.');

			}else{

				$valorDiasIncapadidad=($diasvalor)*($diasincapacidad*0.6667);

				$valorDiasIncapadidad_formateado = number_format($valorDiasIncapadidad, 0, ',', '.');
			}




//VACACIONES

			$Vacaciones="SELECT count(*) FROM `seguimiento_user`  where seg_motivo ='Vacaciones' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($Vacaciones); 
			$rw8=mysqli_fetch_row($DB1->Consulta_ID);
			if(empty($rw8)){

				$diasVacaciones=0;
			}else{
				$diasVacaciones=$rw8[0];
			}



			$valorDiasVacaciones=($diasvalor)*($diasVacaciones);

			$valorDiasVacaciones_formateado = number_format($valorDiasVacaciones, 0, ',', '.');


			$horasdominicales="SELECT SUM(seg_horas_trabajadas) FROM `seguimiento_user`  WHERE  seg_motivo ='IngresoHoras' and seg_fechaingreso>='$fechaAhora' and seg_fechaingreso<='$fechafin'  and seg_idusuario='$idusuario' "; 
			$DB1->Execute($horasdominicales); 
			$rw6=mysqli_fetch_row($DB1->Consulta_ID);



			}else{
				$diasnotrabajo=0;
				$prestamostotal=0;
				$diasvalor=0;
			}


			
	
//SALUD Y PENSION

			$Salud=26000;
			$Pension=26000;

			$saludPorDia=26000/15;
			$pensionPorDia=26000/15;

			if ($terminaContrato=="" and $mesdeingreso==false) {
				if($param36=='Completo'){

					$valorSalud=$saludPorDia*30;	
					$valorPension=$pensionPorDia*30;
				}elseif($param36=='Primera' or $param36=='Segunda'){
					$valorSalud=$saludPorDia*15;	
					$valorPension=$pensionPorDia*15;
				}

			}else{
				if ($diasVacaciones>0) {
					$valorSalud=$saludPorDia*($diassitrabajoParaSumar+$diasVacaciones+$valorPermisosLicSalud+$diasincapacidad);	
					$valorPension=$pensionPorDia*($diassitrabajoParaSumar+$diasVacaciones+$valorPermisosLicPension+$diasincapacidad);
				}else {
					$valorSalud=$saludPorDia*($diassitrabajoParaSumar+$valorPermisosLicSalud+$diasincapacidad);	
					$valorPension=$pensionPorDia*($diassitrabajoParaSumar+$valorPermisosLicPension+$diasincapacidad);
				}

				
			}


			$valorSalud_formateado = number_format($valorSalud, 0, ',', '.');


			$valorPension_formateado = number_format($valorPension, 0, ',', '.');
//Pago por dias

			//auxilio
			$auxilioPorDia=$cargosaldo[3]/30;
			//Total auxilio 15na
			$totalauxilio=$auxilioPorDia*($diassitrabajoParaSumar);


//Total horas domfest
			if (strpos($rw6[0], ".") !== false) {
				
				$partes = explode(".", $rw6[0]);
				$numeroAntesDelPunto = $partes[0];

				$valorHorasDomini=$numeroAntesDelPunto*9681;
				$valorMitadDomini=9681/2;

				$valorTotalHorasDomini=$valorHorasDomini+$valorMitadDomini;
			} else {
				
				$valorHorasDomini=$rw6[0]*9681;
				$valorTotalHorasDomini=$valorHorasDomini;
				
			}	

			
	
			$sedess="SELECT `usu_idsede` FROM `usuarios` WHERE `idusuarios`='$idusuario' ";
			$DB1->Execute($sedess); 
			$id_sedes=$DB1->recogedato(0);

			$idcidades=ciudadesedes($id_sedes,$DB1);
			
			$cantRecogidas=0;
			$valorRecogidas=0;
			$entregas="SELECT count(*)FROM servicios inner join cuentaspromotor on cue_idservicio=idservicios inner join ciudades on ser_ciudadentrega=idciudades  where date(cue_fecharecogida)>='$fechaactual' and  date(cue_fecharecogida)<='$fechafinal' and (`cue_idoperador`= '$idusuario' or  `cue_idoperentrega`= '$idusuario' )and (cue_idciudadori in $idcidades )  ORDER BY ser_guiare  $asc ";
			$DB1->Execute($entregas); 
			$rw10=mysqli_fetch_row($DB1->Consulta_ID);
			if ($cargosaldo[5]=="SI" or $cargosaldo[5]=="Si" or $cargosaldo[5]=="si") {
				$valorRecogidas=$rw10[0]*$cargosaldo[6];
				$cantRecogidas=$rw10[0];
			  }else {
				$valorRecogidas=0;
				$cantRecogidas=0;
			  }
			  $valorRecogidas=$rw10[0]*$cargosaldo[6];
			  $valorRecogidas_formateado = number_format($valorRecogidas, 0, ',', '.');	
	//otros
			$otrosPorDia=$cargosaldo[4]/30;
			//Total auxilio 15na
			$totalOtrosDias=($otrosPorDia*($diassitrabajoParaSumar));

			$totalOtrosDias_formateado = number_format($totalOtrosDias, 0, ',', '.');



			$sueldo_formateado = number_format($cargosaldo[2], 0, ',', '.');



//DIAS DE PRIMA
$primapordia=($cargosaldo[2]+$cargosaldo[3])/360;


$diasDiferencia=0;
$añocontrato="";
$mescontrato="";
$dia="";

$añocontrato=date("Y", strtotime($rw1[6]));
$mescontrato=date("m", strtotime($rw1[6]));
$dia=date("d", strtotime($rw1[6]));



$añocontrato;
// Definir las dos fechas
$fecha1 = "";
$fecha2 = "";

if ($param36=='Primera') {

	if ($añocontrato<date('Y')) {
		$añocontrato=date('Y');
		$mescontrato=date("01");
		$dia=date("01");
		
	}
	$fecha1 = new DateTime("".$añocontrato."-".$mescontrato."-".$dia." 00:00:00");
	$fecha1->format('Y-m-d H:i:s'); // Salida: 2024-11-08 00:00:00
	$fecha2 = new DateTime("".date('Y')."-07-01 00:00:00");


	// Calcular la diferencia
	$diferencia = $fecha1->diff($fecha2);

	$diasDiferencia = $diferencia->days;

	// Mostrar la diferencia en días

	$añocontrato1="";
	$mescontrato1="";
	$dia1="";
	$añocontrato1=date("Y", strtotime($rw1[6]));
	$mescontrato1=date("m", strtotime($rw1[6]));
	$dia1=date("d", strtotime($rw1[6]));

	$calculo=="";
	if (date("".$añocontrato1."-".$mescontrato1."-".$dia1." 00:00:00")<= date("".date('Y')."-01-31 00:00:00") )  {

		$aqui="Menor o igual a enero";

		$diasdeprima=$diasDiferencia-($diasnotrabajo+2);
		// $diasdeprima=$diasDiferencia;
		

	}else if(date("".$añocontrato1."-".$mescontrato1."-".$dia1." 00:00:00")> date("".date('Y')."-01-31 00:00:00")){

		$diasdeprima=$diasDiferencia-($diasnotrabajo+1);
		$aqui="Mayor o igual a febrero";
		if ($idusuario==1819) {
			$diasdeprima=$diasDiferencia-($diasnotrabajo+2);
		}

	}
}
elseif($param36=='Segunda'){
	$resta31s=4;
	if (($añocontrato==date('Y') and $mescontrato<"07") or $añocontrato<date('Y')) {
		$añocontrato=date('Y');
		$mescontrato=date("07");
		$dia=date("01");
		$resta31s=4;
		$fecha1 = new DateTime("".$añocontrato."-".$mescontrato."-".$dia." 00:00:00");
		$fecha1->format('Y-m-d H:i:s');
	   $fecha2 = new DateTime("".date('Y')."-12-31 23:59:59");
   
		
	}else {

		if ($mescontrato==date("07")) {
			
			$resta31s=4;
		}elseif ($mescontrato==date("08")) {
			$resta31s=3;
		}elseif ($mescontrato==date("09")) {
			$resta31s=2;
		}elseif ($mescontrato==date("10")) {
			$resta31s=2;
		}elseif ($mescontrato==date("11")) {
			$resta31s=1;
		}elseif ($mescontrato==date("12")) {
			$resta31s=1;
		}

		$fecha1 = new DateTime("".$añocontrato."-".$mescontrato."-".$dia." 00:00:00");
		$fecha1->format('Y-m-d H:i:s');
	   $fecha2 = new DateTime("".date('Y')."-12-31 23:59:59");
   
	}
	// Calcular la diferencia
	$diferencia = $fecha1->diff($fecha2);

	echo"Diferencia".$diasDiferencia = $diferencia->days;

	// Mostrar la diferencia en días

	$añocontrato1="";
	$mescontrato1="";
	$dia1="";
	$añocontrato1=date("Y", strtotime($rw1[6]));
	$mescontrato1=date("m", strtotime($rw1[6]));
	$dia1=date("d", strtotime($rw1[6]));

	$calculo="";
	// if (date("".$añocontrato1."-".$mescontrato1."-".$dia1." 00:00:00")<= date("".date('Y')."-01-31 00:00:00") )  {

		$aqui="Menor o igual a enero";

		echo"$diasdeprima=($diasDiferencia+1)-($diasnotrabajo+$resta31s);";

		$diasdeprima=($diasDiferencia+1)-($diasnotrabajo+$resta31s);



}




$valordiasprima=$diasdeprima*$primapordia;
$valordiasprima_formateado = number_format($valordiasprima, 0, ',', '.');

$valorTotalDePrimas=$valordiasprima+$valorTotalDePrimas;


			// echo "<td>".$cargosaldo[1]." </td>";//Nombre del cargo
			
			
			// echo "<td>$".$sueldo_formateado."</td>";//Salario basico por mes
			// echo "<td>$".$cargosaldo[3]."</td>";//Auxilio por mes
			
			
			// $diasvalor_formateado = number_format($diasvalor, 0, ',', '.');
			// echo "<td colspan='1' width='0' align='center' ><a id='link'  onclick='pop_dis16($idusuario,\"Resumen_Quincena\",\"$fechas\")';  title='Ingreso de Usuario' >$diassitrabajoPrima Dias </td>"; //Ingreso?
			// echo "<td>".$diasDescanso."</td>";// dias de descanso
			// echo "<td>".$diasnotrabajo."</td>";
			// echo "<td>".$diasincapacidad."</td>";
			// echo "<td>$diasVacaciones</td>"; //VACACIONES
			// echo "<td>".$nombreMotivo."<br>".$permisosLic." Dias</td>"; 



		
		

		
	

		      $colorselect="#8B0000";
			  $si="";
			  $no="";
			  $imagencompr="";
			  $linkbasico="";
			  $textEnviar="Enviar";
			  $colorEnviar="rgb(7, 79, 145)";
			  $validado="";
			  $Observacion="";
			  $cheked1="";
			  $botonEnviar1="none";
			  $confirmado1="";
			  $validadoDesprendible="";

			  $tablaNomina="SELECT`pri_confirma`,`idprimas`, pri_docprima,pri_confirmaUsus, `pri_fechaconfirmausu`,pri_confiAdmin, `pri_fechaadminconfi`, `pri_idadminconfi`,`pri_fecha_inicio`, `pri_fecha_fin`, `pri_idusu`, `pri_semestre`,pri_img_compro FROM `primas` WHERE pri_idusu='$idusuario' and pri_fecha_inicio='$fechaactual' ";
			  $DB1->Execute($tablaNomina); 

			  while($Nomina=mysqli_fetch_row($DB1->Consulta_ID))
			  {


					 $imagencompr=$Nomina[12];
			
				
				if ($Nomina[0]=="Si") {
					$colorselect="#28B463";
					$si="selected";
					$no="";
					$linkbasico="auto";
				}else if($Nomina[0]=="No"){
					$si="";
					$no="selected";
					$colorselect="#8B0000";
					$linkbasico="none";
				}else{
					$si="";
					$no="selected";
					$colorselect="#8B0000";
					$linkbasico="none";
				}

				if($Nomina[2]==""){

					$textEnviar="Enviar";
					$colorEnviar="rgb(7, 79, 145)";
				}else{

					$colorEnviar="#28B463";
					$textEnviar="Reenviar";
				}

				if ($Nomina[3]=="Si") {

					$validadoDesprendible="Validado el $Nomina[5]  Por ".$rw1[1]." ".$rw1[2]." ";
					$validado="Validado✅ <br> $Nomina[5]";
				}elseif($Nomina[3]=="no"){
					$validado="Rechazada❌ <br> $Nomina[5]";
				}else{
					$validado="Pendiente";

				}

				if ($Nomina[5]=="si") {
					$cheked1="checked";
					$botonEnviar1="inline-block";
					$user1="SELECT `usu_nombre` FROM `usuarios` WHERE `idusuarios`='$Nomina[7]' ";
					$DB1->Execute($user1); 
					$nombre1=$DB1->recogedato(0);
					$confirmado1="Por $nombre1 <br> en la fecha: $Nomina[6]";
				}else {
					$cheked1="";
					$botonEnviar1="none";
					$confirmado1="";
				}



				
			  }
			  

			$TotalDevengado= ($totalauxilio + $valordediastrabajados+$valorDiasIncapadidad+$valorDiasVacaciones+$diasPerLicBasValortotalfinal+$ajustessumB)-($valorSalud+$valorPension+$restaABasico+$ajustesresB);
			$TotalDevengado_formateado = number_format($TotalDevengado, 0, ',', '.');
			// echo "<td style='background-color:#F4D03F'>".$diasdeprima."  ".$calculo." </td>";//Valor quincena 

			// echo "<td style='background-color:#F4D03F'>$valordiasprima_formateado</td>";//Valor quincena 
			$tablaPago.="<td>".$valordiasprima_formateado."</td></tr>";

			if ($nombreMotivo="PAGO DE INCAPACIDAD AL 66") {
				$diasincapacidad=$diasincapacidad+$permisosLic;
				$valorDiasIncapadidad=$valorDiasIncapadidad+$diasPerLicBasValortotalfinal;
			}
			$totaldevengado=$valordediastrabajados+$totalauxilio+$valorDiasIncapadidad+$valorDiasVacaciones;
			$totaldeduccion=$valorSalud+$valorPension+$restaABasico+$totaldeduccion;
			$rutaDeComproBas="desprendible_primo.php?cedula=".$rw1[5]."&nombre=".$rw1[1]." ".$rw1[2]."&cargo=$cargosaldo[1]&fechaini=$fechaAhora&fechafin=$fechafinal&cuenta=&diastrabajados=$diasdeprima&sueldo=$valordiasprima&auxilitrans=&pagdiasinca=&totaldeveng=$valordiasprima&salud=&pension=&prestamos=&totaldeduccion=&confirmado=$validadoDesprendible&diasIncapacidad=&firma=$rw1[17]&vacaciones=$valorDiasVacaciones&diasvacaciones=&sede=$rw1[7]&valorAjuste=&transporte=$cargosaldo[3]&sueldobasico=$cargosaldo[2]&semestre=$param36";
			// echo "<td><a href='$rutaDeComproBas' target='_blank'>ver</a>
			// <button style='display: $botonEnviar1;  width:120px;border:1px solid #f9f9f9;background-color: ".$colorEnviar.";color:#f9f9f9; font-size:15px' onclick='enviarDesprendible(\"$rutaDeComproBas\",$idusuario,\"$fechaactual\",\"$fechafinal\",\"guardarDesPrima\",\"Basico\")' id='$param36".$idusuario."guardarCuenCobro'>$textEnviar</button>
			// <input  type='checkbox' $cheked1 id='".$param36."".$idusuario."confirmaAdminPrima1' onchange='confirmaAdmin($idusuario,\"$fechaactual\",\"$fechafinal\",\"confirmaAdminPrima\",\"$param36\",1)' >
			// <label for='miCheckbox'>			
			// <details>
			// <summary>Confirmado</summary>
			// 	<p>$confirmado1<p/>
	        // </details>	
			// </label></td>";//IMPRIMIR
			// echo "<td>".$validado.$Observacion."</td>";//Confirmado?


			$valorHorasDomini_formateado = number_format($valorTotalHorasDomini, 0, ',', '.');

			  $colorselect1="#8B0000";
			  $si1="";
			  $no1="";
			  $imagencompr1="";
			  $linkotros="";
			  $textEnviar1="Enviar";
			  $colorEnviar1="rgb(7, 79, 145)";
			  $validado1="";
			  $Observacion1="";
			  $cheked2="";
			  $botonEnviar2="none";
			  $confirmado="";
			  $validadoDesprendible1="";
			 

			$ajustessumO=0;
			$ajustesresO=0;
			if ($tipoAjusteO=="suma") {
				$ajustessumO=$valorAjusteO;
				$ajustesresO=0;
			}else if($tipoAjusteO=="descuento"){
				$ajustessumO=0;
				$ajustesresO=$valorAjusteO;
			}

			$totalOtrosAPagar=($totalOtrosDias+$valorTotalHorasDomini+$valorRecogidas+$ajustessumO)-($restaAOtros+$ajustesresO);

			$totalOtrosAPagar_formateado = number_format($totalOtrosAPagar, 0, ',', '.');

			$totalOtrosConHD=$totalOtrosDias+$valorTotalHorasDomini;

		    $totalOtrosConHD_formateado = number_format($totalOtrosConHD, 0, ',', '.');
			$rutaDeComp="desprendibleOtros.php?cedula=".$rw1[5]."&nombre=".$rw1[1].$rw1[2]."&valor=".$totalOtrosConHD."&deudas=".$restaAOtros."&fechaini=".$fechaactualSinTiempo."&fechafin=".$fechafinalSinTiempo."&recogidas=".$cantRecogidas."&valorRecogidas=".$valorRecogidas."&otrosdeve=0&confirmado=$validadoDesprendible1&firma=$rw1[17]&valorAjuste=$valorAjusteO&tipoAjuste=$tipoAjusteO&descripcionAjuste=$descripcionAjusteO&descripcionOtros=$descripcionOtros";

			// echo "<td><div id='campo'>";
			// echo "<select  style='width:120px;border:1px solid #f9f9f9;background-color:".$colorselect.";color:#f9f9f9;font-size:15px'  name='$va' id='".$idusuario."$param36'  onchange='confirmarPago($idusuario,\"$fechaactual\",\"$fechafinal\",\"confirmarPagoPrima\",this.value,\"$param36\")' class='borrar' required>";

			// echo"<option value='no' $no>NO</option>";
			// echo"<option value='Si'$si>SI</option>";
		   
	
			// echo"</select>";

			






		
			  if ( $imagencompr=="") {
				// echo "<td>Cargar</td>";


			  
				}elseif ($imagencompr!=""){  
				//   echo "<td><a href='https://sistema.transmillas.com/img_nomina/$imagencompr' style='display: block;' target='_blank' title='Ver comprovante de pago de nomina' >Ver</a>";
				//   echo "<a id='Otros".$idusuario."' style='display: block;  pointer-events: ".$linkotros."; ' onclick='pop_dis16($idusuario,\"Comprobante_nomina_otros\",\"$fechaactual\")';  title='Ingreso de Usuario' >Cambiar</a></td>";

				}
			// echo "<td></td>";
			// echo "<td></td>";
			// echo "<td>$rw1[6]</td>";
			// echo "<td>$terminaContrato</td>";
			
			$totalDevengTodos=$totalDevengTodos+$TotalDevengado;

			$valorAPagarotrosTodos= $valorAPagarotrosTodos+$totalOtrosAPagar;
			$TotalDevengoyOtrosTodos= $TotalDevengoyOtrosTodos+$TotalDevengoyOtros;



		 }            
		 

		}	


	  }


	  $valorTotalDePrimasFormat = number_format($valorTotalDePrimas, 0, ',', '.');
	//   
  
	  $FB->titulo_azul1(" Totales :",1,0,10); 
	  $FB->titulo_azul1(" $va",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  echo "<td>$valorTotalDePrimasFormat </td>";
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  $FB->titulo_azul1(" ------",1,0,0); 
	  
	

$f1 = '2025-01-01';
$f2 = '2025-06-30';

$dias = diasEntreFechas($f1, $f2);
echo "Días entre $f1 y $f2: $dias";
function diasEntreFechas($fecha1, $fecha2) {
    $inicio = new DateTime($fecha1);
    $fin = new DateTime($fecha2);

    // Calcula la diferencia
    $diferencia = $inicio->diff($fin);

    // Devuelve el número de días (puede ser negativo si la fecha1 > fecha2)
    return (int)$diferencia->format('%r%a');
}
function obtenerUsuarioConNomina($identificacion) {
    global $DB1;

    $query = "SELECT `idusuarios` FROM `usuarios` 
              WHERE `usu_identificacion` = '$identificacion' 
              AND `usu_ver_nomina` = '1'";

    $DB1->Execute($query);
    return $DB1->recogedato(0);
}
function obtenerDatosCargo($idCargo) {
    global $DB1;

    $sql = "SELECT `idcargo`, `car_Cargo`, `car_Salario`, `car_Auxilio`, 
                   `car_otros`, `car_Recogida`, `car_ValorRecogida`
            FROM `cargo` 
            WHERE `idcargo` = '$idCargo'";

    $DB1->Execute($sql);
    return mysqli_fetch_row($DB1->Consulta_ID); // Devuelve array con los datos del cargo
}

include("footer.php");



?>
<input type="text" value="<? echo$tablaPago; ?>" id="tablaPago" name="tablaPago">
