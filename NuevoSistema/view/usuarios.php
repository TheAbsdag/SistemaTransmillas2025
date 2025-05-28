<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
  <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/datatables/datatables.min.css">
</head>
<body>
<div class="container mt-4">
  <h2 class="mb-4">Usuarios</h2>

  <div class="row mb-3">
    <div class="col-md-4">
      <label for="rol">Rol</label>
      <select id="filtroRol" class="form-control">
        <option value="">Seleccionar...</option>
        <!-- Opciones cargadas desde PHP -->
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

  <table id="tablaUsuarios" class="table table-striped">
    <thead>
      <tr>
        <th>Rol</th>
        <th>Nombre</th>
        <th>Usuario</th>
        <th>Profesión</th>
        <th>Contrato</th>
        <th>Ver en sistema</th>
        <th>Ver en nómina</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $usuario): ?>
        <tr>
          <td><?= $usuario['rol_nombre'] ?></td>
          <td><?= $usuario['usu_nombre'] ?></td>
          <td><?= $usuario['usu_usuario'] ?></td>
          <td><?= $usuario['usu_nivelacademico'] ?></td>
          <td><?= $usuario['usu_tipocontrato'] ?></td>
          <td><?= $usuario['usu_filtro'] == '1' ? 'Activo' : 'Inactivo' ?></td>
          <td><?= $usuario['usu_ver_nomina'] == '1' ? 'Activo' : 'Inactivo' ?></td>
          <td>
            <a href="#" class="btn btn-sm btn-primary">Editar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script src="../../assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="../../assets/datatables/datatables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#tablaUsuarios').DataTable();

    $('#filtroRol, #filtroEstado').on('change', function() {
      // podrías hacer filtrado local aquí o recargar con AJAX
    });
  });
</script>
</body>
</html>