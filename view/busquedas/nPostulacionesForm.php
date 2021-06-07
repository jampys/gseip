<script type="text/javascript">


    $(document).ready(function(){


        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('.grid-postulaciones').on('click', '.edit', function(){ //ok
            //alert('editar postulacion');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_postulacion = id;
            params.action = "postulaciones2";
            params.operation = "editPostulacion";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                $('#postulacion-form #id_postulante').attr('disabled', true).selectpicker('refresh');
                $("#chalampa #culo").css('display', 'none');
            })
        });


        $('.grid-postulaciones').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_postulacion = id;
            params.action = "postulaciones2";
            params.operation = "editPostulacion";
            params.target = "view";
            $('#etapas_right_side').load('index.php', params,function(){
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#chalampa #footer-buttons button").css('display', 'none');
                //$("#chalampa #culo").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar nueva etapa al postulante
        $('.grid-postulaciones').on('click', '.new', function(){
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.action = "etapas";
            params.operation = "newEtapa";
            params.id_postulacion = id;
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo postulante a la busqueda
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "postulaciones2";
            params.operation = "newPostulacion";
            //params.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').val(params.id_busqueda);
            })
        });




        var dialog;
        $('.grid-postulaciones').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la postulación?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrarGv(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrarGv = function(id) {
            //alert(id);
            params={};
            params.id_postulacion = id;
            //params.id_busqueda = $('#etapas_left_side #add').attr('id_busqueda');
            params.id_busqueda = $('#myModal #id_busquedax').val();
            params.action = "postulaciones2";
            params.operation = "deletePostulacion";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Postulación eliminada con exito</div>');
                    setTimeout(function() {
                            dialog.modal('hide');
                            $('#chalampa').hide();
                            //$('#etapas_left_side .grid').load('index.php',{action:"postulaciones2", id_busqueda:params.id_busqueda, operation:"refreshGrid"});
                            $('#table-postulantes').DataTable().ajax.reload();
                            $('#table-etapas').DataTable().ajax.reload();

                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la postulación</div>');

            });

        };




        $('.grid-postulaciones').on('click', '.etapas', function(){ //ok
            //alert('tocó en etapas');
            var id = $(this).closest('tr').attr('data-id');
            //$('#etapas_left_side #add').attr('id_postulacion', id);
            $('#myModal #id_postulacion').val(id);
            $('#table-etapas').DataTable().ajax.reload();
        });



        //evento al salir o cerrar con la x el modal de etapas
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            $("#search").trigger("click");
        });




        ///----------------------------- etapas--------------------------------------///

        $('.grid-etapas').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_etapa = id;
            params.action = "etapas";
            params.operation = "editEtapa";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        $('.grid-etapas').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_etapa = id;
            params.action = "etapas";
            params.operation = "editEtapa";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#etapa-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            })
        });



        //Guardar etapa luego de ingresar nueva o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar etapa');

            if ($("#etapa-form").valid()){

                var params={};
                params.action = 'etapas';
                params.operation = 'saveEtapa';
                params.id_etapa = $('#id_etapa').val();
                params.id_postulacion = $('#etapa-form #id_postulacion').val();
                params.fecha_etapa = $('#fecha_etapa').val();
                params.etapa = $('#etapa').val();
                params.aplica = $('input[name=aplica]:checked').val();
                params.motivo = $('#motivo').val();
                params.modo_contacto = $('#modo_contacto').val();
                params.comentarios = $('#comentarios').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#etapa-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Etapa guardada con exito').addClass('alert alert-success').show();
                        //$('#etapas_left_side .grid').load('index.php',{action:"etapas", id_postulacion:params.id_postulacion, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#etapa-form').hide();
                                                $('#table-etapas').DataTable().ajax.reload();
                                                $('#table-postulantes').DataTable().ajax.reload();
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar la etapa').addClass('alert alert-danger').show();
                });

            }
            return false;
        });




        var dialog;
        $('.grid-etapas').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la etapa?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrar(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrar = function(id) {
            //alert(id);
            params={};
            params.id_etapa = id;
            //params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            params.id_postulacion = $('#myModal #id_postulacion').val();
            params.action = "etapas";
            params.operation = "deleteEtapa";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Etapa eliminada con exito</div>');
                    setTimeout(function() {
                                dialog.modal('hide');
                                $('#etapa-form').hide();
                                //$('#etapas_left_side .grid').load('index.php',{action:"etapas", id_postulacion:params.id_postulacion, operation:"refreshGrid"});
                                $('#table-etapas').DataTable().ajax.reload();
                                $('#table-postulantes').DataTable().ajax.reload();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la etapa</div>');

            });

        };







    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>

            <div class="modal-body">

                <input type="hidden" name="id_busquedax" id="id_busquedax" value="<?php //print $view->grupo->getIdVencimiento() ?>">
                <input type="hidden" name="id_postulacion" id="id_postulacion" value="<?php //print $view->grupo->getIdVencimiento() ?>">
                
                <div class="row">

                        <div class="col-md-7" id="etapas_left_side">

                            <!-- seccion de postulantes-->
                            <div class="row">
                                <div class="col-md-12">

                                    <!--<div class="clearfix">
                                        <button <?php //echo (PrivilegedUser::dhasPrivilege('PTN_ABM', array(1)) )? '' : 'disabled' ?> class="btn btn-default pull-right dp_green" id="add" name="add" type="submit" title="Agregar postulante">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </div>-->

                                    <div class="grid-postulaciones">
                                        <?php include_once('view/busquedas/nPostulacionesGrid.php');?>
                                    </div>

                                </div>
                            </div>

                            <br/>
                            <!-- seccion de etapas de la postulacion-->
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- incluir datatable de etapas de la postulacion-->
                                    <div class="grid-etapas">
                                        <?php include_once('view/busquedas/etapasGrid.php');?>
                                    </div>

                                </div>
                            </div>



                        </div>



                        <div class="col-md-5" id="etapas_right_side">

                        </div>


                </div>


                <!--<div id="myElem" class="msg" style="display:none"></div>-->

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>




