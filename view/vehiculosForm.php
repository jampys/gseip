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

        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        $('#vehiculo-form').validate({ //ok
            rules: {
                nro_movil: {
                        //required: true,
                        digits: true,
                        maxlength: 3,
                        remote: {
                            url: "index.php",
                            type: "post",
                            dataType: "json",
                            data: {
                                action: "vehiculos",
                                operation: "checkVehiculoNroMovil",
                                nro_movil: function(){ return $('#nro_movil').val();},
                                id_vehiculo: function(){ return $('#id_vehiculo').val();}
                            }
                            /*success: function(data, textStatus, jqXHR) {
                            console.log(textStatus, jqXHR, data);
                            },
                            error: function(data, textStatus, errorThrown) {
                            console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                            }*/
                        }
                },
                matricula: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "vehiculos",
                            operation: "checkVehiculoMatricula",
                            matricula: function(){ return $('#matricula').val();},
                            id_vehiculo: function(){ return $('#id_vehiculo').val();}
                        }
                        /*success: function(data, textStatus, jqXHR) {
                         console.log(textStatus, jqXHR, data);
                         },
                         error: function(data, textStatus, errorThrown) {
                         console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                         }*/

                    }
                },
                marca: {required: true},
                modelo: {required: true}
            },
            messages:{
                nro_movil: {
                    required: "Ingrese el número de movil",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 3 dígitos",
                    remote: "El nro. de movil ingresado ya existe"
                },
                matricula: {
                    required: "Ingrese la matricula",
                    remote: "La matrícula ingresada ya existe"
                },
                marca: "Seleccione una marca",
                modelo: "Ingrese el modelo"
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

                    <div class="form-group">
                        <label class="control-label" for="nro_movil">Nro. móvil</label>
                        <input class="form-control" type="text" name="nro_movil" id="nro_movil" value = "<?php print $view->vehiculo->getNroMovil() ?>" placeholder="Nro. móvil">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="matricula">Matrícula</label>
                        <input class="form-control" type="text" name="matricula" id="matricula"value = "<?php print $view->vehiculo->getMatricula() ?>" placeholder="Matrícula">
                    </div>

                    <div class="form-group required">
                        <label for="marca" class="control-label">Marca</label>
                            <select class="form-control selectpicker show-tick" id="marca" name="marca" title="Seleccione la marca" data-live-search="true" data-size="5">
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


                    <div class="form-group">
                        <label class="control-label" for="id_area" >Modelo año</label>
                        <select class="form-control selectpicker show-tick" id="modelo_ano" name="modelo_ano" title="Seleccione un modelo año" data-live-search="true" data-size="5">
                            <?php foreach ($view->periodos as $per){
                                ?>
                                <option value="<?php echo $per; ?>"
                                    <?php echo ($per == $view->vehiculo->getModeloAno())? 'selected' :'' ?>
                                    >
                                    <?php echo $per; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="responsable" >Responsable</label>

                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <span class="glyphicon glyphicon-tags" ></span>&nbsp  Por defecto el responsable es el RT del contrato al que está afectado el vehículo y no es necesario completar. Solo hacerlo si se quiere designar a una persona diferente.
                        </div>

                        <select id="responsable" name="responsable" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un responsable</option>
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php echo ($em['id_empleado'] == $view->vehiculo->getResponsable())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="propietario" class="control-label">Propietario</label>
                            <select class="form-control selectpicker show-tick" id="propietario" name="propietario" data-live-search="true" data-size="5">
                                <option value="">Seleccione el propietario</option>
                                <?php foreach ($view->companias as $cia){
                                    ?>
                                    <option value="<?php echo $cia['id_compania']; ?>"
                                        <?php echo ($cia['id_compania'] == $view->vehiculo->getPropietario())? 'selected' :'' ?>
                                        >
                                        <?php echo $cia['nombre'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>

                    <div class="form-group">
                        <label for="leasing" class="control-label">Leasing</label>
                        <select class="form-control selectpicker show-tick" id="leasing" name="leasing" data-live-search="true" data-size="5">
                            <option value="">Seleccione el leasing</option>
                            <?php foreach ($view->companias as $cia){
                                ?>
                                <option value="<?php echo $cia['id_compania']; ?>"
                                    <?php echo ($cia['id_compania'] == $view->vehiculo->getLeasing())? 'selected' :'' ?>
                                    >
                                    <?php echo $cia['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha baja</label>
                            <div class="input-group date">
                                <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php print $view->vehiculo->getFechaBaja() ?>" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                    </div>



                    <hr/>

                    <?php if(isset($view->contratos) && sizeof($view->contratos)>0){  ?>
                        <div class="table-responsive">
                            <table class="table table-condensed dpTable table-hover">
                                <thead>
                                <tr>
                                    <th>Contrato</th>
                                    <th>Ubicación</th>
                                    <th>F. Desde</th>
                                    <th>F. Hasta</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($view->contratos as $dom):  ?>
                                    <tr>
                                        <td><?php echo $dom['contrato'];?></td>
                                        <td><?php echo $dom['localidad'];?></td>
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


