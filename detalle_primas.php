
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
// $ano=date('Y');
$ano=$param34;
if($param34!=''){ $fechaactual=$param34." 00:00:00";  }
if($param36!=''){ $fechafinal=$param36." 23:59:59";  }


if($param36=='Primera'){
	$fechaactual=date($ano.'-01-01'.' 00:00:00');
	$fechafinal=date($ano.'-06-30'.' 23:59:59');
	$diasDeLaQuincena=15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-01');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-15');

}elseif($param36=='Segunda'){
	$fecha_aux = date($ano.'-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));
	$fechaactual=date($ano.'-07-01'.' 00:00:00');
	$fechafinal=date($ano.'-12-31 23:59:59');
	$diasDeLaQuincena=$fin-15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);
}elseif($param36=='Completo'){
	$fecha_aux = date($ano.'-'.$param34.'-d'); // Obtener la fecha actual en formato 'YYYY-MM-DD'
	$fin = date('t', strtotime($fecha_aux));

	$fechaactual=date($ano.'-'.$param34.'-01'.' 00:00:00');
	$fechafinal=date($ano.'-'.$param34.'-'.$fin.' 23:59:59');
	$diasDeLaQuincena=$fin-15;
	$fechaactualSinTiempo=date($ano.'-'.$param34.'-16');
	$fechafinalSinTiempo=date($ano.'-'.$param34.'-'.$fin);



}

function diasHastaFinDeAno() {
    // Definimos la fecha de inicio como mañana a las 00:00:00
    $mañana = new DateTime('tomorrow'); 
    $finDeAno = new DateTime(date('Y-12-31'));

    // Si hoy fuera 31 de diciembre, mañana sería 1 de enero.
    // Esta validación evita errores si ejecutas el código el último día del año.
    if ($mañana > $finDeAno) {
        return 0;
    }

    $diferencia = $mañana->diff($finDeAno);
    
    // Sumamos 1 para que incluya el último día (31 de dic) en la cuenta
    return $diferencia->days + 1;
}
function contarMesesCompletosDe31Dias($fechaInicio, $fechaFin) {
    // Usamos DateTimeImmutable para evitar que las fechas cambien accidentalmente
    $inicio = new DateTimeImmutable($fechaInicio);
    $fin = new DateTimeImmutable($fechaFin);
    
    if ($inicio > $fin) return 0;

    $contador = 0;
    $temp = $inicio;

    // 1. Si el rango no empieza el día 1, saltamos al primer día del mes siguiente
    // if ($temp->format('d') !== '01') {
    //     $temp = $temp->modify('first day of next month');
    // }

    // 2. Iteramos mientras el mes actual esté dentro del rango
    // Usamos 'midnight' para comparar solo fechas sin que las horas afecten
    while ($temp->modify('midnight') <= $fin->modify('midnight')) {
        
        $diasEnEsteMes = (int)$temp->format('t');
        
        // Creamos el objeto del último día de este mes
        $ultimoDiaMes = $temp->modify('last day of this month midnight');

        // CONDICIONES PARA CONTAR:
        // - El mes debe tener 31 días.
        // - El último día de este mes debe estar dentro del rango (menor o igual a $fin).
        if ($diasEnEsteMes === 31 && $ultimoDiaMes <= $fin->modify('midnight')) {
            $contador++;
        }
        
        // Saltamos al primer día del mes siguiente
        $temp = $temp->modify('first day of next month');
        
        // Seguridad para evitar bucles infinitos si la fecha es inválida
        if ($temp > $fin->modify('+1 month')) break;
    }

    return $contador;
}
//cuantos dias tiene la quincena
echo'<input type="hidden" value="'.$fechaactual.'" id="fechaactual">';
echo'<input type="hidden" value="'.$fechafinal.'" id="fechafin">';




$fechas=$fechaactual."/".$fechafinal;





