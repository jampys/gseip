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
                //preparo los parametros
                params={};
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.search_especialidad = $("#search_especialidad").val();
                params.search_localidad = $("#search_localidad").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "postulantes";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });



            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulante = id;
                params.action = "postulantes";
                params.operation = "editPostulante";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    //$('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });

            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulante = id;
                params.action = "postulantes";
                params.operation = "editPostulante";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "postulantes";
                params.operation="newPostulante";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#cancel',function(){ //ok
                $('#myModal').modal('hide');
            });



            var dialog;
            $(document).on('click', '#example .delete', function(){
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el postulante?</p>",
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
                params.id_postulante = id;
                params.action = "postulantes";
                params.operation = "deletePostulante";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Postulante eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el postulante</div>');

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

            <h4>Postulantes</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">


                    <div class="form-group col-md-3">
                        <!--<label for="search_localidad" class="control-label">Ubicación</label>-->
                        <select class="form-control selectpicker show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                            <option value="">Seleccione una localidad</option>
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>">
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-3">
                        <!--<label for="id_especialidad" class="control-label">Especialidad</label>-->
                        <select class="form-control selectpicker show-tick" id="search_especialidad" name="search_especialidad" data-live-search="true" data-size="5">
                            <option value="">Seleccione una especialidad</option>
                            <?php foreach ($view->especialidades as $es){
                                ?>
                                <option value="<?php echo $es['id_especialidad']; ?>"
                                    <?php //echo ($es['id_especialidad'] == $view->postulante->getIdEspecialidad())? 'selected' :'' ?>
                                    >
                                    <?php echo $es['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-2">

                    </div>


                    <div class="form-group col-md-2">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-2">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="nuevo postulante" id="new" <?php echo ( PrivilegedUser::dhasAction('PTE_INSERT', array(1)) )? '' : 'disabled' ?>>
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