<?php
// Ejemplo: control de acceso similar al que usas en otras vistas
if (!isset($_POST['sede']) || !isset($_POST['acceso'])) {
    echo "<script>alert('No tiene acceso a esta página'); window.close();</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Tareas Semanales</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="container-fluid mt-4">
    <div class="card shadow-sm">
      <div class="card-header text-white" style="background-color:#00468B;">
        <h5 class="mb-0 text-center">Módulo: Asignación de Tareas Semanales</h5>
      </div>

      <div class="card-body">
        <?php if ($_POST['acceso']!=3) { ?>


        <div class="row mb-3">
          <div class="col-md-4 d-flex flex-column justify-content-end">
            <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalAgregarTarea">
              ➕ Agregar Tarea
            </button>
          </div>

          <div class="col-md-4">
            <label class="form-label">Semana (lunes)</label>
            <input type="date" id="fechaInicioSemana" class="form-control" value="<?= date('Y-m-d', strtotime('monday this week')) ?>">
          </div>

          <div class="col-md-4 d-flex flex-column justify-content-end">
            <label class="form-label">Seleccionar tarea a sortear</label>
            <select id="tareaSeleccionada" class="form-select mb-2">
              <option value="">-- Todas las tareas --</option>
            </select>
            <button id="btnSortear" class="btn btn-warning mb-2">🎲 Sortear tareas del día <?= date('Y-m-d') ?></button>
            <button id="btnRecargar" class="btn btn-outline-secondary">🔄 Recargar tabla</button>
          </div>
        </div>
        <?php } ?>
        <hr>

        <!-- Título con botón para mostrar/ocultar -->
        <h6 class="mb-3">
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#listaUsuariosCollapse" aria-expanded="false" aria-controls="listaUsuariosCollapse">
            Selecciona los usuarios que participarán en el sorteo
        </button>
        </h6>

        <!-- Contenedor colapsable -->
        <div class="collapse" id="listaUsuariosCollapse">
        <div id="listaUsuarios" class="d-flex flex-column gap-2 mb-4">
            <!-- Aquí se cargan dinámicamente los usuarios -->
        </div>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="selectAllUsuarios">
          <label class="form-check-label" for="selectAllUsuarios">Seleccionar todos</label>
        </div>

        <hr>

        <div class="table-responsive">
          <table id="tablaAsignaciones" class="table table-striped table-bordered" style="width:100%">
            <thead class="table-secondary">
              <tr>
                <th>Id</th>
                <th>Fecha</th>
                <th>Operador</th>
                <th>Cédula</th>
                <th>Tarea</th>
                <th class="text-center">Acción</th>

              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>

        <hr>

        <h6>Tareas configuradas</h6>
        <div class="table-responsive">
          <table id="tablaTareasConfig" class="table table-sm table-bordered w-50">
            <thead>
              <tr>
                <th>Tarea</th>
                <th>Cantidad</th>
                <th class="text-center">Acción</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 🟦 Modal para agregar tarea -->
  <div class="modal fade" id="modalAgregarTarea" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Agregar nueva tarea</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formTarea">
            <div class="mb-3">
              <label class="form-label">Nombre de la tarea</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Cantidad de operadores</label>
              <input type="number" name="cantidad" class="form-control" min="1" value="1" required>
            </div>
            <button class="btn btn-success w-100" type="submit">Guardar tarea</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

  <script>
$(document).ready(function () {

const tablaAsignaciones = $('#tablaAsignaciones').DataTable({
    columns: [
        { data: 'id', visible: false },
        { data: 'fecha' },
        { data: 'operador' },
        { data: 'cedula' },
        { data: 'tarea' },
        {
            data: null,
            className: 'text-center',
            orderable: false,
            render: function (data) {
                return `
                    <button class="btn btn-danger btn-sm eliminar-asignacion"
                        data-id="${data.id}">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                `;
            }
        }
    ],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    pageLength: 10
});

  function cargarAsignaciones() {
    const fechaInicio = $('#fechaInicioSemana').val();
    $.post('/nueva_plataforma/controller/TareasController.php', 
      { accion: 'listarSemana', fecha_inicio: fechaInicio }, 
      function (resp) {
        const data = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        tablaAsignaciones.clear().rows.add(data).draw();
      }, 'json'
    ).fail(function(xhr) {
      console.error('Error al cargar asignaciones:', xhr.responseText);
    });
  }

  function cargarTareasConfig() {
    const fechaInicio = $('#fechaInicioSemana').val();
    $.post('/nueva_plataforma/controller/TareasController.php', 
      { accion: 'listarTareas', fecha_inicio: fechaInicio }, 
      function (resp) {
        const tBody = $('#tablaTareasConfig tbody').empty();
        const data = typeof resp === 'string' ? JSON.parse(resp) : resp;

        if (!data || data.length === 0) {
          tBody.append('<tr><td colspan="3" class="text-center text-muted">No hay tareas configuradas</td></tr>');
          return;
        }

        data.forEach(function (r) {
          tBody.append(`
            <tr>
              <td>${r.nombre}</td>
              <td class="text-center">${r.cantidad_usuarios}</td>
              <td class="text-center">
                <button class="btn btn-sm btn-danger eliminar-tarea" data-id="${r.id}">
                  <i class="fa fa-trash"></i>
                </button>
              </td>
            </tr>
          `);
        });
      }, 'json'
    ).fail(function(xhr) {
      console.error('Error al cargar tareas:', xhr.responseText);
    });
  }

  function cargarTareasParaSelect() {
    $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'listarTareas' }, function(resp) {
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
      const select = $('#tareaSeleccionada');
      select.empty().append('<option value="">-- Todas las tareas --</option>');
      data.forEach(t => {
        select.append(`<option value="${t.id}">${t.nombre}</option>`);
      });
    }, 'json').fail(function(xhr) {
      console.error('Error al cargar tareas para el select:', xhr.responseText);
    });
  }

  function cargarUsuarios() {
    $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'listarUsuarios' }, function(resp) {
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
      const lista = $('#listaUsuarios').empty();
      data.forEach(u => {
        lista.append(`
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="usuarios[]" value="${u.idusuarios}" id="user_${u.idusuarios}">
            <label class="form-check-label" for="user_${u.idusuarios}">${u.nombre}</label>
          </div>
        `);
      });
    }, 'json').fail(function(xhr){ console.error('Error al cargar usuarios:', xhr.responseText); });
  }

  $(document).on('change', '#selectAllUsuarios', function() {
    const checked = $(this).is(':checked');
    $('input[name="usuarios[]"]').prop('checked', checked);
  });

  $('#formTarea').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + '&accion=agregarTarea';
    $.post('/nueva_plataforma/controller/TareasController.php', formData, function (resp) {
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
      if (data.ok) {
        Swal.fire({ icon: 'success', title: 'Tarea agregada', timer: 1200, showConfirmButton: false });
        $('#modalAgregarTarea').modal('hide');
        $('#formTarea')[0].reset();
        cargarTareasConfig();
        cargarTareasParaSelect();
      } else {
        Swal.fire('Error', data.msg || 'No se pudo guardar la tarea', 'error');
      }
    }, 'json').fail(function(xhr){ Swal.fire('Error', 'Error en la petición', 'error'); console.error(xhr.responseText); });
  });

  $(document).on('click', '.eliminar-tarea', function () {
    const id = $(this).data('id');
    Swal.fire({
      title: '¿Eliminar tarea?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'eliminarTarea', id }, function (resp) {
          const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
          if (data.ok) {
            Swal.fire({ icon: 'success', title: 'Eliminada', timer: 1000, showConfirmButton: false });
            cargarTareasConfig();
            cargarTareasParaSelect();
            cargarAsignaciones();
          } else {
            Swal.fire('Error', data.msg, 'error');
          }
        }, 'json').fail(function(xhr){ Swal.fire('Error', 'Error en la petición', 'error'); console.error(xhr.responseText); });
      }
    });
  });

  $('#btnSortear').on('click', function () {
    const seleccionados = $('input[name="usuarios[]"]:checked').map(function () {
      return $(this).val();
    }).get();

    if (seleccionados.length === 0) {
      Swal.fire('Atención', 'Selecciona al menos un usuario para el sorteo.', 'warning');
      return;
    }

    const fechaInicio = $('#fechaInicioSemana').val();
    const tareaSeleccionada = $('#tareaSeleccionada').val();

    $.ajax({
      url: '/nueva_plataforma/controller/TareasController.php',
      method: 'POST',
      dataType: 'json',
      data: {
        accion: 'sortearTareasSeleccionadas',
        usuarios: JSON.stringify(seleccionados),
        fecha: fechaInicio,
        tarea_id: tareaSeleccionada
      },
      success: function (data) {
        if (data.ok) {
          Swal.fire('✅ Éxito', data.msg || 'Tareas sorteadas correctamente', 'success');
          cargarAsignaciones();
        } else {
          Swal.fire('❌ Error', data.msg || 'Ocurrió un error', 'error');
        }
      },
      error: function (xhr) {
        console.error('Error al sortear:', xhr.responseText);
        Swal.fire('❌ Error', 'Error en la petición', 'error');
      }
    });
  });

  $('#btnRecargar').on('click', function () {
    cargarUsuarios();
    cargarTareasConfig();
    cargarAsignaciones();
    // cargarTareasParaSelect();
  });

  $('#fechaInicioSemana').on('change', function () {
    cargarTareasConfig();
    cargarAsignaciones();
    cargarTareasParaSelect();
  });

  cargarUsuarios();
  cargarTareasConfig();
  cargarAsignaciones();
  cargarTareasParaSelect();

  $(document).on('click', '.eliminar-asignacion', function () {

    const id = $(this).data('id');

    Swal.fire({
        title: '¿Eliminar asignación?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) {

            $.post(
                '/nueva_plataforma/controller/TareasController.php',
                { accion: 'eliminarAsignacion', id },
                function (resp) {

                    const data = typeof resp === 'string' ? JSON.parse(resp) : resp;

                    if (data.ok) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Asignación eliminada',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        // aquí recargas tus listas
                        cargarAsignaciones();

                    } else {
                        Swal.fire('Error', data.msg, 'error');
                    }
                },
                'json'
            ).fail(function (xhr) {
                Swal.fire('Error', 'Error en la petición', 'error');
                console.error(xhr.responseText);
            });

        }
    });
});
});


  </script>
</body>
</html>
