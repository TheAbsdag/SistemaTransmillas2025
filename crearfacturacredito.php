<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 
$metodo=$_REQUEST["metodo"];
$FB->titulo_azul1("Crear Factura Creditos",9,0,5);  
$FB->abre_form("form1","","post");
//echo '<form action="nuevo_adminok.php" method="post" enctype="multipart/form-data" name="form1" id="form1" >';

// $fechainicial=date("01/m/Y");
$fechados= date("d-m-Y",strtotime($fechaactual."- 2 week"));
?>
<script>

var genviar2;

function agregarguia(idguia,prestamo,flete,manifiesto,idservicio)
{
total=document.getElementById('param1').value;
valortotal=Number(total || 0)+Number(prestamo || 0)+Number(flete || 0);
document.getElementById("param1").value=valortotal;
//console.log(genviar2);
var paramguia=idservicio;
genviar2.push(paramguia);

document.getElementById(idguia).style.display='none';
document.getElementById("agregar").innerHTML +="<tr id='"+idguia+"2'><td>"+idguia+"</td><td>"+prestamo+"</td><td>"+flete+"</td><td>"+manifiesto+"</td><td>"+
"<button type='button' class='btn btn-danger' "+
"onclick='borrarguia(\""+idguia+"\",\""+prestamo+"\",\""+flete+"\",\""+idservicio+"\")'>Quitar</button></td></tr>";
document.getElementById("param9").value=genviar2;
}
function borrarguia(idguia,prestamo,flete,idservicio)
{
	var guia=idguia;
	var testData = document.getElementById(idguia);
	
	if(testData!=null){
		document.getElementById(idguia).style.display='';
	}
	$("#"+guia+"2").remove();

	total=document.getElementById('param1').value;
	valortotal=Number(total || 0)-Number(prestamo || 0)-Number(flete || 0);
	document.getElementById("param1").value=valortotal;
	//var genviar2=document.getElementById("param9").value;
	//genviar2=genviar.split(",");
	var paramguia=idservicio.toString();
	var index = genviar2.indexOf(paramguia);
	if (index > -1) {
		genviar2.splice(index,1);
	}
	console.log(genviar2);
	document.getElementById("param9").value=genviar2;
}

function guardarfactura(dato)
{
 	p1=document.getElementById('param1').value;
	p2=document.getElementById('param2').value;
	//p3=document.getElementById('param3').value;
	p4=document.getElementById('param4').value;
	p5=document.getElementById('param5').value;
	p8=document.getElementById('param8').value;
	p9=document.getElementById('param9').value;
	p10=document.getElementById('param10').value;
	p11=document.getElementById('param11').value;
	if(dato=='2'){
		destino="nuevo_adminok.php?param1="+p1+"&param2="+p2+"&param4="+p4+"&param5="+p5+"&param8="+p8+"&param9="+genviar2+"&param10="+p10+"&param11="+p11+"&tabla=editarprefactura";
		document.location.href=destino;
	}else{
		valorFactura = Number(p1 || 0);
		if(p1=='' || !Number.isFinite(valorFactura) || valorFactura<=0){
			alert('El Valor de la Factura debe ser mayor a 0');
			return false;
		}
		if(p2==''){
			alert('Digite un Numero de Factura');
		}else{
			destino="nuevo_adminok.php?param1="+p1+"&param2="+p2+"&param4="+p4+"&param5="+p5+"&param8="+p8+"&param9="+genviar2+"&param10="+p10+"&param11="+p11+"&tabla=crearfactura";
		//	console.log(destino);
			document.location.href=destino;
			//window.location=destino; 
		}
	}

	
}

