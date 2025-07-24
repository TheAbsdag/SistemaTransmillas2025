<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <title>Transmillas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .form-container {
      max-width: 500px;
      margin: 5% auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .form-container img {
      width: 100px;
      display: block;
      margin: 0 auto 20px;
    }
    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #0d6efd;
    }
    footer {
      text-align: center;
      margin-top: 30px;
      color: #888;
    }
    footer img {
      width: 80px;
      transition: all .2s ease;
    }
    footer img:hover {
      opacity: .83;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-container">
    <form action="rastreoclienteok.php" method="post" enctype="multipart/form-data">
      <img src="img/rastreo.png" alt="Transmillas Logo">
      <h2>Rastreo de Envíos</h2>
      <div class="mb-3">
        <label for="guia" class="form-label">Número de guía</label>
        <input type="text" class="form-control" id="guia" name="guia" placeholder="Ej: BGT00011" required>
      </div>
      <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Teléfono del remitente o destinatario" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary" name="enviar" value="buscar">Buscar</button>
      </div>
    </form>
  </div>
</div>

<footer>
  <p>&copy; Transmillas | Todos los derechos reservados</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