$FB->titulo_azul1("",1,0,7); 
// $FB->titulo_azul1("Trabajador",1,0,0); 
echo "<td colspan='0' width='0' align='center'>Trabajador <br>Todo<input type='checkbox' id='check_todos' onclick='seleccionarTodos()' ></td>";
$FB->titulo_azul1("Tipo Contrato",1,0,0); 
$FB->titulo_azul1("Cedula",1,0,0); 
$FB->titulo_azul1("Cargo",1,0,0); 
$FB->titulo_azul1("Salario por mes",1,0,0); 
$FB->titulo_azul1("Auxilio",1,0,0); 
$FB->titulo_azul1("Ingresos ",1,0,0); 
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
$FB->titulo_azul1("Seleccion",1,'5%',0); 



if($param34 == 2 and $param36=='Segunda'){
	if($fin==29){
		$diasParaSumar=1;

	}else{
		$diasParaSumar=2;
	}


}else{

	$diasParaSumar=0;
}
if($param38=='' or $param38=='Trabajando'){   $conde3="and ( hoj_fechatermino is  null or hoj_fechatermino = '0000-00-00')"; }else{$conde3="and hoj_fechatermino is not null";}  

$valorTotalDePrimas=0;
$tablaPago="";
$sql="SELECT `idhojadevida`,  `hoj_nombre`, `hoj_apellido`,hoj_cargo,
 `hoj_tipocontrato`,`hoj_cedula`,`hoj_fechaingreso`, `sed_nombre`,
 `hoj_fechanacimiento`, `hoj_cedula`,`hoj_direccion`, `hoj_celular`,
 `hoj_estado`,hoj_sede,hoj_fechatermino,hoj_cuen,hoj_tcuenta,hoj_firma,
  hoj_estado,hoj_banco,hoj_fech_año_act FROM hojadevida
