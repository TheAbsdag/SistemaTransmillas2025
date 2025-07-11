<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comunicados e Inducciones</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    thead.azul-blanco th {
      background-color: #01468c;
      color: white;
    }
  </style>
</head>
<body>

<div class="container mt-4">
  <h2 class="mb-4">Comunicados e Inducciones</h2>

  <div class="row mb-3">
    <div class="col-md-4">
      <label for="estado">Estado</label>
      <select id="filtroEstado" class="form-control">
        <option value="">Seleccionar...</option>
        <option value="pendiente">Pendiente</option>
        <option value="revisado">Revisado</option>
        <option value="validado">Validado</option>
      </select>
    </div>
  </div>

  <div class="d-flex justify-content-end mb-3">
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregarCI">
    <i class="fas fa-plus"></i> Agregar Comunicado / Inducción
  </button>
</div>

  <table id="tablaComunicados" class="table table-bordered">
    <thead class="azul-blanco">
      <tr>
        <th>Documento</th>
        <th>Encargado</th>
        <th>Usuario</th>
        <th>Link Documento</th>
        <th>Archivo</th>
        <th>Estado</th>
        <th>Fecha de Registro</th>
        <th>Confirmación Usuario</th>
        <th>Confirmación Encargado</th>
        <th>Editar</th>
        <th>Eliminar</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
  const tabla = $('#tablaComunicados').DataTable({
    ajax: {
      url: '/testSistemaTransmillas/nueva_plataforma/controller/induccionesComunicadosController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.estado = $('#filtroEstado').val();
      },
      dataSrc: ''
    },
    columns: [
      { data: 'ci_nombre_documento' },
      { data: 'ci_encargado' },
      { data: 'ci_usuario' },
      {
        data: 'ci_link_documento',
        render: function (data) {
          return data ? `<a href="${data}" target="_blank">Ver link</a>` : '—';
        }
      },
      {
        data: 'ci_ruta_archivo',
        render: function (data) {
          return data ? `<a href="/ruta_documentos/${data}" target="_blank">Ver archivo</a>` : '—';
        }
      },
      {
        data: 'ci_estado',
        render: function (data, type, row) {
          const clase = data === 'revisado' ? 'bg-success' :
                        data === 'pendiente' ? 'bg-warning' :
                        'bg-danger';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase} text-white"
                    data-id="${row.ci_id}"
                    data-campo="ci_estado">
              <option value="pendiente" ${data === 'pendiente' ? 'selected' : ''}>Pendiente</option>
              <option value="revisado" ${data === 'revisado' ? 'selected' : ''}>Revisado</option>
              <option value="validado" ${data === 'validado' ? 'selected' : ''}>Validado</option>
            </select>
          `;
        }
      },
      { data: 'ci_fecha_registro' },
      { data: 'ci_fecha_confirmacion_usuario' },
      { data: 'ci_fecha_confirmacion_encargado' },
      {
        data: null,
        orderable: false,
        render: function (data, type, row) {
          return `
            <a href="../../cambio_admin.php?id_param=${row.ci_id}&tabla=Comunicado&condecion="
               class="btn btn-sm btn-outline-primary" title="Editar" target="_blank">
              <i class="fas fa-edit"></i>
            </a>`;
        }
      },
      {
        data: null,
        orderable: false,
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-danger eliminar-usuario"
                    data-id="${row.ci_id}" title="Eliminar">
              <i class="fas fa-trash-alt"></i>
            </button>`;
        }
      }
    ]
  });

  $('#filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });

  $('#tablaComunicados tbody').on('change', '.cambiar-campo', function () {
    const id = $(this).data('id');
    const campo = $(this).data('campo');
    const valor = $(this).val();

    $.ajax({
      url: '/testSistemaTransmillas/nueva_plataforma/controller/induccionesComunicadosController.php',
      type: 'POST',
      data: {
        actualizar_campo: true,
        id: id,
        campo: campo,
        valor: valor
      },
      success: function () {
        tabla.ajax.reload(null, false);
      },
      error: function () {
        alert("Hubo un error al actualizar.");
      }
    });
  });

  $('#tablaComunicados tbody').on('click', '.eliminar-usuario', function () {
    const id = $(this).data('id');
    if (confirm('¿Estás seguro de que deseas eliminar este comunicado?')) {
      $.ajax({
        url: '/testSistemaTransmillas/nueva_plataforma/controller/induccionesComunicadosController.php',
        type: 'POST',
        data: {
          eliminar_usuario: true,
          id: id
        },
        success: function () {
          tabla.ajax.reload(null, false);
        },
        error: function () {
          alert('Error al eliminar el registro.');
        }
      });
    }
  });
});
</script>

</body>
</html>
