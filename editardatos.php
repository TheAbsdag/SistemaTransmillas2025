<script>

/* function validarguia()
{

var valorguia= document.getElementById("param34").value;
	var guia="";
	var trueorfalse = false;	
		datos = {"vlores":valorguia,"tipo":"1","idguia":"0"};
		$.ajax({
				url: "validarguia.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(result) {
					guia= result.ser_guiare;
					//alert("EL NUMERO DE GUIA "+guia+" YA EXISTE");
					if(guia!=''){
					trueorfalse=false;
					}else {
						trueorfalse=true;
					}
				}
			});
			
			if(trueorfalse==false){
				$("#enviarmensaje").modal("show"); 
				var divvalor= document.getElementById("mensajevalor2");
				divvalor.innerHTML="<strong>Atencion!</strong> EL NUMERO DE GUIA YA EXISTE: "+guia+" VERIFIQUE!</a>";
				return false;
		
			}else {
				return trueorfalse;
			}
} */

function validarguia()
{
	var valorguia= document.getElementById("param34").value;
	var precio2= document.getElementById("precio").value;
	var credito=document.getElementsByName("param28");
	var memory=credito[1].checked;

	var guia="";
	var trueorfalse;	

			if(precio2==0 && memory==false){ 	
				$("#enviarmensaje").modal("show"); 
							var divvalor= document.getElementById("mensajevalor2");
							divvalor.innerHTML="<strong>Atencion!</strong> NO HAY PRECIOS CONFIGURADOS PARA EL ENVIO DE ESTAS CIUDADES </BR> COMUNIQUESE CON EL ADMINISTRADOR GRACIAS!</a>";
							return false;
			}else if(memory==true){
				var nomcredito=document.getElementById("param113").value;
				var tipocredito=document.getElementById("param37").value;
				var ciudadori=document.getElementById("param4").value;
				var ciudaddes=document.getElementById("param11").value;

				datos = {"tipoguia":"validaprecios","nomcredito":nomcredito,"tipocredito":tipocredito,"ciudadori":ciudadori,"ciudaddes":ciudaddes};
				$.ajax({
					url: "guiasok.php",
					type: "POST",
					data: datos,
					async: false,
					success: function(respuesta) {				
						if(respuesta==2){
								trueorfalse=2;
						}else{
								trueorfalse=3;
							}
					}
				});	
			}	

		 if(trueorfalse==2){
				$("#enviarmensaje").modal("show"); 
					var divvalor= document.getElementById("mensajevalor2");
					divvalor.innerHTML="<strong>Atencion!</strong> NO HAY PRECIOS DE CREDITOS CONFIGURADOS PARA ESTAS OPCIONES. </BR> COMUNIQUESE CON EL ADMINISTRADOR GRACIAS!</a>";
					return false;
			}
			else {				
				//des.enviar.disabled=true;
				return true;
			}
			return false;
}

