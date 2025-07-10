<?php 
require("login_autentica.php"); 
include("layout.php");

?>
<style>
	.file-button {
            display: inline-flex;
            align-items: center;
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
        }
</style>
<script>
		function cambioo2(valor, valor2,valor3,valor4)
{
    var ruta="param20="+valor+"&param21="+valor2+"&paramtipser="+valor3+"&cro="+valor4;
    $.ajax({
    
    url: 'detalle_recoleccioncomprarecogida.php',
    type: 'Get',
    data: ruta,
    }).done(function(res){

        $('#respuesta').html(res)
    });
}


timer2 =0;
function llena_datos(ex, nivel, ordby, asc)
{
	p1=document.getElementById('param31').value;
	p2=document.getElementById('param32').value;
	p3=document.getElementById('labe_param33').value;
	//alert(p3);
	console.log(p3);
	p4=document.getElementById('param34').value;
	
	if(nivel==1){
	p5=document.getElementById('param35').value;
	}else{ p5=0; }
	var pagina=0; 
	if(ordby=="undefined"){ ordby=""; }
	if(asc=="undefined" || asc=="" ){ asc="ASC"; }
	if(ex==1){
		destino="detalle_asignarx.php?p1="+p1+"&p2="+p2+"&p4="+p4;
		location.href=destino;
	}
	else {
		destino="detalle_asignar.php?param31="+p1+"&param32="+p2+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&pagina="+pagina+"&ordby="+ordby+"&asc="+asc;
		MostrarConsulta4(destino, "destino_vesr")
	}
	clearTimeout(timer2);
	timer2=setTimeout(function(){llena_datos(0,nivel,'','ASC')},600000); // 3000ms = 3s
}

  $(function () {
    $(document).on('change', '.borrar', function (event) {
		
		var valor = $(this).val();
		var descripcion=document.getElementById("des_"+$(this).attr('name')).value;
		var idservicio=document.getElementById("servicio_"+$(this).attr('name')).value;
		event.preventDefault();
		$(this).closest('tr').remove();
      	datos = {"tipoguia":"cancelar","servicio":idservicio,"descripcion":descripcion,"llego":valor};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				console.log(respuesta);

			});

    });
});

function limpiar(){

	document.getElementById("labe_param33").value = "";

}
function devolver(idservicio,des){
	
if(!confirm("Esta seguro de Devolver este registro?")) { 
		return;
}

var descripcion=document.getElementById("des_"+des).value;
console.log(descripcion);

	datos = {"tipoguia":"devolver","servicio":idservicio,"descripcion":descripcion};
		$.ajax({
				url: "guiasok.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){

				console.log('okii');
				console.log(idservicio);
				$("#"+idservicio).remove();
			});
}


function buscar_ajax(cadena){

var palabra = cadena;
var d = palabra.search("documento");
var p = palabra.search("papel");
var s = palabra.search("sobre");

var D = palabra.search("DOCUMENTO");
var P = palabra.search("PAPEL");
var S = palabra.search("SOBRE");

var Do = palabra.search("Documento");
var Pa = palabra.search("Papel");
var So = palabra.search("Sobre");

if (d > -1 ) 
{
	var nuevapal = palabra.replace('documento', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(p> -1  )
{
	var nuevapal = palabra.replace('papel', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (s > -1 )
{
	var nuevapal = palabra.replace('sobre', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}else if ( D > -1) 
{
	var nuevapal = palabra.replace('DOCUMENTO', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(P> -1  )
{
	var nuevapal = palabra.replace('PAPEL', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (S > -1)
{
	var nuevapal = palabra.replace('SOBRE', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}else if ( Do > -1) 
{
	var nuevapal = palabra.replace('Documento', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}else if(Pa> -1  )
{
	var nuevapal = palabra.replace('Papel', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;
}
else if (So > -1)
{
	var nuevapal = palabra.replace('Sobre', 'archivo');
	// alert(nuevapal);
	var inputNombre = document.getElementById("param13");
	inputNombre.value = nuevapal;

}
}
</script>
<body onLoad="llena_datos(0,<?php echo $nivel_acceso;?> , '', 'ASC'); ">
<?php 

$FB->titulo_azul1("Asignar Recogida",9,0,5);  
$FB->abre_form("form1","","post");
$posicion=1;


if($param35!=''){ $id_ciudad=$param35;  $conde2=" and cli_idciudad=$id_ciudad"; $conde22=" and usu_idsede=$id_ciudad";  }   else {  $id_ciudad=$id_sedes; }
if($nivel_acceso==1){
		$posicion=4;
	
	$FB->llena_texto("Sede Origen:",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes  )", "", "$id_sedes", 17, 0);
}
else {
	
	$idcidades=ciudadesedes($id_sedes,$DB);
	if($idcidades=='0'){
		$conde1="";
	}else {
	  $conde2=" and  cli_idciudad in $idcidades "; 	
	}	
	$conde22=" and usu_idsede=$id_sedes"; 
}

//echo "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE  (usu_estado=1 or usu_filtro=1) $conde22";

$FB->llena_texto("Reasignar:",34,82,$DB,$reasignar,"",$param4,$posicion,0);
$FB->llena_texto("Busqueda por:",31,82,$DB,$busqueda,"",$param1,1,0);
$FB->llena_texto("Dato:", 32, 1, $DB, "", "","$param2", 4,0);
$sqlo="SELECT `usu_nombre`,zon_nombre FROM  seguimiento_user inner join zonatrabajo on seg_idzona=idzonatrabajo  inner join  `usuarios` on idusuarios=seg_idusuario inner join ciudades c on c.inner_sedes=usu_idsede WHERE `roles_idroles` in (2,3,5) and seg_fechaalcohol='$fechaactual' and (usu_estado=1 or usu_filtro=1) and c.idciudades=$id_ciudad and `seg_motivo`='Ingreso' order by usu_nombre ";
//$sql="SELECT `usu_nombre` FROM `usuarios` WHERE  (usu_estado=1 or usu_filtro=1) $conde22";
$FB->llena_texto("Operario:",33,84,$DB, "$sqlo", "", "$param33", 1,0);
echo '<td><button type="button" class="btn btn-info" onclick="limpiar();">Limpiar</button></td>';
$FB->llena_texto("", 37, 277, $DB, "", "", "llena_datos(0, $nivel_acceso, \"id_nombre\", \"ASC\");",4,0);
echo "</table><table>";
$FB->div_valores("destino_vesr",6); 





include("footer.php");
?>
<script>
	function asignarPaquete(){
		var param1 =document.getElementById('param1').value;
		var param2 =document.getElementById('param2').value;
		// var param3 =document.getElementById('param3').value;
		var id_usuario =document.getElementById('id_usuario').value;
		var id_param2 =document.getElementById('id_param2').value;
		var id_param =document.getElementById('id_param').value;
		var condicion =document.getElementById('condicion').value;

		if (param1 == '' || param1 == '0'||param2 == '' || param2 == '0') {
			alert('Hay un campo vacío');
		} else {
			$.ajax({
			type: 'POST',
			url: 'asignarGuias.php', // Cambia esto por la ruta correcta del script de actualización
			data: { param1: param1,param2:param2,id_usuario:id_usuario,id_param2:id_param2,id_param:id_param,condicion:condicion },
			success: function(response) {
				console.log(response);
				if(response=='OK'){

						alert("Asignadada con exito!");

	
				}
			
			},
			error: function(error) {
				console.error(error);
			}
		});
		}
		




	}

</script>