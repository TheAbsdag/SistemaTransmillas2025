<?php
require("login_autentica.php");
include("layout.php");
$id_usuario=$_SESSION['usuario_id'];

?>
<style>
    #link { color: #FF0000; } /* CSS link color */
	
        .warning {
            color: red;
            display: none;
        }
	
        /* Estilos para oscurecer el fondo */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none; /* Oculto por defecto */
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Estilos de la ventana */
        .ventana {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .ventana h2 {
            margin: 0 0 10px;
        }

        .boton-cerrar {
            margin-top: 15px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }


        .boton-cerrar:hover {
            background: #0056b3;
        }
    
  </style>
  	<div id="miVentana" class="overlay">
        <div class="ventana">
            <h2 id="h2"></h2>
            <p id="p1"></p>
			<p id="p2"></p>           
			<img id="imagenDinamica" style="width: 200px;  height: auto; margin-bottom: 10px;" src="" alt="Imagen">
			<h2 id="numeroDinamico"></h2>
			<button id="cerrarVentana" class="boton-cerrar">Cerrar</button>
        </div>
	</div>
<script>
	    function mostrarVentana(foto,kilo) {
            const miVentana = document.getElementById('miVentana');
			const imagen = document.getElementById('imagenDinamica');
            const numeroTexto = document.getElementById('numeroDinamico');
			const h2 = document.getElementById('h2');
			const p1 = document.getElementById('p1');
			const p2 = document.getElementById('p2');

			if (foto==0) {

				h2.textContent = `¡Este operador no a subido el kilometraje!`;
				p1.textContent = `Recuerda que el operador debe subir foto del kilometraje al finalizar su jornada.`;
				p2.textContent = `En la parte superior izquierda en  opcion salida.`;
				imagen.src = "img/estado.png";

			}else{

				// Asignar la URL de la imagen y el número
				imagen.src = "preoperacional/"+foto;
            	numeroTexto.textContent = `Kilometraje: ${kilo}`;
				h2.textContent = `¡Este operador subido el kilometraje!`;
				p1.textContent = `verifica si coincide la foto y el numero de kilometraje si no comunicate con el operador.`;

			}
			

            miVentana.style.display = 'flex'; // Cambia display a flex para mostrarla
        }

        // Cerrar la ventana flotante
        document.getElementById('cerrarVentana').addEventListener('click', () => {
            const miVentana = document.getElementById('miVentana');
            miVentana.style.display = 'none'; // Oculta la ventana
        });

function validarllegada(des)
{

var valorguia= document.getElementById("codigoEscaneado").value;



	var guia="";
	var trueorfalse = false;
		datos = {"tipoguia":"cuentasoperador","registros":valorguia};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(result) {

						$("#mensaje").modal("show");
							var divvalor= document.getElementById("mensajevalor3");
							divvalor.innerHTML="<strong>OK!</strong>  GUIA VALIDADA CON EXITO</a>";

							return false;


				}
			});



}
</script>

<?php
$conde3="";
$conde1="";
$condet1="";
$conde11="";

if($nivel_acceso==1 or $nivel_acceso==10 or  $nivel_acceso==12 or  $nivel_acceso==9){ $conde4=""; 	 } else { $conde4=" and idsedes=$id_sedes";  }

if(isset($_REQUEST["param1"])){ if($param1!=""){   $id_sedes=$param1; } } else { $param1="";   }
 if($param2!=""){
	  $conde1 ="and asi_idpromotor='$param2'";
	  $condet1 ="and asi_idautoriza='$param2'";
	 $conde3="and (cue_idoperador='$param2'  or cue_idoperentrega='$param2' or cue_idoperpendiente='$param2')";
	 $conde11="and ((gas_iduserremesa='$param2' AND gas_feccom like '$param4%' and gas_pagar='Sede Origen')  or (gas_iduserrecoge='$param2' AND gas_fechavalida like '$param4%' and gas_pagar='Sede Destino' and gas_nomvalida!=''))";
	}
	 else { $conde3="and cue_idoperador=0";}


if($param4!=''){
	 $conde1.=" and asi_fechaconf like '$param4%'";
	 $condet1.=" and asi_fechaconf like '$param4%'";
	 $fechaactual=$param4;

	} else {
		$conde1.=" and asi_fechaconf = '0'";

	}

$FB->titulo_azul1("Cuetas Operador ",9,0,7);
$FB->abre_form("form1","","post");

$conde3.=" and (cue_fecha like '$fechaactual%' or cue_fecharecogida like '$fechaactual%' or cue_fechapcobrar like '$fechaactual%')";
$conde="";
$conde="asi_fecha";
$conde2="and usu_idsede=$id_sedes";

$FB->llena_texto("Sede:",1,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde4 )", "cambio4(\"param2\",\"param1\",\"cuentasoper.php\")", "$id_sedes", 1, 1);
$FB->llena_texto("Fecha de Busqueda:", 4, 10, $DB, "", "", "$fechaactual", 4, 0);
$FB->llena_texto("Operario:",2,2, $DB, "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE  (usu_estado=1 or usu_filtro=1) $conde2 	AND roles_idroles IN (2,3,5,8)", "", "$param2", 1, 1);
echo '<td class="text">Escanear Código: </td><td align="right" ><div class="form-group">
<div class="input-group">
	<div class="input-group-addon"><i class="fa fa-barcode"></i></div>
	<input autofocus type="text" class="form-control producto" name="codigoEscaneado" id="codigoEscaneado" autocomplete="off" onchange="validarllegada(this);">
</div>
</div></td>';
$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);
$FB->cierra_form();

