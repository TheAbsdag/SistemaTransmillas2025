<?php 
require("login_autentica.php");
include("cabezote3.php"); 
$id_usuario=$_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$asc="ASC";
$conde1=""; 
$conde3=""; 
$conde2=""; 
$opcion=$_REQUEST["preguia"];

if($param4!=''){  $fechainicio=$param4;}
if($param5!=''){  $fechaactual=$param5;}

if($param1==""){ $param1="ser_prioridad"; } 
if($param2!=''){ $conde2 =" and ser_numerofactura like '%$param2%'";  }
if($param3!=''){ $conde3 =" and (rel_nom_credito like '%$param3%')";  }else if($param13!=''){$conde3 =" and (rel_nom_credito like '%$param13%')";}

if($param6=='Sin Facturar'){
	$conde4=' and ser_numerofactura is null';
}elseif($param6=='Facturados'){
	$conde4=' and ser_numerofactura>=1';
}else{
	$conde4='';	
}


$sql="SELECT `idservicios`,`ser_fechaentrega`,
`cli_nombre`, `cli_telefono`,`cli_direccion`,
`ser_destinatario`, `ser_telefonocontacto`,
`ser_direccioncontacto`,`ciu_nombre`,`ser_prioridad`,
ser_fecharegistro,ser_consecutivo,ser_guiare,
cli_idciudad,ser_valorprestamo,ser_valor,
rel_nom_credito,ser_numerofactura,ser_valorseguro
FROM  servicios s 
inner join rel_sercli  on idservicios=ser_idservicio 
inner join clientesservicios on idclientesdir=ser_idclientes 
inner join ciudades on idciudades=cli_idciudad   
inner join rel_sercre rs on rs.idservicio=idservicios 
where date(ser_fecharegistro)>='$fechainicio' 
and  date(ser_fecharegistro)<='$fechaactual' 
and ser_clasificacion=2  
and ser_estado>=3 
and ser_estado!=100  $conde1 $conde2 $conde3  $conde4 
ORDER BY idrelsercre $asc  ";
$contsinpesar=0;
$idguias='';
$html1= "";
$totalcontado=0;
$guiafacturadas=0;

