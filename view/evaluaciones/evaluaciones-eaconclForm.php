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



        $(document).on('click', '#submit',function(){
            if ($("#puesto").valid()){
                var params={};
                params.action = 'puestos';
                params.operation = 'savePuesto';
                params.id_puesto=$('#id_puesto').val();
                params.nombre=$('#nombre').val();
                params.descripcion=$('#descripcion').val();
                params.codigo=$('#codigo').val();
                params.id_puesto_superior=$('#id_puesto_superior').val();
                params.id_area=$('#id_area').val();
                params.id_nivel_competencia=$('#id_nivel_competencia').val();
                //alert(params.id_puesto_superior);
                $.post('index.php',params,function(data, status, xhr){

                    objeto.id = data; //data trae el id del puesto
                    //alert(data);
                    //var rta= parseInt(data.charAt(3));
                    //alert(rta);
                    if(data >=0){
                        uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Puesto guardado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"puestos", operation:"refreshGrid"});
                        setTimeout(function() { $("#myElem").hide();
                            $('#myModal').modal('hide');
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el puesto').addClass('alert alert-danger').show();
                    }


                }, "json");


            }
            return false;
        });


        $('#myModal #cancel').on('click', function(){
        //$(document).on('click', '#cancel',function(){
            //$('#myModal').modal('hide');
        });




        $('#puesto').validate({
            rules: {
                codigo: {
                        required: true,
                        digits: true,
                        maxlength: 6
                },
                nombre: {required: true},
                id_area: {required: true},
                id_nivel_competencia: {required: true}
            },
            messages:{
                codigo: {
                    required: "Ingrese el código",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                nombre: "Ingrese el nombre",
                id_area: "Seleccione un área",
                id_nivel_competencia: "Seleccione un nivel de competencia"
            }

        });



    });

</script>





<!-- Modal -->
<fieldset <?php echo ( PrivilegedUser::dhasAction('PUE_UPDATE', array(1)) )? '' : 'disabled' ?>>
<div class="modal fade" id="modalEaconcl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>
            <div class="modal-body">


                <form name ="puesto" id="puesto" method="POST" action="index.php">
                    <input type="hidden" name="id_evaluacion_conclusion" id="id_evaluacion_conclusion" value="<?php print $view->conclusion->getIdEvaluacionConclusion(); ?>">
                    <input type="hidden" name="id_empleado" id="id_empleado" value="<?php print $view->conclusion->getIdEmpleado(); ?>">
                    <input type="hidden" name="periodo" id="periodo" value="<?php print $view->conclusion->getPeriodo(); ?>">

                    <div class="form-group">
                        <label class="control-label" for="descripcion">Fortalezas</label>
                        <textarea class="form-control" name="fortalezas" id="fortalezas" placeholder="Fortalezas" rows="4"><?php print $view->conclusion->getFortalezas(); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="descripcion">Aspectos a mejorar</label>
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


