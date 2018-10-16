<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-tarea, #confirm-ord').dialog({
            autoOpen: false
            //modal: true,
        });

        //para editar una tarea
        $('.grid-tareas').on('click', '.edit', function(){
            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_tarea = id;
            params.action = "obj_tareas";
            params.operation = "editTarea";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });

        //para editar orden de un parte
        $('.grid-ordenes').on('click', '.edit', function(){
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //para ver una tarea
        $('.grid-tareas').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_tarea = id;
            params.action = "obj_tareas";
            params.operation = "editTarea";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#tarea-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver orden de un parte
        $('.grid-ordenes').on('click', '.view', function(){
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#orden-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar una nueva tarea
        $('#left_side').on('click', '#add-tarea', function(){ //ok
            params={};
            params.action = "obj_tareas";
            params.operation = "newTarea";
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_tarea = $('#id_tarea').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Abre formulario para ingresar una nueva orden al parte
        $('#left_side').on('click', '#add-orden', function(){
            params={};
            params.action = "parte-orden";
            params.operation = "newOrden";
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_parte = $('#id_parte').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //eliminar una tarea
        $('.grid-tareas').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-tarea').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar(id);
                        },
                        class:"btn btn-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"btn btn-default"
                    }

                ]
            }).dialog('open');
            return false;
        });


        $.fn.borrar = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_tarea = id;
            params.id_objetivo = $('#id_objetivo').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "obj_tareas";
            params.operation = "deleteTarea";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-tarea #myElemento").html('Tarea eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-tareas').load('index.php',{action:"obj_tareas", id_tarea: params.id_tarea, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-tarea #myElemento").hide();
                                            $('#tarea-form').hide();
                                            $('#confirm-tarea').dialog('close');
                                          }, 2000);
                }else{
                    $("#confirm-tarea #myElemento").html('Error al eliminar el empleado').addClass('alert alert-danger').show();
                }


            });

        };





        //eliminar orden del parte
        $('.grid-ordenes').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-ord').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar(id);
                        },
                        class:"btn btn-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"btn btn-default"
                    }

                ]
            }).dialog('open');
            return false;
        });


        $.fn.borrar = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_parte_orden = id;
            params.id_parte = $('#id_parte').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "parte-orden";
            params.operation = "deleteOrden";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-ord #myElemento").html('Orden eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-ord #myElemento").hide();
                        $('#orden-form').hide();
                        $('#confirm-ord').dialog('close');
                    }, 2000);
                }else{
                    $("#confirm-ord #myElemento").html('Error al eliminar la orden').addClass('alert alert-danger').show();
                }


            });

        };


        //evento al salir o cerrar con la x el modal de actualizar el parte
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            $("#search").trigger("click");
        });




        //Guardar (calcular) parte
        $(document).on('click', '#calcular',function(){
            //alert('calcular');
            //throw new Error();

            if ($("#parte-form").valid()){

                var params={};
                params.action = 'partes';
                params.operation = 'calcularParte';
                params.id_parte = $('#id_parte').val();
                params.id_area = $('#id_area').val();
                params.id_vehiculo = $('#id_vehiculo').val();
                params.id_evento = $('#id_evento').val();
                params.hs_normal = $('#hs_normal').val();
                params.hs_50 = $('#hs_50').val();
                params.hs_100 = $('#hs_100').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.id_parte);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data[0]['flag'] >=0){


                        //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#msg-container").html('<div id="myElem" class="msg alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-check fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                        $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        /*setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#empleado-form').hide();
                        }, 2000);*/
                    }else{
                        //$("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                        $("#msg-container").html('<div id="myElem" class="msg alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-exclamation-triangle fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                    }

                }, 'json');

            }
            return false;
        });


        $('#parte-form').validate({
            rules: {
                id_area: {required: true},
                hs_normal: {
                    require_from_group: [1, ".hs-group"],
                    digits: true,
                    maxlength: 6
                },
                hs_50: {
                    require_from_group: [1, ".hs-group"],
                    digits: true,
                    maxlength: 6
                },
                hs_100: {
                    require_from_group: [1, ".hs-group"],
                    digits: true,
                    maxlength: 6
                }
            },
            messages:{
                id_area: "Seleccione un área",
                hs_normal: {
                    require_from_group: "Complete al menos un tipo de hora",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                hs_50: {
                    require_from_group: "Complete al menos un tipo de hora",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                },
                hs_100: {
                    require_from_group: "Complete al menos un tipo de hora",
                    digits: "Ingrese solo números",
                    maxlength: "Máximo 6 dígitos"
                }

            }

        });





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
                
                <div class="row">

                        <div class="col-md-7" id="left_side">

                            <form name ="parte-form" id="parte-form" method="POST" action="index.php">

                                <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">


                                <!-- seccion de tareas -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-tareas" title="Mostrar tareas">Tareas</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="add-tarea" name="add-tarea" title="Agregar tarea">
                                            <i class="fas fa-plus"></i>&nbsp
                                        </button>
                                    </div>
                                </div>

                                <div id="demo-tareas" class="collapse">
                                    <div class="grid-tareas">
                                        <?php include_once('view/objetivos/tareasGrid.php');?>
                                    </div>
                                </div>


                                <br/>


                                <!-- seccion de ordenes -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="add-orden" name="add-orden" title="Agregar orden">
                                            <i class="fas fa-plus"></i>&nbsp
                                        </button>
                                    </div>
                                </div>

                                <div id="demo-ordenes" class="collapse">
                                    <div class="grid-ordenes">
                                        <?php include_once('view/novedades_partes/ordenesGrid.php');?>
                                    </div>
                                </div>

                            </form>


                        </div>



                        <div class="col-md-5" id="right_side">

                        </div>


                </div>


                <!-- <div id="myElem" class="msg" style="display:none"></div>  para mostrar los resultados de "calcular"   -->
                <div id="msg-container">

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="calcular" name="calcular" type="submit">Calcular</button>
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



<div id="confirm-tarea">
    <div class="modal-body">
        ¿Desea eliminar la tarea?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>




<div id="confirm-ord">
    <div class="modal-body">
        ¿Desea eliminar la orden?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


