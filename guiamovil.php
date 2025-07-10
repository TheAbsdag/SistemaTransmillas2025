<?php
require("login_autentica.php"); 
include("layout.php");  

$DB2 = new DB_mssql;
$DB2->conectar(); 
$nivel_acceso=$_SESSION['usuario_rol'];
$id_usuario=$_SESSION['usuario_id'];
$estadofactura='recoleccion';
if($param15==""){ $param15='Envio Oficina'; }
 $variableunica=date("Y").date("m").date("d").date("h").date("i").date("s").$id_usuario;
?>
<script>

function validarguia()
{
	var valorguia= document.getElementById("param16").value;
	var tiposervicio= document.getElementById("param34").value;
	console.log(tiposervicio);
	var precio2= document.getElementById("precio").value;
	var credito=document.getElementsByName("param28");
	var memory=credito[1].checked;

	var guia="";
	var trueorfalse;	
		datos = {"vlores":valorguia,"tipo":"1","idguia":"0"};
		$.ajax({
				url: "validarguia.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(result) {
					if(result!=null && result!=""){
						guia= result.ser_guiare;
						if(guia!=''){			
							trueorfalse=1;
						}else {
							trueorfalse=3;
						}
					}else {
						trueorfalse=3;
					}	
				}
			});

			if(tiposervicio!='1000'){
				if(precio2==0 && memory==false){ 	
					$("#enviarmensaje").modal("show"); 
								var divvalor= document.getElementById("mensajevalor2");
								divvalor.innerHTML="<strong>Atencion!</strong> NO HAY PRECIOS CONFIGURADOS PARA EL ENVIO DE ESTAS CIUDADES </BR> COMUNIQUESE CON EL ADMINISTRADOR GRACIAS!</a>";
								return false;
				}else if(memory==true){
				//	var nomcredito=document.getElementById("param113").value;
					var combo = document.getElementById("param113");
					var nomcredito = combo.options[combo.selectedIndex].text;
					var tipocredito=document.getElementById("param34").value;
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
			}
			if(trueorfalse==1){
				$("#enviarmensaje").modal("show"); 
				var divvalor= document.getElementById("mensajevalor2");
				divvalor.innerHTML="<strong>Atencion!</strong> EL NUMERO DE GUIA YA EXISTE: "+guia+" VERIFIQUE!</a>";
				return false;
		
			}else if(trueorfalse==2){
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
				url: "buscarclientesotros.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

				if (respuesta === null) {

					//console.log(JSON.stringify(respuesta));
					//document. getElementById("param1").value='';
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1, '');
					document. getElementById("id_param1").value=0;
					document. getElementById("param2").value='';
					document. getElementById("param3").value='';
					document. getElementById("param4").value='';
					document.getElementById("param5").value='0';
					document.getElementById("param51").value='';
					document.getElementById("param19").value='0';
					document.getElementById("param20").value='';
					document.getElementById("param23").value='';
					document.getElementById("param6").value='';
					//document.getElementById("0").checked = true;
					
					document. getElementById("id_param").value=0;		
				}
				else {
					cambio_ajax2(documento, 14, 'clientesdir', 'documento', 1, respuesta.cli_nombre);	
					//document. getElementById("param1").value=respuesta.cli_iddocumento;
					document. getElementById("param2").value=respuesta.cli_telefono;
					document. getElementById("param3").value=respuesta.cli_email;
					//document. getElementById("param4").value='';	
					document. getElementById("param4").value=respuesta.cli_idciudad	
					var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document.getElementById("param5").value=res[0];
					document.getElementById("param51").value=res[1];
					document.getElementById("param19").value=res[2];
					document.getElementById("param20").value=res[3];
					document.getElementById("param23").value=res[4];					
					document. getElementById("param6").value=respuesta.cli_nombre;
					
					document. getElementById("id_param2").value=respuesta.idclientesdir;					
					document. getElementById("id_param").value=respuesta.idclientes;
					document. getElementById("id_param1").value=1;
					if(respuesta.cli_clasificacion!=null){
						//document. getElementById(respuesta.cli_clasificacion).checked = true;
					}	
					 //document.getElementById('id_del_input').checked  document.form1.param7[7].value

				}

				buscarservicio(document.getElementById("param4").value,document.getElementById("param11").value,document.getElementById("param113").value,"guiamovil"); 

			});			

	}	

 } else  if(nombre=='param2'){

	 var telefono = $("input#param2").val();
	 variable2 = telefono.length; 

	if (variable2 >= 7) {
		datos = {"vlores":telefono,"tipo":"telefono"};
		$.ajax({
				url: "buscarclientesotros.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

				if (respuesta === null) {				
					if(document. getElementById("id_param1").value!=1){
					cambio_ajax2(1, 14, 'clientesdir', 'telefono', 1,'');
					}

					document. getElementById("id_param").value=0;
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
					//document. getElementById("param4").value='';
					 var res = respuesta.cli_direccion.split("&");
					 if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param5").value=res[0];
					document. getElementById("param51").value=res[1];
					document. getElementById("param19").value=res[2];
					document. getElementById("param20").value=res[3];
					document. getElementById("param23").value=res[4]; 
					//document. getElementById("param6").value=respuesta.cli_nombre;
					document. getElementById("id_param2").value=respuesta.idclientesdir;
					document. getElementById("id_param").value=respuesta.idclientes;
					if(respuesta.cli_clasificacion!=null){
						//document. getElementById(respuesta.cli_clasificacion).checked = true;
					}	

				}

			});	

			buscarservicio(document.getElementById("param4").value,document.getElementById("param11").value,document.getElementById("param113").value,"guiamovil"); 


	}
 } else  if(nombre=='param6'){	

		var idclinte = document.getElementById("param61").value;
		datos = {"vlores":idclinte,"tipo":"cliente"};
		$.ajax({

				url: "buscarclientesotros.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

					document. getElementById("param4").value=respuesta.cli_idciudad;
					//document. getElementById("param4").value='';
	 				var res = respuesta.cli_direccion.split("&");
					if (typeof res[4] === 'undefined') { res[4]=''; }
					document. getElementById("param5").value=res[0];
					document. getElementById("param51").value=res[1];
					document. getElementById("param19").value=res[2];
					document. getElementById("param20").value=res[3];
					document. getElementById("param23").value=res[4]; 
					document. getElementById("param6").value=respuesta.cli_nombre;
					document. getElementById("id_param2").value=respuesta.idclientesdir;
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
					//document. getElementById("param11").value='';

					document. getElementById("id_param0").value=respuesta.idclientesdir;
				}

				buscarservicio(document.getElementById("param4").value,document.getElementById("param11").value,document.getElementById("param113").value,"guiamovil"); 


			});

	}

 }

}; 

