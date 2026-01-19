<meta name="robots" content="noindex">

 <script>

function cambioo(valor, valor2, valor3, destino)
{
	destino="cotiza_clientes.php?param20="+valor+"&param21="+valor2+"&param3="+valor3;
	document.location.href=destino;
}
function cambioo1(valor)
{
	destino="cotiza_clientes.php?param11="+valor;
	document.location.href=destino;
}
function cambioo2(valor, valor2)
{
	destino="cotiza_clientes.php?param20="+valor+"&param21="+valor2;
	document.location.href=destino;
}
</script>
<?php 

 

require("connection/conectarse.php");
require("connection/funciones.php");
require("connection/arrays.php");
require("connection/funciones_clases.php");
require("connection/sql_transact.php");
require("connection/llenatablas.php");
include("cabezote1.php"); 
include("cabezote4.php"); 

$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$FB = new funciones_varias;
$QL = new sql_transact;
$LT = new llenatablas;



 $fechaactual=date("Y-m-d");
 // $nivel_acceso=$_SESSION['usuario_rol'];
 // echo$paramcambio=$_POST['param3'];
$valor1=$_REQUEST["param1"];
$valor2=$_REQUEST["param2"];
$valor3=$_REQUEST["param3"];
$valor11=$_REQUEST["param11"];
$valor20=$_REQUEST["param20"];
$valor21=$_REQUEST["param21"];

  $sql="SELECT `tip_descripcion`,`tip_nom`FROM  `tiposervicio` where idtiposervicio=$valor3";

	 $DB->Execute($sql);
	 // $descripcion=$DB->recogedato(0);
	 $rw=mysqli_fetch_array($DB->Consulta_ID);

$sql1="SELECT `idpaquetes`,`paq_nombre`,paq_precio  FROM `paquetes` where idpaquetes=$valor11 order by paq_nombre";

	 $DB1->Execute($sql1);
	 // $descripcion=$DB->recogedato(0);
	 $rw1=mysqli_fetch_array($DB1->Consulta_ID);


echo '<form action="#" method="post" enctype="multipart/form-data" name="form1" id="form1"  >';
// $FB->titulo_azul1("Consulta paquete Volumen",9,0,7);  


// $FB->llena_texto("Servicio por volumen: $descripcion",2,223,$DB,"SELECT `idpaquetes`,`paq_nombre`  FROM `paquetes` order by paq_nombre ","cambioo1(this.value,\"cotiza_clientes.php\",1);",$valor11,17,0);

// if ($rw1[0]!='') {
// 	$FB->llena_texto("<h2><mark>$rw1[2]:  </mark></2>","", 16, $DB, "", "", "", 1, 0);
	
// }









$FB->titulo_azul1("Cotizar",9,0,7);  
echo "</tr>";
$FB->llena_texto("Ciudad Origen:",4,2,$DB,"(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 and ciu_pagina='si')", "", "$valor20", 1, 1);
$FB->llena_texto("Ciudad Destino:",11,2,$DB,"(SELECT `idciudades`, `ciu_nombre` FROM `ciudades`  where inner_estados=1 and ciu_pagina='si')","cambioo2(param4.value,this.value , \"cotiza_clientes.php\", 1);",$valor21,1,1);




$FB->llena_texto("Tipo de servicio: $descripcion",37,279,$DB,"(SELECT `pre_tiposervicio`,`tip_nom` FROM `precios` inner join tiposervicio on idtiposervicio = `pre_tiposervicio` and pre_idciudadori=$valor20 and pre_idciudaddes=$valor21  AND pre_estado = 1)","cambioo(param4.value,param11.value,this.value,\"cotiza_clientes.php\",1);",$valor3,17,0);

// $FB->llena_texto("Tipo de servicio: $descripcion",37,279,$DB,"SELECT `idtiposervicio`,`tip_nom` FROM `tiposervicio` where tip_pagina='si'","cambioo(param4.value,param11.value,this.value,\"cotiza_clientes.php\",1);",$valor3,17,0);

