<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validar Guías Enviadas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
<div class="container mt-4">
    <h3 class="bg-primary text-white p-3 rounded">Validar Guías Enviadas</h3>

    <form id="filtroForm" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Fecha de Búsqueda:</label>
            <input type="date" class="form-control" name="fecha" value="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
            <label>Sede Destino:</label>
            <input type="text" class="form-control" name="sedeDestino" placeholder="ID Sede">
        </div>
        <div class="col-md-3">
            <label>Sede Origen:</label>
            <input type="text" class="form-control" name="sedeOrigen" placeholder="ID Sede">
        </div>
        <div class="col-md-3">
            <label>Búsqueda por:</label>
            <select name="param1" class="form-select">
                <option value="">Seleccione</option>
                <option value="ser_consecutivo">Guía</option>
                <option value="ser_paquetedescripcion">Descripción</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Dato:</label>
            <input type="text" name="param2" class="form-control" placeholder="Texto a buscar">
        </div>
        <div class="col-md-3 align-self-end">
            <button type="submit" class="btn btn-success">Buscar</button>
        </div>
    </form>

    <table id="tablaGuias" class="table table-striped">
        <thead class="table-dark text-white">
            <tr>
                <th>Fecha</th>
                <th>Guía</th>
                <th>Paquete</th>
                <th>Descripción</th>
                <th>Piezas</th>
            </tr>
        </thead>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    let tabla = $('#tablaGuias').DataTable({
        ajax: {
            url: '../../../controller/ValidarGuiaController.php',
            type: 'POST',
            data: function (d) {
                return $('#filtroForm').serialize() + '&ajax=1';
            },
            dataSrc: ''
        },
        columns: [
            { data: 'ser_fechaguia' },
            { data: 'ser_consecutivo' },
            { data: 'ser_tipopaquete' },
            { data: 'ser_paquetedescripcion' },
            { data: 'ser_piezas' }
        ]
    });

    $('#filtroForm').on('submit', function (e) {
        e.preventDefault();
        tabla.ajax.reload();
    });
});
</script>
</body>
</html>