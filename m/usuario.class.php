<?php 

require("items.class.php");

//Verificar registro de usuario

function existUser($nick_usuario,$con)
{
	$users = $con->query("SELECT nick_usuario FROM usuarios WHERE nick_usuario='$nick_usuario'");

	if($users->num_rows === 1){
		return true;
	}
	else{
		return false;
	}
	mysqli_close($con);
}

class Usuario extends Items
{
	//Insertar / Guardar Usuario
	public function guardarUsuarioAdmin($nombre,$apellido,$tipo_usuario,$nick_usuario,$contra_usuario,$con)
	{
		if(!existUser($nick_usuario,$con)){	

			$sql = "INSERT INTO usuarios (`id_usuario`, `nick_usuario`, `contra_usuario`, `nombre_usuario`, `apellido_usuario`, `tipo_usuario`, `fecha_registro`, `status_usuario`) VALUES (NULL, '$nick_usuario', '$contra_usuario', '$nombre', '$apellido', '$tipo_usuario', CURRENT_TIMESTAMP, '1')";


			$ok = $con->query($sql);

			if ($con->affected_rows > 0)
			{
				session_start();
				$id_usuario_session = $_SESSION['id_usuario'];
				$tipo_usuario_session = $_SESSION['tipo_usuario'];
				$nick_usuario_session = $_SESSION['nick_usuario'];
				$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :REGISTRO: al usuario :$nick_usuario: con el rol de: $tipo_usuario', CURRENT_TIMESTAMP)");
				return ["code" => 201];
			}

			
		}else
			return ["code" => 1];
	}

	public function guardarUsuarioDirector($nombre_usuario,$apellido_usuario,$nick_usuario,$contra_usuario,$con)
	{
		if(!existUser($nick_usuario,$con)){	

			$sql = "INSERT INTO usuarios (`id_usuario`, `nick_usuario`, `contra_usuario`, `nombre_usuario`, `apellido_usuario`, `tipo_usuario`, `fecha_registro`, `status_usuario`) VALUES (NULL, '$nick_usuario', '$contra_usuario', '$nombre_usuario', '$apellido_usuario', '2', CURRENT_TIMESTAMP, '1')";
			$ok = $con->query($sql);

			if ($con->affected_rows > 0)
			{
				session_start();
				$id_usuario_session = $_SESSION['id_usuario'];
				$tipo_usuario_session = $_SESSION['tipo_usuario'];
				$nick_usuario_session = $_SESSION['nick_usuario'];
				$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :REGISTRO: al usuario :$nick_usuario: con el rol de: Secretari@', CURRENT_TIMESTAMP)");
				return ["code" => 201];
			}
			
		}else
			return ["code" => 1];
	}
	public function login($nick_usuario,$contra_usuario,$con)
	{
		session_start();

		if (!isset($_SESSION["id_sesion"])) {
				
			$users = $con->query("SELECT id_usuario, nick_usuario, status_usuario, tipo_usuario, tema FROM usuarios WHERE nick_usuario='$nick_usuario' AND contra_usuario='$contra_usuario'");
				$row = mysqli_fetch_assoc($users);
			if($users->num_rows === 1){
				if ($row['status_usuario'] == 1) {
					$_SESSION["id_sesion"] = session_id();
					$_SESSION['tema'] = $row['tema'];
					$_SESSION['id_usuario'] = $row['id_usuario'];
					$_SESSION['tipo_usuario'] = $row['tipo_usuario'];
					$_SESSION['nick_usuario'] = $nick_usuario;
					$id_usuario = $row['id_usuario'];

					$sql = $con->query("SELECT id_usuario, DATE_FORMAT(sesion, '%W') AS fecha_dia_letra, DATE_FORMAT(sesion, '%d') AS fecha_dia, DATE_FORMAT(sesion, '%m') AS fecha_mes, DATE_FORMAT(sesion, '%Y') AS fecha_year, DATE_FORMAT(sesion, '%r') AS hora FROM inicio_sesiones WHERE id_usuario=$id_usuario");

					if ($sql->num_rows == 1) {
						$row1 = mysqli_fetch_assoc($sql);
						$_SESSION['hora'] = $row1['hora'];
						$_SESSION['fecha_dia'] = $row1['fecha_dia'];
						$_SESSION['fecha_dia_letra'] = $row1['fecha_dia_letra'];
						$_SESSION['fecha_mes'] = $row1['fecha_mes'];
						$_SESSION['fecha_year'] = $row1['fecha_year'];
						$sql1 = $con->query ("UPDATE `inicio_sesiones` SET `sesion`= CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario'");
					} else {

						$sqll = $con -> query ("INSERT INTO `inicio_sesiones`(`id_usuario`, `sesion`) VALUES ($id_usuario,CURRENT_TIMESTAMP)");

						if ($sqll) {

							$sqlll = $con->query("SELECT id_usuario, DATE_FORMAT(sesion, '%d') AS fecha_dia, DATE_FORMAT(sesion, '%W') AS fecha_dia_letra, DATE_FORMAT(sesion, '%m') AS fecha_mes, DATE_FORMAT(sesion, '%Y') AS fecha_year, DATE_FORMAT(sesion, '%r') AS hora FROM inicio_sesiones WHERE id_usuario=$id_usuario");

							$row2 = $sqlll->fetch_assoc();
							if ($sqlll) {
								$_SESSION['hora'] = $row2['hora'];
								$_SESSION['fecha_dia'] = $row2['fecha_dia'];
								$_SESSION['fecha_dia_letra'] = $row2['fecha_dia_letra'];
								$_SESSION['fecha_mes'] = $row2['fecha_mes'];
								$_SESSION['fecha_year'] = $row2['fecha_year'];
							}
						}
						
					}
					return ["code" => 200]; //si todo va bien
				}elseif ($row['status_usuario'] == 0) {
					return ["code" => 3];
				}
			}else{
				return ["code" => 403];
			}
		}
		return ["code" => 403]; //ya hay un usuario iniciado
		mysqli_close($con);
	}

