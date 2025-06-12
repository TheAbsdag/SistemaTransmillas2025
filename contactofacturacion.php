<!DOCTYPE html>
<html>

<head>
<script>
function enviar_formulario(){
   document. getElementById("param8").value='2';
   document.form1.submit()
}

</script>
</head>
<body>

 <?php 

 $fechaactual=date("Y-m-d");
 $nivel_acceso=$_SESSION['usuario_rol'];
 $id_sedes=$_SESSION['usu_idsede'];

 if($nivel_acceso==1){
	if($param35!=''){   $conde2=""; }  

}
else {	
	$param35=$id_sedes;
	
}

echo "</tr>";
$FB->titulo_azul1("CONTACTO FACTURACION",9,0,7);  
echo "</tr>";

$FB->llena_texto("Nombre del Contacto:",1, 1, $DB, "", "", "", 17, 0);
$FB->llena_texto("Telefono 1:",2, 1, $DB, "", "", "", 4, 0);
$FB->llena_texto("Ext 1:", 3, 1, $DB, "", "", "", 1, 0);
$FB->llena_texto("Telefono 2:",4, 1, $DB, "", "", "", 4, 0);
$FB->llena_texto("Ext 2:", 5, 1, $DB, "", "", "",17, 0);
$FB->llena_texto("Celular:", 6, 1, $DB, "", "", "",4, 0);
$FB->llena_texto("Correo :", 9, 111, $DB, "", "", "",1, 0);

echo '<input type="hidden" name="param7" id="param7" value="'.$idhojadevida.'">';
echo '<input type="hidden" name="param8" id="param8" value="1">';

//echo "<tr><td><button type='submit' class='btn btn-success' formaction='newhojadevidaok.php?condecion=datosfamiliares2&idhojadevida=$idhojadevida'>Gurdar</button></td></tr>";
echo "<tr><td><button type='submit' class='btn btn-success' onclick='enviar_formulario()' >Gurdar</button></td></tr>";

$FB->titulo_azul1("Nombre del Contacto",1,0,7); 
$FB->titulo_azul1("Telefono 1",1,0,0); 
$FB->titulo_azul1("Ext 1",1,0,0); 
$FB->titulo_azul1("Telefono 2",1,0,0); 
$FB->titulo_azul1("Ext 2",1,0,0); 
$FB->titulo_azul1("Celular ",1,0,0); 
$FB->titulo_azul1("Correo",1,0,0); 
$FB->titulo_azul1("Principal",1,0,0); 
$FB->titulo_azul1("Correo automatico",1,0,0); 
$FB->titulo_azul1("Actualizacion de datos",1,0,0); 
$FB->titulo_azul1("Eliminar",1,0,0);


$sql="SELECT `idcontactofacturacion`, `con_nombre`, `cont_telefono1`, `cont_ext1`, `cont_telefono2`, `cont_ext2`, `cont_celular`, `cont_correo`, `cont_fecharegistra`, con_principal,con_correo_automatico, actualizacion_datos FROM `contactofacturacion` WHERE  cont_idhojavida=$idhojadevida";

$DB->Execute($sql); 
$va=0; 

	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
				$id_p=$rw1[0];
				$va++; $p=$va%2;
				if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
				echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				echo "<td>".$rw1[1]."</td>
				<td>".$rw1[2]."</td>
				<td>".$rw1[3]."</td>
				<td>".$rw1[4]."</td>
				<td>".$rw1[5]."</td>
				<td>".$rw1[6]."</td>
				
				<td>".$rw1[7]."</td>";	

				if ($rw1[9]=="1") {
				echo "<td><input type='checkbox' id='$id_p' name='$id_p' checked onclick='sendData(this)'></td>";	

				}else{
				echo "<td><input type='checkbox' id='$id_p' name='$id_p' onclick='sendData(this)'></td>";	
				}
				if ($rw1[10]=="si") {
					echo "<td><input type='checkbox' id='".$id_p."auto' name='".$id_p."auto' checked onclick='selecionado($id_p)'></td>";	
	
					}else{
					echo "<td><input type='checkbox' id='".$id_p."auto' name='".$id_p."auto' onclick='selecionado($id_p)'></td>";	
				}

				if ($rw1[11]=="si") {
					echo "<td><input type='checkbox' id='".$id_p."_actualiza' name='".$id_p."_actualiza' checked onclick='marcarActualizacion($id_p)'></td>";	
				} else {
					echo "<td><input type='checkbox' id='".$id_p."_actualiza' name='".$id_p."_actualiza' onclick='marcarActualizacion($id_p)'></td>";	
				}
				$DB->edites($id_p, "contactofacturacion", 2,"$idhojadevida");
	}
	


	$FB->titulo_azul1(" ------ ",1,0,10); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 
	$FB->titulo_azul1(" ------",1,0,0); 

?> 
<script>
	        let idsSeleccionados = [];

// Función para manejar los clics en los checkboxes
function selecionado(iduser) {
    var checkbox = document.getElementById(iduser + "auto");
    var id = iduser;
    var tipo = checkbox.checked ? "si" : "no";

    let formData = new FormData();
    formData.append("idcon", id);
    formData.append("tipo", tipo);

    fetch("contactofacturaemailauto.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Puedes ajustar esto según la respuesta que esperes
    .then(data => {
        console.log("Respuesta del servidor:", data);
        // Aquí puedes mostrar una notificación si quieres
    })
    .catch(error => {
        console.error("Error al enviar el dato:", error);
    });
}
function marcarActualizacion(idContacto) {
    var check = document.getElementById(idContacto + "_actualiza");
    var id = idContacto;
    var estado = check.checked ? "si" : "no";

    let datos = new FormData();
    datos.append("id_contacto", id);
    datos.append("estado_actualizacion", estado);

    fetch("confirmaciondecorreo.php", {
        method: "POST",
        body: datos
    })
    .then(response => response.json())
    .then(respuesta => {
        console.log("Respuesta del servidor:", respuesta);
        // Aquí puedes mostrar una notificación si deseas
    })
    .catch(error => {
        console.error("Error al enviar el dato:", error);
    });
}


</script>
</body>
</html>