if($param2!=''){
	$FB->titulo_azul1("GUIAS PENDIENTES POR VALIDAR FIRMA",1,0,7);
	$FB->titulo_azul1("GUIA",1,0,7);
	$FB->titulo_azul1("TIPO",1,0,0);
	echo "</tr>";
	$fechaactual2=date("Y-m-d",strtotime($fechaactual."- 1 days"));
$sql="SELECT cue_numeroguia,'recogida' as estado FROM `cuentaspromotor` WHERE cue_idoperador='$param2' and cue_validado=0 and cue_estado<100 and  cue_fecharecogida<='$fechaactual2'
 union
 SELECT cue_numeroguia,'Entrega' as estado FROM `cuentaspromotor` WHERE   cue_idoperentrega='$param2' and cue_validadoentrega=0 and cue_estado<100 and  cue_fecha<='$fechaactual2'
 ";
 $DB1->Execute($sql);
 while($rw21=mysqli_fetch_row($DB1->Consulta_ID))
 {
	 echo "<tr>";
	 if($rw21[1]=='recogida'){
		echo "<td> ".$rw21[0]."</td>
		<td>PENDIENTE VALIDAR FIRMA RECOGIDA </td>";
	 }else{
		echo "<td> ".$rw21[0]."</td>
		<td>PENDIENTE VALIDAR FIRMA ENTREGA</td>";
	 }

	 echo "</tr>";
 }
}
$recoger=0;
$FB->titulo_azul1("GUIAS PENDIENTES POR RECOGER",1,0,7);
$FB->titulo_azul1("IDservicio",1,0,7);
$FB->titulo_azul1("TIPO",1,0,0);
 $vsql="SELECT idservicios,'recogida' as estado FROM `servicios` WHERE ser_idresponsable='$param2' and ser_estado=3 and  ser_fechaasignacion like '$fechaactual%'";
$DB1->Execute($vsql);
while($rw23=mysqli_fetch_row($DB1->Consulta_ID))
{
	echo "<tr>";
	   echo "<td> ".$rw23[0]."</td>
	   <td>RECOGIDA AUN ASIGNADA</td>";
	echo "</tr>";
	$recoger=1;
}



$FB->titulo_azul1("VALIDAR",1,0,7);
$FB->titulo_azul1("GUIAS",1,0,0);
$FB->titulo_azul1("GUIA R",1,0,0);
$FB->titulo_azul1("IMAGEN",1,0,0);


$FB->titulo_azul1("TIPOPAGO",1,0,0);
$FB->titulo_azul1("PXCOBRAR",1,0,0);
$FB->titulo_azul1("PXCANCELAR",1,0,0);
$FB->titulo_azul1("RECOGIDAS / ENVIOS",1,0,0);
$FB->titulo_azul1("COMPRAS",1,0,0);
$FB->titulo_azul1("ALCOBRO",1,0,0);
$FB->titulo_azul1("PRESTAMOS",1,0,0);
$FB->titulo_azul1("ABONOS",1,0,0);
$FB->titulo_azul1("EXEDENTE",1,0,0);
$FB->titulo_azul1("REMESAS",1,0,0);
$FB->titulo_azul1("IMAGEN ",1,0,0);
$FB->titulo_azul1("VALIDAR ",1,0,0);
$FB->titulo_azul1("TRANSPASOS",1,0,0);
$FB->titulo_azul1("PARA COMPRAS",1,0,0);
$FB->titulo_azul1("GASTOS ",1,0,0);
$FB->titulo_azul1("IMAGEN ",1,0,0);
$FB->titulo_azul1("VALIDAR ",1,0,0);
$FB->titulo_azul1("OBSERVACIONES",1,0,0);

$totalasignacion=0;
$totaltranspaso=0;

$totalarecogidas=0;
$totalcompras=0;
$totalgastos=0;
$totalremesas=0;
$va=0;
$va2=0;
$va3=0;
$va4=0;
$va5=0;
$va6=0;
 if($param2!=''){
	$sql="SELECT `idasignaciondinero`,`asi_fecha`,`asi_valor`,  `asi_idautoriza`, `asi_idpromotor` ,asi_tipo,asi_descripcion,asi_usercom,asi_valorcom, asi_idvalidaf as idvalida,'asignaciondinero' as tabla
	FROM `asignaciondinero` WHERE idasignaciondinero>0  $conde1 ORDER BY $conde, $ord $asc";
	$DB1->Execute($sql);
	$asignaciones=array();
	$idasignaciones=array();
	$idtranspaso=array();
	$transpaso=array();
	$gastos=array();
	$descripcion=array();
	$asi_idValida=array();
    $idasigna=array();
	
	$esgasto="nogasto";//jose armando
	while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
	{


		if($rw1[5]=='Asignar Dinero' or ($rw1[5]=='Transpaso Dinero' and $rw1[7]==$param2)){
		$va++;

		$asignaciones[$va]=number_format($rw1[2],0,".",".");
		$idasignaciones[$va]=$rw1[0];
		$totalasignacion=$totalasignacion+$rw1[2];
		}else if($rw1[5]=='entregado') {
			$dineroentregado=number_format($rw1[2],0,".",".");

		}else if($rw1[5]=='Gastos') {
			$va2++;
			$gastos[$va2]=number_format($rw1[8],0,".",".");
			$descripcion[$va2]=$rw1[6];
			$totalgastos=$totalgastos+$rw1[8];
			$idasigna[$va2]=$rw1[0];
			$asi_idValida[$va2]=$rw1[9];//jose armando
			$asi_tabla=$rw1[10];//jose armando

		}
	}

	$sqlt="SELECT `idasignaciondinero`,`asi_fecha`,`asi_valor`, `asi_idautoriza`, `asi_idpromotor` ,asi_tipo,asi_descripcion,asi_usercom, asi_idvalidaf
	FROM `asignaciondinero` WHERE idasignaciondinero>0 and asi_tipo='Transpaso Dinero' $condet1 ORDER BY $conde, $ord $asc";
	$DB1->Execute($sqlt);
while($rw1=mysqli_fetch_row($DB1->Consulta_ID))
	{

			$va6++;
			$transpaso[$va6]=number_format($rw1[2],0,".",".");
			$idtranspaso[$va6]=$rw1[0];
			$totaltranspaso=$totaltranspaso+$rw1[2];


	}

	 $sql="SELECT `idgastos`, `gas_iduserremesa`,gas_usucom,gas_cantcom,gas_feccom ,gas_iduserrecoge,gas_fecrecogida,gas_idvalidaf FROM `gastos` WHERE idgastos>0  $conde11 ORDER BY idgastos";
	$DB1->Execute($sql);

	$remesas=array();
	$idremesas=array();
	$confirmaGasto=array();
	while($rw5=mysqli_fetch_row($DB1->Consulta_ID))
	{
		$va5++;
		$remesas[$va5]=number_format($rw5[3],0,".",".");
		$idremesas[$va5]=$rw5[0];
		$totalremesas=$totalremesas+$rw5[3];
		$confirmaGasto[$va5]=$rw5[7];
	}

}

