<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#puesto').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },
                nombre: {required: true},
                id_area: {required: true},
                id_nivel_competencia: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                nombre: "Ingrese el nombre",
                id_area: "Seleccione un área",
                id_nivel_competencia: "Seleccione un nivel de competencia"
            }

        });



    });

</script>





<!-- Modal -->
<fieldset <?php echo ( PrivilegedUser::dhasAction('VEH_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="vehiculo-form" id="vehiculo-form" method="POST" action="index.php">
                    <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="<?php print $view->vehiculo->getIdVehiculo() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nro_movil">Nro. móvil</label>
                        <input class="form-control" type="text" name="nro_movil" id="nro_movil" value = "<?php print $view->vehiculo->getNroMovil() ?>" placeholder="Nro. móvil">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="matricula">Matrícula</label>
                        <input class="form-control" type="text" name="matricula" id="matricula"value = "<?php print $view->vehiculo->getMatricula() ?>" placeholder="Matrícula">
                    </div>

                    <div class="form-group required">
                        <label for="marca" class="control-label">Marca</label>
                            <select class="form-control selectpicker show-tick" id="marca" name="marca" title="Seleccione la marca">
                                <?php foreach ($view->marcas['enum'] as $mar){
                                    ?>
                                    <option value="<?php echo $mar; ?>"
                                        <?php echo ($mar == $view->vehiculo->getMarca() OR ($mar == $view->marcas['default'] AND !$view->vehiculo->getIdVehiculo()) )? 'selected' :'' ?>
                                        >
                                        <?php echo $mar; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="matricula">Modelo</label>
                        <input class="form-control" type="text" name="modelo" id="modelo"value = "<?php print $view->vehiculo->getModelo() ?>" placeholder="Modelo">
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="id_area" >Área</label>
                        <select class="form-control selectpicker show-tick" id="id_area" name="id_area" title="Seleccione un área">
                            <?php foreach ($view->areas as $area){
                                ?>
                                <option value="<?php echo $area['id_area']; ?>"
                                    <?php echo ($area['id_area'] == $view->puesto->getIdArea())? 'selected' :'' ?>
                                    >
                                    <?php echo $area['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="id_nivel_competencia" >Nivel de competencia</label>
                        <select class="form-control selectpicker show-tick" id="id_nivel_competencia" name="id_nivel_competencia" title="Seleccione un nivel de competencia">
                            <?php foreach ($view->nivelesCompetencias as $nc){
                                ?>
                                <option value="<?php echo $nc['id_nivel_competencia']; ?>"
                                    <?php echo ($nc['id_nivel_competencia'] == $view->puesto->getIdNivelCompetencia())? 'selected' :'' ?>
                                    >
                                    <?php echo $nc['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" placeholder="Descripción" rows="2"><?php print $view->puesto->getDescripcion(); ?></textarea>
                    </div>


                    <?php if(isset($view->domicilios)){  ?>
                        <div class="table-responsive">
                            <table class="table table-condensed dpTable table-hover">
                                <thead>
                                <tr>
                                    <th>Dirección</th>
                                    <th>Localidad</th>
                                    <th>F. Desde</th>
                                    <th>F. Hasta</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($view->domicilios as $dom):  ?>
                                    <tr>
                                        <td><?php echo $dom['direccion'];?></td>
                                        <td><?php echo $dom['CP'].' '.$dom['ciudad'].' '.$dom['provincia'];?></td>
                                        <td><?php echo $dom['fecha_desde'];?></td>
                                        <td><?php echo $dom['fecha_hasta'];?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>


                    <hr/>





                </form>


                <div id="myElem" class="msg" style="display:none"></div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>
                <button class="btn btn-default btn-sm" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>


