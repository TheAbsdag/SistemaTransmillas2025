<?php
if (@$actuliza == "si") {
?>
<script src="js/jquery.confirmModal.min.js"></script>
	<script>
		timer = 0;

		function testtimeout(nombres) {

				buscar(nombres);
		}

		function cambiar() {
			// alert(document.getElementById("param16").value);
			if (document.getElementById("param16").value == '') {
				document.getElementById("param17").required = false;
			} else {
				document.getElementById("param17").required = true;
			}
		}


		function buscar(nombre) {
			if (nombre == 'param1') {
				var documento = $("input#param1").val();
				variable = documento.length;
				var telefono = $("input#param2").val();
				variable2 = telefono.length;

				if (variable >= 7) {
					datos = {
						"vlores": documento,
						"tipo": "documento"
					};


					$.ajax({
						url: "buscarclientesotros.php",
						type: "POST",
						data: datos
					}).done(function(respuesta) {

						if (respuesta === null) {
							
							if(telefono!=''){

							}else{

								cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1, '');
								document.getElementById("id_param1").value = 0;
								document.getElementById("param2").value = '';
								document.getElementById("param3").value = '';
								document.getElementById("param4").value = '';
								document.getElementById("param5").value = '0';
								document.getElementById("param51").value = '';
								document.getElementById("param19").value = '0';
								document.getElementById("param20").value = '';
								document.getElementById("param23").value = '';
								document.getElementById("param6").value = '';
								document.getElementById("0").checked = true;
								document.getElementById("id_param").value = 0;
								document.getElementById("id_param1").value = 0;
								document.getElementById("id_param2").value = 0;

							}



						} else {

							cambio_ajax2(documento, 14, 'clientesdir', 'documento', 1, respuesta.cli_nombre);

							//document.getElementById("param1").value=respuesta.cli_iddocumento;
							if(telefono!=''){
								temtelefono=respuesta.cli_telefono;
								if(temtelefono!=telefono){
								/* 	$.confirmModal('El  Nit/Cedula tiene vinculado otro telefono desea cambiar la Informacion?', function(el) {
										console.log("Ok was clicked!");
										//do delete operation
									}); */
									alert('El  Nit/Cedula tiene vinculado otro telefono se cambiar la Informacion');
								}
							}

							document.getElementById("param2").value = respuesta.cli_telefono;					
							document.getElementById("param3").value = respuesta.cli_email;
							//document.getElementById("param4").value=respuesta.cli_idciudad;
							document.getElementById("param4").value = '';

							var res = respuesta.cli_direccion.split("&");
							document.getElementById("param5").value = res[0];
							document.getElementById("param51").value = res[1];
							document.getElementById("param19").value = res[2];
							document.getElementById("param20").value = res[3];
							document.getElementById("param23").value = res[4];
							document.getElementById("param6").value = respuesta.cli_nombre;
							//document.getElementById(respuesta.cli_clasificacion).checked = true;
							document.getElementById("id_param2").value = respuesta.idclientesdir;
							document.getElementById("id_param").value = respuesta.idclientes;
							document.getElementById("id_param1").value = 1;
							//document.getElementById('id_del_input').checked  document.form1.param7[7].value
							buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value, param113.value, "Compra")
						}
					});

				}else{
					alert('EL Nit/Cedula debe ser mayor a 7 digitos');
				}

			} else if (nombre == 'param2') {

				var documento = $("input#param1").val();
				variable = documento.length;
				var telefono = $("input#param2").val();
				variable2 = telefono.length;


				if (variable2 >= 7) {
					datos = {
						"vlores": telefono,
						"tipo": "telefono"
					};

					$.ajax({
						url: "buscarclientesotros.php",
						type: "POST",
						data: datos
					}).done(function(respuesta) {

						if (respuesta === null) {

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
							//document.getElementById("param7").checked = false; 
							document.getElementById("param19").value = 0;
							document.getElementById("param20").value = '';
							document.getElementById("param23").value = '';

							document.getElementById("id_param1").value = 0;
							document.getElementById("id_param2").value = 0;
						} else {
							//document.getElementById("param1").value=respuesta.cli_iddocumento;
							//document.getElementById("param2").value=respuesta.cli_telefono;
							temdocumento=respuesta.cli_iddocumento;
							variable3=temdocumento.length;
							if(documento!='' && temdocumento!=''){								
								if(temdocumento!=documento){

									alert('El  Telefono tiene vinculado otro Nit/Cedula se cambiar la Informacion');
									
								}
							}

							if(temdocumento!=''){
								document.getElementById("param1").value = respuesta.cli_iddocumento;
							}

								cambio_ajax2(documento, 14, 'clientesdir', 'telefono', 1, respuesta.cli_nombre);

								document.getElementById("param3").value = respuesta.cli_email;
								//document.getElementById("param4").value=respuesta.cli_idciudad;
								document.getElementById("param4").value = '';

								var res = respuesta.cli_direccion.split("&");
								//alert(res[0]);
								document.getElementById("param5").value = res[0];
								document.getElementById("param51").value = res[1];
								document.getElementById("param19").value = res[2];
								document.getElementById("param20").value = res[3];
								document.getElementById("param23").value = res[4];
								//document.getElementById("param6").value=respuesta.cli_nombre;

								document.getElementById("id_param2").value = respuesta.idclientesdir;
								document.getElementById("id_param").value = respuesta.idclientes;
								/* if(respuesta.cli_clasificacion!=null){
									document. getElementById(respuesta.cli_clasificacion).checked = true;
								} */

								buscarservicio(respuesta.cli_idciudad, document.getElementById("param11").value, param113.value, "Compra")
							
						} 
					});

				}else {
							alert('EL Telefono debe ser mayor a 7 digitos');
					 }
			} else if (nombre == 'param6') {

				var idclinte = document.getElementById("param61").value;

				datos = {
					"vlores": idclinte,
					"tipo": "cliente"
				};

				$.ajax({
					url: "buscarclientesotros.php",
					type: "POST",
					data: datos
				}).done(function(respuesta) {

					//document.getElementById("param4").value=respuesta.cli_idciudad;
					document.getElementById("param4").value = '';

					var res = respuesta.cli_direccion.split("&");
					document.getElementById("param5").value = res[0];
					document.getElementById("param51").value = res[1];
					document.getElementById("param19").value = res[2];
					document.getElementById("param20").value = res[3];
					document.getElementById("param23").value = res[4];
					document.getElementById("param6").value = respuesta.cli_nombre;
					document.getElementById("id_param2").value = respuesta.idclientesdir;
					buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value, param113.value, "Compra")

				});

			} else if (nombre == 'param8') {

				var documento = $("input#param7").val();
				variable = documento.length;
				var telefono = $("input#param8").val();
				variable2 = telefono.length;


				if (variable2 >= 7) {
					datos = {
						"vlores": telefono,
						"tipo": "telefono"
					};

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
						// if (respuesta === null) {

							document.getElementById("param9").value = '';

							document.getElementById("param10").value = 0;
							document.getElementById("param101").value = '';
							document.getElementById("param21").value = 0;
							document.getElementById("param22").value = '';
							document.getElementById("param24").value = '';
							document.getElementById("param11").value = '';
							var el = document.getElementById('cupo'); //se define la variable "el" igual a nuestro div
							el.style.display = 'none';

						} else {

							document.getElementById("id_param3").value = cliente.idclientes;
							document.getElementById("param9").value = cliente.cli_nombre;
							var a = 0;
							if (isNaN(parseInt(cliente.cli_valorprestado))) {
								a = 0;
							} else {
								a = parseInt(cliente.cli_valorprestado);
							}
							var b = parseInt(cliente.cli_valoraprobado);
							var disponible = b - a;
							var res = cliente.cli_direccion.split("&");

							temdocumento=cliente.cli_iddocumento;
							variable3=temdocumento.length;
							if(documento!='' && temdocumento!=''){								
								if(temdocumento!=documento){

									alert('El  Telefono tiene vinculado otro Nit/Cedula se cambiar la Informacion');
									
								}
							}

							if(temdocumento!=''){
								document.getElementById("param7").value = cliente.cli_iddocumento;
							}

							document.getElementById("param10").value = res[0];
							document.getElementById("param101").value = res[1];
							document.getElementById("param21").value = res[2];
							document.getElementById("param22").value = res[3];
							document.getElementById("param24").value = res[4];
							document.getElementById("param30").value = disponible;
							document.getElementById("param26").value = cliente.cli_valoraprobado;
							document.getElementById("param11").value = cliente.cli_idciudad;
							//document.getElementById("param11").value='';
							//document.getElementById("id_param2").value=cliente.idclientesdir;
							buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value, param113.value, "Compra")
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
							if (disponible > 0) {

								var el = document.getElementById('cupo'); //se define la variable "el" igual a nuestro div
								el.style.display = (el.style.display == 'none') ? 'block' : 'none';
								el.innerHTML = "<strong>Atencion!</strong> El cupo disponible para este Cliente es de: " + disponible + " y el valor aprobado es de: " + cliente.cli_valoraprobado + "</a>";
							} else {

								var el = document.getElementById('cupo'); //se define la variable "el" igual a nuestro div
								el.style.display = 'none';
							}
						}
					});

				}else {
							alert('EL Telefono debe ser mayor a 7 digitos');
					 }

			} else if (nombre == 'param9') {

				var idclinte = document.getElementById("param71").value;
				datos = {
					"vlores": idclinte,
					"tipo": "cliente"
				};
				$.ajax({

					url: "buscarclientesotros.php",
					type: "POST",
					data: datos
				}).done(function(respuesta) {

					cambio_ajax2(documento, 19, 'clientesdir2', 'documento', 1, respuesta.cli_nombre);
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') {
						res[4] = '';
					}
					document.getElementById("param10").value = res[0];
					document.getElementById("param101").value = res[1];
					document.getElementById("param21").value = res[2];
					document.getElementById("param22").value = res[3];
					document.getElementById("param24").value = res[4];
					document.getElementById("param11").value = respuesta.cli_idciudad;
					//document. getElementById("param11").value='';

					document.getElementById("id_param0").value = respuesta.idclientesdir;
					buscarservicio(document.getElementById("param4").value, document.getElementById("param11").value, param13.value, "oficina")

				});

			} else if (nombre == 'param7') {

				var documento = $("input#param7").val();
				variable = documento.length;
				var telefono = $("input#param8").val();
				variable2 = telefono.length;

				if (variable >= 7) {
					datos = {
						"vlores": documento,
						"tipo": "documento"
					};

					$.ajax({

						url: "buscarclientesotros.php",
						type: "POST",
						data: datos

					}).done(function(respuesta) {

						if (respuesta === null) {

							if(telefono!=''){

							}else{

								//cambio_ajax2(1, 19, 'clientesdir2', 'telefono', 1, '');
								document.getElementById("param9").value = '';
								document.getElementById("param10").value = 0;
								document.getElementById("param101").value = '';
								document.getElementById("param21").value = 0;
								document.getElementById("param22").value = '';
								document.getElementById("param24").value = '';
								document.getElementById("param11").value = '';
								document.getElementById("id_param0").value = 0;
							}
						} else {
							cambio_ajax2(documento, 19, 'clientesdir2', 'documento', 1, respuesta.cli_nombre);

							if(telefono!=''){
								temtelefono=respuesta.cli_telefono;
								if(temtelefono!=telefono){
									alert('El  Nit/Cedula tiene vinculado otro telefono se cambiar la Informacion');
								}
							}
							document.getElementById("param8").value = respuesta.cli_telefono;
							document.getElementById("param9").value = respuesta.cli_nombre;
							var res = respuesta.cli_direccion.split("&");
							if (typeof res[4] === 'undefined') {
								res[4] = '';
							}
							document.getElementById("param10").value = res[0];
							document.getElementById("param101").value = res[1];
							document.getElementById("param21").value = res[2];
							document.getElementById("param22").value = res[3];
							document.getElementById("param24").value = res[4];

							document.getElementById("param11").value = respuesta.cli_idciudad;
							// document. getElementById("param11").value='';
							console.log(respuesta.cli_idciudad);

							document.getElementById("id_param0").value = respuesta.idclientesdir;
							buscarservicio(document.getElementById("param4").value, respuesta.cli_idciudad, param113.value, "compra")
						}

					});

				}else{
					alert('EL Nit/Cedula debe ser mayor a 7 digitos');
				}

			}

		}
	</script>