$comprobados = array();
$descargado = array();

echo$sql1="SELECT `idcuentaspromotor`, `cue_validar`,
`cue_numeroguia`, `cue_tipoevento`, `cue_idciudadori`,
`cue_idciudaddes`, `cue_pordeclarado`,
`cue_valorflete`,`cue_porprestamo`, `cue_prestamo`,
`cue_idservicio`, `cue_idoperador`,  `cue_pordeclarado`,
`cue_vrdeclarado`, `cue_valorflete`,
`cue_abono`, `cue_tipopago`, `cue_fecha`, 
`cue_pendientecobrar`,cue_idoperentrega ,
cue_fecharecogida,ser_guiare,ser_valorpendiente,
cue_validado,cue_idservicio,cue_validadoentrega,
cue_idoperpendiente,cue_fechapcobrar,cue_estado,
cue_transferencia,ser_numerofactura,ser_pendientecobrar,
`ser_confimgrec`,`ser_idverificado`,ser_idverificadopeso,ser_estado 
FROM `cuentaspromotor`  
inner join servicios on cue_idservicio=idservicios   
WHERE  cue_estado<100 and cue_numeroguia='AR75739' $conde3 order by ser_guiare ASC ";

$DB->Execute($sql1);
$compras=array();
$recogidas=array();
$entregas=array();
$html1=array();
$totalprestamo=0;
$totalCancelar=0;
$totalrecogida=0;
$totalcompras=0;
$totalentrega=0;
$totalfantante=0;
$totalxcobrar=0;
$validador=0;
// $descargado="no descargado";

