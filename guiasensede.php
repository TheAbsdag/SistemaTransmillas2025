<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 
$DB1 = new DB_mssql;
$DB1->conectar();
if($param5!=''){ $id_sedes=$param5;     } 
if($nivel_acceso==1 OR $nivel_acceso==10){ $conde2=""; 	 } else { $conde2=" and idsedes=$id_sedes";  }
?>
<script language="javascript">
function buscarsede()
{

	p1=document.getElementById('param1').value;
	p3=document.getElementById('param3').value;
	p4=document.getElementById('param4').value;
	p5=document.getElementById('param5').value;
	p2=document.getElementById('param2').value;
	destino="guiasensede.php?param1="+p1+"&param3="+p3+"&param4="+p4+"&param2="+p2+"&param5="+p5;
	
	
	window.location=destino;
	
}
$(function () {
    $(document).on('change', '.borrar', function (event) {
		
		var valor = $(this).val();
		var descripcion=document.getElementById("des_"+$(this).attr('name')).value;
		var idservicio=document.getElementById("servicio_"+$(this).attr('name')).value;

		event.preventDefault();
		$(this).closest('tr').remove();
      	datos = {"tipoguia":"validarsede","servicio":idservicio,"descripcion":descripcion,"llego":valor};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				
			});

		
    });
});

function validarllegada(des)
{

var valorguia= document.getElementById("codigoEscaneado").value;


	var guia="";
	var trueorfalse = false;	
		datos = {"tipoguia":"validarsede","servicio":valorguia,"descripcion":"Validado Con Pistola","llego":"SI"};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(result) {
					document.getElementById("codigoEscaneado").value = "";
					$("#"+valorguia).remove();
				}
			});			
	}			

</script>

<?php

$FB->abre_form("form1","guiasok.php","post");

$conde="and usu_idsede=$id_sedes"; 
$conde1=" and inner_sedes=$id_sedes"; 
$FB->titulo_azul1("Conteo de Guias Sede",10,0, 5);  

 
$FB->llena_texto("Sede:",5,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio4(\"param1\",\"param5\",\"guiasensede.php\")", "$id_sedes", 1, 1);
$FB->llena_texto("Operario:",1,2, $DB, "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `roles_idroles` in (2,3,5) and  (usu_estado=1 or usu_filtro=1) $conde", "", $param1, 4, 1);
$FB->llena_texto("Busqueda por:",3,82,$DB,$busqueda1,"",$param3,17,0);
$FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2",4,0);



echo '<tr><td class="text">Escanear Código: </td><td align="right" ><div class="form-group">
<div class="input-group">
	<div class="input-group-addon"><i class="fa fa-barcode"></i></div>
	<input autofocus type="text" class="form-control producto" name="codigoEscaneado" id="codigoEscaneado" autocomplete="off" onchange="validarllegada(this);">
</div>
</div></td>';

$FB->llena_texto("Estado:", 4, 82, $DB, $estados, "", "$param4", 4, 0);
$conde3=""; 

if($param2!="" and $param3!=""){ 
 $conde3="and $param3 like '%$param2%' "; 
  }else { $conde3="  "; } 

echo "<tr><td><button type='button' class='btn btn-primary btn-lg' onclick='buscarsede();'>Buscar</button></td><td></td>";
echo "</tr>";


$FB->titulo_azul1("Guias",1,0,7); 
$FB->titulo_azul1("Pre-Guia",1,0,0); 
$FB->titulo_azul1("Imagen",1,0,0); 
$FB->titulo_azul1("Tipo PQ",1,0,0); 
$FB->titulo_azul1("Descripcion",1,0,0); 
$FB->titulo_azul1("Destinatario",1,0,0); 
$FB->titulo_azul1("Ciudad",1,0,0); 
$FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
$FB->titulo_azul1("Valido llegada",1,0,0); 
$FB->titulo_azul1("NO Entregada",1,0,0); 
$FB->titulo_azul1("Actualizado",1,0,0); 
$FB->titulo_azul1("Valido Sede",1,0,0); 
$FB->titulo_azul1("Comentario",1,0,0); 
$FB->titulo_azul1("Esta en Sede",1,0,0); 

$fecha=date("Y-m-d");

if($param4=="1"){ 

	$conde4=" and ser_estentrega='NO ENTREGADO EN SEDE' and ser_fechafinal>='$fecha'";

}else if($param4=="2") {
	$conde4=" and  ser_estentrega='NO EN SEDE' and ser_fechafinal>='$fecha'";
}else{
	$conde4=" and ser_fechafinal<='$fecha 23:59:59'";
}
$sql="SELECT `idservicios`, `ser_consecutivo`,`ser_tipopaquete`,`ser_paquetedescripcion`, `ser_destinatario`, `ciu_nombre`,`ser_direccioncontacto`,`ser_guiare`,ser_estado,ser_descentrega,ser_fechafinal,ser_idasignacion
 FROM serviciosdia where ser_estado in (8,11) and ser_llego='SI'  $conde1 $conde3 $conde4   ORDER BY ser_estado,ser_fechafinal DESC  ";

$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p=$rw1[0];
		
		$va++; $p=$va%2;
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		if($rw1[8]==11){ $color="#F39C12";  }
		echo "<tr class='text' bgcolor='$color' id='$rw1[1]' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		$rw1[6]=str_replace("&"," ", $rw1[6]);
		echo "
		<td>".$rw1[1]."</td>
		<td>".$rw1[7]."</td>";
		$sqlimg="SELECT ser_img_recog,ser_img_entre from servicios where idservicios=$id_p ";
		$DB1->Execute($sqlimg); 
		$img=mysqli_fetch_row($DB1->Consulta_ID);
		if ($img[0]!="") {
			echo "<td align='center' >";
					echo "<a href='imgServicios/$img[0]' target='_blank'>Ver</td>";
		}else {
			echo "<td align='center' >";
			echo "</td>";
		}
		echo "
		<td>".$rw1[2]."</td>
		<td>".$rw1[3]."</td>
		<td>".$rw1[4]."</td>
		<td>".$rw1[5]."</td>
		<td>".$rw1[6]."</td>";

		$sel="SELECT gui_validasede FROM `guias`  where gui_idservicio='$id_p' ";
		$DB1->Execute($sel);		
		$iddepe=$DB1->recogedato(0);	

		$sql5="SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE `idusuarios`='$rw1[11]' ";
		$DB1->Execute($sql5);
		$nombreuser=$DB1->recogedato(1);


		echo "
		<td>".$iddepe."</td>
		<td>".$rw1[9]."</td>
		<td>".$rw1[10]."</td>
		<td>".$nombreuser."</td>
		";

		$descrip="des_$va";
		//$FB->llena_texto("Descripcion:",$descrip,9, $DB, "", "",@$rw[1] ,1, 0);	
		echo "<td><textarea name='des_$va' id='des_$va' value='' style='width:195px; class='text' ></textarea></td>";
	
		echo "<td><div id='campo$va'>";
		echo "<select  style='width:120px;border:1px solid #f9f9f9;background-color:#074f91;color:#f9f9f9;font-size:15px'  name='$va' id='$va'   class='borrar' required>";
		$LT->llenaselect_ar("Selecccione...",$estados);
		echo "</select></div><input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'></td>";
		

	}
echo "<input name='registros' id='registros' type='hidden'  value='$va'>";
$FB->llena_texto("tipoguia", 1, 13, $DB, "", "","operador", 5, 0);
	
include("footer.php");

?>