<?php
}
//include("cabezote1.php"); 
$tiposerv = "compra";
$tcampod = 1;
$tcampot = 121;
if (@$estadofactura == 'verificacion') {
	$tcampod = 1;
	$tcampot = 121;
} else if (@$estadofactura == 'recoleccion') {

	$tcampod = 127;
	$tcampot = 128;
}
@$param4 = $rw[4];
//if($param4==''){  $param4=$id_ciudad; }  
if ($nivel_acceso != 1) {
	$cond6 = " WHERE inner_sedes='$id_sedes'";
} else {
	$cond6 = "";
}
//$FB->abre_form("form1","recoleccionok.php","post");
echo '<form action="recoleccionok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validar_repuesta();" >';

$FB->titulo_azul1("Destinatario", 9, 0, 5);


$FB->llena_texto("CC / Nit:", 7, $tcampod, $DB, "", "", $rw[7], 1, 0);
$FB->llena_texto("Tel&eacute;fono De Contacto:", 8, $tcampot, $DB, "", "", $rw[8], 4, 1);
//$FB->llena_texto("Nombre Destinatario:",9, 1, $DB, "", "", $rw[9], 4, 1);

echo  "<tr bgcolor='#F3F3F3' ><td>Nombre:</td><td colspan=1><div id='clientesdir2'>";
echo ' <select class="trans"  name="param71"  id="param71" ><option  value=0>--Clientes--</option></select>';
echo " <input name='param9' id='param9' class='trans'  type='text' value='$rw[9]' onkeypress='return noenter();'>
</div></td>";

