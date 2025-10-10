<!DOCTYPE html>
<html>
<head>
    <title>Firma Digital en Dispositivos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        .container {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        h2 {
            margin: 10px 0;
        }
        .content {
            flex: 1;
            display: flex;
        }
        #canvas-container {
            flex: 9;
            max-width: 90%;
            height: 100%;
            text-align: center;
            border: 1px solid #000;
            overflow: hidden;
        }
        canvas {
            width: 100%;
            height: 100%;
            cursor: crosshair;
            touch-action: none;
        }
        .buttons {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .buttons button {
            margin: 5px 0;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
        }
        .buttons #imagen-input {
            display: none;
        }
        .buttons button:hover {
            background-color: #45a049;
        }
        .file-loaded {
            background-color: #28a745 !important;
        }
        @media only screen and (max-width: 600px) {
            h2 {
                text-align: center;
            }
            .content {
                flex-direction: column;
            }
            #canvas-container {
                max-width: 100%;
                height: 50%;
                margin-bottom: 20px;
            }
            .buttons {
                flex: 1;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php 
$tipoPago=$_GET['p']; 

if (strtolower($tipoPago) == "al cobro") {
    $textoPago="Esta guia NO está paga";
    $colorPago="style='background-color: #e74c3c; '";
}elseif (strtolower($tipoPago) == "credito") {
    $textoPago="Esta guia NO está paga";
    $colorPago="style='background-color: #e74c3c; '";
}elseif (strtolower($tipoPago) == "contado") {
    $textoPago="Esta guia SI está paga";
    $colorPago="style='background-color: green; '";
}
?> 
  <div id="loading" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">
    <img src="img/enviando.gif" alt="Cargando..." />
</div>
<div class="container">
    <h2>Por favor, Firma la Guía: <?php echo $_GET['idguia']; ?></h2><br>

    <h2 <?php echo $colorPago;?>>Pago: <?php echo $_GET['p']; ?>  <?php echo$textoPago;?></h2>
        <p style='color:#c0392b; font-weight:bold; font-size:14px; line-height:1.4;'>
        <br>
        Aviso: Transmillas no se hace responsable por daños o suciedad en mercancías sin embalaje 
        (ejemplo: colchones, vidrios u objetos frágiles). El embalaje es responsabilidad del cliente.
        </p>
    <div class="content">
        <div id="canvas-container">
            <canvas id="canvas">
              
            </canvas>
        </div>
        <div class="buttons">
            <button class="btn btn-primary" style="background-color: #2e86c1; " id="fileBtn">
                Subir archivo
            </button>
            <input type="file" id="imagen-input" accept="image/*" style="display: none;">
            <button id="limpiar" style="background-color: #e74c3c; ">Limpiar</button>
            <button id="guardar">Guardar</button>
        </div>
    </div>
</div>

<script>
 
  const canvas = document.getElementById("canvas");
  const ctx = canvas.getContext("2d");



$(document).ready(function(){


   

    var canvas = document.getElementById('canvas');
    var ctx = canvas.getContext('2d');
     // === MOSTRAR TEXTO CON VARIABLES PHP EN EL CANVAS ===
    ctx.font = "20px Arial";
    ctx.fillStyle = "<?php echo $colorPago; ?>"; // Asegúrate de que esto devuelva un color válido como "green" o "#ff0000"
    ctx.fillText("Pago: <?php echo $_GET['p']; ?>", 10, 30);
    ctx.fillText("<?php echo $textoPago; ?>", 20, 50);

    var drawing = false;
    var prevX, prevY;
    var inputFile = document.getElementById('imagen-input');
    var fileBtn = document.getElementById('fileBtn');

    // Función para ajustar el tamaño del canvas
    function resizeCanvas() {
        var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        canvas.width = document.getElementById('canvas-container').offsetWidth;
        canvas.height = document.getElementById('canvas-container').offsetHeight;
        ctx.putImageData(imageData, 0, 0);
    }

    resizeCanvas();
    window.addEventListener('resize', function() {
        resizeCanvas();
    });

    // Función para manejar el inicio del dibujo (click o toque)
    function startDrawing(e) {
        drawing = true;
        var rect = canvas.getBoundingClientRect();
        var x = e.clientX ? e.clientX - rect.left : e.touches[0].clientX - rect.left;
        var y = e.clientY ? e.clientY - rect.top : e.touches[0].clientY - rect.top;
        prevX = x;
        prevY = y;
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
    }

    // Función para manejar el dibujo continuo (movimiento del ratón o del dedo)
    function draw(e) {
        if (drawing) {
            var rect = canvas.getBoundingClientRect();
            var x = e.clientX ? e.clientX - rect.left : e.touches[0].clientX - rect.left;
            var y = e.clientY ? e.clientY - rect.top : e.touches[0].clientY - rect.top;
            ctx.lineTo(x, y);
            ctx.stroke();
            prevX = x;
            prevY = y;
        }
    }

    // Función para manejar el fin del dibujo (soltar el click o el toque)
    function endDrawing() {
        drawing = false;
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', endDrawing);

    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        startDrawing(e);
    });

    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        draw(e);
    });

    canvas.addEventListener('touchend', endDrawing);

    $('#limpiar').click(function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        inputFile.value = '';
        fileBtn.innerHTML = 'Subir archivo';
        fileBtn.style.backgroundColor = '#4CAF50';
    });

    $('#guardar').click(function() {
        var canvas = document.getElementById('canvas');
        function isCanvasEmpty(canvas) {
            var blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            return canvas.toDataURL() === blank.toDataURL();
        }


            var formData = new FormData();
            var file = inputFile.files[0];

            if (file) {
                formData.append('imagen', file);
                formData.append('idfirma', <?php echo $_GET['idfirma']; ?>);
                formData.append('idguia', '<?php echo $_GET['idguia']; ?>');
                formData.append('id_param', '<?php echo $_GET['id_param']; ?>');
                $.ajax({
                    type: 'POST',
                    url: 'guardar_imagen.php',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'text',
                    success: function(response) {
                        alert(response);
                        redirigir();
                    }
                });

            } else {
                if (isCanvasEmpty(canvas)) {
                    alert('Por favor, firme el recuadro blanco antes de guardar.');
                } else {
                    var dataURL = canvas.toDataURL();
                    formData.append('image', dataURL);
                    formData.append('idfirma', <?php echo $_GET['idfirma']; ?>);
                    formData.append('idguia', '<?php echo $_GET['idguia']; ?>');

                    $.ajax({
                        type: 'POST',
                        url: 'guardar_firma.php',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'text',
                        success: function(response) {
                            alert(response);
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                            redirigir();
                        }
                    });
            }
        }
    });

    $('#fileBtn').click(function() {
        $('#imagen-input').click();
    });

    $('#imagen-input').change(function() {
        var fileName = this.files[0].name;

        if (fileName) {
            fileBtn.innerHTML = 'Cargado';
            fileBtn.style.backgroundColor = '#28a745';
        } else {
            fileBtn.innerHTML = 'Subir archivo';
            fileBtn.style.backgroundColor = '#4CAF50';
        }
    });

    function redirigir() {

         var pagina = '<?php echo $_GET['pagina2']; ?>';
         var id_param = '<?php echo $_GET['id_param']; ?>';
         var imprimir = '<?php echo $_GET['imprimir']; ?>';
         var idguia = '<?php echo $_GET['idguia']; ?>';

         var ruta="id_param="+id_param+"&imprimir="+imprimir+"&idguia="+idguia;


    $.ajax({
      
      url: 'guardarGuiaImg.php',
      type: 'POST',
      data: ruta,
      })
      .done(function(res){
        // window.location.href = pagina;
		// $('#cuerpo').html(res);
		// boton();
        console.log("Contenido de res:", res);

        // try {
            var datosGui = JSON.parse(res);

            console.log(datosGui.num1);
            console.log(datosGui.num2);
            console.log(datosGui.num3);
            console.log(datosGui.guiaruta);

            let telefonos = [datosGui.num1, datosGui.num2, datosGui.num3];

        // } catch (error) {
        //     console.error("Error al parsear JSON:", error);
        // }

        enviarAlertaWhat("", telefonos, 24, "",""+datosGui.guiaruta+"");
      })
      .fail(function(){

     
      })
      .always(function(){

  
      });


        // var destino = pagina == 'imprimirfactura.php' ? 'imprimirfactura.php' : 'ticketfacturacorreoimprimir.php';
        // window.location.href = destino + '?pagina2=' + pagina + '&id_param=' + id_param + '&imprimir=' + imprimir + '&enviarWhatsapp=1';
    
    }
});

