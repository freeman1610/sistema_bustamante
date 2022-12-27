<?php 
require("items.class.php");
class Interfaz extends Items
{
	// Tema

	public function tema($valor,$con)
	{
		$id_usuario = $_SESSION['id_usuario'];
		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$tema = $interfaz->selectSQL("SELECT tema FROM usuarios WHERE id_usuario = $id_usuario",$con);

		$row = mysqli_fetch_assoc($tema);

		if ($row['tema'] == 0) {
			$sql = $con->query("UPDATE usuarios SET tema = '$valor' WHERE id_usuario = $id_usuario");
			$interfaz = new Interfaz;
			$con = $interfaz->conexion();
			$revisar_modo_default = $interfaz->selectSQL("SELECT tema FROM usuarios WHERE id_usuario = $id_usuario",$con);
			$revisado = mysqli_fetch_assoc($revisar_modo_default);

			if ($revisado['tema'] == 0) {
				$_SESSION['tema'] = $revisado['tema'];
				return ["code" => 0];
			}elseif ($revisado['tema'] == 1) {
				$_SESSION['tema'] = $revisado['tema'];
				return ["code" => 1];
			}

		}elseif ($row['tema'] == 1) {
			$sql = $con->query("UPDATE usuarios SET tema = '$valor' WHERE id_usuario = $id_usuario");

			$interfaz = new Interfaz;
			$con = $interfaz->conexion();
			$revisar_modo_default = $interfaz->selectSQL("SELECT tema FROM usuarios WHERE id_usuario = $id_usuario",$con);
			$revisado = mysqli_fetch_assoc($revisar_modo_default);

			if ($revisado['tema'] == 1) {
				$_SESSION['tema'] = $revisado['tema'];
				return ["code" => 1];

			}elseif ($revisado['tema'] == 0) {
				$_SESSION['tema'] = $revisado['tema'];
				return ["code" => 0];
			}
		}
		mysqli_close($con);
	}


	// Boton de Inicio
	public function inicio() 
	{
		$nick_usuario = $_SESSION['nick_usuario'];
		$hora = $_SESSION['hora'];
		$fecha_dia_actual = $_SESSION['fecha_dia_actual'];
		$fecha_dia = $_SESSION['fecha_dia'];
		$fecha_mes_actual = $_SESSION['fecha_mes_actual'];
		$fecha_year = $_SESSION['fecha_year'];
		?>
		<div class="main inicio-home">
			<div>
				<div class="h1 mb-4 main-letra" align="center">¡Bienvenido <?php echo $nick_usuario; ?>!</div>
				<h5>
					<small class="text-muted main-letra">Inicio de la ultima sesión, el <?php echo $fecha_dia_actual; ?> <?php echo $fecha_dia; ?> de <?php echo $fecha_mes_actual; ?> del <?php echo $fecha_year; ?> a las <?php echo $hora; ?></small>
				</h5>
			</div>			
		</div>
		<?php
		http_response_code(200);
	}

	// Boton para el Auditoria de Datos