// $FB->llena_texto("Ciudad:",11,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades`  where inner_estados=1)", "", "$rw[11]", 4, 1);
//detalles lugar de destino
$FB->llena_texto("Ciudad Destino:", 11, 2, $DB, "(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 )", "buscarservicio(param4.value,this.value,param113.value,\"Compra\");", "$rw[11]", 4, 1);

//$FB->llena_texto("Direccion del Contacto:",10, 1, $DB, "", "", $rw[10], 4, 1);

@$direcc2 = explode("&", $rw[10]);
@$param10 = $direcc2[0];
@$param101 = $direcc2[1];
@$param21 = $direcc2[2];
@$param22 = $direcc2[3];
@$param24 = $direcc2[4];

echo "<tr bgcolor='#FFFFFF' ><td>Direcci&oacute;n del Contacto:</td>
	<td align='left' ><select class='trans'  name='param10' id='param10' >";
echo "<option  value=''>Seleccione...</option>";
$sql = "SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
$LT->llenaselect($sql, 1, 1, $param10, $DB);

echo "</select>
	<input name='param101' id='param101' class='trans'  type='text' value='$param101' onkeypress='return noenter();'>
	</td>";

echo "<td>Lugar de Entrega:</td>
	<td align='left' ><select class='trans'  name='param21' id='param21' >";
