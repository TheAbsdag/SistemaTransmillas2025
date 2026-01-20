<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
<title>Transmillas </title >
<link rel="shortcut icon" href="images/Logo Google Nuevo.png" />
<style>
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
  background: linear-gradient(135deg, #0f3c78, #0a2a52);
  color: #fff;
  overflow: hidden;
}

/* Wrapper */
.wrapper {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Main Card */
.container {
  width: 100%;
  max-width: 420px;
  padding: 45px 35px;
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-radius: 22px;
  box-shadow:
    0 30px 60px rgba(0,0,0,0.45),
    inset 0 1px 0 rgba(255,255,255,0.08);
  text-align: center;
  z-index: 2;
}

/* Brand */
.brand img {
  width: 70px;
  margin-bottom: 12px;
}

.brand h1 {
  font-size: 26px;
  letter-spacing: 3px;
  font-weight: 600;
}

.brand p {
  font-size: 14px;
  opacity: 0.75;
  margin-bottom: 30px;
}

/* Form */
form span {
  display: block;
  margin-bottom: 12px;
  font-size: 14px;
}

form input[type="text"],
form input[type="password"] {
  width: 100%;
  padding: 15px 16px;
  margin-bottom: 15px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,0.25);
  background: rgba(255,255,255,0.12);
  color: #fff;
  font-size: 15px;
  outline: none;
  transition: all .3s ease;
}

form input::placeholder {
  color: rgba(255,255,255,.65);
}

form input:focus {
  background: rgba(255,255,255,0.2);
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239,68,68,.25);
}

/* Button */
form input[type="button"] {
  width: 100%;
  padding: 15px;
  margin-top: 5px;
  border-radius: 14px;
  border: none;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  color: white;
  background: linear-gradient(135deg, #ef4444, #b91c1c);
  transition: all .3s ease;
}

form input[type="button"]:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(239,68,68,.45);
}

/* Background accents */
.bg-bubbles {
  position: absolute;
  inset: 0;
  z-index: 1;
}

.bg-bubbles li {
  position: absolute;
  list-style: none;
  width: 60px;
  height: 60px;
  background: rgba(255,255,255,0.08);
  bottom: -160px;
  border-radius: 50%;
  animation: float 30s infinite linear;
}

.bg-bubbles li:nth-child(odd) {
  background: rgba(255,255,255,0.12);
}

/* Animation */
@keyframes float {
  0% {
    transform: translateY(0) rotate(0deg);
  }
  100% {
    transform: translateY(-900px) rotate(360deg);
  }
}
</style>



</style>
  </head>
  <body>
    <div class="wrapper">
	<div class="container" >
    <div class="brand">
      <img src="images/logo-transmillas.png" alt="Transmillas">
      <h1>TRANSMILLAS</h1>
      <p>Sistema de Gestión</p>
    </div>
		
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