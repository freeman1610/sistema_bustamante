<?php 
if ($_REQUEST['q'] == "") {

	header('Location: ../');

}
else
{

	require("../m/usuario.class.php");

	$usuario = new Usuario;

	$con = $usuario->conexion();

	switch ($_REQUEST["q"]) {
		case 'login':

			$nick_usuario = $_REQUEST["nick_usuario"];
			$contra_user = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> login($nick_usuario,$contra_user,$con);
			break;

		case 'save_us_ad':

			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$tipo_usuario = $_REQUEST["tipo_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$contra_usuario = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> guardarUsuarioAdmin($nombre_usuario,$apellido_usuario,$tipo_usuario,$nick_usuario,$contra_usuario,$con);
			break;

		case 'save_us_dir':

			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$contra_usuario = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> guardarUsuarioDirector($nombre_usuario,$apellido_usuario,$nick_usuario,$contra_usuario,$con);
			break;

		case 'update_us_ad':

			$id_usuario = $_REQUEST["id_usuario"];
			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$tipo_usuario = $_REQUEST["tipo_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$nick_usuario_old = $_REQUEST["nick_usuario_old"];
			$contra_usuario = $_REQUEST["contra_usuario"];
			$status_usuario = $_REQUEST["status_usuario"];

			$respuesta = $usuario -> updateUserAd($id_usuario,$nombre_usuario,$apellido_usuario,$tipo_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$status_usuario,$con);
			break;

		case 'update_us_ad_i':

			$id_usuario = $_REQUEST["id_usuario"];
			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$tipo_usuario = $_REQUEST["tipo_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$nick_usuario_old = $_REQUEST["nick_usuario_old"];
			$contra_usuario = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> updateUserAdI($id_usuario,$nombre_usuario,$apellido_usuario,$tipo_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con);
			break;

		case 'update_us_dir':

			$id_usuario = $_REQUEST["id_usuario"];
			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$nick_usuario_old = $_REQUEST["nick_usuario_old"];
			$contra_usuario = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> updateUserDir($id_usuario,$nombre_usuario,$apellido_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con);
			break;

		case 'update_user_sec':

			$id_usuario = $_REQUEST["id_usuario"];
			$nombre_usuario = $_REQUEST["nombre_usuario"];
			$apellido_usuario = $_REQUEST["apellido_usuario"];
			$nick_usuario = $_REQUEST["nick_usuario"];
			$nick_usuario_old = $_REQUEST["nick_usuario_old"];
			$contra_usuario = $_REQUEST["contra_usuario"];

			$respuesta = $usuario -> updateUserSec($id_usuario,$nombre_usuario,$apellido_usuario,$nick_usuario,$nick_usuario_old,$contra_usuario,$con);
			break;

		case 'delete':
			$id_usuario = $_REQUEST['id_usuario'];
			$respuesta = $usuario -> deleteUser($id_usuario,$con);
			break;

		case 'active':
			$id_usuario = $_REQUEST['id_usuario'];
			$respuesta = $usuario -> activarUser($id_usuario,$con);
			break;

		case 'logout':

			$respuesta = $usuario -> logout();

			break;
	}

	if($respuesta!=null)
		echo json_encode($respuesta);

}

 ?>