echo "<option  value='0'>Seleccione...</option>";
$sql = "SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
$LT->llenaselect($sql, 1, 1, $param21, $DB);
echo "</select>
	<input name='param22' id='param22' class='trans'  type='text' value='$param22' onkeypress='return noenter();'>
	</td></tr>
	";

$FB->llena_texto("Barrio:", 24, 1, $DB, "", "", $param24, 1, 0);
$FB->llena_texto("Email:", 31, 111, $DB, "", "", $rw[31], 4, 0);



$FB->titulo_azul1("Lugar de la Compra", 10, 0, 5);

$FB->llena_texto("CC / Nit:", 1, $tcampod, $DB, "", "", $rw[1], 1, 0);
$FB->llena_texto("Tel&eacute;fonos :", 2, $tcampot, $DB, "", "", $rw[2], 4, 0);
//$FB->llena_texto("Nombre Del Cliente:", 6, 1, $DB, "", "", $rw[6], 1, 0);

echo  "<tr bgcolor='#F3F3F3' ><td>Remitente:</td><td colspan=1><div id='clientesdir'>";
echo ' <select class="trans"  name="param61"  id="param61" ><option  value=0>--Nuevo--</option></select>';
echo " <input name='param6' id='param6' class='trans'  type='text' value='$rw[6]' onkeypress='return noenter();'>
	</div></td>";


