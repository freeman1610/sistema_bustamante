<?php 

require("items.class.php");
require_once '../v/pdf/vendor/autoload.php';
// Verifico si ya existe la cedula del estudiante

function existeCedulaEstudiante($cedula,$con)
{
	$buscar = $con->query("SELECT cedula FROM estudiantes WHERE cedula='$cedula'");

	if($buscar->num_rows === 1)
		return true;
	else 
		return false;
}

class Documentos extends Items
{

	//Guardo el Estudiante

	public function saveEstudiante($nombre,$apellido,$nacionalidad,$cedula,$fecha_na,$lugar_nacimiento,$literal,$periodo_escolar,$con)
	{
		if(!existeCedulaEstudiante($cedula,$con)){

			$id_usuario = $_SESSION['id_usuario'];

			$sql = "INSERT INTO estudiantes (`id_estudiante`, `id_usuario`, `nombre`, `apellido`, `nacionalidad`, `cedula`, `fecha_nacimiento_estudiante`, `lugar_nacimiento`, `literal`, `periodo_escolar`, `status`) VALUES (NULL, $id_usuario, '$nombre', '$apellido', '$nacionalidad', '$cedula', '$fecha_na', '$lugar_nacimiento', '$literal', '$periodo_escolar', '1')";

			$ok = $con->query($sql);

			if ($con->affected_rows > 0){

				$consultar_id_estudiante = $con -> query ("SELECT id_estudiante FROM estudiantes WHERE cedula=$cedula");

				$row = $consultar_id_estudiante->fetch_assoc();

				$id_estudiante = $row['id_estudiante'];

				$guardar_id_estudiante = $con -> query("INSERT INTO `registro_certificaciones`(`id_usuario`, `id_estudiantes`) VALUES ($id_usuario,$id_estudiante)");

				return ["code" => 201];
			}

		}else
			return ["code" => 1];
	}


