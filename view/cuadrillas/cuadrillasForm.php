<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });



        $('#myModal').on('click', '#submit',function(){ //ok

            if ($("#cuadrilla-form").valid()){

                var params={};
                params.action = 'cuadrillas';
                params.operation = 'saveCuadrilla';
                params.id_cuadrilla = $('#id_cuadrilla').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.id_contrato = $('#id_contrato').val();
                params.default_id_vehiculo = $('#default_id_vehiculo').val();
                params.default_id_area = $('#default_id_area').val();
                params.nombre = $('#nombre').val();
                params.nombre_corto = $('#nombre_corto').val();
                params.actividad = $('#actividad').val();
                params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myModal #myElem").html('Cuadrilla guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        setTimeout(function() { $("#myModal #myElem").hide();
                                                $('#myModal').modal('hide');
                                                $("#search").trigger("click");
                                              }, 2000);
                    }else{
                        $("#myModal #myElem").html('Error al guardar la cuadrilla').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });


        //cancel de formulario de postulacion
        $('#myModal #cancel').on('click', function(){
            $('#myModal').modal('hide');
        });


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#cuadrilla-form').validate({ //ok
            errorContainer: $('#cuadrilla-form #myElem'), //https://forum.jquery.com/topic/jquery-validate-it-may-be-a-bug-in-errorcontainer-and-errorplacement
            rules: {
                nombre: {required: true},
                nombre_corto:{
                    required: true,
                    maxlength: 5
                }
                //id_contrato: {required: true}
                //default_id_vehiculo: {required: true},
                //default_id_area: {required: true}
                /*fecha_emision: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaEmision",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                },
                fecha_vencimiento: {
                    required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "renovacionesPersonal",
                            operation: "checkFechaVencimiento",
                            fecha_emision: function(){ return $('#fecha_emision').val();},
                            fecha_vencimiento: function(){ return $('#fecha_vencimiento').val();},
                            //id_empleado: function(){ return $('#id_empleado').val();},
                            id_empleado: function(){ return $('#id_empleado option:selected').attr('id_empleado');},
                            id_grupo: function(){ return $('#id_empleado option:selected').attr('id_grupo');},
                            id_vencimiento: function(){ return $('#id_vencimiento').val();},
                            id_renovacion: function(){ return $('#id_renovacion').val();}
                        }
                    }
                }*/

            },
            messages:{
                nombre: "Ingrese el nombre",
                nombre_corto:{
                    required: "Ingrese el identificador",
                    maxlength: "Máximo 5 caracteres"
                }
                //id_contrato: "Seleccione el contrato"
                //default_id_vehiculo: "Seleccione el vehículo",
                //default_id_area: "Seleccione el área"
                /*fecha_emision: {
                    required: "Ingrese la fecha de emisión",
                    remote: "La fecha de emisión debe ser mayor"
                },
                fecha_vencimiento: {
                    required: "Ingrese la fecha de vencimiento",
                    remote: "La fecha de vencimiento debe ser mayor"
                }*/
            }

        });



    });

</script>



<!-- Modal -->
<fieldset  <?php //echo ($view->renovacion->getIdRnvRenovacion() || !PrivilegedUser::dhasAction('RPE_UPDATE', array(1))   )? 'disabled' : '';  ?>  >
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="cuadrilla-form" id="cuadrilla-form" method="POST" action="index.php">
                    <input type="hidden" name="id_cuadrilla" id="id_cuadrilla" value="<?php print $view->cuadrilla->getIdCuadrilla() ?>">
                    <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->id_contrato ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->cuadrilla->getNombre() ?>"
                            <?php echo ($view->cuadrilla->getHasPartes())? 'disabled title="No es posible editar el nombre de una cuadrilla con novedades existentes"':'' ?>
                               placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Identificador</label>
                        <input class="form-control" type="text" name="nombre_corto" id="nombre_corto" value = "<?php print $view->cuadrilla->getNombreCorto() ?>"
                            <?php echo ($view->cuadrilla->getHasPartes())? 'disabled title="No es posible editar el identificador de una cuadrilla con novedades existentes"':'' ?>
                               placeholder="Abreviatura ó identificador corto">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="actividad">Actividad</label>
                        <textarea class="form-control" name="actividad" id="actividad" placeholder="Describe la actividad principal de la cuadrilla" rows="2"><?php print $view->cuadrilla->getActividad(); ?></textarea>
                    </div>

                    <!--<div class="form-group required">
                        <label for="id_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" title="Seleccione el contrato" data-live-search="true" data-size="5">
                            <?php //foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php //echo $con['id_contrato']; ?>"
                                    <?php //echo ($con['id_contrato'] == $view->cuadrilla->getIdContrato())? 'selected' :'' ?>
                                    >
                                    <?php //echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  //} ?>
                        </select>
                    </div>-->

                    <div class="form-group">
                        <label for="default_id_vehiculo" class="control-label">Vehículo (por defecto)</label>
                        <select class="form-control selectpicker show-tick" id="default_id_vehiculo" name="default_id_vehiculo" data-live-search="true" data-size="5">
                            <option value="">Seleccione un vehículo</option>
                            <?php foreach ($view->vehiculos as $ve){
                                ?>
                                <option value="<?php echo $ve['id_vehiculo']; ?>"
                                    <?php echo ($ve['id_vehiculo'] == $view->cuadrilla->getDefaultIdVehiculo())? 'selected' :'' ?>
                                    >
                                    <?php echo $ve['nro_movil']." ".$ve['modelo'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="default_id_area" class="control-label">Área (por defecto)</label>
                        <select class="form-control selectpicker show-tick" id="default_id_area" name="default_id_area" data-live-search="true" data-size="5">
                            <option value="">Seleccione un área</option>
                            <?php foreach ($view->areas as $ar){
                                ?>
                                <option value="<?php echo $ar['id_area']; ?>"
                                    <?php echo ($ar['id_area'] == $view->cuadrilla->getDefaultIdArea())? 'selected' :'' ?>
                                    >
                                    <?php echo $ar['codigo']." ".$ar['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="disabled" name="disabled" <?php echo ($view->cuadrilla->getDisabled() == 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Seleccione para ocultar la cuadrilla al cargar novedades">Desactivar</a>
                            </label>
                        </div>
                    </div>


                    <div id="myElem" class="msg" style="display:none">
                        <ul class="alert alert-danger" style="list-style-type: none"><p></p></ul>
                    </div>

                </form>







            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ($view->target!='view' )? '' : 'disabled' ?> >Guardar</button>
                <button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
</fieldset>