INNER JOIN sedes ON hoj_sede = idsedes
WHERE (idhojadevida > 0 AND hoj_estado = 'Activo' ) and hoj_tipocontrato='Empresa' $conde4 $conde $conde3
ORDER BY hoj_nombre ASC";
  $DB->Execute($sql); 
  $va=0; 
	  while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	  {



		$idusuario = obtenerUsuarioConNomina($rw1[5]);
		$nombreCompleto=$rw1[1].$rw1[2];
		if ($rw1[20]!="0000-00-00"){
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
			$fechaFinaliza=$fechafinal;

		}

		if($idusuario>=1 and $mostrar==true){

			echo "<tr class='text' bgcolor='$colorFila' onmouseover='this.style.backgroundColor=\"$colorFila\"' onmouseout='this.style.backgroundColor=\"$colorFila\"'>";		
			echo "<td>$idusuario</td>";
			echo "<td>$nombreCompleto</td>";
			echo "<td>".$rw1[4]."</td>";
			echo "<td>".$rw1[5]."</td>";

			$cargosaldo = obtenerDatosCargo($rw1[3],$ano);
			// Ejemplo de acceso a los datos:
			$nombreCargo = $cargosaldo[1];
			$salario     = $cargosaldo[2];
			$auxilio     = $cargosaldo[3];
			$salud     	 = $cargosaldo[7];
			$pension     = $cargosaldo[8];

			echo "<td>".$nombreCargo."</td>";
			echo "<td>".$salario     ."</td>";//Salario Mes
			echo "<td>".$auxilio    ."</td>";//Auxilio



			$conteo = obtenerConteoPorMotivo($fechaInicia, $fechaFinaliza, $idusuario);

			// Capturar cada motivo en su propia variable
			$Ingreso                   = $conteo['Ingreso'];
			$Sancionado               = $conteo['Sancionado'];
			$NoTrabajo                = $conteo['No trabajo']+$Sancionado;
			$Incapacidad              = $conteo['Incapacidad'];
			$SeDevolvio               = $conteo['Se devolvio'];
			$PositivoCovid            = $conteo['Positivo Covid'];
			$CancelacionContrato      = $conteo['Cancelacion contrato'];
			$AbandonoDePuesto         = $conteo['Abandono de puesto'];
			$Vacaciones               = $conteo['Vacaciones'];
			$descanso                 = $conteo['descanso']+$conteo['Descanso'];
			$IngresoPorHoras          = $conteo['Ingreso por horas'];
			$DescansoNoRemunerado     = $conteo['Descanso no remunerado'];
			$DiaDeSancionPs           = $conteo['Dia de sancion Ps'];
			$ReposicionPorFalla       = $conteo['Reposicion por falla'];
			$FestivoEnVacaciones      = $conteo['Festivo en vacaciones'];
			$LicenciaMaternidad       = $conteo['licencia de maternidad'];
			$LicenciaPorLuto          = $conteo['LICENCIA POR LUTO'];
			$PermisoNoRemunerado      = $conteo['PERMISO NO REMUNERADO'];
			$PagoIncapacidad66        = $conteo['PAGO DE INCAPACIDAD AL 66'];
			$Incapacidad50            = $conteo['incapasidad al 50 porciento'];
			$DiaSalarioMinimo         = $conteo['dia salario minimo'];
			$IngresoHoras			  = $conteo['IngresoHoras'];
			$IncapasidadEPS           = $conteo['Incapasidad paga por la EPS'];	

			$licenciasPermisos = $LicenciaMaternidad+$LicenciaPorLuto;
			$inicio = new DateTime($fechaInicia);
			$fin = new DateTime($fechaFinaliza);


			$Ingreso=($Ingreso+$ReposicionPorFalla+$IngresoPorHoras+$IngresoHoras);
			

			$totalDiasPrima=($Ingreso+$descanso+$Incapacidad+$Vacaciones+$licenciasPermisos+$IncapasidadEPS);
			
			

			//==================================================
			//               CALCULO DE PRECIO DE PRIMA
			//==================================================

			if($param36=='Segunda'){
				// Ejemplo de uso

				$DiasRestantes = diasHastaFinDeAno();
				// echo"Otro$totalDiasPrima+$DiasRestantes __";
				$totalDiasPrima=$totalDiasPrima+$DiasRestantes;

				$con31=contarMesesCompletosDe31Dias($fechaInicia, $fechaFinaliza);
				$totalDiasPrima=$totalDiasPrima-$con31;
				// echo"$totalDiasPrima=$totalDiasPrima-$con31";

					
			}
			$ValorDiaPrima=($salario+$auxilio)/360;
			$valorDiasPrima=$totalDiasPrima*$ValorDiaPrima;
			$valorTotalDePrimas=$valorDiasPrima+$valorTotalDePrimas;
			$valorDiasPrima_formateado = number_format($valorDiasPrima, 0, ',', '.');





			echo "<td colspan='1' width='0' align='center' ><a id='link'  onclick='pop_dis16($idusuario,\"Resumen_Quincena\",\"$fechas\")';  title='Ingreso de Usuario' >$Ingreso</td>"; //Ingreso?
			echo "<td>".$descanso     ."</td>";//cantidad de descansos
			echo "<td>".$NoTrabajo  ."</td>";//cantidad de no trabajados
			echo "<td>".$Incapacidad  ."</td>";//cantidad de incapacidad
			echo "<td>".$Vacaciones  ."</td>";//cantidad de vacaciones
			echo "<td>".$licenciasPermisos."</td>";//cantidad licenciasPermisos
			echo "<td style='background-color:rgb(240, 230, 170);'>".$totalDiasPrima  ."</td>";//Total Dias Prima
			echo "<td style='background-color:rgb(183, 230, 190);'>".$valorDiasPrima_formateado   ."</td>";//Fecha final de contrato


	
			 
				$rutaDeComproBas="desprendible_primo.php?cedula=".$rw1[5]."&nombre=".$rw1[1]." ".$rw1[2]."&cargo=$cargosaldo[1]&fechaini=$fechaAhora&fechafin=$fechafinal&diastrabajados=$totalDiasPrima&sueldo=$valordiasprima&totaldeveng=$valorDiasPrima&firma=$rw1[17]&sede=$rw1[7]&transporte=$cargosaldo[3]&sueldobasico=$cargosaldo[2]&semestre=$param36";

				// HTML final
				echo generarCeldasPrima(
					$idusuario, 
					$fechaactual, 
					$fechafinal, 
					$rw1, 
					$cargosaldo, 
					$totalDiasPrima, 
					$valordiasprima, 
					$valorDiasPrima, 
					$validadoDesprendible, 
					$param36, 
					$Observacion, 
					$DB1,
					$rutaDeComproBas
				);
				
				echo "<td>".$fechaIniciContrato    ."</td>";//Fecha inicio contrato
				echo "<td>".$fechaFinContrato    ."</td>";//Fecha final de contrato
				echo"<td><input type='checkbox'  onchange='selecionado1(
					$idusuario,
					\"$nombreCompleto\",
					\"$rw1[4]\",
					\"$rw1[5]\",
					\"$nombreCargo\",
					\"$salario\",
					\"$auxilio\",
					\"$descanso\",
					\"$NoTrabajo\",
					\"$Incapacidad\",
					\"$Vacaciones\",
					\"$licenciasPermisos\",
					\"$totalDiasPrima\",
					\"$valorDiasPrima_formateado\"
					)' class='check_hijo' id='".$idusuario."s1' value='$idusuario'></td>";
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
				}elseif ($rw1[19]=="BBVA"){
					$codigoBanco="13";
				}
				$tablaPago.="<td>$tipoCuenta</td>";
				$tablaPago.="<td>$codigoBanco</td>";
				$tablaPago.="<td>$rw1[15]</td>";
				$tablaPago.="<td>$valorDiasPrima_formateado</td>";
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
	  $FB->titulo_azul1(" ------",1,0,0); 
	  
	


