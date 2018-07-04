<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-daterange').datepicker({
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });


        //$('.image').viewer({});

        //var objeto={};


        $('#myModal').on('click', '#submit',function(){

            if ($("#postulacion-form").valid()){

                var params={};
                params.action = 'postulaciones';
                params.operation = 'savePostulacion';
                params.id_postulacion = $('#id_postulacion').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                params.id_busqueda = $('#id_busqueda').val();
                params.id_postulante = $('#id_postulante').val();
                params.origen_cv = $('#origen_cv').val();
                params.expectativas = $('#expectativas').val();
                params.propuesta_economica = $('#propuesta_economica').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){

                    //objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Postulación guardada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar la postulación').addClass('alert alert-danger').show();
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


        $('#postulacion-form').validate({
            rules: {
                id_busqueda: {required: true},
                id_postulante: {required: true}
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
                id_busqueda: "Seleccione la búsqueda",
                id_postulante: "Seleccione el postulante"
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

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->cuadrilla->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="actividad">Actividad</label>
                        <textarea class="form-control" name="actividad" id="actividad" placeholder="Actividad" rows="2"><?php print $view->cuadrilla->getActividad(); ?></textarea>
                    </div>

                    <div class="form-group required">
                        <label for="id_contrato" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" title="Seleccione el contrato" data-live-search="true" data-size="5">
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>"
                                    <?php echo ($con['id_contrato'] == $view->cuadrilla->getIdContrato())? 'selected' :'' ?>
                                    >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group required">
                        <label for="id_vehiculo" class="control-label">Vehículo (por defecto)</label>
                        <select class="form-control selectpicker show-tick" id="id_vehiculo" name="id_vehiculo" title="Seleccione el vehículo" data-live-search="true" data-size="5">
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

                    <div class="form-group required">
                        <label for="id_area" class="control-label">Área (por defecto)</label>
                        <select class="form-control selectpicker show-tick" id="id_area" name="id_area" title="Seleccione el área" data-live-search="true" data-size="5">
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



