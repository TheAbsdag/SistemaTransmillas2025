<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
<title>Transmillas </title >
<link rel="shortcut icon" href="images/Logo Google Nuevo.png" />
<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

/* RESET */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

/* BODY + BACKGROUND IMAGE */
body {
  font-family: 'Inter', sans-serif;
  min-height: 100vh;
  background-image: url('images/Fondos de pantalla_Nueva.png');
  background-size: cover;
  background-position: center;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* OVERLAY OSCURO */
body::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(
    rgba(11, 60, 111, 0.85),
    rgba(11, 60, 111, 0.85)
  );
  z-index: 0;
}

/* WRAPPER */
.wrapper {
  position: relative;
  z-index: 1;
  width: 100%;
  display: flex;
  justify-content: center;
}

/* CARD LOGIN */
.container {
  width: 100%;
  max-width: 420px;
  background: #ffffff;
  border-radius: 14px;
  padding: 40px 35px;
  box-shadow: 0 25px 50px rgba(0,0,0,0.35);
  text-align: center;
}

/* BRAND */
.brand img {
  width: 70px;
  margin-bottom: 12px;
}

.brand h1 {
  font-size: 24px;
  font-weight: 600;
  letter-spacing: 3px;
  color: #0b3c6f;
}

.brand p {
  font-size: 13px;
  color: #64748b;
  margin-bottom: 28px;
}

/* ERROR MESSAGE */
form span {
  display: block;
  margin-bottom: 12px;
  font-size: 13px;
  color: #dc2626;
}

/* INPUTS */
form input[type="text"],
form input[type="password"] {
  width: 100%;
  padding: 13px 15px;
  margin-bottom: 15px;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
  font-size: 14px;
  background: #f8fbff;
  outline: none;
  transition: all 0.2s ease;
}

form input::placeholder {
  color: #94a3b8;
}

form input:focus {
  border-color: #0b3c6f;
  box-shadow: 0 0 0 3px rgba(11,60,111,0.15);
  background: #ffffff;
}

/* BUTTON */
form input[type="button"] {
  width: 100%;
  padding: 13px;
  margin-top: 8px;
  border-radius: 8px;
  border: none;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  color: #ffffff;
  background: linear-gradient(#0f4c81, #0b3c6f);
  transition: background 0.2s ease, box-shadow 0.2s ease;
}

form input[type="button"]:hover {
  background: linear-gradient(#135c99, #0b3c6f);
  box-shadow: 0 10px 20px rgba(0,0,0,0.25);
}

/* REMOVE OLD BUBBLES / DOTS */
.bg-bubbles {
  display: none !important;
}

/* RESPONSIVE */
@media (max-width: 480px) {
  .container {
    padding: 35px 25px;
  }

  .brand h1 {
    font-size: 22px;
  }
}



</style>



</style>
  </head>
  <body>
    <div class="wrapper">
	<div class="container" >
        <div class="brand">
          <img src="images/Logo Google Nuevo.png">
          <h1>TRANSMILLAS</h1>
          <p>Plataforma Administrativa</p>
        </div>
		
        <form name="form1" method="post" action="login_autentica.php?ingreso='desarrollo'" class="form-2">
        <?php  
        include("mensaje_error.php");
        if (isset($_REQUEST['error_login'])){ $error=$_REQUEST['error_login']; echo "<span>".$error_login_ms[$error]."</span>"; }
		    echo "<br><br><br>";
        ?>
				<input type="text" placeholder="Usuario" name="user" id="user">
				<input type="password" placeholder="Password" name="pass" id="pass">
         <!-- 👇 NUEVO -->
        <input type="hidden" name="device_id" id="device_id">
				<input type="button" value="Login" onClick="form1.submit();">

		</form>        
	</div>

</div>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
 <script>
  (function () {

    function getDeviceId() {
      if (!localStorage.getItem('device_id')) {
        localStorage.setItem('device_id', crypto.randomUUID());
      }
      return localStorage.getItem('device_id');
    }

    // Cuando cargue la página, asignamos el device_id al hidden
    document.addEventListener('DOMContentLoaded', function () {
      var input = document.getElementById('device_id');
      if (input) {
        input.value = getDeviceId();
      }
    });

  })();
  </script> 
  </body>
</html>