timer =0;
function testtimeout(nombres){
clearTimeout(timer);
imer =setTimeout(buscar(nombres),2000);
}

 function cambiar() {
	// alert(document.getElementById("param16").value);
	  if(document.getElementById("param16").value==''){
		 document.getElementById("param17").required=false; 
	  }
	 else {
		 document.getElementById("param17").required=true;
	 } 

 }

 function buscar(nombre) {

 if(nombre=='param1'){
	var documento = $("input#param1").val();
	variable = documento.length; 
	var telefono = $("input#param2").val();
	variable2 = telefono.length; 

	if (variable >= 7) {
		datos = {"vlores":documento,"tipo":"documento"};

		$.ajax({
				url: "buscarclientes.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

				if (respuesta === null) {

					//console.log(JSON.stringify(respuesta));
					//document. getElementById("param1").value='';
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1, '');
					document. getElementById("id_paramvalorguia441").value=0;
					document. getElementById("param2").value='';
					document. getElementById("param3").value='';
					document. getElementById("param4").value='';
					document.getElementById("param5").value='0';
					document.getElementById("param51").value='';
					document.getElementById("param19").value='0';
					document.getElementById("param20").value='';
					document.getElementById("param23").value='';
					document.getElementById("param6").value='';
					document.getElementById("0").checked = true;
					
					document. getElementById("id_param").value='0';		
				}
				else {
					cambio_ajax2(documento, 14, 'clientesdir', 'documento', 1, respuesta.cli_nombre);	
					//document. getElementById("param1").value=respuesta.cli_iddocumento;
					document. getElementById("param2").value=respuesta.cli_telefono;
					document. getElementById("param3").value=respuesta.cli_email;
					document. getElementById("param4").value=respuesta.cli_idciudad	
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document.getElementById("param5").value=res[0];
					document.getElementById("param51").value=res[1];
					document.getElementById("param19").value=res[2];
					document.getElementById("param20").value=res[3];
					document.getElementById("param23").value=res[4];					
					document. getElementById("param6").value=respuesta.cli_nombre;
				//	document. getElementById(respuesta.cli_clasificacion).checked = true;
					document. getElementById("id_param0").value=respuesta.idclientesdir;					
					document. getElementById("id_param").value=respuesta.idclientes;
					document. getElementById("id_param1").value=1;
					 //document.getElementById('id_del_input').checked  document.form1.param7[7].value

				}

			});			

	}	

 } else  if(nombre=='param2'){

	 var telefono = $("input#param2").val();
	 variable2 = telefono.length; 

	if (variable2 >= 7) {
		datos = {"vlores":telefono,"tipo":"telefono"};
		$.ajax({
				url: "buscarclientes.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

				if (respuesta === null) {				
					if(document. getElementById("id_param1").value!=1){
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1,'');
					}

					document.getElementById("id_param").value='0';
					document.getElementById("param3").value='';
					document.getElementById("param6").value='';
					document.getElementById("param4").value='';
					document.getElementById("param5").value=0;
					document.getElementById("param51").value='';
					document.getElementById("0").checked = true;
					//document.getElementById("param7").checked = false; 
					document.getElementById("param19").value=0; 
					document.getElementById("param20").value=''; 
					document.getElementById("param23").value=''; 
				}

				else {
					//document. getElementById("param1").value=respuesta.cli_iddocumento;
					//document. getElementById("param2").value=respuesta.cli_telefono;
					cambio_ajax2(documento, 14, 'clientesdir', 'telefono', 1, respuesta.cli_nombre);
					document. getElementById("param3").value=respuesta.cli_email;
					document. getElementById("param4").value=respuesta.cli_idciudad;
					 var res = respuesta.cli_direccion.split("&");
					 if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param5").value=res[0];
					document. getElementById("param51").value=res[1];
					document. getElementById("param19").value=res[2];
					document. getElementById("param20").value=res[3];
					document. getElementById("param23").value=res[4]; 
					//document. getElementById("param6").value=respuesta.cli_nombre;
					//document. getElementById(respuesta.cli_clasificacion).checked = true;
					document. getElementById("id_param0").value=respuesta.idclientesdir;
					document. getElementById("id_param").value=respuesta.idclientes;	

				}

			});	

	}
 } else  if(nombre=='param6'){	

		var idclinte = document.getElementById("param61").value;
		datos = {"vlores":idclinte,"tipo":"cliente"};
		$.ajax({

				url: "buscarclientes.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

					document. getElementById("param4").value=respuesta.cli_idciudad;
	 				var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param5").value=res[0];
					document. getElementById("param51").value=res[1];
					document. getElementById("param19").value=res[2];
					document. getElementById("param20").value=res[3];
					document. getElementById("param23").value=res[4]; 
					document. getElementById("param6").value=respuesta.cli_nombre;
					document. getElementById("id_param0").value=respuesta.idclientesdir;
			});

 } else  if(nombre=='param8'){
 
	var telefono = $("input#param8").val();
	variable2 = telefono.length; 

	if (variable2 >= 7) {

		datos = {"vlores":telefono,"tipo":"telefono"};
		$.ajax({

				url: "buscarclientes.php",
				type: "POST",
				data: datos

			}).done(function(respuesta){

				if (respuesta === null) {
					
					document. getElementById("param9").value='';
					
					document. getElementById("param10").value=0;
					document. getElementById("param101").value='';
					document. getElementById("param21").value=0;
					document. getElementById("param22").value='';
					document. getElementById("param24").value='';
					document. getElementById("param11").value='';
					document. getElementById("id_param0").value=0;
					}

				else {
					document. getElementById("param9").value=respuesta.cli_nombre;
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param10").value=res[0];
					document. getElementById("param101").value=res[1];
					document. getElementById("param21").value=res[2];
					document. getElementById("param22").value=res[3];
					document. getElementById("param24").value=res[4];
					document. getElementById("param11").value=respuesta.cli_idciudad;
					document. getElementById("id_param0").value=respuesta.idclientesdir;
				}

			});

	}

 } else  if(nombre=='param9'){	

var idclinte = document.getElementById("param71").value;
datos = {"vlores":idclinte,"tipo":"cliente"};
$.ajax({

		url: "buscarclientes.php",
		type: "POST",
		data: datos
	}).done(function(respuesta){

					cambio_ajax2(documento, 19, 'clientesdir2', 'documento', 1, respuesta.cli_nombre);
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param10").value=res[0];
					document. getElementById("param101").value=res[1];
					document. getElementById("param21").value=res[2];
					document. getElementById("param22").value=res[3];
					document. getElementById("param24").value=res[4];
					document. getElementById("param11").value=respuesta.cli_idciudad;
					document. getElementById("id_param0").value=respuesta.idclientesdir;
				
	});

}else if(nombre=='param7'){

	var documento = $("input#param7").val();
	variable = documento.length; 
	var telefono = $("input#param8").val();
	variable2 = telefono.length; 

	if (variable >= 7) {
		datos = {"vlores":documento,"tipo":"documento"};

	 $.ajax({

			 url: "buscarclientes.php",
			 type: "POST",
			 data: datos

		 }).done(function(respuesta){

			 if (respuesta === null) {
				//cambio_ajax2(1, 19, 'clientesdir2', 'telefono', 1, '');
				 document. getElementById("param9").value='';
				 document. getElementById("param10").value=0;
				 document. getElementById("param101").value='';
				 document. getElementById("param21").value=0;
				 document. getElementById("param22").value='';
				 document. getElementById("param24").value='';
				 document. getElementById("param11").value='';
				 document. getElementById("id_param0").value=0;
			 }
			 else {
				cambio_ajax2(documento, 19, 'clientesdir2', 'documento', 1, respuesta.cli_nombre);
				document. getElementById("param8").value=respuesta.cli_telefono;
				 document. getElementById("param9").value=respuesta.cli_nombre;
				 var res = respuesta.cli_direccion.split("&");
				 if (typeof res[4] === 'undefined') { res[4]=''; }
				 document. getElementById("param10").value=res[0];
				 document. getElementById("param101").value=res[1];
				 document. getElementById("param21").value=res[2];
				 document. getElementById("param22").value=res[3];
				 document. getElementById("param24").value=res[4];
				 document. getElementById("param11").value=respuesta.cli_idciudad;
				 document. getElementById("id_param0").value=respuesta.idclientesdir;
			 }

		 });

 }

}

}; 

