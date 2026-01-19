<?php
// Detectar si viene error_login=8
$sesionExpirada = (isset($_GET['error_login']) && $_GET['error_login'] == 8);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transmillas - Sesión Expirada</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: #fff;
            width: 90%;
            max-width: 420px;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h2 {
            color: #d9534f;
            margin-bottom: 10px;
        }

        .card p {
            color: #555;
            font-size: 15px;
            margin-bottom: 25px;
        }

        .btn-login {
            display: inline-block;
            padding: 12px 22px;
            background: #0069d9;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.2s ease-in-out;
        }

        .btn-login:hover {
            background: #004a9f;
        }

        .logo {
            width: 90px;
            margin-bottom: 20px;
            opacity: 0.85;
        }
    </style>
</head>
<body>

<div class="card">
    <img class="logo" src="https://sistema.transmillas.com/images/Logo Google Nuevo.png" alt="Logo">

    <?php if ($sesionExpirada): ?>
        <h2>Sesión Expirada</h2>
        <p>Tu sesión ha finalizado por inactividad.<br>
           Por favor vuelve a iniciar sesión para continuar.</p>
    <?php else: ?>
        <h2>Bienvenido</h2>
        <p>Por favor inicia sesión para continuar.</p>
    <?php endif; ?>

    <a class="btn-login" href="https://sistema.transmillas.com">Iniciar Sesión</a>
</div>

</body>
</html>
