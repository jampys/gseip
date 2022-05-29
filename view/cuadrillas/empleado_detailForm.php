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


        //cancel de formulario de empleado
        $('#empleado-form #cancel').on('click', function(){ //ok
            $('#empleado-form').hide();
        });


        $('#empleado-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                id_empleado: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "cuadrilla-empleado",
                            operation: "checkEmpleado",
                            id_cuadrilla_empleado: function(){ return $('#id_cuadrilla_empleado').val();},
                            id_cuadrilla: function(){ return $('#id_cuadrilla').val();},
                            id_empleado: function(){ return $('#id_empleado').val();}
                        }
                    }
                }
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                id_empleado: {
                    required: "Seleccione un empleado",
                    remote: "El empleado seleccionado ya se encuentra en la cuadrilla"
                }
            }

        });



    });

</script>



<form name ="empleado-form" id="empleado-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_cuadrilla_empleado" id="id_cuadrilla_empleado" value="<?php print $view->empleado->getIdCuadrillaEmpleado() ?>">
    <input type="hidden" name="id_cuadrilla" id="id_cuadrilla" value="<?php print $view->empleado->getIdCuadrilla() ?>">


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Empleado</label>
            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" data-live-search="true" data-size="5">
                <option value="">Seleccione un empleado</option>
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option value="<?php echo $em['id_empleado']; ?>"
                            class="<?php echo ($em['fecha_baja'])? 'inactive' :'' ?>"
                        <?php echo ($em['fecha_baja'])? 'disabled' :'' ?>
                        <?php echo ($em['id_empleado'] == $view->empleado->getIdEmpleado())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre'];?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="conductor" name="conductor" <?php echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para la persona que maneja">Conductor</a>
                </label>
            </div>
        </div>




    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ($view->target!='view' )? '' : 'disabled' ?> >Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>