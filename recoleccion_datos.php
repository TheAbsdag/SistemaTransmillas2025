<?php 
if($actuliza=="si"){
?>
 <script>

timer =0;
function testtimeout(nombres){
clearTimeout(timer);
timer =setTimeout(buscar(nombres),2000);
}


 function cambiar() {
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

	if (variable >= 4) {
		datos = {"vlores":documento,"tipo":"documento"};
		
			
		$.ajax({
				url: "buscarclientesotros.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				
				if (respuesta === null) {
					
					//console.log(JSON.stringify(respuesta));
					//document.getElementById("param1").value='';
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1, '');
					
					document.getElementById("id_param1").value=0;
					document.getElementById("param2").value='';
					document.getElementById("param3").value='';
					document.getElementById("param4").value='';
					document.getElementById("param5").value='0';
					document.getElementById("param51").value='';
					document.getElementById("param19").value='0';
					document.getElementById("param20").value='';
					document.getElementById("param23").value='';
					document.getElementById("param6").value='';
					document.getElementById("0").checked = true;
					document.getElementById("id_param").value=0;
					document.getElementById("id_param1").value=0;
					document.getElementById("id_param2").value=0;
					
				}
				else {
					
					cambio_ajax2(documento, 14, 'clientesdir', 'documento', 1, respuesta.cli_nombre);
					
					//document.getElementById("param1").value=respuesta.cli_iddocumento;
					document.getElementById("param2").value=respuesta.cli_telefono;
					document.getElementById("param3").value=respuesta.cli_email;
					//document.getElementById("param4").value=respuesta.cli_idciudad;
					document.getElementById("param4").value='';

					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') {
						res[4]='';
						
					}
					document.getElementById("param5").value=res[0];
					document.getElementById("param51").value=res[1];
					document.getElementById("param19").value=res[2];
					document.getElementById("param20").value=res[3];
					document.getElementById("param23").value=res[4];
					document.getElementById("param6").value=respuesta.cli_nombre;
					document.getElementById(respuesta.cli_clasificacion).checked = true;
					document.getElementById("id_param2").value=respuesta.idclientesdir;					
					document.getElementById("id_param").value=respuesta.idclientes;
					document.getElementById("id_param1").value=1;
					 //document.getElementById('id_del_input').checked  document.form1.param7[7].value
					 buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value,  param113.value, "Recogida")

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
		}).done(function(respuesta) {
			// Asegúrate de que la respuesta venga parseada si es string
			if (typeof respuesta === 'string') {
				respuesta = JSON.parse(respuesta);
			}

			const cliente = respuesta.cliente || null;
			const servicios = respuesta.servicios || [];

			if (!cliente) {
				if (document.getElementById("id_param1").value != 1) {
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1, '');
								}
				document.getElementById("id_param").value = 0;
				document.getElementById("param3").value = '';
				document.getElementById("param6").value = '';
				document.getElementById("param4").value = '';
				document.getElementById("param5").value = 0;
				document.getElementById("param51").value = '';
								document.getElementById("0").checked = true;
				document.getElementById("param19").value = 0;
				document.getElementById("param20").value = '';
				document.getElementById("param23").value = '';
				document.getElementById("id_param1").value = 0;
				document.getElementById("id_param2").value = 0;
			} else {
				cambio_ajax2(documento, 14, 'clientesdir', 'telefono', 1, cliente.cli_nombre);
								
				document.getElementById("param3").value = cliente.cli_email;
				document.getElementById("param4").value = '';

				// ✅ Protegemos el .split() para que no falle si la dirección no viene
				let direccion = cliente.cli_direccion || '';
				let res = direccion.split("&");
				while (res.length < 5) {
					res.push('');
				}

				document.getElementById("param5").value = res[0];
				document.getElementById("param51").value = res[1];
				document.getElementById("param19").value = res[2];
				document.getElementById("param20").value = res[3];
				document.getElementById("param23").value = res[4];

				document.getElementById("id_param").value = cliente.idclientes;
				document.getElementById("id_param2").value = cliente.idclientesdir;

				if (cliente.cli_clasificacion != null) {
					// document.getElementById(cliente.cli_clasificacion).checked = true;
								}

				buscarservicio(cliente.cli_idciudad, document.getElementById("param11").value, param113.value, "Recogida");

				if (Array.isArray(servicios) && servicios.length > 0) {
					// Limpiar tabla
					const tbody = document.getElementById("tbodyServiciosPendientes");
					tbody.innerHTML = "";

					// Llenar tabla con los servicios
					servicios.forEach(function(servicio, index) {
						const dirRemitente = (servicio.cli_direccion || "").replaceAll("&", " ");
						const dirDestinatario = (servicio.ser_direccioncontacto || "").replaceAll("&", " ");

						const row = `
							<tr>
								<td>${index + 1}</td>
								<td>${servicio.ser_fecharegistro || "N/A"}</td>
								<td>${servicio.cli_nombre || "N/A"}</td>
								<td>${dirRemitente || "N/A"}</td>
								<td>${servicio.ciu_nombre || "N/A"}</td>
								<td>${servicio.cli_telefono || "N/A"}</td>
								<td>${servicio.ser_destinatario || "N/A"}</td>
								<td>${dirDestinatario || "N/A"}</td>
								<td>${servicio.ser_ciudadentrega || "N/A"}</td>
								<td>${servicio.ser_telefonocontacto || "N/A"}</td>
							</tr>
						`;

						tbody.insertAdjacentHTML("beforeend", row);
					});

					// Mostrar modal
					$("#myModalConServicios").modal("show");
				}
							}
						});
					
	}
 } else  if(nombre=='param6'){	
 
		var idclinte = document.getElementById("param61").value;

		datos = {"vlores":idclinte,"tipo":"cliente"};
	
		$.ajax({
				url: "buscarclientesotros.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				
					
					
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					//document.getElementById("param4").value=respuesta.cli_idciudad;
					document.getElementById("param4").value='';
					document.getElementById("param5").value=res[0];
					document.getElementById("param51").value=res[1];
					document.getElementById("param19").value=res[2];
					document.getElementById("param20").value=res[3];
					document.getElementById("param23").value=res[4];
					document.getElementById("param6").value=respuesta.cli_nombre;
					document.getElementById("id_param2").value=respuesta.idclientesdir;
					buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value,  param113.value, "Recogida")

				
			});
  
 } else  if(nombre=='param8'){
	 
	 var telefono = $("input#param8").val();
	variable2 = telefono.length; 
	

	if (variable2 >= 7) {
		datos = {"vlores":telefono,"tipo":"telefono"};
		
$.ajax({
	url: "buscarclientesotros.php",
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
					
					}
				else {
					document.getElementById("param9").value=respuesta.cli_nombre;
					
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') {
						res[4]='';
						
					}
					document.getElementById("param10").value=res[0];
					document.getElementById("param101").value=res[1];
					document.getElementById("param21").value=res[2];
					document.getElementById("param22").value=res[3];
					document.getElementById("param24").value=res[4];
					document.getElementById("param11").value=respuesta.cli_idciudad;
					//document.getElementById("param11").value='';
					buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value,  param113.value, "Recogida")

					//document.getElementById("id_param2").value=respuesta.idclientesdir;
					
				}
			});
					
	}
	 
 }else  if(nombre=='param9'){	

var idclinte = document.getElementById("param71").value;
datos = {"vlores":idclinte,"tipo":"cliente"};
$.ajax({

		url: "buscarclientesotros.php",
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
					//document. getElementById("param11").value='';

					document. getElementById("id_param0").value=respuesta.idclientesdir;
					buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value, param113.value, "Recogida")

				
	});

} 
 else  if(nombre=='param7'){

	var documento = $("input#param7").val();
	variable = documento.length; 
	var telefono = $("input#param8").val();
	variable2 = telefono.length; 

	if (variable >= 7) {
		datos = {"vlores":documento,"tipo":"documento"};

	 $.ajax({

			 url: "buscarclientesotros.php",
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
				//document. getElementById("param11").value='';

				 document. getElementById("id_param0").value=respuesta.idclientesdir;
				 buscarservicio(document.getElementById("param4").value, respuesta.cli_idciudad,  param113.value, "Recogida")

			 }

		 });

 }

}
				
}; 
//para cambiar palabra rchivos documentos etc
function buscar_ajax(cadena){

var palabra = cadena;
var d = palabra.search("documento");
var p = palabra.search("papel");
var s = palabra.search("sobre");

var D = palabra.search("DOCUMENTO");
var P = palabra.search("PAPEL");
var S = palabra.search("SOBRE");

var Do = palabra.search("Documento");
var Pa = palabra.search("Papel");
var So = palabra.search("Sobre");

if (d > -1 ) 
{
	var nuevapal = palabra.replace('documento', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(p> -1  )
{
	var nuevapal = palabra.replace('papel', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (s > -1 )
{
	var nuevapal = palabra.replace('sobre', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}else if ( D > -1) 
{
	var nuevapal = palabra.replace('DOCUMENTO', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(P> -1  )
{
	var nuevapal = palabra.replace('PAPEL', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (S > -1)
{
	var nuevapal = palabra.replace('SOBRE', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}else if ( Do > -1) 
{
	var nuevapal = palabra.replace('Documento', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(Pa> -1  )
{
	var nuevapal = palabra.replace('Papel', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (So > -1)
{
	var nuevapal = palabra.replace('Sobre', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}
}



//`idclientes`, `cli_iddocumento`, `cli_nombre`, `cli_email`, `cli_direccion`, `cli_idciudad`, `cli_telefono`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`
</script>
<?php 
}
//include("cabezote1.php"); 
$tcampod=1;
$tcampot=121;
if($estadofactura=='verificacion'){
$tcampod=1;
$tcampot=121;	
}
else if($estadofactura=='recoleccion'){
	
$tcampod=117;
$tcampot=120;	
}
@$param4=$rw[4];
if($nivel_acceso!=1){  $cond6=" WHERE inner_sedes='$id_sedes'"; }  else { $cond6=""; }
//$FB->abre_form("form1","recoleccionok.php","post");
echo '<form action="recoleccionok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validar_repuesta();" >';

$FB->titulo_azul1("Remitente / Lugar de Recogida",10,0, 5);  

$FB->llena_texto("CC / Nit:",1, $tcampod, $DB, "", "", $rw[1], 1, 0);
$FB->llena_texto("Tel&eacute;fonos :",2, $tcampot, $DB, "", "", $rw[2], 4, 1);
//$FB->llena_texto("Nombre Del Cliente:", 6, 1, $DB, "", "", $rw[6], 1, 0);

	echo  "<tr bgcolor='#F3F3F3' ><td>Cliente:</td><td colspan=1><div id='clientesdir'>";
	echo ' <select class="trans"  name="param61"  id="param61" ><option  value=0>--Nuevo--</option></select>';
	echo " <input name='param6' id='param6' class='trans'  type='text' value='$rw[6]' onkeypress='return noenter();'>
	</div></td>";
	

// $FB->llena_texto("Ciudad:",4,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades` )", "", "$param4", 4, 1);
$FB->llena_texto("Ciudad Origen:",4,2,$DB,"(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1  order by idciudades)", "buscarservicio(param11.value,this.value,param113.value,\"Recogida\");", "$param4", 4, 1);

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
$FB->llena_texto("Email:", 3, 111, $DB, "", "", $rw[3], 1, 0);	
//$FB->llena_texto("&iquest;Credito?:", 7, 212, $DB, "", "3",$rw[7], 4, 2);	



$FB->titulo_azul1("Destinatario",9,0,5); 

$FB->llena_texto("CC / Nit:",7, $tcampod, $DB, "", "", $rw[7], 1, 0);
$FB->llena_texto("Tel&eacute;fono De Contacto:",8, $tcampot, $DB, "", "", $rw[8], 4, 1);
echo  "<tr bgcolor='#F3F3F3' ><td>Nombre:</td><td colspan=1><div id='clientesdir2'>";
echo ' <select class="trans"  name="param71"  id="param71" ><option  value=0>--Clientes--</option></select>';
echo " <input name='param9' id='param9' class='trans'  type='text' value='$rw[9]' onkeypress='return noenter();'>
</div></td>";

// $FB->llena_texto("Ciudad:",11,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades`  where inner_estados=1)", "", "$rw[11]", 4, 1);
$FB->llena_texto("Ciudad Destino:",11,2,$DB,"(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1  order by ciu_nombre )","buscarservicio(param4.value,this.value,param113.value,\"Recogida\");","$rw[11]",4,1);

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
$FB->llena_texto("Email:", 31, 111, $DB, "", "", $rw[31], 4, 0);	


$FB->titulo_azul1("Servicio",9,0,5); 

$FB->llena_texto("Tipo de paquete:",12,82, $DB, $paquete, "",@$rw[12], 1, 0);
// $FB->llena_texto("Dice contener:",13, 1, $DB, "", "", $rw[13], 4, 0);
echo'<td>Dice contener:</td><td><input type="text" name="param13" id="param13" value="'.$rw[13].'" onkeyup="buscar_ajax(this.value);"></td>';

//$FB->llena_texto("Servicio:",15,82, $DB, $Servicio, "",@$rw[15],1, 1);

//$FB->llena_texto("Valor de Prestamo:",16, 118, $DB, "", "", $rw[16],17, 0);

if($id_sedes!=1 or $nivel_acceso==1){
	$FB->llena_texto("Abono:",17, 118, $DB, "", "", $rw[17], 1,0);
}else{
	
	$FB->llena_texto("Abono:",29, 118, $DB, "", "", $rw[17], 1,2);
	$FB->llena_texto("param17", 1, 13, $DB, "", "", $rw[17], 4, 0); 
}
if(@$rw[18]==''){
	$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
	$DB1->Execute($sql12);
	$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
	$seguro=$porcentaje['0'];
} else{
	$seguro=$rw[18];
}

$FB->llena_texto("Seguro:",18, 126, $DB, "", "$seguro", $seguro,4, 0);

$valortservicio=0;
if($id_param!=''){
	 $sql21="select gui_tiposervicio from guias where gui_idservicio=$id_param";
	$DB->Execute($sql21);
	$valortservicio=$DB->recogedato(0);

	 $sql5="SELECT `rel_nom_credito` FROM `rel_sercre` WHERE `idservicio`=$id_param";
			$DB->Execute($sql5);
			$nombrecredito=$DB->recogedato(0);
}
$FB->llena_texto("&iquest;Servicio con Retorno?:", 25, 212, $DB, "", "3","$rw[20]",17, 0);	


if($rw[24]=='' or $rw[24]==NULL or $rw[24]==0){ $creditos=3; } else { $creditos=1; }
$FB->llena_texto("&iquest;Credito?:",28, 216, $DB, "3","creditosrecolecion(this.value,24,\"llega_sub2\",\"total valor\",1,0)", "$creditos", 4, 0);	
echo "<td colspan=4><div id='llega_sub2' >";
	if ($creditos == 1) {
		$telefonos=$rw[2].','.$rw[8];
		//echo "SELECT  cre_nombre,`cre_nombre` FROM `creditos` inner join  rel_crecli on rel_idcredito=idcreditos inner join clientesdir on idclientesdir=rel_idcliente where cli_telefono in ($telefonos) group by idcreditos ";	
		$FB->titulo_azul1("Credito	", 8, 0, 5);
		$FB->llena_texto("Cliente:", 113, 2, $DB, "(SELECT  cre_nombre,`cre_nombre` FROM `creditos` inner join  rel_crecli on rel_idcredito=idcreditos inner join clientesdir on idclientesdir=rel_idcliente where cli_telefono in ($telefonos) group by idcreditos )", "buscarservicio(param4.value,param11.value,this.value,'Recogida');", "$nombrecredito", 1, 1);

		echo "</td></tr></table>";
	}else{
		echo"<input id='param113' name='param113' type='hidden' value=''>";

	}
	echo '</div></td></tr>';



	echo "<tr><td style='background-color: #F5B7B1;'>Tipo de servicio(*)</td><td id='respuesta'><input id='param27' name='param27' type='hidden' value='0'></td><td colspan=2 id='convenir'>";
	if (@$actuliza != "si") {	
		$sql21 = "select gui_tiposervicio from guias where gui_idservicio=$id_param";
		$DB->Execute($sql21);
		$valortservicio = $DB->recogedato(0);
		if ($valortservicio == '1000') {
			echo '<table><tr bgcolor="#FFFFFF" class="text"><td>Valor Flete: </td><td align="right" class="text"><div class="input-group"><span class="input-group-addon">$</span><input name="param107" id="param107" class="form-control" type="text" autocomplete="off" onkeyup="format(this)" onchange="format(this)" value="' . $rw[26] . '" ></div></td><td class="text"># Piezas: </td><td align="right"><div class="input-group"><span class="input-group-addon">$</span><input name="param30" id="param30" class="form-control" type="number" value="'.$rw[27].'" min="1" required=""></div></td></tr></table>';
		}
	}

	echo "</td></tr>";

$FB->llena_texto("param15", 1, 13, $DB, "", "", "$param15", 5, 0); 
$FB->llena_texto("id_param", 1, 13, $DB, "", "", "$rw[0]", 5, 0); //idclientes
$FB->llena_texto("id_param0", 1, 13, $DB, "", "", "$rw[19]", 5, 0); //idservicio
$FB->llena_texto("id_param1", 1, 13, $DB, "", "", "0", 5, 0);
$FB->llena_texto("id_param2", 1, 13, $DB, "", "", "$rw[21]", 5, 0); //idclientesdir
?> 

<script>

function validar_repuesta()
{
	var carga=0;
	var credito=document.getElementsByName("param28");
	var memory=credito[0].checked;
	//alert(credito);
	if(memory==true){
		var combo = document.getElementById("param113");
		var nomcredito = combo.options[combo.selectedIndex].text;
		//var nomcredito=document.getElementById("param113").value;
		var tipocredito=document.getElementById("param27").value;
		var ciudadori=document.getElementById("param4").value;
		var ciudaddes=document.getElementById("param11").value;

		datos = {"tipoguia":"validaprecios","nomcredito":nomcredito,"tipocredito":tipocredito,"ciudadori":ciudadori,"ciudaddes":ciudaddes};
		$.ajax({
			url: "guiasok.php",
			type: "POST",
			data: datos
			}).done(function(respuesta){
				
				if(respuesta==2){
					alert("No Tiene Precios de Creditos  configurados para estas opciones.");
					// my_callback(1); 
				}else{
					var submitFormFunction = Object.getPrototypeOf(form1).submit;
					submitFormFunction.call(form1);
					}
		});
		

	}else{

		console.log('ok22');
	
							var teldestino=document.getElementById("param8").value;
							var telremitente=document.getElementById("param2").value;
							var ciudadori=document.getElementById("param4").value;
							var ciudaddes=document.getElementById("param11").value;

							datos = {"tipoguia":"validarrepetidas","teldestino":teldestino,"telremitente":telremitente,"ciudadori":ciudadori,"ciudaddes":ciudaddes};
							$.ajax({
								url: "guiasok.php",
								type: "POST",
								data: datos,
								async: false,
								success: function(respuesta) {	
									console.log('respuesta');
									console.log(respuesta);
									if(respuesta>0){
										
											idservicio=respuesta;
											trueorfalse=4;
									}else{
											trueorfalse=0;
										}
								}
							});	
						 
							if(trueorfalse==4){
								
									console.log('calling');
									pop_dis5(idservicio,"Recogidas");
									setTimeout(function(){ 
									
									var mensaje="YA HAY UNA SERVICIO CON ESTE MISMO DESTINO, IDSERVICIO. "+idservicio+"  Desea Continuar?";
									var opcion =confirm(mensaje);
									if (opcion == true) {
										
										var submitFormFunction = Object.getPrototypeOf(form1).submit;
										submitFormFunction.call(form1);
									} else{
										return false;
									}
								}, 6000);																						

							}else{
								return true;
							}
		
			
	}
	return false;

}
function atar(){
	let credito = document.getElementById("param113").value;
    let nombre = document.getElementById("param123").value;
    let direccion = document.getElementById("param124").value;
    let idCliente = document.getElementById("param125").value;
	let telefono = document.getElementById("param121").value;

	datos = {"param1":credito,"param2":telefono,"param3":nombre,"param4":direccion,"param5":idCliente,"condeccion":"rel_crecli","tabla":"rel_crecli","general":"0","enviar":"Guardar","recargar":"no"};
								$.ajax({
									url: "nuevo_adminok.php",
									type: "POST",
									data: datos,
									async: false,
									success: function(respuesta) {	
										console.log('respuesta');
										console.log(respuesta);
										if (respuesta.trim() === "ok") {
													// Ocultar un div y tres inputs
													$("#llega_sub3").hide();         // id del div
										    alert("¡Atado con exito!");
										}
									}
								});	
}						 
</script>