</script>

<?php 
//include("cabezote1.php"); 
$tcampod=1;
$tcampot=121;

$id_param2=$id_param;
@$param4=$rw[4];


echo '<form action="#" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validarguia();" >';

$FB->titulo_azul1("Remitente",10,0, 5);  

$FB->llena_texto("CC / Nit:",1, $tcampod, $DB, "", "", $rw[1], 1, 0);
$FB->llena_texto("Tel&eacute;fonos :",2, $tcampot, $DB, "", "", $rw[2], 4, 1);
//$FB->llena_texto("Nombre Del Cliente:", 6, 1, $DB, "", "", $rw[6], 1, 0);
	echo  "<tr bgcolor='#F3F3F3' ><td>Remitente:</td><td colspan=1><div id='clientesdir'>";
	echo ' <select class="trans"  name="param61"  id="param61" ><option  value=0>--Clientes--</option></select>';
	echo " <input name='param6' id='param6' class='trans'  type='text' value='$rw[6]' onkeypress='return noenter();'>
	</div></td>";

$FB->llena_texto("Ciudad:",4,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades` where inner_estados=1)", "calculoo(param11.value,this.value , \"editardatos.php\", 1);", "$param4", 4, 1);
//$FB->llena_texto("Direccion:",5, 1, $DB, "", "", $rw[5], 1, 0);

@$direcc=explode("&",$rw[5]);
@$param5=$direcc[0];
@$param51=$direcc[1];
@$param19=$direcc[2];
@$param20=$direcc[3];
@$param23=$direcc[4];
	echo "<tr bgcolor='#FFFFFF' ><td>Lugar de Recogida:</td>
	<td align='left' ><select class='trans'  name='param5' id='param5' >";
	echo "<option  value=''>Seleccione...</option>";
    $sql="SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
    $LT->llenaselect($sql,1,1, $param5, $DB);
    echo "</select>
	<input name='param51' id='param51' class='trans'  type='text' value='$param51' onkeypress='return noenter();'>
	</td>";

	echo "<td></td>
	<td align='left' ><select class='trans'  name='param19' id='param19' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
    $LT->llenaselect($sql,1,1, $param19, $DB);
    echo "</select>
	<input name='param20' id='param20' class='trans'  type='text' value='$param20' onkeypress='return noenter();'>
	</td>
	
	</tr>";

$FB->llena_texto("Barrio:", 23, 1, $DB, "", "", $param23, 17, 0);
$FB->llena_texto("Hora de Recogida:", 14, 1, $DB, "", "", $rw[14], 4, 0);	
$FB->llena_texto("Email:", 3, 111, $DB, "", "", $rw[3], 4, 0);	
//$FB->llena_texto("&iquest;Credito?:", 7, 212, $DB, "", "3",$rw[7], 4, 2);	

$FB->titulo_azul1("Destinatario",9,0,5); 

$FB->llena_texto("CC / Nit:",7, $tcampod, $DB, "", "", $rw[7], 1, 0);
$FB->llena_texto("Tel&eacute;fono De Contacto:",8, $tcampot, $DB, "", "", $rw[8], 4, 1);
//$FB->llena_texto("Nombre Destinatario:",9, 1, $DB, "", "", $rw[9], 4, 1);

echo  "<tr bgcolor='#F3F3F3' ><td>Nombre:</td><td colspan=1><div id='clientesdir2'>";
echo ' <select class="trans"  name="param71"  id="param71" ><option  value=0>--Clientes--</option></select>';
echo " <input name='param9' id='param9' class='trans'  type='text' value='$rw[9]' onkeypress='return noenter();'>
</div></td>";

$FB->llena_texto("Ciudad:",11,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades`  where inner_estados=1)", "calculoo(param4.value,this.value , \"editardatos.php\", 1);", "$rw[11]", 4, 1);

