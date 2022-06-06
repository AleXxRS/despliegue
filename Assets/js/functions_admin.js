/* con esta funcion bloqueamos todas las teclas  y solo se permitira el ingreso de numeros  */

function controlTag(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; 
    else if (tecla==0||tecla==9)  return true;
    patron =/[0-9\s]/;
    n = String.fromCharCode(tecla);
    return patron.test(n); 
}

/* con esto solo permitimos el ingreso de texto tanto en mayuscula como en minusculas */
function testText(txtString){
    var stringText = new RegExp(/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜü\s]+$/);
    if(stringText.test(txtString)){
        return true;
    }else{
        return false;
    }
}

/* con este script solo validamos los numeros del 0 al 9 */
function testEntero(intCant){
    var intCantidad = new RegExp(/^([0-9])*$/);
    if(intCantidad.test(intCant)){
        return true;
    }else{
        return false;
    }
}

/* con este escrip validamos los correos electronicos que lleven un @ */

function fntEmailValidate(email){
    var stringEmail = new RegExp(/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/);
    if (stringEmail.test(email) == false){
        return false;
    }else{
        return true;
    }
}


/* con esta funcion capturamos la clase .validText que se encuentra en cada campo que se requiera texto esta clase se encuentra en el formulario
 para ingresar un nuevo usuario posteriormente recorremos los elementos con foreach  */
function fntValidText(){
	let validText = document.querySelectorAll(".validText");
    validText.forEach(function(validText) {
        validText.addEventListener('keyup', function(){ /* con el evento "keyup" hacemos que se ejecute la funcion cada vez que se deje de presionar la tecla  */
			let inputValue = this.value; /* con esto obtenemos el valor que se ingreso */
			if(!testText(inputValue)){ /* llamamos a la funcion "testText" que se encarga de validar si solo texto, si lo que se ingreso es incorrecto  */
				this.classList.add('is-invalid'); /* agregamos la clase  is-invalid con" classList.add" */
			}else{
				this.classList.remove('is-invalid'); /* si es correcto lo que se ingreso entonces le quitamos la clase com remove */
			}				
		});
	});
}


function fntValidNumber(){
	let validNumber = document.querySelectorAll(".validNumber");
    validNumber.forEach(function(validNumber) {
        validNumber.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!testEntero(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}


function fntValidEmail(){
	let validEmail = document.querySelectorAll(".validEmail");
    validEmail.forEach(function(validEmail) {
        validEmail.addEventListener('keyup', function(){
			let inputValue = this.value;
			if(!fntEmailValidate(inputValue)){
				this.classList.add('is-invalid');
			}else{
				this.classList.remove('is-invalid');
			}				
		});
	});
}

window.addEventListener('load', function() {
	fntValidText();
	fntValidEmail(); 
	fntValidNumber();
}, false);