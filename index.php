<?php 
// Created By: José M. González A.
session_start();
if (isset($_SESSION["id_sesion"])){
	header("Location: v/");

}
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Login</title>
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="v/css/login.css">
		<link rel="stylesheet" href="v/css/sweetalert2.min.css">
	</head>
	<body onload="document.getElementById('nick_usuario').focus()">
    <div class="container">
    	<div class="container-sl">
    		<div class="slide">
				<div class="slide__item is-active"></div>
				<div class="slide__item"></div>
				<div class="slide__item"></div>
				<!-- 
				<div class="slide__item"></div>
				<div class="slide__item"></div>
				<div class="slide__item"></div>
				<div class="slide__item"></div>
 -->		</div>
    	</div>
    	<div class="row formulario">
	    	<div class="login-caja">
	            <h1>Bienvenido</h1>
	            <form name="form_login" id="form_login">
	            	<label for="nick_usuario">Usuario</label>
	            	<input type="text" id="nick_usuario" name="nick_usuario" placeholder="Usuario" autocomplete="off" required>
		            <label for="contra_usuario">Contraseña</label>
	    	        <input type="password" id="contra_usuario" name="contra_usuario" placeholder="Contraseña" required>
	        	    <button type="submit" class="boton-submit">Entrar</button>
	            </form>
	    	</div>

		<!-- 			Slide -- End 			-->
			
			<div class="title__container">
				<h2 class="title" align="center">Sistema de Certificaciones Finales"Bustamante"</h2>
			</div>
    	</div>
    </div>
    <script src="v/js/sweetalert2.all.min.js"></script>
    <script src="v/js/lg.js"></script>
    <script src="v/js/slide.js"></script>
  </body>
</html>