//$FB->llena_texto("Direccion del Contacto:",10, 1, $DB, "", "", $rw[10], 4, 1);

@$direcc2=explode("&",$rw[10]);
@$param10=$direcc2[0];
@$param101=$direcc2[1];
@$param21=$direcc2[2];
@$param22=$direcc2[3];
@$param24=$direcc2[4];

	echo "<tr bgcolor='#FFFFFF' ><td>Direcci&oacute;n del Contacto:</td>
	<td align='left' ><select class='trans'  name='param10' id='param10' >";
	echo "<option  value=''>Seleccione...</option>";
    $sql="SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
    $LT->llenaselect($sql,1,1, $param10, $DB);

    echo "</select>
	<input name='param101' id='param101' class='trans'  type='text' value='$param101' onkeypress='return noenter();'>
	</td>";

	echo "<td>Lugar de Entrega:</td>
	<td align='left' ><select class='trans'  name='param21' id='param21' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
    $LT->llenaselect($sql,1,1, $param21, $DB);
    echo "</select>
	<input name='param22' id='param22' class='trans'  type='text' value='$param22' onkeypress='return noenter();'>
	</td></tr>
	";

$FB->llena_texto("Barrio:", 24, 1, $DB, "", "", $param24, 1, 0);	

$FB->titulo_azul1("Servicio",9,0,5); 
$FB->llena_texto("Tipo de paquete:",12,82, $DB, $paquete, "",@$rw[12], 1, 0);
$FB->llena_texto("Dice contener:",13, 1, $DB, "", "", $rw[13], 4, 0);


