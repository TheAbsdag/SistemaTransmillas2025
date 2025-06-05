<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 
?>
 <script>
$(function () {
    $(document).on('change', '.borrar', function (event) {
		
		var valor = $(this).val();
		var descripcion=document.getElementById("des_"+$(this).attr('name')).value;
		var idservicio=document.getElementById("servicio_"+$(this).attr('name')).value;
		var piezasg=document.getElementById("piezasg_"+$(this).attr('name')).value;
		var guia=document.getElementById("guia_"+$(this).attr('name')).value;

		event.preventDefault();
		$(this).closest('tr').remove();
      	datos = {"tipoguia":"validar","servicio":idservicio,"descripcion":descripcion,"llego":valor,"piezasg":piezasg,"guia":guia};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				
			});

		
    });
});
</script>
<?php 
 if($nivel_acceso==1 or $nivel_acceso==10 or $nivel_acceso==9){ $conde2="";  	 } else {  $conde2=" and idsedes=$id_sedes"; }
if($param5!=''){ $id_sedes=$param5;  } 

$FB->nuevo("", "$id_sedes", "validar_guias.php");
$FB->abre_form("form1","","post");
 
 $conde3="";
if($param4==''){ $param4=0;   } else { 

$idcidades=ciudadesedes($param4,$DB);
if($idcidades=='0'){
	$conde3="and cli_idciudad=0";

}else {
  $conde3=" and cli_idciudad in $idcidades "; 	
} 
  }

$conde1=" and inner_sedes=$id_sedes    ";  
$FB->titulo_azul1("Validar las Guias Enviadas",10,0, 5);  


$FB->llena_texto("Sede:",5,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio4(\"param4\",\"param5\",\"guiasvalida.php\")", "$id_sedes", 1, 0);
$FB->llena_texto("Sede De Envio:",4,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes )", "cambio4(\"param4\",\"param5\",\"guiasvalida.php\")", "$param4", 4, 0);
$FB->llena_texto("Busqueda por:",1,82,$DB,$busqueda1,"",$param1,17,0);
$FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2",4,0);
$FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);

$FB->titulo_azul1("Guia",1,0,7); 
$FB->titulo_azul1("Pre-Guia",1,0,0); 
$FB->titulo_azul1("Tipo PQ",1,0,0); 
$FB->titulo_azul1("Piezas",1,0,0); 
$FB->titulo_azul1("Pieza #",1,0,0); 
$FB->titulo_azul1("Descripcion",1,0,0); 
$FB->titulo_azul1("Comentario",1,0,0); 
$FB->titulo_azul1("¿En?",1,0,0); 
$FB->titulo_azul1("¿Llego?:",1,0,0); 
$FB->titulo_azul1("Soporte encautacion si aplica",1,0,0); 


$conde2=""; 

if($param2!="" and $param1!=""){ 
 $conde2="and $param1 like '%$param2%' "; 
  }else { $conde2="  "; } 

$sql="SELECT `idservicios`, `ser_consecutivo`,`ser_tipopaquete`,`ser_paquetedescripcion`, `ser_destinatario`, `ciu_nombre`,`ser_direccioncontacto`,ser_piezas,`ser_guiare`,numeropieza,ser_estado,transporta,quien_escanea,fecha_escanea
 FROM serviciosdia inner join piezasguia on ser_consecutivo=numeroguia  where ser_estado in ('6','7')  and guiallega=0  $conde1  $conde2  $conde3  ORDER BY ser_fechafinal $asc ";

$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p=$rw1[0];
		
		$va++; $p=$va%2;
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		$rw1[6]=str_replace("&"," ", $rw1[6]);
		echo "
		<td>".$rw1[1]."</td>
		<td>".$rw1[8]."</td>

		<td>".$rw1[2]."</td>
		<td>".$rw1[7]."</td>
		<td>".$rw1[9]."</td>
		
		<td>".$rw1[3]."</td>
		";
		$descrip="des_$va";
		//$FB->llena_texto("Descripcion:",$descrip,9, $DB, "", "",@$rw[1] ,1, 0);	
		echo "<td><textarea name='des_$va' id='des_$va' value='' style='width:195px; class='text' ></textarea></td>";
		$trans = "SELECT ser_transporta,ser_quien_escanea,ser_fecha_escanea FROM servicios where idservicios =$id_p";
        $DB1->Execute($trans);
        $transp = mysqli_fetch_array($DB1->Consulta_ID);
        
            echo "
            <td ><mark>" . $rw1[11] . "</mark></td>";
   
		echo "<td><div id='campo$va'>";
		echo "<select  style='width:120px;border:1px solid #f9f9f9;background-color:#074f91;color:#f9f9f9;font-size:15px'  name='$va' id='$va'   class='borrar' required>";
		$LT->llenaselect_ar("Selecccione...",$estadosguia);
		echo "</select></div><input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'></td>";
		echo "<input name='piezasg_$va' id='piezasg_$va' type='hidden'  value='$rw1[7]'>";
		echo "<input name='guia_$va' id='guia_$va' type='hidden'  value='$rw1[1]'>";
		echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis16(\"$id_p\",\"documento_encautacion\",\"$rw1[10]\")';   title='Pago Reclamo' >Subir</td>";
		

	}
echo "<input name='registros' id='registros' type='hidden'  value='$va'>";
$FB->llena_texto("tipoguia", 1, 13, $DB, "", "","sedes", 5, 0);
include("footer.php");
?>