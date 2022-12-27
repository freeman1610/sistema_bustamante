(function (document,window) {

if (document.getElementById("adm")!=null)
	document.body.classList.add("adm")
else if (document.getElementById("dir")!=null)
	document.body.classList.add("dir")
else if (document.getElementById("sec")!=null)
	document.body.classList.add("sec")

//Boton de ir a Inicio

if (document.querySelector('.btn-inicio')!=null) {
	document.querySelector('.btn-inicio').addEventListener('click', () => {
		irInicio()
	});
}

async function irInicio(){
	let response = await fetch("../c/interfaz.php?q=inicio",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}
// Boton: ir Crear Certificacion
if (document.querySelector('.btn-crear-certificacion')!=null) {
	document.querySelector('.btn-crear-certificacion').addEventListener('click', () => {
		btnCertificaciones()
	});
}

async function btnCertificaciones(){
	let response = await fetch("../c/interfaz.php?q=registro_ac",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

// Boton: ir Registro de Actividades
if (document.querySelector('.btn-registro-actividad')!=null) {
	document.querySelector('.btn-registro-actividad').addEventListener('click', () => {
		btnRegistroActividades()
	});
}

async function btnRegistroActividades(){
	let response = await fetch("../c/interfaz.php?q=registros_act",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

// Boton ir a editar usuario

if (document.querySelector('.btn-editar-user')!=null) {
	document.querySelector('.btn-editar-user').addEventListener('click', () => {
		btnEditarUsuario()
	});
}
async function btnEditarUsuario() {
	let response = await fetch("../c/interfaz.php?q=editar_us",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}
// Buscar certificado
if (document.querySelector('.btn-search-certificado')!=null) {
	document.querySelector('.btn-search-certificado').addEventListener('click', () => {
		searchEstudiante()
	});
}
async function searchEstudiante() {
	let response = await fetch("../c/interfaz.php?q=buscar_cer",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

// Boton Agregar Usuarios

async function btnAgregarUser() {
	let response = await fetch("../c/interfaz.php?q=crear_us",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

// Boton Cerrar Sección

if (document.querySelector('.btn-cerrar-sesion')!=null) {
	document.querySelector('.btn-cerrar-sesion').addEventListener('click', () => {
		cerrarSeccion()
	});
}
async function cerrarSeccion(){
	let response = await fetch("../c/usuario.php?q=logout",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 200){
			window.location.href="../";
		}else {
			alert("error")
		}
	}
}

//Boton Guardar Usuario-Ad

async function btnGuardarUserAdmin(el) {
	
	let nombre = document.getElementById("nombre_usuario").value
	let apellido = document.getElementById("apellido_usuario").value
	let tipo_usuario = document.getElementById("tipo_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value

	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=save_us_ad&nombre_usuario="+nombre+"&apellido_usuario="+apellido+"&tipo_usuario="+tipo_usuario+"&nick_usuario="+nick_usuario+"&contra_usuario="+contra_usuario,{
			method: "POST"
		});
		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Registro exitoso.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnEditarUsuario();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				el.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}		
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		el.contra_usuario.focus()

	}
}

async function saveUserDir(el) {
	
	let nombre = document.getElementById("nombre_usuario").value
	let apellido = document.getElementById("apellido_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value

	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=save_us_dir&nombre_usuario="+nombre+"&apellido_usuario="+apellido+"&nick_usuario="+nick_usuario+"&contra_usuario="+contra_usuario,{
			method: "POST"
		});
		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Registro exitoso.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnEditarUsuario();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				el.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}		
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		el.contra_usuario.focus()

	}
}
async function editarUser(codigo) {
	let response = await fetch("../c/interfaz.php?q=update_user_i&id="+codigo,{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}
async function updateUserAd(ev) {

	let id_usuario = document.getElementById("id_usuario").value
	let nombre_usuario = document.getElementById("nombre_usuario").value
	let apellido_usuario = document.getElementById("apellido_usuario").value
	let tipo_usuario = document.getElementById("tipo_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let nick_usuario_old = document.getElementById("nick_usuario_old").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value
	let status_usuario = document.getElementById("status_usuario").value
	
	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=update_us_ad&id_usuario="+id_usuario+"&nombre_usuario="+nombre_usuario+"&apellido_usuario="+apellido_usuario+"&tipo_usuario="+tipo_usuario+"&nick_usuario="+nick_usuario+"&nick_usuario_old="+nick_usuario_old+"&contra_usuario="+contra_usuario+"&status_usuario="+status_usuario,{
			method: "POST"
		});

		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Actualización exitosa.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnListaUsuarios();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				ev.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}	
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		ev.contra_usuario.focus()

	}
}
async function updateUserAdI(ev) {

	let id_usuario = document.getElementById("id_usuario").value
	let nombre_usuario = document.getElementById("nombre_usuario").value
	let apellido_usuario = document.getElementById("apellido_usuario").value
	let tipo_usuario = document.getElementById("tipo_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let nick_usuario_old = document.getElementById("nick_usuario_old").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value
	
	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=update_us_ad_i&id_usuario="+id_usuario+"&nombre_usuario="+nombre_usuario+"&apellido_usuario="+apellido_usuario+"&tipo_usuario="+tipo_usuario+"&nick_usuario="+nick_usuario+"&nick_usuario_old="+nick_usuario_old+"&contra_usuario="+contra_usuario,{
			method: "POST"
		});

		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Actualización exitosa.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnListaUsuarios();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				ev.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}	
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		ev.contra_usuario.focus()

	}
}

async function updateUserDir(ev) {

	let id_usuario = document.getElementById("id_usuario").value
	let nombre_usuario = document.getElementById("nombre_usuario").value
	let apellido_usuario = document.getElementById("apellido_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let nick_usuario_old = document.getElementById("nick_usuario_old").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value
	
	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=update_us_dir&id_usuario="+id_usuario+"&nombre_usuario="+nombre_usuario+"&apellido_usuario="+apellido_usuario+"&nick_usuario="+nick_usuario+"&nick_usuario_old="+nick_usuario_old+"&contra_usuario="+contra_usuario,{
			method: "POST"
		});

		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Actualización exitosa.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnListaUsuarios();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				ev.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}	
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		ev.contra_usuario.focus()

	}
}

