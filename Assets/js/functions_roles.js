var tableRoles;
var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function () {

    tableRoles = $('#tableRoles').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": " " + base_url + "/Roles/getRoles",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id_rol" },
            { "data": "nombre_rol" },
            { "data": "descripcion_rol" },
            { "data": "status" },
            { "data": "options" }
        ],
        "responsive": "true",
        "bDestroy": true,
        "iDisplayLength": 2, /* a qui colocamos cuantos elementos podemos observar ne la tabla en este caso solo 2 si son mas elementos pasan a la siguiene pagina */
        "order": [[0, "desc"]]   /*  esto se coloca para que se ordene de manera decendente con respecto a 0 */
    });


    //NUEVO ROL
    var formRol = document.querySelector("#formRol");
    formRol.onsubmit = function (e) {
        e.preventDefault();

        /* con "value" extraemos los datos ingresados en los campos del modal cada uno tiene su proprio "id" */
        var intIdRol = document.querySelector('#idRol').value;
        var strNombre = document.querySelector('#txtNombre').value;
        var strDescripcion = document.querySelector('#txtDescripcion').value;
        var intStatus = document.querySelector('#listStatus').value;


        /* si los campos de registro estan vacios se genera un mensaje  */
        if (strNombre == '' || strDescripcion == '' || intStatus == '') {
            swal("Atención", "Todos los campos son obligatorios.", "error");
            return false;
        }

        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url + '/Roles/setRol';
        var formData = new FormData(formRol);

        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {

            if (request.readyState == 4 && request.status == 200) {


                /* con esto convertimos de formato sql a formato JSON */
                var objData = JSON.parse(request.responseText);

                /*  si existen los datos  y se guardan se genera un modal  */
                /* para dirigirnos a un elemento del array se hace con punto */
                /* ejmplo asi nos dirigimos al elemnto status del array "arrResponse" ubicado en el archivo roles controllers objData.status */
                if (objData.status) {
                    $('#modalFormRol').modal("hide");
                    formRol.reset();
                    /* de igual forma nos dirigimos al elemento msg del array "arrResponse" con objData.msg */
                    swal("Roles de usuario", objData.msg, "success");/* esto viene de la libreria de notificaciones del template */


                    tableRoles.api().ajax.reload();

                } else {
                    swal("Error", objData.msg, "error");
                }
            }

            return false;
        }


    }

});

$('#tableRoles').DataTable();

function openModal() {

    document.querySelector('#idRol').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    document.querySelector("#formRol").reset();

    $('#modalFormRol').modal('show');
}




/* cuando la ventana o la pagina termina de cargar  ejecutara la funcion "fntEditRol"   */
window.addEventListener('load', function () {
    /* fntEditRol();
    fntDelRol();
    fntPermisos(); */
}, false);

/*  */
function fntEditRol(id_rol) {

            document.querySelector('#titleModal').innerHTML = "Actualizar Rol"; /* con esto cambiamos el nombre del modal  */
            document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate"); /* con replace cambiamos las clases de  "headerRegister" por  "headerUpdate" */
            document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");/* con replace cambiamos las clases de  "btn-primary" por  "btn-info" es de boostrap para cambiar el color del boton */
            document.querySelector('#btnText').innerHTML = "Actualizar"; /* con esto cambiamos el texto del nombre de guardar por actualizar  */


            /* id_rol este parametro es el id_rol del msql se recibe de el controller  */
            var idrol =id_rol ;
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/roles/getRol/' + idrol;
            request.open("GET", ajaxUrl, true);
            request.send();


            /* con esta funcion hacemos que los campos en el modal de registro al momento de editar el registro esten llenos con los datos correspondientes   */
            
            request.onreadystatechange = function(){
                
                if (request.readyState == 4 && request.status == 200) {
                    
                    var objData = JSON.parse(request.responseText); /* convertimos "request.responseText" a un objeto para poder usarlo  */

                    if(objData.status)
                    {
                        document.querySelector("#idRol").value = objData.data.id_rol; /* con esto invocamos al id del rol con value */
                        document.querySelector("#txtNombre").value = objData.data.nombre_rol; /* con value invocamos el nombre del rol */
                        document.querySelector("#txtDescripcion").value = objData.data.descripcion_rol; /* con value invocamos la descripcion del rol se puede ver cunado presionamos f12 en firefox y vemos el array en json*/

                        if(objData.data.status == 1) /* con  "objData.data.status" vemos qe el status sea igual a 1 esto se puede ver en el array de  la consola con f12 en firefox*/
                       {
                           var optionSelect = '<option value="1" selected class="notBlock">Activo</option>';
                       }else{
                           var optionSelect = '<option value="2" selected class="notBlock">Inactivo</option>';
                       
                        }
                       var htmlSelect = `${optionSelect}
                                       <option value="1">Activo</option>
                                       <option value="2">Inactivo</option>
                                       `;
                       document.querySelector("#listStatus").innerHTML = htmlSelect;
                        $('#modalFormRol').modal('show');
                    }
                    else{
                        swal("Error",objData.msg ,"Error");
                    }

                }
            }
}


