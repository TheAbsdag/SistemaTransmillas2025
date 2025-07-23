<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
<!-- Bootstrap 5 CSS desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
thead.azul-blanco th {
  background-color: #01468c; /* Tu azul exacto */
  color: white;
}
</style>
<body>
<div class="container mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-white text-center bg-primary">
      <h3 class="mb-0">Módulo WhatsApp</h3>
    </div>

    <div class="card-body">
      <div class="row mb-3 align-items-end">
        <div class="col-md-4">
          <label for="filtroFecha" class="form-label">📅 Fecha</label>
          <input type="date" id="filtroFecha" class="form-control" />
        </div>

        <div class="col-md-4">
          <label for="filtroTipoMensaje" class="form-label">💬 Tipo de mensaje</label>
          <select id="filtroTipoMensaje" class="form-select">
            <option value="">Todos</option>
            <option value="Alertas">Alertas</option>
            <option value="ChatBot">Chat Bot</option>
            <option value="ServiciosHechos">Servicios Hechos</option>
            <option value="manual">Mensaje manual</option>
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>📆 Fecha</th>
              <th>📥 Mensaje recibido</th>
              <th>📤 Mensaje enviado</th>
              <th>🆔 ID WhatsApp</th>
              <th>📞 Número</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
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
      url: '/testSistemaTransmillas/nueva_plataforma/controller/WhatsappController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.fecha = $('#filtroFecha').val();
        d.tipo = $('#filtroTipoMensaje').val();
      },
      dataSrc: ''
    },
    columns: [
      { data: 'fecha_hora' },
      { data: 'mensaje_recibido' },
       { data: 'mensaje_enviado' },
      { data: 'id_wa' },
      { data: 'telefono_wa' }
      
      // 🔁 Interactivo: usu_filtro → Ver en sistema
         // 🔁 Interactivo: usu_filtro → Ver en sistema
    //   {
    //     data: 'usu_filtro',
    //     render: function (data, type, row) {
    //       const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
    //       return `
    //         <select class="form-select form-select-sm cambiar-campo ${clase}"
    //                 data-id="${row.idusuarios}"
    //                 data-campo="usu_filtro">
    //           <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
    //           <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
    //         </select>
    //       `;
    //     }
    //   },
    //   {
    //     data: 'usu_ver_nomina',
    //     render: function (data, type, row) {
    //       const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
    //       return `
    //         <select class="form-select form-select-sm cambiar-campo ${clase}"
    //                 data-id="${row.idusuarios}"
    //                 data-campo="usu_ver_nomina">
    //           <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
    //           <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
    //         </select>
    //       `;
    //     }
    //   },
    //   {
    //     data: 'usu_estado',
    //     render: function (data, type, row) {
    //       const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
    //       return `
    //         <select class="form-select form-select-sm cambiar-campo ${clase}"
    //                 data-id="${row.idusuarios}"
    //                 data-campo="usu_estado">
    //           <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
    //           <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
    //         </select>
    //       `;
    //     }
    //   },
    //   {
    //     data: null,
    //     orderable: false,
    //     searchable: false,
    //     render: function (data, type, row) {
    //       return `
    //         <a href="../../cambio_admin.php?id_param=${row.idusuarios}&tabla=Usuario&condecion=" 
    //           class="btn btn-sm btn-outline-primary" title="Editar" target="_blank">
    //           <i class="fas fa-edit"></i>
    //         </a>
    //       `;
    //     }
    //   },
    //   {
    //     data: null,
    //     orderable: false,
    //     searchable: false,
    //     render: function (data, type, row) {
    //       return `
    //         <button class="btn btn-sm btn-danger eliminar-usuario"
    //                 title="Eliminar"
    //                 data-id="${row.idusuarios}">
    //           <i class="fas fa-trash-alt"></i>
    //         </button>
    //       `;
    //     }
    //   }
      ]
  });

  $('#filtroFecha, #filtroTipoMensaje').on('change', function () {
    tabla.ajax.reload();
  });
});

// 🔁 Detectar cambios en cualquier campo editable
$('#tablaUsuarios tbody').on('change', '.cambiar-campo', function () {
  const id = $(this).data('id');
  const campo = $(this).data('campo');
  const valor = $(this).val();

  // if(id == "usu_estado" and valor==0){
  //   alert('Está apunto de desactivar al usuario, recuerde colocar fecha de finalizacion en la hoja de vida si aun no lo ha hecho');

  // }

  $.ajax({
    url: '/testSistemaTransmillas/nueva_plataforma/controller/WhatsappController.php',
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
$('#tablaUsuarios tbody').on('click', '.eliminar-usuario', function () {
  const id = $(this).data('id');

  if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
    $.ajax({
      url: '/testSistemaTransmillas/nueva_plataforma/controller/WhatsappController.php',
      type: 'POST',
      data: {
        eliminar_usuario: true,
        id: id
      },
      success: function (res) {
        $('#tablaUsuarios').DataTable().ajax.reload(null, false);
      },
      error: function () {
        alert('Error al eliminar el usuario.');
      }
    });
  }
});
</script>
</body>
</html>