$DB->Execute($sql); $va=0; 

	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p=$rw1[0];
		$va++; $p=$va%2;
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		if($rw1[17]=='' or $rw1[17]==null){
			 $rw1[17]='Sin Facturar'; 
			$idguias=$id_p.','.$idguias;
		}else{
			$estadog='Hay Guias Facturadas en este Preriodo de Tiempo';
			$color='#A4EC5C';
			$guiafacturadas++;
		}
		
		$html1.="<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		$direc1=str_replace("&"," ", $rw1[4]);
		$direct2=str_replace("&"," ", $rw1[7]);
		$pordeclarado=(intval($rw1[18])*1)/100;
		$rw1[15]=$rw1[15]+$pordeclarado;
		$totalcontado=$rw1[15]+$totalcontado;
		if ($rw1[15]<=2000) {
			$colorflete="#e73c3c";
			$contsinpesar=$contsinpesar+1;
		}else {
			$colorflete="";
		}
		

		$html1.="<td>".$rw1[10]."</td>
		<td>".$rw1[11]."</td>
		<td>".$rw1[12]."</td>
		<td>".$rw1[2]."</td>
		<td>".$rw1[3]."</td>
		<td>".$direc1."</td>
		<td>".$rw1[5]."</td>
		<td>".$rw1[6]."</td>
		<td>".$direct2."</td>
		<td>".$rw1[8]."</td>
		<td bgcolor='$colorflete'>$ ".$rw1[15]."</td>
		<td>".$rw1[16]."</td>
		<td>".$rw1[17]."</td>
		";
			$sqlrecogida ="SELECT ima_ruta,ima_tipo,idimagenguias,ima_fecha from imagenguias where ima_tipo like '%Recogida%' and  ima_idservicio=$id_p ";
			if ($guiasir[3]<"2024-12-01") {

					
					$DB1->Execute($sqlrecogida); 
					$guiasir=mysqli_fetch_row($DB1->Consulta_ID);
					$recogidag=$guiasir[0];
					if ($recogidag=='') {
						$colorfoto="#e74c3c";
						$confotor="Sin foto";
					}else {
						$colorfoto="";
						$confotor="<a href=' https://8b55-190-24-125-143.ngrok-free.app/SistemaTransmillas/$recogidag' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
						
					}

			}elseif ($guiasir[3]<="2025-02-13") {

					$DB1->Execute($sqlrecogida); 
					$guiasir=mysqli_fetch_row($DB1->Consulta_ID);
					$recogidag=$guiasir[0];
					if ($recogidag=='') {
						$colorfoto="#e74c3c";
						$confotor="Sin foto";
					}else {
						$colorfoto="";
						$confotor="<a href='$recogidag' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
						
					}

			}else{
				$colorfoto="";
				if (strpos($recogidag, 'ticketfacturacorreoimprimir') !== false) {
					$recogidag="$recogidag&vis=adm";
				} 
				$confotor="<a href='$recogidag' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
				
			}


		

		$confoto="";
		$sqlentrega="SELECT ima_ruta,ima_tipo,idimagenguias,ima_fecha from imagenguias where ima_tipo like '%Entrega%' and  ima_idservicio=$id_p ";

			if ($guiasi[3]<"2024-12-01") {

				$DB1->Execute($sqlentrega); 
				$guiasi=mysqli_fetch_row($DB1->Consulta_ID);
				$entrgasg=$guiasi[0];
				if ($entrgasg=='') {
					$colorfoto="#e74c3c";
					$confoto="Sin foto";
				}else {
						$colorfoto="";
						$confoto="<a href='  https://8b55-190-24-125-143.ngrok-free.app/SistemaTransmillas/$entrgasg' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
						
				}

			}elseif ($guiasi[3]<="2025-02-13") {

				$DB1->Execute($sqlentrega); 
				$guiasi=mysqli_fetch_row($DB1->Consulta_ID);
				$entrgasg=$guiasi[0];
				if ($entrgasg=='') {
					$colorfoto="#e74c3c";
					$confoto="Sin foto";
				}else {
					$colorfoto="";
					$confoto="<a href='$entrgasg' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
				}
				
			}else{
				$colorfoto="";
				if (strpos($entrgasg, 'ticketfacturacorreoimprimir') !== false) {
					$entrgasg="$entrgasg&vis=adm";
				}else {
					$entrgasg=$guiasi[0];
				}
					$confoto="<a href='$entrgasg' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";


				
			} 


		
		$html1.= "<td align='center'  bgcolor='$colorfoto'>";
		$html1.= "$confotor";
		$html1.= "</td>";
		$html1.= "<td align='center'  bgcolor='$colorfoto'>";
		$html1.= "$confoto";
		$html1.= "</td>";

		$html1.="<td align='center' ><a  onclick='pop_dis5($id_p,\"Recogidas\")';  style='cursor: pointer;' title='Recogidas' ><img src='img/recogidas.png'></a></td>";

		$html1.= "</tr>"; 
	}
	$html1.= "<tr><td align='center' > Total Datos:".$va."</td>"; 
	
	$html1.= "</tr>"; 
	$total=$va-$guiafacturadas;
	
	if($opcion==3 and $total>=1){
		$idguias = substr($idguias, 0, -1);
		 $variable=date("Y").date("m").date("d").date("h").date("i").date("s");
		  $variableunica=$variable;
		$fechafactura='DE: '.$fechainicio.' Hasta '.$fechaactual;
		$sqll1="INSERT INTO `facturascreditos`(`fac_numerofactura`,`fac_fechafactura`,`fac_fechaprefac`,`fac_idservicios`, `fac_estado`,`fac_credito`, `fac_iduserpre`) 
		values ('$variableunica','$fechaactual','$fechafactura','$idguias','Pre-Facturado','$param3','$id_nombre')";
		$DB1->Execute($sqll1);
		
		echo '<div class="alert alert-success" role="alert">
			Se agrego la Pre-Factura con existo, Con '.$total.' Guias 
		</div>';
		if($estadog!=''){
			echo '<div class="alert alert-danger" role="alert">'.$estadog.'</div>';
		}
	}elseif($opcion==3 and $va<=0) {
		echo '<div class="alert alert-danger" role="alert">No hay Guias para Facturar</div>';
	}
	
	if ($contsinpesar>0) {
		echo '<div class="alert alert-danger" role="alert">
		¡Hay '.$contsinpesar.' guias sin pesar verifique!
	  </div>';
		// echo 'alertaGuias();';
		// echo '</script>';
	}
$FB->titulo_azul1("Total GUIAS: $va",1,0,7); 
$FB->titulo_azul1("Total Flete:",1,0,0); 
$FB->titulo_azul1(" $totalcontado",1,0,0); 

$FB->titulo_azul1("Fecha Ingreso",1,0,7); 
$FB->titulo_azul1("#Guia",1,0,0); 
$FB->titulo_azul1("#Relacionado",1,0,0); 
$FB->titulo_azul1("Remitente",1,0,0); 
$FB->titulo_azul1("Tel&eacute;fono",1,0,0); 
$FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
$FB->titulo_azul1("Destinatario",1,0,0); 
$FB->titulo_azul1("Tel&eacute;fono",1,0,0); 
$FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
$FB->titulo_azul1("Ciudad",1,0,0); 
$FB->titulo_azul1("Flete + %Seguro",1,0,0); 
$FB->titulo_azul1("Credito",1,0,0); 
$FB->titulo_azul1("Factura No",1,0,0); 
$FB->titulo_azul1("Guia R",1,0,0); 
$FB->titulo_azul1("Guia E",1,0,0); 
$FB->titulo_azul1("Imagen",1,0,0); 

echo $html1;

$FB->titulo_azul1("Total GUIAS: $va",1,0,7); 
$FB->titulo_azul1("Total Flete:",1,0,0); 
$FB->titulo_azul1(" $totalcontado",1,0,0); 

include("footer.php");
?>