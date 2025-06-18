<!DOCTYPE html>
<html>

<head>
<script>

</script>
</head>
<body>

 <?php
session_start();
$documento_por_vencer = []; 

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
$FB->titulo_azul1("Actalizar", 1, 0, 0);
$FB->titulo_azul1("Correo", 1, 0, 0);
$FB->titulo_azul1("Historial", 1, 0, 0);
echo "</tr>";
// $FB->titulo_azul1("Imagenes de Documentos",9,0,7);  
// echo "</tr>";

$sql = "SELECT d1.iddoccliente, d1.docl_nombre, d1.docl_documento, d1.docl_idhvc, d1.docl_fecha_venc, d1.docl_fecha_creacion FROM doc_hoja_clientes d1 INNER JOIN (
            SELECT docl_nombre, MAX(iddoccliente) AS max_id FROM doc_hoja_clientes WHERE docl_idhvc = '$idhojadevida' GROUP BY docl_nombre) d2 ON d1.docl_nombre = d2.docl_nombre AND d1.iddoccliente = d2.max_id WHERE d1.docl_idhvc = '$idhojadevida' ORDER BY d1.docl_nombre";
$DB->Execute($sql); 
$va = (($compag - 1) * $CantidadMostrar); 

while ($rw1 = mysqli_fetch_row($DB->Consulta_ID)) {
    $id_p = $rw1[0];
    $va++; 
    $p = $va % 2;

    $fecha_vencimiento = $rw1[4];
    $fecha_actual = date("Y-m-d");
    $dias_restantes = (strtotime($fecha_vencimiento) - strtotime($fecha_actual)) / (60 * 60 * 24);

    if ($dias_restantes <= 30) {
    $documento_por_vencer[$idhojadevida] = true;
        $color = "#FFCCCC"; 
    } else {
        $color = ($p == 0) ? "#FFFFFF" : "#EFEFEF";
    }

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
        <button type='button' class='btn btn-primary btn-sm' onclick=\"abrirEditarModal($id_p, '".htmlspecialchars($rw1[1], ENT_QUOTES)."', '".$rw1[4]."', '".$rw1[2]."')\">Actualizar</button>
      </td>";

    echo "<td style='text-align: center;'>
        <button type='button' class='btn btn-warning btn-sm' onclick='enviarCorreo($id_p)'>Enviar</button>
    </td>";

    echo "<td style='text-align: center;'>
        <button type='button' class='btn btn-success btn-sm' onclick='historialDocumentos({$rw1[3]}, " . json_encode($rw1[1]) . ")'>Registros</button>
    </td>";


    echo "</tr>";
}


echo '<input type="hidden" name="param7" id="param7" value="'.$idhojadevida.'">';
echo '<input type="hidden" name="param8" id="param8" value="1">';
echo '<input type="hidden" name="param8" id="foto" value="">';

$FB->titulo_azul1("Nombre :",1,0,7);
$FB->titulo_azul1("Documento",1,0,0); 

echo "</tr>";
echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>CAMARA DE COMERCIO:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 1, 35, 'Ver Imagen');
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Rut:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 2, 35, 'Ver Imagen');
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Poliza:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 3, 35, 'Ver Imagen');
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Contrato:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 4, 35, 'Ver Imagen');
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'><td>Certificacion cuenta bancaria:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 5, 35, 'Ver Imagen');
echo "</tr>"; 

echo "<tr class='text' bgcolor='$color' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>><td>Cedula representante legal:</td>";
echo $LT->llenadocs3($DB1, "hojadevidacliente",$idhojadevida, 6, 35, 'Ver Imagen');
echo "</tr>";


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
        location.reload();
	
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
    document.getElementById('edit_nombre_texto').innerText = nombre;
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
    const nombre = document.getElementById('edit_nombre_texto').innerText;
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

function enviarCorreo(id_doccliente) {
    if (confirm("¿Deseas enviar el correo con la solicitud?")) {
        fetch("enviar_correo.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "iddoccliente=" + encodeURIComponent(id_doccliente)
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error("Error al enviar el correo:", error);
            alert("Hubo un error al intentar enviar el correo.");
        });
    }
}

function historialDocumentos(idhojadevida, nombreDoc) {
    console.log("ID:", idhojadevida, "Nombre:", nombreDoc);
    fetch("obtener_historial.php?id=" + idhojadevida + "&nombre=" + encodeURIComponent(nombreDoc))
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
    console.log("Respuesta del servidor:", data);

    if (!Array.isArray(data)) {
        alert("Error en la respuesta: " + (data.error || "No se recibieron datos válidos."));
        return;
    }

    const modalExistente = document.getElementById("modalRegistros");
    if (modalExistente) modalExistente.remove();

    const modal = document.createElement("div");
    modal.id = "modalRegistros";
    modal.style.cssText = `
        position: fixed;
        top: 10%;
        left: 20%;
        width: 60%;
        background: #fff;
        border: 2px solid #666;
        border-radius: 10px;
        padding: 20px;
        z-index: 1000;
        box-shadow: 0px 0px 20px rgba(0,0,0,0.5);
    `;

    modal.innerHTML = `
        <h4>Historial de Documentos: <span style="color:#000000;">${nombreDoc}</span></h4>
        <table id="table_registros" class="table table-bordered">
            <thead>
                <tr>
                    <th>Fecha de creación</th>
                    <th>Fecha de vencimiento</th>
                    <th>Archivo</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <div style="text-align: right;">
            <button class="btn btn-danger" onclick="document.getElementById('modalRegistros').remove()">Cerrar</button>
        </div>
    `;

    document.body.appendChild(modal);

    const tbody = modal.querySelector("tbody");
    data.forEach(function (item) {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.docl_fecha_creacion}</td>
            <td>${item.docl_fecha_venc}</td>
            <td><a href="./img_docHVC/${item.docl_documento}" target="_blank">Ver</a></td>
        `;
        tbody.appendChild(row);
    });
})
}

</script>
<div id="modalEditar" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); width:900px; background:#fff; padding:20px; border:2px solid #666; border-radius:0; z-index:1000; box-shadow: 0 0 15px rgba(0,0,0,0.2);">
  <h4 style="color: black;">Actualizar Documento</h4>
  <input type="hidden" id="edit_iddoccliente">
  
  <div>
  <label style="color: black;">Nombre:</label><br>
  <div id="edit_nombre_texto" style="padding:8px; background-color:#f0f0f0; border:1px solid #ccc; border-radius:4px;"></div>
</div>
  <div>
    <label style="color: black;">Fecha de vencimiento:</label><br>
    <input type="date" id="edit_fecha" style="width:100%;">
  </div>

  <div>
    <label style="color: black;">Documento actual:</label><br>
    <div id="documento_actual" style="margin-bottom:15px;"></div>
    <input type="file" id="edit_documento"> 
  </div>

  <div style="margin-top:10px;">
    <button type="button" class="btn btn-success" onclick="guardarEdicion()">Guardar</button>
    <button type="button" onclick="cerrarModal()" class="btn btn-secondary">Cancelar</button>
  </div>
</div>


</body>
</html>
