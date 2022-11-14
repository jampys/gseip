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
        /*$('#empleado-form').on('change', '#id_empleado', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "cap_empleados";
            params.operation = "getEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_empleado = $('#id_empleado').val();
            //params.activos = 1;

            $('#myModal #id_contrato').empty();


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
                            $("#myModal #id_contrato").append('<option value="'+data[indice]["id_contrato"]+'"'
                            +'>'+label+'</option>');
                        });

                        //si es una edicion o view, selecciona el concepto.
                        //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                    }

                    $('#myModal #id_contrato').selectpicker('refresh');
                    //$('#add_fecha').val('');

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });


        });*/



        function getData(url, params){
            var jqxhr = $.ajax({
                url:"index.php",
                type:"post",
                data: params,
                dataType:"json"//xml,html,script,json
            });
            return jqxhr ;
        }


        $('#empleado-form').on('change', '#id_contrato', function(e){
            //alert('seleccionó un contrato');
            //throw new Error();
            params={};
            params.action = "cap_empleados";
            params.operation = "getEmpleados";
            //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
            params.id_contrato = $('#id_contrato').val();

            getData('index.php', params)
                .then(function(data){

                    //completo select de empleados
                    $('#id_empleado').empty();
                    if(Object.keys(data).length > 0){
                        //$('#id_empleado').html('<option value="">Todos los empleados</option>');
                        $.each(data, function(index, val){
                            var label = data[index]["legajo"]+' '+data[index]["apellido"]+' '+data[index]["nombre"];
                            $("#id_empleado").append('<option value="'+data[index]["id_empleado"]+'"'
                            +' id_convenio="'+data[index]["id_convenio"]+'"'
                            +'>'+label+'</option>');
                        });
                        $('#id_empleado').selectpicker('refresh');
                    }

                    params.operation = "getCuadrillas";
                    return getData('index.php', params);


                }).catch(function(data, textStatus, errorThrown){

                    alert(data.responseText);
                })


        });


        //Guardar empleado luego de ingresar empleado nuevo o editar
        $('#empleado-form').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#empleado-form").valid()){

                var params={};
                params.action = 'cap_empleados';
                params.operation = 'saveEmpleado';
                params.id_capacitacion_empleado = $('#myModal #id_capacitacion_empleado').val();
                params.id_empleado = $('#myModal #id_empleado').val();
                params.id_capacitacion = $('#myModal #id_capacitacion').val();
                params.id_contrato = $('#myModal #id_contrato').val();
                params.id_edicion = $('#myModal #id_edicion').val();
                params.asistio = $('#myModal #asistio').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            $('#empleado-form').hide();
                            //$('#etapas_left_side .grid').load('index.php',{action:"nc_acciones", id_no_conformidad:params.id_no_conformidad, operation:"refreshGrid"});
                            $('#table-empleados').DataTable().ajax.reload();
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('No es posible guardar el empleado').addClass('alert alert-danger').show();
                });

            }
            return false;
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
                //id_edicion: {required: true},
                observaciones: {
                    maxlength: 200
                }
            },
            messages:{
                id_empleado: "Seleccione un empleado",
                id_contrato: "Seleccione un contrato",
                //id_edicion: "Seleccione una edición",
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
            <label for="id_contrato" class="control-label">Contrato</label>
            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5" title="seleccione un contrato">
                <!-- se completa dinamicamente desde javascript  -->
                <?php foreach ($view->contratos as $co){
                    ?>
                    <option value="<?php echo $co['id_contrato']; ?>"
                        <?php //echo ($co['id_contrato'] == $view->empleado->getIdContrato())? 'selected' :'' ?>
                        >
                        <?php echo $co['nombre'].' '.$co['nro_contrato']; ?>
                    </option>
                <?php  } ?>
            </select>
        </div>


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



        <div class="form-group">
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













