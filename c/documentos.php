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
		require("../m/documentos.class.php");

		$documentos = new Documentos;

		$id_usuarioo = $_SESSION['id_usuario'];

		$con = $documentos->conexion();

		switch ($_REQUEST["q"]) {
			case 'save_estudiante':

				$nombre = $_REQUEST['nombre'];
				$apellido = $_REQUEST['apellido'];
				$nacionalidad = $_REQUEST['nacionalidad'];
				$cedula = $_REQUEST['cedula'];
				$fecha_na = $_REQUEST['fecha_na'];
				$lugar_nacimiento = $_REQUEST['lugar_nacimiento'];
				$literal = $_REQUEST['literal'];
				$periodo_escolar = $_REQUEST['periodo_escolar'];

				$respuesta = $documentos -> saveEstudiante($nombre,$apellido,$nacionalidad,$cedula,$fecha_na,$lugar_nacimiento,$literal,$periodo_escolar,$con);

				break;

			case 'update_estudiante':

				$id_estudiante = $_REQUEST['id_estudiante'];
				$nombre = $_REQUEST['nombre'];
				$apellido = $_REQUEST['apellido'];
				$nacionalidad = $_REQUEST['nacionalidad'];
				$cedula = $_REQUEST['cedula'];
				$cedula_old = $_REQUEST['cedula_old'];
				$fecha_na = $_REQUEST['fecha_na'];
				$lugar_nacimiento = $_REQUEST['lugar_nacimiento'];
				$literal = $_REQUEST['literal'];
				$periodo_escolar = $_REQUEST['periodo_escolar'];

				$respuesta = $documentos -> updateEstudiante($id_estudiante,$nombre,$apellido,$nacionalidad,$cedula,$cedula_old,$fecha_na,$lugar_nacimiento,$literal,$periodo_escolar,$con);

				break;
			case 'save_plantilla':

				$nombre_director = $_REQUEST['nombre_director'];
				$apellido_director = $_REQUEST['apellido_director'];
				$nacionalidad_director = $_REQUEST['nacionalidad_director'];
				$genero = $_REQUEST['genero'];
				$cedula_director = $_REQUEST['cedula_director'];

				$respuesta = $documentos -> savePlantilla($nombre_director,$apellido_director,$nacionalidad_director,$genero,$cedula_director,$con);

				break;
			case 'generar_certificado_final':

				$id_estudiante = $_REQUEST['id_estudiante'];	

				$respuesta = $documentos -> generarCertificadoFinal($id_estudiante,$con);

				break;

			case 'generar_acta_buena_conducta':

				$id_estudiante = $_REQUEST['id_estudiante'];	

				$respuesta = $documentos -> generarConstanciaBuenaConducta($id_estudiante,$con);

				break;
 
			case 'generar_constancia_prosecucion':

				$id_estudiante = $_REQUEST['id_estudiante'];	

				$respuesta = $documentos -> generarConstanciaProsecucion($id_estudiante,$con);

				break;

			case 'create_example_pdf_cer_final':

				$respuesta = $documentos -> generarEjemCertificadoFinal($con);


				break;

			case 'guardar_creacion_ejem_cer_final':

				$guardar_creacion_ejem_cer_final = $con->query("INSERT INTO `crear_doc_cert_final_ejem`(`id_num_example_cer_final`, `id_usuario`, `fecha`) VALUES (NULL, '$id_usuarioo', CURRENT_TIMESTAMP)");

				break;

			case 'create_example_pdf_cons_cond':

				$respuesta = $documentos -> generarEjemConstanciaBuenaConducta($con);


				break;

			case 'guardar_creacion_ejem_const_conducta':
				
				$guardar_creacion_ejem_const_conducta = $con->query("INSERT INTO `crear_doc_const_conducta_ejem`(`id_num_example_const_conducta`, `id_usuario`, `fecha`) VALUES (NULL, '$id_usuarioo', CURRENT_TIMESTAMP)");
				
				break;

			case 'create_example_pdf_cons_prese':

				$respuesta = $documentos -> generarEjemConstanciaProsecucion($con);


				break;

			case 'guardar_creacion_ejem_const_prose':

				$guardar_creacion_ejem_const_prose = $con->query("INSERT INTO `crear_doc_const_prose_ejem`(`id_num_example_const_prose`, `id_usuario`, `fecha`) VALUES (NULL, '$id_usuarioo', CURRENT_TIMESTAMP)");

				break;	

			case 'create_list_user':

				$respuesta = $documentos -> generarListaUsers($con);

				break;

			case 'create_pdf_accion_user':

				$id_usuario = $_REQUEST['id_usuario'];

				$respuesta = $documentos -> pdfAccionesUser($id_usuario,$con);

				break;

			case 'create_pdf_bxc':

				$respuesta = $documentos -> pdfBusquedaXCedula($con);

				break;
			
			case 'create_pdf_bxf':

				$respuesta = $documentos -> pdfBusquedaXFechas($con);

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

 