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

	if ($_REQUEST['q'] == "") {

		header('Location: ../');

	}
	else
	{

		require("../m/sistema.class.php");

		$sistema = new Sistema;

		$con = $sistema->conexion();

		switch ($_REQUEST["q"]) {

			case 'create_respald':

				$respuesta = $sistema -> createRespalBD();
				break;

			case 'delete_zip_create_respald':

				$respuesta = $sistema -> deleteZipBackup();
				break;

		}

		if($respuesta!=null){
			echo json_encode($respuesta);
		}

	}
}
 ?>