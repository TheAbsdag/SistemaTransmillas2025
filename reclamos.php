<?php 
require("login_autentica.php"); 
include("layaut2.php");

$fechaactual=date("Y-m-d");

$param34=$_REQUEST["param34"]; 
$param36=$_REQUEST["param33"];
$param38=$_REQUEST["param38"];
?>
<head>

	</head>
<body onLoad="llena_datos(0,<?php echo $nivel_acceso;?> , '', 'ASC');">
<script>


timer2 =0;
function llena_datos(ex, nivel, ordby, asc)
{
//	p1=document.getElementById('param31').value;
//	
	p1=0;
	p3=document.getElementById('param33').value;
	p4=document.getElementById('param34').value;
	p5=document.getElementById('param35').value;
	CantidadMostrar=document.getElementById('CantidadMostrar').value;
	p8=document.getElementById('param38').value;
	pag=document.getElementById('pag').value;
	if(pag==''){pag=1; }
	

	var pagina=0; 
	if(ordby=="undefined"){ ordby=""; }
	if(asc=="undefined" || asc=="" ){ asc="ASC"; }
// $cons =0;
	if(ex==1){
   		
		destino="detalle_reclamosxl.php?p1="+p1+"&p2="+p2+"&p4="+p4;
		location.href=destino;
	}else{


		destino="detalle_reclamos.php?param33="+p3+"&param34="+p4+"&param35="+p5+"&param38="+p8+"&CantidadMostrar="+CantidadMostrar+"&pag="+pag+"&pagina="+pagina+"&ordby="+ordby+"&asc="+asc;
		MostrarConsulta4(destino, "destino_vesr")
	 }
//"&pag="+pag+
	//"&CantidadMostrar="+CantidadMostrar+ 
	clearTimeout(timer2);
	timer2=setTimeout(function(){llena_datos(0,nivel,'','ASC')},600000); // 3000ms = 3s
     }


/* $(document).ready(function() {
    $('#datatable').DataTable();
} ); */
</script>
<?php 

//echo $_SESSION['usuario_rol'];
$FB->abre_form("form1","","post");
 if($nivel_acceso==1 or $nivel_acceso==12){
	 
//	if($rcrear==1) { $FB->nuevoname("reclamos", $condecion, "Inasistencia"); }
}
if($rcrear==1) { $FB->nuevoname("reclamos", $condecion, "Ingrese el Reclamo"); }

 


$FB->titulo_azul1("Reclamos",9,0,5);  


//$FB->llena_texto("Sede :",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "", "$param35",1, 0);
$FB->llena_texto("Estado de Reclamo:", 34, 82, $DB, $estadoreclamo, "", "$param34", 1, 0);
$FB->llena_texto("Tipo de Reclamo:",33,82, $DB, $tiporeclamo, "", "$param33", 4, 0);
$FB->llena_texto("Clientes Dif:",38,82, $DB, $clienteP, "", "$param38", 1, 0);
$FB->llena_texto("No. Guia:",35,1, $DB, "", "","", 4, 0);


// $FB->llena_texto("Busqueda por:",1,82,$DB,$busqueda,"",$param1,1,0);
// $FB->llena_texto("Dato:", 2, 1, $DB, "", "","$param2", 4,0);
// $FB->llena_texto("", 3, 142, $DB, "BUSCAR", "","", 1, 0);//////////////

$FB->llena_texto("", 37, 277, $DB, "", "", "llena_datos(0, $nivel_acceso, \"id_nombre\", \"ASC\");",1,0);
$FB->div_valores("destino_vesr",12); 

$FB->cierra_form(); 

if(isset($_REQUEST["CantidadMostrar"])){ $CantidadMostrar=$_REQUEST["CantidadMostrar"]; } else { $CantidadMostrar=50; } 
 $pag=$_REQUEST['pag'];
 
 

echo "<td colspan='1' width='0' align='center' ><a id='link' onclick='pop_dis16(\"$id_p\",\"agregadocumentos\",\"$rw1[15]\")';   title='Pago Reclamo' ></td>";

echo '<input type="hidden" name="CantidadMostrar" id="CantidadMostrar" value='.$CantidadMostrar.'>';
echo '<input type="hidden" name="pag" id="pag" value='.$pag.'>';


// echo $LT->llenadocs3($DB, "cargadocumentos", $rw1[0], 1, 35, 'Ver Foto Pago');


 // $varaler= 'Perdida';
 //  $sql = "SELECT count(*) FROM `reclamos`";
 // $DB->Execute($sql); 
 // $valor=$DB->recogedato(0);

//  $sql1 = "SELECT count(rec_tipo) FROM `reclamos` WHERE rec_estado='confirmar' ";
 $sql1 = "SELECT count(*) FROM `reclamos` inner join servicios on rec_idservicio=idservicios WHERE idreclamos>0 and `rec_estado`= 'Confirmar' ORDER BY idreclamos";

 $DB1->Execute($sql1); 
 $valor1=$DB1->recogedato(0);


 // $resultado_contar = mysql_query($sql);
 // $contados = mysql_result($resultado_contar,0,'COUNT');

 
 // echo 'Número de total de registros: ' . $valor;

 	
?>

<button type="button" class="btn btn-danger" style="background: #E74C3C">Nuevos _ <?=$valor1;?> </button>

<a class="btn btn-primary" href="uploaded/politicapdf6735738.pdf" role="button">Contrato cliente</a>
<a class="btn btn-primary" href="uploaded/RECIBO PAGO COMERCIAL.docx" role="button">Recibo de pago comercial</a>
<a class="btn btn-primary" href="uploaded/RECIBO_DE_PAGO_COMERCIALdocx8790895docx3171166.docx" role="button">Recibo pago comercial</a>
<a class="btn btn-primary" href="#" role="button">Plantilla 4</a>


<?php


include("footer.php");

?>
