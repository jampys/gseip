<script type="text/javascript">


    $(document).ready(function(){

        //Necesario para que funcione el plug-in bootstrap-select
        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        moment.locale('es');
        $('#fecha_edicion').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            parentEl: '#myModal',
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        });


        //Select dependiente: al seleccionar contrato carga periodos vigentes
        $('#etapa-form').on('change', '#id_empleado', function(e){
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


        //Guardar etapa luego de ingresar empleado nuevo o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar etapa');

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


        //cancel de formulario de etapa
        $('#etapa-form #cancel').on('click', function(){
            $('#etapa-form').hide();
        });


        $('#etapa-form').validate({ //ok
            rules: {
                nombre: {required: true},
                fecha_edicion: {required: true},
                duracion: {
                    required: true,
                    number: true,
                    maxlength: 4
                },
                capacitador: {
                    required: true,
                    maxlength: 50
                },
                id_modalidad: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                fecha_edicion: "Seleccione la fecha de la edición",
                duracion: {
                    required: "Ingrese la duración",
                    number: "Solo números. Utilice un punto como separador decimal",
                    maxlength: "Máximo 4 dígitos"
                },
                capacitador: {
                    required: "Ingrese el nombre del capacitador",
                    maxlength: "Máximo 50 caracteres"
                },
                id_modalidad: "Ingrese la modalidad"

            }

        });



    });

</script>



<form name ="etapa-form" id="etapa-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label ?></strong>
    </div>

    <input type="hidden" name="id_edicion" id="id_edicion" value="<?php print $view->edicion->getIdEdicion() ?>">
    <input type="hidden" name="id_capacitacion" id="id_capacitacion" value="<?php print $view->edicion->getIdCapacitacion() ?>">


        <div class="form-group required">
            <label for="nombre" class="control-label">Nombre</label>
            <input class="form-control" type="text" name="nombre" id="nombre" value="<?php print $view->edicion->getNombre() ?>" placeholder="Nombre">
        </div>


        <div class="form-group required">
            <label class="control-label" for="fecha_programada">Fecha edición</label>
            <div class="inner-addon right-addon">
                <input class="form-control" type="text" name="fecha_edicion" id="fecha_edicion" value="<?php print $view->edicion->getFechaEdicion() ?>">
                <i class="glyphicon glyphicon-calendar"></i>
            </div>
        </div>


        <div class="form-group required">
            <label for="capacitador" class="control-label">Capacitador</label>
            <input class="form-control" type="text" name="capacitador" id="capacitador" value="<?php print $view->edicion->getCapacitador() ?>">
        </div>


        <div class="form-group required">
            <label for="duracion" class="control-label" title="Duración indicada en horas">Duración (hs)</label>
            <input class="form-control" type="text" name="duracion" id="duracion" value="<?php print $view->edicion->getDuracion() ?>">
        </div>


        <div class="form-group required">
            <label for="id_modalidad" class="control-label">Modalidad</label>
            <select class="form-control selectpicker show-tick" id="id_modalidad" name="id_modalidad" title="Seleccione la modalidad" data-live-search="true" data-size="5">
                <?php foreach ($view->modalidades as $mod){
                    ?>
                    <option value="<?php echo $mod['id_modalidad']; ?>"
                        <?php echo ($mod['id_modalidad'] == $view->edicion->getIdModalidad() )? 'selected' :'' ?>
                        >
                        <?php echo $mod['nombre']; ?>
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













