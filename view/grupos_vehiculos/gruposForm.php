﻿<script type="text/javascript">


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



        $('#myModal').on('click', '#submit',function(){

            if ($("#grupo-form").valid()){

                var params={};
                params.action = 'vto_gruposVehiculos';
                params.operation = 'saveGrupo';
                params.id_grupo = $('#id_grupo').val();
                params.nombre = $('#nombre').val();
                params.nro_referencia = $('#nro_referencia').val();
                params.id_vencimiento = $('#id_vencimiento').val();
                params.fecha_baja = $('#fecha_baja').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Grupo guardado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"vto_gruposVehiculos", operation:"refreshGrid"});
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el grupo').addClass('alert alert-danger').show();
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


        $('#grupo-form').validate({ //ok
            rules: {
                nombre: {required: true},
                //nro_referencia: {required: true},
                id_vencimiento: {required: true}
            },
            messages:{
                nombre: "Ingrese un nombre",
                //nro_referencia: "Ingrese un nro de referencia",
                id_vencimiento: "Seleccione un vencimiento"
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

                    <div class="form-group">
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


