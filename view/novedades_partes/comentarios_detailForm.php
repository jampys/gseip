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


        //cancel de formulario de parte-empleado
        $('#empleado-form #cancel').on('click', function(){ //ok
            //alert('cancelar form parte-empleado');
            $('#empleado-form').hide();
        });



        //Guardar parte-empleado luego de ingresar nuevo o editar
        $('#empleado-form').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#empleado-form").valid()){

                var params={};
                params.action = 'parte-empleado';
                params.operation = 'saveEmpleado';
                params.id_parte = $('#id_parte').val();
                params.id_parte_empleado = $('#id_parte_empleado').val();
                params.id_empleado = $('#id_empleado').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                params.conductor = $('#conductor').prop('checked')? 1:0;
                params.avoid_event = $('#avoid_event').prop('checked')? 1:'';
                params.comentario = $('#comentario').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#left_side .grid-conceptos').load('index.php',{action:"parte-empleado-concepto", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#empleado-form').hide();
                        }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });




        $('#empleado-form').validate({ //ok
            rules: {
                id_empleado: {required: true}
                /*conductor: {
                    //required: true,
                    remote: {
                        url: "index.php",
                        type: "post",
                        dataType: "json",
                        data: {
                            action: "parte-empleado",
                            operation: "checkEmpleado",
                            id_parte_empleado: function(){ return $('#id_parte_empleado').val();},
                            id_parte: function(){ return $('#id_parte').val();}
                        }
                    }
                }*/
            },
            messages:{
                id_empleado: "Seleccione un empleado"
                /*conductor: {
                    //required: "Seleccione un empleado",
                    remote: "La cuadrilla tiene asignado otro conductor"
                }*/
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
            <textarea class="form-control" name="comentarios" id="comentarios" placeholder="Comentarios" rows="2"><?php print $view->parte->getComentarios(); ?></textarea>
        </div>

        <div id="myElem" class="msg" style="display:none"></div>



    <div id="footer-buttons" class="pull-right">
        <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
        <!--<button class="btn btn-default" id="cancel" name="cancel" type="button" data-dismiss="modal">Cancelar</button>-->
        <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
    </div>


    </fieldset>
</form>













