(function (document,window) {

document.getElementById('form_login').addEventListener('submit', (e) => {
	event.preventDefault()
	login(e.target)
});

async function login (el) {
	let nick_usuario = document.getElementById("nick_usuario").value
	let contra_usuario = document.getElementById("contra_usuario").value

	const response = await fetch("c/usuario.php?q=login&nick_usuario="+nick_usuario+"&contra_usuario="+contra_usuario,{
		method: "POST"
	});

	if(response.status == 200){
		let responseText = await response.json()
		if (responseText.code == 200){
			window.location.href="v/";
		}
		else if(responseText.code == 403){
			Swal.fire({
				text:"Usuario o Contraseña erroneo.",
				icon:"error",
				toast: true,
				position: 'bottom-start',
				timer: 4000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			el.nick_usuario.focus()
		}
		else if(responseText.code == 3){
			Swal.fire({
				title: "Tu cuenta ha sido suspendida,",
				text:"ponte en contacto con un Administrador/a o Director/a para más informacion.",
				icon:"warning",
				toast: true,
				position: 'bottom-start',
				timer: 10000,
				showConfirmButton: false,
				timerProgressBar: true
			});
			el.nick_usuario.focus()
		}
	}
	else{

		Swal.fire({
			text:"Hay una sección activa.",
			icon:"warning",
			allowOutsideClick:false,
			allowEscapeKey:false,
			allowEnterKey:false,
			showConfirmButton:true
		});
		document.body.addEventListener("click", e =>{
			if(e.target.matches(".swal2-confirm")){
				window.location.href="v/"
			}
		});
	}
		
}

})(document,window);