while($rw2=mysqli_fetch_row($DB->Consulta_ID))
{

	$va3++;
	$prestamo=$rw2[8]+$rw2[9];
	$recogida=0;
	$compras=0;
	$entregas=0;
	$entregas2=0;
	$id_p=$rw2[24];
	$numeroGuia=$rw2[2];
	$por=1;
	$rw2[20]=substr ($rw2[20], 0, -9);
	$rw2[17]=substr ($rw2[17], 0, -9);
	$impri=0;
	$xcobrar=0;
	$xcancelar=0;
	$xcancelar2=1;
    $rw2[27]=substr($rw2[27], 0, -9);

	$cue_idoperador=$rw2[11];
    $cue_idoperentrega=$rw2[19];
	



	$color4="";
	$color6="";
if($rw2[18]==1  and (($rw2[11]==$param2 and $rw2[20]==$fechaactual) or ($rw2[26]==$param2 and $rw2[27]==$fechaactual)))  // pendiente por cobrar asignado
{

 if($rw2[26]==$param2 ){
	$color4="style=background-color:green";
 }else{
	$color4="style=background-color:red";
 }

	if($rw2[16]=='Compra'){


				$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
				$compras=$rw2[9];

				//$prestamo=$rw2[8];
			}else {
				$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
				$compras=0;
			}

	$entregas=0;

	$entregas2=0;

	$recogida=0;
	$por=1;
	$impri=1;


}else if($rw2[18]==2 and $rw2[26]==$param2 and $rw2[27]==$fechaactual) // pendiente por cobrar cobrada $rw2[18]=cue_pendientecobrar, $rw2[26]== cue_idoperpendiente  $rw2[27]=cue_fechapcobrar
{

	$color4="";
	if($rw2[16]=='Compra'){


				$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
				$compras=$rw2[9];

				//$prestamo=$rw2[8];
			}else {
				$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
				$compras=0;
			}

	if($rw2[29]!='' and $rw2[29]!='Efectivo' and $rw2[29]!='Factura'){

		$xcobrar=0;
	}

	$totalxcobrar=$totalxcobrar+$xcobrar;

	$entregas=0;
	$entregas2=0;
	$recogida=0;
	$por=1;
	$impri=1;

}else if($rw2[18]==4 and $rw2[11]==$param2 and $rw2[20]==$fechaactual ){  //pendiente por cancelar

	if($rw2[16]=='Compra'){


		$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
		$xcancelar=$rw2[22];
		$compras=$rw2[9];
		
	}else {
		$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];
		$compras=0;
	}

}else if(($rw2[18]==5 or $rw2[18]==3)  and ($rw2[26]==$param2 and $rw2[27]==$fechaactual) ) //pendiente por cancelar
{

	 if($rw2[16]=='Compra'){

		if($rw2[18]==5){
			$color6="style=background-color:green";
			$xcancelar2=2;
			$xcancelar=$rw2[22];
		 }else{
			$color6="style=background-color:red";
			$xcancelar2=-1;
			$xcancelar=$rw2[22];
		 }
		 $por=1;
		 $impri=1;

	 }

}else {	//entra aqui cuando no es pendiente por cobrar o por cancelar.

if($rw2[20]==$fechaactual and $rw2[11]==$param2){  //recogida
	//echo "aquii",$rw2[21];
	$impri=1;
	if($rw2[3]=='1'){  // decontado

		if($rw2[11]==$param2){    //recogida

			if($rw2[16]=='Compra'){

				$compras=$rw2[9];
				//$rw2[15]=0;
				$prestamo=0;
				$por=-1;
				$recogida=$rw2[6]+$rw2[7];
				if($rw2[18]==5 or $rw2[18]==3){
					$xcancelar=$rw2[22];
				}
			}else {
				$por=-1;
				$recogida=$rw2[6]+$rw2[7];

			}

		}	else if($rw2[19]==$param2){ //entrega


			if($rw2[16]=='Compra'){

				$rw2[15]=0;
				$prestamo=0;
				$recogida=$rw2[6]+$rw2[7];

			}else {

					$entregas=0;
					$compras=0;
					$recogida=0;
					$por=1;
			}

		}

	}else if($rw2[3]=='2'){  //credito

		if($rw2[11]==$param2){
			if($rw2[16]=='Compra'){

					$compras=$rw2[9];
					$rw2[15]=0;
					$prestamo=0;
					if($rw2[18]==5 or $rw2[18]==3){
						$xcancelar=$rw2[22];
					}

				}else {
					$rw2[15]=0;
					$prestamo=0;
				}
		} else 	 if($rw2[19]==$param2){

			$compras=0;
		}

	}else if($rw2[3]=='3'){  // al cobro 	echo "cobro",$rw2[21];

			if($rw2[16]=='Compra'){

					$compras=$rw2[9];
					$rw2[15]=0;
					$prestamo=0;
					$entregas2=$rw2[6]+$rw2[7];
					if($rw2[18]==5 or $rw2[18]==3){
						$xcancelar=$rw2[22];
					}

			}else if($rw2[16]=='Envio Oficina') {
				$por=-1;
				
				$entregas2=$rw2[6]+$rw2[7];

			}else {
					$por=-1;
					
					$entregas2=$rw2[6]+$rw2[7];
			}

     	}

	}


	if($rw2[17]==$fechaactual and $rw2[19]==$param2){  

		$impri=1;


		if($rw2[3]=='1'){  // decontado

				if($rw2[16]=='Compra'){



				}else {

						$entregas=0;

						$compras=0;
						$recogida=0;
						$por=1;
				}

		} else if($rw2[3]=='2'){  //credito

								$compras=0;

		}else if($rw2[3]=='3'){  // al cobro

			if($rw2[15]>0 and $rw2[9]==0){  //$rw2[15]=cue_abono  $rw2[9]=cue_prestamo
				$entregas2=$rw2[6]+$rw2[7];
			}else {
				$entregas=$rw2[6]+$rw2[7];
			}



		}

	}
}

 if($rw2[18]==2 and $rw2[3]!='2'){ //pendientepor cobrar, cobrada y diferentes a credito
	$recogida=0;
	$xcobrar=$rw2[6]+$rw2[7]-$rw2[15];


}

	$guiare=$rw2[21];

	if($entregas2!=0){ //entregas2 recogidas o entrega con abono y sin prestano
		$entregas3=$entregas2;
		$color2="style=background-color:red";
	}else if($rw2[28]==11){  // $rw2[28]= estado 11=No Entregado
		$entregas3=$entregas;
		$entregas=0;
		$color2="style=background-color:red";
	}
	else{
		$entregas3=$entregas;
		$color2="";
	}


	if($impri==1){
	
		//Verificar guias no Pesadas/descargadas 
		if (($rw2[34] == "" || $rw2[34] == 0) and $rw2[35]<=6) {
			$descargado[] = [
				"estado" => "no descargado",
				"numeroGuia" => $numeroGuia
			];
		} else {
			$descargado[] = [
				"estado" => "0",
				"numeroGuia" => $numeroGuia
			];
		}


		if($prestamo<=0 and $rw2[15]>=1 and $rw2[3]=='1' and $rw2[19]==$param2){ //$rw2[15] =abono

			$faltante=$recogida+$entregas3-$rw2[15];

		}else if($prestamo<=0){
			$faltante=0;
		}
		else {
			$faltante=$prestamo-($rw2[15]*$por);

		}

		if($rw2[15]>$prestamo and $rw2[19]==$param2){  //$rw2[19]=entregas
			$faltante=$prestamo-($rw2[15]*$por)+$recogida+$entregas3;
			$entregas=0;
		}

			// <td>".$rw2[1]."</td>
			$validador++;

			if($rw2[19]==$param2){ //$rw2[19]=entregas
			$selec= "<td><div id='campo$validador'>"; if($rw2[25]==1){ $st="SI"; $colorfondo="#074f91"; } else { $st="NO"; $colorfondo="#941727"; }
			$selec.=  "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'  name='param14' id='param14'  onChange='cambio_ajax2(this.value, 74, \"campo$validador\", \"$validador\", 1, $id_p); checkSelects();' class='borrar'  required>";
			$selec.= $LT->llenaselect_ac($st,$estados);
			$selec.=  "</select></div><input name='servicio_$validador' id='servicio_$validador' type='hidden'  value='$rw2[25]'></td>";

			}else {

			$selec= "<td><div id='campo$validador'>"; if($rw2[23]==1){ $st="SI"; $colorfondo="#074f91"; } else { $st="NO"; $colorfondo="#941727"; }
			$selec.=  "<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:18px'  name='param14' id='param14'  onChange='cambio_ajax2(this.value, 73, \"campo$validador\", \"$validador\", 1, $id_p); checkSelects();' class='borrar' required>";
			$selec.= $LT->llenaselect_ac($st,$estados);
			$selec.=  "</select></div><input name='servicio_$validador' id='servicio_$validador' type='hidden'  value='$rw2[23]'></td>";

			}
			if($rw2[28]==3){
				$selec= "<td><div id='campo$validador'>SIN RECOGER</td>";
				$recoger=1;
			}elseif($rw2[28]==9){
				$selec= "<td><div id='campo$validador'>SIN ENTREGAR</td>";
				$recoger=1;
			}

			if($faltante<0){
				$color3="style=background-color:red";
				$faltante=0;
			}else{
				$color3="";
			}

			if($rw2[18]==3 or ($rw2[18]==5 and $rw2[26]==$param2 )){
				$faltante=0;
			}
			$color7="";
			$recogida2=$recogida;
			if($rw2[29]!='' and $rw2[29]!='Efectivo' and $rw2[31]!=6){

				$sql = "Select pag_userverifica from pagoscuentas where pag_idservicio='$id_p'";
				$DB1->Execute($sql);
				$verificado = $DB1->recogedato(0);
				if($verificado ==''){
					$Pagotrans=$rw2[29]."<br>"."Sin Verificar";
					$color7="style=background-color:yellow";
				}else{
				$Pagotrans=$rw2[29]."<br>"."Verificado";

				}

				$recogida='';
				$entregas='';
				$xcobrar=0;
				$entregas3=$entregas3+$faltante;
				$faltante=0;
			}elseif($rw2[31]==6){

				$recogida='';
				$entregas='';
				$xcobrar=0;
				$entregas3=$entregas3+$faltante;
				$faltante=0;

				$Pagotrans="Pago con Factura Externa"
				."<br> #".$rw2[30];

			}else{

				$Pagotrans='Efectivo';



			}
			$cue_idoperador=$rw2[11];
			$cue_idoperentrega=$rw2[19];
			// echo"/".$rw2[32]."".$guiare."/";
			// echo$rw2[33];
			$fotoen="";
			$confirma="";
			$contfoto="";
			$fotorec="";
			if($cue_idoperador==$param2){
				if ($rw2[32]=="si") {
					$confirma="✅";
				}else {
					$confirma="no";
				}
				$sqlrecogida="SELECT ima_ruta,ima_tipo,idimagenguias,ima_fecha,ima_dir from imagenguias where ima_tipo ='Recogida' and  ima_idservicio=$id_p ";
				$DB1->Execute($sqlrecogida); 
				
				$guiasi=mysqli_fetch_row($DB1->Consulta_ID);
				$idee=$guiare."_Recogida";
				$fechaent=$guiasi[3];
				$fotorec=$guiasi[0];
						if ($fotorec==""){
							$comprobados[] = "Subir Foto Guia";
							$contfoto="<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"$idee\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia</a></td>";


						}else{
							if ($fechaent<"2024-05-30") {
								$colorfoto="";
								echo"<a href='https://b9e4-190-25-33-50.ngrok-free.app/SistemaTransmillas/$recogidag' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
								
							}elseif ($fechaent<="2025-02-13") {
								$colorfoto="";
							$contfoto="<a href='https://sistema.transmillas.com/editarImg.php?img=".$fotorec."&guia=".$guiare."&idser=".$id_p."' target='_blank'>Ver✏️$confirma</a><br>-------<br><a href='del_admin.php?id_param=$guiasi[2]&tabla=Elimina Archivo2&ruta=$fotorec' title='Eliminar' 
							onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'><i class='fa fa-trash-o'></i></a>";	
							}else{
								$colorfoto="";
								
								$contfoto="<a href='".$fotorec."&vis=adm' target='_blank'>Ver/a><br>-------<br><a href='del_admin.php?id_param=$guiasi[2]&tabla=Elimina Archivo2&ruta=$fotorec' title='Eliminar' 
								onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'><i class='fa fa-trash-o'></i></a>";
							}

							$comprobados[] = "0";
	
						}
				}elseif ($cue_idoperentrega==$param2){

					if ($rw2[33]=="si") {
						$confirma="✅";
					}else {
						$confirma="no";
					}
					$sqlrecogida="SELECT ima_ruta,ima_tipo,idimagenguias,ima_fecha,ima_dir from imagenguias where ima_tipo = 'Entrega' and  ima_idservicio=$id_p ";
					$DB1->Execute($sqlrecogida); 
					$guiasi=mysqli_fetch_row($DB1->Consulta_ID);
					$fechaent=$guiasi[3];
					$idee=$guiare."_Entrega";
							$fotorec=$guiasi[0];
							if ($fotorec==""){

							    $comprobados[] = "Subir Foto Guia";
								$contfoto="<a  onclick='pop_dis16($id_p,\"Fotoguia\",\"$idee\")';  style='cursor: pointer;' title='Foto guia' >Subir Foto Guia</a></td>";
							}else{

								if ($fechaent<"2024-05-30") {
									$colorfoto="";
									echo"<a href='https://b9e4-190-25-33-50.ngrok-free.app/SistemaTransmillas/$recogidag' target='_blank'>&nbsp;<i class='fa fa-camera-retro fa-lg'></i>&nbsp;Ver Foto Guia </a>";
									
								}elseif ($fechaent<="2025-02-13") {
									$colorfoto="";
									$contfoto="<a href='https://sistema.transmillas.com/editarImg.php?img=".$fotorec."&guia=".$guiare."&idser=".$id_p."' target='_blank'>Ver✏️ $confirma</a><br>-------<br><a href='del_admin.php?id_param=$guiasi[2]&tabla=Elimina Archivo2&ruta=$fotorec' title='Eliminar' 
									onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'><i class='fa fa-trash-o'></i></a>";		
								}else{
									$colorfoto="";
									
									$contfoto="<a href='".$fotorec."&vis=adm' target='_blank'>Ver/a><br>-------<br><a href='del_admin.php?id_param=$guiasi[2]&tabla=Elimina Archivo2&ruta=$fotorec' title='Eliminar' 
									onClick='return confirm(\"".utf8_encode("Est&aacute; seguro de eliminar este registro?")."\")'><i class='fa fa-trash-o'></i></a>";
								}

							    
	
							}
		
				}







			$html1[$va3]=$selec."
			<td align='center' ><a  onclick='pop_dis5($id_p,\"Recogidas\")';  style='cursor: pointer;' title='Detalle Guia' >$rw2[2]</td>
			<td> ".$guiare."</td>
			
			<td style='text-align:center;' >$contfoto</td>
			

			<td $color7> ".$Pagotrans."</td>
			<td $color4 > ".$xcobrar."</td>
			<td $color6 > ".$xcancelar."</td>
			<td>$ ".$recogida2."</td>
			<td>$ ".$compras."</td>
			<td $color2 >$ ".$entregas3." es al cobro $entregas3 + $faltante</td>
			<td>$ ".$prestamo."</td>
			<td>$ ".$rw2[15]."</td>
			<td  $color3 >$ ".$faltante."</td>

			";
			if($xcancelar2==2){
				$xcancelar=0;
				$xcancelar2=1;
			}
			$xcancelar=str_replace(".","", $xcancelar);
			$totalprestamo=$prestamo+$totalprestamo;
			$totalrecogida=$recogida+$totalrecogida;
			$totalcompras=$compras+$totalcompras;
			$totalentrega=$entregas+$totalentrega;


	$totalfantante=$faltante+$totalfantante;



		$totalCancelar=($xcancelar*$xcancelar2)+$totalCancelar;
		$impri=0;
	} else {
		$va3--;
	}
}


