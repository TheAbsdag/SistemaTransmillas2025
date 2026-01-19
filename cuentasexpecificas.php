<?php 
require("login_autentica.php"); 
include("layout.php");
//include("cabezote4.php"); 
?>
<head>
<style>
	.whatsapp-button {
		display: inline-flex;  /* se comporta en línea */
		align-items: center;
		background-color: #25D366;
		color: white;
		font-size: 14px;
		font-weight: bold;
		padding: 8px 12px;
		border: none;
		border-radius: 6px;
		cursor: pointer;
		box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
		transition: background 0.3s, transform 0.2s;
		margin-left: 10px; /* espacio con lo que esté antes */
	}

	.whatsapp-button:hover {
		background-color: #1ebe5d;
		transform: scale(1.05);
	}
</style>
	</head>
<body onLoad="llena_datos(0,<?php echo $nivel_acceso;?> , '', 'ASC'); 
 cambio_ajax2(0, 16, 'llega_sub1', 'param33', 1, <?php echo $param33;?>);
">
<script>
  let seleccionados = [];

timer2 =0;
function llena_datos(ex, nivel, ordby, asc)
{
	p1=document.getElementById('param31').value;
	p6=document.getElementById('param36').value;
	p2=document.getElementById('param32').value;
	p3=document.getElementById('param33').value;
	p7=0;
	p4=document.getElementById('param34').value;
	p5=document.getElementById('param35').value;
	p8=document.getElementById('param38').value;
	p9=document.getElementById('param40').value;
	p10=document.getElementById('param41').value;
	p11=document.getElementById('param42').value;
	
	var pagina=0; 
	if(ordby=="undefined"){ ordby=""; }
	if(asc=="undefined" || asc=="" ){ asc="ASC"; }
	if(ex==1){

		destino="detalle_cuentasgexcel.php?param31="+p1+"&param32="+p2+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&param37="+p7+"&param38="+p8;
		location.href=destino;

	}else if(ex==2){

		destino="detalle_cuentasespecificas.php?param31="+p1+"&param32="+p2+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&param37="+p7+"&param38="+p8+"&param40="+p9+"&param41="+p10+"&accion=actualizar&param42="+p11;
		MostrarConsulta4(destino, "destino_vesr")
	//	destino1="actualizarcampos.php?param31="+p1+"&param32="+p2+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&param37="+p7+"&param38="+p8+"&param40="+p9+"&param41="+p10+"&pagina="+pagina+"&ordby="+ordby+"&asc="+asc;

	}
	else {

			destino="detalle_cuentasespecificas.php?param31="+p1+"&param32="+p2+"&param33="+p3+"&param34="+p4+"&param35="+p5+"&param36="+p6+"&param37="+p7+"&param38="+seleccionados+"&param42="+p11;
			MostrarConsulta4(destino, "destino_vesr")
	
	}
	clearTimeout(timer2);
	timer2=setTimeout(function(){llena_datos(0,nivel,'','ASC')},600000); // 3000ms = 3s
}


</script>
<?php 

//echo $_SESSION['usuario_rol'];
$FB->abre_form("form1","","post");
$FB->titulo_azul1("Reporte Especifico Sedes",9,0,5);  
$FB->abre_form("form1","","post");


if($nivel_acceso==1 or $nivel_acceso==10 or $nivel_acceso==12){
	if($param35!=''){   $conde2=""; }  

}
else {	

	  $conde2.=" and idsedes='$id_sedes' "; 	
	
}
$estado['']='Seleccione...'; 
$estado['NoRecibe']='Sin recibir'; 


