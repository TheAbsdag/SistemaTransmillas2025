  let seleccionados = [];
$(document).ready(function () {
  // Array donde guardaremos las filas seleccionadas

  const tabla = $('#tablaLiquidaciones').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/LiquidacionesController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.Anio = $('#filtroAnio').val();
        d.ciudad = $('#filtroCiudad').val();
        d.operador = $('#filtroOperador').val();
        d.estado = $('#filtroEstado').val();
      },
      dataSrc: ''
    },
    columns: [
      // 🟢 Nueva columna con checkbox
      {
        data: null,
        orderable: false,
        className: 'text-center',
        render: function (data, type, row) {
          return `<input type="checkbox" class="chk-liquidacion" data-id="${row.idhojadevida}">`;
        }
      },
      { data: 'nombre_completo' },
      { data: 'hoj_cedula' },
      { data: 'hoj_tipocontrato' },
      { data: 'car_cargo' },
      { data: 'car_salario' },
      { data: 'car_Auxilio' },
      { data: 'hoj_fechaInicial' },
      { data: 'hoj_fechaFinal' },
      { data: 'diasDefechaaFecha' },
      { data: 'dias_noTrabajados' },
      { data: 'diasEfectivos' },
      { data: 'valor_cesantias' },
      { data: 'intereses_cesantias' },
      { data: 'diasEfectivosPrimas' },
      { data: 'valor_prima' },
      { data: 'diasAPagarVacaciones' },
      { data: 'dias_vacaciones' },
      { data: 'valor_vacaciones' },
      { data: 'valorDeudas'},
      { data: 'valorTotalLiquidar' },
      {
        data: null,
        render: function (data, type, row) {
          // Creamos el objeto con los datos
          const info = {
            nombre: row.nombre_completo || '',
            cedula: row.hoj_cedula || '',
            fecha_ingreso: row.hoj_fechaInicial || '',
            fecha_retiro: row.hoj_fechaFinal || '',
            dias_trabajados: row.diasEfectivos || 0,
            dias_cesantias: row.diasEfectivos || 0,
            dias_prima: row.diasEfectivosPrimas || 0,
            dias_vacaciones: row.diasAPagarVacaciones || 0,
            sueldobasico: row.car_salario || 0,
            transporte: row.car_Auxilio || 0,
            cesantias: row.valor_cesantias || 0,
            intereses: row.intereses_cesantias || 0,
            prima: row.valor_prima || 0,
            vacaciones: row.valor_vacaciones || 0,
            valorTotalDevengado: row.valorTotalDevengado || 0,
            valor_total: row.valorTotalLiquidar || 0,
            cargo: row.car_cargo || '',
            noTrabajados: row.dias_noTrabajados || 0,
            valorVacacionesCompletas: row.valorVacacionesCompletas || 0,
            valorDeudas: row.valorDeudas || 0
          };

          // Lo convertimos en string JSON escapado
          const jsonData = encodeURIComponent(JSON.stringify(info));

          return `
            <button class="btn btn-sm btn-primary btn-ver-pdf" 
                    data-json="${jsonData}">
              <i class="bi bi-file-earmark-pdf"></i> Ver desprendible
            </button>
          `;
        }
      },
      {
        data: null,
        render: function (data, type, row) {
          const id = row.idhojadevida;
          const valor = Number(row.EstadoLiquidacion); // Convertir a número

          let selectHtml = '';

          if (valor === 1) {
            selectHtml = `
              <select class="form-select form-select-sm estado-liquidacion bg-success text-white" data-id="${id}">
                <option value="0" >No</option>
                <option value="1" selected>Sí</option>
              </select>
            `;
          } else {
            selectHtml = `
              <select class="form-select form-select-sm estado-liquidacion bg-danger text-white" data-id="${id}">
                <option value="0" selected >No</option>
                <option value="1" >Sí</option>
              </select>
            `;
          }

          return selectHtml;
        }
      },
      {
        data: 'comprobante',
        render: function (data, type, row) {
          if (!data) {
            return ''; // no hay comprobante → no muestra nada
          } else {
            return `
              <button 
                class="btn btn-sm btn-outline-primary" 
                onclick="verComprobante('${data}')">
                <i class="bi bi-eye"></i> Ver comprobante
              </button>
            `;
          }
        }
      }
    ]
  });

  // Filtros
  $('#filtroAnio, #filtroCiudad,#filtroOperador,#filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });

  // 🟡 Manejar los checkboxes
  $('#tablaLiquidaciones tbody').on('change', '.chk-liquidacion', function () {
    const fila = tabla.row($(this).closest('tr')).data();
    const id = fila.idhojadevida;
    const checkbox = this;

    if (checkbox.checked) {
      // ✅ Verificar si el ID está liquidado antes de agregarlo
      $.ajax({
        url: '/nueva_plataforma/controller/LiquidacionesController.php',
        type: 'POST',
        data: {
          accion: 'verificarLiquidado',
          idhojadevida: id
        },
        dataType: 'json',
        success: function (response) {
          if (response.success && response.liquidado) {
            // Si está liquidado, agregarlo al array
            if (!seleccionados.includes(id)) {
              seleccionados.push(id);
            }
            console.log('Seleccionados:', seleccionados);
          } else {
            // ❌ No está liquidado
            alert('Esta persona aún no está liquidada.');
            checkbox.checked = false;
          }
        },
        error: function () {
          alert('Error al verificar el estado de la liquidación.');
          checkbox.checked = false;
        }
      });
    } else {
      // Si se desmarca, quitar del array
      seleccionados = seleccionados.filter(item => item !== id);
      console.log('Seleccionados:', seleccionados);
    }
  });


  // Agrega al inicio del <thead>
  $('#tablaLiquidaciones thead tr').prepend('<th><input type="checkbox" id="chk-todos"></th>');

  // Al cambiar el general, marcar todos los demás
  $('#tablaLiquidaciones thead').on('change', '#chk-todos', function() {
    const isChecked = this.checked;
    $('.chk-liquidacion', tabla.rows().nodes()).prop('checked', isChecked).trigger('change');
  });


  //para ver el Desprendible de liquidacion
  $(document).on('click', '.btn-ver-pdf', function() {
    const jsonData = $(this).data('json');
    const datos = JSON.parse(decodeURIComponent(jsonData));

    // Crear un objeto FormData y agregar los datos
    const formData = new FormData();
    for (const key in datos) {
      formData.append(key, datos[key]);
    }

    // Enviar por POST y abrir el PDF
    fetch('../view/Pdfs/DesprendibleLiquidacion.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.blob())
    .then(blob => {
      const url = URL.createObjectURL(blob);
      window.open(url, '_blank'); // abrir en nueva pestaña
    })
    .catch(error => console.error('Error al generar PDF:', error));
  });

  // Función para aplicar color al select según el valor
  function aplicarColorSelect($select, valor) {
    $select.removeClass('bg-success bg-danger text-white');
    if (Number(valor) === 1) {
      $select.addClass('bg-success text-white'); // verde: liquidado
    } else {
      $select.addClass('bg-danger text-white'); // rojo: por liquidar
    }
  }

  // Cada vez que se redibuja la tabla (por filtros o carga AJAX)
  $('#tablaLiquidaciones').on('draw.dt', function () {
    $('.estado-liquidacion').each(function () {
      aplicarColorSelect($(this), $(this).val());
    });
  });

  // ✅ Manejo del cambio de estado con envío de todos los datos
  $(document).on('change', '.estado-liquidacion', function () {
    const $select = $(this);
    const nuevoEstado = $select.val();
    const id = $select.data('id');
    const row = tabla.row($select.closest('tr')).data();

    aplicarColorSelect($select, nuevoEstado); // cambia color visual inmediatamente

    // Crear objeto con todos los datos del form
    const datos = {
      accion: 'actualizarEstadoLiquidacion',
      idLiquidado: row.idLiquidado,
      idQuienLiqd: row.idLiquidado,
      liquidado: nuevoEstado,
      idhojadevida: row.idhojadevida,
      nombre: row.nombre_completo || '',
      cedula: row.hoj_cedula || '',
      fecha_ingreso: row.hoj_fechaInicial || '',
      fecha_retiro: row.hoj_fechaFinal || '',
      dias_trabajados: row.diasEfectivos || 0,
      dias_cesantias: row.diasEfectivos || 0,
      dias_prima: row.diasEfectivosPrimas || 0,
      dias_vacaciones: row.diasAPagarVacaciones || 0,
      sueldobasico: row.car_salario || 0,
      transporte: row.car_Auxilio || 0,
      cesantias: row.valor_cesantias || 0,
      intereses: row.intereses_cesantias || 0,
      prima: row.valor_prima || 0,
      vacaciones: row.valor_vacaciones || 0,
      valor_total: row.valorTotalLiquidar || 0,
      cargo: row.car_cargo || '',
      valorTotalDevengado: row.valorTotalDevengado || 0,
      dias_noTrabajados: row.dias_noTrabajados || 0,
      valorVacacionesCompletas: row.valorVacacionesCompletas || 0,
      valorDeudas: row.valorDeudas || 0
      
    };

    // Enviar al servidor
    $.ajax({
      url: '/nueva_plataforma/controller/LiquidacionesController.php',
      type: 'POST',
      data: datos,
      success: function (response) {
        console.log('✅ Estado y datos enviados:', response);
        // Mostrar alerta si quieres
        // Swal.fire({ icon: 'success', title: 'Actualizado correctamente', timer: 1200, showConfirmButton: false });
      },
      error: function (xhr, status, error) {
        console.error('❌ Error al actualizar:', error);
        alert('Error al actualizar el estado');
        // opcional: revertir cambio visual si hay error
        $select.val(nuevoEstado === '1' ? '0' : '1');
        aplicarColorSelect($select, $select.val());
      }
    });
  });
});