	// UPDATE USER_AD

	public function updateUserAd($id_usuario,$nombre_usuario,$apellido_usuario,$tipo_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$status_usuario,$con)
	{
		if(existUser($nick_usuario_old,$con)){

			if(($nick_usuario_old == $nick_usuario)  || (!existUser($nick_usuario,$con)) ){
				$sql = "UPDATE usuarios SET
				nick_usuario = '$nick_usuario',
				contra_usuario = '$contra_usuario',
				nombre_usuario ='$nombre_usuario',
				apellido_usuario ='$apellido_usuario',
				tipo_usuario ='$tipo_usuario',
				status_usuario ='$status_usuario' WHERE id_usuario = '$id_usuario'";

				$ok=$con->query($sql);

				if ($con->affected_rows > 0 || $ok)
				{
					session_start();
					$id_usuario_session = $_SESSION['id_usuario'];
					$tipo_usuario_session = $_SESSION['tipo_usuario'];
					$nick_usuario_session = $_SESSION['nick_usuario'];
					$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :ACTUALIZO: al usuario :$nick_usuario_old', CURRENT_TIMESTAMP)");
					return ["code" => 201];
				}
				else{
					return ["code" => 400];
				}
				mysqli_close($con);
			}else
				return ["code" => 1];
		}else
			return ["code" => 1];
	}


	public function updateUserAdI($id_usuario,$nombre_usuario,$apellido_usuario,$tipo_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con)
	{
		if(existUser($nick_usuario_old,$con)){

			if(($nick_usuario_old == $nick_usuario)  || (!existUser($nick_usuario,$con)) ){
				$sql = "UPDATE usuarios SET
				nick_usuario = '$nick_usuario',
				contra_usuario = '$contra_usuario',
				nombre_usuario ='$nombre_usuario',
				apellido_usuario ='$apellido_usuario',
				tipo_usuario ='$tipo_usuario' WHERE id_usuario = '$id_usuario'";

				$ok=$con->query($sql);

				if ($con->affected_rows > 0 || $ok)
				{
					session_start();
					$id_usuario_session = $_SESSION['id_usuario'];
					$tipo_usuario_session = $_SESSION['tipo_usuario'];
					$nick_usuario_session = $_SESSION['nick_usuario'];
					$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :ACTUALIZO: al usuario :$nick_usuario_old', CURRENT_TIMESTAMP)");
					return ["code" => 201];
				}
				else{
					return ["code" => 400];
				}
				mysqli_close($con);
			}else
				return ["code" => 1];
		}else
			return ["code" => 1];
	}