// $FB->llena_texto("Ciudad:",4,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades`  where inner_estados=1)", "", "$param4", 4, 1);
//envio detalles origen

$FB->llena_texto("Ciudad Origen:", 4, 2, $DB, "(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1)", "buscarservicio(this.value,param11.value,param113.value,\"Compra\");", "$param4", 4, 1);
//$FB->llena_texto("Direccion:",5, 1, $DB, "", "", $rw[5], 1, 0);

@$direcc = explode("&", $rw[5]);
@$param5 = $direcc[0];
@$param51 = $direcc[1];
@$param19 = $direcc[2];
@$param20 = $direcc[3];
@$param23 = $direcc[4];

echo "<tr bgcolor='#FFFFFF' ><td>Lugar de Recogida:</td>
	<td align='left' ><select class='trans'  name='param5' id='param5' >";
echo "<option  value=''>Seleccione...</option>";
$sql = "SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
$LT->llenaselect($sql, 1, 1, $param5, $DB);
echo "</select>
	<input name='param51' id='param51' class='trans'  type='text' value='$param51' onkeypress='return noenter();'>
	</td>";

echo "<td></td>
	<td align='left' ><select class='trans'  name='param19' id='param19' >";
echo "<option  value='0'>Seleccione...</option>";
$sql = "SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
$LT->llenaselect($sql, 1, 1, $param19, $DB);
echo "</select>
	<input name='param20' id='param20' class='trans'  type='text' value='$param20' onkeypress='return noenter();'>
	</td>
	
	</tr>";

$FB->llena_texto("Barrio:", 23, 1, $DB, "", "", $param23, 17, 0);
$FB->llena_texto("Hora de Recogida:", 14, 1, $DB, "", "", $rw[14], 4, 0);
$FB->llena_texto("Email:", 3, 111, $DB, "", "", $rw[3], 1, 0);

$FB->titulo_azul1("Servicio", 9, 0, 5);

$FB->llena_texto("Tipo de paquete:", 12, 82, $DB, $paquete, "", @$rw[12], 1, 0);
$FB->llena_texto("Dice contener:", 13, 1, $DB, "", "", $rw[13], 4, 0);
//$FB->llena_texto("Servicio:",15,82, $DB, $Servicio, "",@$rw[15],1, 1);

$FB->llena_texto("Valor de Prestamo:", 16, 118, $DB, "", "", $rw[16], 17, 0);
$FB->llena_texto("param30", 1, 13, $DB, "", "", "0", 5, 0); //valor disponible
$FB->llena_texto("param26", 1, 13, $DB, "", "", "0", 5, 0); //valor valor aprobado

