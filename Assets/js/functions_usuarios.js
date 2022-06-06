var tableUsuarios;
document.addEventListener('DOMContentLoaded', function(){

    /* com esta segmento hacemos que se vea la tabla con sus datos  */
    tableUsuarios = $('#tableUsuarios').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Usuarios/getUsuarios",
            "dataSrc":""
        },
        "columns":[
            {"data":"id_persona"},
            {"data":"identificacion"},
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"email_user"},
            {"data":"telefono"},
            {"data":"nombre_rol"},
            {"data":"status"},
            {"data":"options"}
        ],
        /* estos son los botones para la exportacion a excel pdf etc */
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class='far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary"
            },{
                "extend": "excelHtml5",
                "text": "<i class='fas fa-file-excel'></i> Excel",
                "titleAttr":"exportar a Excel",
                "className": "btn btn-success"
            },{
                "extend": "pdfHtml5",
                "text": "<i class='fas fa-file-pdf'></i> PDF",
                "titleAttr":"exportar a PDF",
                "className": "btn btn-danger"
            },{
                "extend": "csvHtml5",
                "text": "<i class='fas fa-file-csv'></i> CSV",
                "titleAttr":"exportar a CSV",
                "className": "btn btn-info"
            }
        ],
        "resonsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 3,
        "order":[[0,"desc"]]  
    });

    
    var formUsuario = document.querySelector("#formUsuario");
    formUsuario.onsubmit = function(e) {

		e.preventDefault(); /* con esto evitamos que al momento de darle click al boton guardar que se refresque  */

		/* estas variables vienen de los campos de formularios */
		var strIdentificacion = document.querySelector('#txtIdentificacion').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strApellido = document.querySelector('#txtApellido').value;
        var strEmail = document.querySelector('#txtEmail').value;
        var intTelefono = document.querySelector('#txtTelefono').value;
        var intTipousuario = document.querySelector('#listRolid').value;
        var strPassword = document.querySelector('#txtPassword').value;
        
        /* creamos una validacion " || " este operador es o hacemos una validacion si los campos estan vacios nos retornara un mensaje  */
        
        if(strIdentificacion == '' || strApellido == '' || strNombre == '' || strEmail == '' || intTelefono == '' || intTipousuario == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error"); /* swal es la libreria para usar mensajes  */
            return false;
        }
        

 /* con esta funcion hacemos que mientras haya un campo en rojo , si hay un campo en rojo no se enviaran los datos para modificar o guardar nuevo usuario y se mostrara un mensaje*/
        let elementsValid = document.getElementsByClassName("valid"); /* con let idicamos que esa variable solo va a ser usada dentre de esta funcion  */
        for (let i = 0; i < elementsValid.length; i++) { 
            if(elementsValid[i].classList.contains('is-invalid')) { 
                swal("Atención", "Por favor verifique los campos en rojo." , "error"); /*  este mensaje se mostrara cuando haya un campo en rojo */
                return false;
            } 
        } 



        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); /* con esto validamos en que navegador estamos para crear el objeto, para el uso del ajax */
        var ajaxUrl = base_url+'/Usuarios/setUsuario'; 
        var formData = new FormData(formUsuario);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        
        /* aqui esta el problema en parse */
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);

                /* si objData.status es verdadero en verdadero quiere decir que si se guadaron los datos   */
                if(objData.status)
                {
                    /* con ".modal("hide") hacemos que se oculte cuando los datos se guardan correctamente  */
                    $('#modalFormUsuario').modal("hide");
                    formUsuario.reset();/* con .reset limpiamos las casillas  */
                    swal("Usuarios", objData.msg ,"success");/* y con sawal mostramos un modal de exitoso */
                     /* con esta linea hacemos que la tabla se refresque  cuando cuando guardamos un nuevo dato   */
                    tableUsuarios.api().ajax.reload();
                       
                }else{
                    swal("Error", objData.msg , "error");/* de lo contrario mostramos un modal de error  */
                }
            }
        }


    }
}, false);


window.addEventListener('load', function() {
        fntRolesUsuario();
      /*   fntViewUsuario();
        fntEditUsuario();
        fntDelUsuario(); */
}, false);


/* esta funcion es para extraer los datos de los roles  */
function fntRolesUsuario(){
    var ajaxUrl = base_url+'/Roles/getSelectRoles';
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){ /* hacemos una validacion si nos devuleve un 200 quiere decir que la peticion fue exitosa */
            document.querySelector('#listRolid').innerHTML = request.responseText; /* con request.responseText incluimos los option de "getSelectRoles" en controllers/Roles */
            document.querySelector('#listRolid').value = 1; /* con .value seleccionamos automatica la primera opcion */
            $('#listRolid').selectpicker('render'); /* con render hacemos que la pagina se refresque al seleccionar  */
        }
    }
    
}


