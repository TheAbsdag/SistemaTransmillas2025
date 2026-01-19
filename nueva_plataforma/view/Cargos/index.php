<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Cargos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../images/Logo Google Nuevo.png">
<!-- Bootstrap 5 CSS desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
        <div class="container-fluid">
            <!-- Botón de regreso -->
            <button class="btn btn-light" onclick="history.back()">⬅ Volver</button>
        </div>
      <h3 class="mb-0">Cargos</h3>
    </div>

    <div class="card-body">
        <div class="row mb-3 align-items-end">


        <div class="col-md-4">
            <label for="filtroContrato" class="form-label">Tipo de contrato</label>
            <select id="filtroContrato" class="form-select">
                <option value="">Todos</option>
                <option value="Empresa">Empresa</option>
                <option value="Prestacion de servicios">Prestación de servicios</option>
            </select>
        </div>

        <div class="col-md-4 text-end">
            <label class="form-label d-block invisible">Botón</label>
            <button 
                class="btn btn-primary text-white w-100"
                data-bs-toggle="modal"
                data-bs-target="#modalNuevoCargo">
                <i class="bi bi-plus-circle me-1"></i> Nuevo cargo
            </button>
        </div>
        </div>

      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
                <th>Cargo</th>
                <th>Tipo de Contrato</th>
                <th>Editar</th>
                <th>Eliminar</th>


            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>


<!-- Modal Editar Cargo -->
<div class="modal fade" id="modalServicioAuto" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <!-- HEADER -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar cargo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <!-- ================= DATOS DEL CARGO ================= -->
        <h5 class="mb-3">Datos del cargo</h5>

        <div class="row g-3">

          <input type="hidden" name="idcargo">

          <!-- Nombre cargo -->
          <div class="col-md-6">
            <label class="form-label">Cargo</label>
            <input type="text" name="cargo" class="form-control">
          </div>

          <!-- Tipo contrato -->
          <div class="col-md-6">
            <label class="form-label">Tipo contrato</label>
            <select name="Tipo_Contrato" class="form-select">
              <option value="Empresa">Empresa</option>
              <option value="Prestacion de servicios">Prestación de servicios</option>
            </select>
          </div>

          <!-- Recogida -->
          <div class="col-md-3">
            <label class="form-label">¿Tiene recogida?</label>
            <select name="recogida" id="recogida" class="form-select">
              <option value="NO">NO</option>
              <option value="SI">SI</option>
            </select>
          </div>

          <!-- Valor recogida -->
          <div class="col-md-3">
            <label class="form-label">Valor recogida</label>
            <div class="input-group">
              <span class="input-group-text">$</span>
              <input type="number" name="valor_recogida" class="form-control" disabled>
            </div>
          </div>

        </div>

        <hr class="my-4">

        <!-- ================= HISTORIAL SALARIOS ================= -->
        <h5 class="mb-3">Historial de salarios</h5>

        <div class="table-responsive">
        <table class="table table-bordered table-sm" id="tablaSalarios">
          <thead class="table-light">
            <tr>
              <th>Año</th>
              <th>Salario</th>
              <th>Auxilio</th>
              <th>Otros</th>
              <th>Días</th>
              <th>Des Salud</th>
              <th>Des Pension</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        </div>

        <!-- BOTÓN NUEVO SALARIO -->
        <div class="text-end mt-3">
          <button type="button" id="btnNuevoSalario" class="btn btn-outline-primary">
            ➕ Agregar nuevo salario
          </button>
        </div>

        <!-- FORM NUEVO SALARIO -->
        <div id="formNuevoSalario" class="mt-4 d-none">
          <h5>Nuevo salario</h5>

          <div class="row g-3">
            <div class="col-md-2">
              <label>Año</label>
              <input type="number" name="anio" class="form-control">
            </div>
            <div class="col-md-2">
              <label>Salario</label>
              <input type="number" name="salario" class="form-control">
            </div>
            <div class="col-md-2">
              <label>Auxilio</label>
              <input type="number" name="auxilio" class="form-control">
            </div>
            <div class="col-md-2">
              <label>Otros</label>
              <input type="number" name="otros" class="form-control">
            </div>
            <div class="col-md-2">
              <label>Días</label>
              <input type="number" name="dias" class="form-control">
            </div>
            <div class="col-md-2">
              <label>- Salud</label>
              <input type="number" name="salud" class="form-control">
            </div>
            <div class="col-md-2">
              <label>- Pension</label>
              <input type="number" name="pension" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
              <button type="button" id="btnGuardarSalario" class="btn btn-success w-100">
                Guardar
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cerrar
        </button>
        <button type="button" id="btnGuardarCargo" class="btn btn-primary">
          Guardar cambios del cargo
        </button>
      </div>

    </div>
  </div>
