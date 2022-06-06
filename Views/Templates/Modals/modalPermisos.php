<!-- modal para el boton de permisos  -->


<div class="modal fade modalPermisos" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class= "modal-title 4h"> Permisos Roles de Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">X</span>
        </button>
      </div>

      <div class="modal-body">
        
        

        <div class="col-md-12">
          <div class="tile">

            <form action="" id="formPermisos" name="formPermisos">
			<input type="hidden" id="idrol" name="idrol" value="<?= $data['idrol']; ?>" required=""> <!-- esto se hace para almacenar los datos del rol -->
              <div class="table-responsive">
                <table class="table">
                  <thead>

				  <!-- este es el campo para los nombres de los permisos -->
                    <tr>
                      <th>#</th>
                      <th>Módulo</th>
                      <th>Ver</th>
                      <th>Crear</th>
                      <th>Actualizar</th>
                      <th>Eliminar</th>
                    </tr>
                  </thead>
                  <tbody>
					  <?php 
                                $no=1;
                                $modulos = $data['modulos'];  /* con esto accedemos al array en el apartado de los modulos  */
                                for ($i=0; $i < count($modulos); $i++) {  /* con el cliclo for recorremos el array hasta que se terminen los modulos */

                                    $permisos = $modulos[$i]['permisos'];
                                    $rCheck = $permisos['r'] == 1 ? " checked " : ""; /* con esto hacemos que si el permiso leer en la tabla este con el valor 1 se le colocara un checked de lo contrario sera vacio*/
                                    $wCheck = $permisos['w'] == 1 ? " checked " : "";/* con esto hacemos que si el permiso escribir en la tabla este con el valor 1 se le colocara un checked de lo contrario sera vacio */
                                    $uCheck = $permisos['u'] == 1 ? " checked " : "";/* con esto hacemos que si el permiso actualizar en la tabla este con el valor 1 se le colocara un checked de lo contrario sera vacio*/
                                    $dCheck = $permisos['d'] == 1 ? " checked " : "";/* con esto hacemos que si el permiso borrar en la tabla este con el valor 1 se le colocara un checked de lo contrario sera vacio*/

                                    $idmod = $modulos[$i]['id_modulo']; /* este id_modulo es el mismo de la base de datos en mysql */
								
                            ?>

                    <tr>
                      <td>
                                <?= $no; ?>
                                <input type="hidden" name="modulos[<?= $i; ?>][idmodulo]" value="<?= $idmod ?>" required >
                            </td>
                            <td>
                                <?= $modulos[$i]['titulo']; ?>  <!-- con  esto insertamos los nombre de los modulos en forma de un array "dashboard,usuario,cliente.etc " -->
                            </td>

							<!-- estos son los campos de de los botones para los permisos  -->
							<!-- leer -->
                            <td>
                              <div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][r]" <?= $rCheck ?> ><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>

							<!-- escribir -->
                            <td>
								<div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][w]" <?= $wCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>

							<!-- actualizar -->
                            <td>
								<div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][u]" <?= $uCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>

							<!-- borrar -->
                            <td>
								<div class="toggle-flip">
                                  <label>
                                    <input type="checkbox" name="modulos[<?= $i; ?>][d]" <?= $dCheck ?>><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                                  </label>
                                </div>
                            </td>

                          </tr>
                          <?php 
                                $no++;
                    }
                            ?>
                  </tbody>
                </table>
              </div>

              <div class="text-center">
                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle" aria-hidden="true"></i> Guardar</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="app-menu__icon fas fa-sign-out-alt" aria-hidden="true"></i> Salir</button>
              </div>
              
            </form>
          </div>
        </div>

      </div>
      

    </div>
  </div>
</div>
