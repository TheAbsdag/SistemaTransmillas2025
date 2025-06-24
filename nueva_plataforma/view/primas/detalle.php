<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// require_once '../../controller/DetallePrimasController.php';

$sedes = $this->obtenerSedes($db);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detalle de Primas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h3 class="mb-4">Detalle de Primas</h3>

    <form method="POST" action="?controller=DetallePrimas&action=detalle" class="mb-4">
        <div class="row g-3">
            <div class="col-md-2">
                <label for="param34" class="form-label">Mes</label>
                <select name="param34" id="param34" class="form-select" required>
                    <option value="">Seleccione</option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= ($_POST['param34'] ?? '') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                            <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="param36" class="form-label">Periodo</label>
                <select name="param36" id="param36" class="form-select" required>
                    <option value="">Seleccione</option>
                    <option value="Primera" <?= ($_POST['param36'] ?? '') == 'Primera' ? 'selected' : '' ?>>Primera Quincena</option>
                    <option value="Segunda" <?= ($_POST['param36'] ?? '') == 'Segunda' ? 'selected' : '' ?>>Segunda Quincena</option>
                    <option value="Completo" <?= ($_POST['param36'] ?? '') == 'Completo' ? 'selected' : '' ?>>Mes Completo</option>
                </select>
            </div>

            <select name="param35" class="form-select">
                <option value="">Sede</option>
                <?php foreach ($sedes as $sede): ?>
                    <option value="<?= $sede['id_sedes'] ?>" <?= ($_POST['param35'] ?? '') == $sede['id_sedes'] ? 'selected' : '' ?>>
                        <?= $sede['nombre_sede'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="col-md-3">
                <label for="param33" class="form-label">Cédula</label>
                <input type="text" name="param33" id="param33" class="form-control" value="<?= $_POST['param33'] ?? '' ?>">
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </div>
    </form>

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
