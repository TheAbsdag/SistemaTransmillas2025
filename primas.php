
<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 
$fechaactual=date("Y-m-d");
?>
<head>
<style>
        .container{
            float: left;
            margin-right: 10px;
			display: flex;
            align-items: center;
        }
		.archivo-button {
         margin-right: 10px; /* Espacio entre el input y el botón */
        }
        .email-button {
            display: inline-flex;
            align-items: center;
            background-color: #2196F3; /* Color de fondo */
            color: white; /* Color del texto */
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
			margin-right: 10px; /* Espacio entre el input y el botón */
        }

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
			margin-right: 10px; /* Espacio entre el input y el botón */
        }
        .email-button i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }

        .file-button i {
            margin-right: 8px; /* Espacio entre el icono y el texto */
        }



        .file-button {
            background-color: #4CAF50;
        }
	
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	</head>
<body onLoad="llena_datos(0,<?php echo $nivel_acceso;?> , '', 'ASC'); 
 cambio_ajax2(<?php echo $id_sedes;?>, 16, 'llega_sub1', 'param33', 1, 0); 
">

<?php 





$FB->titulo_azul1("Nomina de Empleados",9,0,5);  

// $FB->abre_form("form1","","post");
if($nivel_acceso==1 or $nivel_acceso==12){
	if($param35!=''){   $conde2=""; }  

}
else {	
	$param35=$id_sedes;
	  $conde2.=" and idsedes='$id_sedes' "; 	
	
}
$fechacompleta=date('Y-m-d');
$mes=date('m');
$dia=date('d');
if($mes>=1 and $mes<=7){
	$mes=date('m');
$quincena1='Primera';

}else{
	$mes=date('m');
	// echo$mes = date('m', strtotime('-1 month', strtotime($fechacompleta)));
	$quincena1='Segunda';
}

$semestres['Primera']='Primero';
$semestres['Segunda']='Segundo';
// $FB->llena_texto("Año:", 34, 82, $DB, $mesd, "", "$mes", 1, 0);

$startYear = 2024; // Año inicial
$currentYear = 2025; // Año actual
for ($year = $startYear; $year <= $currentYear; $year++){
	
	$años["$year"]="$year";
	
	
}
$FB->llena_texto("A&ntildeo:", 34, 82, $DB, $años, "", "$añoA", 1, 0);
$FB->llena_texto("Semestre", 36, 82, $DB, $semestres, "", "$quincena1", 4, 0);

$FB->llena_texto("Sede :",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 and sed_principal='si' $conde2  order by sed_nombre asc  )", "cambio_ajax2(this.value, 16, \"llega_sub1\", \"param33\", 1, 0)", "$param35",1, 0);
$FB->llena_texto("Operario:", 33, 444, $DB, "llega_sub1", "", "",4,0);
// $FB->llena_texto("Motivo Ingreso:", 32, 82, $DB, $motivoingreso, "", "", 1, 0);
$FB->llena_texto("", 37, 277, $DB, "", "", "llena_datos(0, $nivel_acceso, \"id_nombre\", \"ASC\");",1,0);
// $FB->llena_texto("Tipo de Contrato:",37,82, $DB, $tipocontrato, "", "", 4, 0);
echo "<tr><td colspan='4'>

 <div  class='container'>
 <input type='file' id='comproPago' class='archivo-button'>
 <button type='button' id='enviarIds' class='email-button' ><i class='fas fa-envelope'></i>Cargar comprobante</button>
 <button type='button' class='icon-button file-button' onclick='descargarExcelPagoPrimas()'><i class='fas fa-file'></i>Excel para pagos</button>

 </div></td></tr>";