$FB->llena_texto("Fecha Inicinio:", 34, 10, $DB, "", "", "$fechaactual", 1, 0);
$FB->llena_texto("Fecha Final:", 36, 10, $DB, "", "", "$fechaactual", 4, 0);
$FB->llena_texto("Sede :",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio_ajax2(this.value, 16, \"llega_sub1\", \"param33\", 1, 0)", "$param35",1, 0);
//$FB->llena_texto("Sede Destino:",38,2,$DB,"(SELECT  `idsedes`,`sed_nombre` FROM sedes)", "", "$param38", 4,0);
$sqlC="SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 ";
$DB1->Execute($sqlC); 

?>

<!-- Select con multiple -->
 <td><label>Sede destino</label></td>
 <td>
<select id="param38" name="param38">
  <option value="" disabled selected>-- Selecciona una opción --</option>
  <?php
  	while($rwC=mysqli_fetch_row($DB1->Consulta_ID))
	{
		echo'<option value="'.$rwC[0].'">'.$rwC[1].'</option>';
	}
  ?>

</select>
<div id="seleccionados" style="margin-top:10px;"></div>
</td></tr>
<?php
$FB->llena_texto("Operario:", 33, 444, $DB, "llega_sub1", "", "",1,0);
$FB->llena_texto("Busqueda por:",31,82,$DB,$busqueda,"",$param1,4,0);
$FB->llena_texto("Dato:", 32, 1, $DB, "", "","$param32", 1,0);





$FB->llena_texto("Manifiesto:", 40, 1, $DB, "", "","$param40", 4,0);
$FB->llena_texto("Codigo:", 41, 1, $DB, "", "","$param41", 1,0);
$FB->llena_texto("Excel", 39, 150, $DB, "Exportar", "","llena_datos(1, $nivel_acceso, \"id_nombre\", \"ASC\");", 4, 0);
$FB->llena_texto( "Estado:", 42, 82, $DB, $estado, "","$param42", 1,0);
$FB->llena_texto("", 37, 277, $DB, "", "", "llena_datos(0, $nivel_acceso, \"id_nombre\", \"ASC\");",1,0);
echo "<td><button type='button' class='btn btn-info' onclick='llena_datos(2, $nivel_acceso, \"id_nombre\", \"ASC\");'>Guardar</button><button type='button' onclick='pop_dis5(0,\"Notificacion_Whatsapp\")' class='whatsapp-button'>Whatsapp</button></td>";

$FB->div_valores("destino_vesr",12); 

$FB->cierra_form(); 

include("footer.php");
?>
<script>
function alertaGuias(){

alert("¡Hay guias sin pesar verifique1");


}
function seleccionarTodos() {
  const estado = document.getElementById('check_todos').checked;
  const checkboxes = document.querySelectorAll('.check_hijo');

  checkboxes.forEach(chk => {
    chk.checked = estado;

    // Forzar que se dispare el evento onchange original
    chk.dispatchEvent(new Event('change'));
  });
}
function bloquearTextoArea(area) {
    const fijo = " Transmillas le informa Queremos informarle que su 🚚encomienda #_____ se encuentra ";
    if (!area.value.startsWith(fijo)) {
        area.value = fijo;
    }
}
let datosusertabla = [];
	function selecionado1(id,telefono,consecutivo,telefonoRemi) {
			var checkbox = document.getElementById(id + "s1");
			var contrato = "Prestacion";
			const data = {
				id: id,
				telefono: telefono,
				consecutivo: consecutivo,
				telefonoRemi: telefonoRemi
			};

			if (checkbox.checked) {
				// Agregar el objeto con los parámetros al array
				datosusertabla.push(data);
			} else {
				// Buscar el objeto en el array y eliminarlo
				datosusertabla = datosusertabla.filter(item => item.id !== id);
			}

			console.log("Datos User tabla:", datosusertabla);
	}

	async function sendWhatsapp() {

		const mensaje = document.getElementById("mensajeExtra").value;
		const seleccion = document.getElementById("mensajeFijo").value;

		for (const { id, telefono, consecutivo, telefonoRemi } of datosusertabla) {

			console.log(`Procesando ID: ${id}, Teléfono: ${telefono}`);

			var telefonoPrueba = "3125215864";
			// 🟢 OPCIÓN 1 — Encomienda (NO valida Navidad)
			if (seleccion === "1") {

				enviarAlertaWhat(telefono, 37, consecutivo, mensaje);
				continue;
			}

			// 🎄 OPCIÓN 2 — MENSAJE NAVIDAD
			if (seleccion === "2") {

				// 🔍 Verificar destinatario
				const respDestino = await verificarMensaje37(telefonoPrueba);

				if (!respDestino.ya_enviado) {
					enviarAlertaWhat(
						telefonoPrueba,
						37,
						"Siempre sera nuestra prioridad, Le deseamos una feliz navidad🎄 y un prospero año nuevo✨, transmillas",
						"a su servicio"
					);
				} else {
					console.log(`🎄 Mensaje Navidad YA enviado a ${telefonoPrueba}`);
				}

				// 🔍 Verificar remitente
				const respRemi = await verificarMensaje37(telefonoPrueba);

				if (!respRemi.ya_enviado) {
					enviarAlertaWhat(
						telefonoPrueba,
						37,
						"Siempre sera nuestra prioridad, Le deseamos una feliz navidad🎄 y un prospero año nuevo✨, transmillas",
						"a su servicio"
					);
				} else {
					console.log(`🎄 Mensaje Navidad YA enviado a ${telefonoPrueba}`);
				}
			}
		}

		datosusertabla = [];
		alert('Proceso de envío finalizado');
	}

	async function enviarAlertaWhat( telefono, tipo, texto1,texto2) {
		// URL de la API
		const url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

		// Datos a enviar en la solicitud
		const data = {
			texto1: texto1, // Número de guía
			telefono: telefono,    // Número de teléfono
			tipo_alerta: tipo,     // Tipo de alerta
			texto2: texto2       // ID de la guía
		};

		try {
			// Realizar la solicitud POST con fetch
			const response = await fetch(url, {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					"Authorization": "Bearer MiSuperToken123" // Si la API requiere autenticación
				},
				body: JSON.stringify(data) // Convertir los datos a JSON
			});

			// Verificar si la respuesta fue exitosa
			if (!response.ok) {
				throw new Error(`Error en la solicitud: ${response.statusText}`);
			}

			// Decodificar la respuesta
			const responseData = await response.json();
			
			// Mostrar la respuesta
			console.log("Respuesta de la API:", responseData);
				// Muestra solo el mensaje de éxito (o el campo específico que necesites)
				// if (responseData.message) {
				// 	alert(responseData.message); // Muestra solo el mensaje
				// } else {
				// 	alert("Operación realizada con éxito");
				// }
		} catch (error) {
			// Manejar errores
			console.error("Error en la solicitud:", error);
		}
	}


  const select = document.getElementById("param38");
  const divSeleccionados = document.getElementById("seleccionados");

  select.addEventListener("change", function () {
    const valor = this.value;
    const texto = this.options[this.selectedIndex].text;

    // Verificar que no esté repetido
    if (!seleccionados.includes(valor)) {
      seleccionados.push(valor);
      mostrarSeleccionado(valor, texto);
    }

    // Volver a dejar el select en el placeholder
    this.value = "";
    console.log("Array actual:", seleccionados);
  });

  function mostrarSeleccionado(valor, texto) {
    const item = document.createElement("div");
    item.setAttribute("data-id", valor);
    item.style.display = "inline-block";
    item.style.margin = "5px";
    item.style.padding = "5px 10px";
    item.style.border = "1px solid #ccc";
    item.style.borderRadius = "5px";
    item.style.background = "#f4f4f4";

    item.innerHTML = `
      ${texto} <span style="cursor:pointer;color:red;font-weight:bold;">❌</span>
    `;

    // Evento para eliminar
    item.querySelector("span").addEventListener("click", function () {
      seleccionados = seleccionados.filter(v => v !== valor);
      item.remove();
      console.log("Array actualizado:", seleccionados);
    });

    divSeleccionados.appendChild(item);
  }
	async function verificarMensaje37(telefono) {

		const formData = new FormData();
		formData.append('verificar_mensaje_37', true);
		formData.append('telefono', telefono);

		const response = await fetch('/nueva_plataforma/controller/WhatsappController.php', {
			method: 'POST',
			body: formData
		});

		return await response.json();
	}

</script>