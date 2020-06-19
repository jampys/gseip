<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $(document).on('click', '#search', function(){ //ok

                    //alert('presiono en buscar');
                    //var id = $(this).attr('data-id');
                    params={};
                    params.cuil = $("#search_empleado").val();
                    params.id_habilidad = $("#search_habilidad").val();
                    params.action = "habilidad-empleado";
                    params.operation = "buscar";
                    //alert(params.cuil);
                    //alert(params.id_habilidad);
                    $('#content').load('index.php', params);

            });



            /*$('#search_empleado').closest('.form-group').find(':input').on('keyup', function(e){ //ok
                //alert('hola');
                var code = (e.keyCode || e.which);
                if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                    return;
                }

                var items="";

                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": $(this).val(),  "action":"empleados", "operation":"autocompletarEmpleadosByCuil"},
                    success: function(data) {
                        $.each(data.slice(0, 5),function(index,item) {
                            //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                            items+="<option value='"+item['cuil']+"'>"+item['apellido']+' '+item['nombre']+"</option>";
                        });

                        $("#search_empleado").html(items);
                        $('.selectpicker').selectpicker('refresh');
                    }

                });

            });*/



            /*$('#search_habilidad').closest('.form-group').find(':input').on('keyup', function(e){ //ok
                //alert('hola');
                var code = (e.keyCode || e.which);
                if(code == 37 || code == 38 || code == 39 || code == 40 || code == 13) { // do nothing if it's an arrow key or enter
                    return;
                }

                var items="";

                $.ajax({
                    url: "index.php",
                    type: "post",
                    dataType: "json",
                    data: { "term": $(this).val(),  "action":"habilidades", "operation":"autocompletarHabilidades"},
                    success: function(data) {
                        $.each(data.slice(0, 5),function(index,item)
                        {
                            //data.slice(0, 5) trae los 5 primeros elementos del array. Se hace porque la propiedad data-size de bootstrap-select no funciona para este caso
                            items+="<option value='"+item['id_habilidad']+"'>"+item['nombre']+"</option>";
                        });

                        $("#search_habilidad").html(items);
                        $('.selectpicker').selectpicker('refresh');
                    }

                });

            });*/



            $(document).on('click', '.edit, .view', function(){
                alert('Funcionalidad en construccion');
                var id = $(this).attr('data-id');
                params={};
                params.id_habilidad = id;
                params.action = "habilidades";
                params.operation = "editHabilidad";
                /*$('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })*/

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "habilidad-empleado";
                params.operation="new";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


         
            $(document).on('click', '#cancel',function(){ //ok
                $('#myModal').modal('hide');
            });




            var dialog;
            $(document).on('click', '#example .delete', function(){ //ok

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la habillidad al empleado?</p>",
                    size: 'small',
                    centerVertical: true,
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
                params.id_habilidad_empleado = id;
                params.action = "habilidad-empleado";
                params.operation = "deleteHabilidadEmpleado";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Habilidad eliminada con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click"); // refresh grid
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la habilidad</div>');

                });

            };




        });

    </script>




</head>
<body>


    <?php require_once('templates/header.php'); ?>


    <div class="container">




        <br/>
        <div class="row">


            <div class="col-md-1"></div>

            <div class="col-md-10">

                <h4>Habilidades por empleado</h4>
                <hr class="hr-primary"/>

                <div class="row clearfix">
                    <form id="search_form" name="search_form">
                        <div class="form-group col-md-4">
                            <label for="search_empleado" class="control-label">Empleado</label>
                            <select id="search_empleado" name="search_empleado" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                                <option value="">Seleccione un empleado</option>
                                <?php foreach ($view->empleados as $em){
                                    ?>
                                    <option value="<?php echo $em['cuil']; ?>">
                                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="search_habilidad" class="control-label">Habilidad</label>
                            <select id="search_habilidad" name="search_habilidad" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                                <option value="">Seleccione una habilidad</option>
                                <?php foreach ($view->habilidades as $hab){
                                    ?>
                                    <option value="<?php echo $hab['id_habilidad']; ?>">
                                        <?php echo $hab['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-default" id="search" title="Buscar">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-default" id="new" <?php echo ( PrivilegedUser::dhasAction('HEM_INSERT', array(1)) )? '' : 'disabled' ?> >
                                <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                            </button>
                        </div>
                    </form>
                </div>


            </div>


            <div class="col-md-1"></div>

        </div>
        <br/>









        <div id="content" class="row">
            <?php include_once ($view->contentTemplate);  ?>
        </div>

    </div>

    <div id="popupbox"></div>



    <?php require_once('templates/footer.php'); ?>


</body>


</html>