if ($id_sedes != 1 or $nivel_acceso == 1) {
	$FB->llena_texto("Abono:", 17, 118, $DB, "", "", $rw[17], 4, 0);
} else {

	$FB->llena_texto("Abono:", 29, 118, $DB, "", "", $rw[17], 4, 2);
	$FB->llena_texto("param17", 1, 13, $DB, "", "", "$rw[17]", 5, 0);
}
if (@$rw[18] == '') {
	$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
	$DB1->Execute($sql12);
	$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
	$seguro=$porcentaje['0'];
} else {
	$seguro = $rw[18];
}

$FB->llena_texto("Seguro:", 18, 126, $DB, "", "$seguro", $seguro, 1, 1);
$FB->llena_texto("&iquest;Servicio con Retorno?:", 25, 212, $DB, "", "3", "$rw[20]", 4, 0);
$valortservicio = 0;

if ($id_param != '') {

	$sql21 = "select gui_tiposervicio from guias where gui_idservicio=$id_param";
	$DB->Execute($sql21);
	$valortservicio = $DB->recogedato(0);

	$sql5 = "SELECT `rel_nom_credito` FROM `rel_sercre` WHERE `idservicio`=$id_param";
	$DB->Execute($sql5);
	$nombrecredito = $DB->recogedato(0);
}

// $FB->llena_texto("Tipo de Servicio:",27,279, $DB, "(SELECT `idtiposervicio`,`tip_nom` FROM `tiposervicio`)", "","$valortservicio", 1, 1);
// $FB->llena_texto("Tipo de servicio: $descripcion",37,279,$DB,"(SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$valor20 and pre_idciudaddes=$valor21 and tip_pagina ='si' order by tip_nom)","cambioo3(param4.value,param11.value,this.value, \"cotiza_clientes.php\", 1);",$valor3,17,1);

if ($rw[24] ==2) {
	$creditos = 1;
} else {
	$creditos = 0;
}
$FB->llena_texto("&iquest;Credito?:", 28, 216, $DB, "3", "creditosrecolecion(this.value,24,\"llega_sub2\",\"total valor\",1,0)", "$creditos", 1, 0);

echo "<td><div id='llega_sub2' >";
if ($creditos == 1) {
	$telefonos=$param4.','.$param11;
	$FB->titulo_azul1("Credito	", 8, 0, 5);
	$FB->llena_texto("Cliente:", 113, 2, $DB, "(SELECT  cre_nombre,`cre_nombre` FROM `creditos` inner join  rel_crecli on rel_idcredito=idcreditos inner join clientesdir on idclientesdir=rel_idcliente where cli_telefono in ($telefonos) group by idcreditos )", "buscarservicio(param4.value,param11.value,this.value,'Compra');", "$nombrecredito", 1, 1);

}else{
	echo"<input id='param113' name='param113' type='hidden' value=''>";

}
echo '</td></div>';


//	if ($valortservicio == '1000') {
			echo "<tr><td style='background-color: #F5B7B1;'>Tipo de servicio(*)</td><td id='respuesta'><input id='param37' name='param37' type='hidden' value=''></td><td colspan=2 id='convenir'>";
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
$FB->llena_texto("id_param3", 1, 13, $DB, "", "", "$rw[0]", 5, 0); //idclientes destinatarios
$FB->llena_texto("id_param0", 1, 13, $DB, "", "", "$rw[19]", 5, 0); //idservicio
$FB->llena_texto("id_param1", 1, 13, $DB, "", "", "0", 5, 0);
$FB->llena_texto("id_param2", 1, 13, $DB, "", "", "$rw[21]", 5, 0); //idclientesdir

echo '<div id="cupo" class="alert alert-danger" style="display:none;"  > </div>';
$FB->cierra_tabla();

if ($boton == 'si') {
	$FB->llena_texto("", 1, 142, $DB, "Guardar", "", 0, 12, 0);
}
//echo "<a  onclick='verificar_datos()';  style='cursor: pointer;' class='btn btn-primary btn-lg' title='Recoger Paquete' role='button' >probarrr</a>";

