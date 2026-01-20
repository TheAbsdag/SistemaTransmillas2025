<html>
<head>
<meta charset="UTF-8" name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" >
<title>Transmillas </title >
<link rel="shortcut icon" href="images/Logo Google Nuevo.png" />
<style>

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: 'Inter', sans-serif;
  min-height: 100vh;
  background: linear-gradient(
    135deg,
    #0b3c6f 0%,
    #0f4c81 60%,
    #0b3c6f 100%
  );
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Card */
.container {
  width: 100%;
  max-width: 420px;
  background: #ffffff;
  border-radius: 10px;
  padding: 35px 30px;
  box-shadow: 0 15px 35px rgba(0,0,0,.25);
  text-align: center;
}

/* Brand */
.brand img {
  width: 65px;
  margin-bottom: 10px;
}

.brand h1 {
  font-size: 22px;
  font-weight: 600;
  letter-spacing: 2px;
  color: #0b3c6f;
}

.brand p {
  font-size: 13px;
  color: #64748b;
  margin-bottom: 25px;
}

/* Inputs */
form input[type="text"],
form input[type="password"] {
  width: 100%;
  padding: 12px 14px;
  margin-bottom: 14px;
  border-radius: 6px;
  border: 1px solid #d9e2ec;
  font-size: 14px;
  outline: none;
  transition: border .2s ease, box-shadow .2s ease;
}

form input:focus {
  border-color: #0b3c6f;
  box-shadow: 0 0 0 3px rgba(11,60,111,.15);
}

/* Button */
form input[type="button"] {
  width: 100%;
  padding: 12px;
  margin-top: 8px;
  border-radius: 6px;
  border: none;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  color: white;
  background: linear-gradient(#0f4c81, #0b3c6f);
}

form input[type="button"]:hover {
  background: linear-gradient(#135c99, #0b3c6f);
}

/* Error */
form span {
  display: block;
  margin-bottom: 10px;
  color: #dc2626;
  font-size: 13px;
}

</style>



</style>
  </head>
  <body>
    <div class="wrapper">
	<div class="container" >
        <div class="brand">
          <img src="images/logo-transmillas.png">
          <h1>TRANSMILLAS</h1>
          <p>Plataforma Administrativa</p>
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