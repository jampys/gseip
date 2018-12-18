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



        $('#modalEaconcl').on('click', '#submit',function(){
            //alert('a guardar');
            if ($("#eaconcl-form").valid()){
                var params={};
                params.action = 'evaluaciones';
                params.operation = 'saveEaconcl';
                params.id_evaluacion_conclusion = $('#id_evaluacion_conclusion').val();
                params.id_empleado = $('#id_empleado').val();
                params.id_plan_evaluacion = $('#id_plan_evaluacion').val();
                params.periodo = $('#periodo').val();
                params.fortalezas = $('#fortalezas').val();
                params.aspectos_mejorar = $('#aspectos_mejorar').val();
                //alert(params.id_puesto_superior);
                $.post('index.php',params,function(data, status, xhr){

                    //alert(rta);
                    if(data >=0){
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Comentarios guardados con exito').addClass('alert alert-success').show();
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#modalEaconcl').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar los comentarios').addClass('alert alert-danger').show();
                    }


                }, "json");


            }
            return false;
        });


        $('#myModal #cancel').on('click', function(){
        //$(document).on('click', '#cancel',function(){
            //$('#myModal').modal('hide');
        });




        $('#eaconcl-form').validate({
            rules: {
                /*codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },*/
                fortalezas: {required: true,
                             maxlength: 500},
                aspectos_mejorar: {required: true,
                                   maxlength: 500}
            },
            messages:{
                /*codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },*/
                fortalezas: {required: "Ingrese las fortalezas",
                             maxlength: "Máximo 500 caracteres"},
                aspectos_mejorar: {required: "Ingrese los aspectos a mejorar",
                                   maxlength: "Máximo 500 caracteres"}
            }

        });



    });

</script>





<!-- Modal -->
<fieldset <?php //echo ( PrivilegedUser::dhasAction('PUE_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="modalEaconcl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="eaconcl-form" id="eaconcl-form" method="POST" action="index.php">
                    <input type="hidden" name="id_evaluacion_conclusion" id="id_evaluacion_conclusion" value="<?php print $view->conclusion->getIdEvaluacionConclusion(); ?>">
                    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->params['id_empleado'] ?>">
                    <input type="hidden" name="periodo" id="periodo" value="<?php print $view->params['periodo'] ?>">
                    <input type="hidden" name="id_plan_evaluacion" id="id_plan_evaluacion" value="<?php print $view->params['id_plan_evaluacion'] ?>">

                    <div class="form-group required">
                        <label class="control-label" for="fortalezas">Fortalezas</label>
                        <a href="#" title="<?php echo $view->conclusion->getEvaluador().' '.$view->conclusion->getFecha() ?>">?</a>
                        <textarea class="form-control" name="fortalezas" id="fortalezas" placeholder="Fortalezas" rows="4"><?php print $view->conclusion->getFortalezas(); ?></textarea>
                    </div>

                    <div class="form-group required">
                        <label class="control-label" for="aspectos_mejorar">Aspectos a mejorar</label>
                        <a href="#" title="<?php echo $view->conclusion->getEvaluador().' '.$view->conclusion->getFecha() ?>">?</a>
                        <textarea class="form-control" name="aspectos_mejorar" id="aspectos_mejorar" placeholder="Aspectos a mejorar" rows="4"><?php print $view->conclusion->getAspectosMejorar(); ?></textarea>
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