	public function updateUserDir($id_usuario,$nombre_usuario,$apellido_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con)
	{
		if(existUser($nick_usuario_old,$con)){

			if(($nick_usuario_old == $nick_usuario)  || (!existUser($nick_usuario,$con)) ){
				$sql = "UPDATE usuarios SET
				nick_usuario = '$nick_usuario',
				contra_usuario = '$contra_usuario',
				nombre_usuario ='$nombre_usuario',
				apellido_usuario ='$apellido_usuario' WHERE id_usuario = '$id_usuario'";

				$ok=$con->query($sql);

				if ($con->affected_rows > 0 || $ok)
				{
					session_start();
					$id_usuario_session = $_SESSION['id_usuario'];
					$tipo_usuario_session = $_SESSION['tipo_usuario'];
					$nick_usuario_session = $_SESSION['nick_usuario'];
					$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :ACTUALIZO: al usuario :$nick_usuario_old', CURRENT_TIMESTAMP)");
					return ["code" => 201];
				}
				else{
					return ["code" => 400];
				}
				mysqli_close($con);
			}else
				return ["code" => 1];
		}else
			return ["code" => 1];
	}

	// UPDATE USER SEC

	public function updateUserSec($id_usuario,$nombre_usuario,$apellido_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con)
	{
		if(existUser($nick_usuario_old,$con)){

			if(($nick_usuario_old == $nick_usuario)  || (!existUser($nick_usuario,$con)) ){
				$sql = "UPDATE usuarios SET
				nick_usuario = '$nick_usuario',
				contra_usuario = '$contra_usuario',
				nombre_usuario ='$nombre_usuario',
				apellido_usuario ='$apellido_usuario' WHERE id_usuario = '$id_usuario'";

				$ok=$con->query($sql);

				if ($con->affected_rows > 0 || $ok)
				{
					session_start();
					$id_usuario_session = $_SESSION['id_usuario'];
					$tipo_usuario_session = $_SESSION['tipo_usuario'];
					$nick_usuario_session = $_SESSION['nick_usuario'];
					$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), se actualizo', CURRENT_TIMESTAMP)");
					return ["code" => 201];
				}
				else{
					return ["code" => 400];
				}
				mysqli_close($con);
			}else
				return ["code" => 1];
		}else
			return ["code" => 1];
	}

	public function deleteUser($id_usuario,$con)
	{
		$sql = "UPDATE usuarios SET status_usuario = '0' WHERE id_usuario = '$id_usuario'";
		$ok=$con->query($sql);
		if ($con->affected_rows > 0 || $ok)
		{
			session_start();
			$sql_eliminar_nickusuario = $con->query("SELECT nick_usuario FROM usuarios WHERE id_usuario = '$id_usuario'");
			$result_sql_nickusuario = mysqli_fetch_assoc($sql_eliminar_nickusuario);
			$nick_usuario = $result_sql_nickusuario['nick_usuario'];
			$id_usuario_session = $_SESSION['id_usuario'];
			$tipo_usuario_session = $_SESSION['tipo_usuario'];
			$nick_usuario_session = $_SESSION['nick_usuario'];
			$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :ELIMINO: al usuario :$nick_usuario', CURRENT_TIMESTAMP)");
			return ["code" => 201];
		}
		else{
			return ["code" => 400];
		}
		mysqli_close($con);
	}

	public function activarUser($id_usuario,$con)
	{
		$sql = "UPDATE usuarios SET status_usuario = '1' WHERE id_usuario = '$id_usuario'";
		$ok=$con->query($sql);
		if ($con->affected_rows > 0 || $ok)
		{
			session_start();
			$sql_activar_nickusuario = $con->query("SELECT nick_usuario FROM usuarios WHERE id_usuario = '$id_usuario'");
			$result_sql_nickusuario = mysqli_fetch_assoc($sql_activar_nickusuario);
			$nick_usuario = $result_sql_nickusuario['nick_usuario'];
			$id_usuario_session = $_SESSION['id_usuario'];
			$tipo_usuario_session = $_SESSION['tipo_usuario'];
			$nick_usuario_session = $_SESSION['nick_usuario'];
			$registro_add_user = $con->query("INSERT INTO `acciones_usuarios`(`id_usuario`, `accion`, `fecha_accion`) VALUES ('$id_usuario_session', 'El Usuario :$nick_usuario_session: (rol actual :$tipo_usuario_session:), :ACTIVO: al usuario :$nick_usuario', CURRENT_TIMESTAMP)");
			return ["code" => 201];
		}
		else{
			return ["code" => 400];
		}
		mysqli_close($con);
	}

	public function logout()
	{
		session_start();
		session_unset();
		$_SESSION = array();
		if(isset($_COOKIE[session_name()]))
		{
			setcookie(session_name(), '', time() - 42000, '/');
		}
		if(isset($_COOKIE['PHPSESSID']))
		{ 
			setcookie("PHPSESSID",'',time());
		}
		session_destroy();
		return ["code" => 200];
	}
}

?>