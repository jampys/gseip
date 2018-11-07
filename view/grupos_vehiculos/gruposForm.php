<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-daterange').datepicker({ //ok
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



        $('#myModal').on('click', '#submit',function(){ //ok

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
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar la postulación').addClass('alert alert-danger').show();
                });

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


                <form name ="grupo-form" id="grupo-form" method="POST" action="index.php">
                    <input type="hidden" name="id_grupo" id="id_grupo" value="<?php print $view->grupo->getIdGrupo() ?>">

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nombre</label>
                        <input class="form-control" type="text" name="nombre" id="nombre" value = "<?php print $view->grupo->getNombre() ?>" placeholder="Nombre">
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="nombre">Nro. referencia</label>
                        <input class="form-control" type="text" name="nro_referencia" id="nro_referencia" value = "<?php print $view->grupo->getNroReferencia() ?>" placeholder="Nro. referencia">
                    </div>


                    <div class="form-group required required">
                        <label for="id_vencimiento" class="control-label">Vencimiento</label>
                        <select class="form-control selectpicker show-tick" id="id_vencimiento" name="id_vencimiento" title="Seleccione el vencimiento" data-live-search="true" data-size="5">
                            <?php foreach ($view->vencimientos as $vto){
                                ?>
                                <option value="<?php echo $vto['id_vencimiento']; ?>"
                                    <?php echo ($vto['id_vencimiento'] == $view->grupo->getIdVencimiento())? 'selected' :'' ?>
                                    >
                                    <?php echo $vto['nombre'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label class="control-label" for="fecha">Fecha baja</label>
                        <div class="input-group date">
                            <input class="form-control" type="text" name="fecha_baja" id="fecha_baja" value = "<?php print $view->grupo->getFechaBaja() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
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