	public function updateEstudiante($id_estudiante,$nombre,$apellido,$nacionalidad,$cedula,$cedula_old,$fecha_na,$lugar_nacimiento,$literal,$periodo_escolar,$con)
	{

		if(existeCedulaEstudiante($cedula_old,$con)){


			if(($cedula_old == $cedula)  || (!existeCedulaEstudiante($cedula,$con))){
				
				$sql = "UPDATE
						    `estudiantes`
						SET
						    `nombre` = '$nombre',
						    `apellido` = '$apellido',
						    `nacionalidad` = '$nacionalidad',
						    `cedula` = '$cedula',
						    `fecha_nacimiento_estudiante` = '$fecha_na',
						    `lugar_nacimiento` = '$lugar_nacimiento',
						    `literal` = '$literal',
						    `periodo_escolar` = '$periodo_escolar'
						WHERE
						    `id_estudiante` = $id_estudiante";

				$ok = $con->query($sql);

				if ($con->affected_rows > 0 || $ok)
				{
					$id_usuario = $_SESSION['id_usuario'];

					$guardar_modificacion = $con->query("INSERT INTO `estudiantes_modificados`(`id_usuario`, `id_estudiante`) VALUES ($id_usuario, $id_estudiante)");
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

	// Creo o Actualizo la plantilla del certificado

	public function  savePlantilla($nombre_director,$apellido_director,$nacionalidad_director,$genero,$cedula_director,$con)
	{
		$Documentos = new Documentos;
		$con = $Documentos->conexion();
		$comprobar = $Documentos->selectAll("plantilla_documentos",$con);

		// Compruebo si ya existe una plantilla en la BD
		// Si ya hay una plantilla la actualizo, sino, la creo. XD

		if($comprobar->num_rows ===1)
		{
			$id_usuario = $_SESSION['id_usuario'];

			$actualizar_plantilla = "UPDATE
									    `plantilla_documentos`
									SET
									    `nombre_director` = '$nombre_director',
									    `apellido_director` = '$apellido_director',
									    `nacionalidad` = '$nacionalidad_director',
									    `cedula_director` = '$cedula_director',
									    `genero` = '$genero'
									WHERE
    									`id_documentos` = 1";

			$ok = $con -> query ($actualizar_plantilla);

			if ($con->affected_rows > 0 || $ok){
				return ["code" => 2];
			}

		}
		else
		{

			$id_usuario = $_SESSION['id_usuario'];

			$sql = "INSERT INTO `plantilla_documentos` (`id_usuario`, `nombre_director`, `apellido_director`, `nacionalidad`, `cedula_director`, `genero`) VALUES ('$id_usuario','$nombre_director','$apellido_director','$nacionalidad_director','$cedula_director','$genero')";

			$ok = $con->query($sql);

			if ($con->affected_rows > 0){

				return ["code" => 201];
			
			}
		}

	}

	// Generar PDF.1 de Certificado Final
		

	public function generarCertificadoFinal($id_estudiante,$con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$row = $documentos->selectOne("estudiantes","id_estudiante",$id_estudiante,$con);
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);

		$row1 = $resultado_plantilla->fetch_assoc();

		$id_usuario = $_SESSION['id_usuario'];

		$nomE = $row['nombre'];
		$apeE = $row['apellido'];
		$ced = $row['cedula'];
		$fecR = $row['fecha_registro'];

		$codigo_certificado = hash('md5', "$nomE $apeE Certificado Final $ced $fecR");
		$comprobar_codigo_cer_exist = $con->query("SELECT codigo_documento FROM registro_documentos WHERE codigo_documento = '$codigo_certificado'  AND tipo_documento = 'Certificación Final' AND id_estudiantes = '$id_estudiante'");

		if ($comprobar_codigo_cer_exist->num_rows == 0) {
			$gurdar_registro_pdf_cer_fin = $con->query("INSERT INTO `registro_documentos`(`codigo_documento`, `tipo_documento`, `id_usuario`, `id_estudiantes`) VALUES ('$codigo_certificado','Certificación Final','$id_usuario','$id_estudiante')");
		}elseif ($comprobar_codigo_cer_exist->num_rows > 0) {
			$update_fecha_cer_final =  $con->query("UPDATE registro_documentos SET fecha_registro = CURRENT_TIMESTAMP WHERE codigo_documento = '$codigo_certificado'");
		}
		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Certficacion Final</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="font-size: 16px; margin-top: 30px; text-align: center;" align="center">
								<span style="font-size: 20px;"><strong>CERTIFICADO</strong></span>
								<br>
							<span style="font-size: 20px; margin-top: -10px;"><strong>DE EDUCACIÓN PRIMARIA</strong></span>
								<br>
							</div>
						</div>
						<span style="line-height: 1.5;">
								Quien suscribe <strong style="text-decoration: underline;"><!-- Nombre y apellido del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong> titular de la Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del director --><?php 
								if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}?></strong> en su condición de Director(a) (E) de la <strong style="text-decoration: underline;"><!-- Nombre de la escuela -->Unidad Educativa Básica “Bustamante”</strong>, ubicado en  el municipio <strong style="text-decoration: underline;">San Cristobal</strong> de la parroquia <strong style="text-decoration: underline;">La Ermita</strong> adscrito  a la Zona Educativa del estado <strong style="text-decoration: underline;">Táchira</strong> certifica por medio de la presente que el (la) estudiante <strong style="text-decoration: underline;"> <!-- Nombre y Apellido del estudiante --> <?php echo $row['apellido']; ?> <?php echo $row['nombre'] ?></strong>, titular de Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del estudiante --><?php 

								if ($row['nacionalidad'] == "venezolana") {
									
									echo 'V-'.$row['cedula'].'';

								}elseif ($row['nacionalidad'] == "extranjera") {
									
									echo 'E-'.$row['cedula'].'';
								
								}

								 ?></strong>, nacido (a) en: <strong style="text-decoration: underline;"><!-- Lugar de Nacimiento --><?php echo $row['lugar_nacimiento']; ?></strong> en fecha: <strong style="text-decoration: underline;"><!-- Fecha de Nacimiento --><?php echo $row['fecha_nacimiento_estudiante']; ?></strong>,cursó el: <strong style="text-decoration: underline;">6to Grado</strong> correspondiéndole el literal: <strong style="text-decoration: underline;"><!-- Literal del estudiante -->“<?php echo $row['literal']; ?>”</strong> durante el período escolar <strong style="text-decoration: underline;"><!-- Perido Escolar --><?php echo $row['periodo_escolar']; ?></strong><strong>, siendo promovido (a) al</strong> <strong style="text-decoration: underline;">1ER AÑO</strong> <strong>del Nivel de Educación Media</strong>, previo cumplimiento a los requisitos establecidos en la Normativa Legal vigente.
						</span>
						<br>
						<br>
						<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 
							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}


							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 80px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
		<br>
		<br>
		<br>
		<div align="right">
			<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: <?php echo $codigo_certificado; ?></strong>
		</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('certificado_final_'.$codigo_certificado.'_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}

	// "EJEMPLO PDF.2" DE LA CERTFICACION FINAL

	public function generarEjemCertificadoFinal($con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);

		$row1 = $resultado_plantilla->fetch_assoc();

		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Certficacion Final</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="font-size: 16px; margin-top: 30px; text-align: center;" align="center">
								<span style="font-size: 20px;"><strong>CERTIFICADO</strong></span>
								<br>
							<span style="font-size: 20px; margin-top: -10px;"><strong>DE EDUCACIÓN PRIMARIA</strong></span>
								<br>
							</div>
						</div>
						<span style="line-height: 1.5;">
								Quien suscribe <strong style="text-decoration: underline;"><!-- Nombre y apellido del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong> titular de la Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del director --><?php 
								if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}?></strong> en su condición de Director(a) (E) de la <strong style="text-decoration: underline;"><!-- Nombre de la escuela -->Unidad Educativa Básica “Bustamante”</strong>, ubicado en  el municipio <strong style="text-decoration: underline;">San Cristobal</strong> de la parroquia <strong style="text-decoration: underline;">La Ermita</strong> adscrito  a la Zona Educativa del estado <strong style="text-decoration: underline;">Táchira</strong> certifica por medio de la presente que el (la) estudiante <strong style="text-decoration: underline;"> <!-- Nombre y Apellido del estudiante -->(Nombre y Apellido del Estudiante)</strong>, titular de Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del estudiante -->(Cedula del Estudiante)</strong>, nacido (a) en: <strong style="text-decoration: underline;"><!-- Lugar de Nacimiento -->(Lugar de Nacimiento del Estudiante)</strong> en fecha: <strong style="text-decoration: underline;"><!-- Fecha de Nacimiento -->(Fecha de Nacimiento del Estudiante)</strong>,cursó el: <strong style="text-decoration: underline;">6to Grado</strong> correspondiéndole el literal: <strong style="text-decoration: underline;"><!-- Literal del estudiante -->“(Literal del Estudiante)”</strong> durante el período escolar <strong style="text-decoration: underline;"><!-- Perido Escolar -->(Perido Escolar)</strong><strong>, siendo promovido (a) al</strong> <strong style="text-decoration: underline;">1ER AÑO</strong> <strong>del Nivel de Educación Media</strong>, previo cumplimiento a los requisitos establecidos en la Normativa Legal vigente.
						</span>
						<br>
						<br>
						<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 
							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}


							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 90px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
		<br>
		<div align="right">
			<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: (Codigo que genera el Sistema)</strong>
		</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('ejem_certificado_final_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}

	// Genero PDF.3 Constancia de Buena Conducta

	public function generarConstanciaBuenaConducta($id_estudiante,$con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$row = $documentos->selectOne("estudiantes","id_estudiante",$id_estudiante,$con);
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);

		function obtenerEdad($fecha_nacimiento)
		{
		    $nacimiento = new DateTime($fecha_nacimiento);
		    $ahora = new DateTime(date("d-m-Y"));
		    $diferencia = $ahora->diff($nacimiento);
		    return $diferencia->format("%y");
		}

		$fecha_nacimiento_array = explode("-", $row['fecha_nacimiento_estudiante']);

		$fecha_nacimiento_reacomodada = "".$fecha_nacimiento_array[2]."-".$fecha_nacimiento_array[1]."-".$fecha_nacimiento_array[0]."";

		$edad_final = obtenerEdad($fecha_nacimiento_reacomodada);

		$row1 = $resultado_plantilla->fetch_assoc();

		$id_usuario = $_SESSION['id_usuario'];

		$nomE_con_buena = $row['nombre'];
		$apeE_con_buena = $row['apellido'];
		$ced_con_buena = $row['cedula'];
		$fecR_con_buena = $row['fecha_registro'];

		$codigo_constancia_conducta = hash('md5', "$nomE_con_buena $apeE_con_buena Constancia de Buena Conducta $ced_con_buena $fecR_con_buena");
		$comprobar_codigo_con_buena_exist = $con->query("SELECT codigo_documento FROM registro_documentos WHERE codigo_documento = '$codigo_constancia_conducta'  AND tipo_documento = 'Constancia de Buena Conducta' AND id_estudiantes = '$id_estudiante'");

		if ($comprobar_codigo_con_buena_exist->num_rows == 0) {
			$gurdar_registro_pdf_constan_buena = $con->query("INSERT INTO `registro_documentos`(`codigo_documento`, `tipo_documento`, `id_usuario`, `id_estudiantes`) VALUES ('$codigo_constancia_conducta','Constancia de Buena Conducta','$id_usuario','$id_estudiante')");
		}elseif ($comprobar_codigo_con_buena_exist->num_rows > 0) {
			$update_fecha_con_buena =  $con->query("UPDATE registro_documentos SET fecha_registro = CURRENT_TIMESTAMP WHERE codigo_documento = '$codigo_constancia_conducta'");
		}
		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Constancia de Buena Conducta</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="margin-top: 30px; text-align: center;" align="center">
								<strong style="font-size: 20px; text-decoration: underline;">CONSTANCIA DE CONDUCTA</strong>
							</div>
						</div>
						<span style="line-height: 1.5;">
								<span style="padding-left: 50px;">Quien</span> suscribe, <strong><!-- Nombre del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong>, titular de la Cédula de Identidad N° <strong><?php 

								 if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}

								  ?></strong> Director (E); por medio de la presente <strong>HACE CONSTAR</strong> que el (la) estudiante.</span>
								<br>
								<br>
								<div align="center" style="margin-bottom: 10px;">
								<strong style="text-decoration: underline; font-size: 16;"><?php echo "".$row['apellido']." ".$row['nombre'].""; ?></strong>
								</div>
								<span>De <strong><?php 

								if ($edad_final == "10") {
									echo "DIEZ ($edad_final)";
								}elseif ($edad_final == "11") {
									echo "ONCE ($edad_final)";
								}elseif ($edad_final == "12") {
									echo "DOCE ($edad_final)";
								}elseif ($edad_final == "13") {
									echo "TRECE ($edad_final)";
								}elseif ($edad_final == "14") {
									echo "CATORCE ($edad_final)";
								}

								 ?></strong> años de edad, Natural de <strong><?php 

								 $lugar_nacimiento_framentado = explode(",", $row['lugar_nacimiento']);
								 $lugar_nacimiento_reacomodado = "".$lugar_nacimiento_framentado[0]." - ".$lugar_nacimiento_framentado[1]."";
								 echo $lugar_nacimiento_reacomodado;

								  ?></strong> de Nacionalidad <strong><?php 

								 if ($row['nacionalidad'] == "venezolana") {
										
									echo 'VENEZOLANA';

								}elseif ($row['nacionalidad'] == "extranjera") {
									
									echo 'EXTRANJERA';
								
								}

								   ?></strong> durante el tiempo que cursó estudios en este plantel se observó <strong>BUENA CONDUCTA.</strong></span>
							<br>
							<br>
							<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 


							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}


							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 90px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px; padding-left: 60px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
			<br>
			<br>
			<br>
			<div align="right">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: <?php echo $codigo_constancia_conducta; ?></strong>
			</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('constancia_buena_conducta_'.$codigo_constancia_conducta.'_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}




	// EJEMPLO PDF.4 DE CONSTAnCIA DE BUENA CONDUCTA

	public function generarEjemConstanciaBuenaConducta($con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);

		$row1 = $resultado_plantilla->fetch_assoc();
		
		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Constancia de Buena Conducta</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="margin-top: 30px; text-align: center;" align="center">
								<strong style="font-size: 20px; text-decoration: underline;">CONSTANCIA DE CONDUCTA</strong>
							</div>
						</div>
						<span style="line-height: 1.5;">
								<span style="padding-left: 50px;">Quien</span> suscribe, <strong><!-- Nombre del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong>, titular de la Cédula de Identidad N° <strong><?php 

								 if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}