function pruebas(){

    console.log('okiiii');


};
async function enviarAlertaWhat(numguia, telefonos, tipo, idservi,imagen1) {
    // console.log('link'+imagen1);
    // Mostrar el GIF de carga
    const loadingDiv = document.getElementById("loading");
    loadingDiv.style.display = "block";
    // URL de la API
    const url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

    // const telefonosjs = JSON.parse(telefonos);
    let telefonosjs = telefonos;

    for (const telefono of telefonosjs) {
      console.log(telefono); 
       if (telefono=="") {
        
       }else{
        
          // console.log(`Teléfono ${index + 1}: ${telefono}`);
        
        // Datos a enviar en la solicitud
        const data = {
            numero_guia: numguia, // Número de guía
            telefono: telefono,    // Número de teléfono
            tipo_alerta: tipo,     // Tipo de alerta
            id_guia: idservi,     // ID de la guía
            imagen1: imagen1
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

        } catch (error) {
            // Manejar errores
            console.error("Error en la solicitud:", error);
            alert("Error al enviaral numero."+telefono);

        }
     }
    }
        // Ocultar el GIF de carga
        loadingDiv.style.display = "none";
  alert("Los mensajes han sido enviados.");
   window.location.href = "inicio.php";
}


</script>

</body>
</html>