?>
<script>
	function validar_repuesta(my_callback) {
		var carga = 0;
		var credito = document.getElementsByName("param28");
		var memory = credito[0].checked;
		//alert(credito);

		if (memory == true) {
			var nomcredito = document.getElementById("param113").value;
			var tipocredito = document.getElementById("param27").value;
			var ciudadori = document.getElementById("param4").value;
			var ciudaddes = document.getElementById("param11").value;

			datos = {
				"tipoguia": "validaprecios",
				"nomcredito": nomcredito,
				"tipocredito": tipocredito,
				"ciudadori": ciudadori,
				"ciudaddes": ciudaddes
			};
			$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos
			}).done(function(respuesta) {

				if (respuesta == 2) {
					alert("No Tiene Precios de Creditos  configurados para estas opciones.");
					// my_callback(1); 
				} else {
					var valap = document.getElementById("param26").value; // valor aprobado
					if (valap != '') {

						var valdis = document.getElementById("param30").value;
						var valor1 = document.getElementById("param16").value;
						var valor2 = document.getElementById("param17").value;
						valor2 = valor2.split('.').join('');
						var resvalor = valor1.split('.').join('');

						if (isNaN(parseInt(resvalor))) {
							valpres = 0;
						} else {
							valpres = parseInt(resvalor);
						}
						if (isNaN(parseInt(valor2))) {
							valabono = 0;
						} else {
							valabono = parseInt(valor2);
						}
						var saldofinal = valdis - valpres + valabono;

						if (saldofinal < 0) {
							alert("No Tiene cupo disponible para este Cliente, su cupo es de: " + valdis + " y el valor aprobado es de: " + valap);

							var r = confirm("Desea continuar?");
							if (r == true) {
								submitFormFunction.call(form1);
							} else {
								//txt = "You pressed Cancel!";
							}

						} else {
							var submitFormFunction = Object.getPrototypeOf(form1).submit;
							submitFormFunction.call(form1);
						}
					} else {

						var valorprestamo = document.getElementById("param16").value;
						var valorabono = document.getElementById("param17").value;
						valorprestamo = valorprestamo.split('.').join('');
						if (isNaN(parseInt(valorprestamo))) {
							valorprestamo = 0;
						} else {
							valpvalorprestamores = parseInt(valorprestamo);
						}
						valorabono = valorabono.split('.').join('');
						if (isNaN(parseInt(valorabono))) {
							valorabono = 0;
						} else {
							valorabono = parseInt(valorabono);
						}
						var porcentaje = valorprestamo * 30;
						porcentaje = porcentaje / 100;
						console.log('p1');
						//alert(valorprestamo);
						if (valorprestamo > 4000000 && valorabono < valorprestamo) {
							$("#enviarmensaje").modal("show");
							var divvalor = document.getElementById("mensajevalor2");
							divvalor.innerHTML = "<strong>Atencion!</strong> Este No es Cliente Autorizado y supera el valor de Prestamo de un Millon. </BR> COMUNIQUESE CON EL ADMINISTRADOR GRACIAS!</a>";
							return false;
						} else if (valorprestamo <= 4000000 && valorabono < porcentaje) {
							$("#enviarmensaje").modal("show");
							var divvalor = document.getElementById("mensajevalor2");
							divvalor.innerHTML = "<strong>Atencion!</strong> Este No es Cliente Autorizado, . </BR> debe tener un abono mayor 30%!</a>";
							return false;
						} else {
							console.log('no tine');
							var submitFormFunction = Object.getPrototypeOf(form1).submit;
							submitFormFunction.call(form1);
							return true;
						}

					}
				}
			});


		} else {

			console.log('ok22');
			trueorfalse2 = 0;
			var teldestino = document.getElementById("param8").value;
			var telremitente = document.getElementById("param2").value;
			var ciudadori = document.getElementById("param4").value;
			var ciudaddes = document.getElementById("param11").value;

			datos = {
				"tipoguia": "validarrepetidas",
				"teldestino": teldestino,
				"telremitente": telremitente,
				"ciudadori": ciudadori,
				"ciudaddes": ciudaddes
			};
			$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(respuesta) {

					if (respuesta > 0) {

						idservicio = respuesta;
						trueorfalse = 4;
					} else {
						trueorfalse = 0;
					}
				}
			});



			var valap = document.getElementById("param26").value;
			if (valap != '' && valap > 0) {
				var valdis = document.getElementById("param30").value;
				var valor1 = document.getElementById("param16").value;
				var valor2 = document.getElementById("param17").value;
				valor2 = valor2.split('.').join('');
				var resvalor = valor1.split('.').join('');
				if (isNaN(parseInt(resvalor))) {
					valpres = 0;
				} else {
					valpres = parseInt(resvalor);
				}
				if (isNaN(parseInt(valor2))) {
					valabono = 0;
				} else {
					valabono = parseInt(valor2);
				}
				var saldofinal = valdis - valpres + valabono;
				if (saldofinal < 0) {
					alert("No Tiene cupo disponible para este Cliente, su cupo es de: " + valdis + " y el valor aprobado es de: " + valap);
					var r = confirm("Desea continuar?");
					if (r == true) {
						//	submitFormFunction.call(form1);
						trueorfalse2 = 1;
					} else {
						//txt = "You pressed Cancel!";
						trueorfalse = 0;
						return false;
					}
				} else {
					trueorfalse2 = 1;
					/* var submitFormFunction = Object.getPrototypeOf(form1).submit;
					submitFormFunction.call(form1); */
				}
			} else {


				var valorprestamo = document.getElementById("param16").value;
				var valorabono = document.getElementById("param17").value;
				valorprestamo = valorprestamo.split('.').join('');
				if (isNaN(parseInt(valorprestamo))) {
					valorprestamo = 0;
				} else {
					valpvalorprestamores = parseInt(valorprestamo);
				}
				valorabono = valorabono.split('.').join('');
				if (isNaN(parseInt(valorabono))) {
					valorabono = 0;
				} else {
					valorabono = parseInt(valorabono);
				}
				var porcentaje = valorprestamo * 30;
				porcentaje = porcentaje / 100;

				console.log('p2');
				console.log(valorabono);
				//alert(porcentaje);
				if (valorprestamo > 6000000 && valorabono < valorprestamo) {
					$("#enviarmensaje").modal("show");
					var divvalor = document.getElementById("mensajevalor2");
					divvalor.innerHTML = "<strong>Atencion!</strong> Este No es Cliente Autorizado y supera el valor de Prestamo de 6 Millones. </BR> COMUNIQUESE CON EL ADMINISTRADOR GRACIAS!</a>";
					return false;
				} else if (valorprestamo <= 6000000 && valorabono < porcentaje) {
					$("#enviarmensaje").modal("show");
					var divvalor = document.getElementById("mensajevalor2");
					divvalor.innerHTML = "<strong>Atencion!</strong> Este No es Cliente Autorizado, . </BR> debe tener un abono mayor 30%!</a>";
					return false;
				} else {
					/* var submitFormFunction = Object.getPrototypeOf(form1).submit;
							submitFormFunction.call(form1); */
					trueorfalse2 = 1;
					//return true;
				}
			}

			if (trueorfalse == 4) {

				console.log('calling');
				pop_dis5(idservicio, "Recogidas");
				setTimeout(function() {

					var mensaje = "YA HAY UNA SERVICIO CON ESTE MISMO DESTINO, IDSERVICIO. " + idservicio + "  Desea Continuar?";
					var opcion = confirm(mensaje);
					if (opcion == true) {


						var submitFormFunction = Object.getPrototypeOf(form1).submit;
						submitFormFunction.call(form1);


					} else {
						return false;
					}
				}, 6000);

			} else {


				return true;
			}

		}
		return false;

	}

</script>