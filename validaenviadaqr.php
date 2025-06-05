<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<?php
require("login_autentica.php"); //coneccion bade de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();
$id_nombre=$_SESSION['usuario_nombre'];
$color="#B20F08";
//Obtenemos los datos de los input
$cond="";
$guia = $_REQUEST["guia"];
$pieza = $_REQUEST["pieza"];
$ciudado = $_POST["ciudado"];
?>
<style type="text/css">
  /* .boton_personalizado{
    text-decoration: none;
    padding: 50px;
    font-weight: 50;
    font-size: 50px;
    color: #ffffff;
    background-color: #1883ba;
    border-radius: 6px;
    border: 2px solid #0016b0;
  }

  table {
	width: 20x;
	height: 50px;
} */

body {
            background-color: #f4f4f4;
        }
        .container {
            max-width: 95%;
            margin-top: 50px; 
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            background: white;
            text-align: center;
        }
        .status-box {
            font-size: 50px;
            font-weight: bold;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .status-error { background-color: #dc3545; color: white; }
        .status-warning { background-color: #ffc107; color: black; }
        .status-success { background-color: #28a745; color: white; }
        .btn-custom {
            font-size: 50px;
            padding: 15px 30px;
        }
		.mb-3{
            font-size: 50px;
            padding: 15px 30px;
        }
</style>
<body>

<div class="container">
	<?php
	//Consultamos  informacion de la guia que se esta escaneando 
	$sql="SELECT `ser_piezas`,idservicios,ser_estado,ser_desvaliguia,ser_ciudadentrega,ser_idverificadopeso,ciu_nombre,sed_color,sed_nombre FROM  `servicios` INNER JOIN ciudades on idciudades=ser_ciudadentrega inner join  sedes on inner_sedes=idsedes WHERE ser_consecutivo='$guia'  ";		
	$DB1->Execute($sql);
	$rw1=mysqli_fetch_row($DB1->Consulta_ID);


	//Capturamos la informacion de la guia en las variables
	$idser=$rw1[1];
	$piezasg=$rw1[0];// cantidad de piezas 
	$estado=$rw1[2];
	$descricion=$rw1[3];
	$inser=1;

	//Valida si existe o no la guia
	if($idser==''){
		$color="#B20F08";
		echo "<table><tr style='font-size:62px;text-align:left;' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		echo "<td>";
		echo "<div class='status-box status-error'>";
		echo "<h1><span class='label label-warning'>EL NUMERO DE GUIA NO EXISTE,  VERIFIQUE!</span></h1>";
		echo "</div>";
		echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
		echo "</tr></table>"; 
	}else {
	// Si existe la guia

		//Consultamos la guia si la pieza de la guia ya esta creada 
		$sql5="SELECT  idpiezasguia from piezasguia where numeroguia='$guia'and numeropieza='$pieza' ";		
		$DB1->Execute($sql5);
		$rw5=mysqli_fetch_row($DB1->Consulta_ID);

		if ($rw5[0]=="") {
			# code...
		

				$date = date("Y-m-d H:i:s"); 
				// Se verifica si la guia eta en estado 6 y si ya fue pesada peso verificado
				if($estado==6 and $rw1[5]==1){

						$estadog=7;


						echo "<table><tr style='font-size:32px;text-align:left;' bgcolor='$color' >";
						echo "<td>";
						echo "<div class='status-box status-success'>";
						echo "<h1><span class='label label-warning'>GUIA:</span></h1>";
						echo "<h1><span class='label label-warning'>$guia</span></h1>";
						echo "<h1><span class='label label-warning'>DESTINO: </span></h1>";
						echo "<h1><span class='label label-warning'>$rw1[8] </span></h1>";
						echo "<h1><span class='label label-warning'>pieza $pieza</span></h1>";
						echo "</div>";
						echo'
						<select class="form-select mb-3" name="tipoVehiculo" id="tipoVehiculo" required onchange="enviarDatos()">
						<option value="">Seleccione el tipo de vehículo o situacion del paquete:</option>
							<option value="Bus">Bus</option>
							<option value="Jurgon">Jurgón</option>
							<option value="En escala temporal"> En escala temporal</option>
							<option value="Devuelto a centro de distribucion">Devuelto a centro de distribucion</option>
						</select>';
						echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
						echo "</tr></table>"; 


































						// // se verifica si es una guia con mas de 1 pieza 
						// if($piezasg>1){

						// 	//Se agrega la nueva pieza
						// 	$sql="INSERT INTO `piezasguia`(`numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) values ('$guia',$pieza,'$id_nombre','$date')";
						// 	// $DB1->Execute($sql);
						// 	$idpieza=$DB1->Executeid($sql); 

						// 	//Se cuenta cuantas piezas hay de esa guia 
						// 	$sql="SELECT  count(numeropieza) from piezasguia where numeroguia='$guia' ";		
						// 	$DB->Execute($sql);
						// 	$rw2=mysqli_fetch_row($DB->Consulta_ID);

						// 	//Se verifica si el numero de piezas es igualal numero total de piezas que existen
						// 	if($rw2[0]!=$piezasg){
						// 		$inser=0;
						// 		$sql2="UPDATE `servicios` SET  `ser_fechaguia`='$fechatiempo' WHERE `idservicios`='$idser' ";			
						// 		$DB->Execute($sql2);
						// 		$color=$rw1[7];
						// 		echo "<table><tr style='font-size:32px;text-align:left;' bgcolor='$color' >";
						// 		echo "<td>";
						// 		echo "<div class='status-box status-success'>";
						// 		echo "<h1><span class='label label-warning'>GUIA:</span></h1>";
						// 		echo "<h1><span class='label label-warning'>$guia</span></h1>";
						// 		echo "<h1><span class='label label-warning'>DESTINO: </span></h1>";
						// 		echo "<h1><span class='label label-warning'>$rw1[8] </span></h1>";
						// 		echo "<h1><span class='label label-warning'>pieza $pieza</span></h1>";
						// 		echo "</div>";
						// 		echo'
						// 		<select class="form-select mb-3" name="tipoVehiculo" id="tipoVehiculo" required onchange="enviarDatos()">
						// 		<option value="">Seleccione el tipo de vehículo o situacion del paquete:</option>
						// 			<option value="Bus">Bus</option>
						// 			<option value="Jurgon">Jurgón</option>
						// 			<option value="En escala temporal"> En escala temporal</option>
						// 			<option value="Devuelto a centro de distribucion">Devuelto a centro de distribucion</option>
						// 		</select>';
						// 		echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
						// 		echo "</tr></table>"; 
						// 	}

						// }else{
						// 	//Si es solo una pieza 
						// 	$sql4="INSERT INTO `piezasguia`( `numeroguia`, `numeropieza`,`quien_escanea`,`fecha_escanea`) values ('$guia',$pieza,'$id_nombre','$date')";
						// 	// $DB1->Execute($sql4);
						// 	$idpieza=$DB1->Executeid($sql4); 
						// }

						// //Si se in sertaron ya todas la piezas 
						// if($inser==1){

						// 	$sql1="UPDATE `cuentaspromotor` SET  `cue_fecha`='$fechatiempo', cue_estado='7'  where cue_idservicio=$idser";
						// 	$DB1->Execute($sql1);			
							
						// 	$sql2="UPDATE `servicios` SET  `ser_idusuarioregistro`='$id_usuario',`ser_fechaguia`='$fechatiempo',ser_estado='7'
						// 	WHERE `idservicios`='$idser' ";			
						// 	$DB->Execute($sql2);
							
						// 	$sql3="UPDATE `guias` SET `gui_ensede`='$id_nombre',`gui_fechaensede`='$fechatiempo' WHERE `gui_idservicio`='$idser'";
						// 	$DB->Execute($sql3); 
							
						// 	$color=$rw1[7];
						// 	echo "<table ><tr style='font-size:32px;text-align:left;' bgcolor='$color' >";
						// 	echo "<td>";
						// 	echo "<div class='status-box status-success'>";
						// 	echo "<h1><span class='label label-warning'>GUIA:</span></h1>";
						// 	echo "<h1><span class='label label-warning'>$guia</span></h1>";
						// 	echo "<h1><span class='label label-warning'>DESTINO: </span></h1>";
						// 	echo "<h1><span class='label label-warning'>$rw1[8] </span></h1>";
						// 	echo "<h1><span class='label label-warning'>pieza $pieza</span></h1>";
						// 	echo "</div>";
						// 	echo'<select class="form-select mb-3" name="tipoVehiculo" id="tipoVehiculo" required onchange="enviarDatos()">
						// 	<option value="">Seleccione el tipo de vehículo o situacion del paquete:</option>
						// 		<option value="Bus">Bus</option>
						// 		<option value="Jurgon">Jurgón</option>
						// 		<option value="En escala temporal"> En escala temporal</option>
						// 		<option value="Devuelto a centro de distribucion">Devuelto a centro de distribucion</option>
						// 	</select>';
						// 	echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
						// 	echo "</td></tr></table>"; 
						
						// }

			}else if($estado==7){
				$color="#B20F08";

					echo "<table><tr bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
				echo "<td>";
				echo "<div class='status-box status-warning'>";
				echo "<h1><span class='label label-warning'>LA GUIA YA FUE ENVIADA, </span></h1>";
				echo "<h1><span class='label label-warning'> VERIFIQUE LA GUIA</span></h1>";
				echo "</div>";
				echo'
				<select class="form-select mb-3" name="tipoVehiculo" id="tipoVehiculo" required onchange="enviarDatos()">
				<option value="">Seleccione el tipo de vehículo o situacion del paquete:</option>
					<option value="Bus">Bus</option>
					<option value="Jurgon">Jurgón</option>
					<option value="En escala temporal"> En escala temporal</option>
					<option value="Devuelto a centro de distribucion">Devuelto a centro de distribucion</option>
				</select>';
				echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
				echo "</td></tr></table>"; 


			}else{
				$color="#B20F08";

					echo "<div class='status-box status-error' bgcolor='$color'>";
					echo "LA GUIA NO ESTA EN ESTADO  DE ENVIO,  VERIFIQUE LA GUIA!";
					echo "</div>";
					echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
					


			}

		}else {
			$idpieza=$rw5[0];
			$color="#B20F08";

					echo "<div class='status-box status-error' bgcolor='$color'>";
					echo "ESTA PIEZA YA FUE ESCANEADA!";
					echo "</div>";
					echo'
					<select class="form-select mb-3" name="tipoVehiculo" id="tipoVehiculo" required onchange="enviarDatos()">
					<option value="">Seleccione el tipo de vehículo o situacion del paquete:</option>
						<option value="Bus">Bus</option>
						<option value="Jurgon">Jurgón</option>
						<option value="En escala temporal"> En escala temporal</option>
						<option value="Devuelto a centro de distribucion">Devuelto a centro de distribucion</option>
					</select>';
					echo '<center><input  name="button" type="button" class="btn btn-primary btn-custom" onclick="validarYCerrar();" value="ACEPTAR" /></center>';
			
		}
	}


	?>

			


</div>
</body>
</html>

<script>
function enviarDatos(){
    var tipoVehiculo = document.getElementById("tipoVehiculo").value;
    if (tipoVehiculo === "") {
        return; // No enviar si no se ha seleccionado una opción válida
    }


    var variable2 = "<?php echo $id_nombre;?>";
    var numeroPieza = "<?php echo $pieza;?>";
    var numeroGui = "<?php echo $guia;?>";





    var datos = {
        tipoVehiculo: tipoVehiculo,
        numeroPieza: numeroPieza,
        numeroGui: numeroGui,
		variable2: variable2
    };

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "enviarpor.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Respuesta del servidor:", xhr.responseText);
			alert("Respuesta del servidor:", xhr.responseText);
        }
    };

    var parametros = "tipoVehiculo=" + encodeURIComponent(datos.tipoVehiculo) + 
                     "&numeroPieza=" + encodeURIComponent(datos.numeroPieza) + 
                     "&numeroGui=" + encodeURIComponent(datos.numeroGui)+
					 "&variable2=" + encodeURIComponent(datos.variable2);

    xhr.send(parametros);
}

function validarYCerrar() {
    var tipoVehiculo = document.getElementById("tipoVehiculo").value;
    
    if (tipoVehiculo === "") {
        alert("Por favor, seleccione un tipo de vehículo antes de continuar.");
    } else {
        window.close();
    }
}
</script>';