function obtenerUsuarioConNomina($identificacion) {
    global $DB1;

    $query = "SELECT `idusuarios` FROM `usuarios` 
              WHERE `usu_identificacion` = '$identificacion' 
              AND `usu_ver_nomina` = '1'";

    $DB1->Execute($query);
    return $DB1->recogedato(0);
}
function obtenerDatosCargo($idCargo,$ano) {
    global $DB1;

    // $sql = "SELECT `idcargo`, `car_Cargo`, `car_Salario`, `car_Auxilio`, 
    //                `car_otros`, `car_Recogida`, `car_ValorRecogida`
    //         FROM `cargo` 
    //         WHERE `idcargo` = '$idCargo'";
    $sql="SELECT  `idcargo`, `car_Cargo`, `salario`, `auxilio`, `otros`,car_Recogida,car_ValorRecogida,des_salud,des_pension FROM `cargo`INNER JOIN salarios_cargos on idcargo=id_relCargo  WHERE idcargo='$idCargo' and anio='$ano'";


    $DB1->Execute($sql);
    return mysqli_fetch_row($DB1->Consulta_ID); // Devuelve array con los datos del cargo
}

function obtenerConteoPorMotivo($fechaInicio, $fechaFin, $idUsuario) {
    global $DB1;

    // Lista completa de motivos a considerar
    $motivos = [
        'Ingreso',
        'No trabajo',
        'Sancionado',
        'Incapacidad',
        'Se devolvio',
        'Positivo Covid',
        'Cancelacion contrato',
        'Abandono de puesto',
        'Vacaciones',
        'Descanso',
        'Ingreso por horas',
        'Descanso no remunerado',
        'Dia de sancion Ps',
        'Reposicion por falla',
        'Festivo en vacaciones',
        'licencia de maternidad',
        'LICENCIA POR LUTO',
        'PERMISO NO REMUNERADO',
        'PAGO DE INCAPACIDAD AL 66',
        'incapasidad al 50 porciento',
        'dia salario minimo',
		'IngresoHoras',
		'Incapasidad paga por la EPS'	
    ];

    // Escapar motivos para SQL
    $motivosSQL = implode("','", array_map('addslashes', $motivos));

    // Consulta SQL
    $sql = "
        SELECT seg_motivo, COUNT(*) AS cantidad 
        FROM seguimiento_user
        WHERE seg_fechaingreso BETWEEN '$fechaInicio' AND '$fechaFin'
          AND seg_idusuario = '$idUsuario'
          AND seg_motivo IN ('$motivosSQL')
        GROUP BY seg_motivo
    ";

    // Ejecutar consulta
    $DB1->Execute($sql);

    // Inicializar todos los motivos en 0
    $conteo = array_fill_keys($motivos, 0);

    // Recorrer resultados
    while ($fila = mysqli_fetch_assoc($DB1->Consulta_ID)) {
        $motivo = $fila['seg_motivo'];
        $cantidad = (int)$fila['cantidad'];
        $conteo[$motivo] = $cantidad;
    }

    return $conteo;
}
function generarCeldasPrima($idusuario, $fechaactual, $fechaFinaliza, $rw1, $cargosaldo, $totalDiasPrima, $valordiasprima, $valorDiasPrima, $validadoDesprendible, $param36, $Observacion, $DB1,$rutaDeComproBas) {
    $html = "";
			$colorSelect="#8B0000";
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

    $query = "SELECT `pri_confirma`, `idprimas`, `pri_docprima`, `pri_confirmaUsus`, `pri_fechaconfirmausu`, `pri_confiAdmin`, `pri_fechaadminconfi`, `pri_idadminconfi`, `pri_fecha_inicio`, `pri_fecha_fin`, `pri_idusu`, `pri_semestre`, `pri_img_compro` 
              FROM `primas` 
              WHERE pri_idusu='$idusuario' AND pri_fecha_inicio='$fechaactual'";
    $DB1->Execute($query); 
    $encontrado = false;

    while ($Nomina = mysqli_fetch_row($DB1->Consulta_ID)) {
        $encontrado = true;

        // ↓↓↓ misma lógica de antes ↓↓↓
        $imagenCompro   = $Nomina[12];
        $priConfirma    = $Nomina[0];
        $priDoc         = $Nomina[2];
        $confirmaUsus   = $Nomina[3];
        $fechaConfirmUs = $Nomina[4];
        $confirmaAdmin  = $Nomina[5];
        $fechaAdmin     = $Nomina[6];
        $idAdmin        = $Nomina[7];

        $colorSelect = ($priConfirma == "Si") ? "#28B463" : "#8B0000";
        $si = ($priConfirma == "Si") ? "selected" : "";
        $no = ($priConfirma != "Si") ? "selected" : "";
        $botonEnviar1 = ($confirmaAdmin != "si")  ? "none" : "inline-block";
        $textEnviar  = empty($priDoc) ? "Enviar" : "Reenviar";
        $colorEnviar = empty($priDoc) ? "rgb(7, 79, 145)" : "#28B463";

        if ($confirmaUsus == "Si") {
		$validadoDesprendible="Validado el $Nomina[4]  Por ".$rw1[1]." ".$rw1[2]." ";

            $validado = "Validado✅ <br> $fechaConfirmUs";
        } elseif ($confirmaUsus == "no") {
            $validado = "Rechazada❌ <br> $fechaConfirmUs";
        } else {
            $validado = "Pendiente";
        }
        $confirmado1 = $cheked1 = "";
        if ($confirmaAdmin == "si") {
            $cheked1 = "checked";
            $user1 = "SELECT `usu_nombre` FROM `usuarios` WHERE `idusuarios`='$idAdmin'";
            $DB1->Execute($user1); 
            $nombre1 = $DB1->recogedato(0);
            $confirmado1 = "Por $nombre1 <br> en la fecha: $fechaAdmin";
        }

        $rutaDeComproBas=$rutaDeComproBas."&confirmado=".$validadoDesprendible;

        $html .= "<td>
            <a href='$rutaDeComproBas' target='_blank'>Ver</a>
            <button 
                style='display: ".$botonEnviar1."; width:120px; border:1px solid #f9f9f9; background-color:".$colorEnviar."; color:#fff; font-size:15px; border-radius: 5px;' 
                onclick='enviarDesprendible(\"$rutaDeComproBas\", $idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"guardarDesPrima\", \"Basico\")' 
                id='{$param36}{$idusuario}guardarCuenCobro'>
                ".$textEnviar."
            </button>
            <input type='checkbox' $cheked1 id='{$param36}{$idusuario}confirmaAdminPrima1' 
                onchange='confirmaAdmin($idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"confirmaAdminPrima\", \"$param36\", 1)' />
            <label>
                <details><summary>Confirmado</summary><p>$confirmado1</p></details>
            </label>
        </td>";

        $html .= "<td>$validado $Observacion</td>";


        $html .= "<td>
            <select 
                style='width:120px; border:1px solid #f9f9f9; background-color:".$colorSelect."; color:#fff; font-size:15px; border-radius: 5px;'  
                name='$param36' 
                id='{$idusuario}{$param36}' 
                onchange='confirmarPago($idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"confirmarPagoPrima\", this.value, \"$param36\")' 
                class='borrar' required>
                    <option value='no' $no>NO</option>
                    <option value='Si' $si>SI</option>
            </select>
        </td>";
		if ( $imagenCompro=="") {
			$html .= "<td>Cargar</td>";
		}elseif ($imagenCompro!=""){  
			$html .= "<td><a href='https://sistema.transmillas.com/img_nomina/primas/$imagenCompro' style='display: block;' target='_blank' title='Ver comprovante de pago de nomina' >Ver</a>";
		}
    }

    // Si no encontró ningún registro en la tabla primas
    if (!$encontrado) {


        $html .= "<td>
            <a href='$rutaDeComproBas' target='_blank'>Ver</a>
            <button 
                style='display: ".$botonEnviar1."; width:120px; border:1px solid #f9f9f9; background-color:".$colorEnviar."; color:#fff; font-size:15px; border-radius: 5px;' 
                onclick='enviarDesprendible(\"$rutaDeComproBas\", $idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"guardarDesPrima\", \"Basico\")' 
                id='{$param36}{$idusuario}guardarCuenCobro'>
                $textEnviar
            </button>
            <input type='checkbox' $cheked1 id='{$param36}{$idusuario}confirmaAdminPrima1' 
                onchange='confirmaAdmin($idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"confirmaAdminPrima\", \"$param36\", 1)' />
            <label>
                <details><summary>Confirmado</summary><p>$confirmado1</p></details>
            </label>
        </td>";

        $html .= "<td>Pendiente $Observacion</td>";

        $html .= "<td>
            <select 
                style='width:120px; border:1px solid #f9f9f9; background-color:".$colorSelect."; color:#fff; font-size:15px; border-radius: 5px;'  
                name='$param36' 
                id='{$idusuario}{$param36}' 
                onchange='confirmarPago($idusuario, \"$fechaactual\", \"$fechaFinaliza\", \"confirmarPagoPrima\", this.value, \"$param36\")' 
                class='borrar' required>
                    <option value='no' $no>NO</option>
                    <option value='Si' $si>SI</option>
            </select>
        </td>";
		if ( $imagenCompro=="") {
			$html .= "<td>Cargar</td>";
		}elseif ($imagenCompro!=""){  
			$html .= "<td><a href='https://sistema.transmillas.com/img_nomina/$imagenCompro' style='display: block;' target='_blank' title='Ver comprovante de pago de nomina' >Ver</a>";
		}
    }

    return $html;
}

include("footer.php");



?>
<input type="hidden" value="<? echo$tablaPago; ?>" id="tablaPago" name="tablaPago">
