<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
<title>Transmillas </title >
<link rel="shortcut icon" href="images/Logo Google Nuevo.png" />
<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Inter', sans-serif;
  min-height: 100vh;
  background-color: #0f172a;
  color: #fff;
  overflow: hidden;
}

/* Background responsive */
@media (min-width: 768px) {
  body {
    background-image: url('images/Fondos de pantalla_Nueva.png');
    background-size: cover;
    background-position: center;
  }
}

@media (max-width: 767px) {
  body {
    background-image: url('images/Fondos de celular-Nueva.png');
    background-size: cover;
    background-position: center;
  }
}

/* Wrapper */
.wrapper {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Card glass */
.container {
  width: 100%;
  max-width: 380px;
  padding: 40px 30px;
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  border-radius: 18px;
  box-shadow: 0 25px 50px rgba(0,0,0,.35);
  text-align: center;
  z-index: 2;
}

/* Title */
.container h1 {
  font-size: 32px;
  font-weight: 600;
  margin-bottom: 30px;
}

/* Form */
form {
  position: relative;
}

/* Inputs */
form input[type="text"],
form input[type="password"] {
  width: 100%;
  padding: 14px 16px;
  margin-bottom: 15px;
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,0.25);
  background: rgba(255,255,255,0.15);
  color: #fff;
  font-size: 15px;
  outline: none;
  transition: all .3s ease;
}

form input::placeholder {
  color: rgba(255,255,255,.7);
}

form input:focus {
  background: rgba(255,255,255,0.25);
  border-color: #38bdf8;
  box-shadow: 0 0 0 3px rgba(56,189,248,.25);
}

/* Button */
form input[type="button"] {
  width: 100%;
  padding: 14px;
  border-radius: 12px;
  border: none;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  color: #0f172a;
  background: linear-gradient(135deg, #38bdf8, #22d3ee);
  transition: all .3s ease;
}

form input[type="button"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(56,189,248,.45);
}

/* Error message */
form span {
  display: block;
  margin-bottom: 15px;
  font-size: 14px;
}

/* Background bubbles */
.bg-bubbles {
  position: absolute;
  inset: 0;
  z-index: 1;
}

.bg-bubbles li {
  position: absolute;
  list-style: none;
  width: 40px;
  height: 40px;
  background: rgba(255,255,255,0.12);
  bottom: -150px;
  border-radius: 50%;
  animation: float 25s infinite linear;
}

.bg-bubbles li:nth-child(odd) {
  background: rgba(255,255,255,0.18);
}

/* Animation */
@keyframes float {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  100% {
    transform: translateY(-800px) rotate(360deg);
  }
}


</style>
  </head>
  <body>
    <div class="wrapper">
	<div class="container" >
		
        <form name="form1" method="post" action="login_autentica.php?ingreso='desarrollo'" class="form-2">
        <?php  
        include("mensaje_error.php");
        if (isset($_REQUEST['error_login'])){ $error=$_REQUEST['error_login']; echo "<span style='color:#FFFFFF'>".$error_login_ms[$error]."</span>"; }
		echo "<br><br><br>";
        ?>
				<input type="text" placeholder="Usuario" name="user" id="user">
				<input type="password" placeholder="Password" name="pass" id="pass">
         <!-- 👇 NUEVO -->
        <input type="hidden" name="device_id" id="device_id">
				<input type="button" value="Login" onClick="form1.submit();">

		</form>        
	</div>
	<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
	</ul>
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