</div>

      <!-- nuevo Cargo -->
<div class="modal fade" id="modalNuevoCargo" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Nuevo cargo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6>Datos del cargo</h6>
        <div class="row g-3">

          <div class="col-md-6">
            <label>Cargo</label>
            <input type="text" id="nc_cargo" class="form-control">
          </div>

          <div class="col-md-6">
            <label>Tipo contrato</label>
            <select id="nc_tipo_contrato" class="form-select">
              <option value="">Seleccione</option>
              <option value="Empresa">Empresa</option>
              <option value="Prestacion de servicios">Prestación de servicios</option>
            </select>
          </div>

          <div class="col-md-4">
            <label>¿Tiene recogida?</label>
            <select id="nc_recogida" class="form-select">
              <option value="NO">NO</option>
              <option value="SI">SI</option>
            </select>
          </div>

          <div class="col-md-4">
            <label>Valor recogida</label>
            <input type="number" id="nc_valor_recogida" class="form-control" disabled>
          </div>

        </div>

        <hr>

        <h6>Salario inicial</h6>
        <div class="row g-3">

          <div class="col-md-3">
            <label>Año</label>
            <input type="number" id="nc_anio" class="form-control" value="2025">
          </div>

          <div class="col-md-3">
            <label>Salario</label>
            <input type="number" id="nc_salario" class="form-control">
          </div>

          <div class="col-md-3">
            <label>Auxilio</label>
            <input type="number" id="nc_auxilio" class="form-control">
          </div>

          <div class="col-md-3">
            <label>Otros</label>
            <input type="number" id="nc_otros" class="form-control">
          </div>

          <div class="col-md-3">
            <label>Días</label>
            <input type="number" id="nc_dias" class="form-control">
          </div>

          <div class="col-md-3">
            <label>- salud</label>
            <input type="number" id="nc_salud" class="form-control">
          </div>

          <div class="col-md-3">
            <label>- Pension</label>
            <input type="number" id="nc_pension" class="form-control">
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-success" id="btnGuardarNuevoCargo">
          Guardar cargo
        </button>
      </div>

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
    processing: true,    // Muestra "procesando..."
    serverSide: true,    // Activa server-side
    
    ajax: {
      url: '/nueva_plataforma/controller/CargosController.php',
      type: 'POST',
      data: function (d) {
        d.tipo_contrato = $('#filtroContrato').val(); // 👈 nuevo
        d.ajax = true; // <-- para que entre en el if
      }
    },
    columns: [
      { data: 'car_Cargo' },
      { data: 'car_tipoContrato' },
      {
        data: 'idcargo',
        render: function (data) {
          return `
            <button 
              class="btn btn-sm btn-primary btn-editar"
              data-id="${data}">
              <i class="fa fa-edit"></i>
            </button>
          `;
        }
      },
      {
        data: 'idcargo',
        render: function (data) {
          return `
            <button 
              class="btn btn-sm btn-danger btn-eliminar-cargo"
              data-id="${data}">
              <i class="fa fa-trash"></i>
            </button>
          `;
        }
      }
    ]
  });

  // 🔄 Recargar cuando cambian filtros
  $('#filtroContrato').on('change', function () {
    tabla.ajax.reload();
  });
    // 🔍 Solo buscar al presionar Enter
  $('#tablaUsuarios_filter input')
    .off() // quitar el evento keyup que viene por defecto
    .on('keyup', function (e) {
      if (e.keyCode === 13) { // 13 = Enter
        tabla.search(this.value).draw();
      }
    });
});







let creditosSeleccionados = [];
$(document).on('click', '.btn-editar', function () {
    let idcargo = $(this).data('id');

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'GET',
        data: { accion: 'ver_cargo', idcargo: idcargo },
        dataType: 'json',
        success: function (resp) {
            // Datos cargo
            $('[name="idcargo"]').val(resp.cargo.idcargo);
            $('[name="cargo"]').val(resp.cargo.car_Cargo);
            $('[name="Tipo_Contrato"]').val(resp.cargo.car_tipoContrato);

            // Tabla salarios
            let html = '';
            resp.salarios.forEach(s => {
                html += `
                    <tr>
                        <td>${s.anio}</td>
                        <td>${s.salario}</td>
                        <td>${s.auxilio}</td>
                        <td>${s.otros}</td>
                        <td>${s.dias}</td>
                    </tr>`;
            });

            $('#tablaSalarios tbody').html(html);
            $('#modalServicioAuto').modal('show');
        }
    });
});

  // Envío del formulario de edición