</script>
<body>
<?php 

	if($param4!=''){  $fechaactual1=$param4;}else{ $fechaactual1=$fechaactual; }
	if($param5!=''){  $fechaactual=$param5;}
	echo $param2;
	$FB->llena_texto("Cliente:",3, 2, $DB, "(SELECT `cre_nombre`,`cre_nombre` FROM `creditos` where cre_nombre='$param3')", "", "$param3",1,0);
	$FB->llena_texto("Fecha Factura:", 4, 10, $DB, "", "", "$fechaactual1", 4, 0);
	$FB->llena_texto("Fecha de Vencimiento:", 5, 10, $DB, "", "", "$fechaactual", 17, 0);
	$FB->llena_texto("N&#176; Factura:", 2, 1, $DB, "", "","$param2", 4,1);
	$FB->llena_texto("Valor de la Factura:", 1, 1, $DB, "", "","0", 1,1);
	$FB->llena_texto("param6", 4, 13, $DB, "", "", $param6, 5,2);
	$FB->llena_texto("tabla", 4, 13, $DB, "", "", "crearfactura", 5,2);
	$FB->llena_texto("param10", 4, 13, $DB, "", "", "$metodo", 5,2);
	$FB->llena_texto("param11", 4, 13, $DB, "", "", "$param2", 5,2);

	if($metodo=='Editar') {
		echo "<td><button type='button' class='btn btn-success'  onclick='guardarfactura(1)' >Editar Factura</button></td>";	
	}else{
		echo "<td><button type='button' class='btn btn-success'  onclick='guardarfactura(1)' >Crear Factura</button></td>";	
		echo "<td><button type='button' class='btn btn-primary'  onclick='guardarfactura(2)' >Editar Pre-Factura</button></td></td>";	

	}
	
	//	$FB->llena_texto("", 3, 142, $DB, "Crear Factura", "","", 1, 0);
$FB->div_valores("destino_vesr",12); 

$FB->cierra_form(); 

include("footer.php");


?>
<script>
	//p1=document.getElementById('param1').value;
	p1=0;
	p2=document.getElementById('param2').value;
	p3=document.getElementById('param3').value;	
	p4=document.getElementById('param4').value;
	p5=document.getElementById('param5').value;
	p6=document.getElementById('param6').value;
	metodo=document.getElementById('param10').value;

	//alert(p3);
	var pagina=0; 
	var asc="ASC";
	
	if(metodo=='Editar'){
		pagina='detalle_editarfactura.php';
	}else{
		pagina='detalle_crearfacturacreditos.php';
	}

		destino=pagina+"?param1="+p1+"&param2="+p2+"&param3="+p3+"&param4="+p4+"&param5="+p5+"&param6="+p6+"&pagina="+pagina+"&asc="+asc;
		MostrarConsulta4(destino, "destino_vesr");

		function myFunction(){
			var totalInput=document.getElementById('param7');
			var valorFacturaInput=document.getElementById("param1");
			var serviciosInput=document.getElementById("param9");
			if(!totalInput || !valorFacturaInput || !serviciosInput){
				return false;
			}
			total=totalInput.value;
			valortotal=Number(total || 0);
			valorFacturaInput.value=Number.isFinite(valortotal) ? valortotal : 0;
			var  genviar=serviciosInput.value || '';
			genviar2=genviar ? genviar.split(",").filter(Boolean) : [];

			serviciosInput.value=genviar2.join(",");
			return true;
		}
		var intentosInit=0;
		var timerInitFactura=setInterval(function(){
			intentosInit++;
			if(myFunction() || intentosInit>=40){
				clearInterval(timerInitFactura);
			}
		},200);
	//	sleep(2000);
	function copyColumnToClipboard(columnIndex) {
            const table = document.getElementById('agregar');
            let columnText = '';

            // Recorrer las filas de la tabla y obtener el texto de la columna especificada, omitiendo la primera fila (índice 0)
            for (let i = 1; i < table.rows.length; i++) { // Comenzar desde índice 1
                const cellText = table.rows[i].cells[columnIndex].innerText;
                const parts = cellText.split('-');
                if (parts.length === 2) {
                    columnText += parts[0] + '\t' + parts[1] + '\n';
                } else {
                    columnText += cellText + '\n'; // Manejar el caso donde no hay guion
                }
            }

            // Crear un elemento textarea para copiar al portapapeles
            const textarea = document.createElement('textarea');
            textarea.value = columnText;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);

            alert('Columna copiada al portapapeles');
        }
</script>
