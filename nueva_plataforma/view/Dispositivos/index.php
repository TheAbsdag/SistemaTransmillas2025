<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi dispositivo | Plataforma</title>

  <!-- META -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- BOOTSTRAP 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- FONT AWESOME -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

  <!-- ESTILOS MODERNOS -->
  <style>
    body {
      min-height: 100vh;
      background: radial-gradient(circle at top left, #eef2ff, #f8fafc 40%, #e5e7eb);
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    .page-wrapper {
      max-width: 1100px;
      margin: auto;
      padding: 60px 20px;
    }

    .device-card {
      background: rgba(255,255,255,.85);
      backdrop-filter: blur(18px);
      border-radius: 20px;
      box-shadow: 0 25px 50px rgba(0,0,0,.08);
      overflow: hidden;
    }

    .device-header {
      background: rgb(12, 69, 130);
      color: #fff;
      padding: 32px;
    }

    .device-header h1 {
      font-size: 26px;
      font-weight: 600;
      margin: 0;
    }

    .device-header small {
      opacity: .75;
    }

    .info-box {
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 18px;
      background: #f9fafb;
      height: 100%;
    }

    .info-label {
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #6b7280;
    }

    .info-value {
      font-weight: 600;
      color: #111827;
      word-break: break-all;
    }

    .status-box {
      border-radius: 14px;
      padding: 18px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .btn-action {
      padding: 14px 30px;
      font-size: 16px;
      border-radius: 14px;
    }
  </style>
</head>

<body>

<div class="page-wrapper">

  <div class="device-card">

    <!-- HEADER -->
    <div class="device-header">
      <h1>
        <i class="fas fa-shield-halved me-2"></i>
        Seguridad del dispositivo
      </h1>
      <small>
        Vinculación y autorización del equipo actual
      </small>
    </div>

    <!-- BODY -->
    <div class="p-4 p-md-5">

      <!-- INFO -->
      <div class="row g-4 mb-4">

        <div class="col-md-4">
          <div class="info-box">
            <div class="info-label mb-1">
              <i class="fas fa-globe me-1"></i> Navegador
            </div>
            <div class="info-value" id="infoNavegador">—</div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="info-box">
            <div class="info-label mb-1">
              <i class="fas fa-microchip me-1"></i> Plataforma
            </div>
            <div class="info-value" id="infoPlataforma">—</div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="info-box">
            <div class="info-label mb-1">
              <i class="fas fa-expand me-1"></i> Resolución
            </div>
            <div class="info-value" id="infoResolucion">—</div>
          </div>
        </div>

      </div>

      <!-- STATUS -->
      <div id="estadoDispositivo" class="status-box mb-4"
           style="background:#f3f4f6;color:#374151;">
        <i class="fas fa-spinner fa-spin"></i>
        Verificando estado del dispositivo…
      </div>

      <!-- ACTION -->
      <div class="d-flex justify-content-end">
        <button
          id="btnVincular"
          class="btn btn-primary btn-action"
          onclick="vincularDispositivo()"
          disabled>
          <i class="fas fa-link me-2"></i>
          Vincular este dispositivo
        </button>
      </div>

    </div>

  </div>

</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
/* ===========================
   INFO DISPOSITIVO
=========================== */
document.getElementById('infoNavegador').innerText = navigator.userAgent;
document.getElementById('infoPlataforma').innerText = navigator.platform;
document.getElementById('infoResolucion').innerText =
  screen.width + ' x ' + screen.height;

/* ===========================
   DEVICE ID
=========================== */
function getDeviceId() {
  if (!localStorage.getItem('device_id')) {
    localStorage.setItem('device_id', crypto.randomUUID());
  }
  return localStorage.getItem('device_id');
}

function getFingerprint() {
  return {
    user_agent: navigator.userAgent,
    platform: navigator.platform,
    screen_width: screen.width,
    screen_height: screen.height
  };
}

/* ===========================
   VERIFICAR ESTADO
=========================== */
$(document).ready(function () {

  $.ajax({
    url: 'DispositivosController.php',
    type: 'POST',
    dataType: 'json',
    data: {
      verificar_dispositivo: true,
      device_id: getDeviceId()
    },
    success: function (res) {

      const estado = $('#estadoDispositivo');
      const btn = $('#btnVincular');

      if (res.vinculado) {

        if (res.autorizado) {
          estado
            .css({background:'#ecfdf5',color:'#065f46'})
            .html('<i class="fas fa-check-circle"></i> Dispositivo autorizado');
          btn.hide();
        } else {
          estado
            .css({background:'#fffbeb',color:'#92400e'})
            .html('<i class="fas fa-clock"></i> Pendiente de autorización');
          btn.hide();
        }

      } else {
        estado
          .css({background:'#eef2ff',color:'#3730a3'})
          .html('<i class="fas fa-info-circle"></i> Dispositivo no vinculado');
        btn.prop('disabled', false);
      }
    }
  });

});

/* ===========================
   VINCULAR
=========================== */
function vincularDispositivo() {

  $('#btnVincular')
    .prop('disabled', true)
    .html('<i class="fas fa-spinner fa-spin me-2"></i> Vinculando…');

  const fingerprint = getFingerprint();

  $.ajax({
    url: 'DispositivosController.php',
    type: 'POST',
    dataType: 'json',
    data: {
      vincular_dispositivo: true,
      device_id: getDeviceId(),

      user_agent: fingerprint.user_agent,
      platform: fingerprint.platform,
      screen_width: fingerprint.screen_width,
      screen_height: fingerprint.screen_height
    },
    success: function () {
      location.reload();
    }
  });
}

</script>

</body>
</html>
