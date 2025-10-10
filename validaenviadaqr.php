<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<?php

// $redirectIfNotLogged = "nueva_plataforma/controller/ValidarQrController.php?guia=".$_REQUEST["guia"]."&pieza=".$_REQUEST["pieza"].""; 
require("login_autentica.php"); // conexión base de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();
$id_nombre = $_SESSION['usuario_nombre'];

$guia   = $_REQUEST["guia"];
$pieza  = $_REQUEST["pieza"];
$ciudado = $_POST["ciudado"];
?>

<style>
    body {
        background-color: #f4f4f4;
        font-size: 2rem; /* Tamaño base más grande */
    }
    .main-container {
        max-width: 95%;
        margin: 20px auto;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #fff;
        text-align: center;
    }
    .status-box {
        font-size: 2.5rem; /* Mucho más grande */
        font-weight: bold;
        padding: 40px;
        border-radius: 14px;
        margin-bottom: 40px;
        line-height: 1.6;
    }
    .status-error { background-color: #dc3545; color: white; }
    .status-warning { background-color: #ffc107; color: black; }
    .status-success { background-color: #28a745; color: white; }

    .btn-custom {
        font-size: 2.3rem; /* Botón grande */
        padding: 25px 60px;
        border-radius: 14px;
    }

    .form-label {
        font-size: 2.3rem;
        font-weight: bold;
    }

    .form-select-lg {
        font-size: 2.2rem;
        padding: 20px;
    }

    /* Ajuste extra para pantallas pequeñas */
    @media (max-width: 576px) {
        body {
            font-size: 2.4rem; /* Todo el texto aún más grande */
        }
        .status-box {
            font-size: 3rem;
            padding: 50px 25px;
        }
        .btn-custom {
            width: 100%;
            font-size: 2.8rem;
            padding: 30px;
        }
        .form-label {
            font-size: 2.6rem;
        }
        .form-select-lg {
            font-size: 2.5rem;
            padding: 25px;
        }
    }
</style>

<div class="main-container">

<?php
$sql = "SELECT ser_piezas, idservicios, ser_estado, ser_desvaliguia, ser_ciudadentrega, ser_idverificadopeso, ciu_nombre, sed_color, sed_nombre 
        FROM servicios 
        INNER JOIN ciudades ON idciudades = ser_ciudadentrega 
        INNER JOIN sedes ON inner_sedes = idsedes 
        WHERE ser_consecutivo = '$guia'";		

$DB1->Execute($sql);
$rw1 = mysqli_fetch_row($DB1->Consulta_ID);

$idser     = $rw1[1] ?? null;
$piezasg   = $rw1[0] ?? null;
$estado    = $rw1[2] ?? null;
$descricion= $rw1[3] ?? null;

if (!$idser) {
    echo '<div class="status-box status-error">
	        <p><strong>Guía:</strong> '.$guia.'</p>
            ❌ El número de guía no existe, verifique.
          </div>
          <button class="btn btn-primary btn-custom" onclick="validarYCerrar();">Aceptar</button>';
} else {
    $sql5 = "SELECT idpiezasguia FROM piezasguia WHERE numeroguia='$guia' AND numeropieza='$pieza'";
    $DB1->Execute($sql5);
    $rw5 = mysqli_fetch_row($DB1->Consulta_ID);

    if (empty($rw5[0])) {
        if ($estado == 6 && $rw1[5] == 1) {
            echo '
			
			<div class="alert alert-warning alert-dismissible fade show" role="alert">
				⚠️ <strong>Atención:</strong> Ahora debe seleccionar una opción y dar <b>Aceptar</b> 
				para que el escaneo sea efectivo, de lo contrario <b>no quedará escaneada</b>.
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
			</div>
            <div class="status-box status-success">
                <p><strong>Guía:</strong> '.$guia.'</p>
                <p><strong>Destino:</strong> '.$rw1[8].'</p>
                <p><strong>Pieza:</strong> '.$pieza.'</p>
            </div>

            <div class="mb-4">
                <label for="tipoVehiculo" class="form-label fw-bold">Seleccione el tipo de vehículo o situación del paquete:</label>
                <select class="form-select form-select-lg" name="tipoVehiculo" id="tipoVehiculo" required>
                    <option value="">-- Seleccione --</option>
                    <option value="Bus">Bus</option>
                    <option value="Jurgon">Furgón</option>
                    <option value="En escala temporal">En escala temporal</option>
                    <option value="Devuelto a centro de distribucion">Devuelto a centro de distribución</option>
                </select>
            </div>

            <button class="btn btn-primary btn-custom" 
                onclick="enviarDatos(\''.$guia.'\', \''.$pieza.'\', \''.$id_nombre.'\', \''.$idser.'\', \''.$piezasg.'\');">
                Aceptar
            </button>';
        } elseif ($estado == 7) {
            echo '<div class="status-box status-warning">
					<p><strong>Guía:</strong> '.$guia.'</p>
					<p><strong>Destino:</strong> '.$rw1[8].'</p>
					<p><strong>Pieza:</strong> '.$pieza.'</p>
                    ⚠️ La guía ya fue enviada. Verifique la guía.
                  </div>
                  <button class="btn btn-primary btn-custom" onclick="validarYCerrar();">Aceptar</button>';
        } else {
            echo '<div class="status-box status-error">
			        <p><strong>Guía:</strong> '.$guia.'</p>
					<p><strong>Destino:</strong> '.$rw1[8].'</p>
					<p><strong>Pieza:</strong> '.$pieza.'</p>
                    ❌ La guía no está en estado de envío, verifique.
                  </div>
                  <button class="btn btn-primary btn-custom" onclick="validarYCerrar();">Aceptar</button>';
        }
    } else {
         echo '<div class="status-box status-error">
		        <p><strong>Guía:</strong> '.$guia.'</p>
                <p><strong>Destino:</strong> '.$rw1[8].'</p>
                <p><strong>Pieza:</strong> '.$pieza.'</p>
                ❌ Esta pieza ya fue escaneada.
              </div>
              <button class="btn btn-primary btn-custom" onclick="validarYCerrar();">Aceptar</button>';
    }
}
?>

</div>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

function enviarDatos(guia, pieza, id_nombre, idser,piezasg) {
    // Valor seleccionado en el select
    const tipoVehiculo = document.getElementById("tipoVehiculo").value;

    // Validación opcional
    if (tipoVehiculo === "") {
        alert("Debes seleccionar una opción");
        return;
    }

    // Enviar datos por AJAX
    $.ajax({
        url: "validaenviadaqrok.php",   // tu archivo PHP
        type: "POST",
        data: {
            tipoVehiculo: tipoVehiculo,
            guia: guia,
            pieza: pieza,
            id_nombre: id_nombre,
            idser: idser,
            piezasg: piezasg
        },
        success: function(respuesta) {
            console.log("Servidor dice:", respuesta);

            let resp = respuesta.trim();

            if (resp === "OK") {
                // 🔊 Reproducir sonido de éxito
                let audio = new Audio("sonidos/escaneoExitoso.mp3"); 
                audio.play();

                alert("✅ Escaneo realizado con éxito");
                window.history.back(); // 🔙 vuelve a la página anterior
            } else {
                alert("❌ Error en el escaneo: " + resp);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en AJAX:", error);
            alert("⚠️ Ocurrió un error al enviar los datos");
        }
    });
}



function validarYCerrar() {
  window.history.back(); // 🔙 vuelve a la página anterior
}








</script>';
