<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
<!-- Bootstrap 5 CSS desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
        <th>Estado</th>
      </tr>
    </thead>
    <tbody></tbody>
</table>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <!-- ✅ DataTables desde CDN -->

<script>
$(document).ready(function () {
  const tabla = $('#tablaUsuarios').DataTable({
    ajax: {
      url: '/testSistemaTransmillas/nueva_plataforma/controller/UsuarioController.php',
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
      // 🔁 Interactivo: usu_filtro → Ver en sistema
         // 🔁 Interactivo: usu_filtro → Ver en sistema
      {
        data: 'usu_filtro',
        render: function (data, type, row) {
          const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase}"
                    data-id="${row.idusuarios}"
                    data-campo="usu_filtro">
              <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
              <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
            </select>
          `;
        }
      },
      {
        data: 'usu_ver_nomina',
        render: function (data, type, row) {
          const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase}"
                    data-id="${row.idusuarios}"
                    data-campo="usu_ver_nomina">
              <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
              <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
            </select>
          `;
        }
      },
      {
        data: 'usu_estado',
        render: function (data, type, row) {
          const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase}"
                    data-id="${row.idusuarios}"
                    data-campo="usu_estado">
              <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
              <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
            </select>
          `;
        }
      }
      ]
  });

  $('#filtroRol, #filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });
});

// 🔁 Detectar cambios en cualquier campo editable
$('#tablaUsuarios tbody').on('change', '.cambiar-campo', function () {
  const id = $(this).data('id');
  const campo = $(this).data('campo');
  const valor = $(this).val();

  $.ajax({
    url: '/testSistemaTransmillas/nueva_plataforma/controller/UsuarioController.php',
    type: 'POST',
    data: {
      actualizar_campo: true,
      id: id,
      campo: campo,
      valor: valor
    },
    success: function (res) {
      $('#tablaUsuarios').DataTable().ajax.reload(null, false);
    },
    error: function () {
      alert("Hubo un error al actualizar.");
    }
  });
});
</script>
</body>
</html>
