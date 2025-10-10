<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 

$FB->nuevo("", "$param5", "asignar_guiassede.php");
$FB->abre_form("form1","guiasok.php","post");
?>
<script language="javascript">
function buscarsede()
{

	p1=document.getElementById('param1').value;
	p2=document.getElementById('param2').value;
	p5=document.getElementById('param5').value;
	p4=document.getElementById('param4').value;
	destino="guiassede.php?param1="+p1+"&param2="+p2+"&param4="+p4+"&param5="+p5;
	
	
	window.location=destino;
	
}
</script>

<?php


if($param5!=''){ 
			$id_sedes=$param5; 
			$idcidades=ciudadesedes($param5,$DB);
			if($idcidades=='0'){
				$conde1="";

			}else {
			  $conde1=" and cli_idciudad in $idcidades "; 	
			}
  } else {  
  
		$idcidades=ciudadesedes($id_sedes,$DB);
		if($idcidades=='0'){
			$conde1="";

		}else {
		  $conde1=" and cli_idciudad in $idcidades "; 	
		}


  }
if($nivel_acceso==1 or $nivel_acceso==10){ $conde2="";  	 } else {  $conde2=" and idsedes=$id_sedes"; }
 
 
 $conde3="";
if($param4==''){ $param4=0;   } else { $conde3=" and inner_sedes=$param4";   }




$FB->titulo_azul1("Guias Enviadas A Otras Sedes",10,0, 5);  