//`idclientes`, `cli_iddocumento`, `cli_nombre`, `cli_email`, `cli_direccion`, `cli_idciudad`, `cli_telefono`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`

</script>
<?php 
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
// echo $id_usuario;
//if($param4==''){  $param4=$id_ciudad; }  
if($nivel_acceso!=1){  $cond6=" WHERE inner_sedes='$id_sedes' and inner_estados=1"; }  else { $cond6=" Where inner_estados=1"; }
echo '<form action="recolecciondatosok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validarguia();" >';

$FB->titulo_azul1("Remitente",10,0, 5);  

$FB->llena_texto("CC / Nit:",1, $tcampod, $DB, "", "", $rw[1], 1, 0);
$FB->llena_texto("Tel&eacute;fonos :",2, $tcampot, $DB, "", "", $rw[2], 1, 1);
//$FB->llena_texto("Nombre Del Cliente:", 6, 1, $DB, "", "", $rw[6], 1, 0);
	echo  "<tr bgcolor='#FFFFFF' ><td>Remitente:</td><td colspan=1><div id='clientesdir'>";
	echo ' <select class="trans"  name="param61"  id="param61" ><option  value=0>--Nuevo--</option></select>';
	echo " <input name='param6' id='param6' class='trans'  type='text' value='$rw[6]' onkeypress='return noenter();'>
	</div></td>";