$FB->llena_texto("Valor de Prestamo:",16, 118, $DB, "", "", $rw[16],17, 0);

$sqlc="SELECT sum(abo_valor),abo_estado FROM `abonosguias` where abo_idservicio='$id_param2' GROUP by abo_estado order by 2 asc";
$DB->Execute($sqlc);
$rw21=mysqli_fetch_row($DB->Consulta_ID); 
$Abonos=@$rw21[0];
$devolucion=@$rw21[1];
if($devolucion!=0){
	$Abonos=$Abonos-$devolucion;
}
if($Abonos>$rw[17]){
	$rw[17]=$Abonos;
}
$FB->llena_texto("Abono:",39, 118, $DB, "", "", $rw[17], 4,2);
$FB->llena_texto("param17", 1, 13, $DB, "", "", "$rw[17]", 5, 0); 


if(@$rw[18]==''){
	$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
	$DB1->Execute($sql12);
	$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
	$seguro=$porcentaje['0'];
} else{
	$seguro=$rw[18];
}

if($nivel_acceso!=1){
$FB->llena_texto("Seguro:",18, 126, $DB, "", "$seguro", $seguro, 1, 0);

}else{
	$FB->llena_texto("Seguro:",18, 118, $DB, "", "$seguro", $seguro, 1, 0);
}
$FB->llena_texto("Peso KG:",26,1, $DB, "", "","$rw[24]" ,4, 1);	
$FB->llena_texto("Volumen:",27,1, $DB, "", "","$rw[26]",1, 0);	
$FB->llena_texto("# Piezas:",30, 123, $DB, "", "", "$rw[27]", 4, 'min=1');
$FB->llena_texto("Estado Paquete:",31,9, $DB, "", "","$rw[28]" ,17, 1);
$FB->llena_texto("&iquest;Verificado?:",32, 214, $DB, "", "","$rw[29]", 4, 1);	
$FB->llena_texto("Tipo:",33,2, $DB, "(SELECT `tip_nombre`,`tip_nombre` FROM `tipo`)", "","$rw[30]", 1, 1);
$FB->llena_texto("Giros $:",29,118, $DB, "", "","",4, 0);	
$FB->llena_texto("&iquest;Servicio con Retorno?:", 25, 212, $DB, "", "3","",17, 0);	
$FB->llena_texto("# GUIA:",34, 1, $DB, "", "", $rw[25],4, 1);
//$FB->llena_texto("&iquest;Servicio con Retorno?:", 25, 212, $DB, "", "3","",17, 0);	
$FB->llena_texto("Reasignar Fecha:", 35, 10, $DB, "", "", "$rw[23]", 1, 0);
$FB->llena_texto("Servicio:",15,82, $DB, $Servicio, "",$rw[15],4, 1);

 $sql21="select gui_tiposervicio from guias where gui_idservicio=$id_param2";
$DB->Execute($sql21);
 $valortservicio=$DB->recogedato(0);
$aux = "";
$aux2 = "";
if ($valortservicio == 1000) {
	$aux2 = "selected";
} elseif ($valortservicio == 0) {
    $aux = "selected";
}

echo "<td style='background-color: #F5B7B1;'>Tipo de servicio(*)</td>
<td id='respuesta'>";
$sql="SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$rw[4] and pre_idciudaddes=$rw[11] and pre_estado=1 order by tip_nom";
echo '<select name="param37" id="param37" onchange="valorpagaredit(this.value,20,\"llega_sub2\",\"total valor\",1,1)" class="form-control" type="number" required="">';
// echo "<option  value='0'" . $aux . ">Carga via terrestre</option>";

