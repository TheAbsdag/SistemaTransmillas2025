<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>CorreosFacturacion</title>
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
.mi-header {
        background-color: #00458D; /* Naranja por ejemplo */
        color: white;
}
</style>
<body>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">Correos de facturacion</h3>
    </div>
    <div class="card-body">
      <div class="row mb-3 align-items-end">
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

      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
                <th>De</th>
                <th>Contenido</th>
                <th>adjuntos</th>
                <th>Responder</th>
                

            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalResponder" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formResponder" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Responder correo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="email_id" id="email_id">

          <div class="mb-3">
            <label>Para</label>
            <input type="email" class="form-control" name="para" id="correoDestino" required>
          </div>

          <div class="mb-3">
            <label>Mensaje</label>
            <textarea class="form-control" name="mensaje" rows="6" required></textarea>
          </div>

          <div class="mb-3">
            <label>Adjunto</label>
            <input type="file" class="form-control" name="adjunto">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Enviar
          </button>
        </div>
      </form>
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
    url: '/nueva_plataforma/controller/CorreoFacturasController.php',
    type: 'POST',
    data: function (d) {
        d.ajax = true;
    },
    dataSrc: ''
    },
    columns: [
    { data: 'from' },
    { data: 'subject' },
    { data: 'attachments' },
    {
    data: null,
    orderable: false,
    render: function (data, type, row) {
        return `
        <button class="btn btn-sm btn-primary btn-responder"
                data-id="${row.id}"
                data-from="${row.from}">
            <i class="fas fa-reply"></i>
        </button>
        `;
    }
    }

    ]
  });

  $('#filtroRol, #filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });
});

$(document).on('click', '.btn-responder', function () {
  const email = $(this).data('from');
  const id = $(this).data('id');

  $('#correoDestino').val(email);
  $('#email_id').val(id);

  $('#modalResponder').modal('show');
});

$('#formResponder').on('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  formData.append('accion', 'responder');

  $.ajax({
    url: '/nueva_plataforma/controller/CorreoController.php',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function (res) {
      alert('✅ Correo enviado correctamente');
      $('#modalResponder').modal('hide');
      $('#tablaUsuarios').DataTable().ajax.reload();
    },
    error: function () {
      alert('❌ Error al enviar el correo');
    }
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
    url: '/nueva_plataforma/controller/UsuarioController.php',
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
      url: '/nueva_plataforma/controller/UsuarioController.php',
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
