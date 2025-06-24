<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Primas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h3>Detalle de Primas</h3>
    <table id="tablaDetallePrimas" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Contrato</th>
                <th>Cargo</th>
                <th>Salario</th>
                <th>Auxilio</th>
                <th>Días Prima</th>
                <th>Total Prima</th>
                <th>Confirmado</th>
                <th>Pagado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($datos as $item): ?>
            <tr>
                <td><?= $item['hoj_nombre'] . ' ' . $item['hoj_apellido'] ?></td>
                <td><?= $item['hoj_cedula'] ?></td>
                <td><?= $item['hoj_tipocontrato'] ?></td>
                <td><?= $item['hoj_cargo'] ?></td>
                <td>$<?= number_format($item['hoj_salario'], 0, ',', '.') ?></td>
                <td>$<?= number_format($item['hoj_auxilio'], 0, ',', '.') ?></td>
                <td><?= $item['hoj_dias_prima'] ?></td>
                <td>$<?= number_format($item['hoj_total_prima'], 0, ',', '.') ?></td>
                <td><?= $item['hoj_confirmado'] ?></td>
                <td><?= $item['hoj_pagado'] ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tablaDetallePrimas').DataTable();
        });
    </script>
</body>
</html>