								  ?></strong> Director (E); por medio de la presente <strong>HACE CONSTAR</strong> que el (la) estudiante.</span>
								<br>
								<br>
								<div align="center" style="margin-bottom: 10px;">
								<strong style="text-decoration: underline; font-size: 16;">(Nombre y Apellido del Estudiante)</strong>
								</div>
								<span>De <strong>(Edad del Estudiante)</strong> años de edad, Natural de <strong>(Lugar de Nacimiento)</strong> de Nacionalidad <strong>(Nacionalidad del Estudiante)</strong> durante el tiempo que cursó estudios en este plantel se observó <strong>BUENA CONDUCTA.</strong></span>
							<br>
							<br>
							<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 


							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}


							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 90px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
			<br>
			<div align="right">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: (Codigo que Genera el Sistema)</strong>
			</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('ejem_constancia_buena_conducta_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}

	// Genero PDF.5 Constancia de Prosecucion

	public function generarConstanciaProsecucion($id_estudiante,$con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$row = $documentos->selectOne("estudiantes","id_estudiante",$id_estudiante,$con);
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);


		$row1 = $resultado_plantilla->fetch_assoc();

		$id_usuario = $_SESSION['id_usuario'];

		$nomE_con_prose = $row['nombre'];
		$apeE_con_prose = $row['apellido'];
		$ced_con_prose = $row['cedula'];
		$fecR_con_prose = $row['fecha_registro'];

		$codigo_constancia_proesecucion = hash('md5', "$nomE_con_prose $apeE_con_prose Constancia de Prosecución $ced_con_prose $fecR_con_prose");
		$comprobar_codigo_con_prose_exist = $con->query("SELECT codigo_documento FROM registro_documentos WHERE codigo_documento = '$codigo_constancia_proesecucion'  AND tipo_documento = 'Constancia de Prosecución' AND id_estudiantes = '$id_estudiante'");

		if ($comprobar_codigo_con_prose_exist->num_rows == 0) {
			$gurdar_registro_pdf_con_prose = $con->query("INSERT INTO `registro_documentos`(`codigo_documento`, `tipo_documento`, `id_usuario`, `id_estudiantes`) VALUES ('$codigo_constancia_proesecucion','Constancia de Prosecución','$id_usuario','$id_estudiante')");
		}elseif ($comprobar_codigo_con_prose_exist->num_rows > 0) {
			$update_fecha_con_prose =  $con->query("UPDATE registro_documentos SET fecha_registro = CURRENT_TIMESTAMP WHERE codigo_documento = '$codigo_constancia_proesecucion'");
		}
		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Certficacion Final</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="font-size: 16px; margin-top: 30px; text-align: center;" align="center">
								<strong>CONSTANCIA DE PROSECUCIÓN </strong>
								<br>
							<strong style="margin-top: -10px;">EN EL NIVEL DE EDUCACION PRIMARIA</strong>
								<br>
							</div>
						</div>
						<span style="line-height: 1.5;">
								Quien suscribe <strong style="text-decoration: underline;"><!-- Nombre y apellido del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong> titular de la Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del director --><?php 
								if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}?></strong> en su condición de Director(a) (E) de la <strong style="text-decoration: underline;"><!-- Nombre de la escuela -->Unidad Educativa Básica “Bustamante”</strong>, ubicado en  el municipio <strong style="text-decoration: underline;">San Cristobal</strong> de la parroquia <strong style="text-decoration: underline;">La Ermita</strong> adscrito  a la Zona Educativa del estado <strong style="text-decoration: underline;">Táchira</strong> certifica por medio de la presente que el (la) estudiante <strong style="text-decoration: underline;"> <!-- Nombre y Apellido del estudiante --> <?php echo $row['apellido']; ?> <?php echo $row['nombre'] ?></strong>, titular de Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del estudiante --><?php 

								if ($row['nacionalidad'] == "venezolana") {
									
									echo 'V-'.$row['cedula'].'';

								}elseif ($row['nacionalidad'] == "extranjera") {
									
									echo 'E-'.$row['cedula'].'';
								
								}

								 ?></strong>, nacido (a) en: <strong style="text-decoration: underline;"><!-- Lugar de Nacimiento --><?php echo $row['lugar_nacimiento']; ?></strong> en fecha: <strong style="text-decoration: underline;"><!-- Fecha de Nacimiento --><?php echo $row['fecha_nacimiento_estudiante']; ?></strong>,cursó el: <strong style="text-decoration: underline;">6to Grado</strong> correspondiéndole el literal: <strong style="text-decoration: underline;"><!-- Literal del estudiante -->“<?php echo $row['literal']; ?>”</strong> durante el período escolar <strong style="text-decoration: underline;"><!-- Perido Escolar --><?php echo $row['periodo_escolar']; ?></strong><strong>, siendo promovido (a) al</strong> <strong style="text-decoration: underline;">1ER AÑO</strong> <strong>del Nivel de Educación Media.</strong>
						</span>
						<br>
						<br>
						<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 

							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}

							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 90px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px; padding-left: 60px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
			<br>
			<br>
			<br>
			<div align="right">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: <?php echo $codigo_constancia_proesecucion; ?></strong>
			</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('constancia_prosecucion_'.$codigo_constancia_proesecucion.'_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}

	// EJEMPLO PDF.6 DE CONSTANCIA DE PROSECUCION

	public function generarEjemConstanciaProsecucion($con)
	{
		$documentos = new Documentos;
		$con = $documentos->conexion();
		$resultado_plantilla = $documentos->selectAll("plantilla_documentos",$con);
		$row1 = $resultado_plantilla->fetch_assoc();


		mysqli_close($con);

		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Certficacion Final</title>
		    <link rel="stylesheet" href="../v/css/pdf_certificado_final.css">
		</head>
		<body>
			<div style="width: 100%;">
				<div align="center" style="width: 80%;  margin: 0 auto;">
			<table align="center">
				<tr>
					<td class="main-documento">
						<div style="width: 100%; margin-bottom: 10px" align="center">
							<img style="margin-top: 45px" align="center" src="../v/img/escudo_de_venezuela.png">
							<div style="font-size: 16px; margin-top: 30px; text-align: center;" align="center">
								<strong>CONSTANCIA DE PROSECUCIÓN </strong>
								<br>
							<strong style="margin-top: -10px;">EN EL NIVEL DE EDUCACION PRIMARIA</strong>
								<br>
							</div>
						</div>
						<span style="line-height: 1.5;">
								Quien suscribe <strong style="text-decoration: underline;"><!-- Nombre y apellido del director --><?php 

								if ($row1['genero'] == "hombre") {

									echo "Lcdo. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}elseif ($row1['genero'] == "mujer") {

									echo "Lcda. ".$row1['nombre_director']." ".$row1['apellido_director']."";

								}

								 ?></strong> titular de la Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del director --><?php 
								if ($row1['nacionalidad'] == "venezolana") {
									echo 'V-'.$row1['cedula_director'].'';
								}elseif ($row1['nacionalidad'] == "extranjera") {
									echo 'E-'.$row1['cedula_director'].'';
								}?></strong> en su condición de Director(a) (E) de la <strong style="text-decoration: underline;"><!-- Nombre de la escuela -->Unidad Educativa Básica “Bustamante”</strong>, ubicado en  el municipio <strong style="text-decoration: underline;">San Cristobal</strong> de la parroquia <strong style="text-decoration: underline;">La Ermita</strong> adscrito  a la Zona Educativa del estado <strong style="text-decoration: underline;">Táchira</strong> certifica por medio de la presente que el (la) estudiante <strong style="text-decoration: underline;"> <!-- Nombre y Apellido del estudiante -->(Nombre y Apellido del Estudiante)</strong>, titular de Cédula de Identidad Nº <strong style="text-decoration: underline;"><!-- Cedula del estudiante -->(Cedula del Estudiante)</strong>, nacido (a) en: <strong style="text-decoration: underline;"><!-- Lugar de Nacimiento -->(Lugar de Nacimiento del Estudiante)</strong> en fecha: <strong style="text-decoration: underline;"><!-- Fecha de Nacimiento -->(Fecha de Nacimiento del Estudiante)</strong>,cursó el: <strong style="text-decoration: underline;">6to Grado</strong> correspondiéndole el literal: <strong style="text-decoration: underline;"><!-- Literal del estudiante -->“(Literal del Estudiante)”</strong> durante el período escolar <strong style="text-decoration: underline;"><!-- Perido Escolar -->(Perido Escolar)</strong><strong>, siendo promovido (a) al</strong> <strong style="text-decoration: underline;">1ER AÑO</strong> <strong>del Nivel de Educación Media.</strong>
						</span>
						<br>
						<br>
						<br>
						<span>
							Constancia que se expide en <strong style="text-decoration: underline;">San Cristobal</strong>, a los <strong style="text-decoration: underline;"><?php $dia = date("j"); echo $dia; ?></strong> días del mes de <strong style="text-decoration: underline;"><?php 

							$mes = date("m");
							switch ($mes) {
								case '1':
									echo "Enero";
									break;
								case '2':
									echo "Febrero";
									break;
								case '3':
									echo "Marzo";
									break;
								case '4':
									echo "Abril";
									break;
								case '5':
									echo "Mayo";
									break;
								case '6':
									echo "Junio";
									break;
								case '7':
									echo "Julio";
									break;
								case '8':
									echo "Agosto";
									break;
								case '9':
									echo "Septiembre";
									break;
								case '10':
									echo "Octubre";
									break;
								case '11':
									echo "Noviembre";
									break;
								case '12':
									echo "Diciembre";
									break;
							}

							 ?></strong> de <strong style="text-decoration: underline;"><?php $año = date("Y"); echo $año; ?></strong>.
						</span>
						<br>
						<br>
						<br>
					</td>

				</tr>
			</table>
				<table align="center" style="margin-left: 90px;">
					<tr>
						<td>
							<table style="border-collapse: collapse; border: 1px solid; width: 200px;">
								<tr style="border-collapse: collapse;border: 1px solid;" align="center">
									<td style="padding-bottom: 2px;">
										<div>
											<strong style="font-size: 9px;">Autoridad Educativa</strong>	
										</div>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px; border: 1px solid;padding-bottom: 2px;">
									Director (a):  <?php echo $row1['nombre_director']; ?> <?php echo $row1['apellido_director']; ?>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="font-size: 9px; text-align: justify; padding-left: 5px;padding-bottom: 2px;">
										<span style="font-size: 9px;">Número de C.I:<?php 
										if ($row1['nacionalidad'] == "venezolana") {
											echo 'V-'.$row1['cedula_director'].'';
										}elseif ($row1['nacionalidad'] == "extranjera") {
											echo 'E-'.$row1['cedula_director'].'';
										}?></span>
									</td>
								</tr>
								<tr style="border: 1px solid;">
									<td style="height: 60px; text-align: left; border: 1px solid;">
										<div style="padding-bottom: 45px;">
											<span style="padding-left: 5px;margin-bottom: 20px; font-size: 13px;">Firma: </span>
										</div>
									</td>
								</tr>
							</table>
						</td>
					<td>
						<div style="padding-top: 1px;">
							<table style="border: 1px solid; margin-left: 20px; margin-bottom:9px; padding-left: 30px; padding-right: 30px;">
							<tr style="padding-top: 30px;">
								<td height="80">
									SELLO DEL PLANTEL
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<br>
			<br>
			<div align="left">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: justify;">Certificado válido a nivel nacional e internacional.</strong>
			</div>
			<br>
			<div align="right">
				<strong style="font-size: 10px; font-family: 'Arial'; text-align: right;">Codigo del Documento: (Codigo generado por el Sistema)</strong>
			</div>
		</div>
		</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('ejem_constancia_prosecucion_'.$dia.'_'.$mes.'_'.$año.'.pdf');
	}

	// Lista de usuarios PDF.7

	public function generarListaUsers($con)
	{
		$nick_usuario = $_SESSION['nick_usuario'];
		$id_usuario_s = $_SESSION['id_usuario'];

		if ($_SESSION['tipo_usuario'] == 0) {

		 	$enfacis = "#160053";
		 	$enfacis_opacity = "rgba(22,0,88, .6)";

		}elseif ($_SESSION['tipo_usuario'] == 1) {

			$enfacis = "#002240";
		 	$enfacis_opacity = "rgba(0,34,64, .6)";

		}

		$documentos = new Documentos;
		$con = $documentos->conexion();
		$list_users = $documentos->selectSQL("SELECT *, DATE_FORMAT(fecha_registro, '%r') AS hora, LOCALTIMESTAMP FROM usuarios",$con);
		$query_fecha_hora = $documentos->selectSQL("SELECT DATE_FORMAT(LOCALTIMESTAMP, '%d-%m-%Y %r') AS hora FROM usuarios LIMIT 1",$con);

		$fecha_hora = mysqli_fetch_assoc($query_fecha_hora);

		$fecha_hora_explode = explode(" ", $fecha_hora['hora']);

		$codigo_list_user = hash("md5", "".$fecha_hora['hora']." $nick_usuario Listado de Usuarios PDF");

		$query_cod_list_user = $documentos->selectSQL("SELECT id_usuario FROM pdfs_usuarios WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Listado de Usuarios PDF'",$con);

		if ($query_cod_list_user->num_rows == 0) {
			$gurdar_pdf_list_user = $con->query("INSERT INTO `pdfs_usuarios`(`id_usuario`, `tipo_pdf`, `codigo_pdf`, `fecha_registro`) VALUES ('$id_usuario_s','Listado de Usuarios PDF','$codigo_list_user',CURRENT_TIMESTAMP)");
		}elseif ($query_cod_list_user->num_rows > 0) {
			$update_fecha_list_user =  $con->query("UPDATE pdfs_usuarios SET codigo_pdf = '$codigo_list_user', fecha_registro = CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Listado de Usuarios PDF'");
		}

		mysqli_close($con);
		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Lista de Usuario <?php if($_SESSION['tipo_usuario'] == 0){echo "(ADMIN)";}elseif($_SESSION['tipo_usuario'] == 1){echo "(DIRECTOR@)";} ?></title>
		    <link rel="stylesheet" href="../v/css/pdf_user_list.css">
		</head>
			<body>
				<div style="width: 100%;">
					<div align="center" style="width: 90%;  margin: 0 auto;">
						<div style="width: 100%;" align="center">
							<div class="b-r" style="background: <?php echo $enfacis; ?>; padding: 10px; color: white; text-align: center;" align="center">
							<b>Lista de Usuarios del Sistema de Certificaciones Finales</b>
							<strong style="font-size: 20px; text-decoration: underline;">"Bustamante"</strong>
							</div>
						</div>
						<div class="b-r" align="left" style="margin-top: 20px; width: 60%; background: <?php echo $enfacis_opacity; ?>; color: #fff; margin-bottom: 20px;">
							<br>
							<div class="text-description" style="margin-top: 10px;">
								<span style="width: 70%; background: <?php echo $enfacis; ?>; padding: 15px; color: #fff;">Datos del Documento</span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Creado por: <?php echo $nick_usuario; ?> (<?php 

									if ($_SESSION['tipo_usuario'] == 0) {
										echo "Admin";
									}elseif ($_SESSION['tipo_usuario'] == 1) {
										echo "Director@";
									}

								 ?>)</span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Fecha de Creación: <?php echo $fecha_hora['hora']; ?></span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Codigo del Documento: <?php echo $codigo_list_user; ?></span>
							</div>
							<br>
						</div>
						<table>
							<thead style="background: <?php echo $enfacis; ?>">
								<tr>
									<?php if ($_SESSION['tipo_usuario'] == 0): ?>
									<th>ID</th>
									<?php endif ?>
									<th>Nick User</th>
									<th>Nombre</th>
									<th>Apellido</th>
									<th>Tipo de Usuario (Rol)</th>
									<th>Fecha de Registro</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								while($row = mysqli_fetch_assoc($list_users))
								{
								 ?>
								<tr>
									<?php if ($_SESSION['tipo_usuario'] == 0): ?>
										
									<td><?php echo $row['id_usuario']; ?></td>
									<?php endif ?>
									<td><?php echo $row['nick_usuario']; ?></td>
									<td><?php echo $row['nombre_usuario']; ?></td>
									<td><?php echo $row['apellido_usuario']; ?></td>
									<td><?php 

									if ($row['tipo_usuario'] == 0) {
										echo "<span style='color: #160053;'>Admin</span>";
									}elseif ($row['tipo_usuario'] == 1) {
										echo "<span style='color: #002240;'>Director@</span>";
									}elseif ($row['tipo_usuario'] == 2) {
										echo "<span style='color: #0087FF;'>Secretari@</span>";
									}

									 ?></td>
									<td><?php 

									$fecha_array = explode(" ", $row['fecha_registro']);
									$fecha_año = explode("-", $fecha_array[0]);

									$fecha_año_array = "$fecha_año[2]-$fecha_año[1]-$fecha_año[0]";

									echo "".$fecha_año_array." ".$row['hora']."";

									 ?></td>
									<td><?php 

									if ($row['status_usuario'] == 0) {
										echo '<span style="color: red">"ELIMINADO"</span>';
									}elseif($row['status_usuario'] == 1){
										echo '<span style="color: green">"ACTIVO"</span>';
									}

									 ?></td>
								</tr>
								<?php 
								}
								 ?>
							</tbody>
						</table>
					</div>
				</div>
			</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('lista_usuarios_admin_'.$fecha_hora_explode[0].'.pdf');
	}

	// PDF.8 Acciones de un Usuario

	public function pdfAccionesUser($id_usuario,$con)
	{
		$nick_usuario_s = $_SESSION['nick_usuario'];
		$id_usuario_s = $_SESSION['id_usuario'];

		if ($_SESSION['tipo_usuario'] == 0) {

		 	$enfacis_d_a_u = "#160053";
		 	$enfacis_d_a_u_opacity = "rgba(22,0,88, .6)";

		}elseif ($_SESSION['tipo_usuario'] == 1) {

			$enfacis_d_a_u = "#002240";
		 	$enfacis_d_a_u_opacity = "rgba(0,34,64, .6)";

		}

		$documentos = new Documentos;
		$con = $documentos->conexion();
		$consulta_inicio_sesion_o = $documentos->selectSQL("SELECT DATE_FORMAT(sesion, '%d-%m-%Y %r') AS fecha FROM inicio_sesiones WHERE id_usuario = '$id_usuario'",$con);

		$consulta_nick_user_o = $documentos->selectSQL("SELECT nick_usuario FROM usuarios WHERE id_usuario = '$id_usuario'",$con);

		$consulta_acciones_user_o = $documentos->selectSQL("SELECT acciones_usuarios.accion, DATE_FORMAT(acciones_usuarios.fecha_accion, '%d-%m-%Y %r') as fecha FROM acciones_usuarios INNER JOIN usuarios ON usuarios.id_usuario = acciones_usuarios.id_usuario WHERE acciones_usuarios.id_usuario = '$id_usuario' ORDER BY acciones_usuarios.fecha_accion DESC",$con);

		$query_fecha_horas = $documentos->selectSQL("SELECT DATE_FORMAT(LOCALTIMESTAMP, '%d-%m-%Y %r') AS hora FROM usuarios LIMIT 1",$con);

		$result_inicio_sesion_o = mysqli_fetch_assoc($consulta_inicio_sesion_o);

		$result_fecha_horas = mysqli_fetch_assoc($query_fecha_horas);

		$nick_user_query = mysqli_fetch_assoc($consulta_nick_user_o);

		$fecha_hora_a_explode = explode(" ", $result_fecha_horas['hora']);

		$codigo_acciones_user = hash("md5", "".$result_fecha_horas['hora']." $nick_usuario_s ".$nick_user_query['nick_usuario']." Acciones del Usuario PDF");

		$query_cod_acciones_user = $documentos->selectSQL("SELECT id_usuario FROM pdfs_usuarios WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Acciones del Usuario PDF'",$con);

		if ($query_cod_acciones_user->num_rows == 0) {
			$gurdar_pdf_list_user = $con->query("INSERT INTO `pdfs_usuarios`(`id_usuario`, `tipo_pdf`, `codigo_pdf`, `fecha_registro`) VALUES ('$id_usuario_s','Acciones del Usuario PDF','$codigo_acciones_user',CURRENT_TIMESTAMP)");
		}elseif ($query_cod_acciones_user->num_rows > 0) {
			$update_fecha_list_user =  $con->query("UPDATE pdfs_usuarios SET codigo_pdf = '$codigo_acciones_user', fecha_registro = CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Acciones del Usuario PDF'");
		}

		mysqli_close($con);


		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Acciones del Usuario <?php echo $nick_user_query['nick_usuario']; ?> - <?php if($_SESSION['tipo_usuario'] == 0){ echo "(ADMIN)"; }elseif($_SESSION['tipo_usuario'] == 1){echo "(DIRECTOR@)";} ?></title>
		    <link rel="stylesheet" href="../v/css/pdf_detalles_acciones_user.css">
		</head>
			<body>
				<div style="width: 100%;">
					<div align="center" style="width: 90%;  margin: 0 auto;">
						<div style="width: 100%;" align="center">
							<div class="b-r" style="background: <?php echo $enfacis_d_a_u; ?>; padding: 10px; color: white; text-align: center;" align="center">
							<b>Acciones del Usuario <span style="color: #30FF00;">"<?php echo $nick_user_query['nick_usuario']; ?>"</span> en el Sistema de Certificaciones Finales</b>
							<strong style="font-size: 20px; text-decoration: underline;">"Bustamante"</strong>
							</div>
						</div>
						<div class="b-r" align="left" style="margin-top: 20px; width: 50%; background: <?php echo $enfacis_d_a_u_opacity; ?>; color: #fff; margin-bottom: 5px;">
							<br>
							<div class="text-description" style="margin-top: 10px;">
								<span style="width: 70%; background: <?php echo $enfacis_d_a_u; ?>; padding: 15px; color: #fff;">Datos del Documento</span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Creado por: <?php echo $nick_usuario_s; ?> <?php if($_SESSION['tipo_usuario'] == 0){echo "(Admin)";}elseif($_SESSION['tipo_usuario']){echo "(Director@)";} ?></span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Fecha de Creación: <?php echo $result_fecha_horas['hora']; ?></span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Codigo del Documento: <?php echo $codigo_acciones_user; ?></span>
							</div>
							<br>
						</div>
						<div class="hrr">
							<hr>
						</div>
						<div align="center" style="padding:5px;">
							<span style="padding: 10px; font-size: 30px; border-radius: 3px; color: <?php echo $enfacis_d_a_u; ?>;">Acciones del Usuario:</span>
						</div>
						<?php 


						if ($consulta_acciones_user_o->num_rows > 0) {
							?>
							<table align="center">
								<thead style="background: <?php echo $enfacis_d_a_u; ?>">
									<tr>
										<th>Descripción</th>
										<th>Fecha</th>
									</tr>
								</thead>
								<tbody>
									<?php 
									while ($row_a_u = mysqli_fetch_assoc($consulta_acciones_user_o))
									{
									 ?><tr><?php
										
								$accion_explode = explode(":", $row_a_u['accion']);
								$longitud_accion_explode = count($accion_explode);
								?><td><?php echo $accion_explode[0]; ?><span style="color: #ffc107;"><?php echo $accion_explode[1]; ?></span><?php echo $accion_explode[2]; 
								if ($accion_explode[3] == 0) {
									echo '<span style="color: #007bff;">Admin</span>';
								}elseif ($accion_explode[3] == 1) {
									echo '<span style="color: #007bff;">Director</span>';
								}elseif ($accion_explode[3] == 2) {
									echo '<span style="color: #007bff;">Secretari@</span>';
								}
								echo $accion_explode[4];
								if ($longitud_accion_explode > 7)
								{
									if ($accion_explode[5] == "ELIMINO")
									{
										echo '<span style="color: #dc3545;">"ELIMINO"</span>';
									}elseif ($accion_explode[5] == "ACTIVO") {
										echo '<span style="color: #28a745;">"ACTIVO"</span>';
									}elseif ($accion_explode[5] == "ACTUALIZO") {
										echo '<span style="color: #28a745;">"ACTUALIZO"</span>';
									}elseif ($accion_explode[5] == "REGISTRO") {
										echo '<span style="color: #007bff;">"REGISTRO"</span>';
									}
									echo $accion_explode[6];
									echo '<span style="color: #17a2b8;">'.$accion_explode[7].'</span>';
								}
								?></td>
										<td><?php echo $row_a_u['fecha']; ?></td>
									</tr>	
								<?php } ?>							
								</tbody>
							</table>
							<?php
						}else{
							echo "El Usuario ".$nick_user_query['nick_usuario']." no ha hecho ninguna acción hasta el momento.";
						}

						 ?>
						<div class="hrr">
							<hr>
						</div>
						<div align="center" style="padding:5px;">
							<span style="padding: 10px; font-size: 30px; border-radius: 3px; color: <?php echo $enfacis_d_a_u; ?>;">Ultimo Inicio de Sesión:</span>
						</div>
						<div>
							<?php 

							if ($consulta_inicio_sesion_o->num_rows>0) {
								?>
							<span><?php echo $result_inicio_sesion_o['fecha']; ?></span>
							<?php
							}else{
								echo "El Usuario ".$nick_user_query['nick_usuario']." nunca ha iniciado sesión hasta la fecha.";
							}

							 ?>
						</div>
					</div>
				</div>
			</body>
		</html>
		<?php
		$html = ob_get_clean();

		// instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// Render the HTML as PDF
		$dompdf->render();
		// Output the generated PDF to Browser
		$dompdf->stream('acciones_user_'.$fecha_hora_a_explode[0].'.pdf');
	}


	// PDF.9 Busqueda por Cedula
	public function pdfBusquedaXCedula($con)

	{
		$nick_usuario_s = $_SESSION['nick_usuario'];
		$id_usuario_s = $_SESSION['id_usuario'];

		if ($_SESSION['tipo_usuario'] == 0) {

		 	$enfacis_d_a_u = "#160053";
		 	$enfacis_d_a_u_opacity = "rgba(22,0,88, .6)";

		}elseif ($_SESSION['tipo_usuario'] == 1) {

			$enfacis_d_a_u = "#002240";
		 	$enfacis_d_a_u_opacity = "rgba(0,34,64, .6)";

		}

		$consultaTablaBusquedaXCedula = $con -> query("SELECT num_ced, DATE_FORMAT(busqueda_x_cedula_md_busqueda.fecha, '%d-%m-%Y %r') AS fecha_consulta, nick_usuario FROM busqueda_x_cedula_md_busqueda INNER JOIN usuarios ON busqueda_x_cedula_md_busqueda.id_usuario = usuarios.id_usuario ORDER BY busqueda_x_cedula_md_busqueda.fecha DESC ");

		$consultaFechaActual = $con -> query ("SELECT DATE_FORMAT(LOCALTIMESTAMP, '%d-%m-%Y %r') AS fechaActual FROM usuarios LIMIT 1");

		$fechaActual = $consultaFechaActual->fetch_assoc();

		$datosCodigoDeDocumento = $fechaActual['fechaActual']." ".$nick_usuario_s." BusquedaXCedula";

		$codigoBusquedaXCedula = hash("md5", $datosCodigoDeDocumento);

		$consultaCodigoDocumentosBXCExiste = $con-> query ("SELECT id_usuario FROM pdfs_usuarios WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Cedula PDF'");

		if ($consultaCodigoDocumentosBXCExiste->num_rows == 0) {
			$guardar_PDF_BXC = $con->query("INSERT INTO `pdfs_usuarios`(`id_usuario`, `tipo_pdf`, `codigo_pdf`, `fecha_registro`) VALUES ('$id_usuario_s','Busqueda por Cedula PDF','$codigoBusquedaXCedula',CURRENT_TIMESTAMP)");
		}elseif ($consultaCodigoDocumentosBXCExiste->num_rows > 0) {
			$update_PDF_BXC =  $con->query("UPDATE pdfs_usuarios SET codigo_pdf = '$codigoBusquedaXCedula', fecha_registro = CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Cedula PDF'");
		}

		mysqli_close($con);


		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Busqueda por Cedula PDF</title>
		    <link rel="stylesheet" href="../v/css/pdf_detalles_acciones_user.css">
		</head>
			<body>
				<div style="width: 100%;">
					<div align="center" style="width: 90%;  margin: 0 auto;">
						<div style="width: 100%;" align="center">
							<div class="b-r" style="background: <?php echo $enfacis_d_a_u; ?>; padding: 10px; color: white; text-align: center;" align="center">
							<b>Busqueda por Cedula realizadas en el Sistema de Certificaciones Finales</b>
							<strong style="font-size: 20px; text-decoration: underline;">"Bustamante"</strong>
							</div>
						</div>
						<div class="b-r" align="left" style="margin-top: 20px; width: 50%; background: <?php echo $enfacis_d_a_u_opacity; ?>; color: #fff; margin-bottom: 5px;">
							<br>
							<div class="text-description" style="margin-top: 10px;">
								<span style="width: 70%; background: <?php echo $enfacis_d_a_u; ?>; padding: 15px; color: #fff;">Datos del Documento</span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Creado por: 
								<?php 

								echo $nick_usuario_s;

								if($_SESSION['tipo_usuario'] == 0){
									echo "(Admin)";
								}elseif($_SESSION['tipo_usuario']){
									echo "(Director@)";
								};

								 ?>
								 </span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<?php 

								echo "<span>Fecha de Creación: ".$fechaActual['fechaActual']."</span>";

								 ?>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<?php 

								echo "<span>Codigo del Documento: ".$codigoBusquedaXCedula."</span>";

								?>
							</div>
							<br>
						</div>
						<div class="hrr">
							<hr>
						</div>
						<table align="center">
							<thead style="background: <?php echo $enfacis_d_a_u; ?>">
								<tr>
									<th>Usuario</th>
									<th>N° de Cedula Consultada</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								while ($row_consulta = mysqli_fetch_assoc($consultaTablaBusquedaXCedula)) {

									echo '
										<tr>
											<td>'.$row_consulta["nick_usuario"].'</td>
											<td>'.$row_consulta["num_ced"].'</td>
											<td>'.$row_consulta["fecha_consulta"].'</td>
										</tr>				
									';

								}

								 ?>
							</tbody>
						</table>
					</div>
				</div>
			</body>
		</html>
		<?php

		$solo_dmY = explode(" ", $fechaActual['fechaActual']);

		$html = ob_get_clean();

		// // instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// // (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// // Render the HTML as PDF
		$dompdf->render();
		// // Output the generated PDF to Browser
		$dompdf->stream('busqueda_x_cedula_pdf_'.$solo_dmY[0]);


	}


	// PDF.10 Busqueda por Fechas
	public function pdfBusquedaXFechas($con)
	{

		$nick_usuario_s = $_SESSION['nick_usuario'];
		$id_usuario_s = $_SESSION['id_usuario'];

		if ($_SESSION['tipo_usuario'] == 0) {

		 	$enfacis_d_a_u = "#160053";
		 	$enfacis_d_a_u_opacity = "rgba(22,0,88, .6)";

		}elseif ($_SESSION['tipo_usuario'] == 1) {

			$enfacis_d_a_u = "#002240";
		 	$enfacis_d_a_u_opacity = "rgba(0,34,64, .6)";

		}

		$consultaTablaBusquedaXFecha = $con -> query("SELECT nick_usuario, DATE_FORMAT(fecha_i, '%d-%m-%Y') AS fecha_i, DATE_FORMAT(fecha_f, '%d-%m-%Y') AS fecha_f, DATE_FORMAT(busqueda_x_fecha_md_busqueda.fecha, '%d-%m-%Y %r') AS fecha_consulta FROM busqueda_x_fecha_md_busqueda INNER JOIN usuarios ON busqueda_x_fecha_md_busqueda.id_usuario = usuarios.id_usuario ORDER BY busqueda_x_fecha_md_busqueda.fecha DESC");

		$consultaFechaActual = $con -> query ("SELECT DATE_FORMAT(LOCALTIMESTAMP, '%d-%m-%Y %r') AS fechaActual FROM usuarios LIMIT 1");

		$fechaActual = $consultaFechaActual->fetch_assoc();

		$datosCodigoDeDocumento = $fechaActual['fechaActual']." ".$nick_usuario_s." BusquedaXFechas";

		$codigoBusquedaXFecha = hash("md5", $datosCodigoDeDocumento);

		$consultaCodigoDocumentosBXFExiste = $con-> query ("SELECT id_usuario FROM pdfs_usuarios WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Fecha PDF'");

		if ($consultaCodigoDocumentosBXFExiste->num_rows == 0) {
			$guardar_PDF_BXC = $con->query("INSERT INTO `pdfs_usuarios`(`id_usuario`, `tipo_pdf`, `codigo_pdf`, `fecha_registro`) VALUES ('$id_usuario_s','Busqueda por Fecha PDF','$codigoBusquedaXFecha',CURRENT_TIMESTAMP)");
		}elseif ($consultaCodigoDocumentosBXFExiste->num_rows > 0) {
			$update_PDF_BXC =  $con->query("UPDATE pdfs_usuarios SET codigo_pdf = '$codigoBusquedaXFecha', fecha_registro = CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Fecha PDF'");
		}

		mysqli_close($con);


		ob_start();
		?>
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Busqueda por Fechas PDF</title>
		    <link rel="stylesheet" href="../v/css/pdf_detalles_acciones_user.css">
		</head>
			<body>
				<div style="width: 100%;">
					<div align="center" style="width: 90%;  margin: 0 auto;">
						<div style="width: 100%;" align="center">
							<div class="b-r" style="background: <?php echo $enfacis_d_a_u; ?>; padding: 10px; color: white; text-align: center;" align="center">
							<b>Busqueda por Fechas realizadas en el Sistema de Certificaciones Finales</b>
							<strong style="font-size: 20px; text-decoration: underline;">"Bustamante"</strong>
							</div>
						</div>
						<div class="b-r" align="left" style="margin-top: 20px; width: 50%; background: <?php echo $enfacis_d_a_u_opacity; ?>; color: #fff; margin-bottom: 5px;">
							<br>
							<div class="text-description" style="margin-top: 10px;">
								<span style="width: 70%; background: <?php echo $enfacis_d_a_u; ?>; padding: 15px; color: #fff;">Datos del Documento</span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<span>Creado por: 
								<?php 

								echo $nick_usuario_s;

								if($_SESSION['tipo_usuario'] == 0){
									echo "(Admin)";
								}elseif($_SESSION['tipo_usuario']){
									echo "(Director@)";
								};

								 ?>
								 </span>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<?php 

								echo "<span>Fecha de Creación: ".$fechaActual['fechaActual']."</span>";

								 ?>
							</div>
							<div class="text-description-sub" style="font-size: 13px;">
								<?php 

								echo "<span>Codigo del Documento: ".$codigoBusquedaXFecha."</span>";

								?>
							</div>
							<br>
						</div>
						<div class="hrr">
							<hr>
						</div>
						<table align="center">
							<thead style="background: <?php echo $enfacis_d_a_u; ?>">
								<tr>
									<th>Usuario</th>
									<th>Fecha Inicial</th>
									<th>Fecha Final</th>
									<th>Fecha de Consulta</th>
								</tr>
							</thead>
							<tbody>
								<?php 

								while ($row_consulta = mysqli_fetch_assoc($consultaTablaBusquedaXFecha)) {

									echo '
										<tr>
											<td>'.$row_consulta["nick_usuario"].'</td>
											<td>'.$row_consulta["fecha_i"].'</td>
											<td>'.$row_consulta["fecha_f"].'</td>
											<td>'.$row_consulta["fecha_consulta"].'</td>
										</tr>				
									';

								}

								 ?>
							</tbody>
						</table>
					</div>
				</div>
			</body>
		</html>
		<?php

		$solo_dmY = explode(" ", $fechaActual['fechaActual']);

		$html = ob_get_clean();

		// // instantiate and use the dompdf class
		$dompdf = new Dompdf\Dompdf();

		$dompdf->loadHtml($html);
		// // (Optional) Setup the paper size and orientation
		$dompdf->setPaper('letter', 'portrait');
		// // Render the HTML as PDF
		$dompdf->render();
		// // Output the generated PDF to Browser
		$dompdf->stream('busqueda_x_fechas_pdf_'.$solo_dmY[0]);
	}

}

 ?>