
$(document).ready(function () {
  const tabla = $('#tablaUsuarios').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/UsuarioController.php',
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
       { data: 'usu_identificacion' },
      { data: 'usu_usuario' },
      { data: 'usu_nivelacademico' },
      { data: 'usu_tipocontrato' },
      // 🔁 Interactivo: agregar dispositivo
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button
              class="btn btn-sm btn-info"
              title="Dispositivos"
              onclick="verDispositivos(${row.idusuarios})">
              <i class="fas fa-laptop"></i>
            </button>
          `;
        }
      },
         // 🔁 Interactivo: usu_filtro → Ver en sistema
      {
        data: 'usu_filtro',
        render: function (data, type, row) {
          const clase = data == 1 ? 'estado-activo' : 'estado-inactivo';
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
          const clase = data == 1 ? 'estado-activo' : 'estado-inactivo';
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
          const clase = data == 1 ? 'estado-activo' : 'estado-inactivo';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase}"
                    data-id="${row.idusuarios}"
                    data-campo="usu_estado">
              <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
              <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
            </select>
          `;
        }
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <a href="../../cambio_admin.php?id_param=${row.idusuarios}&tabla=Usuario&condecion=" 
              class="btn btn-sm btn-outline-primary" title="Editar" target="_blank">
              <i class="fas fa-edit"></i>
            </a>
          `;
        }
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-danger eliminar-usuario"
                    title="Eliminar"
                    data-id="${row.idusuarios}">
              <i class="fas fa-trash-alt"></i>
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

function verDispositivos(idUsuario) {

  window.usuarioDispositivoActual = idUsuario;

  $.ajax({
    url: '/nueva_plataforma/controller/UsuarioController.php',
    type: 'POST',
    dataType: 'json',
    data: {
      listar_dispositivos: true,
      idusuario: idUsuario
    },
    success: function (data) {

      let tbody = $('#tablaDispositivos tbody');
      tbody.html('');

      if (data.length === 0) {
        tbody.append(`
          <tr>
            <td colspan="6" class="text-muted">
              No hay dispositivos asociados
            </td>
          </tr>
        `);
      }

      data.forEach(d => {

        const estado = d.authorized == 1
          ? '<span class="badge bg-success">Autorizado</span>'
          : '<span class="badge bg-warning text-dark">Pendiente</span>';

        const accion = d.authorized == 1
          ? `<button class="btn btn-sm btn-danger" onclick="bloquearDispositivo(${d.id})">
               <i class="fas fa-ban"></i>
             </button>`
          : `<button class="btn btn-sm btn-success" onclick="autorizarDispositivo(${d.id})">
               <i class="fas fa-check"></i>
             </button>`;

        tbody.append(`
          <tr>
            <td>${d.device_name ?? 'Sin nombre'}</td>
            <td>${d.device_type ?? '-'}</td>
            <td>${d.last_login ?? '-'}</td>
            <td>${d.ip_last ?? '-'}</td>
            <td>${estado}</td>
            <td>${accion}</td>
          </tr>
        `);
      });

      $('#modalDispositivos').modal('show');
    }
  });
}
function autorizarDispositivo(idDispositivo) {

  if (!confirm('¿Autorizar este dispositivo?')) return;

  $.ajax({
    url: '/nueva_plataforma/controller/UsuarioController.php',
    type: 'POST',
    dataType: 'json',
    data: {
      autorizar_dispositivo: true,
      id: idDispositivo
    },
    success: function (res) {
      if (res.ok) {
        // recargar la lista sin cerrar el modal
        verDispositivos(window.usuarioDispositivoActual);
      }
    }
  });
}
function bloquearDispositivo(idDispositivo) {

  if (!confirm('¿Bloquear este dispositivo?')) return;

  $.ajax({
    url: '/nueva_plataforma/controller/UsuarioController.php',
    type: 'POST',
    dataType: 'json',
    data: {
      bloquear_dispositivo: true,
      id: idDispositivo
    },
    success: function (res) {
      if (res.ok) {
        // recargar la lista sin cerrar el modal
        verDispositivos(window.usuarioDispositivoActual);
      }
    }
  });
}