if($va3==0){

		 $html1[1]= "
		<td>0</td>
		<td>0</td>
		<td>0</td>
		<td></td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		<td>$ 0 </td>
		";
}

		 $html1[0]= "
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>
		<td>---</td>

		";

 $mayor=max($va, $va2, $va3, $va5);
$sql5="SELECT `roles_idroles`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$param2'";
$DB->Execute($sql5);
$idrol=$DB->recogedato(0);
$nompromotor=$DB->recogedato(1);
$va4=0;

for($a=1;$a<=$mayor;$a++){


	$va4++; $p=$va4%2;
	if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}

	echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
	if($va3==0){ $html1[$va4]=$html1[1]; }
	if($html1[$va4]==""){ $html1[$va4]=$html1[0]; }


//remesas
$descripremesa=$idremesas[$va4]."_gastos";
	if ($remesas[$va4]!="") {
		$verselectrem="";

		if($confirmaGasto[$va4]!=""){
			$colorfondorem="#074f91"; $sirem="selected"; $norem="";
			
			$verselectrem="<td>
			<div id='campo$idremesas[$va4]'>
			<select  style='$verselectrem width:120px;border:1px solid #f9f9f9;background-color:$colorfondorem;color:#f9f9f9;font-size:15px'  name='$idremesas[$va4]' id='$idremesas[$va4]'   onChange='cambio_ajax2(this.value,802, \"campo".$idremesas[$va4]."\", \"$idremesas[$va4]\", 1, \"$descripremesa\" ); checkSelects();'    class='borrar' required>
			<option value='NO' $norem>NO</option>
			<option value='SI' $sirem>SI</option>
			</select></td>
			</td>";
		} else {
			$colorfondorem="#941727"; $norem="selected"; $sirem="";
			
			$verselectrem="<td>
			<div id='campo$idremesas[$va4]'>
			<select  style='$verselectrem width:120px;border:1px solid #f9f9f9;background-color:$colorfondorem;color:#f9f9f9;font-size:15px'  name='$idremesas[$va4]' id='$idremesas[$va4]'   onChange='cambio_ajax2(this.value,802, \"campo".$idremesas[$va4]."\", \"$idremesas[$va4]\", 1, \"$descripremesa\" ); checkSelects();'    class='borrar' required>
			<option value='NO' $norem>NO</option>
			<option value='SI' $sirem>SI</option>
			</select></td>
			</td>";
		}
	}else{
		
		$verselectrem="<td></td>";
		

	}