$FB->llena_texto("Ciudad:",4,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades` $cond6)", "buscarservicio(this.value,param11.value,param,param113.value,\"oficina\");", "$param4", 1, 1);
//$FB->llena_texto("Direccion:",5, 1, $DB, "", "", $rw[5], 1, 0);

@$direcc=explode("&",$rw[5]);
@$param5=$direcc[0];
@$param51=$direcc[1];
@$param19=$direcc[2];
@$param20=$direcc[3];
@$param23=$direcc[4];
	echo "<tr bgcolor='#FFFFFF' ><td>Lugar de Recogida:</td>
	<td align='left' ><select class='trans'  name='param5' id='param5' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
    $LT->llenaselect($sql,1,1, $param5, $DB);
    echo "</select>
	<input name='param51' id='param51' class='trans'  type='text' value='$param51' onkeypress='return noenter();'>
	</td>";

	echo "</tr><tr bgcolor='#F3F3F3' ><td></td>
	<td align='left' ><select class='trans'  name='param19' id='param19' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
    $LT->llenaselect($sql,1,1, $param19, $DB);
    echo "</select>
	<input name='param20' id='param20' class='trans'  type='text' value='$param20' onkeypress='return noenter();'>
	</td>
	
	</tr>";

$FB->llena_texto("Barrio:", 23, 1, $DB, "", "", $param23, 1, 0);	
$FB->llena_texto("Email:", 3, 111, $DB, "", "", $rw[3], 17	, 0);	
//$FB->llena_texto("&iquest;Credito?:", 7, 212, $DB, "", "3",$rw[7], 4, 2);	

$FB->titulo_azul1("Destinatario",9,0,5); 

$FB->llena_texto("Tel&eacute;fono:",8, $tcampot, $DB, "", "", $rw[8], 17, 1);
$FB->llena_texto("Nombre:",9, 1, $DB, "", "", $rw[9], 17, 1);
$FB->llena_texto("Ciudad:",11,2,$DB,"(SELECT `idciudades`,`ciu_nombre` FROM `ciudades`  where inner_estados=1)", "buscarservicio(param4.value,this.value,param113.value,\"oficina\");", "$rw[11]", 1, 1);

//$FB->llena_texto("Direccion del Contacto:",10, 1, $DB, "", "", $rw[10], 4, 1);

@$direcc2=explode("&",$rw[10]);
@$param10=$direcc2[0];
@$param101=$direcc2[1];
@$param21=$direcc2[2];
@$param22=$direcc2[3];
@$param24=$direcc2[4];

	echo "</tr><tr bgcolor='#F3F3F3' ><td>Direcci&oacute;n del Contacto:</td>
	<td align='left' ><select class='trans'  name='param10' id='param10' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `iddireccion`, `dir_nombre` FROM `direccion` ";
    $LT->llenaselect($sql,1,1, $param10, $DB);

    echo "</select>
	<input name='param101' id='param101' class='trans'  type='text' value='$param101' onkeypress='return noenter();'>
	</td></tr>";

	echo "<tr bgcolor='#FFFFFF' ><td>Lugar de Entrega:</td>
	<td align='left' ><select class='trans'  name='param21' id='param21' >";
	echo "<option  value='0'>Seleccione...</option>";
    $sql="SELECT `idlugar`, `lug_nombre` FROM `lugar`  ";
    $LT->llenaselect($sql,1,1, $param21, $DB);
    echo "</select>
	<input name='param22' id='param22' class='trans'  type='text' value='$param22' onkeypress='return noenter();'>
	</td>
	";

$FB->llena_texto("Barrio:", 24, 1, $DB, "", "", $param24, 1, 0);	
$FB->titulo_azul1("Servicio",9,0,5); 
$FB->llena_texto("Tipo paquete:",12,82, $DB, $paquete, "",@$rw[12], 1, 0);
$FB->llena_texto("Contiene?:",13, 1, $DB, "", "", $rw[13], 1, 0);
//$FB->llena_texto("Servicio:",15,82, $DB, $Servicio, "",@$rw[15],1, 1);

//$FB->llena_texto("Valor de Prestamo:",16, 118, $DB, "", "", $rw[16],17, 0);
$FB->llena_texto("# GUIA:",16, 1, $DB, "", "", $rw[16],1, 2);
$FB->llena_texto("# Piezas:",29, 123, $DB, "", "", "$rw[27]", 1, 'min=1');
$FB->llena_texto("Abono:",17, 118, $DB, "", "", $rw[17], 1, 2);
if(@$rw[18]==''){
	$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
	$DB1->Execute($sql12);
	$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
	$seguro=$porcentaje['0'];
} else{
	$seguro=$rw[18];
}

//$FB->llena_texto("Seguro:",18, 126, $DB, "", "", $seguro, 1, 0);
$FB->llena_texto("Seguro:",18, 126, $DB, "valorpagar(param34.value,202,\"llega_sub3\",\"total valor\",1,$id_usuario)", "$seguro", $seguro, 1, 0);
$FB->llena_texto("Peso KG:",26,1, $DB, "", "valorpagar(param34.value,202,\"llega_sub3\",\"total valor\",1,$id_usuario)","" ,17, 0);	
$FB->llena_texto("Volumen:",27,1, $DB, "", "valorpagar(param34.value,202,\"llega_sub3\",\"total valor\",1,$id_usuario)","",17, 0);	
//$FB->llena_texto("Giros $:",29,118, $DB, "", "","",1, 0);	
$FB->llena_texto("&iquest;Servicio con Retorno?:", 25, 212, $DB, "", "3","",1, 0);	
//$FB->llena_texto("Tipo de Servicio:",34,279, $DB, "(SELECT `idtiposervicio`,`tip_nom` FROM `tiposervicio`)", "","", 1, 1);
$FB->llena_texto("Tipo Pago:", 28, 213, $DB, "3", "valorpagar(this.value,201,\"llega_sub2\",\"total valor\",1,$id_usuario)","@$rw[25]",1, 1);

echo '<tr><div id="confirmacionmensaje" class="alert alert-danger" style="display:none;"  > </div>';
echo '<td align="right" class="text" colspan="2"><div id="llega_sub2">';
echo"<input id='param113' name='param113' type='hidden' value=''>";
echo '</div></td></tr>';

echo "<tr><td style='background-color: #F5B7B1;'>Tipo de servicio(*)</td><td id='respuesta'></td></tr>";
//$FB->llena_texto("Tipo Pago:", 28, 213, $DB, "3", "valorpagar(this.value,17,\"llega_sub2\",\"total valor\",1,$id_usuario)","",1, 0);
//$FB->llena_texto("Pendiente por Cobrar:",29, 5, $DB, "", "", "", 1, 1);

$FB->llena_texto("param15", 1, 13, $DB, "", "", "$param15", 5, 0); 
$FB->llena_texto("id_param", 1, 13, $DB, "", "", "$rw[0]", 5, 0); // idcliente
$FB->llena_texto("id_param0", 1, 13, $DB, "", "", "$rw[21]", 5, 0);
$FB->llena_texto("id_param1", 1, 13, $DB, "", "", "0", 5, 0);
$FB->llena_texto("id_param2", 1, 13, $DB, "", "", "$rw[19]", 5, 0);  // clientesdir
$FB->llena_texto("id_usuario", 1, 13, $DB, "", "", "$id_usuario", 5, 0);
$FB->llena_texto("variableunica", 1, 13, $DB, "", "", "$variableunica", 5, 0);

$FB->titulo_azul1("Datos quien recibe",14,0, 5); 
// $FB->llena_texto("Foto :",87, 6, $DB, "", "","", 1, 1);
echo"<tr class='text'><td><label>Foto (*)</label></td><td><input type='file' accept='image/*' id='param91' name='param91' required  capture='environment'></td></tr>";

$FB->llena_texto("Nombre:",92, 1, $DB, "", "","", 1, 1);
$FB->llena_texto("Telefono Whatsapp:",93, 1, $DB, "", "","", 1, 1);

echo '<tr><td colspan=2 align="right" class="text"><div id="llega_sub3">';
$FB->titulo_azul1("Valor",8,0,5);  
$FB->llena_texto("",111, 118, $DB, "", "","0", 1, 0);
echo '</div></td></tr></table>';

 $FB->llena_texto("", 1, 142, $DB, "Guardar", "", 0, 12, 0);
require("footer.php");
?> 
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("param26").addEventListener("input", function() {
                let valor = parseFloat(this.value);
                if (valor > 0) {
					
						alert("Verifica si este servicio tiene volumen antes de guardar");

	
                }
            });
        });
    </script>