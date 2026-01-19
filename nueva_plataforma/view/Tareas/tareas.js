$(document).ready(function () {
  // === Cargar asignaciones de la semana ===
  function cargarAsignaciones() {
    const fechaInicio = $('#fechaInicioSemana').val();
    $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'listarSemana', fecha_inicio: fechaInicio }, function (resp) {
      const tabla = $('#tablaAsignaciones').DataTable();
      tabla.clear().rows.add(resp).draw();
    }, 'json');
  }

  // === Inicializar tabla de asignaciones ===
  $('#tablaAsignaciones').DataTable({
    columns: [
      { data: 'fecha' },
      { data: 'operador' },
      { data: 'cedula' },
      { data: 'tarea' }
    ],
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
    pageLength: 10
  });

  // === Cargar tareas configuradas ===
  function cargarTareasConfig() {
    $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'listarTareas' }, function (resp) {
      const tBody = $('#tablaTareasConfig tbody').empty();
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;

      if (data.length === 0) {
        tBody.append('<tr><td colspan="3" class="text-center text-muted">No hay tareas configuradas</td></tr>');
        return;
      }

      data.forEach(function (r) {
        tBody.append(`
          <tr>
            <td>${r.nombre}</td>
            <td class="text-center">${r.cantidad_usuarios}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-danger btnEliminarTarea" data-id="${r.id}">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        `);
      });
    });
  }

  // === Guardar tarea desde el modal ===
  $('#formTarea').on('submit', function (e) {
    e.preventDefault();
    const formData = $(this).serialize() + '&accion=agregarTarea';

    $.post('/nueva_plataforma/controller/TareasController.php', formData, function (resp) {
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;

      if (data.ok) {
        Swal.fire({
          icon: 'success',
          title: 'Tarea agregada',
          timer: 1500,
          showConfirmButton: false
        });
        $('#modalTarea').modal('hide');
        $('#formTarea')[0].reset();
        cargarTareasConfig();
      } else {
        Swal.fire('Error', data.msg || 'No se pudo guardar la tarea', 'error');
      }
    });
  });

  // === Eliminar tarea configurada ===
  $(document).on('click', '.btnEliminarTarea', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: '¿Eliminar tarea?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'eliminarTarea', id }, function (resp) {
          const data = typeof resp === 'string' ? JSON.parse(resp) : resp;

          if (data.ok) {
            Swal.fire({
              icon: 'success',
              title: 'Eliminada',
              timer: 1200,
              showConfirmButton: false
            });
            cargarTareasConfig();
          } else {
            Swal.fire('Error', data.msg || 'No se pudo eliminar', 'error');
          }
        });
      }
    });
  });

  // === Sortear tareas del día ===
  $('#btnSortear').on('click', function () {
    Swal.fire({
      title: '¿Sortear tareas del día?',
      text: 'Esto asignará operadores automáticamente.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, sortear',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'sortear' }, function (resp) {
          const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
          Swal.fire('Completado', data.msg || 'Tareas sorteadas con éxito', 'success');
          cargarAsignaciones();
        });
      }
    });
  });

  // === Recargar tabla ===
  $('#btnRecargar').on('click', function () {
    cargarAsignaciones();
    cargarTareasConfig();
  });

  // === Inicialización al cargar ===
  cargarAsignaciones();
  cargarTareasConfig();
});


function cargarUsuarios() {
  $.post('/nueva_plataforma/controller/TareasController.php', { accion: 'listarUsuarios' }, function(resp) {
    const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
    const lista = $('#listaUsuarios').empty();

    data.forEach(u => {
      lista.append(`
        <div class="form-check me-3">
          <input class="form-check-input" type="checkbox" name="usuarios[]" value="${u.idusuarios}" id="user_${u.idusuarios}">
          <label class="form-check-label" for="user_${u.idusuarios}">${u.nombre}</label>
        </div>
      `);
    });
  });
}

$('#btnSortear').on('click', function() {
  const seleccionados = $('input[name="usuarios[]"]:checked').map(function() {
    return $(this).val();
  }).get();

  if (seleccionados.length === 0) {
    alert('Selecciona al menos un usuario para el sorteo.');
    return;
  }

  $.ajax({
    url: '/nueva_plataforma/controller/TareasController.php',
    method: 'POST',
    data: { 
      accion: 'sortearTareasSeleccionadas',
      usuarios: JSON.stringify(seleccionados)
    },
    success: function(resp) {
      const data = typeof resp === 'string' ? JSON.parse(resp) : resp;
      if (data.ok) {
        alert('✅ ' + data.msg);
      } else {
        alert('❌ ' + data.msg);
      }
    }
  });
});