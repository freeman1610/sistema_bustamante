<?php
	session_start();
	if (!(array_key_exists("id_sesion", $_SESSION)) || ($_SESSION["id_sesion"]!=session_id()))
	{
		echo '
		<head>
		<title>Error 403</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="css/estilos.css">
		</head>
		<body class="logout">
			<div class="container">
				<div class="main-principal-logout">
					<div>
						<span> No has iniciado sesión </span>
						<div>
							<p class="login_a" >Regresar a <button class="btn-regresar-login">Login</button></p>
						</div>
					</div>
				</div>
			</div>
		</body>
		<script>
			document.querySelector(".btn-regresar-login").addEventListener("click", () => {
				window.location.href="../";
			});
		</script>
		';
		}
	else
	{
	$nick_usuario=$_SESSION['nick_usuario'];

	$hora = $_SESSION['hora'];
	$fecha_dia_letra = $_SESSION['fecha_dia_letra'];
	$fecha_dia = $_SESSION['fecha_dia'];
	$fecha_mes = $_SESSION['fecha_mes'];
	$fecha_year = $_SESSION['fecha_year'];

	// Convierto el $fecha_dia_letra del ingles al español con el switch de abajo

	switch ($fecha_dia_letra) {
		case 'Monday':
			$fecha_dia_actual = "Lunes";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Tuesday':
			$fecha_dia_actual = "Martes";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Wednesday':
			$fecha_dia_actual = "Miercoles";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Thursday':
			$fecha_dia_actual = "Jueves";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Friday':
			$fecha_dia_actual = "Viernes";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Saturday':
			$fecha_dia_actual = "Sabado";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
		case 'Sunday':
			$fecha_dia_actual = "Domingo";
			$_SESSION['fecha_dia_actual'] = $fecha_dia_actual; 
			break;
	}

	// Convierto el $fecha_mes en un string dependiendo del mes (numero), con el switch de abajo

	switch ($fecha_mes) {
		case '1':
			$fecha_mes_actual = "Enero";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '2':
			$fecha_mes_actual = "Febreo";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '3':
			$fecha_mes_actual = "Marzo";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '4':
			$fecha_mes_actual = "Abril";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '5':
			$fecha_mes_actual = "Mayo";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '6':
			$fecha_mes_actual = "Junio";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '7':
			$fecha_mes_actual = "Julio";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '8':
			$fecha_mes_actual = "Agosto";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '9':
			$fecha_mes_actual = "Septiembre";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '10':
			$fecha_mes_actual = "Octubre";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '11':
			$fecha_mes_actual = "Noviembre";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
		case '12':
			$fecha_mes_actual = "Diciembre";
			$_SESSION['fecha_mes_actual'] = $fecha_mes_actual;
			break;
	}

	//Escojo la apariencia de la interfaz dependiendo del tipo de usuario

	if ($_SESSION['tipo_usuario'] == 0) {
		$rol = "adm";
	}elseif ($_SESSION['tipo_usuario'] == 1) {
		$rol = "dir";
	}elseif ($_SESSION['tipo_usuario'] == 2) {
		$rol = "sec";
	}

	// Tema
	$tema = $_SESSION['tema'];
 ?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/fontello.css">
		<link rel="stylesheet" href="css/estilos.css">
		<link rel="stylesheet" href="css/sweetalert2.min.css">
		<title>Sistema de Certificaciones Finales</title>
	</head>
	<body style="<?php if ($tema == 0) {
		echo "background: white; transition: 1s;";
	}else if ($tema == 1){
		echo "background: rgba(0,0,0, .5); transition: 1s; color: #fff;";
	} ?>" id="<?php echo $rol; ?>">
		<div class="container-fluid">
			<div class="row">			
				<div class="barra-lateral col-12 col-sm-auto">
					<div class="logo">
						<h2>Usuario: <?php echo $nick_usuario; ?></h2>
					</div>
					<nav class="menu d-flex d-sm-block justify-content-center flex-wrap">
						<button title="Inicio" class="btn-inicio">
							<i class="icon-home"></i>
							<span>Inicio</span>
						</button>
						<button title="Auditoria de Datos" class="btn-registro-actividad">
							<i class="icon-flag"></i>
							<span>Auditoria de Datos</span>
						</button>
						<button title="Documentos y Estudiantes" class="btn-crear-certificacion">
							<i class="icon-doc-text"></i>
							<span>Documentos y Estudiantes</span>
						</button>
						<button title="Buscar Estudiante" class="btn-search-certificado">
							<i class="icon-search"></i>
							<span>Buscar Estudiante</span>
						</button>
						<?php 

						if($_SESSION['tipo_usuario'] == 0)
						{
							echo '
							<button title="Usuarios y Sistema" class="btn-editar-user">
								<i class="icon-users"></i>
								<span>Usuarios y Sistema</span>
							</button>
								';
						}elseif ($_SESSION['tipo_usuario'] == 1) {
							echo '
							<button title="Usuarios" class="btn-editar-user">
								<i class="icon-users"></i>
								<span>Usuarios</span>
							</button>
								';
						}elseif ($_SESSION['tipo_usuario'] == 2) {
							echo '
							<button title="Editar Usuario" class="btn-editar-user">
								<i class="icon-users"></i>
								<span>Editar Usuario</span>
							</button>
								';
						}

						 ?>
						<button title="Cerrar Sesión" class="btn-cerrar-sesion">
							<i class="icon-logout"></i>
							<span>Cerrar Sesión</span>
						</button>
					</nav>
				</div>				
				<main class="main col">
					<div class="row men col-12 col-md-12 justify-content-center mb-4">
						<div id="centro" style="<?php if ($tema == 0) {
							echo "background: #fff;transition: 1s;";
						} else if ($tema == 1) {
							echo "background: #343a40;transition: 1s;";
						}
						 ?>" class="centro col-12">		
							<div class="main inicio-home">
								<div>
									<div class="h1 mb-4 main-letra" align="center">¡Bienvenido <?php echo $nick_usuario; ?>!
									</div>
									<h5>
										<small class="text-muted main-letra">Inicio de la ultima sesión, el <?php echo $fecha_dia_actual; ?> <?php echo $fecha_dia; ?> de <?php echo $fecha_mes_actual; ?> del <?php echo $fecha_year; ?> a las <?php echo $hora; ?></small>
									</h5>
								</div>
							</div>
						</div>
					</div>
				</main>
			</div>
			<div class="subir-contenedor">
				<div class="subir-boton">
					<img src="img/arriba.png" width="50">
				</div>
			</div>
		</div>
		<script src="js/sweetalert2.all.min.js"></script>
		<script src="js/tether.min.js"></script>
		<script src="js/jquery-3.2.1.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/plotly-latest.min.js"></script>
		<script src="js/js.js"></script>
	</body>
</html>
<?php 
} ?>