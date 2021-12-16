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
        $('#add-form').on('change', '#add_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "partes2";
            params.operation = "getPeriodosAndCuadrillas";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#add_contrato').val();
            //params.activos = 1;

            $('#id_periodo').empty();
            $('#cuadrilla').empty();


            $.ajax({
                url:"index.php",
                type:"post",
                //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                data: params,
                dataType:"json",//xml,html,script,json
                success: function(data, textStatus, jqXHR) {

                    //$("#id_periodo").html('<option value="">Seleccione un período</option>');
                    if(Object.keys(data['periodos']).length > 0){
                        $.each(data['periodos'], function(indice, val){
                            var label = data['periodos'][indice]["nombre"]+' ('+data['periodos'][indice]["fecha_desde"]+' - '+data['periodos'][indice]["fecha_hasta"]+')';
                            $("#id_periodo").append('<option value="'+data['periodos'][indice]["id_periodo"]+'"'
                            +' fecha_desde="'+data['periodos'][indice]["fecha_desde"]+'"'
                            +' fecha_hasta="'+data['periodos'][indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');
                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                    }

                    $("#cuadrilla").html('<option value="">Seleccione una cuadrilla</option>');
                    if(Object.keys(data['cuadrillas']).length > 0){
                        $.each(data['cuadrillas'], function(indice, val){
                            var label = data['cuadrillas'][indice]["nombre"];
                            $("#cuadrilla").append('<option value="'+data['cuadrillas'][indice]["nombre"]+'"'
                                //+' fecha_desde="'+data['periodos'][indice]["fecha_desde"]+'"'
                                //+' fecha_hasta="'+data['periodos'][indice]["fecha_hasta"]+'"'
                            +'>'+label+'</option>');
                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                    }

                    $('#id_periodo').selectpicker('refresh');
                    $('#cuadrilla').selectpicker('refresh');
                    $('#add_fecha').val('');

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


        //cancel de formulario de etapa
        $('#etapa-form #cancel').on('click', function(){
            $('#etapa-form').hide();
        });


        $('#etapa-form').validate({ //ok
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                accion: {
                    required: true,
                    maxlength: 200
                },
                fecha_implementacion: {required: true},
                id_responsable_ejecucion: {required: true}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }, */
                accion: {
                    required: "Ingrese una descripción de la acción",
                    maxlength: "Máximo 200 caracteres"
                },
                etapa: "Seleccione una etapa",
                fecha_implementacion: "Seleccione la fecha de implementación",
                id_responsable_ejecucion: "Seleccione el responsable de ejecución"
            }

        });



    });

</script>



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
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
            <label class="control-label" for="accion">Acción</label>
            <textarea class="form-control" name="accion" id="accion" placeholder="Descripción de la acción" rows="4"><?php print $view->accion->getAccion(); ?></textarea>
        </div>

    <div class="form-group required">
        <label class="control-label" for="fecha_implementacion">Fecha implementación</label>
        <div class="inner-addon right-addon">
            <input class="form-control" type="text" name="fecha_implementacion" id="fecha_implementacion" value = "<?php print $view->accion->getFechaImplementacion() ?>" placeholder="DD/MM/AAAA" readonly>
            <i class="glyphicon glyphicon-calendar"></i>
        </div>
    </div>


        <div class="form-group required">
            <label for="id_responsable_ejecucion" class="control-label">Responsable ejecución</label>
            <select id="id_responsable_ejecucion" name="id_responsable_ejecucion" class="form-control selectpicker show-tick" data-live-search="true" data-size="5" title="Seleccione un responsable ejecución">
                <?php foreach ($view->empleados as $em){
                    ?>
                    <option value="<?php echo $em['id_empleado']; ?>"
                        <?php echo ($em['id_empleado'] == $view->accion->getIdResponsableEjecucion())? 'selected' :'' ?>
                        >
                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
                    </option>
                <?php  } ?>
            </select>
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













