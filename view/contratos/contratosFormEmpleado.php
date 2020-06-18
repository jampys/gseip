<script type="text/javascript">


    $(document).ready(function(){

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


        $('#empleado-form').validate({
            errorContainer: '#myModal #myElem',
            rules: {
                id_empleado: {required: true},
                puesto: {required: true},
                fecha_desde: {required: true}
            },
            messages:{
                id_empleado: "Seleccione un empleado",
                puesto: "Seleccione un puesto",
                fecha_desde: "Seleccione la fecha de afectación"
            }
        });


        moment.locale('es');
        $('#myModal #fecha_desde, #myModal #fecha_hasta').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            parentEl: '#myModal',
            minDate: '01/01/2010',
            maxDate: '31/12/2029',
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        });




    });

</script>





<!-- Modal -->
<fieldset <?php //echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->empleado->getDomain() ) )? '' : 'disabled' ?>>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">
                    <input type="hidden" name="id" id="id" value="<?php //print $view->client->getId() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="id_empleado">Empleado</label>
                        <!--<input type="text" class="form-control empleado-group" id="empleado" name="empleado" placeholder="Empleado">
                        <input type="hidden" name="id_empleado" id="id_empleado" class="empleado-group"/>-->
                        <select id="id_empleado" name="id_empleado" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un empleado">
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>"
                                    <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="puesto" >Puesto</label>
                        <select class="form-control selectpicker show-tick" id="puesto" name="puesto" data-live-search="true" data-size="5" title="Seleccione el puesto">
                            <?php foreach ($view->puestos as $pu){
                                ?>
                                <option value="<?php echo $pu['id_puesto']; ?>"
                                    <?php //echo ($sup['codigo'] == $view->puesto->getCodigoSuperior())? 'selected' :'' ?>
                                    >
                                    <?php echo $pu['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="id_localidad" >Ubicación</label>
                        <select class="form-control selectpicker show-tick" id="id_localidad" name="id_localidad" title="Seleccione la ubicación" data-live-search="true" data-size="5">
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>"
                                    <?php //echo ($loc['id_localidad'] == $view->empleado->getIdLocalidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group required">
                        <label class="control-label" for="id_proceso" >Proceso</label>

                        <div class="alert alert-info fade in">
                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                            <span class="glyphicon glyphicon-tags" ></span>&nbsp  Puede seleccionar mas de un proceso.
                        </div>

                        <select multiple class="form-control selectpicker" id="id_proceso" name="id_proceso" data-selected-text-format="count" data-live-search="true" data-size="5" >
                            <?php foreach ($view->procesos as $pro){
                                ?>
                                <option value="<?php echo $pro['id_proceso']; ?>">
                                    <?php echo $pro['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="row">
                        <div class="form-group col-md-6 required">
                            <label for="meta" class="control-label">Fecha afectación</label>
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="meta_valor" class="control-label">Fecha desafectación</label>
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>
                    </div>












                </form>

                <div id="myElem" style="display:none">
                    <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                </div>



            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ( PrivilegedUser::dhasPrivilege('CON_ABM', $view->empleado->getDomain()) && $view->target!='view' )? '' : 'disabled' ?> >Aceptar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



