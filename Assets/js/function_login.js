$('.login-content [data-toggle="flip"]').click(function () {
	$('.login-box').toggleClass('flipped');
	return false;
});

var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {
	if (document.querySelector("#formLogin")) {
		let formLogin = document.querySelector("#formLogin");
		formLogin.onsubmit = function (e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txtEmail').value;
			let strPassword = document.querySelector('#txtPassword').value;

			if (strEmail == "" || strPassword == "") /*si el emlail o la contraseña estan vacio se mostrara un mensaje de error  */ {
				swal("Por favor", "Escribe usuario y contraseñaa.", "error");
				return false;
			} else {
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); /* creamos el objeto  XMLHttpRequest */
				var ajaxUrl = base_url + '/Login/loginUser';  /* aqui nos dirijimos al contralador Login a la funcion loginUser */
				var formData = new FormData(formLogin); /* cremos el objeto formData  */
				request.open("POST", ajaxUrl, true); /* con open abrimos la conexion para que con post enviemos los datos */
				request.send(formData);

				console.log(request);

				request.onreadystatechange = function () {
					if (request.readyState != 4) return; /* si readyState es diferente de 4 entonces no hacemos nada y retornamos  */
					if (request.status == 200) { /* si status es igual a 200 entonces se ejecuta la funcion */
						var objData = JSON.parse(request.responseText); /* convertimos a formato json y lo almacenamos en objData */
						if (objData.status) /* si el status que viene en el array del formato json objData es igual a 1  */ {
							window.location = base_url + 'dashboard'; /* nos envia al dashboard */
						} else {
							/* si el estatus es diferente de 1 quire se ejecutara el else   */
							swal("Atención", objData.msg, "error");/* nos mostrara un me mensaje de error, con el mensaje que viene en el array convertido en json */
							document.querySelector('#txtPassword').value = "";/* tambien seleccionaremos el campo del txtPassword con su id y el valor de su campo lo limpiamos   */
						}
					} else /* el status al momento de enviar es diferente de 200(quiere decir que la peticion no es exitoso)  */ {
						swal("Atención", "Error en el proceso", "error");/* nos mostrara un mensaje de error  */
					}
					divLoading.style.display = "none";
					return false;


				}
			}
		}
	}


	/* codigo mio */
	if (document.querySelector("#formRecetPass")) {
		let formRecetPass = document.querySelector("#formRecetPass");
		formRecetPass.onsubmit = function (e) {
			e.preventDefault();

			let strEmail = document.querySelector('#txtEmailReset').value;
			if (strEmail == "") {
				swal("Por favor", "Escribe tu correo electrónico.", "error");
				return false;
			} else {
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ?
					new XMLHttpRequest() :
					new ActiveXObject('Microsoft.XMLHTTP');

				var ajaxUrl = base_url + '/Login/resetPass';
				var formData = new FormData(formRecetPass);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {

					if (request.readyState != 4) return;

					if (request.status == 200) { /* si es estatus es 200 quiere decir que que estamos recibiendo una respuesta del servidor */
						var objData = JSON.parse(request.responseText);

						if (objData.status) {  /* si en el array el status es 1 quiere decir que el usuario esta activo */
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Aceptar",
								closeOnConfirm: false, /* con esto indicamos que no se va a cerrar la alerta hasta que le demos en confirmar */
							}, function (isConfirm) {
								if (isConfirm) {
									window.location = base_url;

								}
							});
						} else {
							swal("Atención", objData.msg, "error"); /*  si el estatus es diferente de 1 quiere decir que ocurrio un error  */
						}
					} else {
						swal("Atención", "Error en el proceso", "error");/* si existio algun error en el servidor un 400 o etc nos mostrara este mensaje */
					}
					/* divLoading.style.display = "none";
					return false; */
				}
			}
		}
	}

	if (document.querySelector("#formCambiarPass")) {
		let formCambiarPass = document.querySelector("#formCambiarPass");
		formCambiarPass.onsubmit = function (e) {
			e.preventDefault();

			let strPassword = document.querySelector('#txtPassword').value;
			let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;
			let idUsuario = document.querySelector('#idUsuario').value;

			if (strPassword == "" || strPasswordConfirm == "") {
				swal("Por favor", "Escribe la nueva contraseña.", "error");
				return false;
			} else {
				if (strPassword.length < 5) { /* aqui verificamos que la contraseña sea mayor a 5 caracteres */
					swal("Atención", "La contraseña debe tener un mínimo de 5 caracteres.", "info");
					return false;
				}
				if (strPassword != strPasswordConfirm) { /* aqui verfificamos que la contraseña coincida con la confirmacion de contraswña */
					swal("Atención", "Las contraseñas no son iguales.", "error");
					return false;
				}
				divLoading.style.display = "flex";
				var request = (window.XMLHttpRequest) ?
					new XMLHttpRequest() :
					new ActiveXObject('Microsoft.XMLHTTP');
				var ajaxUrl = base_url + '/Login/setPassword';
				var formData = new FormData(formCambiarPass);
				request.open("POST", ajaxUrl, true);
				request.send(formData);
				request.onreadystatechange = function () {
					if (request.readyState != 4) return;
					if (request.status == 200) {
						var objData = JSON.parse(request.responseText);
						if (objData.status) {
							swal({
								title: "",
								text: objData.msg,
								type: "success",
								confirmButtonText: "Iniciar sessión",
								closeOnConfirm: false,
							}, function (isConfirm) {
								if (isConfirm) {
									window.location = base_url + 'login';
								}
							});
						} else {
							swal("Atención", objData.msg, "error");
						}
					} else {
						swal("Atención", "Error en el proceso", "error");
					}
					divLoading.style.display = "none";
				}
			}
		}
	}

}, false);