function fntViewUsuario(id_persona){
  

             /* con "tnis" lo que hacemos es refererirnos al ese elemento al que le hemos hecho click */
             /* y con  "getAttribute" lo que hacemos es seleccionar un atribuo en este caso es "us"  que tiene la id del usuario */
            var idPersona= id_persona; 

            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Usuarios/getUsuario/'+idPersona;
            request.open("GET",ajaxUrl,true);
            request.send();

            request.onreadystatechange = function(){

                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);

                    if(objData.status)
                    {
                        var estadoUsuario = objData.data.status == 1 ?  /* creamos una variable "estadoUsuario" donde accedemos al objeto luego a data(que son datos) y luego a status */
                        '<span class="badge badge-success">Activo</span>' :  /* si status es igual a 1 entonces mostrara el span de activo de lo contrario : (esto es de lo contrario) se muestra el inactivo*/
                        '<span class="badge badge-danger">Inactivo</span>';

                        /* accedemos a las id que estan en views-modal y le colocamos con "innerHTML" los valores correspondientes accediendo a data y luego a su tabla*/
                        document.querySelector("#celIdentificacion").innerHTML = objData.data.identificacion;
                        document.querySelector("#celNombre").innerHTML = objData.data.nombres;
                        document.querySelector("#celApellido").innerHTML = objData.data.apellidos;
                        document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                        document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                        document.querySelector("#celTipoUsuario").innerHTML = objData.data.nombre_rol;
                        document.querySelector("#celEstado").innerHTML = estadoUsuario;
                        document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro; 
                        
                        $('#modalViewUser').modal('show');
                        
                    }else{
                        swal("Error",objData.msg,"error");
                    }
                }
            }
}

function fntEditUsuario(id_persona){
    
            
            /* configuramos la apariencia del modal el titulo, el color, y el nombre del boton de guardar a actualizar */
            document.querySelector("#titleModal").innerHTML = "Actualizar Usuarios";
            document.querySelector(".modal-header").classList.replace("headerRegister", "headerUpdate");
            document.querySelector("#btnActionForm").classList.replace("btn-primary", "btn-info");
            document.querySelector("#btnText").innerHTML ="Actualizar";
            

             /* con "tnis" lo que hacemos es refererirnos al ese elemento al que le hemos hecho click */
             /* y con  "getAttribute" lo que hacemos es seleccionar un atribuo en este caso es "us"  que tiene la id del usuario */
            var idPersona= id_persona;

            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Usuarios/getUsuario/'+idPersona;
            request.open("GET",ajaxUrl,true);
            request.send();

            request.onreadystatechange = function(){

                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);

                    if(objData.status)
                    {

                        document.querySelector("#idUsuario").value = objData.data.id_persona;
                        document.querySelector("#txtIdentificacion").value = objData.data.identificacion;
                        document.querySelector("#txtNombre").value = objData.data.nombres;
                        document.querySelector("#txtApellido").value = objData.data.apellidos;
                        document.querySelector("#txtTelefono").value = objData.data.telefono;
                        document.querySelector("#txtEmail").value = objData.data.email_user;
                        document.querySelector("#listRolid").value =objData.data.id_rol;

                         $('#listRolid').selectpicker('render'); /* esto se coloca para renderizar o actualizar el "#listRolid"  y asignarle el valor que le colocamos arriba */
                      
                        if(objData.data.status == 1){  /* si status es = a 1 entonces se le asigna un valor 1 */
                        document.querySelector("#listStatus").value = 1;
                        }else{
                            document.querySelector("#listStatus").value = 2; /* de lo contrario se le asigna un valor 2 */
                        }
                        $('#listStatus').selectpicker('render'); /* actualizamos los valores con render  */
                        }   
                    }
                    
                    $('#modalFormUsuario').modal('show');
            }
}

function fntDelUsuario(id_persona) {

    
            var idUsuario =id_persona;

                swal({
                title: "Eliminar Usuario",
                text: "¿Realmente quiere eliminar el Usuario?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
                }, function (isConfirm) {

                    if (isConfirm) {

                    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); /* con esto creamos un objeto "window.XMLHttpRequest si es chrome " o "Microsoft.XMLHTTP" si es edg */
                    var ajaxUrl = base_url + '/Usuarios/delUsuario/'; /* este metodo  "delRol" se configurara en la carpeta controllers */
                    var strData = "idUsuario=" + idUsuario; /* este idUsuario es el de arriba este almacena el valor rl de cada elemento */
                    request.open("POST", ajaxUrl, true); /* usamos el mentodo post para mandar informacion a la url */
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); /* esta linea y la de abajo es la forma de como se van a enviar los datos */
                    request.send(strData);/* con esto enviamos los datos  "strData"  */
                    request.onreadystatechange = function () {    /* con "onreadystatechange" obtenemos la respuesta */
                        if (request.readyState == 4 && request.status == 200) {  /* si el estatus es 200 quiere decir que la peticion fue exitosa, es como 404 */
                            var objData = JSON.parse(request.responseText); /* con "parse" convertimos a objeto "request.responseText" y lo almacenara en "objData"*/
                            if (objData.status) {
                                swal("Eliminar!", objData.msg, "success");

                               tableUsuarios.api().ajax.reload();
                                

                            } else {
                                swal("Atención!", objData.msg, "error");
                            }
                        }
                    }
                }


            });
}

/* esta funcion es para mostrar el modal de registro aqui se muestra la apariencia del modal */
function openModal()
{
    document.querySelector('#idUsuario').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar"; /* se le coloca en el nombre guardar al boton */
    document.querySelector('#titleModal').innerHTML = "Nuevo Usuario"; /* se le coloca nuevo usuario al titulo del usuario */
    document.querySelector("#formUsuario").reset(); 
    $('#modalFormUsuario').modal('show');
}


/* se icieron algunos cambios en las funciones fntEditRol,fntDelRol, fntPermisos
ya que habian problemas al momento de tener mas de una pagina en la tabla (no funcionaban ) se elimno el evento 
y el llamado a estas funciones ya que se estan haciendo directamente desde los botones esto se puede ver en los controladores
de rol y usuarios  */