	public function btnRegistroActvidades()
	{

		$tema = $_SESSION['tema'];

		if ($_SESSION['tipo_usuario'] == 0 || $_SESSION['tipo_usuario'] == 1) {
			// Traigo la tabla inicio_sesiones

			$interfaz = new Interfaz;
			$con = $interfaz->conexion();


			$consulta_inicio_sesion = $interfaz->selectSQL("SELECT usuarios.nick_usuario, usuarios.tipo_usuario, usuarios.id_usuario, DATE_FORMAT(inicio_sesiones.sesion, '%d-%m-%Y %r') as fecha_hora FROM inicio_sesiones INNER JOIN usuarios ON usuarios.id_usuario = inicio_sesiones.id_usuario ORDER BY `inicio_sesiones`.`sesion` DESC",$con);

			// Traigo la tabla busqueda_x_cedula_md_busqueda

			$consulta_busqueda_x_cedula_md_busqueda = $interfaz->selectSQL("SELECT nick_usuario, num_ced, DATE_FORMAT(busqueda_x_cedula_md_busqueda.fecha, '%d-%m-%Y %r') AS fechaa FROM busqueda_x_cedula_md_busqueda INNER JOIN usuarios ON busqueda_x_cedula_md_busqueda.id_usuario = usuarios.id_usuario ORDER BY fechaa DESC",$con);

			// Traigo la tabla busqueda_x_fecha_md_busqueda

			$consulta_busqueda_x_fecha_md_busqueda = $interfaz->selectSQL("SELECT nick_usuario, DATE_FORMAT(busqueda_x_fecha_md_busqueda.fecha_i, '%d-%m-%Y') AS fechaI, DATE_FORMAT(busqueda_x_fecha_md_busqueda.fecha_f, '%d-%m-%Y') AS fechaF, DATE_FORMAT(busqueda_x_fecha_md_busqueda.fecha, '%d-%m-%Y %r') AS fechaC FROM busqueda_x_fecha_md_busqueda INNER JOIN usuarios ON busqueda_x_fecha_md_busqueda.id_usuario = usuarios.id_usuario ORDER BY busqueda_x_fecha_md_busqueda.fecha DESC",$con);

			// Traigo la tabla export_db

			$consulta_export_db = $interfaz->selectSQL("SELECT export_db.id_usuario, nick_usuario, DATE_FORMAT(export_db.fecha, '%d-%m-%Y %r') AS fecha_consulta FROM export_db INNER JOIN usuarios ON export_db.id_usuario = usuarios.id_usuario ORDER BY export_db.fecha DESC",$con);

			// Traigo la tabla estudiante_modificados

			$consulta_estudiantes_modificados = $interfaz->selectSQL("SELECT usuarios.nick_usuario, estudiantes.nombre, estudiantes.cedula, DATE_FORMAT(estudiantes_modificados.fecha_registro, '%d-%m-%Y %r') as fecha_hora FROM estudiantes_modificados INNER JOIN usuarios ON estudiantes_modificados.id_usuario = usuarios.id_usuario INNER JOIN estudiantes ON estudiantes.id_estudiante = estudiantes_modificados.id_estudiante ORDER BY estudiantes_modificados.fecha_registro DESC",$con);

			// Traigo la tabla registro_documentos

			$consulta_registro_documentos = $interfaz->selectSQL("SELECT registro_documentos.codigo_documento, registro_documentos.tipo_documento, usuarios.nick_usuario, estudiantes.cedula, estudiantes.nombre, DATE_FORMAT(registro_documentos.fecha_registro, '%d-%m-%Y %r') as fecha_hora FROM registro_documentos INNER JOIN usuarios ON registro_documentos.id_usuario = usuarios.id_usuario INNER JOIN estudiantes ON estudiantes.id_estudiante = registro_documentos.id_estudiantes ORDER BY registro_documentos.fecha_registro DESC",$con);

			// Traigo la tabla pdfs_usuarios

			$consulta_pdfs_usuarios = $interfaz->selectSQL("SELECT usuarios.nick_usuario, pdfs_usuarios.id_usuario, pdfs_usuarios.tipo_pdf, pdfs_usuarios.codigo_pdf, DATE_FORMAT(pdfs_usuarios.fecha_registro, '%d-%m-%Y %r') AS hora FROM pdfs_usuarios INNER JOIN usuarios ON pdfs_usuarios.id_usuario = usuarios.id_usuario ORDER BY pdfs_usuarios.fecha_registro DESC ",$con);

			// Traigo la tabla acciones_usuarios

			$consulta_acciones_usuarios = $interfaz->selectSQL("SELECT acciones_usuarios.id_usuario, acciones_usuarios.accion, acciones_usuarios.fecha_accion, usuarios.nick_usuario, DATE_FORMAT(acciones_usuarios.fecha_accion, '%d-%m-%Y %r') as fecha_hora FROM acciones_usuarios INNER JOIN usuarios ON usuarios.id_usuario = acciones_usuarios.id_usuario ORDER BY acciones_usuarios.fecha_accion DESC",$con);

			mysqli_close($con);

			?>
			<div class="h1 pb-3 pt-3" align="center" style="color: var(--success); cursor: default;">Auditoria de Datos</div>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Inicio de Sesiones</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
								<th>ID</th>
							<?php endif ?>
							<th>Usuario</th>
							<th>Tipo de Usuario</th>
							<th>Hora de Ingreso</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$iterador_inicio_sesiones = 0;
						while($row_inicio_sesion = mysqli_fetch_assoc($consulta_inicio_sesion))
							{
								$iterador_inicio_sesiones++;
								
						 ?>
						<tr <?php if ($iterador_inicio_sesiones > 3) {
							echo "style='display:none;' class='tr-none-i-s'";
						} ?>>
							<?php if ($_SESSION['tipo_usuario'] == 0) {
								?><td><?php echo $row_inicio_sesion['id_usuario']; ?></td>
							<?php } ?>
							<td><?php echo $row_inicio_sesion['nick_usuario']; ?></td>
							<td style="color: var(--blue);"><?php 


								if ($row_inicio_sesion['tipo_usuario'] == 0) {
									echo "Admin";
								}elseif ($row_inicio_sesion['tipo_usuario'] == 1) {
									echo "Director";
								}elseif ($row_inicio_sesion['tipo_usuario'] == 2) {
									echo "Secretaria/o";
								}

								 ?></td>
							<td><?php echo $row_inicio_sesion['fecha_hora']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_inicio_sesiones > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-inicio-sesion btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-inicio-sesion btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Busquedas realizadas por CEDULA</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Usuario</th>
							<th>N° de Cedula</th>
							<th>Fecha de Consulta</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_cedu_buscada = 0;

						while($row_cedu_buscada = mysqli_fetch_assoc($consulta_busqueda_x_cedula_md_busqueda))
						{
							$iterador_cedu_buscada++;

						 ?>
						<tr <?php if ($iterador_cedu_buscada > 3) {
							echo "style='display:none;' class='tr-none-c-b'";
						} ?>>
							<td><?php echo $row_cedu_buscada['nick_usuario']; ?></td>
							<td><?php echo $row_cedu_buscada['num_ced']; ?></td>
							<td><?php echo $row_cedu_buscada['fechaa']; ?></td>
						</tr>
				<?php  	}	?>
					</tbody>
				</table>
				<div align="center">
				<?php 

			if ($iterador_cedu_buscada > 3) {
				echo '
					<button class="btn ver-mas-cedu-buscada btn-secondary">Ver Más</button>
					<button style="display:none;" class="btn ver-menos-cedu-buscada btn-secondary">Ver Menos</button>
				';
			}
				// FIN DE "busqueda_x_cedula_md_busqueda"
				 ?>
					<button class="btn generar-pdf-busqueda-x-cedula btn-secondary">Generar PDF</button>
				</div>
			</div>
			<hr>
			<?php 

			// Muestro Tabla "export_db"

			 ?>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Exportaciones de la Base de Datos del Sistema</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>ID del Usuario</th>
							<th>Usuario</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_export_bd = 0;

						while($row_export_bd = mysqli_fetch_assoc($consulta_export_db))
						{
							$iterador_export_bd++;

						 ?>
						<tr <?php if ($iterador_export_bd > 3) {
							echo "style='display:none;' class='tr-none-c-b'";
						} ?>>
							<td><?php echo $row_export_bd['id_usuario']; ?></td>
							<td><?php echo $row_export_bd['nick_usuario']; ?></td>
							<td><?php echo $row_export_bd['fecha_consulta']; ?></td>
						</tr>
				<?php  	}	?>
					</tbody>
				</table>
				<div align="center">
				<?php 

			if ($iterador_export_bd > 3) {
				echo '
					<button class="btn ver-mas-cedu-buscada btn-secondary">Ver Más</button>
					<button style="display:none;" class="btn ver-menos-cedu-buscada btn-secondary">Ver Menos</button>
				';
			}
				// FIN DE "busqueda_x_cedula_md_busqueda"
				 ?>
					<!-- <button class="btn generar-pdf-export_bd btn-secondary">Generar PDF</button> -->
				</div>
			</div>

			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Busquedas realizadas por FECHA</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Usuario</th>
							<th>Fecha Inicial</th>
							<th>Fecha Final</th>
							<th>Fecha de Consulta</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_fecha_buscada = 0;

						while($row_fecha_buscada = mysqli_fetch_assoc($consulta_busqueda_x_fecha_md_busqueda))
						{
							$iterador_fecha_buscada++;

						 ?>
						<tr <?php if ($iterador_fecha_buscada > 3) {
							echo "style='display:none;' class='tr-none-f-b'";
						} ?>>
							<td><?php echo $row_fecha_buscada['nick_usuario']; ?></td>
							<td><?php echo $row_fecha_buscada['fechaI']; ?></td>
							<td><?php echo $row_fecha_buscada['fechaF']; ?></td>
							<td><?php echo $row_fecha_buscada['fechaC']; ?></td>
						</tr>
				<?php  	}	?>
					</tbody>
				</table>
				<div align="center">
				<?php 

				if ($iterador_fecha_buscada > 3) {
					echo '
						<button class="btn ver-mas-fecha-buscada btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-fecha-buscada btn-secondary">Ver Menos</button>
					';
				}
				// FIN DE "busqueda_x_fecha_md_busqueda"
				 ?>
				 	<button class="btn generar-pdf-busqueda-x-fechas btn-secondary">Generar PDF</button>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Estudiantes Modificados</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Usuario</th>
							<th>Estudiante</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_estu_modi = 0;
						while($row_estu_modi = mysqli_fetch_assoc($consulta_estudiantes_modificados))
						{
							$iterador_estu_modi++;

						 ?>
						<tr <?php if ($iterador_estu_modi > 3) {
							echo "style='display:none;' class='tr-none-e-m'";
						} ?>>
							<td><?php echo $row_estu_modi['nick_usuario']; ?></td>
							<td style="cursor: pointer;" title="<?php echo $row_estu_modi['nombre']; ?>"><?php echo $row_estu_modi['cedula']; ?></td>
							<td><?php echo $row_estu_modi['fecha_hora']; ?></td>
						</tr>
				<?php  	}	?>
					</tbody>
				</table>
				<?php 

				if ($iterador_estu_modi > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-estu-modi btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-estu-modi btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Documentos Creados (PDF'S)(Estudiantes)</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Nombre del Usuario</th>
							<th>Tipo de Documento</th>
							<th>Codigo del Documento</th>
							<th>Estudiante</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_docu_regis = 0;
						while($row_docu_regis = mysqli_fetch_assoc($consulta_registro_documentos))
						{
							$iterador_docu_regis++;

						 ?>
						<tr <?php if ($iterador_docu_regis > 3) {
							echo "style='display:none;' class='tr-none-d-r'";
						} ?>>
							<td><?php echo $row_docu_regis['nick_usuario']; ?></td>
							<td><?php 

							if ($row_docu_regis['tipo_documento'] == "Constancia de Prosecución") {
								echo "<span style='color:var(--blue);'>".$row_docu_regis['tipo_documento']."</span>";
							}elseif ($row_docu_regis['tipo_documento'] == "Certificación Final") {
								echo "<span style='color:var(--orange);'>".$row_docu_regis['tipo_documento']."</span>";
							}elseif ($row_docu_regis['tipo_documento'] == "Constancia de Buena Conducta") {
								echo "<span style='color:var(--primary);'>".$row_docu_regis['tipo_documento']."</span>";
							}

							 ?></td>
							<td><?php echo $row_docu_regis['codigo_documento']; ?></td>
							<td style="cursor: pointer;" title="<?php echo $row_docu_regis['nombre']; ?>"><?php echo $row_docu_regis['cedula']; ?></td>
							<td><?php echo $row_docu_regis['fecha_hora']; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_docu_regis > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-docu-regis btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-docu-regis btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Documentos Creados (PDF'S)(Usuarios)</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
							<th>ID del Usuario</th>	
							<?php endif ?>
							<th>Nombre del Usuario</th>
							<th>Tipo de Documento</th>
							<th>Codigo del Documento</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_pdfs_users = 0;
						while($row_list_users = mysqli_fetch_assoc($consulta_pdfs_usuarios))
						{
							$iterador_pdfs_users++;
						 ?>
						<tr <?php if ($iterador_pdfs_users > 3) {
							echo "style='display:none;' class='tr-none-d-r-u'";
						} ?>>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
								<td><?php echo $row_list_users['id_usuario']; ?></td>
							<?php endif ?>
							<td><?php echo $row_list_users['nick_usuario']; ?></td>
							<td><?php echo $row_list_users['tipo_pdf']; ?></td>
							<td><?php echo $row_list_users['codigo_pdf']; ?></td>
							<td><?php echo $row_list_users['hora']; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_pdfs_users > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-docu-regis-u btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-docu-regis-u btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Actividad de los Usuarios</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
								<th>ID User</th>
							<?php endif ?>
							<th>Usuario</th>
							<th>Acción</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_a_u = 0;
						while($row_acti_user = mysqli_fetch_assoc($consulta_acciones_usuarios))
						{
							$iterador_a_u++;

							// Acomodo el array de la accion atravez de explode's

							$accion_explode = explode(":", $row_acti_user['accion']);

							$longitud_accion_explode = count($accion_explode);
						 ?>
						<tr <?php if ($iterador_a_u > 3) {
							echo "style='display:none;' class='tr-none-a-u'";
						} ?>>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
								<td><?php echo $row_acti_user['id_usuario']; ?></td>
							<?php endif ?>
							<td><?php echo $row_acti_user['nick_usuario']; ?></td>
							<td><?php echo $accion_explode[0]; ?><span style="color: var(--yellow);"><?php echo $accion_explode[1]; ?></span><?php echo $accion_explode[2]; 

							if ($accion_explode[3] == 0) {
								echo '<span style="color: var(--blue);">Admin</span>';
							}elseif ($accion_explode[3] == 1) {
								echo '<span style="color: var(--blue);">Director</span>';
							}elseif ($accion_explode[3] == 2) {
								echo '<span style="color: var(--blue);">Secretaria/o</span>';
							}
							echo $accion_explode[4];
							if ($longitud_accion_explode > 7)
							{
								if ($accion_explode[5] == "ELIMINO")
								{
									echo '<span style="color: var(--danger);">"ELIMINO"</span>';
								}elseif ($accion_explode[5] == "ACTIVO") {
									echo '<span style="color: var(--success);">"ACTIVO"</span>';
								}elseif ($accion_explode[5] == "ACTUALIZO") {
									echo '<span style="color: var(--success);">"ACTUALIZO"</span>';
								}elseif ($accion_explode[5] == "REGISTRO") {
									echo '<span style="color: var(--primary);">"REGISTRO"</span>';
								}
								echo $accion_explode[6];
								echo '<span style="color: var(--cyan);">'.$accion_explode[7].'</span>';
							}
							?></td>
							<td><?php echo $row_acti_user['fecha_hora']; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_a_u > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-acti_user btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-acti_user btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<?php
			http_response_code(200);
		}elseif ($_SESSION['tipo_usuario'] == 2) {
			
			$interfaz = new Interfaz;
			$con = $interfaz->conexion();


			$consulta_inicio_sesion = $interfaz->selectSQL("SELECT usuarios.nick_usuario, usuarios.tipo_usuario, usuarios.id_usuario, DATE_FORMAT(inicio_sesiones.sesion, '%d-%m-%Y %r') as fecha_hora FROM inicio_sesiones INNER JOIN usuarios ON usuarios.id_usuario = inicio_sesiones.id_usuario ORDER BY `inicio_sesiones`.`sesion` DESC",$con);

			// Traigo la tabla estudiante_modificados

			$consulta_estudiantes_modificados = $interfaz->selectSQL("SELECT usuarios.nick_usuario, estudiantes.nombre, estudiantes.cedula, DATE_FORMAT(estudiantes_modificados.fecha_registro, '%d-%m-%Y %r') as fecha_hora FROM estudiantes_modificados INNER JOIN usuarios ON estudiantes_modificados.id_usuario = usuarios.id_usuario INNER JOIN estudiantes ON estudiantes.id_estudiante = estudiantes_modificados.id_estudiante ORDER BY estudiantes_modificados.fecha_registro DESC",$con);

			// Traigo la tabla registro_documentos

			$consulta_registro_documentos = $interfaz->selectSQL("SELECT registro_documentos.codigo_documento, registro_documentos.tipo_documento, usuarios.nick_usuario, estudiantes.cedula, estudiantes.nombre, DATE_FORMAT(registro_documentos.fecha_registro, '%d-%m-%Y %r') as fecha_hora FROM registro_documentos INNER JOIN usuarios ON registro_documentos.id_usuario = usuarios.id_usuario INNER JOIN estudiantes ON estudiantes.id_estudiante = registro_documentos.id_estudiantes ORDER BY registro_documentos.fecha_registro DESC",$con);

			mysqli_close($con);

			?>
			<div class="h1 pb-3 pt-3" align="center" style="color: var(--success); cursor: default;">Auditoria de Datos</div>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Inicio de Sesiones</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<?php if ($_SESSION['tipo_usuario'] == 0): ?>
								<th>ID</th>
							<?php endif ?>
							<th>Usuario</th>
							<th>Tipo de Usuario</th>
							<th>Hora de Ingreso</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$iterador_inicio_sesiones = 0;
						while($row_inicio_sesion = mysqli_fetch_assoc($consulta_inicio_sesion))
							{
								$iterador_inicio_sesiones++;
								
						 ?>
						<tr <?php if ($iterador_inicio_sesiones > 3) {
							echo "style='display:none;' class='tr-none-i-s'";
						} ?>>
							<?php if ($_SESSION['tipo_usuario'] == 0) {
								?><td><?php echo $row_inicio_sesion['id_usuario']; ?></td>
							<?php } ?>
							<td><?php echo $row_inicio_sesion['nick_usuario']; ?></td>
							<td style="color: var(--blue);"><?php 


								if ($row_inicio_sesion['tipo_usuario'] == 0) {
									echo "Admin";
								}elseif ($row_inicio_sesion['tipo_usuario'] == 1) {
									echo "Director";
								}elseif ($row_inicio_sesion['tipo_usuario'] == 2) {
									echo "Secretaria/o";
								}

								 ?></td>
							<td><?php echo $row_inicio_sesion['fecha_hora']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_inicio_sesiones > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-inicio-sesion btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-inicio-sesion btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Estudiantes Modificados</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Usuario</th>
							<th>Estudiante</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_estu_modi = 0;
						while($row_estu_modi = mysqli_fetch_assoc($consulta_estudiantes_modificados))
						{
							$iterador_estu_modi++;

						 ?>
						<tr <?php if ($iterador_estu_modi > 3) {
							echo "style='display:none;' class='tr-none-e-m'";
						} ?>>
							<td><?php echo $row_estu_modi['nick_usuario']; ?></td>
							<td style="cursor: pointer;" title="<?php echo $row_estu_modi['nombre']; ?>"><?php echo $row_estu_modi['cedula']; ?></td>
							<td><?php echo $row_estu_modi['fecha_hora']; ?></td>
						</tr>
				<?php  	}	?>
					</tbody>
				</table>
				<?php 

				if ($iterador_estu_modi > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-estu-modi btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-estu-modi btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<div class="h3 titulos-i-r-a <?php if($tema == 1){echo 'dark-title-i-r';} ?>" style="text-decoration: underline;">Documentos Creados (PDF'S)(Estudiantes)</div>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Nombre del Usuario</th>
							<th>Tipo de Documento</th>
							<th>Codigo del Documento</th>
							<th>Estudiante</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<?php 

						$iterador_docu_regis = 0;
						while($row_docu_regis = mysqli_fetch_assoc($consulta_registro_documentos))
						{
							$iterador_docu_regis++;

						 ?>
						<tr <?php if ($iterador_docu_regis > 3) {
							echo "style='display:none;' class='tr-none-d-r'";
						} ?>>
							<td><?php echo $row_docu_regis['nick_usuario']; ?></td>
							<td><?php 

							if ($row_docu_regis['tipo_documento'] == "Constancia de Prosecución") {
								echo "<span style='color:var(--blue);'>".$row_docu_regis['tipo_documento']."</span>";
							}elseif ($row_docu_regis['tipo_documento'] == "Certificación Final") {
								echo "<span style='color:var(--orange);'>".$row_docu_regis['tipo_documento']."</span>";
							}elseif ($row_docu_regis['tipo_documento'] == "Constancia de Buena Conducta") {
								echo "<span style='color:var(--primary);'>".$row_docu_regis['tipo_documento']."</span>";
							}

							?></td>
							<td><?php echo $row_docu_regis['codigo_documento']; ?></td>
							<td style="cursor: pointer;" title="<?php echo $row_docu_regis['nombre']; ?>"><?php echo $row_docu_regis['cedula']; ?></td>
							<td><?php echo $row_docu_regis['fecha_hora']; ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<?php 

				if ($iterador_docu_regis > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-docu-regis btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-docu-regis btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
			</div>
			<?php
			http_response_code(200);
		}
	}

	//Boton para el menu de usuarios

	public function editarUsuarios()
	{
		$tema = $_SESSION['tema'];
		if ($_SESSION['tipo_usuario'] == 0) {
			?>
			<div>
				<div class="row justify-content-center pb-5">
					<div class="col-12" align="center">
						<div class="h1 menus-secundarios menus-secundarios-admin">Usuarios y Sistema</div>
					</div>
					<div class="col-12 mt-4" align="center">
						<button class="lista-usuarios btn btn-outline-primary">Lista de Usuarios</button>
					</div>
					<div class="col-12 mt-4" align="center">
						<button class="agregar-user-ad btn btn-outline-success">Agregar Usuarios</button>
					</div>
					<div class="col-12 mt-4" align="center">
						<button title="Puedes crear un Backup del sistema actual, se recomienda tener una copia del sistema cado cierto tiempo para tener un respaldo si llegara a suceder un fallo grave del sistema." class="btn-create-backup-system btn btn-outline-enfasis">Respaldo del Sistema</button>
					</div>
					<div class="col-7 col-sm-3 mt-3" align="center">
						<h4>
							<small class="text-muted"><label for="tema" style="cursor: pointer;">Tema</label></small>
						</h4>
						<select name="tema" id="tema" class="form-control dark-inputs" style="cursor: pointer;<?php if($tema == 0){echo 'background: #fff; color: #000;';}elseif($tema == 1){echo 'background: rgba(0,0,0, .3); color: #fff;';} ?>">
							<?php 
							$tema = $_SESSION['tema'];
							if ($tema == 0) {
								echo '
								<option id="default-color" value="0">Normal</option>
								<option id="dark-color" value="1">Oscuro</option>
								';
							} else if($tema == 1) {
								echo '
								<option id="dark-color" value="1">Oscuro</option>
								<option id="default-color" value="0">Normal</option>
								';
							}
							

							 ?>
						</select>
					</div>
				</div>
			</div>
			<?php
			http_response_code(200);
		}elseif ($_SESSION['tipo_usuario'] == 1) {
			?>
			<div>
				<div class="row justify-content-center">
					<div class="col-12" align="center">
						<div class="h1 menus-secundarios-admin">Usuarios</div>
					</div>
					<div class="col-12 mt-5" align="center">
						<button class="btn lista-usuarios btn-outline-primary">Lista de Usuarios</button>
					</div>
					<div class="col-12 mt-5" align="center">
						<button class="btn agregar-user-ad btn-outline-success">Agregar Usuarios</button>
					</div>
					<div class="col-10 col-sm-3 mt-3 mb-3" align="center">
						<h4>
							<small class="text-muted"><label for="tema" style="cursor: pointer;">Tema</label></small>
						</h4>
						<select name="tema" id="tema" class="form-control dark-inputs" style="cursor: pointer;<?php if($tema == 0){echo 'background: #fff; color: #000;';}elseif($tema == 1){echo 'background: rgba(0,0,0, .3); color: #fff';} ?>">
							<?php 
							$tema = $_SESSION['tema'];
							if ($tema == 0) {
								echo '
								<option id="default-color" value="0">Normal</option>
								<option id="dark-color" value="1">Oscuro</option>
								';
							} else if($tema == 1) {
								echo '
								<option id="dark-color" value="1">Oscuro</option>
								<option id="default-color" value="0">Normal</option>
								';
							}
							

							 ?>
						</select>
					</div>
				</div>
			</div>
			<?php
			http_response_code(200);
		}elseif ($_SESSION['tipo_usuario'] == 2) {
			?>
			<div class="row justify-content-center">
				<div class="col-12" align="center">
					<div class="h1 menus-secundarios">Usuario</div>
				</div>
				<div class="col-12 mt-5 mb-5" align="center">
					<button class="btn btn-i-edit-sec btn-outline-primary">Editar Usuarios</button>
				</div>
				<div class="col-10 col-sm-3 mt-3 mb-3" align="center">
					<h4>
						<small class="text-muted"><label for="tema" style="cursor: pointer;">Tema</label></small>
					</h4>
					<select name="tema" id="tema" class="form-control dark-inputs" style="cursor: pointer;<?php if($tema == 0){echo 'background: #fff; color: #000;';}elseif($tema == 1){echo 'background: rgba(0,0,0, .3); color: #fff';} ?>">
						<?php 
						$tema = $_SESSION['tema'];
						if ($tema == 0) {
							echo '
							<option id="default-color" value="0">Normal</option>
							<option id="dark-color" value="1">Oscuro</option>
							';
						} else if($tema == 1) {
							echo '
							<option id="dark-color" value="1">Oscuro</option>
							<option id="default-color" value="0">Normal</option>
							';
						}
						

						 ?>
					</select>
				</div>
			</div>
			<?php
			http_response_code(200);
		}
	}

	// Tabla estadisticas de documentos del mes

	public function estadisticaDocumentosMes($con)
	{
		$fecha = date("Y-m");

		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$documentos_e = $interfaz->selectSQL("SELECT DATE_FORMAT(registro_documentos.fecha_registro, '%d-%m-%Y') AS fecha, registro_documentos.tipo_documento FROM registro_documentos WHERE registro_documentos.fecha_registro LIKE '%$fecha%' ORDER BY registro_documentos.fecha_registro ASC",$con);
		mysqli_close($con);
		if ($documentos_e->num_rows > 0) {


			$array_tipo_doc_cerFinal_X = array();
			$array_fecha_cerFinal_Y = array();
			$array_tipo_doc_contCond_X = array();
			$array_fecha_contCond_Y = array();
			$array_tipo_doc_contProse_X = array();
			$array_fecha_contProse_Y = array();

			while ($i = $documentos_e->fetch_assoc()) {
				if ($i['tipo_documento'] == "Certificación Final") {

					$array_tipo_doc_cerFinal_X[] = $i['tipo_documento'];
					if ($i['fecha'] != null) {
						$array_fecha_cerFinal_Y[] = $i['fecha'];
					}

				}elseif ($i['tipo_documento'] == "Constancia de Buena Conducta") {

					$array_tipo_doc_contCond_X[] = $i['tipo_documento'];
					if ($i['fecha'] != null) {
						$array_fecha_contCond_Y[] = $i['fecha'];
					}

				}elseif ($i['tipo_documento'] == "Constancia de Prosecución") {

					$array_tipo_doc_contProse_X[] = $i['tipo_documento'];
					if ($i['fecha'] != null) {
						$array_fecha_contProse_Y[] = $i['fecha'];
					}
				}
			}

			return ["code" => 200, "tipo_doc_cerFinal_X" => $array_tipo_doc_cerFinal_X, "tipo_doc_contCond_X" => $array_tipo_doc_contCond_X, "tipo_doc_contProse_X" => $array_tipo_doc_contProse_X, "fecha_cerFinal_Y" => $array_fecha_cerFinal_Y, "fecha_contCond_Y" => $array_fecha_contCond_Y, "fecha_contProse_Y" => $array_fecha_contProse_Y];
		}else{
			return ["code" => 404];
		}
	}

	// Interfaz Update USUARIO: Secretaria/o

	public function interfazUpdateUserSec()
	{
		$id_usuario = $_SESSION['id_usuario'];
		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$row = $interfaz->selectOne("usuarios","id_usuario",$id_usuario,$con);
		mysqli_close($con);
		?>


			<form id="form_update_secretario" name="form_update_secretario">
			<div class="h1 mt-4" align="center">Modificar Usuario: <span style="color: <?php if ($row['tipo_usuario']==0) { echo "var(--adm);"; }elseif($row['tipo_usuario']==1){echo "var(--dir);"; }elseif($row['tipo_usuario']==2){echo "var(--sec);"; } ?>"><?php echo $row['nick_usuario']; ?></span></div>
				<div class="form-group row justify-content-center" align="center">
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" value="<?php echo $row['nombre_usuario']; ?>">
						<input type="hidden" id="id_usuario" value="<?php echo $row['id_usuario']; ?>">
						<input type="hidden" id="nick_usuario_old" value="<?php echo $row['nick_usuario']; ?>">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Apellido Solo debe llevar Letras." autocomplete="off" name="apellido_usuario" value="<?php echo $row['apellido_usuario']; ?>">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" value="<?php echo $row['nick_usuario']; ?>" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="contra_usuario" id="contra" class="label-regis-cer">Contraseña</label>
						<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" value="<?php echo $row['contra_usuario']; ?>">
					</div>
					<div class="col-12">
						<div class="row justify-content-center">
							<div class="col-11 col-md-10 mt-3" align="center">
								<label for="contra_usuario_confirm" id="contra1" class="label-regis-cer">Confirme Contraseña</label>
								<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" value="<?php echo $row['contra_usuario']; ?>" placeholder="Repita la Contraseña">
							</div>
						</div>
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn btn-primary btn-block" type="submit">Guardar</button>
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn regresar-menu-user-ce btn-primary btn-block">Regresar</button>
					</div>
				</div>
			</form>


		<?php
		http_response_code(200);

	}


	// Boton de ir al menu de certificaciones
	
	public function btnDocumentos(){
		?>
		<div class="row justify-content-center">
			<div class="col-12">
				<div class="h1" style="cursor: default;" align="center">Documentos y Estudiantes</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-11 col-md-5">
					<div class="row justify-content-center">
						<div class="col-12 mt-4" align="center">
								<button class="btn create-cer btn-outline-success">Registrar Estudiante</button>
							</div>
							<div class="col-12 mt-4" align="center">
								<button class="btn ir-searc btn-outline-enfasis">Buscar Estudiante</button>
							</div>
							<div class="col-12 mt-4" align="center">
								<button class="btn update-model-cer btn-outline-primary">Editar Datos de la Plantilla</button>
							</div>
					</div>
				</div>
				<div class="col-11 col-md-5">
					<div class="row justify-content-center">
						<div class="col-12 mt-4" align="center">
							<button class="btn create-example-pdf-cer-final btn-outline-success">Certificación Final (Ejem)</button>
						</div>
						<div class="col-12 mt-4" align="center">
							<button class="btn create-example-pdf-cons-cond btn-outline-enfasis">Constancia de Conducta (Ejem)</button>
						</div>
						<div class="col-12 mt-4" align="center">
							<button class="btn create-example-pdf-cons-prese btn-outline-primary">Constancia de Prosecusión (Ejem)</button>
						</div>
					</div>
				</div>
				<div class="col-11 col-md-5 mt-4 mb-3" align="center">
					<button class="btn create-graphy-bars btn-outline-enfasis">Estadistica de Documentos del Mes</button>
				</div>
			</div>
		</div>
		<?php
		http_response_code(200);
	}

	public function crearEstudiante()
	{
		$tema = $_SESSION['tema'];
		?>

		<form id="guardar_cer" name="guardar_cer">
		<div class="h3 col-12 titulo-crear-cer" align="center">Crear Estudiante</div>
			<div class="form-group row justify-content-center" align="center">
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nombre" class="label-regis-cer">Nombre(s)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre" name="nombre" placeholder="Nombre" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="40" autocomplete="off" title="El Nombre solo puede llevar letras con acentos y espacios." required>
				</div>

				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="apellido" class="label-regis-cer">Apellido(s)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido" name="apellido" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="40" autocomplete="off" title="El Apellido solo puede llevar letras con acentos y espacios." placeholder="Apellido" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3">
					<label for="nacionalidad" class="label-regis-cer">Nacionalidad</label>
					<select  id="nacionalidad" name="nacionalidad" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" required>
						<option value="">Seleccione</option>
						<option value="venezolana">Venezolana</option>
						<option value="extranjera">Extranjera</option>
					</select>
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="cedula" class="label-regis-cer">Cedula del Estudiante</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="cedula" name="cedula" pattern="[0-9]+" minlength="6" maxlength="8" title="La Cedula solo puede llevar Numeros." placeholder="Cedula" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="fecha_nacimiento_estudiante" class="label-regis-cer">Fecha de Nacimiento del Estudiante</label>
						<input type="date" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="fecha_nacimiento_estudiante" name="fecha_nacimiento_estudiante" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="lugar_nacimiento" class="label-regis-cer">Lugar de Nacimineto</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="lugar_nacimiento" name="lugar_nacimiento" placeholder="Municipio, Estado" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ,\s]+" title="Solo Letras, Espacios, comas." maxlength="40" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="literal" class="label-regis-cer">Literal del Estudiante</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="literal" name="literal" pattern="[A-Z]+" maxlength="1" placeholder="Letra (Mayuscula)." title="Solo una Letra Mayuscula" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3">
					<label for="periodo_escolar">Periodo Escolar</label>
					<input type="text" id="periodo_escolar" name="periodo_escolar" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" title="Solo numeros y un guion" maxlength="9" minlength="9" autocomplete="off" placeholder="Ejem: 2019-2020" required pattern="[0-9--]+">
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn btn-primary btn-block" type="submit">Guardar</button>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn return-cer btn-primary btn-block">Volver al Menu Anterior</button>
				</div>
			</div>
		</form>

		<?php
		http_response_code(200);
	}


	public function plantillaCertificacion()
	{
		$tema = $_SESSION['tema'];

		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$plantilla_existe = $interfaz->selectAll("plantilla_documentos",$con);
		mysqli_close($con);

		if ($plantilla_existe->num_rows ===1)
		{
			$row = $plantilla_existe->fetch_assoc();
		?>

		<form id="guardar_plantilla" name="guardar_plantilla">
		<div class="h3 col-12 titulo-crear-cer" align="center">Datos de la Plantilla de los Documentos</div>
			<div class="form-group row justify-content-center" align="center">
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nombre_director" class="label-regis-cer">Nombre(s) de el (la) Director(a)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_director" name="nombre_director" placeholder="Nombre" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" autocomplete="off" title="El Nombre del Director solo puede llevar letras con acentos y espacios." required value="<?php echo $row['nombre_director']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="apellido_director" class="label-regis-cer">Apellido(s) de el (la) Director(a)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_director" name="apellido_director" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="20" autocomplete="off" title="El Apellido solo puede llevar letras con acentos y espacios." placeholder="Apellido" required autocomplete="off" value="<?php echo $row['apellido_director']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nacionalidad" class="label-regis-cer">Nacionalidad</label>
					<select name="nacionalidad" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" id="nacionalidad" required>
						<?php 

						if ($row['nacionalidad'] == "venezolana") {
							echo '
							<option value="venezolana">Venezolana</option>
							<option value="extranjera">Extranjera</option>';

						}elseif ($row['nacionalidad'] == "extranjera") {
							echo '
							<option value="extranjera">Extranjera</option>
							<option value="venezolana">Venezolana</option>';
						}

						 ?>
					</select>
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="genero" class="label-regis-cer">Genero</label>
					<select name="genero" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" id="genero" required>
						<?php 

						if ($row['genero'] == "hombre") {
							echo '
							<option value="hombre">Hombre</option>
							<option value="mujer">Mujer</option>';

						}elseif ($row['genero'] == "mujer") {
							echo '
							<option value="mujer">Mujer</option>
							<option value="hombre">Hombre</option>';
						}

						 ?>
					</select>
				</div>
				<div class="col-12 mt-3" align="center">
					<div>
						<label for="cedula_director" class="label-regis-cer">Cedula del (la) Director(a)</label>
					</div>
					<div class="col-12 col-md-3">
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="cedula_director" name="cedula_director" pattern="[0-9.]+" minlength="6" maxlength="10" title="La Cedula solo puede llevar Numeros y Puntos. Ejem (00.000.000)" placeholder="Cedula Ejem: (00.000.000)" required="." autocomplete="off" value="<?php echo $row['cedula_director']; ?>">
					</div>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn btn-primary btn-block" type="submit">Guardar</button>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn return-cer btn-primary btn-block">Volver al Menu Anterior</button>
				</div>
			</div>
		</form>

		<?php
		http_response_code(200);
		}
		else
		{
			?>

		<form id="guardar_plantilla" name="guardar_plantilla">
		<div class="h3 col-12 titulo-crear-cer" align="center">Datos de la Plantilla de la Certificación</div>
			<div class="form-group row justify-content-center" align="center">
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nombre_director" class="label-regis-cer">Nombre(s) de el (la) Director(a)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_director" name="nombre_director" placeholder="Nombre" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" autocomplete="off" title="El Nombre del Director solo puede llevar letras con acentos y espacios." required>
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="apellido_director" class="label-regis-cer">Apellido(s) de el (la) Director(a)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_director" name="apellido_director" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="20" autocomplete="off" title="El Apellido solo puede llevar letras con acentos y espacios." placeholder="Apellido" required autocomplete="off">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nacionalidad" class="label-regis-cer">Nacionalidad</label>
					<select name="nacionalidad" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" id="nacionalidad" required>
						<option value="">Seleccione</option>
						<option value="venezolana">Venezolana</option>
						<option value="extranjera">Extranjera</option>
					</select>
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="genero" class="label-regis-cer">Genero</label>
					<select name="genero" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" id="genero" required>
						<option value="">Seleccione</option>
						<option value="hombre">Hombre</option>
						<option value="mujer">Mujer</option>
					</select>
				</div>
				<div class="col-12 mt-3" align="center">
					<div>
						<label for="cedula_director" class="label-regis-cer">Cedula del (la) Director(a)</label>
					</div>
					<div class="col-12 col-md-5">
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="cedula_director" name="cedula_director" pattern="[0-9 .]+" minlength="10" maxlength="10" title="La Cedula solo puede llevar Numeros y Puntos. Ejem (00.000.000)" placeholder="Cedula Ejem: (00.000.000)" required autocomplete="off">
					</div>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn btn-primary btn-block" type="submit">Guardar</button>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn return-cer btn-primary btn-block">Volver al Menu Anterior</button>
				</div>
			</div>
		</form>

		<?php
		http_response_code(200);
		}
		
		
	}


	//Boton de crear usuarios

	public function btnCrearUserAdmin()
	{
		$tema = $_SESSION['tema'];
		if ($_SESSION["tipo_usuario"] == 0) {
			?>
			<form id="form_guardar_usuario_ad" name="form_guardar_usuario_ad">
			<div class="h1 title-crear-usuario" align="center">Crear Usuario</div>
				<div class="form-group row justify-content-center" align="center">
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" required title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" placeholder="Nombre">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="20" title="El Apellido solo puede llevar Letras" autocomplete="off" name="apellido_usuario" placeholder="Apellido">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="tipo_usuario" class="label-regis-cer">Tipo de Usuario</label>
						<select name="tipo_usuario" id="tipo_usuario" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" style="cursor: pointer;" required>
							<option value="">Seleccione</option>
							<option value="2">Secretaria/o</option>
							<option value="1">Director</option>
							<option value="0">Administrador</option>
						</select>
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" placeholder="Ejem: luis2020" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="contra_usuario" id="contra" class="label-regis-cer">Contraseña</label>
						<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" placeholder="Contraseña" maxlength="16">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="contra_usuario_confirm" id="contra1" class="label-regis-cer">Confirme Contraseña</label>
						<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" placeholder="Repita la Contraseña" maxlength="16">
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn btn-submit-sv-user-ad btn-primary btn-block" type="submit">Guardar</button>
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn retroceder-menu-us btn-primary btn-block">Volver al Menu Anterior</button>
					</div>
				</div>
			</form>
			<?php
			http_response_code(200);
		}elseif ($_SESSION["tipo_usuario"] == 1) {
			?>
			<form id="form_crear_user" name="form_crear_user">
			<div class="h1 mt-4" align="center">Crear Usuario</div>
				<div class="form-group row justify-content-center" align="center">
					<div class="col-12 col-md-5 mt-3" align="center">
						<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" placeholder="Nombre">
					</div>
					<div class="col-12 col-md-5 mt-3" align="center">
						<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required autocomplete="off" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="20" title="El Apellido Solo debe llevar Letras." name="apellido_usuario" placeholder="Apellido">
					</div>
					<div class="col-12 col-md-5 mt-3" align="center">
						<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" placeholder="Ejem: luis2020" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
					</div>
					<div class="col-12 col-md-5 mt-3" align="center">
						<label id="contra" for="contra_usuario" class="label-regis-cer">Contraseña</label>
						<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" placeholder="Contraseña">
					</div>
					<div class="col-12">
						<div class="row justify-content-center">
							<div class="col-12 col-md-5 mt-3" align="center">
								<label id="contra1" for="contra_usuario_confirm" class="label-regis-cer">Confirme Contraseña</label>
								<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" placeholder="Repita la Contraseña">
							</div>
						</div>
					</div>
					<div class="col-10 mt-2">
						<h6>
							<small class="text-muted">NOTA: "El Usuario de tipo DIRECTOR solo puede crear USUARIOS de tipo SECRETARIO"</small>
						</h6>
					</div>
					<div class="col-10 col-md-5 mt-2">
						<button class="btn btn-primary btn-block" type="submit">Guardar</button>
					</div>
					<div class="col-10 col-md-5 mt-2">
						<button class="btn retroceder-menu-us btn-primary btn-block">Volver al Menu Anterior</button>
					</div>
				</div>
			</form>
			<?php
			http_response_code(200);
		}
	}

	public function listaDeUsuarios($con)
	{
		
		if ($_SESSION['tipo_usuario'] == 0 ) {
			$interfaz = new Interfaz;
			$con = $interfaz->conexion();
			$usuarios = $interfaz->selectSQL("SELECT *, DATE_FORMAT(fecha_registro, '%r') as hora FROM usuarios",$con);
			mysqli_close($con);

			?>
			<div class="row">
				<div class="col-12 mt-3" align="center">
					<div class="h3" align="center">Lista de Usuarios</div>
				</div>
			</div>
				<div class="table-responsive mb-3" align="center">
					<table class="table" align="center">
						<thead class="thead-default">
							<tr style="background: rgba(51,51,51, .9); color: #fff;">
								<th style="color: #00D8FF">ID</th>
								<th style="color: #ff450f">Nombre de Usuario</th>
								<th style="color: #bbb">Contraseña</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th style="color: #00D8FF">Tipo de Usuario</th>
								<th>Fecha de Registro</th>
								<th style="color: #00D8FF">Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$iterador_list_user = 0;

							 while($row = mysqli_fetch_assoc($usuarios))
							{
							 ?>
							<tr>
								<th <?php
								$tema = $_SESSION['tema'];
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php echo $row['id_usuario']; ?></th>
								<th id="nick_usuario" <?php
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php echo $row['nick_usuario']; ?></th>
								<th <?php
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla); width:150px;"';
								}elseif ($tema ==0) {
									echo 'style="width:150px;"';
								} ?>><?php if ($tema == 1) {
									echo "<input type='password' class='pass-list' style='background:none; border:none; width:80px; color:#fff;' disabled='on' data-id='".$iterador_list_user."' value=".$row['contra_usuario'].">";
								}elseif ($tema == 0) {
									echo "<input type='password' class='pass-list' style='background:none; border:none; width:50px;' disabled='on' data-id='".$iterador_list_user."' value=".$row['contra_usuario'].">";
								} ?><button class="btn-ver-pass" <?php echo "data-id='".$iterador_list_user."'"; ?> style="background: none; display: block; color: var(--success); cursor: pointer;">Ver</button><button class="btn-ocultar-pass" <?php echo "data-id='".$iterador_list_user."'"; ?> style="background: none; display: none; color: var(--success); cursor: pointer;">Ocultar</button></th>
								<th <?php if ($tema == 1) {echo 'style="background: var(--color-dark-tabla)"';}?>><?php echo $row['nombre_usuario']; ?></th>
								<th <?php
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php echo $row['apellido_usuario']; ?></th>
								<?php
								if ($row['tipo_usuario']==0) {
									if ($tema == 1) {
										echo "<th style='color: var(--adm); background: var(--color-dark-tabla);'>Admin</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--adm);'>Admin</th>";
									}
								}elseif ($row['tipo_usuario']==1) {
									if ($tema == 1) {
										echo "<th style='color: var(--dir); background: var(--color-dark-tabla);'>Director(a)</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--dir);'>Director(a)</th>";
									}
								}elseif ($row['tipo_usuario']==2) {
									if ($tema == 1) {
										echo "<th style='color: var(--sec); background: var(--color-dark-tabla);'>Secretaria/o</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--sec);'>Secretaria/o</th>";
									}
								}
								 ?></th>
								<th <?php if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php

								// Acomodo la fecha que devuelve el array del mysqli_result para darle un mejor estilo visual

								$fecha_array = explode(" ", $row['fecha_registro']);
								$fecha_año = explode("-", $fecha_array[0]);

								$fecha_año_array = "$fecha_año[2]-$fecha_año[1]-$fecha_año[0]";

								echo "".$fecha_año_array." ".$row['hora']."";

								  ?></th>
								<?php 

								if ($row['status_usuario']==1) {
									if ($tema == 1) {
										echo "<th style='color: var(--success); background: var(--color-dark-tabla);'>Activo</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--success)'>Activo</th>";
									}
									
								}elseif ($row['status_usuario']==0) {

									if ($tema == 1) {
										echo "<th style='color: var(--danger); background: var(--color-dark-tabla);'>Eliminado</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--danger);'>Eliminado</th>";
									}
								}

								 ?>
								<th class="btn-editar-usuario" data-id="<?php echo $row['id_usuario'] ?>" style="vertical-align: middle;">Editar</th>
								<?php if ($_SESSION['nick_usuario']!=$row['nick_usuario'] && $row['status_usuario'] == 1) {
								
									echo "<th class='btn-delete-usuario' data-id='".$row['id_usuario']."' style='vertical-align: middle;'>Borrar</th>
											";
								}elseif ($row['status_usuario'] == 0) {
									echo "<th class='btn-activar-usuario' data-id='".$row['id_usuario']."' style='vertical-align: middle;'>Activar</th>
											";
								}

								$iterador_list_user++;
								 ?>
								 <th class="btn-ver-acciones" data-id="<?php echo $row['id_usuario']; ?>">Ver Acciones</th>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="row justify-content-center" align="center">
					<div class="col-12 mt-2 mb-2 col-md-5" align="center">
						<button class="btn create_list_user btn-primary btn-block">Generar Listado de Usuarios (PDF)</button>
					</div>
					<div class="col-12 mt-2 mb-2 col-md-5" align="center">
						<button class="btn retroceder-menu-us btn-primary btn-block">Volver al Menu Anterior</button>
					</div>
				</div>
			<?php
			http_response_code(200);
		}elseif ($_SESSION['tipo_usuario'] == 1) {

			$tema = $_SESSION['tema'];

			$interfaz = new Interfaz;
			$con = $interfaz->conexion();
			$usuarios = $interfaz->selectSQL("SELECT *, DATE_FORMAT(fecha_registro, '%r') as hora FROM usuarios",$con);
			mysqli_close($con);

			?>
			<div class="row">
				<div class="col-12 mt-3" align="center">
					<div class="h3" align="center">Lista de Usuarios</div>
				</div>
			</div>
				<div class="table-responsive mb-3" align="center">
					<table class="table" align="center">
						<thead class="thead-default">
							<tr style="background: rgba(51,51,51, .9); color: #fff;">
								<th style="color: #ff450f">Nombre de Usuario</th>
								<th>Nombre</th>
								<th>Apellido</th>
								<th style="color: #00D8FF">Tipo de Usuario</th>
								<th>Fecha de Registro</th>
								<th style="color: #00D8FF">Estado</th>
							</tr>
						</thead>
						<tbody>
							<?php while($row = mysqli_fetch_assoc($usuarios))
							{ ?>
							<tr>
								<th id="nick_usuario" <?php
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php echo $row['nick_usuario']; ?></th>
								<th <?php if ($tema == 1) {echo 'style="background: var(--color-dark-tabla)"';}?>><?php echo $row['nombre_usuario']; ?></th>
								<th <?php
								 if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php echo $row['apellido_usuario']; ?></th>
								<?php
								if ($row['tipo_usuario']==0) {
									if ($tema == 1) {
										echo "<th style='color: var(--adm); background: var(--color-dark-tabla);'>Admin</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--adm);'>Admin</th>";
									}
								}elseif ($row['tipo_usuario']==1) {
									if ($tema == 1) {
										echo "<th style='color: var(--dir); background: var(--color-dark-tabla);'>Director(a)</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--dir);'>Director(a)</th>";
									}
								}elseif ($row['tipo_usuario']==2) {
									if ($tema == 1) {
										echo "<th style='color: var(--sec); background: var(--color-dark-tabla);'>Secretaria/o</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--sec);'>Secretaria/o</th>";
									}
								}
								 ?></th>
								<th <?php if ($tema == 1) {
									echo 'style="background: var(--color-dark-tabla)"';
								} ?>><?php

								// Acomodo la fecha que devuelve el array del mysqli_result para darle un mejor estilo visual

								$fecha_array = explode(" ", $row['fecha_registro']);
								$fecha_año = explode("-", $fecha_array[0]);

								$fecha_año_array = "$fecha_año[2]-$fecha_año[1]-$fecha_año[0]";

								echo "".$fecha_año_array." ".$row['hora']."";

								  ?></th>
								<?php 

								if ($row['status_usuario']==1) {
									if ($tema == 1) {
										echo "<th style='color: var(--success); background: var(--color-dark-tabla);'>Activo</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--success)'>Activo</th>";
									}
									
								}elseif ($row['status_usuario']==0) {

									if ($tema == 1) {
										echo "<th style='color: var(--danger); background: var(--color-dark-tabla);'>Eliminado</th>";
									} else if($tema == 0) {
										echo "<th style='color: var(--danger);'>Eliminado</th>";
									}
								}


								if ($_SESSION['tipo_usuario'] == 1) {
									if ($row['tipo_usuario'] == 2 || $_SESSION['nick_usuario'] == $row['nick_usuario']) {
										echo '<th class="btn-editar-usuario" data-id="'.$row['id_usuario'].'" style="vertical-align: middle;">Editar</th>';
									}
								}

								if ($_SESSION['nick_usuario']!=$row['nick_usuario'] && $row['status_usuario'] == 1 && $row['tipo_usuario'] == 2) {
								
									echo "<th class='btn-delete-usuario' data-id='".$row['id_usuario']."' style='vertical-align: middle;'>Borrar</th>
											";
								}elseif ($row['status_usuario'] == 0) {
									echo "<th class='btn-activar-usuario' data-id='".$row['id_usuario']."' style='vertical-align: middle;'>Activar</th>
											";
								} 

								 ?>
								 <th class="btn-ver-acciones" data-id="<?php echo $row['id_usuario']; ?>">Ver Acciones</th>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="row justify-content-center" align="center">
					<div class="col-12 mt-2 mb-2 col-md-5" align="center">
						<button class="btn create_list_user btn-primary btn-block">Generar Listado de Usuarios (PDF)</button>
					</div>
					<div class="col-12 mt-2 mb-2 col-md-5" align="center">
						<button class="btn retroceder-menu-us btn-primary btn-block">Volver al Menu Anterior</button>
					</div>
				</div>
			<?php
			http_response_code(200);
		}
	}

	public function verDetallesUser($id_usuario,$con)
	{
		$tema = $_SESSION['tema'];
		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$consulta_inicio_sesion_o = $interfaz->selectSQL("SELECT DATE_FORMAT(sesion, '%d-%m-%Y %r') AS fecha FROM inicio_sesiones WHERE id_usuario = '$id_usuario'",$con);
		$consulta_nick_user_o = $interfaz->selectSQL("SELECT nick_usuario FROM usuarios WHERE id_usuario = '$id_usuario'",$con);
		$consulta_acciones_user_o = $interfaz->selectSQL("SELECT acciones_usuarios.accion, DATE_FORMAT(acciones_usuarios.fecha_accion, '%d-%m-%Y %r') as fecha FROM acciones_usuarios INNER JOIN usuarios ON usuarios.id_usuario = acciones_usuarios.id_usuario WHERE acciones_usuarios.id_usuario = '$id_usuario' ORDER BY acciones_usuarios.fecha_accion DESC",$con);
		mysqli_close($con);
		$nick_user_query = mysqli_fetch_assoc($consulta_nick_user_o);
		?>

		<div class="row justify-content-center" align="center">
			<div class="col-10 mt-2">
				<div class="h1 titulo-v-d-u" style="text-decoration: underline; border-radius: 3px; color: var(--gray);">Acciones del Usuario: <span style="color: var(--success);"><?php echo $nick_user_query['nick_usuario']; ?></span></div>
			</div>
			<div class="col-11 mt-3">
				<div class="h3 titulo-v-d-u <?php if($tema == 1){echo 'dark-title-i-r';} ?>">Acciones del Usuario</div>
				<?php 

				if ($consulta_acciones_user_o->num_rows > 0) {
					$iterador_consulta_a_u_o = 0;
					?>

					<div class="table-responsive">
						<table class="table">
							<thead style="background: var(--color-e); color: #fff;">
								<tr>
									<th>Descripción</th>
									<th>Fecha</th>
								</tr>
							</thead>
							<tbody>
							<?php while ($row_consulta_user = mysqli_fetch_assoc($consulta_acciones_user_o)) {
									$iterador_consulta_a_u_o++;
								 ?>
								<tr <?php if ($iterador_consulta_a_u_o > 3) {
									echo 'style="display:none;" class="tr-none-a-u"';
								} ?>>
									<?php 


								$accion_explode = explode(":", $row_consulta_user['accion']);
								$longitud_accion_explode = count($accion_explode);
								?><td><?php echo $accion_explode[0]; ?><span style="color: var(--yellow);"><?php echo $accion_explode[1]; ?></span><?php echo $accion_explode[2]; 
								if ($accion_explode[3] == 0) {
									echo '<span style="color: var(--blue);">Admin</span>';
								}elseif ($accion_explode[3] == 1) {
									echo '<span style="color: var(--blue);">Director</span>';
								}elseif ($accion_explode[3] == 2) {
									echo '<span style="color: var(--blue);">Secretaria/o</span>';
								}
								echo $accion_explode[4];
								if ($longitud_accion_explode > 7)
								{
									if ($accion_explode[5] == "ELIMINO")
									{
										echo '<span style="color: var(--danger);">"ELIMINO"</span>';
									}elseif ($accion_explode[5] == "ACTIVO") {
										echo '<span style="color: var(--success);">"ACTIVO"</span>';
									}elseif ($accion_explode[5] == "ACTUALIZO") {
										echo '<span style="color: var(--success);">"ACTUALIZO"</span>';
									}elseif ($accion_explode[5] == "REGISTRO") {
										echo '<span style="color: var(--primary);">"REGISTRO"</span>';
									}
									echo $accion_explode[6];
									echo '<span style="color: var(--cyan);">'.$accion_explode[7].'</span>';
								}
								?></td>
									<td><?php echo $row_consulta_user['fecha']; ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
						<?php 

				if ($iterador_consulta_a_u_o > 3) {
					echo '
					<div align="center">
						<button class="btn ver-mas-acti_user btn-secondary">Ver Más</button>
						<button style="display:none;" class="btn ver-menos-acti_user btn-secondary">Ver Menos</button>
					</div>
					';
				}

				 ?>
					</div>

					<?php

				}else{
					echo "El Usuario ".$nick_user_query['nick_usuario']." no ha hecho ninguna acción hasta el momento.";
				}


				 ?>
				
			</div>
			<div class="col-12">
				<hr>
			</div>
			<div class="col-11">
				<div class="h3 titulo-v-d-u <?php if($tema == 1){echo 'dark-title-i-r';} ?>">Ultimo Inicio de Sesión</div>
				<div><?php 

				if ($consulta_inicio_sesion_o->num_rows > 0) {
					$fecha_session = mysqli_fetch_assoc($consulta_inicio_sesion_o);
					echo $fecha_session['fecha'];
				}else{
					echo "El Usuario ".$nick_user_query['nick_usuario']." nunca ha iniciado sesión hasta la fecha.";
				}

				 ?></div>
			</div>
			<?php 


			if ($consulta_inicio_sesion_o->num_rows > 0 || $consulta_acciones_user_o->num_rows > 0) {
				?>
				<div class="col-11 mt-3 mb-2 col-md-5">
					<button title="Generar un PDF con las Acciones del Usuario <?php echo $nick_user_query['nick_usuario']; ?>" data-id="<?php echo $id_usuario; ?>" class="btn generar-pdf-acciones-user btn-primary btn-block">Generar PDF</button>
				</div>
				<?php
			}


			 ?>
			<div class="col-11 mt-3 mb-2 col-md-5">
				<button class="btn ir-lista-usuarios btn-primary btn-block">Volver a la Lista de Usuario</button>
			</div>
			<div class="col-9 mb-2">
				<h7>
					<small class="text-muted">*NOTA: Esta son las acciones desde el punto de vista entre USUARIOS, las acciones como: creación de documentos y de estudiantes, estan en la vista de Auditoria de Datos.*</small>
				</h7>
			</div>
			<div class="col-9 mb-2">
				<h7>
					<small class="text-muted"><?php 

					if ($consulta_inicio_sesion_o->num_rows == 0 || $consulta_acciones_user_o->num_rows == 0) {
					
						echo "*Si el Usuario ".$nick_user_query['nick_usuario']." no tiene ningún inicio de sesión, no va a ver tampoco ningún registro en el Auditoria de Datos*";
					}

					 ?></small>
				</h7>
			</div>
		</div>

		<?php
	}

	public function updateUserI($id_usuario,$con)
	{
		$tema = $_SESSION['tema'];
		if ($_SESSION['tipo_usuario'] == 0 ) {
			if ($_SESSION['id_usuario'] == $id_usuario) {
				$interfaz = new Interfaz;
				$con = $interfaz->conexion();
				$row = $interfaz->selectOne("usuarios","id_usuario",$id_usuario,$con);
				mysqli_close($con);
				?>

				<form id="form_update_usuario_ad_i" name="form_update_usuario_ad_i">
				<div class="h1 mt-4" align="center">Actualizar tu Usuario: <span style="color: <?php if ($row['tipo_usuario']==0) { echo "var(--adm);"; }elseif($row['tipo_usuario']==1){echo "var(--dir);"; }elseif($row['tipo_usuario']==2){echo "var(--sec);"; } ?>"><?php echo $row['nick_usuario']; ?></span> <span style="color: var(--green);">ID: <?php echo $row['id_usuario']; ?></span></div>
					<div class="form-group row justify-content-center" align="center">
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" value="<?php echo $row['nombre_usuario']; ?>">
							<input type="hidden" id="id_usuario" value="<?php echo $row['id_usuario']; ?>">
							<input type="hidden" id="nick_usuario_old" value="<?php echo $row['nick_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Apellido Solo debe llevar Letras." autocomplete="off" name="apellido_usuario" value="<?php echo $row['apellido_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="tipo_usuario" class="label-regis-cer">Tipo de Usuario</label>
							<select name="tipo_usuario" id="tipo_usuario" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" style="cursor: pointer;" required>
								<?php 
								if ($row['tipo_usuario'] == 0) {
									?>
									<option value="<?php echo $row['tipo_usuario']; ?>"><?php 
									if ($row['tipo_usuario'] == 0) {
										echo "Administrador";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="2">Secretaria/o</option>
									<option value="1">Director</option>
									<?php
								}if ($row['tipo_usuario'] == 1) {
									?>
									<option value="<?php echo $row['tipo_usuario'];  ?>"><?php 
									if ($row['tipo_usuario'] == 1) {
										echo "Director";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="2">Secretaria/o</option>
									<option value="0">Administrador</option>
									<?php
								}if ($row['tipo_usuario'] == 2) {
									?>
									<option value="<?php echo $row['tipo_usuario'];  ?>"><?php 
									if ($row['tipo_usuario'] == 2) {
										echo "Secretaria/o";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="1">Director</option>
									<option value="0">Administrador</option>
								<?php
								}
								 ?>
							</select>
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" value="<?php echo $row['nick_usuario']; ?>" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="contra_usuario" id="contra" class="label-regis-cer">Contraseña</label>
							<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" value="<?php echo $row['contra_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="contra_usuario_confirm" id="contra1" class="label-regis-cer">Confirme Contraseña</label>
							<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" value="<?php echo $row['contra_usuario']; ?>" placeholder="Repita la Contraseña">
						</div>
						<?php if ($_SESSION['nick_usuario'] != $row['nick_usuario']){ ?>
							<div class="row justify-content-center" style="width: 100%;">
								<div class="col-12 col-md-5 mt-3">
									<label for="status_usuario" class="label-regis-cer">Estado</label>
									<select name="status_usuario" id="status_usuario" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> <?php if($row['status_usuario'] == 0){echo "eliminado";}elseif($row['status_usuario'] == 1){echo "activo";} ?>" style="cursor: pointer;" required>
							<?php   if ($row['status_usuario'] == 0) {
											echo "
											<option value='0' class='eliminado option-el'>Eliminado</option>
											<option value='1' class='activo option-ac'>Activo</option>
											";
										}elseif ($row['status_usuario'] == 1) {
											echo "
											<option value='1' class='activo option-ac'>Activo</option>
											<option value='0' class='eliminado option-el'>Eliminado</option>
											";
										}?>
									</select>			
								</div>
							</div>
						<?php } ?>
						<div class="col-10 col-md-5 mt-4">
							<button class="btn btn-submit-update-user-ad btn-primary btn-block" type="submit">Guardar</button>
						</div>
						<div class="col-10 col-md-5 mt-4">
							<button class="btn ir-lista-usuarios btn-primary btn-block">Ir a Lista de Usuario</button>
						</div>
					</div>
				</form>

				<?php
			}else{
				$interfaz = new Interfaz;
				$con = $interfaz->conexion();
				$row = $interfaz->selectOne("usuarios","id_usuario",$id_usuario,$con);
				mysqli_close($con);
				?>

				<form id="form_update_usuario_ad" name="form_update_usuario_ad">
				<div class="h1 mt-4" align="center">Actualizar Usuario: <span style="color: <?php if ($row['tipo_usuario']==0) { echo "var(--adm);"; }elseif($row['tipo_usuario']==1){echo "var(--dir);"; }elseif($row['tipo_usuario']==2){echo "var(--sec);"; } ?>"><?php echo $row['nick_usuario']; ?></span> <span style="color: var(--green);">ID: <?php echo $row['id_usuario']; ?></span></div>
					<div class="form-group row justify-content-center" align="center">
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" value="<?php echo $row['nombre_usuario']; ?>">
							<input type="hidden" id="id_usuario" value="<?php echo $row['id_usuario']; ?>">
							<input type="hidden" id="nick_usuario_old" value="<?php echo $row['nick_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Apellido Solo debe llevar Letras." autocomplete="off" name="apellido_usuario" value="<?php echo $row['apellido_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="tipo_usuario" class="label-regis-cer">Tipo de Usuario</label>
							<select name="tipo_usuario" id="tipo_usuario" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" style="cursor: pointer;" required>
								<?php 
								if ($row['tipo_usuario'] == 0) {
									?>
									<option value="<?php echo $row['tipo_usuario']; ?>"><?php 
									if ($row['tipo_usuario'] == 0) {
										echo "Administrador";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="2">Secretaria/o</option>
									<option value="1">Director</option>
									<?php
								}if ($row['tipo_usuario'] == 1) {
									?>
									<option value="<?php echo $row['tipo_usuario'];  ?>"><?php 
									if ($row['tipo_usuario'] == 1) {
										echo "Director";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="2">Secretaria/o</option>
									<option value="0">Administrador</option>
									<?php
								}if ($row['tipo_usuario'] == 2) {
									?>
									<option value="<?php echo $row['tipo_usuario'];  ?>"><?php 
									if ($row['tipo_usuario'] == 2) {
										echo "Secretaria/o";
									}
									 ?></option>
									<option value="">Seleccione</option>
									<option value="1">Director</option>
									<option value="0">Administrador</option>
								<?php
								}
								 ?>
							</select>
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
							<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" value="<?php echo $row['nick_usuario']; ?>" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="contra_usuario" id="contra" class="label-regis-cer">Contraseña</label>
							<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" value="<?php echo $row['contra_usuario']; ?>">
						</div>
						<div class="col-11 col-md-5 mt-3" align="center">
							<label for="contra_usuario_confirm" id="contra1" class="label-regis-cer">Confirme Contraseña</label>
							<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" value="<?php echo $row['contra_usuario']; ?>" placeholder="Repita la Contraseña">
						</div>
						<?php if ($_SESSION['nick_usuario'] != $row['nick_usuario']){ ?>
							<div class="row justify-content-center" style="width: 100%;">
								<div class="col-12 col-md-5 mt-3">
									<label for="status_usuario" class="label-regis-cer">Estado</label>
									<select name="status_usuario" id="status_usuario" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> <?php if($row['status_usuario'] == 0){echo "eliminado";}elseif($row['status_usuario'] == 1){echo "activo";} ?>" style="cursor: pointer;" required>
							<?php   if ($row['status_usuario'] == 0) {
											echo "
											<option value='0' class='eliminado option-el'>Eliminado</option>
											<option value='1' class='activo option-ac'>Activo</option>
											";
										}elseif ($row['status_usuario'] == 1) {
											echo "
											<option value='1' class='activo option-ac'>Activo</option>
											<option value='0' class='eliminado option-el'>Eliminado</option>
											";
										}?>
									</select>			
								</div>
							</div>
						<?php } ?>
						<div class="col-10 col-md-5 mt-4">
							<button class="btn btn-submit-update-user-ad btn-primary btn-block" type="submit">Guardar</button>
						</div>
						<div class="col-10 col-md-5 mt-4">
							<button class="btn ir-lista-usuarios btn-primary btn-block">Ir a Lista de Usuario</button>
						</div>
					</div>
				</form>

				<?php
			}
		}elseif ($_SESSION['tipo_usuario'] == 1 ) {
			
			$interfaz = new Interfaz;
			$con = $interfaz->conexion();
			$row = $interfaz->selectOne("usuarios","id_usuario",$id_usuario,$con);
			mysqli_close($con);

			?>


			<form id="form_update_usuario_dir" name="form_update_usuario_dir">
			<div class="h1 mt-4" align="center">Actualizar Usuario: <span style="color: <?php if ($row['tipo_usuario']==0) { echo "var(--adm);"; }elseif($row['tipo_usuario']==1){echo "var(--dir);"; }elseif($row['tipo_usuario']==2){echo "var(--sec);"; } ?>"><?php echo $row['nick_usuario']; ?></span></div>
				<div class="form-group row justify-content-center" align="center">
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="nombre_usuario" class="label-regis-cer">Nombre</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Nombre Solo debe llevar Letras." autocomplete="off" name="nombre_usuario" value="<?php echo $row['nombre_usuario']; ?>">
						<input type="hidden" id="id_usuario" value="<?php echo $row['id_usuario']; ?>">
						<input type="hidden" id="nick_usuario_old" value="<?php echo $row['nick_usuario']; ?>">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="apellido_usuario" class="label-regis-cer">Apellido</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido_usuario" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="15" title="El Apellido Solo debe llevar Letras." autocomplete="off" name="apellido_usuario" value="<?php echo $row['apellido_usuario']; ?>">
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label id="nick" for="nick_usuario" class="label-regis-cer">Nombre de Usuario</label>
						<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nick_usuario" name="nick_usuario" value="<?php echo $row['nick_usuario']; ?>" autocomplete="off" required pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s 0-9]+" maxlength="10" minlength="4" title="El Nombre de Usuario solo puede llevar letras y numeros. (Minimo 4 Caracteres)">	
					</div>
					<div class="col-11 col-md-5 mt-3" align="center">
						<label for="contra_usuario" id="contra" class="label-regis-cer">Contraseña</label>
						<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario" required autocomplete="off" name="contra_usuario" value="<?php echo $row['contra_usuario']; ?>">
					</div>
					<div class="col-12">
						<div class="row justify-content-center">
							<div class="col-11 col-md-10 mt-3" align="center">
								<label for="contra_usuario_confirm" id="contra1" class="label-regis-cer">Confirme Contraseña</label>
								<input type="password" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="contra_usuario_confirm" required autocomplete="off" name="contra_usuario_confirm" value="<?php echo $row['contra_usuario']; ?>" placeholder="Repita la Contraseña">
							</div>
						</div>
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn btn-submit-update-user-dir btn-primary btn-block" type="submit">Guardar</button>
					</div>
					<div class="col-10 col-md-5 mt-4">
						<button class="btn ir-lista-usuarios btn-primary btn-block">Ir a Lista de Usuario</button>
					</div>
				</div>
			</form>


			<?php
		}
	}

	//Boton de Buscar Certificaciones

	public function searchCertificacion()
	{
		$tema = $_SESSION['tema'];
		?>
		<div class="buscar-cer">
			<div class="form-group" align="center">
			<div class="h1" >Buscar Estudiante</div>
				<form id="form_search_cedula" name="form_search_cedula">
					<h3>
						<small class="text-muted">Ingrese la cedula del estudiante</small>
					</h3>
					<input type="search" id="input_search_cedula" name="input_search_cedula" class="search-cer" required title="Ingrese la cedula aquí. (Solo numeros)" pattern="^[0-9]+" autocomplete="off" minlength="6" maxlength="8" placeholder="Cedula">
					<button class="search-cer-button" type="submit">Buscar</button>
				</form>
				<hr style="margin-bottom: 0;">
					<h3 class="mt-1">
						<small class="text-muted">Buscar por orden de fecha</small>
					</h3>
				<form id="form_search_fechas" name="form_search_fechas" class="form-inline justify-content-center">
					<div class="col-6">
						<label for="buscar-estudiante-start">Desde</label>
						<input type="date" id="buscar-estudiante-start" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" required>
					</div>
					<div class="col-6">
						<label for="buscar-estudiante-end">Hasta</label>
						<input type="date" id="buscar-estudiante-end" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" required>
					</div>
					<div class="col-5 mt-2" align="center">
						<button class="boton-search-fecha" type="submit">Buscar</button>
					</div>
				</form>
			</div>
		</div>
		<?php
		http_response_code(200);
	}

	// Lista de resultado de la busqueda del estudiante

	// Por cedula:
	public function busquedaPorCedula($cedula,$con)
	{

		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$buscarCedula = $interfaz->selectSQL("SELECT estudiantes.id_estudiante, estudiantes.id_usuario, estudiantes.nombre, estudiantes.apellido, estudiantes.nacionalidad, estudiantes.cedula, usuarios.id_usuario, usuarios.nick_usuario, usuarios.nombre_usuario, DATE_FORMAT(estudiantes.fecha_registro, '%r') as hora, estudiantes.fecha_registro FROM estudiantes INNER JOIN usuarios ON estudiantes.id_usuario=usuarios.id_usuario WHERE cedula = $cedula",$con);

		$id_usuarioo = $_SESSION['id_usuario'];

		$insert_busqueda_x_cedula_md_busqueda = $con->query("INSERT INTO `busqueda_x_cedula_md_busqueda`(`id_busqueda_x_c`, `id_usuario`, `num_ced`, `fecha`) VALUES (NULL, '$id_usuarioo', '$cedula', CURRENT_TIMESTAMP)");

		mysqli_close($con);

		if ($buscarCedula->num_rows > 0)
		{
			$row = $buscarCedula->fetch_assoc();
			http_response_code(202);
			?>
			<div class="row pt-2">
				<div class="col-12">
					<div class="h3" align="center">Resultatado de la Cedula: <span style="color: var(--success);"><?php echo $row['cedula']; ?></span></div>
				</div>
			</div>
			<div class="table-responsive" align="center">
				<table class="table" align="center">
					<thead class="thead-default">
						<tr style="background: rgba(51,51,51, .9); color: #fff;">
							<th>ID</th>
							<th>Registrado por</th>
							<th>Cedula</th>
							<th>Nombre(s)</th>
							<th>Apellido(s)</th>
							<th>Fecha de Registro</th>
						</tr>
					</thead>
					<tbody>
						<tr style="font-size: 14px;">
							<th style="vertical-align: middle;">ID <?php echo $row['id_estudiante']; ?></th>
							<th style="vertical-align: middle;"><?php echo $row['nombre_usuario']; ?>, Nick_User: "<?php echo $row['nick_usuario']; ?>" ID: <?php echo $row['id_usuario']; ?></th>
							<th style="color: var(--success);vertical-align: middle;">
								<?php 

								if ($row['nacionalidad'] == "venezolana") {
									
									echo "V-".$row['cedula']."";

								}elseif ($row['nacionalidad'] == "extranjera") {
									
									echo "E-".$row['cedula']."";

								}
								 ?>
							</th>
							<th style="vertical-align: middle;"><?php echo $row['nombre']; ?></th>
							<th style="vertical-align: middle;"><?php echo $row['apellido']; ?></th>
							<th style="vertical-align: middle;"><?php

							$fecha_array_1 = explode(" ", $row['fecha_registro'] );

							$fecha_array_2 = explode("-", $fecha_array_1[0]);

							$fecha_arreglada = "".$fecha_array_2[2]."-".$fecha_array_2[1]."-".$fecha_array_2[0]." ".$row['hora']."";

							echo $fecha_arreglada; 


							 ?></th>
							<?php

							echo "<th class='btn-modifcar-estudiante' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Modificar Datos</th>";

							echo "<th class='btn-generar-certificado'data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Certificado Final</th>";

							echo "<th class='btn-generar-acta-buena-conducta' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Acta de Buena Conduta</th>";

							echo "<th class='btn-generar-constancia-prosecucion' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Constancia de Prosecución</th>";

							 ?>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row justify-content-center pb-2" align="center">
				<div class="col-12 mt-2 col-md-5" align="center">
					<button class="btn retroceder-menu-search btn-secondary">Volver al Menu Anterior</button>
				</div>
			</div>
			

			<?php
			
		}
		else
		{
			http_response_code(404);
		}

	}

	// Por fechas:

	public function busquedaPorFecha($fecha_start,$fecha_end,$con)
	{

		$horas_comienzo = $fecha_start.' 00:00:00';
		$horas_fin = $fecha_end.' 23:59:59';
		$tema = $_SESSION['tema'];

		$id_usuarioa = $_SESSION['id_usuario'];

		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$buscarPorFecha = $interfaz->selectSQL("SELECT estudiantes.id_estudiante, estudiantes.id_usuario, estudiantes.nombre, estudiantes.apellido, estudiantes.nacionalidad, estudiantes.cedula, usuarios.id_usuario, usuarios.nick_usuario, usuarios.nombre_usuario, DATE_FORMAT(estudiantes.fecha_registro, '%r') as hora, DATE_FORMAT(estudiantes.fecha_registro, '%d') as fecha_dia, DATE_FORMAT(estudiantes.fecha_registro, '%m') as fecha_mes, DATE_FORMAT(estudiantes.fecha_registro, '%Y') as year FROM estudiantes INNER JOIN usuarios ON estudiantes.id_usuario=usuarios.id_usuario WHERE estudiantes.fecha_registro BETWEEN '$horas_comienzo' AND '$horas_fin'",$con);

		$insert_busqueda_x_fecha_md_busqueda = $con->query("INSERT INTO `busqueda_x_fecha_md_busqueda`(`id_busqueda_x_f`, `id_usuario`, `fecha_i`, `fecha_f`, `fecha`) VALUES (NULL,'$id_usuarioa','$fecha_start','$fecha_end',CURRENT_TIMESTAMP)");

		mysqli_close($con);

		if ($buscarPorFecha->num_rows > 0)
		{

			http_response_code(202);
			?>
			<div class="row pt-2">
				<div class="col-12">
					<div class="h3" align="center">
						<?php 

						if ($fecha_start == $fecha_end) {
							
							$fecha_arreglada = explode("-", $fecha_start);

							$convertido = "$fecha_arreglada[2]-$fecha_arreglada[1]-$fecha_arreglada[0]";

							if ($tema == 1) {
								echo "Resultatado de la busqueda del <span style='color: var(--success);'>$convertido</span>";
							} elseif ($tema == 0) {
								echo "Resultatado de la busqueda del <span style='color: var(--color-e);'>$convertido</span>";
							}							

						}else{

							$fecha_arreglada = explode("-", $fecha_start);

							$fecha_arreglada1 = explode("-", $fecha_end);

							$convertido = "$fecha_arreglada[2]-$fecha_arreglada[1]-$fecha_arreglada[0]";

							$convertido1 = "$fecha_arreglada1[2]-$fecha_arreglada1[1]-$fecha_arreglada1[0]";
							if ($tema == 1) {
								echo "Resultatado de la busqueda del <span style='color: var(--success);'>$convertido</span> al <span style='color: var(--success);'>$convertido1</span>";
							} elseif ($tema == 0) {
								echo "Resultatado de la busqueda del <span style='color: var(--color-e);'>$convertido</span> al <span style='color: var(--color-e);'>$convertido1</span>";
							}
							
						}

						 ?>						
					</div>
				</div>
			</div>
			<div class="row justify-content-center pb-2" align="center">
				<div class="col-12 col-md-5" align="center">
					<button class="btn retroceder-menu-search btn-secondary">Volver al Menu Anterior</button>
				</div>
			</div>
			<div class="table-responsive" align="center">
				<table class="table" align="center">
					<thead class="thead-default">
						<tr style="background: rgba(51,51,51, .9); color: #fff;">
							<th>ID</th>
							<th>Registrado por</th>
							<th>Cedula</th>
							<th>Nombre(s)</th>
							<th>Apellido(s)</th>
							<th>Fecha de Registro</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($row = mysqli_fetch_assoc($buscarPorFecha)) {
						?>
						<tr style="font-size: 14px;">
							<th style="vertical-align: middle;">ID <?php echo $row['id_estudiante']; ?></th>
							<th style="vertical-align: middle;"><?php echo $row['nombre_usuario']; ?>, Nick_User: "<?php echo $row['nick_usuario']; ?>" ID: <?php echo $row['id_usuario']; ?></th>
							<th style="color: var(--success);vertical-align: middle;">
								<?php 

								if ($row['nacionalidad'] == "venezolana") {
									
									echo "V-".$row['cedula']."";

								}elseif ($row['nacionalidad'] == "extranjera") {
									
									echo "E-".$row['cedula']."";

								}
								 ?>
							</th>
							<th style="vertical-align: middle;"><?php echo $row['nombre']; ?></th>
							<th style="vertical-align: middle;"><?php echo $row['apellido']; ?></th>
							<th style="vertical-align: middle;"><?php echo $row['fecha_dia']; echo "-".$row['fecha_mes']."-"; echo "".$row['year']." "; echo $row['hora']; ?></th>
							<?php

							echo "<th class='btn-modifcar-estudiante' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Modificar Datos</th>";

							echo "<th class='btn-generar-certificado' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Certificado Final</th>";

							echo "<th class='btn-generar-acta-buena-conducta' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Acta de Buena Conduta</th>";

							echo "<th class='btn-generar-constancia-prosecucion' data-id='".$row['id_estudiante']."' style='vertical-align: middle;'>Generar Constancia de Prosecución</th>";

							 ?>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="row justify-content-center pb-2" align="center">
				<div class="col-12 mt-2 pb-2 col-md-5" align="center">
					<button class="btn retroceder-menu-search btn-secondary">Volver al Menu Anterior</button>
				</div>
			</div>
			

			<?php
		}
		else
		{
			http_response_code(404);
		}
	}

	// Interfaz para editar estudiante

	public function modificarEstudiante($id_estudiante,$con)
	{
		$interfaz = new Interfaz;
		$con = $interfaz->conexion();
		$row = $interfaz->selectOne("estudiantes","id_estudiante",$id_estudiante,$con);
		mysqli_close($con);
		$tema = $_SESSION['tema'];

		?>

		<form id="update_estudiante" name="update_estudiante">
		<div class="h3 col-12 titulo-crear-cer" align="center">Editar Estudiante: <span style="<?php if($tema == 1){echo "color: var(--success);";}elseif($tema == 0){echo "color:var(--color-e);";} ?>"><?php echo $row['nombre']; ?></span></div>
			<div class="form-group row justify-content-center" align="center">
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="nombre" class="label-regis-cer">Nombre(s)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="nombre" name="nombre" placeholder="Nombre" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="40" autocomplete="off" title="El Nombre solo puede llevar letras con acentos y espacios." required value="<?php echo $row['nombre']; ?>">
					<input type="hidden" id="id_estudiante" name="id_estudiante" value="<?php echo $row['id_estudiante']; ?>">
					<input type="hidden" id="cedula_old" name="cedula_old" value="<?php echo $row['cedula']; ?>">
				</div>

				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="apellido" class="label-regis-cer">Apellido(s)</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="apellido" name="apellido" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" maxlength="40" autocomplete="off" title="El Apellido solo puede llevar letras con acentos y espacios." placeholder="Apellido" required autocomplete="off" value="<?php echo $row['apellido'] ?>">
				</div>
				<div class="col-11 col-md-5 mt-3">
					<label for="nacionalidad" class="label-regis-cer">Nacionalidad</label>
					<select  id="nacionalidad" name="nacionalidad" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" required>
						<?php 

						if ($row['nacionalidad'] == "venezolana")
						{

							echo '<option value="venezolana">Venezolana</option>
								<option value="extranjera">Extranjera</option>';
						
						}
						elseif ($row['nacionalidad'] == "extranjera")
						{

							echo '<option value="extranjera">Extranjera</option>
							<option value="venezolana">Venezolana</option>';
						}
						else
						{

							echo '<option value="venezolana">Venezolana</option>
								<option value="extranjera">Extranjera</option>';
						}


						 ?>
					</select>
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="cedula" class="label-regis-cer">Cedula del Estudiante</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="cedula" name="cedula" pattern="[0-9]+" minlength="6" maxlength="8" title="La Cedula solo puede llevar Numeros." placeholder="Cedula" required autocomplete="off" value="<?php echo $row['cedula']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="fecha_nacimiento_estudiante" class="label-regis-cer">Fecha de Nacimiento del Estudiante</label>
						<input type="date" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="fecha_nacimiento_estudiante" name="fecha_nacimiento_estudiante" required autocomplete="off" value="<?php echo $row['fecha_nacimiento_estudiante']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="lugar_nacimiento" class="label-regis-cer">Lugar de Nacimineto</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="lugar_nacimiento" name="lugar_nacimiento" placeholder="Municipio, Estado" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ,\s]+" title="Solo Letras, Espacios, comas." maxlength="40" required autocomplete="off" value="<?php echo $row['lugar_nacimiento']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3" align="center">
					<label for="literal" class="label-regis-cer">Literal del Estudiante</label>
					<input type="text" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?> label-regis-cer" id="literal" name="literal" pattern="[A-Z]+" maxlength="1" placeholder="Letra (Mayuscula)." title="Solo una Letra Mayuscula" required autocomplete="off" value="<?php echo $row['literal']; ?>">
				</div>
				<div class="col-11 col-md-5 mt-3">
					<label for="periodo_escolar">Periodo Escolar</label>
					<input type="text" id="periodo_escolar" name="periodo_escolar" class="form-control <?php if($tema == 1){echo "dark-inputs";} ?>" title="Solo numeros y un guion como lo muestra en el ejemplo." maxlength="9" minlength="9" placeholder="Ejem: 2019-2020" required="-" pattern="[0-9--]+" autocomplete="off" value="<?php echo $row['periodo_escolar']; ?>">
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn btn-primary btn-block" type="submit">Guardar</button>
				</div>
				<div class="col-12 col-md-5 mt-4">
					<button class="btn retroceder-menu-search-only btn-primary btn-block">Regresar</button>
				</div>
			</div>
		</form>

		<?php
	}


}
 ?>