// echo "<tr><td colspan='4'>
// <div style='display: inline-block;'>
// 	<input type='file' id='comproPago'>
// 	<button class='btn btn-primary' id='enviarIds'>Cargar comprobante</button>
// </div>
// <div style='display: inline-block;'>
// 	<button class='btn btn-primary' onclick='descargarExcel()'>
// 	<svg width='20px' height='20px' viewBox='0 0 1024 1024' class='icon' version='1.1' xmlns='http://www.w3.org/2000/svg' fill='#fafafa' stroke='#fafafa'><g id='SVGRepo_bgCarrier' stroke-width='0'></g><g id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round' stroke='#CCCCCC' stroke-width='14.336000000000002'></g><g id='SVGRepo_iconCarrier'><path d='M214.5 264.6h587.4v513.9H214.5z' fill='#E1F0FF'></path><path d='M214.5 240.2h587.4v97.9H214.5zM801.9 240.2h24.5v538.4h-24.5zM190 240.2h24.5v538.4H190z' fill='#45b079'></path><path d='M214.5 460.4h587.4v24.5H214.5zM214.5 607.3h587.4v24.5H214.5z' fill='#14b344'></path><path d='M385.8 338.1h24.5v416h-24.5zM606.1 338.1h24.5v416h-24.5z' fill='#14b344'></path><path d='M214.5 754.1h587.4v24.5H214.5z' fill='#45b079'></path></g></svg>
// 	Excel parafiscales</button>
// 	<button class='btn btn-primary' onclick='descargarExcelPagoNomina()'>
// 	<svg width='20px' height='20px' viewBox='0 0 1024 1024' class='icon' version='1.1' xmlns='http://www.w3.org/2000/svg' fill='#fafafa' stroke='#fafafa'><g id='SVGRepo_bgCarrier' stroke-width='0'></g><g id='SVGRepo_tracerCarrier' stroke-linecap='round' stroke-linejoin='round' stroke='#CCCCCC' stroke-width='14.336000000000002'></g><g id='SVGRepo_iconCarrier'><path d='M214.5 264.6h587.4v513.9H214.5z' fill='#E1F0FF'></path><path d='M214.5 240.2h587.4v97.9H214.5zM801.9 240.2h24.5v538.4h-24.5zM190 240.2h24.5v538.4H190z' fill='#45b079'></path><path d='M214.5 460.4h587.4v24.5H214.5zM214.5 607.3h587.4v24.5H214.5z' fill='#14b344'></path><path d='M385.8 338.1h24.5v416h-24.5zM606.1 338.1h24.5v416h-24.5z' fill='#14b344'></path><path d='M214.5 754.1h587.4v24.5H214.5z' fill='#45b079'></path></g></svg>
// 	Excel para pagos</button></td></tr>
// </div>";
$FB->div_valores("destino_vesr",12); 

$FB->cierra_form(); 

echo"</table>";
include("footer.php");

?>


<script>





function confirmarPago(idusuario,fechaactual,fechafinal,confirmarPago,confirma,tipo){
	
	// var valorAbono=document.getElementById(valor1,fechaini,fechafin).value;
	// var valorAbono = document.getElementById(valor1).value;
	
	var select = document.getElementById(idusuario+tipo);
	var enlace = document.getElementById(tipo+idusuario);


datos = {"idUsuario":idusuario,"fechaini":fechaactual,"fechafin":fechafinal,"funcion":confirmarPago,"confirma":confirma,"tipo":tipo};
		$.ajax({
				url: "guardaNomina.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				


				if (respuesta.trim().toLowerCase() === "ok") {
					// La respuesta es "ok", realiza las acciones correspondientes
					console.log("OK");
					// Cambia el color de fondo
		
					if (confirma=="Si") {
					select.style.backgroundColor = "#28B463"; // Cambia "red" por el color que desees
					enlace.style.pointerEvents = "auto";
					}else{

						select.style.backgroundColor = "#8B0000"; // Cambia "red" por el color que desees
						enlace.style.pointerEvents = "none";
					}
				} else {
					// La respuesta no es "ok", maneja el caso de otra manera si es necesario
					console.log("error al guardar cambio");
				}
			});
			
}



