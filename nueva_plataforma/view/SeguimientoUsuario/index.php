<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Seguimiento de Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .noti_bubble {
            float: right;
            padding: 2px 6px;
            background-color: red;
            color: white;
            font-weight: bold;
            border-radius: 60px;
            box-shadow: 1px 1px 3px gray;
            cursor: pointer;
        }

        .noti_options {
            position: absolute;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-top: 5px;
            z-index: 1000;
        }

        .table td,
        .table th {
            white-space: nowrap;
        }

        .dataTables_wrapper {
            overflow-x: auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4">
        <div class="card shadow p-3 mb-4 bg-body rounded">
            <div class="card-header mi-header d-flex align-items-center justify-content-between">
                <button class="btn btn-light" onclick="history.back()">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </button>
                <h3 class="mb-0">
                    <i class="fas fa-users me-2"></i> Seguimiento de Usuarios
                </h3>
            </div>

            <div class="card-body">
                <!-- Botones de acción principales -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-lg" onclick="abrirModalFestivos()">
                            <i class="fas fa-calendar-plus"></i> + Día de descanso
                        </button>
                        <button type="button" class="btn btn-danger btn-lg" onclick="abrirModalVacaciones()">
                            <i class="fas fa-umbrella-beach"></i> + Vacaciones
                        </button>
                        <button type="button" class="btn btn-danger btn-lg" onclick="abrirModalLicencias()">
                            <i class="fas fa-file-medical"></i> + Licencias y permisos
                        </button>
                        <?php if ($_SESSION['usuario_rol'] == 1 || $_SESSION['usuario_rol'] == 12): ?>
                            <button type="button" class="btn btn-primary btn-lg" onclick="abrirModalIngreso()">
                                <i class="fas fa-user-plus"></i> Ingreso manual
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Filtros -->
                <form id="formFiltros" class="row g-3 mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Fecha Inicial</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Final</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                            value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sede</label>
                        <select name="sede" id="sede" class="form-select">
                            <option value="">Todas</option>
                            <?php foreach ($sedes as $s): ?>
                                <option value="<?= $s['idsedes'] ?>"><?= htmlspecialchars($s['sed_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Operario</label>
                        <select name="operario" id="operario" class="form-select">
                            <option value="">Todos</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Motivo Ingreso</label>
                        <select name="motivo" id="motivo" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($motivos as $m): ?>
                                <option value="<?= $m['mot_nombre'] ?>"><?= htmlspecialchars($m['mot_nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipo Contrato</label>
                        <select name="tipo_contrato" id="tipo_contrato" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach ($tiposContrato as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-12 text-end">
                        <button type="button" class="btn btn-primary" onclick="recargarTabla()">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- Celda para mostrar deuda del operario seleccionado -->
                <div id="miCelda" class="alert alert-info" style="display:none;"></div>

                <!-- Tabla de seguimiento -->
                <div class="table-responsive">
                    <table id="tablaSeguimiento" class="table table-hover table-bordered align-middle"
                        style="width:100%">
                        <thead class="thead-modern">
                            <tr>
                                <th>Operador</th>
                                <th>Preoperacional</th>
                                <th>Validación</th>
                                <th>Imagen</th>
                                <th>Ingreso?</th>
                                <th>Descripción</th>
                                <th>Fecha Ingreso</th>
                                <th>Zona Trabajo</th>
                                <th>Trabaja con</th>
                                <th>Hora Almuerzo</th>
                                <th>Retorno Almuerzo</th>
                                <th>Retorno Oficina</th>
                                <th>Hora Salida</th>
                                <th>TEM Entrada</th>
                                <th>TEM Salida</th>
                                <th>Tipo Contrato</th>
                                <th>PLACA</th>
                                <th>Fecha Seguro</th>
                                <th>Fecha Tecno</th>
                                <th>Fecha Licencia</th>
                                <th>Cambio Aceite</th>
                                <?php if ($_SESSION['usuario_rol'] == 1 || $_SESSION['usuario_rol'] == 12): ?>
                                    <th>Eliminar</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALES -->

    <!-- Modal Ingreso (SeguimientoUser) -->
    <div class="modal fade" id="modalIngreso" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Registrar ingreso de operario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formIngreso" enctype="multipart/form-data">
                        <input type="hidden" name="accion" value="guardar_ingreso">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Operario</label>
                                <select name="operario" id="ing_operario" class="form-select" required></select>
                            </div>
                            <div class="col-md-6">
                                <label>Sede</label>
                                <select name="sede" id="ing_sede" class="form-select" required>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($sedes as $s): ?>
                                        <option value="<?= $s['idsedes'] ?>"><?= htmlspecialchars($s['sed_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Fecha Ingreso</label>
                                <input type="date" name="fecha" class="form-control" value="<?= date('Y-m-d') ?>"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label>Motivo</label>
                                <select name="motivo" class="form-select" required>
                                    <?php foreach ($motivos as $m): ?>
                                        <option value="<?= $m['mot_nombre'] ?>"><?= htmlspecialchars($m['mot_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Zona</label>
                                <select name="zona" id="ing_zona" class="form-select" required></select>
                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label>Prueba Alcohol</label>
                                <select name="prueba" class="form-select" required>
                                    <option value="No aplica">No aplica</option>
                                    <option value="Negativo">Negativo</option>
                                    <option value="Positivo">Positivo</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Imagen</label>
                                <input type="file" name="imagen" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarIngreso()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Festivos (día de descanso para todos) -->
    <div class="modal fade" id="modalFestivos" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Agregar día de descanso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFestivos">
                        <input type="hidden" name="accion" value="guardar_festivos">
                        <div class="mb-3">
                            <label>Fecha</label>
                            <input type="date" name="fecha" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Sede (opcional, si se quiere restringir)</label>
                            <select name="sede" class="form-select">
                                <option value="">Todas las sedes</option>
                                <?php foreach ($sedes as $s): ?>
                                    <option value="<?= $s['idsedes'] ?>"><?= htmlspecialchars($s['sed_nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-danger" onclick="guardarFestivos()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vacaciones -->
    <div class="modal fade" id="modalVacaciones" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Agregar vacaciones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formVacaciones">
                        <input type="hidden" name="accion" value="guardar_vacaciones">
                        <div class="mb-3">
                            <label>Operario</label>
                            <select name="operario" id="vac_operario" class="form-select" required></select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Fecha inicio</label>
                                <input type="date" name="fecha_ini" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Fecha fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-danger" onclick="guardarVacaciones()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Licencias y permisos -->
    <div class="modal fade" id="modalLicencias" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Agregar licencia / permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formLicencias">
                        <input type="hidden" name="accion" value="guardar_licencia">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label>Operario</label>
                                <select name="operario" id="lic_operario" class="form-select" required></select>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha inicio</label>
                                <input type="date" name="fecha_ini" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label>Fecha fin</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Motivo</label>
                                <select name="motivo" class="form-select" required>
                                    <?php foreach ($motivos as $m): ?>
                                        <option value="<?= $m['mot_nombre'] ?>"><?= htmlspecialchars($m['mot_nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-danger" onclick="guardarLicencias()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap, DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        const dirPage = window.location.pathname;

        // Inicializar DataTable
        let tabla = $('#tablaSeguimiento').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: dirPage,
                type: 'POST',
                data: function (d) {
                    d.ajax = true;
                    d.fecha_inicio = $('#fecha_inicio').val();
                    d.fecha_fin = $('#fecha_fin').val();
                    d.sede = $('#sede').val();
                    d.operario = $('#operario').val();
                    d.motivo = $('#motivo').val();
                    d.tipo_contrato = $('#tipo_contrato').val();
                }
            },
            columns: [
                {
                    data: 'alerta_html',
                    render: function (data, type, row) {
                        return (row.alerta_html || '') + ' ' + row.usu_nombre;
                    }
                },
                { data: 'preoperacional_link' },
                { data: 'validacion_link' },
                { data: 'imagen_link' },
                { data: 'ingreso_link' },
                { data: 'seg_descr' },
                { data: 'seg_fechaingreso' },
                { data: 'zona_link' },
                { data: 'companero_link' },
                { data: 'hora_almuerzo_link' },
                { data: 'retorno_almuerzo_link' },
                { data: 'retorno_oficina_link' },
                { data: 'seg_fechafinalizo' },
                { data: 'tem_entrada_link' },
                { data: 'tem_salida_link' },
                { data: 'usu_tipocontrato' },
                { data: 'veh_placa' },
                { data: 'fecha_seguro_html' },
                { data: 'fecha_tecno_html' },
                { data: 'fecha_licencia_html' },
                { data: 'cambio_aceite_html' },
                <?php if ($_SESSION['usuario_rol'] == 1 || $_SESSION['usuario_rol'] == 12): ?>
                { data: 'eliminar_html' }
            <?php endif; ?>
            ],
            columnDefs: [
                { targets: '_all', className: 'text-center' }
            ],
            createdRow: function (row, data, dataIndex) {
                if (data.row_color) {
                    $(row).css('background-color', data.row_color);
                }
            },
            scrollX: true
        });

        function recargarTabla() {
            tabla.ajax.reload();
        }

        // Cuando cambia la sede, cargar operarios en los selects
        $('#sede').on('change', function () {
            let sede = $(this).val();
            if (sede) {
                $.get(dirPage, { accion: 'get_operarios', idsede: sede }, function (data) {
                    let options = '<option value="">Todos</option>';
                    data.forEach(op => {
                        options += `<option value="${op.idusuarios}">${op.usu_nombre}</option>`;
                    });
                    $('#operario').html(options);
                    // También actualizar selects de modales
                    $('#ing_operario, #vac_operario, #lic_operario').html(options);
                });
            } else {
                $('#operario').html('<option value="">Todos</option>');
            }
        });

        // Cuando cambia la sede en el modal de ingreso, cargar zonas
        $('#ing_sede').on('change', function () {
            let sede = $(this).val();
            if (sede) {
                $.get(dirPage, { accion: 'get_zonas', idsede: sede }, function (data) {
                    let options = '<option value="">Seleccione</option>';
                    data.forEach(z => {
                        options += `<option value="${z.idzonatrabajo}">${z.zon_nombre}</option>`;
                    });
                    $('#ing_zona').html(options);
                });
            }
        });

        // Mostrar deuda al seleccionar operario en el filtro
        $('#operario').on('change', function () {
            let id = $(this).val();
            if (id) {
                $.get(dirPage, { accion: 'get_deuda', idoperario: id }, function (res) {
                    $('#miCelda').html('Debe: $ ' + res.deuda).show();
                });
            } else {
                $('#miCelda').hide();
            }
        });

        // Funciones para abrir modales
        function abrirModalIngreso() {
            $('#modalIngreso').modal('show');
        }
        function abrirModalFestivos() {
            $('#modalFestivos').modal('show');
        }
        function abrirModalVacaciones() {
            $('#modalVacaciones').modal('show');
        }
        function abrirModalLicencias() {
            $('#modalLicencias').modal('show');
        }

        // Guardar ingreso
        function guardarIngreso() {
            let formData = new FormData(document.getElementById('formIngreso'));
            $.ajax({
                url: dirPage,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    alert(res.message);
                    if (res.success) {
                        $('#modalIngreso').modal('hide');
                        tabla.ajax.reload();
                    }
                },
                error: function () {
                    alert('Error en la petición');
                }
            });
        }

        function guardarFestivos() {
            let data = $('#formFestivos').serialize();
            $.post(dirPage, data, function (res) {
                alert(res.message);
                if (res.success) {
                    $('#modalFestivos').modal('hide');
                    tabla.ajax.reload();
                }
            }, 'json');
        }

        function guardarVacaciones() {
            let data = $('#formVacaciones').serialize();
            $.post(dirPage, data, function (res) {
                alert(res.message);
                if (res.success) {
                    $('#modalVacaciones').modal('hide');
                    tabla.ajax.reload();
                }
            }, 'json');
        }

        function guardarLicencias() {
            let data = $('#formLicencias').serialize();
            $.post(dirPage, data, function (res) {
                alert(res.message);
                if (res.success) {
                    $('#modalLicencias').modal('hide');
                    tabla.ajax.reload();
                }
            }, 'json');
        }

        // Para manejar burbujas de alerta (hover)
        $(document).on('click', '.noti_bubble', function () {
            var id = $(this).data('id');
            $('.noti_options[data-id="' + id + '"]').toggle();
        });
    </script>
</body>

</html>