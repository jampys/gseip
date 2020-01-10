<script type="text/javascript">


    $(document).ready(function(){


        //cancel de formulario de parte-empleado
        $('#comentarios-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-empleado');
            $('#comentarios-form').hide();
        });



        //Guardar parte-empleado luego de ingresar nuevo o editar
        $('#comentarios-form').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#comentarios-form").valid()){

                var params={};
                params.action = 'parte-comentarios';
                params.operation = 'saveComentarios';
                params.id_parte = $('#id_parte').val();
                params.comentarios = $('#comentarios').val();
                //alert(params.comentarios);

                $.post('index.php',params,function(data, status, xhr){

                    if(data >=0){
                        $("#comentarios-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Comentarios guardados con exito').addClass('alert alert-success').show();
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#comentarios-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar los comentarios').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#comentarios-form').validate({ //ok
            rules: {
                comentarios: {maxlength: 200}
            },
            messages:{
                comentarios: "Máximo 200 caracteres"
            }

        });



    });

</script>



<form name ="comentarios-form" id="comentarios-form" method="POST" action="index.php">
    <fieldset>

    <div class="alert alert-info">
        <strong><?php echo $view->label; ?></strong>
    </div>

        <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->


        <div class="form-group">
            <label class="control-label" for="servicio">Comentarios</label>
            <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="5"><?php print $view->parte->getComentarios(); ?></textarea>
        </div>

        <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit" <?php echo ($view->periodo->getClosedDate())? 'disabled' : '' ?> >Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













