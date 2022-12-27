<?php 


// require_once '../v/pdf/vendor/autoload.php';

// $nick_usuario_s = $_SESSION['nick_usuario'];
// $id_usuario_s = $_SESSION['id_usuario'];

// if ($_SESSION['tipo_usuario'] == 0) {

//  	$enfacis_d_a_u = "#160053";
//  	$enfacis_d_a_u_opacity = "rgba(22,0,88, .6)";

// }elseif ($_SESSION['tipo_usuario'] == 1) {

// 	$enfacis_d_a_u = "#002240";
//  	$enfacis_d_a_u_opacity = "rgba(0,34,64, .6)";

// }

// $consultaTablaBusquedaXCedula = $con -> query("SELECT num_ced, DATE_FORMAT(busqueda_x_cedula_md_busqueda.fecha, '%d-%m-%Y %r') AS fecha_consulta, nick_usuario FROM busqueda_x_cedula_md_busqueda INNER JOIN usuarios ON busqueda_x_cedula_md_busqueda.id_usuario = usuarios.id_usuario ORDER BY busqueda_x_cedula_md_busqueda.fecha DESC ");

// $consultaFechaActual = $con -> query ("SELECT DATE_FORMAT(LOCALTIMESTAMP, '%d-%m-%Y %r') AS fechaActual FROM usuarios LIMIT 1");

// $fechaActual = $consultaFechaActual->fetch_assoc();

// $datosCodigoDeDocumento = $fechaActual['fechaActual']." ".$nick_usuario_s." BusquedaXCedula";

// $codigoBusquedaXCedula = hash("md5", $datosCodigoDeDocumento);

// $consultaCodigoDocumentosBXCExiste = $con-> query ("SELECT id_usuario FROM pdfs_usuarios WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Cedula PDF'");

// if ($consultaCodigoDocumentosBXCExiste->num_rows == 0) {
// 	$guardar_PDF_BXC = $con->query("INSERT INTO `pdfs_usuarios`(`id_usuario`, `tipo_pdf`, `codigo_pdf`, `fecha_registro`) VALUES ('$id_usuario_s','Busqueda por Cedula PDF','$codigoBusquedaXCedula',CURRENT_TIMESTAMP)");
// }elseif ($consultaCodigoDocumentosBXCExiste->num_rows > 0) {
// 	$update_PDF_BXC =  $con->query("UPDATE pdfs_usuarios SET codigo_pdf = '$codigoBusquedaXCedula', fecha_registro = CURRENT_TIMESTAMP WHERE id_usuario = '$id_usuario_s' AND tipo_pdf = 'Busqueda por Cedula PDF'");
// }

// mysqli_close($con);


// ob_start();
// ?>
// <!DOCTYPE html>
// <html lang="en">
// <head>
// 	<meta charset="UTF-8">
// 	<title>Busqueda por Cedula PDF</title>
//     <link rel="stylesheet" href="../v/css/pdf_detalles_acciones_user.css">
// </head>
// 	<body>
// 		<div style="width: 100%;">
// 			<div align="center" style="width: 90%;  margin: 0 auto;">
// 				<div style="width: 100%;" align="center">
// 					<div class="b-r" style="background: <?php echo $enfacis_d_a_u; ?>; padding: 10px; color: white; text-align: center;" align="center">
// 					<b>Busqueda por Cedula realizadas en el Sistema de Certificaciones Finales</b>
// 					<strong style="font-size: 20px; text-decoration: underline;">"Bustamante"</strong>
// 					</div>
// 				</div>
// 				<div class="b-r" align="left" style="margin-top: 20px; width: 50%; background: <?php echo $enfacis_d_a_u_opacity; ?>; color: #fff; margin-bottom: 5px;">
// 					<br>
// 					<div class="text-description" style="margin-top: 10px;">
// 						<span style="width: 70%; background: <?php echo $enfacis_d_a_u; ?>; padding: 15px; color: #fff;">Datos del Documento</span>
// 					</div>
// 					<div class="text-description-sub" style="font-size: 13px;">
// 						<?php 

// 						echo "<span>Creado por: ".$nick_usuario_s;if($_SESSION['tipo_usuario'] == 0){echo "(Admin)";}elseif($_SESSION['tipo_usuario']){echo "(Director@)";}."</span>";

// 						 ?>
// 					</div>
// 					<div class="text-description-sub" style="font-size: 13px;">
// 						<?php 

// 						echo "<span>Fecha de Creación: ".$fechaActual['fechaActual']."</span>";

// 						 ?>
// 					</div>
// 					<div class="text-description-sub" style="font-size: 13px;">
// 						<?php 

// 						echo "<span>Codigo del Documento: ".$codigoBusquedaXCedula."</span>";

// 						?>
// 					</div>
// 					<br>
// 				</div>
// 				<div class="hrr">
// 					<hr>
// 				</div>
// 				<table align="center">
// 					<thead style="background: <?php echo $enfacis_d_a_u; ?>">
// 						<tr>
// 							<th>Usuario</th>
// 							<th>N° de Cedula Consultada</th>
// 							<th>Fecha</th>
// 						</tr>
// 					</thead>
// 					<tbody>
// 						<?php 

// 						while ($row_consulta = mysqli_fetch_assoc($consultaTablaBusquedaXCedula)) {

// 							echo '
// 								<tr>
// 									<td>'.$row_consulta["nick_usuario"].'</td>
// 									<td>'.$row_consulta["num_ced"].'</td>
// 									<td>'.$row_consulta["fecha_consulta"].'</td>
// 								</tr>				
// 							';

// 						}

// 						 ?>
// 					</tbody>
// 				</table>
// 			</div>
// 		</div>
// 	</body>
// </html>
// <?php

// $solo_dmY = explode(" ", $fechaActual['fechaActual']);

// $html = ob_get_clean();

// // // instantiate and use the dompdf class
// $dompdf = new Dompdf\Dompdf();

// $dompdf->loadHtml($html);
// // // (Optional) Setup the paper size and orientation
// $dompdf->setPaper('letter', 'portrait');
// // // Render the HTML as PDF
// $dompdf->render();
// // // Output the generated PDF to Browser
// $dompdf->stream('busqueda_x_cedula_pdf_'.$solo_dmY[0]);



 ?>