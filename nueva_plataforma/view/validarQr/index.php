<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Guía</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .mi-header {
      background-color: #00458D;
      color: white;
    }
    .card-header {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <!-- Barra superior -->
  <nav class="navbar navbar-expand-lg" style="background:#002f6c; color:white;">
    <div class="container-fluid">    
      <span class="navbar-brand ms-3 text-white">Detalle de Guía</span>
    </div>
  </nav>

                    
                
  <div class="container mt-4">
    <!-- Información de la guía -->
    <div class="row g-4">
      <!-- Remitente -->
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header mi-header">
            <i class="bi bi-person-fill me-2"></i> Remitente
          </div>
          <div class="card-body">
            <p><strong>Ciudad Origen:</strong> <?php echo $guia['nombre_ciudad_cliente']; ?></p>
            <p><strong>Nombre:</strong><?php echo $guia['cli_nombre']; ?></p>
            <p><strong>Dirección:</strong><?php echo str_replace("&", " ", $guia['cli_direccion']);  ?></p>
          </div>
        </div>
      </div>

      <!-- Destinatario -->
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header mi-header" >
            <i class="bi bi-box-arrow-in-down-right me-2"></i> Destinatario
          </div>
          <div class="card-body">
            <p><strong>Ciudad Destino:</strong> <?php echo$guia['nombre_ciudad_entrega']; ?></p>
            <p><strong>Nombre:</strong> <?php echo$guia['ser_destinatario']; ?></p>
            <p><strong>Dirección:</strong> <?php echo str_replace("&", " ", $guia['ser_direccioncontacto']);  ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- Piezas -->
    <div class="row mt-4">
      <div class="col-12">
        <div class="card shadow text-center">
          <div class="card-header" style="background:#0d6efd; color:white;">
            <i class="bi bi-box-seam me-2"></i> Cantidad de Piezas
          </div>
          <div class="card-body">
            <h3 class="fw-bold text-primary"><?php echo $guia['ser_piezas']; ?></h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
