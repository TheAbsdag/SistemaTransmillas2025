<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
  <!-- ✅ jQuery primero -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- ✅ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

 <!-- ✅ Bootstrap 5 desde CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ DataTables desde CDN -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4">Usuarios</h2>

  <div class="row mb-3">
    <div class="col-md-4">
      <label for="rol">Rol</label>
      <select id="filtroRol" class="form-control">
        <option value="">Seleccionar...</option>
        <?php foreach ($roles as $rol): ?>
          <option value="<?= $rol['idroles'] ?>"><?= $rol['rol_nombre'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label for="estado">Estado</label>
      <select id="filtroEstado" class="form-control">
        <option value="">Seleccionar...</option>
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
      </select>
    </div>
  </div>

  <table id="tablaUsuarios" class="table table-bordered">
    <thead>
      <tr>
        <th>Rol</th>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Profesión</th>
        <th>Contrato</th>
        <th>Ver en sistema</th>
        <th>Ver en nómina</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>


<script>
$(document).ready(function () {
  const tabla = $('#tablaUsuarios').DataTable({
    ajax: {
      url: '../../controller/UsuarioController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.rol = $('#filtroRol').val();
        d.estado = $('#filtroEstado').val();
      },
      dataSrc: ''
    },
    columns: [
      { data: 'rol_nombre' },
      { data: 'usu_nombre' },
      { data: 'usu_usuario' },
      { data: 'usu_nivelacademico' },
      { data: 'usu_tipocontrato' },
      { data: 'usu_filtro', render: d => d == '1' ? 'Activo' : 'Inactivo' },
      { data: 'usu_ver_nomina', render: d => d == '1' ? 'Activo' : 'Inactivo' }
    ]
  });

  $('#filtroRol, #filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });
});
</script>
</body>
</html>