if ($rw[0]!='') {
	$FB->llena_texto("<mark>$rw[1]:  </mark>","", 16, $DB, "", "", "$rw[0]:", 1, 0);
	
}

//echo "<div id='mensaje2'></div>";
// $FB->llena_texto("Ingresa el valor de la compra o prestamo que deseas ",17, 1, $DB, "", "", "",17, 0);
//$FB->llena_texto("Si desea hacer una compra y solicitar un prestamo<br>  Ingresa el valor de la compra o prestamo que desea",16, 118, $DB, "", "", "",17, 0);

$FB->llena_texto("param17", 1, 13, $DB, "", "", "0", 5, 0); 


$sql12="SELECT seg_nombre FROM `seguro`  order by idseguro desc limit 1";
$DB1->Execute($sql12);
$porcentaje=mysqli_fetch_array($DB1->Consulta_ID);
$seguro=$porcentaje['0'];

$FB->llena_texto("Seguro:<span style='background-color: #FCBBB4 ;'>Recuerda que el valor minimo para asegurar <br>  tu encomienda es de 100.000 y que de esto se cobrara el 1% </span>",18, 126, $DB, "", "$seguro", $seguro, 17, 0);
$FB->llena_texto("Peso KG:",26,1, $DB, "", "","$rw[24]" ,1, 1);	
// $FB->llena_texto("Volumen:",27,1, $DB, "", "","",17, 0);
// $param27=0;
$valorapagar=0;


// echo "<tr><td><button type='button'  class='btn btn-success' onclick='cotizarguiacliente(23,\"llega_sub2\")'>Consultar</button></td><td><button type='button' class='btn btn-success' onclick="window.location.href='https://wa.me/573103524119?text=Hola'">Volver al chat</button></td></tr>";
echo "<tr><td><button type='button' class='btn btn-success' onclick='cotizarguiacliente(23,\"llega_sub2\")'>Consultar</button></td><td><button type='button' class='btn btn-success' onclick=\"window.location.href='https://wa.me/573103524119?text=Hola'\">Volver al chat</button></td></tr>";
$FB->div_valores("mensaje2",6); 
$FB->div_valores("destino_vesr",6); 
// echo "<li>";
//                             echo "<a  onclick='pop_dis55(1, \"Cotizar\")'; > Cotizar</a>";
// 
                             echo "</li>";
                             echo '</form >';
                             echo $param3;

    
?>



 <script>
function cotizarguiacliente(destino, div)
{

	var cuidadori=document.getElementById("param4").value;
	var cuidaddes=document.getElementById("param11").value;
	//var prestamo=document.getElementById("param16").value;
	var abono=document.getElementById("param17").value;
	var seguro=document.getElementById("param18").value;
	var peso=document.getElementById("param26").value;
	// var volumen=document.getElementById("param27").value;
	var valortservicio=document.getElementById("param37").value;
	// var valor=$('input:radio[name=param7]:checked').val();
	// var cliente=document.getElementById("param8").value;
	//var valor=0;
	var prestamo=0;
	destino="resultadoscotizacioncl.php?param2="+cuidadori+"&param3="+cuidaddes+"&param4="+prestamo+"&param5="+abono+"&param6="+seguro+"&param7="+peso+"&param8=0&cond=23&valortservicio="+valortservicio;
	MostrarConsulta4(destino, "destino_vesr")

	
    alert('El valor mostrado acontinuacion está sujeto al volumen del paquete, es decir que el tamaño podria aumentar el valor del envio, si tiene dudas envienos su número de telefono o comuniquese a nuestros números. ');
}
// function cambioo(valor, valor2, valor3, destino)
// {
// 	destino="cotiza_clientes.php?param1="+valor+"&param2="+valor2+"&param3="+valor3;
// 	document.location.href=destino;
// }



</script>