function fntDelRol(id_rol) {


            var idrol =id_rol;

                swal({
                title: "Eliminar Rol",
                text: "¿Realmente quiere eliminar el Rol?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "Si, eliminar!",
                cancelButtonText: "No, cancelar!",
                closeOnConfirm: false,
                closeOnCancel: true
                }, function (isConfirm) {

                    if (isConfirm) {

                    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP'); /* con esto creamos un objeto "window.XMLHttpRequest si es chrome " o "Microsoft.XMLHTTP" si es edg */
                    var ajaxUrl = base_url + '/Roles/delRol/'; /* este metodo  "delRol" se configurara en la carpeta controllers */
                    var strData = "idrol=" + idrol; /* este idrol es el de arriba este almacena el valor rl de cada elemento */
                    request.open("POST", ajaxUrl, true); /* usamos el mentodo post para mandar informacion a la url */
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); /* esta linea y la de abajo es la forma de como se van a enviar los datos */
                    request.send(strData);/* con esto enviamos los datos  "strData"  */
                    request.onreadystatechange = function () {    /* con "onreadystatechange" obtenemos la respuesta */
                        if (request.readyState == 4 && request.status == 200) {  /* si el estatus es 200 quiere decir que la peticion fue exitosa, es como 404 */
                            var objData = JSON.parse(request.responseText); /* con "parse" convertimos a objeto "request.responseText" y lo almacenara en "objData"*/
                            if (objData.status) {
                                swal("Eliminar!", objData.msg, "success");
                                tableRoles.api().ajax.reload ();
                            } else {
                                swal("Atención!", objData.msg, "error");
                            }
                        }
                    }
                }


            });
}

 

function fntPermisos(id_rol) {



            var idrol= id_rol;
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Permisos/getPermisosRol/' + idrol;
            request.open("GET", ajaxUrl, true);
            request.send();
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200){
                    document.querySelector('#contentAjax').innerHTML = request.responseText;
                    $('.modalPermisos').modal('show');
                    document.querySelector('#formPermisos').addEventListener('submit', fntSavePermisos, false);
                }
            }
}

function fntSavePermisos(evnet) {
    evnet.preventDefault();  /* con evnet evitamos que la pagina se recargue cuando guardamos los datos  */
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Permisos/setPermisos';
    var formElement = document.querySelector("#formPermisos");  
    var formData = new FormData(formElement);
    request.open("POST", ajaxUrl, true);
    request.send(formData);

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {  /* si esta condicion se cumple quiere decir que se hizo correctamente la peticion  */
            var objData = JSON.parse(request.responseText); /* con JSON.parse csonvertimos a un objeto "responseText" y se almacena en "objdata"  */
            if (objData.status) {  /* si el estatus del objeto objData es igual a 1 o verdadero nos salta un anuncio de succes */
                swal("Permisos de usuario", objData.msg, "success");
            } else {
                swal("Error", objData.msg, "error");/* de lo contrario no sale un mensaje de error */
            }
        }
    }

}

/* se icieron algunos cambios en las funciones fntEditRol,fntDelRol, fntPermisos
ya que habian problemas al momento de tener mas de una pagina en la tabla (no funcionaban ) se elimno el evento 
y el llamado a estas funciones ya que se estan haciendo directamente desde los botones esto se puede ver en los controladores
de rol y usuarios  */