<?php
require("login_autentica.php");
include("layout.php");
$DB2 = new DB_mssql;
$DB2->conectar();

$fechaactual = date("Y-m-d");
$id_usuario = $_SESSION['usuario_id'];	
if ($param2 == '') {
	$param2 = 0;
}
?>
	<script>

function alertaDinero(){


	alert("alertaDinero()");
}
</script>
<head>
	<script>
	</script>
</head>
<body>

	<?php

	//$id_sedes=$_SESSION['usu_idsede'];
	//echo $_SESSION['usuario_rol'];
	$FB->abre_form("form1", "", "post");
	$FB->titulo_azul1("  Recogidas / Entregas", 1, 0, 5);


	$Querydrag_drop = ("SELECT  orden_idservicio,orden_iduserencargado,orden_id,orden,ord_estado,ord_tipo FROM ord_recoentregas WHERE orden_iduserencargado =$id_usuario and orden_fechadiaejecucion ='$fechaactual' order by orden asc ");
	$DB2->Execute($Querydrag_drop);
	$orden1 = 0;
	$va = 0;
	while ($dataDrag_Drop = mysqli_fetch_assoc($DB2->Consulta_ID)) {

		$imprimir = 0;
	
		$idservicio = $dataDrag_Drop['orden_idservicio'];
		if ($dataDrag_Drop['ord_tipo'] == 'Recogida' or $dataDrag_Drop['ord_tipo'] == 'Entrega') {
			$conde3 = "";
			$conde3 = "and ((ser_idresponsable='$id_usuario' and ser_fechaasignacion like '$fechaactual%' and ser_estado=3 ) or ( ser_idusuarioguia='$id_usuario' and ser_fechaguia like '$fechaactual%' and ser_estado=9))";

			$conde1 = "";

			$sql = "SELECT `idservicios`,`ser_fechaentrega`,`cli_nombre`,`cli_telefono`,`cli_direccion`, `ser_destinatario`, `ser_telefonocontacto`,
 `ser_direccioncontacto`,`ciu_nombre`,`ser_prioridad`,ser_estado,ser_visto,usu_nombre,`ser_fechaasignacion`,`ser_consecutivo`,ser_valorprestamo,ser_guiare,ser_piezas
 FROM serviciosdia inner join usuarios on ser_idresponsable=idusuarios   where ser_estado in (3,9) $conde3  and  idservicios='$idservicio' ORDER BY ser_fechaentrega $asc ";

			$DB->Execute($sql);

			while ($rw1 = mysqli_fetch_row($DB->Consulta_ID)) {
				$id_p = $rw1[0];
				$va++;
				$p = $va % 2;
				if ($p == 0) {
					$color = "#FFFFFF";
				} else {
					$color = "#EFEFEF";
				}
				$rw1[4] = str_replace("&", " ", $rw1[4]);
				$rw1[7] = str_replace("&", " ", $rw1[7]);
				echo "<tr style='font-size:12px;text-align:left;' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				echo "<td>";

				echo "<div class='alert alert-success'>";
				if ($rw1[10] == 3) {
					$valorcompra = "";

					if ($rw1[9] == "Compra") {
						$valorcompra = "$ " . $rw1[15];
					}
					echo "<h4><span class='label label-warning'> $rw1[9] $valorcompra</span></h4>";

					//	echo "FECHA: $rw1[13]<br>";
					echo "<p  align='left'>RECOGIDA: $rw1[1]<br>";
					echo "CLIENTE: $rw1[2]<br>";
					echo "TELÉFONO: *********<br></p>";
					echo "<div class='alert alert-info'>DIRECCIÓN: $rw1[4]</div>";
					echo "<p  align='left'>DESTINATARIO: $rw1[5]<br>";
					//echo "TELÉFONO: $rw1[6]<br>";
					echo "CIUDAD: $rw1[8]<br></p>";

					echo "<div id='campo$va'>";
					if ($rw1[11] == 1) {
						$st = "SI";
						$colorfondo = "#074f91";
					} else {
						$st = "NO";
						$colorfondo = "#941727";
					}
					echo " ¿EN RUTA?<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 110, \"campo$va\", \"$va\", 1, $id_p)'  required>";
					$LT->llenaselect_ar($st, $estado_rec);
					echo "</select>";

					if ($rw1[11] == 1) {
						// echo "<a  onclick='pop_dis133($id_p,\"Recoger Paquete\")';  style='cursor: pointer;' class='btn btn-primary btn-lg' title='Recoger Paquete' role='button' >Recoger</a>";
					}
					echo "</div>";
				} else  if ($rw1[10] == 9) {

					echo "<h4><span class='label label-warning'>ENTREGAS</span></h4>";
					echo "<div class='alert alert-info'>DIRECCIÓN: $rw1[7]</div>";
					echo "<p  align='left'>DESTINATARIO: $rw1[5]<br>";
					echo "TELÉFONO: *********<br>";
					echo "<p><strong style='font-size:18px; color:#941727; font-weight:bold;'>PIEZAS: $rw1[17]</strong></p>";
					echo "CIUDAD: $rw1[8]<br>";
					echo "Pre GUIA: $rw1[16]<br>";
					echo "GUIA: $rw1[14]<br></p>";


					if ($rw1[11] == 1) {
						$st = "SI";
						$colorfondo = "#074f91";

						$estado_rec2[0] = "SI";
						echo "<select name='param14' id='param14' class='form-control'  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'  required>";
						$LT->llenaselect_ar($st, $estado_rec2);
						echo "</select>";
					} else {
						$st = "NO";
						$colorfondo = "#941727";
						echo "<div id='campo$va'>";
						echo " ¿EN RUTA?<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 111, \"campo$va\", \"$va\", 1, $id_p)'  required>";
						$LT->llenaselect_ar($st, $estado_rec);
						echo "</select>";
						echo "</div>";
					}
					// echo "<a  onclick='pop_dis13($id_p,\"Entregar Guias\")';  style='cursor: pointer;' class='btn btn-primary btn-lg' title='Entregar Guias' role='button' >Entregar</a>";
				}
				echo "</p></div></td>";

				echo "</tr>";
			}
		} elseif ($dataDrag_Drop['ord_tipo'] == 'Remesa') {

			$conde22 = " and ((gas_iduserrecoge='$id_usuario' and gas_recogio=0 ) or (gas_iduserremesa='$id_usuario' and gas_entrego=0))";
			$conde11 = "and ((date(gas_fecharegistro)<='$fechaactual' and gas_usucom<=0 ) OR (date(gas_feccom)>='$fechaactual' ))";
			$fecha = date("Y-m-d", strtotime($fecha_actual . "- 10 days"));

	$sql = "SELECT `idgastos`, `gas_fecharegistro`, `usu_nombre`, `gas_idciudadori`, `sed_nombre`, `gas_empresa`, `gas_bus`, `gas_telconductor`,`gas_pagar`,`gas_iduserremesa`, `gas_nomremesa`,`gas_descripcion`,`gas_peso`,`gas_piezas`,`gas_valor`,gas_usucom,
  gas_cantcom,gas_feccom ,gas_idciudaddes,gas_iduserrecoge,gas_recogio,gas_entrego,gas_fecrecogida 
  FROM `gastos` inner join usuarios on gas_idusuario=idusuarios inner join sedes on idsedes=gas_idciudaddes
   WHERE idgastos>0  and gas_fecharegistro>='$fecha' $conde11 $conde22 and idgastos='$idservicio' ORDER BY gas_fecharegistro desc";
			$DB1->Execute($sql);
			while ($rw1 = mysqli_fetch_row($DB1->Consulta_ID)) {
				$id_p = $rw1[0];
				$va++;
				$p = $va % 2;

				if ($rw1[16] <= 0) {
					$estador = "Pendiente por Aprobar";
					$color2 = "#00FF00";
				} else {
					$estador = "Solicitud  Aprobada";
					$color2 = "#C8C6F9";
				}

				if ($p == 0) {
					$color = "#FFFFFF";
				} else {
					$color = "#EFEFEF";
				}



				$sql2 = "SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes='$rw1[3]'";
				$DB->Execute($sql2);
				$rw = mysqli_fetch_row($DB->Consulta_ID);

				echo "<tr style='font-size:12px;text-align:left;' bgcolor='$color2' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				echo "<td>";
				echo "<h4><span class='label label-warning'>REMESA - $estador</span></h4>";
				echo "<div class='alert alert-info'>
				Empresa TR: $rw1[5] </BR>
				# BUS: $rw1[6] </BR>
				Tel Conductor: $rw1[7] </BR>
				</div>";
				echo "<p  align='left'>Sede Origen: $rw[1]<br>";
				echo "Sede Destino: $rw1[4]<br>";
				echo "Descripcion: $rw1[11]<br>";
				echo "Peso: $rw1[12]<br>";
				echo "Piezas: $rw1[13]<br>";
				echo "Pagar en?: $rw1[8] -  valor para Aprobar: $rw1[14] <br>";
				echo "Confirmo: $rw1[15]<br>";


				if ($id_usuario == $rw1[19]) {

					$rw1[16] = number_format($rw1[16], 0, ".", ".");

					if ($rw1[8] == 'Sede Origen') {
						echo "<h4><span class='label label-danger'>REMESA  PAGADA: $rw1[16]</span></h4>";
					} else {
						echo "<h4><span class='label label-warning'>VALOR  APROBADO A PAGAR: $rw1[16]</span></h4>";
					}

					$modo = "RECOGER";
					if ($rw1[20] == 1) {
						$st = "SI";
						$colorfondo = "#074f91";
					} else if ($rw1[21] == 2) {
						$st = "Devolver";
						$colorfondo = "#074f91";
					} else {
						$st = "Selecccione...";
						$colorfondo = "#941727";
					}

					echo "<div id='campo$va'>";
					echo "$modo <select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 75, \"campo$va\", \"$va\", 1, $id_p)'  required>";
					$LT->llenaselect_ar($st, $estado_pen);
					echo "</select>";
					echo "</div>";
				} else if ($id_usuario == $rw1[9] and $rw1[15] != '') {
					$modo = " ENTREGADO";
					$rw1[16] = number_format($rw1[16], 0, ".", ".");
					if ($rw1[8] == 'Sede Origen') {
						echo "<h4><span class='label label-warning'>VALOR  APROBADO A PAGAR: $rw1[16]</span></h4>";
					} else {
						//echo "<h4><span class='label label-warning'>REMESA  PAGADA: $rw1[16]</span></h4>";
					}

					if ($rw1[21] == 1) {
						$st = "SI";
						$colorfondo = "#074f91";
					} else if ($rw1[21] == 2) {
						$st = "Devolver";
						$colorfondo = "#074f91";
					} else {
						$st = "Selecccione...";
						$colorfondo = "#941727";
					}

					echo "<div id='campo$va'>";
					echo "$modo <select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 76, \"campo$va\", \"$va\", 1, $id_p)'  required>";
					$LT->llenaselect_ar($st, $estado_pen);
					echo "</select>";
					echo "</div>";
				}

				echo "</p></div></td>";


				echo "</tr>";
			}
		} elseif ($dataDrag_Drop['ord_tipo'] == 'diligencia') {

			$sql6 = "SELECT iddiligencias,`dil_ruta`, `dil_user`, `dil_fecha`, `dil_estado`,'2001' as ser_estado from diligencias where  iddiligencias=$idservicio and dil_estado='Activo'";
			$DB1->Execute($sql6);
			$diligencia = mysqli_fetch_row($DB1->Consulta_ID);
			$color = "#EFEFEF";
			if($diligencia[0]!=''){
				$va++;

				echo "<tr style='font-size:12px;text-align:left;' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				echo "<td>";

				echo "<div class='alert alert-success'>";
				echo "<h4><span class='label label-warning'>En Descargue.</span></h4>";
				echo "<div class='alert alert-info'>
				Ruta: $diligencia[1] </BR>
				</div>";

				echo "<div id='campo$va'>";
				echo "Termino <select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 83, \"campo$va\", \"$va\", 1, $idservicio)'  required>";
				$LT->llenaselect_ar($st, $estados);
				echo "</select>";
				echo "</div>";

				echo "</p></div></td>";
				echo "</tr>";
			}
		}
	}


	include("footer.php");
	?>