// UPDATE - SECRETARI@

async function updateUserSec(ev) {

	let id_usuario = document.getElementById("id_usuario").value
	let nombre_usuario = document.getElementById("nombre_usuario").value
	let apellido_usuario = document.getElementById("apellido_usuario").value
	let nick_usuario = document.getElementById("nick_usuario").value
	let nick_usuario_old = document.getElementById("nick_usuario_old").value
	let contra_usuario = document.getElementById("contra_usuario").value
	let contra_usuario_confirm = document.getElementById("contra_usuario_confirm").value
	
	if (contra_usuario == contra_usuario_confirm)
	{
		let response = await fetch("../c/usuario.php?q=update_user_sec&id_usuario="+id_usuario+"&nombre_usuario="+nombre_usuario+"&apellido_usuario="+apellido_usuario+"&nick_usuario="+nick_usuario+"&nick_usuario_old="+nick_usuario_old+"&contra_usuario="+contra_usuario,{
			method: "POST"
		});

		if(response.status == 200){
			let responseText = await response.json()
			if (responseText.code == 201){
				Swal.fire({
					title:"Actualización exitosa.",
					icon:"success",
					toast: true,
					position: 'bottom-start',
					timer: 5000,
					showConfirmButton: false,
					timerProgressBar: true
				});
				document.getElementById("centro").innerHTML = "";
				btnEditarUsuario();
			}
			else if(responseText.code == 1){
				document.getElementById("nick").innerText="El nombre de usuario ya esta usado, intenta con otro."
				document.getElementById("nick").style="color:red;font-size: 14px;"
				document.getElementById("nick_usuario").style="border-color:red"
				ev.nick_usuario.focus()
			}
			else{
				alert("Registro fallido")
			}

		}	
	}else{

		document.getElementById("contra").innerText="Las contraseñas deben coincidir"
		document.getElementById("contra").style="color:red"
		document.getElementById("contra1").style="color:red"
		document.getElementById("contra_usuario").style="border-color:red"
		document.getElementById("contra_usuario_confirm").style="border-color:red"
		ev.contra_usuario.focus()

	}
}

// Boton De Eliminar de La lista de Usuarios

