<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Guías Enviadas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
</head>
<body class="container py-4">
    <h2 class="mb-4">Validar Guías Enviadas</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <label>Fecha de Búsqueda</label>
            <input type="date" id="fecha" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label>Sede Destino</label>
            <select id="sedeDestino" class="form-select"></select>
        </div>
        <div class="col-md-3">
            <label>Sede Origen</label>
            <select id="sedeOrigen" class="form-select"></select>
        </div>
        <div class="col-md-3">
            <label>Buscar por</label>
            <input type="text" id="param2" class="form-control" placeholder="Guía, Descripción, etc.">
            <input type="hidden" id="param1" value="ser_consecutivo">
        </div>
    </div>

    <table id="tablaGuias" class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>Fecha</th>
                <th>Guía</th>
                <th>Paquete</th>
                <th>Descripción</th>
                <th>Piezas</th>
                <th>Validar</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        $(document).ready(function () {
            $('#tablaGuias').DataTable();
            cargarGuias();

            $('#fecha, #sedeDestino, #sedeOrigen, #param2').on('change keyup', function () {
                cargarGuias();
            });
        });

        function cargarGuias() {
            const data = {
                ajax: true,
                fecha: $('#fecha').val(),
                sedeDestino: $('#sedeDestino').val(),
                sedeOrigen: $('#sedeOrigen').val(),
                param1: $('#param1').val(),
                param2: $('#param2').val(),
            };

            $.post("/testSistemaTransmillas/nueva_plataforma/controller/ValidarGuiaController.php", data, function (respuesta) {
                const datos = JSON.parse(respuesta);
                const tabla = $('#tablaGuias').DataTable();
                tabla.clear();

                datos.forEach(d => {
                    tabla.row.add([
                        d.ser_fechaguia,
                        d.ser_consecutivo,
                        d.ser_tipopaquete,
                        d.ser_paquetedescripcion,
                        d.ser_piezas,
                        `<select class="form-select" onchange="validarGuia('${d.ser_consecutivo}', this.value)">
                            <option value="">Seleccione</option>
                            <option value="OK">OK</option>
                            <option value="Error">Error</option>
                        </select>`
                    ]);
                });

                tabla.draw();
            });
        }

        function validarGuia(guia, valor) {
            $.post("/testSistemaTransmillas/nueva_plataforma/controller/ValidarGuiaController.php", {
                actualizar_validacion: true,
                guia: guia,
                valor: valor
            }, function (respuesta) {
                const res = JSON.parse(respuesta);
                if (res.ok) {
                    alert("Guía validada correctamente");
                } else {
                    alert("Error al validar la guía");
                }
            });
        }
    </script>
</body>
</html>
