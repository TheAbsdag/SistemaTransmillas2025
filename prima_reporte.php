<?php
$datos = [];
if (isset($_POST['datosusertabla'])) {
    $datosJSON = $_POST['datosusertabla'];
    $datos = json_decode($datosJSON, true);
}
$datos2 = [];
if (isset($_POST['datosusertablaP'])) {
    $datosJSON2 = $_POST['datosusertablaP'];
    $datos2 = json_decode($datosJSON2, true);
}

// $fechaactual=$_POST['param33'];

$semestre=$_POST['param36'];
if ($semestre=="Primera") {
    $dia='30';
    $mes='06';
}else{
    $dia='31';
    $mes='12';
}
$año=$_POST['param34'];
$fechaactual=date($año.'-'.$mes.'-'.$dia)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Nómina</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos (opcional para botones) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            padding: 30px;
        }
        .card-header {
            background: linear-gradient(90deg, #2196f3, #1e88e5);
            color: white;
        }
        table thead th {
            vertical-align: middle;
        }
        .custom-controls {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .container{
            max-width: 1900px;

        }
    </style>
</head>
<body>

<div class="container">
    <div class="custom-controls">
    
        <input type="file" class="form-control w-auto" id="archivo" accept="image/*,application/pdf" onchange="mostrarImagen(event)">
        <button class="btn btn-outline-primary" onclick="guardarImagenCompleta()">
            <i class="bi bi-image"></i> Guardar imagen completa
        </button>

    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Tabla de Nómina</span>
            <div>
                <button class="btn btn-sm btn-light me-2" onclick="guardarImagen()" title="Guardar imagen"><i class="bi bi-camera"></i></button>
                <button class="btn btn-sm btn-light" onclick="generarPDF()" title="Generar PDF"><i class="bi bi-file-earmark-pdf-fill"></i></button>
            </div>
        </div>
        <div class="card-body table-responsive" id="tablaNomina">
            <?php if (is_array($datos) && count($datos) > 0): ?>
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <?php
                            $encabezados = [
                                		"id",
                                        "nombreCompleto",
                                        "contrato",
                                        "cedula",
                                        "nombreCargo",
                                        "salario",
                                        "auxilio",
                                        "descanso",
                                        "NoTrabajo",
                                        "Incapacidad",
                                        "Vacaciones",
                                        "licenciasPermisos",
                                        "totalDiasPrima",
                                        "valorDiasPrima_formateado"
                            ];
                            foreach ($encabezados as $col) {
                                
                                echo "<th>" . htmlspecialchars($col) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos as $fila): ?>
                            <tr>
                                <?php $ids[] = $fila['id'];
                                 foreach ($encabezados as $col): ?>
                                    <td><?= htmlspecialchars($fila[$col]) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">No se recibieron datos o el arreglo está vacío.</p>
            <?php endif; ?>
        </div>
        <div class="card-body table-responsive" id="tablaNomina2">
            <?php if (is_array($datos2) && count($datos2) > 0): ?>
                <table class="table table-hover table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <?php
                            $encabezados = [
                                "id", "trabajador", "cedula", "dias", "", "valortdiasbase",
                                "valorHoras", "valorRecogidas", "otropordia", "valorOtros",
                                "prestamosDescuadres", "abonoDeudas", "retegarantias", "valorquicena"

                            ];
                            foreach ($encabezados as $col) {
                                
                                echo "<th>" . htmlspecialchars($col) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($datos2 as $fila): ?>
                            <tr>
                                <?php $ids[] = $fila['id'];
                                 foreach ($encabezados as $col): ?>
                                    <td><?= htmlspecialchars($fila[$col]) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">No se recibieron datos o el arreglo está vacío.</p>
            <?php endif; ?>
        </div>

        
        
        <div class="card mt-3 shadow-sm" id="imagenCargadaContainer" style="display:none;">
            <div class="card-header">
                Imagen Cargada
            </div>
            <div class="card-body text-center">
                <img id="imagenCargada" src="" alt="Imagen cargada" class="img-fluid">
            </div>
        </div>

    </div>
</div>

<!-- Librerías JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // Mostrar la imagen cargada
    function mostrarImagen(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById("imagenCargada");
            img.src = e.target.result;
            document.getElementById("imagenCargadaContainer").style.display = "block";
        };
        if (file) reader.readAsDataURL(file);
    }

    // Captura completa de tabla e imagen cargada (sin cortes)
    function guardarImagenCompleta() {
        const tabla = document.getElementById("tablaNomina");
        const tabla2 = document.getElementById("tablaNomina2");

        const imagenContainer = document.getElementById("imagenCargadaContainer");
        

        const contenedor = document.createElement("div");
        contenedor.style.position = "absolute";
        contenedor.style.top = "-9999px";
        contenedor.style.left = "-9999px";
        contenedor.style.background = "#fff";
        contenedor.style.padding = "20px";

        const tablaClon = tabla.cloneNode(true);
        const tablaClon2 = tabla2.cloneNode(true);

        contenedor.appendChild(tablaClon);
        contenedor.appendChild(tablaClon2);

        if (imagenContainer.style.display !== "none") {
            const imagenClon = imagenContainer.cloneNode(true);
            contenedor.appendChild(imagenClon);
        }

        document.body.appendChild(contenedor);

        html2canvas(contenedor, { useCORS: true, scale: 2 }).then(canvas => {
            canvas.toBlob(function(blob) {
                const formData = new FormData();
                formData.append('imagen', blob, 'reporte_nomina.png');

                // Solo ids es arreglo
                // const ids = [1, 2, 3];
                const idsDesdePHP = <?= json_encode($ids) ?>;

                const fecha = "<?= $fechaactual ?>";
               
                // Otros son datos simples
                // const fecha = '2025-05-02';


                // Enviar
                formData.append('ids', JSON.stringify(idsDesdePHP));
                formData.append('fecha', fecha);


                fetch('primaImgReporteNomina.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exito) {
                        alert("Imagen guardada con éxito: " + data.nombre_archivo);
                    } else {
                        alert("Error: " + data.mensaje);
                    }
                    document.body.removeChild(contenedor);
                })
                .catch(error => {
                    console.error("Error al enviar al servidor:", error);
                    document.body.removeChild(contenedor);
                });
            }, 'image/png');
        });
    }
</script>
</body>
</html>