async function deleteUser(id_usuario) {

	let response = await fetch("../c/usuario.php?q=delete&id_usuario="+id_usuario,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 201){
			Swal.fire({
				title:"Eliminación exitosa.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			btnListaUsuarios()
		}
		else
			alert("error")
	}

}

async function activarUser(id_usuario) {

	let response = await fetch("../c/usuario.php?q=active&id_usuario="+id_usuario,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 201){
			Swal.fire({
				title:"Activación exitosa.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			btnListaUsuarios()
		}
		else
			alert("error")
	}

}

async function btnListaUsuarios() {
	let response = await fetch("../c/interfaz.php?q=list_users",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}
async function btnAgregarUserAdmin(el) {
	let response = await fetch("../c/interfaz.php?q=crear_us",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

// Certificaciones


async function crearEstudiante()
{
	let response = await fetch("../c/interfaz.php?q=create_cer",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}


async function verDetallesUser(id_usuario)
{
	let response = await fetch("../c/interfaz.php?q=ver_detalles_user&id_usuario="+id_usuario,{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

async function guardarEstudiante(ev) {
	let nombre = document.getElementById('nombre').value
	let apellido = document.getElementById('apellido').value
	let nacionalidad = document.getElementById('nacionalidad').value
	let cedula = document.getElementById('cedula').value
	let fecha_na = document.getElementById('fecha_nacimiento_estudiante').value
	let lugar_nacimiento = document.getElementById('lugar_nacimiento').value
	let literal = document.getElementById('literal').value
	let periodo_escolar = document.getElementById('periodo_escolar').value

	let response = await fetch("../c/documentos.php?q=save_estudiante&nombre="+nombre+"&apellido="+apellido+"&cedula="+cedula+"&fecha_na="+fecha_na+"&lugar_nacimiento="+lugar_nacimiento+"&literal="+literal+"&nacionalidad="+nacionalidad+"&periodo_escolar="+periodo_escolar,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 201){
			Swal.fire({
				title:"Estudiante creado exitosamente,",
				text: "si quiere crear algún documento del Estudiante "+nombre+", dirijase a la interfaz de busqueda.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}else if (responseText.code == 1){
			Swal.fire({
				title:"La cedula del estudiante ya esta guardada en el registro,",
				text:"Buscala en la interfaz de busqueda para modificarla.",
				icon:"error",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			ev.cedula.focus()
		}
	}
}

// Actualizo al estudiante desde la busqueda

async function updateEstudiante(ev) {

	let id_estudiante = document.getElementById('id_estudiante').value
	let nombre = document.getElementById('nombre').value
	let apellido = document.getElementById('apellido').value
	let nacionalidad = document.getElementById('nacionalidad').value
	let cedula = document.getElementById('cedula').value
	let cedula_old = document.getElementById('cedula_old').value
	let fecha_na = document.getElementById('fecha_nacimiento_estudiante').value
	let lugar_nacimiento = document.getElementById('lugar_nacimiento').value
	let literal = document.getElementById('literal').value
	let periodo_escolar = document.getElementById('periodo_escolar').value

	let response = await fetch("../c/documentos.php?q=update_estudiante&id_estudiante="+id_estudiante+"&nombre="+nombre+"&apellido="+apellido+"&cedula="+cedula+"&cedula_old="+cedula_old+"&fecha_na="+fecha_na+"&lugar_nacimiento="+lugar_nacimiento+"&literal="+literal+"&nacionalidad="+nacionalidad+"&periodo_escolar="+periodo_escolar,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 201){
			Swal.fire({
				title:"Actualización exitosa del estudiante "+nombre+",",
				text: "Realice de nuevo la busqueda de la cedula "+cedula+" para ver los cambios.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			searchEstudiante()


		}else if (responseText.code == 1){
			Swal.fire({
				title:"La cedula del estudiante ya esta guardada en el registro,",
				text:"Buscala en la interfaz de busqueda para modificarla.",
				icon:"error",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			ev.cedula.focus()
		}
	}
}

async function guardarPlantilla (ev){

	let nombre_director = document.getElementById('nombre_director').value
	let apellido_director = document.getElementById('apellido_director').value
	let nacionalidad_director = document.getElementById('nacionalidad').value
	let genero = document.getElementById('genero').value
	let cedula_director = document.getElementById('cedula_director').value

	let response = await fetch("../c/documentos.php?q=save_plantilla&nombre_director="+nombre_director+"&apellido_director="+apellido_director+"&nacionalidad_director="+nacionalidad_director+"&genero="+genero+"&cedula_director="+cedula_director,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 201)
		{
			Swal.fire({
				title:"Plantilla creada exitosamente.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}
		else if (responseText.code == 2)
		{
			Swal.fire({
				title:"Plantilla Actualizadad exitosamente.",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}
		else if (responseText.code == 3)
		{
			Swal.fire({
				title:"No hubo ningun cambio.",
				icon:"warning",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}
	}

}

// Buscar Por Cedula -----------------------------------------------------

async function searchPorCedula(ev) {

	let search_cedula = document.getElementById('input_search_cedula').value

	let response = await fetch("../c/interfaz.php?q=search_cedula&cedula="+search_cedula,{
		method: "POST"
	});

	if(response.status == 202)

	{

		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText

	}

	else if (response.status == 404)

	{

		Swal.fire({
			title:"Error 404",
			text: 'No se ha encontrado ninguna coincidencia con la cedula "'+search_cedula+'", registre o verifique la cedula.',
			icon:"warning",
			timer: 10000,
			timerProgressBar: true
		});

	}

}

// Buscar Por Fechas -------------------------------------------------

async function searchPorFechas(ev) {

	let fecha_start = document.getElementById('buscar-estudiante-start').value

	let fecha_end = document.getElementById('buscar-estudiante-end').value

	// Cambio de posicion de la fecha Inicial:

	fecha_start_array = fecha_start.split("-");

	fecha_start_ordenada = ""+fecha_start_array['2']+"-"+fecha_start_array['1']+"-"+fecha_start_array['0']+"";

	fecha_end_array = fecha_end.split("-");

	fecha_end_ordenada = ""+fecha_end_array['2']+"-"+fecha_end_array['1']+"-"+fecha_end_array['0']+"";

	if (fecha_start > fecha_end)
	{
		
		Swal.fire({
			title:"No puedes realizar una busqueda desde el "+fecha_start_ordenada+" al "+fecha_end_ordenada+" porque no es logico",
			icon:"warning",
			toast: true,
			position: 'bottom-start',
			timer: 10000,
			showConfirmButton: false,
			timerProgressBar: true
		});
	}
	else
	{

		let response = await fetch("../c/interfaz.php?q=search_fecha&fecha_start="+fecha_start+"&fecha_end="+fecha_end,{
			method: "POST"
		});

		if(response.status == 202)

		{

			let responseText = await response.text()

			document.getElementById("centro").innerHTML = responseText

		}

		else if (response.status == 404)

		{

			Swal.fire({
				title:"No sea encontrado ningun registro entre los periodos de "+fecha_start_ordenada+" y "+fecha_end_ordenada,
				icon:"warning",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});

		}
		
	}

}

async function plantillaDocumento() {
	let response = await fetch("../c/interfaz.php?q=plantilla",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}


async function createGraphyBars() {
	let response = await fetch("../c/interfaz.php?q=estadistica_mes_docs",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 200)
		{
			document.getElementById('centro').innerHTML = '<div class="row justify-content-center"><div class="col-10 mt-3"><div class="h3" align="center">Estadisticas de Documentos Creados durante el presente Mes</div></div><div class="col-11 mt-2 mb-3 col-estadisticas-doc" align="center"><div id="cargar_barras" class="cargar_barras" style="overflow-x: auto;"></div></div><div class="col-11 mb-2" align="center"><h7><small class="text-muted">*NOTA: solo se mostrara los documentos hechos solo una vez, osea, si creas un documento nuevo de un mismo estudiante no se tomara encuenta.*</small></h7></div><div class="col-11 col-md-5 mb-3" align="center"><button class="btn return-cer btn-secondary">Regresar al Menu Anterior</button></div></div>'
		let tipo_doc_cerFinal_X = responseText.tipo_doc_cerFinal_X
		let tipo_doc_contCond_X = responseText.tipo_doc_contCond_X
		let tipo_doc_contProse_X = responseText.tipo_doc_contProse_X
		let array_fecha_cerFinal_Y = responseText.fecha_cerFinal_Y
		let array_fecha_contCond_Y = responseText.fecha_contCond_Y
		let array_fecha_contProse_Y = responseText.fecha_contProse_Y

		let trace1 = {
		  y: [tipo_doc_cerFinal_X.length],
		  x: [array_fecha_cerFinal_Y.toString()],
		  
		  name: 'Certificaciones Finales',
		  type: 'bar'
		};

		let trace2 = {
		  y: [tipo_doc_contCond_X.length],
		  x: [array_fecha_contCond_Y.toString()],
		  name: 'Constancia de Buena Conducta',
		  type: 'bar'
		};

		let trace3 = {
		  y: [tipo_doc_contProse_X.length],
		  x: [array_fecha_contProse_Y.toString()],
		  name: 'Constancia de Prosecución de Estudios',
		  type: 'bar',
		   marker: {
		      color: 'red'
		    }
		};

		let data = [trace1, trace2, trace3];

		let layout = {
			barmode: 'stack',
			title: 'Documentos Creados durante el presente Mes',
			xaxis: {
				title: 'Fecha'
			},
			yaxis: {
				title: 'Cantidad'
			}
		};

		Plotly.newPlot('cargar_barras', data, layout);
		}
		else if (responseText.code == 404)
		{
			Swal.fire({
				title:"No se ha encontrado ningun registro del mes actual",
				text: "(404)",
				icon:"warning",
				toast: true,
				position: 'bottom-start',
				timer: 8000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}
	}
}

// Generar Certificado Final

async function generarCertificadoFinal(id_estudiante) {

	let response = await fetch("../c/documentos.php?q=generar_certificado_final&id_estudiante="+id_estudiante,{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}

// Generar Acta de Buena Conducta

async function generarActaBuenaConducta(id_estudiante) {

	let response = await fetch("../c/documentos.php?q=generar_acta_buena_conducta&id_estudiante="+id_estudiante,{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}


async function generarConstanciaProsecucion(id_estudiante) {

	let response = await fetch("../c/documentos.php?q=generar_constancia_prosecucion&id_estudiante="+id_estudiante,{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}

async function createExamplePdfCerFinal() {

	let response = await fetch("../c/documentos.php?q=create_example_pdf_cer_final",{
		method: "POST"
	});
 
	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
		let responseCF = await fetch("../c/documentos.php?q=guardar_creacion_ejem_cer_final",{
			method: "POST"
		});
	}
}


async function createExamplePdfConsCond() {

	let response = await fetch("../c/documentos.php?q=create_example_pdf_cons_cond",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
		// Guardo el registro
		let responseCBC = await fetch("../c/documentos.php?q=guardar_creacion_ejem_const_conducta",{
			method: "POST"
		});
	}
}

async function createExamplePdfConsPrese() {

	let response = await fetch("../c/documentos.php?q=create_example_pdf_cons_prese",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
		let responseCPE = await fetch("../c/documentos.php?q=guardar_creacion_ejem_const_prose",{
			method: "POST"
		});
	}
}

async function createListUserAdm() {

	let response = await fetch("../c/documentos.php?q=create_list_user",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}


async function backupSystem() {

	let response = await fetch("../c/sistema.php?q=create_respald",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
		let responsess = fetch("../c/sistema.php?q=delete_zip_create_respald",{
			method: "POST"
		});
	}
}


async function pdfAccionesUser(id_usuario) {

	let response = await fetch("../c/documentos.php?q=create_pdf_accion_user&id_usuario="+id_usuario,{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}


async function pdfBusquedaXCedula() {

	let response = await fetch("../c/documentos.php?q=create_pdf_bxc",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}


async function pdfBusquedaXFechas() {

	let response = await fetch("../c/documentos.php?q=create_pdf_bxf",{
		method: "POST"
	});

	if(response.status == 200){
		let responseUrl = await response.url
		window.location.assign(responseUrl)
	}
}

async function modificarEstudiante(id_estudiante) {

	let response = await fetch("../c/interfaz.php?q=update_estudiante&id_estudiante="+id_estudiante,{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}


async function btnUpdateSecI() {

	let response = await fetch("../c/interfaz.php?q=update_user_sec_i",{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.text()
		document.getElementById("centro").innerHTML = responseText
	}
}

async function saveTema(valor) {

	let response = await fetch("../c/interfaz.php?q=tema&valor="+valor,{
		method: "POST"
	});
	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 0){
			Swal.fire({
				title:"Modo Normal",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}else if (responseText.code == 1) {
			Swal.fire({
				title:"Modo Oscuro",
				icon:"success",
				toast: true,
				position: 'bottom-start',
				timer: 5000,
				showConfirmButton: false,
				timerProgressBar: true
			});
		}
	}
}

// Sub-Botones

document.body.addEventListener('change', ev =>{
    if(ev.target.matches("#tema")){
    	if (ev.target.value == 0)
    	{
    		document.body.style="background: white; transition: 1s;"
			document.querySelector('#centro').style="background: #fff;transition: 1s;"
			document.querySelector('#tema').style="background: #fff;color: #000; transition: 1s;"
			let tema = 0;
			saveTema(tema)
    	}

    	if (ev.target.value == 1)
    	{
			document.body.style="background: rgba(0,0,0, .5); transition: 1s; color: #fff;"
			document.querySelector('#centro').style="background: #343a40;transition: 1s;"
			document.querySelector('#tema').style="background: rgba(0,0,0, .3);color: #fff;transition: 1s;"
			let tema = 1;
			saveTema(tema)
    	}
		
	}
	
});

document.body.addEventListener("click", e =>{


	if(e.target.matches(".agregar-user-ad")){
		btnAgregarUserAdmin()
	}

	if(e.target.matches(".btn-ver-pass")){
        let inputss = document.querySelectorAll('.pass-list')
        let btnssV = document.querySelectorAll('.btn-ver-pass')
        let btnssO = document.querySelectorAll('.btn-ocultar-pass')
        document.querySelector('.btn-ver-pass').dataset=inputss[e.target.dataset.id].attributes.type.value="text"
        document.querySelector('.btn-ver-pass').dataset=btnssV[e.target.dataset.id].style.display="none"
        document.querySelector('.btn-ocultar-pass').dataset=btnssO[e.target.dataset.id].style.display="block"
    }

    if(e.target.matches(".btn-ocultar-pass")){
        let inputss = document.querySelectorAll('.pass-list')
        let btnssV = document.querySelectorAll('.btn-ver-pass')
        let btnssO = document.querySelectorAll('.btn-ocultar-pass')
        document.querySelector('.btn-ver-pass').dataset=inputss[e.target.dataset.id].attributes.type.value="password"
        document.querySelector('.btn-ver-pass').dataset=btnssO[e.target.dataset.id].style.display="none"
        document.querySelector('.btn-ocultar-pass').dataset=btnssV[e.target.dataset.id].style.display="block"
    }

	if(e.target.matches(".create-cer")){
		crearEstudiante()
	}
	if(e.target.matches(".ir-searc")){
		searchEstudiante()
	}

	if(e.target.matches(".update-model-cer")){
		plantillaDocumento()
	}

	if(e.target.matches(".create-example-pdf-cer-final")){
		createExamplePdfCerFinal()
	}

	if(e.target.matches(".create-example-pdf-cons-cond")){
		createExamplePdfConsCond()
	}

	if(e.target.matches(".create-example-pdf-cons-prese")){
		createExamplePdfConsPrese()
	}

	if(e.target.matches(".regresar-menu-user-ce")){
		document.getElementById("centro").innerHTML=""
		btnEditarUsuario()
	}

	if(e.target.matches(".return-cer")){
		document.getElementById("centro").innerHTML=""
		btnCertificaciones()
	}

	if(e.target.matches(".create-graphy-bars")){
		createGraphyBars()
	}

	if(e.target.matches(".lista-usuarios")){
		btnListaUsuarios()
	}

	if(e.target.matches(".btn-i-edit-sec")){
		btnUpdateSecI()
	}

	if(e.target.matches(".retroceder-menu-search-only")){
		document.querySelector(".men").innerHTML=""
		document.querySelector(".men").appendChild(this.pageBack)
	}

	if(e.target.matches(".retroceder-menu-search")){
		searchEstudiante()
	}

	if(e.target.matches(".btn-modifcar-estudiante")){
		this.pageBack = document.importNode(document.querySelector(".centro"),true)
		modificarEstudiante(e.target.dataset.id)
	}

	if(e.target.matches(".btn-ver-acciones")){
		verDetallesUser(e.target.dataset.id)
	}

	if(e.target.matches(".create_list_user")){
		createListUserAdm()
	}

	if(e.target.matches(".btn-create-backup-system")){
		backupSystem()
	}

	if(e.target.matches(".retroceder-menu-us")){
		document.getElementById("centro").innerHTML=""
		btnEditarUsuario()
	}

	if(e.target.matches(".ir-lista-usuarios")){
		document.getElementById("centro").innerHTML=""
		btnListaUsuarios()
	}

	if(e.target.matches(".btn-generar-certificado")){
		generarCertificadoFinal(e.target.dataset.id)
	}

	if(e.target.matches(".btn-generar-acta-buena-conducta")){
		generarActaBuenaConducta(e.target.dataset.id)
	}

	if(e.target.matches(".btn-generar-constancia-prosecucion")){
		generarConstanciaProsecucion(e.target.dataset.id)
	}

	if(e.target.matches(".btn-editar-usuario")){
		editarUser(e.target.dataset.id)
	}

	if(e.target.matches(".generar-pdf-acciones-user")){
		pdfAccionesUser(e.target.dataset.id)
	}

	if(e.target.matches(".generar-pdf-busqueda-x-cedula")){
		pdfBusquedaXCedula()
	}

	if(e.target.matches(".generar-pdf-busqueda-x-fechas")){
		pdfBusquedaXFechas()
	}


	if(e.target.matches(".btn-delete-usuario")){
		let id_usuario = (e.target.dataset.id)
		let nick_usuario = document.body.querySelector('#nick_usuario').innerText
		Swal.fire({
			text:"¿Quieres eliminar al usuario: "+nick_usuario+"?",
			icon:"question",
			confirmButtonText: "Si",
			cancelButtonText: "No",
			showConfirmButton: true,
			showCancelButton: true
		});
		document.querySelector(".swal2-confirm").classList.add("confirmar-delete-user")
		document.querySelector(".swal2-confirm").setAttribute("data-id", id_usuario)
	}

	if(e.target.matches(".confirmar-delete-user")){
		deleteUser(e.target.dataset.id)
	}
		// Botones para ver mas / menos de la Interfaz de Registro de Actividades
	if(e.target.matches(".ver-mas-inicio-sesion")){
		let array_sesiones = document.querySelectorAll('.tr-none-i-s')

		let array_sesiones_num = document.querySelectorAll('.tr-none-i-s').length

		for(var i = 0; i < array_sesiones_num; i++){
				array_sesiones[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-inicio-sesion').style.display = 'none'
		document.querySelector('.ver-menos-inicio-sesion').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-inicio-sesion")){
		let array_sesiones = document.querySelectorAll('.tr-none-i-s')

		let array_sesiones_num = document.querySelectorAll('.tr-none-i-s').length

		for(var i = 0; i < array_sesiones_num; i++){
				array_sesiones[i].style='display: none;'
		}
		document.querySelector('.ver-menos-inicio-sesion').style.display = 'none'
		document.querySelector('.ver-mas-inicio-sesion').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-estu-modi")){
		let array_e_m = document.querySelectorAll('.tr-none-e-m')

		let array_e_m_num = document.querySelectorAll('.tr-none-e-m').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-estu-modi').style.display = 'none'
		document.querySelector('.ver-menos-estu-modi').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-estu-modi")){
		let array_e_m = document.querySelectorAll('.tr-none-e-m')

		let array_e_m_num = document.querySelectorAll('.tr-none-e-m').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-estu-modi').style.display = 'none'
		document.querySelector('.ver-mas-estu-modi').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-cedu-buscada")){
		let array_e_m = document.querySelectorAll('.tr-none-c-b')

		let array_e_m_num = document.querySelectorAll('.tr-none-c-b').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-cedu-buscada').style.display = 'none'
		document.querySelector('.ver-menos-cedu-buscada').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-cedu-buscada")){
		let array_e_m = document.querySelectorAll('.tr-none-c-b')

		let array_e_m_num = document.querySelectorAll('.tr-none-c-b').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-cedu-buscada').style.display = 'none'
		document.querySelector('.ver-mas-cedu-buscada').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-fecha-buscada")){
		let array_e_m = document.querySelectorAll('.tr-none-f-b')

		let array_e_m_num = document.querySelectorAll('.tr-none-f-b').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-fecha-buscada').style.display = 'none'
		document.querySelector('.ver-menos-fecha-buscada').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-fecha-buscada")){
		let array_e_m = document.querySelectorAll('.tr-none-f-b')

		let array_e_m_num = document.querySelectorAll('.tr-none-f-b').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-fecha-buscada').style.display = 'none'
		document.querySelector('.ver-mas-fecha-buscada').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-docu-regis-u")){
		let array_e_m = document.querySelectorAll('.tr-none-d-r-u')

		let array_e_m_num = document.querySelectorAll('.tr-none-d-r-u').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-docu-regis-u').style.display = 'none'
		document.querySelector('.ver-menos-docu-regis-u').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-docu-regis-u")){
		let array_e_m = document.querySelectorAll('.tr-none-d-r-u')

		let array_e_m_num = document.querySelectorAll('.tr-none-d-r-u').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-docu-regis-u').style.display = 'none'
		document.querySelector('.ver-mas-docu-regis-u').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-docu-regis")){
		let array_e_m = document.querySelectorAll('.tr-none-d-r')

		let array_e_m_num = document.querySelectorAll('.tr-none-d-r').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-docu-regis').style.display = 'none'
		document.querySelector('.ver-menos-docu-regis').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-docu-regis")){
		let array_e_m = document.querySelectorAll('.tr-none-d-r')

		let array_e_m_num = document.querySelectorAll('.tr-none-d-r').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-docu-regis').style.display = 'none'
		document.querySelector('.ver-mas-docu-regis').style.display = 'initial'
	}

	if(e.target.matches(".ver-mas-acti_user")){
		let array_e_m = document.querySelectorAll('.tr-none-a-u')

		let array_e_m_num = document.querySelectorAll('.tr-none-a-u').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: table-row;'
		}
		document.querySelector('.ver-mas-acti_user').style.display = 'none'
		document.querySelector('.ver-menos-acti_user').style.display = 'initial'
	}

	if(e.target.matches(".ver-menos-acti_user")){
		let array_e_m = document.querySelectorAll('.tr-none-a-u')

		let array_e_m_num = document.querySelectorAll('.tr-none-a-u').length

		for(var i = 0; i < array_e_m_num; i++){
				array_e_m[i].style='display: none;'
		}
		document.querySelector('.ver-menos-acti_user').style.display = 'none'
		document.querySelector('.ver-mas-acti_user').style.display = 'initial'
	}
	
	if(e.target.matches(".btn-activar-usuario")){
		let id_usuario = (e.target.dataset.id)
		let nick_usuario = document.body.querySelector('#nick_usuario').innerText
		Swal.fire({
			text:"¿Quieres activar al usuario: "+nick_usuario+"?",
			icon:"question",
			confirmButtonText: "Si",
			cancelButtonText: "No",
			showConfirmButton: true,
			showCancelButton: true
		});
		document.querySelector(".swal2-confirm").classList.add("confirmar-activar-user")
		document.querySelector(".swal2-confirm").setAttribute("data-id", id_usuario)
	}
	if(e.target.matches(".confirmar-activar-user")){
		activarUser(e.target.dataset.id)
	}

	if(e.target.matches(".option-el")){
		document.getElementById('status_usuario').classList.remove("activo")
		document.getElementById('status_usuario').classList.add("eliminado")
	}else if (e.target.matches(".option-ac")) {
		document.getElementById('status_usuario').classList.remove("eliminado")
		document.getElementById('status_usuario').classList.add("activo")
	}
});

// To all Forms
document.querySelector("#centro").addEventListener("submit", ev =>{
	
	if(ev.target.matches('#form_guardar_usuario_ad')){
		ev.preventDefault()
		btnGuardarUserAdmin(ev.target)
	}

	if(ev.target.matches('#form_crear_user')){
		ev.preventDefault()
		saveUserDir(ev.target)
	}
	
	if(ev.target.matches('#form_update_usuario_ad')){
		ev.preventDefault()
		updateUserAd(ev.target)
	}

	if(ev.target.matches('#form_update_usuario_ad_i')){
		ev.preventDefault()
		updateUserAdI(ev.target)
	}

	if(ev.target.matches('#form_update_usuario_dir')){
		ev.preventDefault()
		updateUserDir(ev.target)
	}

	if(ev.target.matches('#form_update_secretario')){
		ev.preventDefault()
		updateUserSec(ev.target)
	}
	
	if(ev.target.matches('#guardar_cer')){
		ev.preventDefault()
		guardarEstudiante(ev.target)
	}
	
	if(ev.target.matches('#guardar_plantilla')){
		ev.preventDefault()
		guardarPlantilla(ev.target)
	}

	if(ev.target.matches('#form_search_cedula')){
		ev.preventDefault()
		searchPorCedula(ev.target)
	}

	if(ev.target.matches('#form_search_fechas')){
		ev.preventDefault()
		searchPorFechas(ev.target)
	}

	if(ev.target.matches('#update_estudiante')){
		ev.preventDefault()
		updateEstudiante(ev.target)
	}
});


})(document,window);

window.onscroll = function(){
	if (document.documentElement.scrollTop > 100) {
		document.querySelector('.subir-boton').classList.add('show');
	}else {
		document.querySelector('.subir-boton').classList.remove('show');
	}
}
document.querySelector('.subir-boton').addEventListener('click', () => {
	window.scrollTo({
		top: 0,
		behavior: 'smooth'
	});
});