$(document).on('submit', '#formEditarCliente', function(e) {
    e.preventDefault();

    let formData = $(this).serializeArray(); // convierte a array de objetos
    formData.push({ name: "accion", value: "editar_cliente" });
    formData.push({ name: "creditos_asignados", value: creditosSeleccionados.join(',') });

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: $.param(formData), // convertir a string
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                alert('Cliente actualizado correctamente.');
                $('#modalServicioAuto').modal('hide');

                if ($.fn.DataTable.isDataTable('#miTablaClientes')) {
                    $('#miTablaClientes').DataTable().ajax.reload();
                }
            } else {
                alert(resp.message || 'No se pudo actualizar el cliente.');
            }
        },
        error: function() {
            alert('Error en la petición.');
        }
    });
});



    


$(document).on('click', '.btn-editar', function () {

    const idcargo = $(this).data('id');

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'GET',
        data: {
            accion: 'obtener_cargo',
            idcargo: idcargo
        },
        dataType: 'json',
        success: function (resp) {

            // ===== DATOS DEL CARGO =====
            $('[name="idcargo"]').val(resp.cargo.idcargo);
            $('[name="cargo"]').val(resp.cargo.car_Cargo);
            $('[name="Tipo_Contrato"]').val(resp.cargo.car_tipoContrato);
            $('[name="recogida"]').val(resp.cargo.car_Recogida);

            if (resp.cargo.car_Recogida === 'SI') {
                $('[name="valor_recogida"]')
                    .prop('disabled', false)
                    .val(resp.cargo.car_ValorRecogida);
            } else {
                $('[name="valor_recogida"]')
                    .prop('disabled', true)
                    .val('');
            }

            // ===== TABLA SALARIOS =====
            let filas = '';
            resp.salarios.forEach(s => {
                filas += `
                    <tr>
                        <td>${s.anio}</td>
                        <td>$${Number(s.salario).toLocaleString()}</td>
                        <td>$${Number(s.auxilio).toLocaleString()}</td>
                        <td>$${Number(s.otros).toLocaleString()}</td>
                        <td>${s.dias}</td>
                        <td>$${Number(s.des_salud).toLocaleString()}</td>
                        <td>$${Number(s.des_pension).toLocaleString()}</td>

                        <td class="text-center">
                            <button 
                                class="btn btn-sm btn-danger btn-eliminar-salario"
                                data-id="${s.id_salCargo}"
                                data-anio="${s.anio}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#tablaSalarios tbody').html(filas);

            // Reset nuevo salario
            $('#formNuevoSalario').addClass('d-none');
            $('#btnNuevoSalario').text('➕ Agregar nuevo salario');

            // Abrir modal
            $('#modalServicioAuto').modal('show');
        },
        error: function () {
            alert('Error al cargar el cargo');
        }
    });
});
// Mostrar / ocultar formulario nuevo salario
$('#btnNuevoSalario').on('click', function () {
    $('#formNuevoSalario').toggleClass('d-none');

    // Texto dinámico
    if ($('#formNuevoSalario').hasClass('d-none')) {
        $(this).text('➕ Agregar nuevo salario');
    } else {
        $(this).text('➖ Cancelar');
    }
});

$(document).on('change', '#recogida', function () {
    if ($(this).val() === 'SI') {
        $('[name="valor_recogida"]').prop('disabled', false);
    } else {
        $('[name="valor_recogida"]').val('').prop('disabled', true);
    }
});

$('#btnGuardarCargo').on('click', function () {

    const data = {
        accion: 'actualizar_cargo',
        idcargo: $('[name="idcargo"]').val(),
        cargo: $('[name="cargo"]').val(),
        Tipo_Contrato: $('[name="Tipo_Contrato"]').val(),
        recogida: $('[name="recogida"]').val(),
        valor_recogida: $('[name="valor_recogida"]').val()
    };

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (resp) {

            if (resp.success) {
                alert('✅ Cargo actualizado');

                // Cerrar modal
                $('#modalServicioAuto').modal('hide');

                // Recargar DataTable
                if ($.fn.DataTable.isDataTable('#tablaUsuarios')) {
                    $('#tablaUsuarios').DataTable().ajax.reload(null, false);
                }

            } else {
                alert(resp.message || 'Error al guardar');
            }
        },
        error: function () {
            alert('Error en la petición');
        }
    });
});
$('#btnGuardarSalario').on('click', function () {

    const data = {
        accion: 'guardar_salario',
        idcargo: $('[name="idcargo"]').val(),
        anio: $('[name="anio"]').val(),
        salario: $('[name="salario"]').val(),
        auxilio: $('[name="auxilio"]').val(),
        otros: $('[name="otros"]').val(),
        dias: $('[name="dias"]').val(),
        salud: $('[name="salud"]').val(),
        pension: $('[name="pension"]').val()
    };

    // Validación rápida
    if (!data.anio || !data.salario) {
        alert('Año y salario son obligatorios');
        return;
    }

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (resp) {

            if (resp.success) {
                alert('✅ Salario guardado');

                // Limpiar form
                $('#formNuevoSalario input').val('');
                $('#formNuevoSalario').addClass('d-none');
                $('#btnNuevoSalario').text('➕ Agregar nuevo salario');

                // Recargar salarios del cargo
                $('.btn-editar[data-id="' + data.idcargo + '"]').click();

            } else {
                alert(resp.message || 'No se pudo guardar');
            }
        },
        error: function () {
            alert('Error en la petición');
        }
    });
});

$(document).on('click', '.btn-eliminar-salario', function () {

    const idSalario = $(this).data('id');
    const anio = $(this).data('anio');
    const idcargo = $('[name="idcargo"]').val();

    if (!confirm(`¿Seguro que deseas eliminar el salario del año ${anio}?`)) {
        return;
    }

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: {
            accion: 'eliminar_salario',
            id_salario: idSalario
        },
        dataType: 'json',
        success: function (resp) {

            if (resp.success) {
                alert('🗑️ Salario eliminado');

                // Recargar salarios del cargo
                $('.btn-editar[data-id="' + idcargo + '"]').click();

            } else {
                alert(resp.message || 'No se pudo eliminar');
            }
        },
        error: function () {
            alert('Error en la petición');
        }
    });
});

$(document).on('click', '.btn-eliminar-cargo', function () {

    const idcargo = $(this).data('id');

    if (!confirm('⚠️ ¿Seguro que deseas eliminar este cargo?')) {
        return;
    }

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: {
            accion: 'eliminar_cargo',
            idcargo: idcargo
        },
        dataType: 'json',
        success: function (resp) {

            if (resp.success) {
                alert('🗑️ Cargo eliminado');

                $('#tablaUsuarios').DataTable().ajax.reload(null, false);

            } else {
                alert(resp.message);
            }
        },
        error: function () {
            alert('Error en la petición');
        }
    });
});

//Nuevo cargo
$('#btnNuevoCargo').on('click', function () {
    $('#modalNuevoCargo').modal('show');
});

$('#nc_recogida').on('change', function () {
    if ($(this).val() === 'SI') {
        $('#nc_valor_recogida').prop('disabled', false);
    } else {
        $('#nc_valor_recogida').val('').prop('disabled', true);
    }
});

$('#btnGuardarNuevoCargo').on('click', function () {

    const data = {
        accion: 'guardar_nuevo_cargo',
        cargo: $('#nc_cargo').val(),
        tipo_contrato: $('#nc_tipo_contrato').val(),
        recogida: $('#nc_recogida').val(),
        valor_recogida: $('#nc_valor_recogida').val(),
        anio: $('#nc_anio').val(),
        salario: $('#nc_salario').val(),
        auxilio: $('#nc_auxilio').val(),
        otros: $('#nc_otros').val(),
        dias: $('#nc_dias').val(),
        salud: $('#nc_salud').val(),
        pension: $('#nc_pension').val()
    };

    if (!data.cargo || !data.salario || !data.anio) {
        alert('Cargo, año y salario son obligatorios');
        return;
    }

    $.ajax({
        url: '/nueva_plataforma/controller/CargosController.php',
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function (resp) {

            if (resp.success) {
                alert('✅ Cargo creado');

                $('#modalNuevoCargo').modal('hide');
                $('#tablaUsuarios').DataTable().ajax.reload();

            } else {
                alert(resp.message || 'Error al crear cargo');
            }
        },
        error: function () {
            alert('Error en la petición');
        }
    });
});

const modalNuevoCargo = document.getElementById('modalNuevoCargo');

modalNuevoCargo.addEventListener('shown.bs.modal', function () {

    // Limpiar inputs
    modalNuevoCargo.querySelectorAll('input').forEach(input => {
        input.value = '';
    });

    // Reset selects
    modalNuevoCargo.querySelectorAll('select').forEach(select => {
        select.selectedIndex = 0;
    });

    // Reset lógica de recogida
    document.getElementById('nc_valor_recogida').disabled = true;

    // Año por defecto (opcional)
    document.getElementById('nc_anio').value = new Date().getFullYear();

    // Foco automático
    document.getElementById('nc_cargo').focus();
});


</script>
</body>
</html>