//gastos
$descripgasto=  $idasigna[$va4]."_$asi_tabla";
	if ($gastos[$va4]!="") {
		$verselect="";

		if($asi_idValida[$va4]!=""){
			$colorfondo="#074f91"; $si="selected"; $no="";
			$verselect="<td>
			<div id='campo$idasigna[$va4]'>
			<select  style='$verselect width:120px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:15px'  name='".$idasigna[$va4]."' id='".$idasigna[$va4]."'   onChange='cambio_ajax2(this.value,802, \"campo".$idasigna[$va4]."\", \"".$idasigna[$va4]."\", 1, \"$descripgasto\" ); checkSelects();'    class='borrar' required>
			<option value='NO' $no>NO</option>
			<option value='SI' $si>SI</option>
			</select></div>
			</td>";
		} else {
			$colorfondo="#941727"; $no="selected"; $si="";
			$verselect="<td>
			<div id='campo$idasigna[$va4]'>
			<select  style='$verselect width:120px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:15px'  name='".$idasigna[$va4]."' id='".$idasigna[$va4]."'   onChange='cambio_ajax2(this.value,802, \"campo".$idasigna[$va4]."\", \"".$idasigna[$va4]."\", 1, \"$descripgasto\" ); checkSelects();'    class='borrar' required>
			<option value='NO' $no>NO</option>
			<option value='SI' $si>SI</option>
			</select></div>
			</td>";
		}
	}else{
		$verselect="<td></td>";

	}

	echo $html1[$va4]."<td align='center' ><a  onclick='pop_dis5($idremesas[$va4],\"remesas\")';  style='cursor: pointer;' title='Remesas' >$ ".@$remesas[$va4]."</td>";
	$LT->llenadocs2($DB, "gastos", $idremesas[$va4], 2, 35, 'Historico');
	



		echo"<td align='center' ><a  onclick='pop_dis5($idtranspaso[$va4],\"asignar dinero\")';  style='cursor: pointer;' title='transpasos' >$ ".@$transpaso[$va4]."</td>
		<td align='center' ><a  onclick='pop_dis5($idasignaciones[$va4],\"asignar dinero\")';  style='cursor: pointer;' title='Compras' >$ ".@$asignaciones[$va4]."</td>
		<td>$ ".@$gastos[$va4]."</td>";
		$LT->llenadocs2($DB1, "asignaciondinero", $idasigna[$va4], 1, 35, 1);
		
		echo$verselect;
		echo"<td> ".@$descripcion[$va4]."</td>

		";
	echo "</tr>";

}

	$totalaentregar=$totalrecogida-$totalcompras+$totalCancelar+$totalentrega+$totalfantante+$totalasignacion-$totalgastos-$totalremesas+$totalxcobrar-$totaltranspaso;
	$totalasignacion=number_format($totalasignacion,0,".",".");
	$totalrecogida=number_format($totalrecogida,0,".",".");
	$totalcompras=number_format($totalcompras,0,".",".");
	$totalentrega=number_format($totalentrega,0,".",".");
	$totalgastos=number_format($totalgastos,0,".",".");
	$totalremesas=number_format($totalremesas,0,".",".");
	$totaltranspaso=number_format($totaltranspaso,0,".",".");


	$totalfantante=number_format($totalfantante,0,".",".");
	$totalCancelar=number_format($totalCancelar,0,".",".");
	$totalxcobrar=number_format($totalxcobrar,0,".",".");




$FB->titulo_azul1(" $va3",1,0,10);
$FB->titulo_azul1(" $va3",1,0,0);



$FB->titulo_azul1(" $va3",1,0,0);
$FB->titulo_azul1("______________ ",1,0,0);