$LT->llenaselect($sql,0,1, $valortservicio, $DB);
echo "<option  value='1000' " . $aux2 . " >A convenir</option>";
echo "</select>";
echo "</td>";

if($rw[15]=='Compra'){
	$FB->llena_texto("Pendiente Cancelar:",36, 118, $DB, "", "", "0", 1, 0);
	}else
	{
		$FB->llena_texto("param36", 4, 13, $DB, "", "", 0, 5, 0);
	}


//$FB->llena_texto("Tipo de Servicio:",37,279, $DB, "(SELECT `idtiposervicio`,`tip_nom` FROM `tiposervicio`)", "","$valortservicio", 1, 1);
$FB->llena_texto("Tipo Pago: ", 28, 215, $DB, "3", "valorpagaredit(this.value,20,\"llega_sub2\",\"total valor\",1,$id_usuario)","$rw[31]",4, 0);

$kilos=$rw[24];
$sqlprecios="SELECT idprecioskilos FROM `precioskilos` where '$kilos' BETWEEN pre_inicial and prec_final;"; 
$DB->Execute($sqlprecios);
$confipre=mysqli_fetch_row($DB->Consulta_ID); 
$idprecios=$confipre[0];
if($idprecios==0 or $idprecios==''){
	$idprecios=1;
}

if($valortservicio=='1000'){

	$preciokilo=$rw[32];

	$param7=1;
	$param8=1;

}elseif($valortservicio==1 and $rw[31]!=2){ //carga especial opcion contado
		
	$sql33="SELECT tip_preciokilo,tip_precioadicional from tiposervicio WHERE `idtiposervicio`=1"; 
	$DB->Execute($sql33);
	$rw7=mysqli_fetch_row($DB->Consulta_ID); 
	@$preciokilo=$rw7[0];
	@$precioadicional=$rw7[1];
	
}else if($rw[31]==2){

	$sqlc="SELECT rel_nom_credito,idcreditos FROM `rel_sercre` inner join creditos on cre_nombre=rel_nom_credito where idservicio=$id_param2 ";
   $DB->Execute($sqlc);
   $rw21=mysqli_fetch_row($DB->Consulta_ID); 
	$creditouser=$rw21[0];
	$idcredito=$rw21[1];

   $sql3="SELECT `pre_preciokilo`,`con_precios` FROM `precios_credito`  inner join `configuracionkilos` on con_idprecioskilos=idprecioscredito  WHERE   con_tipo='Credito'  and   `pre_idciudadori`='$rw[4]'  and `pre_idciudades`='$rw[11]' and pre_tiposervicio='$valortservicio' and pre_idcredito='$idcredito'  and con_idprecios='$idprecios' and pre_estado=1";
   $DB->Execute($sql3);
   $rw2=mysqli_fetch_row($DB->Consulta_ID); 

   @$preciokilo=$rw2[0];
   @$precioadicional=$rw2[1];
	   
} else if($valortservicio==0 and $rw[31]!=2){

	$sql="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios 
	where con_tipo='normal'  and pre_idciudadori='$rw[4]' and pre_idciudaddes='$rw[11]' and pre_tiposervicio=0 and con_idprecios='$idprecios' and pre_estado=1 ";
	 $DB->Execute($sql);
	$rw3 = mysqli_fetch_row($DB->Consulta_ID); 
	
	@$preciokilo=$rw3[1];
	@$precioadicional=$rw3[2];
	//@$serciudad=$param11;
}
else {

	$sql="SELECT `idprecios`, `pre_kilo`, `con_precios` FROM `precios` inner join `configuracionkilos` on con_idprecioskilos=idprecios 
	where con_tipo='normal'  and pre_idciudadori='$rw[4]' and pre_idciudaddes='$rw[11]' and pre_tiposervicio=$valortservicio and   con_idprecios='$idprecios' and pre_estado=1";
	$DB->Execute($sql);
	$rw3 = mysqli_fetch_row($DB->Consulta_ID); 

	@$preciokilo=$rw3[1];
	@$precioadicional=$rw3[2];
}