$('#formComprobante').on('submit', function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  // 🔹 Agregamos los IDs seleccionados
  formData.append('seleccionados', JSON.stringify(seleccionados));

  // 🔹 Agregamos una acción para que el controlador sepa qué método llamar
  formData.append('accion', 'subirComprobante');

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: formData,
    contentType: false, // necesario para enviar archivos
    processData: false, // no procesar los datos (FormData se maneja crudo)
    success: function (response) {
      console.log('✅ Respuesta del servidor:', response);

      Swal.fire({
        icon: 'success',
        title: 'Comprobante subido correctamente',
        timer: 1500,
        showConfirmButton: false
      });

      // Cerrar modal si estás usando Bootstrap
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalComprobante'));
      modal.hide();
    },
    error: function (xhr, status, error) {
      console.error('❌ Error al subir el comprobante:', error);
      Swal.fire({ icon: 'error', title: 'Error al subir el comprobante' });
    }
  });
});

$('#filtroCiudad').on('change', function () {
  let ciudadId = $(this).val();

  // Limpio los operadores
  $('#filtroOperador').html('<option value="">Cargando...</option>');

  if (ciudadId) {
    $.ajax({
      url: '/nueva_plataforma/controller/DescargasOficinaController.php',
      type: 'POST',
      data: {
        accion: 'listarOperadoresPorCiudad',
        ciudad: ciudadId
      },
      dataType: 'json',
      success: function (operadores) {
        let opciones = '<option value="">Seleccione...</option>';
        operadores.forEach(op => {
          opciones += `<option value="${op.idusuarios}">${op.usu_nombre}</option>`;
        });
        $('#filtroOperador').html(opciones);
      },
      error: function () {
        $('#filtroOperador').html('<option value="">Error cargando operadores</option>');
      }
    });
  } else {
    $('#filtroOperador').html('<option value="">Seleccione...</option>');
  }

  // recargo tabla también cuando cambia ciudad
  $('#tablaLiquidaciones').DataTable().ajax.reload();
});

function verComprobante(nombreArchivo) {
  const ruta = `/nueva_plataforma/uploads/comprobantesLiqui/${nombreArchivo}`;

  // Insertamos el archivo dentro del iframe del modal
  document.getElementById('iframeComprobante').src = ruta;

  // Mostramos el modal
  const modal = new bootstrap.Modal(document.getElementById('modalVerComprobante'));
  modal.show();
}



 