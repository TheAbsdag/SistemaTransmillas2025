<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Correos Enviados</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    .mi-header {
      background-color: #00458D;
      color: white;
    }
    thead.azul-blanco th {
      background-color: #01468c;
      color: white;
      text-align: center;
    }
  </style>
</head>

<body>

<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">

    <!-- HEADER -->
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">
        <i class="fas fa-envelope"></i> Correos enviados
      </h3>
    </div>

    <!-- BODY -->
    <div class="card-body">

      <!-- FILTROS (listos para crecer) -->
      <div class="row mb-3 align-items-end">
        <div class="col-md-3">
            <label>Bandeja</label>
        <select id="tipoCorreo" class="form-control">
        <option value="enviados">Enviados</option>
        <option value="recibidos">Recibidos</option>
        </select>
        </div>
        <div class="col-md-3">
          <label>Estado</label>
          <select id="filtroEstado" class="form-control">
            <option value="">Todos</option>
            <option value="enviado">Enviado</option>
            <option value="error">Error</option>
          </select>
        </div>


        <div class="col-md-3">
          <label>Desde</label>
          <input type="date" id="fechaDesde" class="form-control">
        </div>

        <div class="col-md-3">
          <label>Hasta</label>
          <input type="date" id="fechaHasta" class="form-control">
        </div>
      </div>

      <!-- TABLA -->
      <div class="table-responsive">
        <table id="tablaCorreos"
               class="table table-hover table-bordered align-middle text-center w-100">
          <thead class="azul-blanco">
            <tr>
              <th>Para</th>
              <th>Asunto</th>
              <th>Adjunto</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- MODAL VER CORREO -->
<div class="modal fade" id="modalVerCorreo" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header mi-header">
        <h5 class="modal-title">
          <i class="fas fa-eye"></i> Detalle del correo
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="detalleCorreo"></div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {

  const tabla = $('#tablaCorreos').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/CorreosEnviadosController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.estado = $('#filtroEstado').val();
        d.desde  = $('#fechaDesde').val();
        d.hasta  = $('#fechaHasta').val();
        d.tipo   = $('#tipoCorreo').val();
  

      },
      dataSrc: ''
    },
    order: [[4, 'desc']],
    columns: [
      { data: 'correo_destino' },
      { data: 'asunto' },
      {
        data: 'nombre_archivo',
        render: d => d
          ? `<i class="fas fa-paperclip"></i> ${d}`
          : ''
      },
      {
        data: 'estado',
        render: e => e === 'enviado'
          ? '<span class="badge bg-success">Enviado</span>'
          : '<span class="badge bg-danger">Error</span>'
      },
      { data: 'fecha_envio' },
      {
        data: null,
        orderable: false,
        render: row => {

        let btnDescargar = '';
        if (row.nombre_archivo) {
            btnDescargar = `
            <a class="btn btn-sm btn-secondary"
                title="Descargar adjunto"
                href="/nueva_plataforma/controller/CorreosEnviadosController.php?accion=descargar&id=${row.id}">
                <i class="fas fa-download"></i>
            </a>
            `;
        }

        return `
            <button class="btn btn-sm btn-info ver-correo"
                    data-id="${row.id}">
            <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-warning reenviar-correo"
                    data-id="${row.id}">
            <i class="fas fa-paper-plane"></i>
            </button>
            ${btnDescargar}
        `;
        }
      }
    ]
  });

  $('#filtroEstado, #fechaDesde, #fechaHasta').on('change', function () {
    tabla.ajax.reload();
  });

});
$(document).on('click', '.ver-correo', function () {
    const id = $(this).data('id');

    $.ajax({
        url: '/nueva_plataforma/controller/CorreosEnviadosController.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (res) {
            if (res.ok) {
                $('#detalleCorreo').html(res.html);
                $('#modalVerCorreo').modal('show');
            } else {
                alert('No se pudo cargar el correo');
            }
        },
        error: function () {
            alert('Error al consultar el correo');
        }
    });
});
  /* ==========================
     ✈️ REENVIAR CORREO
  ========================== */
  $(document).on('click', '.reenviar-correo', function () {
    const id = $(this).data('id');

    if (!confirm('¿Deseas reenviar este correo?')) return;

    $.ajax({
      url: '/nueva_plataforma/controller/CorreosEnviadosController.php',
      type: 'POST',
      dataType: 'json',
      data: {
        accion: 'reenviar',
        id: id
      },
      success: function (res) {
        if (res.ok) {
          alert('✅ ' + res.msg);
          tabla.ajax.reload(null, false); // sin perder página
        } else {
          alert('❌ ' + res.msg);
        }
      },
      error: function () {
        alert('❌ Error de servidor al reenviar');
      }
    });
  });

</script>
