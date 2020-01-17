<style>


</style>



<script type="text/javascript">


    $(document).ready(function(){



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





    });

</script>



        <!--<div class="col-md-1"></div>-->


        <div class="col-md-12">


            <br/>
            <h4>Carga de novedades</h4>
            <hr class="hr-primary"/>




            <form id="add-form" name="add-form" action="index.php?action=novedades2&operation=newParte" method="post">

                <div class="row">


                    <div class="form-group col-md-3">
                        <!-- <label for="add_contrato" class="control-label">Nuevos partes</label>-->
                        <select class="form-control selectpicker show-tick" id="add_contrato" name="add_contrato" data-live-search="true" data-size="5" title="Seleccione un contrato">
                            <!--<option value="">Seleccione un contrato</option>-->
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <!-- <label for="id_periodo" class="control-label">&nbsp;</label>-->
                        <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un período" data-live-search="true" data-size="5">
                            <!-- se completa dinamicamente desde javascript  -->
                        </select>
                    </div>



                    <div class="form-group col-md-2">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="submit" class="form-control btn btn-default" title="nuevo parte" id="new" <?php echo ( PrivilegedUser::dhasAction('PAR_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-4">

                    </div>




                </div>


            </form>





        </div>


        <!--<div class="col-md-1"></div>-->




    <div id="myElem" class="msg" style="display:none">

    </div>