//$FB->llena_texto("Mensajero:",1,2, $DB, "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `roles_idroles` in (2,3,5)", "cambio2(this.value,\"guias.php\",\"Usuario\")", $rw[1], 1, 1);
$FB->llena_texto("Sede Origen:",5,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio4(\"param4\",\"param5\",\"guiassede.php\")", "$id_sedes", 1, 1);
$FB->llena_texto("Sede Destino:",4,2,$DB,"(SELECT  `idsedes`,`sed_nombre` FROM sedes )", "cambio4(\"param4\",\"param5\",\"guiassede.php\")", "$param4", 4, 1);
$FB->llena_texto("Busqueda por:",1,82,$DB,$busqueda1,"",$param1,17,0);
$FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2",4,0);


 //$FB->llena_texto("Buscar", 1, 142, $DB, "Buscar", "onclick=form3.submit();", 0, 12, 0);
echo "<tr><td><button type='button' class='btn btn-primary btn-lg' onclick='buscarsede();'>Buscar</button></td><td></td>";
echo "<td><button type='submit' class='btn btn-danger btn-lg' >Enviar</button></td><td></td><tr>";
//$FB->llena_texto("", 3, 133, $DB, "Guardar", "onclick=form1.submit();","", 4, 0);


$FB->titulo_azul1("Guia",1,0,7); 
$FB->titulo_azul1("Pre-Guia",1,0,0);
$FB->titulo_azul1("Pieza #",1,0,0);
$FB->titulo_azul1("Tipo PQ",1,0,0); 
$FB->titulo_azul1("Descripcion",1,0,0); 
$FB->titulo_azul1("Destinatario",1,0,0); 
$FB->titulo_azul1("Ciudad",1,0,0); 
$FB->titulo_azul1("¿Se va?",1,0,0); 
$FB->titulo_azul1("Quien escaneo",1,0,0); 
$FB->titulo_azul1("Fecha escaneo",1,0,0); 

$FB->titulo_azul1("Enviar",1,0,0); 


$conde=""; 

if($param2!="" and $param1!=""){ 
 $conde="and $param1 like '%$param2%' "; 
  }else { $conde="  "; } 

//SELECT `idservicios`, `ser_consecutivo`,`ser_tipopaquete`,`ser_paquetedescripcion`, `ser_destinatario`, `ciu_nombre`,`ser_direccioncontacto`,`ser_guiare`,ser_piezas FROM serviciosdia where ser_estado='6' and ser_idverificadopeso=1 and cli_idciudad in (1,15,16,17,18,19,29,37,47,55,71,72,77,79,82,83,84,85,89,90,91,96,97,100,103,105,107,108,109,110,112) ORDER BY ser_fechafinal ASC

 $sql="SELECT `idservicios`, `ser_consecutivo`,`ser_tipopaquete`,`ser_paquetedescripcion`, `ser_destinatario`, `ciu_nombre`,`ser_direccioncontacto`,`ser_guiare`,ser_piezas 
 FROM serviciosdia  where ser_estado='6' and  ser_idverificadopeso=1  $conde1 $conde  $conde3   ORDER BY ser_fechafinal $asc  ";

$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
	
		$id_p=$rw1[0];
		
		if($rw1[8]>1){

			$transporta="";
			$quienescaneo="";
			$fechaescaneo="";
			$piezaenviada=array();
				$sqll1="SELECT `idpiezasguia`, `numeroguia`, `numeropieza`, `transporta`,`quien_escanea`,fecha_escanea FROM `piezasguia` WHERE numeroguia='$rw1[1]'";
				$DB1->Execute($sqll1);
				while($datos=mysqli_fetch_row($DB1->Consulta_ID))
				{
					$piezaenviada[]=$datos[2];
					$numerospezas=$datos[2];
					$transporta=$datos[3];
					$quienescaneo=$datos[4];
					$fechaescaneo=$datos[5];
					$idpieza=$datos[0];
				}
				if($rw1[8]==$numerospezas){  }
				for($a=1;$a<=$rw1[8];$a++){

					if (in_array($a, $piezaenviada)) { //verificar que la guia no este en la tabla piezasguia
						
					}else{

					$va++; $p=$va%2;
					if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
					echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
					$rw1[6]=str_replace("&"," ", $rw1[6]);
					echo "
					<td>".$rw1[1]."</td>
					<td>".$rw1[7]."</td>
					<td>".$a."</td>
					<td>".$rw1[2]."</td>
					<td>".$rw1[3]."</td>
					<td>".$rw1[4]."</td>		
					<td>".$rw1[5]."</td>";
				

					echo"<td>
					<select class='form-select mb-3' name='tipoVehiculo' id='tipoVehiculo'  onchange=\"enviarDatos(this.value, '$idpieza')\">>
					<option value=''>Seleccione el tipo de vehículo o situacion del paquete:</option>
						<option value='Bus'" . ($transporta == "Bus" ? "selected" : "") . ">Bus</option>
						<option value='Jurgon'" . ($transporta == "Jurgon" ? "selected" : "") . ">Furgón</option>
						<option value='En escala temporal'" . ($transporta == "En escala temporal" ? "selected" : "") . "> En escala temporal</option>
						<option value='Devuelto a centro de distribucion'" . ($transporta == "Devuelto a centro de distribucion" ? "selected" : "") . ">Devuelto a centro de distribucion</option>
					</select></td>";
					echo "
					<td>".$quienescaneo."</td>
					<td>".$fechaescaneo."</td>
					";
					echo "<td><input type='checkbox' name='asignar_$va' id='asignar_$va' value='1' style='width:95px; class='trans' >
					<input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'>
					<input name='guia_$va' id='guia_$va' type='hidden'  value='$rw1[1]'>
					<input name='pieza_$va' id='pieza_$va' type='hidden'  value='$a'>
					<input name='piezasg_$va' id='piezasg_$va' type='hidden'  value='$rw1[8]'>
					</td></tr>";

					}
				}
			}else {
				$transporta="";
				$quienescaneo="";
				$fechaescaneo="";

				$sqll1="SELECT `idpiezasguia`, `numeroguia`, `numeropieza`, `transporta`,`quien_escanea`,fecha_escanea FROM `piezasguia` WHERE numeroguia='$rw1[1]'";
				$DB1->Execute($sqll1);
				while($datos=mysqli_fetch_row($DB1->Consulta_ID))
				{

					$transporta=$datos[3];
					$quienescaneo=$datos[4];
					$fechaescaneo=$datos[5];
					$idpieza=$datos[0];
				}
				$va++; $p=$va%2;
				if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
				echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				$rw1[6]=str_replace("&"," ", $rw1[6]);
				echo "
				<td>".$rw1[1]."</td>
				<td>".$rw1[7]."</td>
				<td>".$rw1[8]."</td>
				<td>".$rw1[2]."</td>
				<td>".$rw1[3]."</td>
				<td>".$rw1[4]."</td>		
				<td>".$rw1[5]."</td>";
				

				echo"<td>
				<select class='form-select mb-3' name='tipoVehiculo' id='tipoVehiculo'  onchange=\"enviarDatos(this.value, '$idpieza')\">
				<option value=''>Seleccione el tipo de vehículo o situacion del paquete:</option>
					<option value='Bus'" . ($transporta == "Bus" ? "selected" : "") . ">Bus</option>
					<option value='Jurgon'" . ($transporta == "Jurgon" ? "selected" : "") . ">Furgón</option>
					<option value='En escala temporal'" . ($transporta == "En escala temporal" ? "selected" : "") . "> En escala temporal</option>
					<option value='Devuelto a centro de distribucion'" . ($transporta == "Devuelto a centro de distribucion" ? "selected" : "") . ">Devuelto a centro de distribucion</option>
				</select></td>";
				echo "
				<td>".$quienescaneo."</td>
				<td>".$fechaescaneo."</td>
				";
				echo "<td><input type='checkbox' name='asignar_$va' id='asignar_$va' value='1' style='width:95px; class='trans' >
				<input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'>
				<input name='guia_$va' id='guia_$va' type='hidden'  value='$rw1[1]'>
				<input name='pieza_$va' id='pieza_$va' type='hidden'  value='1'>
				<input name='piezasg_$va' id='piezasg_$va' type='hidden'  value='$rw1[8]'>

				</td></tr>";
			}


	}
echo "<input name='registros' id='registros' type='hidden'  value='$va'>";
$FB->llena_texto("tipoguia", 1, 13, $DB, "", "","sedes", 5, 0);
include("footer.php");
?>
<script>
	function enviarDatos(tipoVehiculo,variable1) {
    // var tipoVehiculo = document.getElementById("tipoVehiculo").value;
    // if (tipoVehiculo === "") {
    //     return; // No enviar si no se ha seleccionado una opción válida
    // }

    // // Pasar las variables de PHP a JavaScript
    // var variable1 = "<?php echo $idpieza;?>";
    var variable2 = "";



    var datos = {
        tipoVehiculo: tipoVehiculo,
        variable1: variable1,
        variable2: variable2
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "enviarpor.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Respuesta del servidor:", xhr.responseText);
        }
    };

    var parametros = "tipoVehiculo=" + encodeURIComponent(datos.tipoVehiculo) + 
                     "&variable1=" + encodeURIComponent(datos.variable1) + 
                     "&variable2=" + encodeURIComponent(datos.variable2);

    xhr.send(parametros);
}
</script>