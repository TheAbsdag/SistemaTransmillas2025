<!DOCTYPE html>
<html>

<head>
<script>

</script>
</head>
<body>

 <?php 

 $fechaactual=date("Y-m-d");
 $nivel_acceso=$_SESSION['usuario_rol'];
 $id_sedes=$_SESSION['usu_idsede'];

 if($nivel_acceso==1){
	if($param35!=''){   $conde2=""; }  

}
else {	
	$param35=$id_sedes;
	
}
echo "</tr>";
$FB->titulo_azul1("Documentos clientes",9,0,7);  
echo "</tr>";



$FB->llena_texto("Nombre",1, 9, $DB, "", "", "",1,0);
$FB->llena_texto("Fecha de vencimiento:", 3, 10, $DB, "", "", "", 17, 0);
$FB->llena_texto("Documentos:", 2, 6, $DB, "", "", "",1, 0);


echo '<input type="hidden" name="param4" id="param4" value="'.$idhojadevida.'">';


echo "<tr><td><button type='button' class='btn btn-success' onclick='enviar_formulario()' >Gurdar</button></td></tr>";
$FB->titulo_azul1("Nombre :",1,0,7);
$FB->titulo_azul1("Fecha de vencimiento ",1,0,0); 
$FB->titulo_azul1("Documento",1,0,0); 
$FB->titulo_azul1("Eliminar",1,0,0); 
$FB->titulo_azul1("Editar", 1, 0, 0);
echo "</tr>";
// $FB->titulo_azul1("Imagenes de Documentos",9,0,7);  
// echo "</tr>";

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>CAMARA DE COMERCIO:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 1, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Rut:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 2, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Poliza:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 3, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Contrato:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 4, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Certificacion cuenta bancaria:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 5, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>><td>Cedula representante legal:</td>";
echo "<td></td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$id_p, 6, 35, 'Ver Imagen');
echo "<td></td>";
echo "</tr>";

$sql = "SELECT iddoccliente, docl_nombre, docl_documento, docl_idhvc, docl_fecha_venc FROM doc_hoja_clientes WHERE docl_idhvc = '$idhojadevida'";
$DB->Execute($sql); 
$va = (($compag - 1) * $CantidadMostrar); 

while ($rw1 = mysqli_fetch_row($DB->Consulta_ID)) {
    $id_p = $rw1[0];
    $va++; 
    $p = $va % 2;
    $color = ($p == 0) ? "#FFFFFF" : "#EFEFEF";

    echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
    echo "<td>".$rw1[1]."</td>"; 
    echo "<td>".$rw1[4]."</td>"; 

    if (!empty($rw1[2])) {
        $ruta = "./img_docHVC/" . $rw1[2]; 
        echo "<td style='text-align: center;'><a href='$ruta' target='_blank'>Ver Imagen</a></td>";
    } else {
        echo "<td style='text-align: center;'>Sin archivo</td>";
    }
    echo "<td style='text-align: center;'><button class='btn btn-danger' onclick='eliminarDocumento($id_p, \"$rw1[2]\")'>Eliminar</button></td>";

    echo "<td style='text-align: center;'>
    <button type='button' class='btn btn-primary btn-sm' onclick=\"abrirEditarModal($id_p, '".htmlspecialchars($rw1[1], ENT_QUOTES)."', '$rw1[4]', '$rw1[2]')\">Editar</button></td>";

    echo "</tr>";
}


echo '<input type="hidden" name="param7" id="param7" value="'.$idhojadevida.'">';
echo '<input type="hidden" name="param8" id="param8" value="1">';
echo '<input type="hidden" name="param8" id="foto" value="">';



?> 
<script>

function enviar_formulario() {
    let formData = new FormData(); // Crea un objeto FormData para enviar los datos

    // Capturar valores de los inputs
    formData.append("nombre", document.getElementById("param1").value);
    formData.append("documento", document.getElementById("param2").files[0]); 
    formData.append("fecha", document.getElementById("param3").value);
    formData.append("idhv", document.getElementById("param4").value);

    // Enviar con fetch al PHP
    fetch("new_documentcli.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text()) 
    .then(data => {
        alert(data);
				row.find(".nombre").val(""); 
                row.find(".fecha").val(""); 
	
    })
    .catch(error => console.error("Error:", error));
}

function eliminarDocumento(id, archivo) {
    if (confirm("¿Estás seguro de que deseas eliminar este documento?")) {
            fetch("eliminar_documentcli.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `iddoccliente=${id}&archivo=${encodeURIComponent(archivo)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            alert("Hubo un problema al eliminar.");
        });
    }

}
function abrirEditarModal(id, nombre, fecha, documento) {
    document.getElementById('edit_iddoccliente').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_fecha').value = fecha;

    if (documento) {
        const link = `<a href='./img_docHVC/${documento}' target='_blank'>Ver documento actual</a>`;
        document.getElementById('documento_actual').innerHTML = link;
    } else {
        document.getElementById('documento_actual').innerText = 'Sin documento actual';
    }

    document.getElementById('modalEditar').style.display = 'block';
}


function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

function guardarEdicion() {
    const id = document.getElementById('edit_iddoccliente').value;
    const nombre = document.getElementById('edit_nombre').value;
    const fecha = document.getElementById('edit_fecha').value;
    const archivo = document.getElementById('edit_documento').files[0];

    const formData = new FormData();
    formData.append("iddoccliente", id);
    formData.append("nombre", nombre);
    formData.append("fecha", fecha);
    if (archivo) {
        formData.append("documento", archivo);
    }

    fetch("editar_documentcli.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data); 
        cerrarModal(); 
        location.reload(); 
    })
    .catch(error => {
        console.error("Error al guardar edición:", error);
        alert("Ocurrió un error al guardar los cambios.");
    });
}

</script>
<div id="modalEditar" style="display:none; position:fixed; top:20%; left:35%; background:#fff; padding:20px; border:2px solid #666; border-radius:10px; z-index:1000;">
  <h4>Editar Documento</h4>
  <input type="hidden" id="edit_iddoccliente">
  
  <div>
    <label>Nombre:</label><br>
    <input type="text" id="edit_nombre" style="width:100%;">
  </div>
  
  <div>
    <label>Fecha de vencimiento:</label><br>
    <input type="date" id="edit_fecha" style="width:100%;">
  </div>

  <div>
    <label>Documento actual:</label><br>
    <div id="documento_actual" style="margin-bottom:5px;"></div>
    <input type="file" id="edit_documento"> 
  </div>

  <div style="margin-top:10px;">
    <button type="button" class="btn btn-success" onclick="guardarEdicion()">Guardar</button>
    <button onclick="cerrarModal()" class="btn btn-secondary">Cancelar</button>
  </div>
</div>


</body>
</html>
