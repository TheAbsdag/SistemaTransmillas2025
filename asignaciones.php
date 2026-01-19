<?php 
require("login_autentica.php"); 
include("layout.php");
if($param2=='') { $param2=0;  }
?>
<head>
  <script src="js/jquery.printPage.js" type="text/javascript"></script>
  <style>

        .error {
            color: red;
            font-size: 14px;
            display: none;
        }
    </style>

</head>
<body onload="<?php 
 switch ($tabla)
{
	case "Factura":
	echo "pop_dis4($id_param,'Factura');";
	break;
	
	default:
	
	break;

} 
 ?>
">

<?php 

//echo $_SESSION['usuario_rol'];
$FB->abre_form("form1","","post");
$FB->titulo_azul1("Recogidas",1,0,5);  

$conde3="";	
$conde3="and ser_idresponsable='$id_usuario'";	

$conde1=""; 

 $sql="SELECT `idservicios`,`ser_fechaentrega`,`cli_nombre`,`cli_telefono`,`cli_direccion`, `ser_destinatario`, `ser_telefonocontacto`,
 `ser_direccioncontacto`,`ciu_nombre`,`ser_prioridad`,ser_estado,ser_visto,usu_nombre,`ser_fechaasignacion`,ser_valorprestamo
 FROM serviciosdia inner join usuarios on ser_idresponsable=idusuarios   where ser_estado in (3)  $conde3  ORDER BY ser_fechaentrega $asc ";

$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p=$rw1[0];
		$va++; $p=$va%2;
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		$rw1[4]=str_replace("&"," ", $rw1[4]);
		$rw1[7]=str_replace("&"," ", $rw1[7]);
		echo "<tr style='font-size:12px;text-align:left;' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		echo "<td>";
		$valorcompra="";
		echo "<div class='alert alert-success'>";
		if($rw1[9]=="Compra"){
			$valorcompra="$ ".$rw1[14];
		}
		echo "<h4><span class='label label-warning'> $rw1[9] $valorcompra</span></h4>";

		
	//	echo "FECHA: $rw1[13]<br>";

		echo "<p  align='left'>RECOGIDA: $rw1[1] <br>";
		echo "CLIENTE: $rw1[2]<br>";
		echo "TELÉFONO: *****<br></p>";
	
	echo "<div class='alert alert-info'>DIRECCIÓN: $rw1[4]</div>";
		echo "<p  align='left'>DESTINATARIO: $rw1[5]<br>";
		//echo "TELÉFONO: $rw1[6]<br>";
		echo "CIUDAD: $rw1[8]<br></p>";
	
		
		echo "<div id='campo$va'>"; if($rw1[11]==1){ $st="SI"; $colorfondo="#074f91"; } else { $st="NO"; $colorfondo="#941727"; } 
		echo " ¿EN RUTA?<select  style='width:100px;border:1px solid #f9f9f9;background-color:$colorfondo;color:#f9f9f9;font-size:12px'  name='param$va' id='param$va'  onChange='cambio_ajax2(this.value, 71, \"campo$va\", \"$va\", 1, $id_p)'  required>";
			$LT->llenaselect_ar($st,$estado_rec);
		echo "</select>";
	
		if($rw1[11]==1){
		// echo "<a  onclick='pop_dis133($id_p,\"Recoger Paquete\")';  style='cursor: pointer;' class='btn btn-primary btn-lg' title='Recoger Paquete' role='button' >Recoger</a>";
		 }	
		 echo "</div>";
		echo "</p></div></td>";
		
		
	echo "</tr>"; 
	}


include("footer.php");
?>
    <script>
        function validarNombre() {
            let inputNombre = document.getElementById("param82").value.trim();
            let errorMensaje = document.getElementById("errorNombre");

            // Expresión regular: verifica que haya al menos dos palabras separadas por un espacio
            let regex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ]+ [A-Za-zÁÉÍÓÚáéíóúÑñ]+$/;

            if (regex.test(inputNombre)) {
                errorMensaje.style.display = "none"; // Esconde el mensaje si es válido
            } else {
                errorMensaje.style.display = "block"; // Muestra el mensaje si no es válido
            }
        }
    </script>