$FB->titulo_azul1("______________ ",1,0,0);
$FB->titulo_azul1("$ $totalxcobrar",1,0,0);
$FB->titulo_azul1("$ $totalCancelar",1,0,0);
$FB->titulo_azul1("$ $totalrecogida",1,0,0);
$FB->titulo_azul1("$ $totalcompras",1,0,0);
$FB->titulo_azul1("$ $totalentrega",1,0,0);
$FB->titulo_azul1("______________ ",1,0,0);
$FB->titulo_azul1("______________ ",1,0,0);
$FB->titulo_azul1("$ $totalfantante",1,0,0);
$FB->titulo_azul1("$ $totalremesas",1,0,0);
$FB->titulo_azul1("___________________",1,0,0);
$FB->titulo_azul1("___________________",1,0,0);
$FB->titulo_azul1("$ $totaltranspaso",1,0,0);
$FB->titulo_azul1("$ $totalasignacion",1,0,0);
$FB->titulo_azul1("$ $totalgastos",1,0,0);
$FB->titulo_azul1("___________________",1,0,0);
$FB->titulo_azul1("___________________",1,0,0);

  $slqs="SELECT sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idpromotor='$param2' and deu_tipo='Prestamos' order by `deu_fecha` ";
$DB1->Execute($slqs);
$prestamos=$DB1->recogedato(0);

$slqs2="SELECT sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idpromotor='$param2' and deu_tipo='Descuadre' order by `deu_fecha` ";
$DB1->Execute($slqs2);
$descuadredia=$DB1->recogedato(0);

$slqs6="SELECT sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idpromotor='$param2' and deu_tipo='MalEnviados' order by `deu_fecha` ";
$DB1->Execute($slqs6);
$malEnviadas=$DB1->recogedato(0);

  $slq3="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`<'$param4' and deu_idpromotor='$param2' and deu_tipo in ('Prestamos','Descuadre','MalEnviados')";
$DB1->Execute($slq3);
 $prestamostotal=$DB1->recogedato(0);




  $slq4="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`<'$param4' and deu_idpromotor='$param2' and deu_tipo='Pagos'";
$DB1->Execute($slq4);
 $pagostoral=$DB1->recogedato(0);

$saldopendiente=$prestamostotal-$pagostoral;


