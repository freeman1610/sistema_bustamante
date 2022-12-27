<?php
	session_start();
	if (!(array_key_exists("id_sesion", $_SESSION)) || ($_SESSION["id_sesion"]!=session_id()))
	{
		echo '
		<div class="main-principal-logout">
			<div>
				<div style="color:var(--yellow); font-size:35px;">Error 403</div>
				<span style="color:var(--dark);">Sesión terminada</span>
				<div>
					<p class="login_a" >Presione el boton de cerrar sección o ve al login para ingresar.</p>
				</div>
			</div>
		</div>
		';
		}
	else
	{ 
		
	if ($_REQUEST["q"] == "") {
		header('Location: ../');
	}
	else
	{
		require("../m/interfaz.class.php");

		$interfaz = new Interfaz;

		$con = $interfaz->conexion();

		switch ($_REQUEST["q"]) {

			case 'tema':
				$valor = $_REQUEST['valor'];
				$respuesta = $interfaz -> tema($valor,$con);
				break;
			
			case 'inicio':
			
				$respuesta = $interfaz -> inicio();
				break;

			case 'registros_act':
			
				$respuesta = $interfaz -> btnRegistroActvidades();
				break;
			
			case 'editar_us':
			
				$respuesta = $interfaz -> editarUsuarios();
					break;	
			
			case 'crear_us':
			
				$respuesta = $interfaz -> btnCrearUserAdmin();
				break;
			
			case 'registro_ac':
			
				$respuesta = $interfaz -> btnDocumentos();
				break;
			
			case 'create_cer':
			
				$respuesta = $interfaz -> crearEstudiante();
				break;
			
			case 'plantilla':

				$respuesta = $interfaz -> plantillaCertificacion();
				break;

			case 'list_users':

				$respuesta = $interfaz -> listaDeUsuarios($con);
				break;

			case 'update_user_sec_i':

				$respuesta = $interfaz -> interfazUpdateUserSec($con);
				break;

			case 'update_user_i':

				$id_usuario = $_REQUEST['id'];
				$respuesta = $interfaz -> updateUserI($id_usuario,$con);
				break;

			case 'delete_user':

				$id_usuario = $_REQUEST['id'];
				$respuesta = $interfaz -> deleteUser($id_usuario,$con);
				break;

			case 'buscar_cer':

				$respuesta = $interfaz -> searchCertificacion();
				break;

			case 'search_cedula':

				$cedula = $_REQUEST['cedula'];

				$respuesta = $interfaz -> busquedaPorCedula($cedula,$con);
				break;

			case 'search_fecha':

				$fecha_start = $_REQUEST['fecha_start'];

				$fecha_end = $_REQUEST['fecha_end'];		

				$respuesta = $interfaz -> busquedaPorFecha($fecha_start,$fecha_end,$con);
				break;

			case 'update_estudiante':

				$id_estudiante = $_REQUEST['id_estudiante'];		

				$respuesta = $interfaz -> modificarEstudiante($id_estudiante,$con);
				break;

			case 'ver_detalles_user':

				$id_usuario = $_REQUEST['id_usuario'];		

				$respuesta = $interfaz -> verDetallesUser($id_usuario,$con);
				break;

			case 'estadistica_mes_docs':
			
				$respuesta = $interfaz -> estadisticaDocumentosMes($con);
				break;

			default:
				header('Location: ../');
				break;
		}

		if($respuesta!=null){
			echo json_encode($respuesta);
		}
	}

}
 ?>