timer2 =0;
function llena_datos(ex, nivel, ordby, asc)
{
//	p1=document.getElementById('param31').value;
//	
	p1=0;
	// p2=document.getElementById('param32').value;
	p3=document.getElementById('param33').value;
	p4=document.getElementById('param34').value;
	p5=document.getElementById('param35').value;
	p6=document.getElementById('param36').value;
	// p7=document.getElementById('param37').value;
	//p7=document.getElementById('param37').value;
	var pagina=0; 
	if(ordby=="undefined"){ ordby=""; }
	if(asc=="undefined" || asc=="" ){ asc="ASC"; }
	if(ex==1){
		destino="detalle_nominaxl.php?p1="+p1+"&p4="+p4;
		location.href=destino;
	}
	else {
		destino="detalle_primas.php?param31="+p1+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&pagina="+pagina+"&ordby="+ordby+"&asc="+asc;
		MostrarConsulta4(destino, "destino_vesr")
	}
	clearTimeout(timer2);
	timer2=setTimeout(function(){llena_datos(0,nivel,'','ASC')},600000); // 3000ms = 3s
}



function descargarExcel(){

alert("descargar excel");

var idusuario="ok";

    // p2=document.getElementById('param32').value;
	 p3=document.getElementById('param33').value;
	 p4=document.getElementById('param34').value;
	 p5=document.getElementById('param35').value;
	 p6=document.getElementById('param36').value;
    //  p7=document.getElementById('param37').value;




destino="excelDeNomina.php?param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6;
		// location.href=destino;
		window.open(destino, '_blank');

}
function descargarExcelPagoNomina(){

alert("descargar excel");

var idusuario="ok";

    // p2=document.getElementById('param32').value;
	 p3=document.getElementById('param33').value;
	 p4=document.getElementById('param34').value;
	 p5=document.getElementById('param35').value;
	 p6=document.getElementById('param36').value;
    //  p7=document.getElementById('param37').value;




destino="excelPagoNomina.php?param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6;
		// location.href=destino;
		window.open(destino, '_blank');

}



function enviarDesprendible(desprendible,idusuario,fechaactual,fechafinal,funcion,tipo){

	var boton = document.getElementById(tipo+idusuario+funcion);
	


	datos = {"desprendible":desprendible,"idUsuario":idusuario,"fechaini":fechaactual,"fechafin":fechafinal,"funcion":funcion,"tipo":tipo};
		$.ajax({
				url: "guardaNomina.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				


				 if (respuesta.trim().toLowerCase() === "ok") {
					boton.textContent = 'Reenviar';
				// 	// La respuesta es "ok", realiza las acciones correspondientes
				// 	console.log("OK");
				// 	// Cambia el color de fondo
		
				// 	if (confirma=="Si") {
					boton.style.backgroundColor = "#28B463"; // Cambia "red" por el color que desees
				// 	enlace.style.pointerEvents = "auto";
				// 	}else{

				// 		select.style.backgroundColor = "#8B0000"; // Cambia "red" por el color que desees
				// 		enlace.style.pointerEvents = "none";
				// 	}
				// } else {
				// 	// La respuesta no es "ok", maneja el caso de otra manera si es necesario
				// 	console.log("error al guardar cambio");
				 }
			});
}

function confirmaAdmin(idusuario,fechaactual,fechafinal,funcion,tipo,aux1) {
    var checkbox = document.getElementById(tipo+idusuario+funcion+aux1);
    var boton = document.getElementById(tipo+idusuario+"guardarCuenCobro");

	// const checkbox = document.getElementById('myCheckbox'); // Asegúrate de que el ID coincide
    if (checkbox) {
        console.log(checkbox.checked);
    } else {
        console.error("El elemento no existe en el DOM.");
    }

				if (checkbox.checked) {
					var confirma ="si";
				} else {
					var confirma ="no";
				}

	alert("deseas confirmar este documento?");
	

	datos = {"idUsuario":idusuario,"fechaini":fechaactual,"fechafin":fechafinal,"funcion":funcion,"tipo":tipo,"confirma":confirma};
		$.ajax({
				url: "guardaNomina.php",
				type: "POST",
				data: datos
			}).done(function(respuesta){
				


				if (checkbox.checked) {
					boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
				} else {
					boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
				}
			});
}

