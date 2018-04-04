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


        $('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });

        $('#fecha_desde').datepicker().on('changeDate', function (selected) { //ok
            var minDate = new Date(selected.date.valueOf());
            $('#fecha_hasta').datepicker('setStartDate', minDate);
            //$('#fecha_hasta').datepicker('setStartDate', minDate).datepicker('update', minDate);
        });

        $('#fecha_hasta').datepicker().on('changeDate', function (selected) { //ok
            var maxDate = new Date(selected.date.valueOf());
            $('#fecha_desde').datepicker('setEndDate', maxDate);
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
                        <label class="control-label" for="id_area" >Modelo año</label>
                        <select class="form-control selectpicker show-tick" id="id_area" name="id_area" title="Seleccione un modelo año">
                            <?php foreach ($view->periodos as $per){
                                ?>
                                <option value="<?php echo $per; ?>"
                                    <?php echo ($per == $view->vehiculo->getModeloAño())? 'selected' :'' ?>
                                    >
                                    <?php echo $view->vehiculo->getModeloAño(); ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>



                    <hr/>
                    <div class="form-group">
                        <label for="id_contrato" class="control-label">Contrato</label>
                            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" title="Seleccione el contrato" data-live-search="true" data-size="5">
                                <?php foreach ($view->contratos_combo as $con){
                                    ?>
                                    <option value="<?php echo $con['id_contrato']; ?>"
                                        <?php echo ($con['id_contrato'] == $view->vehiculo->getIdContrato())? 'selected' :'' ?>
                                        >
                                        <?php echo $con['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="fecha">Desde / hasta</label>
                            <div class="input-group input-daterange">
                                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php print $view->vehiculo->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">a</div>
                                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php print $view->vehiculo->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                            </div>
                    </div>


                    <div class="form-group">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="cambio_contrato" name="cambio_contrato" <?php echo (!$view->vehiculo->getIdVehiculo())? 'disabled' :'' ?> > <a href="#" title="Registra el cambio de contrato y conserva el anterior como historico">Cambio de contrato</a>
                                </label>
                            </div>
                    </div>


                    <?php if(isset($view->contratos)){  ?>
                        <div class="table-responsive">
                            <table class="table table-condensed dpTable table-hover">
                                <thead>
                                <tr>
                                    <th>Contrato</th>
                                    <th>F. Desde</th>
                                    <th>F. Hasta</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($view->contratos as $dom):  ?>
                                    <tr>
                                        <td><?php echo $dom['contrato'];?></td>
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


