<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#empleado-form').on('change', '#id_empleado', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "cap_empleados";
            params.operation = "getEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_empleado = $('#id_empleado').val();
            //params.activos = 1;

            $('#id_contrato').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {



                    //$("#id_contrato").html('<option value="">Seleccione un contrato</option>');
                    if(Object.keys(data).length > 0){
                        $.each(data, function(indice, val){
                            var label = data[indice]["contrato"];
                            $("#id_contrato").append('<option value="'+data[indice]["id_contrato"]+'"'
                            +'>'+label+'</option>');
                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                    }

                    $('#id_contrato').selectpicker('refresh');
                    //$('#add_fecha').val('');

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //cancel de formulario de empleado
        $('#empleado-form #cancel').on('click', function(){
            $('#empleado-form').hide();
        });


        $('#empleado-form').validate({ //ok
            rules: {
                id_empleado: {required: true},
                id_contrato: {required: true},
                observaciones: {
                    maxlength: 200
                }
            },
            messages:{
                id_empleado: "Seleccione un empleado",
                id_contrato: "Seleccione un contrato",
                accion: {
                    maxlength: "Máximo 200 caracteres"
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

    <input type="hidden" name="id_capacitacion_empleado" id="id_capacitacion_empleado" value="<?php print $view->empleado->getIdCapacitacionEmpleado() ?>">
    <input type="hidden" name="id_capacitacion" id="id_capacitacion" value="<?php print $view->empleado->getIdCapacitacion() ?>">


        <div class="form-group required">
            <label for="id_empleado" class="control-label">Empleado</label>
            <select id="id_empleado" name="id_empleado" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un empleado">
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option value="<?php echo $em['id_empleado']; ?>"
                        <?php echo ($em['id_empleado'] == $view->empleado->getIdEmpleado())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="id_contrato" class="control-label">Contrato</label>
            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5" title="seleccione un contrato">
                <!-- se completa dinamicamente desde javascript  -->
                <?php foreach ($view->contratos as $co){
                    ?>
                    <option value="<?php echo $co['id_contrato']; ?>"
                        <?php echo ($co['id_contrato'] == $view->empleado->getIdContrato())? 'selected' :'' ?>
                        >
                        <?php echo $co['contrato']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group required">
            <label for="id_contrato" class="control-label">Edicion</label>
            <select class="form-control selectpicker show-tick" id="id_edicion" name="id_edicion" data-live-search="true" data-size="5" title="seleccione una edición">
                <?php foreach ($view->ediciones as $ed){
                    ?>
                    <option value="<?php echo $ed['id_edicion']; ?>"
                        <?php echo ($ed['id_edicion'] == $view->empleado->getIdEdicion())? 'selected' :'' ?>
                        >
                        <?php echo $ed['fecha_edicion'].' '.$ed['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" id="asistio" name="asistio" <?php echo (!$view->empleado->getAsistio())? '' :'checked' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para indicar que estuvo presente en la capacitación">Asistió</a>
                </label>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label" for="accion">Observaciones</label>
            <textarea class="form-control" name="observaciones" id="observaciones" placeholder="Observaciones" rows="3"><?php print $view->empleado->getObservaciones(); ?></textarea>
        </div>









    <div id="myElem" class="msg" style="display:none">
        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
    </div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit"  <?php echo ( PrivilegedUser::dhasPrivilege('NC_ABM', array(1)) && $view->target!='view')? '' : 'disabled' ?>  >Guardar</button>
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