let idsSeleccionados = [];

    // Función para manejar los clics en los checkboxes
    function selecionado(iduser) {
		var checkbox = document.getElementById(iduser+"s");
        // const checkbox = event.target;
        const id = iduser;

        if (checkbox.checked) {
            // Si el checkbox está marcado, agregar el ID al array
            idsSeleccionados.push(id);
        } else {
            // Si el checkbox está desmarcado, eliminar el ID del array
            const index = idsSeleccionados.indexOf(id);
            if (index !== -1) {
                idsSeleccionados.splice(index, 1);
            }
        }

        console.log("IDs seleccionados:", idsSeleccionados);
    }

    // Obtener todos los checkboxes y agregar un event listener para manejar los clics
    const checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('click', manejarClicCheckbox);
    });



	document.getElementById('enviarIds').addEventListener('click', function() {

		// const imagenInput = document.getElementById('comproPago');
        // const imagen = imagenInput.files[0];
        // const idsSeleccionados = [1, 2, 3]; // Tu array de IDs seleccionados





		
		const imagenInput = document.getElementById('comproPago').files[0];
        // const idsSeleccionados = [1, 2, 3]; // Tu array de IDs seleccionados
        const fechaHoraini = document.getElementById('fechaactual').value;
        const fechaHorafin = document.getElementById('fechafin').value;
            var funcion ="primas";
            const formData = new FormData();
            formData.append('imagen', imagenInput);
            formData.append('ids', JSON.stringify(idsSeleccionados));
            formData.append('fechaHora1', fechaHoraini);
            formData.append('fechaHora2', fechaHorafin);
			formData.append('funcion', funcion);
            $.ajax({
                url: 'comproPago.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
					idsSeleccionados = [];
					document.getElementById('comproPago').value = '';
                    console.log('Respuesta del servidor:', data);
                    // Aquí puedes manejar la respuesta del servidor si es necesario
					alert("Imagen cargada satisfactoriamente");
					
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
    });


	function pop_Retegarantia(idusuario, tabla, fechaactual, aux1){
		// var checkbox = document.getElementById(tipo+idusuario+funcion+aux1);
		var funcion = "devolverRetegarantia";


					// if (checkbox.checked) {
					// 	var confirma ="si";
					// } else {
					// 	var confirma ="no";
					// }

		alert("Devolver "+aux1+" ?");
		

		datos = {"idUsuario":idusuario,"fechaini":fechaactual,"funcion":funcion,"valor":aux1};
			$.ajax({
					url: "guardaNomina.php",
					type: "POST",
					data: datos
				}).done(function(respuesta){
					


					// if (checkbox.checked) {
					// 	boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
					// } else {
					// 	boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
					// }
				});
     }
	 function pop_Retegarantiaresta(idusuario, tabla, fechaactual, aux1){
		// var checkbox = document.getElementById(tipo+idusuario+funcion+aux1);
		var funcion = "restarRetegarantia";


					// if (checkbox.checked) {
					// 	var confirma ="si";
					// } else {
					// 	var confirma ="no";
					// }


		
  // Selecciona el div por su id
  var div = document.getElementById(idusuario);
  

		datos = {"idUsuario":idusuario,"fechaini":fechaactual,"funcion":funcion,"valor":aux1};
			$.ajax({
					url: "guardaNomina.php",
					type: "POST",
					data: datos
				}).done(function(respuesta){
					
					// if (respuesta=="ok") {
						alert("Valor descontado");
					// }
					// Cambia el contenido del div
					div.innerHTML = "$"+respuesta;
					// if (checkbox.checked) {
					// 	boton.style.display = "inline-block"; // Muestra el botón si el checkbox está marcado
					// } else {
					// 	boton.style.display = "none"; // Oculta el botón si el checkbox no está marcado
					// }
				});
     }

	 function descargarExcelPagoPrimas(){

alert("descargar excel");

var idusuario="ok";

    // p2=document.getElementById('param32').value;
	tablaPago=document.getElementById('tablaPago').value;
	//  p4=document.getElementById('param34').value;
	//  p5=document.getElementById('param35').value;
	  p6=document.getElementById('param36').value;
    //  p7=document.getElementById('param37').value;




destino="excelPagoPrimas.php?tablaPago="+tablaPago+"&param36="+p6;
		// location.href=destino;
		window.open(destino, '_blank');

}
</script>