$kilosvolumen=$rw[24]+$rw[26];
$sqlprecios=$sql;

if($rw[24]>$precioinicialkilos){

	@$precio1=($kilosvolumen-$precioinicialkilos)*$precioadicional;
	@$precio=$preciokilo+$precio1;

}elseif($rw[24]<1) {

	@$precio1=$rw[26]*$precioadicional;
	@$precio=$rw[24];

}else{

	@$precio1=$rw[26]*$precioadicional;
	@$precio=$preciokilo+$precio1;

}

$param44=str_replace(".","", $rw[16]);
$param55=str_replace(".","", $rw[17]);
$param66=str_replace(".","", $rw[18]);

	 $sql="SELECT `pre_porcentaje` FROM `prestamo` WHERE `pre_inicio`<'$param44' and `pre_final`>='$param44'";
		$DB->Execute($sql);
		$porprestamo=$DB->recogedato(0);
	
		$dosporcentaje=explode(" ",$porprestamo); 

		if(@$dosporcentaje[1]=='%'){
			$porprestamo=($param44*@$dosporcentaje[0])/100;
		}

		$pordeclarado=(intval($param66)*1)/100;
		$totalvalorapagar=$precio+$param44-$param55+$pordeclarado+$porprestamo;
		
		if($valortservicio=='1000'){
			$valorapagar=$precio;
		}
		else{
			$valorapagar=$precio+$pordeclarado;
		}

$FB->llena_texto("id_param", 1, 13, $DB, "", "", "$rw[0]", 5, 0); // idcliente
$FB->llena_texto("id_param0", 1, 13, $DB, "", "", "$rw[21]", 5, 0); // idclientedir
$FB->llena_texto("id_param1", 1, 13, $DB, "", "", "0", 5, 0);
$FB->llena_texto("id_param2", 1, 13, $DB, "", "", "$id_param2", 5, 0);  // idservicios
$FB->llena_texto("id_usuario", 1, 13, $DB, "", "", "$id_usuario", 5, 0);
$FB->llena_texto("param114", 1, 13, $DB, "", "", "$totalvalorapagar", 5, 0);
$FB->llena_texto("estado", 1, 13, $DB, "", "", "$rw[33]", 5, 0);

echo "<tr><td>";
echo '<div id="llega_sub2" style="display: block; visibility: visible;">';

$FB->llena_texto("porprestamo", 1, 13, $DB, "", "", "$porprestamo", 5, 0);
$FB->llena_texto("pordeclarado", 1, 13, $DB, "", "", "$pordeclarado", 5, 0);
$FB->llena_texto("precio", 1, 13, $DB, "", "", "$precio", 5, 0);

if($rw[31]==1){
		
	
	$FB->titulo_azul1("",8,0,5);  
	$FB->llena_texto("Valor:",111, 118, $DB, "", "", $valorapagar, 6, 0);
	//$FB->llena_texto("Pendiente por Cobrar:",12, 5, $DB, "", "", $rw[9], 1, 1);
	$FB->llena_texto("Pendiente por Cobrar:",112, 5, $DB, "", "", "$rw[45]", 1, 2);
	if($rw[37]==6){
		$FB->llena_texto("param112", 1, 13, $DB, "", "", "6", 5, 0);
	}

	
}else if($rw[31]==2){
	$telefonos="'".$rw[2]."','".$rw[8]."'";
	$FB->titulo_azul1("",8,0,5);  
   $FB->llena_texto("Cliente:",113,2, $DB, "(SELECT  idcreditos,`cre_nombre` FROM `creditos` inner join  rel_crecli on rel_idcredito=idcreditos inner join clientesdir on idclientesdir=rel_idcliente where cli_telefono in ($telefonos) group by idcreditos )", "buscarservicio(param4.value,param11.value,this.value,\"Editar\");", "", 1, 1);

	
}else if($rw[31]==3){

	$FB->titulo_azul1("Valor",8,0,5);  
	$FB->llena_texto("",111, 118, $DB, "", "", $valorapagar, 6, 0);
	
 }

 echo '</div>'; 
 echo "</td></tr>";

?> 
