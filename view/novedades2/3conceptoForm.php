<style>



</style>



<script type="text/javascript">


    $(document).ready(function(){


        $('.input-group.date').datepicker({ //ok para fecha (nuevo)
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true,
            autoclose: true
        }); //.datepicker('setDate', new Date()); //pone por defecto la fecha actual
        //$('.input-group.date').datepicker('setDate', new Date());


        //restringe el selector de fechas al periodo seleccionado
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        //$('#add_fecha').datepicker('setStartDate', '18/05/2019');
        //$('.input-group.date').datepicker('setStartDate', '21/04/2019');
        $('.input-group.date').datepicker('setStartDate', fecha_desde);
        $('.input-group.date').datepicker('setEndDate', fecha_hasta);
        //$('#add_fecha').datepicker('setDate', new Date()); //pone por defecto la fecha actual



        $('#code_form').validate({
            rules: {
                code: {required: true}
            },
            messages:{
                code: "Ingrese el código de recuperación"
            }
            /*tooltip_options: {
                usuario: {trigger:'focus'},
                contraseña: {trigger:'focus'}
            }*/
        });


        $(document).on('click', '#enviar',function(){

            if ($("#code_form").valid()){
                //alert('boton restaurar');
                var params={};
                params.action='login';
                params.operation='check-code';
                params.code=$('#code').val();
                //params.contraseña=$('#contraseña').val();

                $.ajax({
                    url:"index.php",
                    type:"post",
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        if(data['id'] >= 1){ //Envió codigo por email con exito
                            //$("#myElem").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Enviando código de recuperación...').addClass('alert alert-info').show();
                            $("#myElem").html(data['msg']).removeClass('alert alert-danger').addClass('alert alert-success').show();
                            setTimeout(function(){  $("#myElem").hide();
                                                    window.location.href = "index.php?action=login&operation=toNewPasswordform";
                                                 }, 1500);
                        }
                        else {
                            $("form button").prop("disabled", false); //habilito botones
                            $("#myElem").html(data['msg']).addClass('alert alert-danger').show();
                        }

                    },
                    error: function(data, textStatus, errorThrown) {
                        alert(data.responseText);
                        /*$("#myElem").html('Error de conexión con la base de datos').addClass('alert alert-danger').show();
                         setTimeout(function() { $("#myElem").hide();
                         }, 2000);*/
                    },
                    beforeSend: function() {
                        // setting a timeout
                        //$("#myElem").html('Enviando código de recuperación...').removeClass('alert alert-danger').addClass('alert alert-info').show();
                        $("form button").prop("disabled", true); //deshabilito botones
                    }

                });

            }
            return false;
        });



        $(document).on('click', '#regresar',function(){
            //alert('regresar');
            window.location.href = "index.php?action=login";
            return false;
        });







    });

</script>





                        <form name ="empleado-form" id="empleado-form" method="POST" action="index.php">


                                <div class="alert alert-info">
                                    <strong><?php echo $view->label; ?></strong>
                                </div>

                                <!--<input type="hidden" name="id_parte" id="id_parte" value="<?php //print $view->empleado->getIdParte() ?>">-->
                                <input type="hidden" name="id_parte_empleado" id="id_parte_empleado" value="<?php //print $view->empleado->getIdParteEmpleado() ?>">







                                <div class="form-group required">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="conductor" name="conductor" <?php //echo ($view->empleado->getConductor()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Marcar la persona que maneja">Conductor</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="avoid_event" name="avoid_event" <?php //echo ($view->empleado->getAvoidEvent()== 1)? 'checked' :'' ?> <?php //echo (!$view->renovacion->getIdRenovacion())? 'disabled' :'' ?> > <a href="#" title="Si hay un evento evita el curso definido para éste y calcula conceptos de manera normal">Evitar evento</a>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="servicio">Comentario</label>
                                    <textarea class="form-control" name="comentario" id="comentario" placeholder="Comentario" rows="2"><?php //print $view->empleado->getComentario(); ?></textarea>
                                </div>



                        </form>








