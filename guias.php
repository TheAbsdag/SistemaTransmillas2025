<?php 
require("login_autentica.php"); 
include("layout.php");
$DB1 = new DB_mssql;
$DB1->conectar();
if($param35!=''){ $id_sedes=$param35;  } 
if($nivel_acceso==1 OR $nivel_acceso==10){ $conde2=""; 	 } else { $conde2=" and idsedes=$id_sedes";  }
?>
<style>
        .container-left {
            float: left;
            margin-right: 10px; /* Espacio entre botones */
        }

        .container-right {
            float: right;
            margin-left: 10px; /* Espacio entre botones */
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
        #loading {
            display: none; /* Ocultar inicialmente */
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000; /* Asegurarse de que está por encima de otros elementos */
        }
                /* Estilos específicos para la tabla de preguntas */
        .tabla-preguntas {
            width: 100%;
            border-collapse: collapse;
        }
        .tabla-preguntas th, .tabla-preguntas td {
            border: 1px solid gray;

            padding: 8px;
            text-align: left;
        }

        .tabla-preguntas th {
            background-color: #003366; /* Azul oscuro */
            color: white; /* Letra blanca */
        }
        #loading {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000; /* Asegurarse de que está por encima de otros elementos */
        }
        .search-box {
            position: relative;
            width: 300px;
            justify-content: flex-end; /* Alinear a la derecha */
        }
        .search-box input[type="text"] {
            width: 100%;
            padding: 10px;
            padding-left: 40px; /* Espacio para el icono */
            border: 2px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .search-box .fa-search {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
</style>
<script language="javascript">

function cambioestado(valor,item){


		// var input = document.getElementById("miInput");
		// var idservicio = input.value;
		// var valor = $(this).val();
		// var descripcion=document.getElementById("des_"+$(this).attr('name')).value;
		var idservicio=document.getElementById("servicio_"+item).value;
		var piezasg=document.getElementById("piezasg_"+item).value;
		var guia=document.getElementById("guia_"+item).value;

		// event.preventDefault();
		// $(this).closest('tr').remove();
		datos = {"tipoguia":"validardevuelta","servicio":idservicio,"llego":valor,"piezasg":piezasg,"guia":guia};
		$.ajax({
			url: "guiasok.php",
			type: "POST",
			data: datos
		}).done(function(respuesta){
			
		});

		alert("Se cambio a "+valor);
}








function buscarsede()
{

	p1=document.getElementById('param31').value;
	p3=document.getElementById('param33').value;
	p5=document.getElementById('param35').value;
	p2=document.getElementById('param32').value;
	destino="guias.php?param31="+p1+"&param33="+p3+"&param32="+p2+"&param35="+p5;
	
	
	window.location=destino;
	
}
function validarllegada(des)
{

var valorguia= document.getElementById("codigoEscaneado").value;
var operador= document.getElementById("param31").value;
var param1= operador;
var ciudado= document.getElementById("param35").value;
if(ciudado==0){

	alert('Seleccione la ciudad de Destino');

}
if(operador==0){

alert('Seleccione el operario');

}else{

	var guia="";
	var trueorfalse = false;	
		datos = {"valores":valorguia,"operador":operador,"ciudado":ciudado};
		$.ajax({
				url: "asignaoper.php",
				type: "POST",
				data: datos,
				async: false,
				success: function(result) {
/* 					guia= result.resultado;
					if(guia==1){
						
						 alert('EL NUMERO DE GUIA NO EXISTE ,  VERIFIQUE');

					}else if(guia==2){
						$("#mensaje").modal("show"); 
							var divvalor= document.getElementById("mensajevalor3");
							divvalor.innerHTML="<strong>OK!</strong>  GUIA ASIGNADA CON EXITO</a>";


					}else if(guia==3) {

							alert('LA GUIA NO ESTA EN ESTADO  DE ASIGNAR,  VERIFIQUE LA GUIA!');
					}else if(guia==4) {
							$("#mensaje").modal("show"); 
							var divvalor= document.getElementById("mensajevalor3");
							divvalor.innerHTML="<strong>YA FUE ASIGNADA!</strong> LA GUIA YA FUE ASIGNADA,  VERIFIQUE LA GUIA</a>";						
						
					} */
					
				}
			});
			

	}		
	
}
</script>

<?php

$FB->nuevo("", "$id_sedes", "asignar_planillas.php");
$FB->abre_form("form1","guiasok.php","post");

$conde="and usu_idsede=$id_sedes"; 
$conde1=" and inner_sedes=$id_sedes"; 
$FB->titulo_azul1("Asignar Guias A Los Operadores",10,0, 5);  

$FB->llena_texto("Sede:",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde2 )", "cambio4(\"param31\",\"param35\",\"guias.php\")", "$id_sedes", 1, 1);
$FB->llena_texto("Operario:",31,2, $DB, "SELECT `idusuarios`,concat_ws(' / ',usu_nombre,zon_nombre) as nombre FROM  seguimiento_user inner join zonatrabajo on seg_idzona=idzonatrabajo  inner join  `usuarios` on idusuarios=seg_idusuario  WHERE `roles_idroles` in (2,3,5) and seg_fechaalcohol='$fechaactual' and (usu_estado=1 or usu_filtro=1) $conde", "", $param31, 4, 1);
$FB->llena_texto("Busqueda por:",33,82,$DB,$busqueda1,"",$param33,17,0);
$FB->llena_texto("Dato:", 32, 1, $DB, "", "","$param32",4,0);
echo '<tr><td class="text">Escanear Código: </td><td align="right" ><div class="form-group">
<div class="input-group">
	<div class="input-group-addon"><i class="fa fa-barcode"></i></div>
	<input autofocus type="text" class="form-control producto" name="codigoEscaneado" id="codigoEscaneado" autocomplete="off" onchange="validarllegada(this);">
</div>
</div></td>';

$sql0="SELECT count(idservicios) as total FROM serviciosdia where ser_estado in (8,11) and ser_llego='SI' $conde1 $conde3  ";
$DB1->Execute($sql0);
$total=mysqli_fetch_row($DB1->Consulta_ID);
$FB->llena_texto("Total Registros:", 7, 1, $DB, "", "","$total[0]",4,0);


$conde3=""; 

if($param32!="" and $param33!=""){ 
 $conde3="and $param33 like '%$param32%' "; 
  }else { $conde3="  "; } 

echo "<tr><td><button type='button' class='btn btn-primary btn-lg' onclick='buscarsede();'>Buscar</button></td><td></td>";
echo "<td><button type='submit' class='btn btn-danger btn-lg' >Enviar</button></td><td style='text-align: right;'><button type='button'  onclick='enviarids(\"$id_p\",\"Whatsapp operador\")' >Mensaje a clientes</button></td><tr>";


$FB->titulo_azul1("IDguia",1,0,7); 
$FB->titulo_azul1("Guias",1,0,0); 
$FB->titulo_azul1("Vr Flete",1,0,0); 
$FB->titulo_azul1("Piezas",1,0,0); 
$FB->titulo_azul1("Tipo PQ",1,0,0); 
$FB->titulo_azul1("Descripcion",1,0,0); 
$FB->titulo_azul1("Destinatario",1,0,0); 
$FB->titulo_azul1("Ciudad",1,0,0); 
$FB->titulo_azul1("Direcci&oacute;n",1,0,0); 
$FB->titulo_azul1("NO Entregada",1,0,0); 
$FB->titulo_azul1("Foto evidencia",1,0,0);
$FB->titulo_azul1("Fecha evidencia",1,0,0);
$FB->titulo_azul1("Devolver o Bodega :",1,0,0); 
$FB->titulo_azul1("Asignar :",1,0,0); 
$FB->titulo_azul1("Factura",1,0,0); 
$FB->titulo_azul1("Editar :",1,0,0); 
$FB->titulo_azul1("Mensaje :",1,0,0); 



$sql="SELECT `idservicios`, `ser_consecutivo`,`ser_tipopaquete`,`ser_paquetedescripcion`, `ser_destinatario`, `ciu_nombre`,`ser_direccioncontacto`,`ser_guiare`,ser_estado,ser_descentrega,ser_pendientecobrar,ser_valor,ser_piezas,ser_piezas,ser_llego,ser_numerofactura
 FROM serviciosdia where ser_estado in (8,11) and ser_llego='SI' $conde1 $conde3   ORDER BY ser_estado,ser_fechafinal DESC ";

$DB->Execute($sql); $va=0; 
	while($rw1=mysqli_fetch_row($DB->Consulta_ID))
	{
		$id_p=$rw1[0];
		
		$va++; $p=$va%2;
		if($p==0){$color="#FFFFFF";} else{$color="#EFEFEF";}
		if($rw1[10]==1){
			$color="#ff4242";
		}else if($rw1[10]==6){
			$color='#0A3F7B';
		}
		else if($rw1[8]==11){ $color="#F39C12";  }
		echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
		$rw1[6]=str_replace("&"," ", $rw1[6]);
				echo "<td align='center' ><a  onclick='pop_dis5($id_p,\"Recogidas\")';  style='cursor: pointer;' title='Detalle Guia' >$id_p</td>";

		echo "
		<td>".$rw1[1]."</td>
		<td>".$rw1[11]."</td>
		<td>".$rw1[12]."</td>
		<td>".$rw1[2]."</td>
		<td>".$rw1[3]."</td>
		<td>".$rw1[4]."</td>
		<td>".$rw1[5]."</td>
		<td>".$rw1[6]."</td>
		<td>".$rw1[9]."</td>
		";

		if ($rw1[9]!="") {

			$sql2="SELECT ser_img_evidencia, ser_fecha_evidencia FROM `servicios` WHERE idservicios='$id_p'";
			
			$DB1->Execute($sql2);
			while($rw2=mysqli_fetch_row($DB1->Consulta_ID))
			{
				$imagenEvidencia=$rw2[0];
				$fechaEvidencia=$rw2[1];

			}


		
			if ($imagenEvidencia=="") {
				echo "
				<td></td>
				";
				echo "
				<td></td>
				";
			}else{

				echo "
				<td><a href='imgNoEntregas/$imagenEvidencia' target='_blank'>Ver Imagen</a></td>
				";
				echo "
				<td>$fechaEvidencia</td>
				";
			}
			
		}else {
			echo "
				<td></td>
				";
				echo "
				<td></td>
				";
				
		}
		$llegoestado=$rw1[13];
		if($totalxcobrar>=1){
			$color='#0A3F7B';
		} 


		
		echo "<td><div id='campo$va'>";
		echo "<select  style='width:120px;border:1px solid #f9f9f9;background-color:#074f91;color:#f9f9f9;font-size:15px'  name='$va' id='$va' onchange='cambioestado(this.value,\"$va\")' class='borrar' required>";
		// $LT->llenaselect_ar("Selecccione...",$estadosguiasinfin);
		echo"<option value='0'>Seleccione....</option>";
		if ($llegoestado=="devueltaremitente") {
			echo"<option value='devueltaremitente' selected >Devolver a ciudad de origen </option>";
			echo"<option value='devueltaBodega'>Enviar a bodega en Bogota</option>";
		}elseif ($llegoestado=="devueltaBodega") {
			echo"<option value='devueltaremitente'>Devolver a ciudad de origen </option>";
			echo"<option value='devueltaBodega' selected>Enviar a bodega en Bogota</option>";
		}else {
			echo"<option value='devueltaremitente'>Devolver a ciudad de origen </option>";
		echo"<option value='devueltaBodega' >Enviar a bodega en Bogota</option>";
		}

		echo "</select></div><input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'></td>";
		echo "<input name='piezasg_$va' id='piezasg_$va' type='hidden'  value='$rw1[11]'>";
		echo "<input name='guia_$va' id='guia_$va' type='hidden'  value='$rw1[1]'>";


		if($rw1[10]!=1){	
			echo "<td><input type='checkbox' name='asignar_$va' id='asignar_$va' value='1' style='width:95px; class='trans' >
			<input name='servicio_$va' id='servicio_$va' type='hidden'  value='$rw1[0]'>
			<input name='direccion_$va' id='direccion_$va' type='hidden'  value='$rw1[6]'>
			<input name='guia_$va' id='guia_$va' type='hidden'  value='$rw1[1]'>
			</td>";
		}else{
			echo "<input name='asignar_$va' id='asignar_$va' type='hidden'  value='0'>";
			echo "<td>Pendiente por Cobrar, Debe pagarse Antes.</td>";
		}
		echo "<td>".$rw1[15]."</td>";
		echo "<td align='center'>";
		echo "<a  onclick='pop_dis11($id_p,\"Editar datos\",\"guias.php\",\"recogerok.php\",0)';  style='cursor: pointer;' title='Editardatos' ><img src='img/informacion.jpg'></a></td>";
		$colortd="";
		$sql5="SELECT count(*) FROM `registro` WHERE mensaje_enviado in('7','8','17','18','19','21') and `id_servicio`='$id_p' ";
		$DB1->Execute($sql5);
		$canti=$DB1->recogedato(0);
		
		if ($canti>0) {
			$colortd="style='background-color: #ffcc00;'";
		}else{
			$colortd="";
		}
		echo "<td ".$colortd."'><input type='checkbox'  onchange='selecionado($id_p)' class='checkbox' id='".$id_p."s' value='$id_p'></td></tr>";
		
		


	}
	
echo "<input name='registros' id='registros' type='hidden'  value='$va'>";
$FB->llena_texto("tipoguia", 1, 13, $DB, "", "","operador", 5, 0);
	
include("footer.php");

?>
<script>
	 let idsSeleccionados = [];
	function enviarids(id_param,tabla) {
	let arrayParam = encodeURIComponent(JSON.stringify(idsSeleccionados));
	$("#myModal").modal("show");
	var destino=`detalle_pop.php?ide=${arrayParam}&id_param=${encodeURIComponent(id_param)}&tabla=${encodeURIComponent(tabla)}`;
	MostrarConsulta(destino, "llena_sub1");
	}

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
	function sendWhatsapp(fileNames) {
		const mensaje = document.getElementById('chekWhatsapp').value;
		// Recorre cada elemento en fileNames y ejecuta una función para cada uno
		fileNames.forEach(function (service) {
			// Cada `service` contiene [idservicios, ser_telefonocontacto, ser_consecutivo, cli_telefono]
			const [id, contacto, consecutivo, telefono] = service;
			
			// Llama a una función o envía un mensaje por cada servicio
			console.log(`Procesando servicio ID: ${id}, Contacto: ${contacto}, Consecutivo: ${consecutivo}, Teléfono: ${telefono}, Mensaje: ${mensaje}`);
			
			enviarAlertaWhat(consecutivo,contacto,mensaje,id);
			enviarAlertaWhat(consecutivo,telefono,mensaje,id);
			// Aquí puedes ejecutar la función deseada para cada servicio
			// Por ejemplo, podrías enviar un mensaje por WhatsApp usando una API o una integración adicional
		});
		alert('Todos las alertas han sido enviadas');
	}

	async function enviarAlertaWhat(numguia, telefono, tipo, idservi) {
    // URL de la API
    const url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

    // Datos a enviar en la solicitud
    const data = {
        numero_guia: numguia, // Número de guía
        telefono: telefono,    // Número de teléfono
        tipo_alerta: tipo,     // Tipo de alerta
        id_guia: idservi       // ID de la guía
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



</script>