if($param2!=''){



$slq12="SELECT sum(abo_valor) FROM `abonosguias` WHERE abo_iduser='$param2' and abo_fecha like '$param4%' and `abo_estado`='abono'";
 $DB->Execute($slq12);
 $valorabono=$DB->recogedato(0);



$FB->titulo_azul1("VALOR DE  ",1,0,10);
$FB->titulo_azul1(" LOS ",1,0,0);
$FB->titulo_azul1("ABONOS",1,0,0);
$FB->titulo_azul1("____ ",1,0,0);
$FB->titulo_azul1(" ______",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
//$FB->titulo_azul1("$ $valorabono",1,0,0);

echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param2,\"Abonoscuentas\",\"$param4\")';  style='cursor: pointer;' title='Detalle Abonos' >$ $valorabono</td>";

 $sql="SELECT sum(abo_valor) FROM `abonosguias` WHERE abo_iduser='$param2' and abo_fecha like '$param4%' and `abo_estado`='devolucion'";
$DB->Execute($sql);
$devoluciong=$DB->recogedato(0);


$FB->titulo_azul1("VALOR DE  ",1,0,10);
$FB->titulo_azul1(" LAS",1,0,0);
$FB->titulo_azul1("DEVOLUCIONES",1,0,0);
$FB->titulo_azul1("____ ",1,0,0);
$FB->titulo_azul1(" ______",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
//$FB->titulo_azul1("$ $valorabono",1,0,0);

echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis6($param2,\"Devolucionescuentas\",\"$param4\")';  style='cursor: pointer;' title='Detalle Abonos' >$ $devoluciong</td>";
}

if($idrol!=3 and $param2!=''){

 $slq8="SELECT  sum(`asi_valor`) FROM `asignaciondinero` WHERE `asi_fecha`='$param4' and asi_idautoriza='$param2' and asi_tipo='entregado' and asi_idpromotor>0";
$DB1->Execute($slq8);
 $dinerorecogido=$DB1->recogedato(0);

$FB->titulo_azul1("VALOR DE  ",1,0,10);
$FB->titulo_azul1(" DINERO ",1,0,0);
$FB->titulo_azul1("RECOGIDO",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("$ $dinerorecogido",1,0,0);

if($idrol==5){
	$totalaentregar=$totalaentregar+$valorabono;
}else{
	$totalaentregar=$totalaentregar+$valorabono+$dinerorecogido;
}

}
if($idrol==3){
	$totalaentregar=$totalaentregar+$valorabono;
}
$slq4="SELECT  sum(`deu_valor`) FROM `duedapromotor` WHERE `deu_fecha`='$param4' and deu_idpromotor='$param2' and deu_tipo='Pagos'";
$DB1->Execute($slq4);
 $pagosdia=$DB1->recogedato(0);

  $valorenviar=$prestamos+$saldopendiente-$pagosdia+$descuadredia;
  $totalaentregar=$totalaentregar-$devoluciong;
  $totalentregasinformatear=$totalaentregar-$devoluciong;//para mostrar en entregar valor
$totalaentregar=number_format($totalaentregar,0,".",".");

$FB->titulo_azul1("VALOR TOTAL",1,0,10);
$FB->titulo_azul1("A",1,0,0);
$FB->titulo_azul1("ENTREGAR:",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("$ $totalaentregar",1,0,0);

$FB->titulo_azul1("",1,0,1);
$fechaa=date('Y-m-d');
$kilome="SELECT `pre_img_kilo_sal`, `pre_kilom_sal` FROM `pre-operacional`  WHERE 	prefechaingreso like '%$fechaa%' and preidusuario='$param2'";
$DB1->Execute($kilome);
$kil=mysqli_fetch_row($DB1->Consulta_ID); 

if ($kil[0]=="" and date('h:m:s')>="05:00:00") {
	
	echo"<script>mostrarVentana(0,0);</script>";
}else if ($kil[0]!="" and date('h:m:s')>="05:00:00") {
	
	echo"<script>mostrarVentana(\"$kil[0]\",\"$kil[1]\");</script>";
}

$sqlcrediec="SELECT `idrelsercre`, `idservicio`, `rel_nom_credito` FROM `rel_sercre` WHERE  idservicio=$id_p ";
$DB1->Execute($sqlcrediec); 

$credinom=mysqli_fetch_row($DB1->Consulta_ID);

$sqlrem="SELECT `idgastos`, `gas_iduserremesa`,gas_usucom,gas_cantcom,gas_feccom ,gas_iduserrecoge,gas_fecrecogida,gas_idvalidaf FROM `gastos` WHERE idgastos>0 and gas_iduserrecoge='$param2'  and gas_recogio<=0 and gas_fecharegistro >= '2025-05-09 00:00:00' ORDER BY idgastos";
$DB1->Execute($sqlrem);
while($Rem=mysqli_fetch_row($DB1->Consulta_ID))
{ 

	$remesasPendientes++;
}


$valorABuscar = "Subir Foto Guia";

if($recoger==0 or $id_usuario==2){

		
		$estados = array_column($descargado, "estado");
        
		if (in_array("no descargado", $estados)) {
			$FB->titulo_azul1("EL Operador aún tiene GUIAS SIN DESCARGAR por favor  ¡Valide el PESO!",1,0,0);
		   
		}
		elseif($remesasPendientes>0){
			$FB->titulo_azul1("EL Operador aún tiene REMESAS PENDIENTES por favor  Verifique!",1,0,0);
		}
		else{
			//  no efste en el array.";
			$FB->titulo_azul1("Entregar",1,0,0);
			$FB->titulo_azul1("saldo",1,0,0);
			$FB->titulo_azul1("dia",1,0,0);
			$FB->titulo_azul1("<a onclick='pop_dis225($param2,\"Entregar valor\",$id_sedes,$valorenviar,$totalentregasinformatear)';  style='cursor: pointer;' title='Entregar valor' id='entregad' ><img src='img/usar.png'></a>", 1,0,0);
		}

}else{
	$FB->titulo_azul1("EL Operador aún tiene GUIAS Asignadas POR RECOGER O ENTREGAR Verifique!",1,0,0);
}

$prestamos=number_format($prestamos,0,".",".");
$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("PRESTAMOS",1,0,0);
$FB->titulo_azul1("Dia:",1,0,0);
$FB->titulo_azul1("$ $prestamos",1,0,0);

$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("DESCUADRES",1,0,0);
$FB->titulo_azul1("DIA:",1,0,0);
$FB->titulo_azul1("$ $descuadredia",1,0,0);

$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("MAl ENVIADAS",1,0,0);
$FB->titulo_azul1("DIA:",1,0,0);
$FB->titulo_azul1("$ $malEnviadas",1,0,0);

$pagosdia=number_format($pagosdia,0,".",".");
$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("ABONO",1,0,0);
$FB->titulo_azul1("DEL ",1,0,0);
$FB->titulo_azul1("PROMOTOR ",1,0,0);
$FB->titulo_azul1("Dia:",1,0,0);
$FB->titulo_azul1("$ $pagosdia",1,0,0);

$saldopendiente=number_format($saldopendiente,0,".",".");


$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("SALDO",1,0,0);
$FB->titulo_azul1("PENDIENTE:",1,0,0);
$FB->titulo_azul1("$ $saldopendiente",1,0,0);



$slq22="SELECT sum(asi_valor) FROM `asignaciondinero` WHERE `asi_fecha`='$param4' and `asi_idpromotor`='$param2' and asi_tipo='entregado'";
$DB1->Execute($slq22);
 $dineroentregado=$DB1->recogedato(0);
//S $totaldeuda=$valorenviar-$dineroentregado;
 $totaldeuda=$valorenviar;

$dineroentregado=number_format($dineroentregado,0,".",".");
$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("VALOR",1,0,0);
$FB->titulo_azul1("ENTREGADO:",1,0,0);
echo "<td colspan='1' width='0' align='center' ><a id='dinero' onclick='pop_dis6($param2,\"dineroentregado\",\"$param4\")';  style='cursor: pointer;' title='Dinero Entregado' >$ $dineroentregado</td>";

//$FB->titulo_azul1("$ $dineroentregado",1,0,0);


$totaldeuda=number_format($totaldeuda,0,".",".");
$FB->titulo_azul1("_________",1,0,10);
$FB->titulo_azul1("_________",1,0,0);
$FB->titulo_azul1("TOTAL",1,0,0);
$FB->titulo_azul1("DEUDA ",1,0,0);
$FB->titulo_azul1("PROMOTOR:",1,0,0);
$FB->titulo_azul1("",1,0,0);
$FB->titulo_azul1("$ $totaldeuda",1,0,0);



if ($remesasPendientes>0) {
	echo '<div class="alert alert-danger" role="alert">
			¡Este operador tiene '.$remesasPendientes.' Remesas Pendientes NO puede entregar cuentas!
		  </div>';
}


echo'<button id="myButton" style="display: none;">Submit</button>';

	$estados = array_column($descargado, "estado");
        
	if (in_array("no descargado", $estados)) {
		// Tu contador ya lo tienes hecho antes
		echo '<div class="alert alert-danger" role="alert">';
		echo '¡Hay '.$contsinpesar.' guías sin pesar, verifique!<br>';

		// Recorremos el array $descargado
		foreach ($descargado as $guia) {
			if ($guia['estado'] === "no descargado") {
				echo "Guía N°: " . $guia['numeroGuia'] . "<br>";
			}
		}

		echo '</div>';
	}
	

include("footer.php"); ?>



<script>
	

  checkSelects();

  

	
		//  function verifica(valoract,valores){
		// const numberInput = valoract;
		// const referenceNumber = valoract;
		// const warning = document.getElementById('warning');
		// const minLength = 1; 
		
		// 	const value = numberInput.value;
	
		// 	if (value.length >= minLength) {
		// 		const numericValue = parseFloat(value);
	
		// 		if (!isNaN(numericValue) && numericValue < referenceNumber) {
		// 			// warning.style.display = 'block';
		// 			alert("El valor no puede ser menor al actual ");
		// 			numberInput.value=referenceNumber;
		// 		} else {
		// 			// warning.style.display = 'none';
		// 		}
		// 	} else {
		// 		// warning.style.display = 'none';
		// 	}
		
		
		
		// }
		function verifica(input, minValue) {
            const enteredValue = parseFloat(input.value); // Valor ingresado por el usuario

            // Comparar los valores
            if (enteredValue < minValue) {
                document.getElementById('error-message').style.display = 'block';
                input.value = minValue; // Revertir al valor mínimo si es necesario
            } else {
                document.getElementById('error-message').style.display = 'none';
            }
        }
	

	


</script>
