﻿<!DOCTYPE html>

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
                params.search_busqueda = $("#search_busqueda").val();
                params.search_postulante = $("#search_postulante").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "postulaciones";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    $('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "postulaciones";
                params.operation = "editPostulacion";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.etapas', function(){ //ok
                //alert('presiono sobre etapas');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_postulacion = id;
                params.action = "etapas";
                //params.operation = "etapas"; //entra en default
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_postulacion', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "postulaciones";
                params.operation="newPostulacion";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#example .delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                var id = $(this).closest('tr').attr('data-id');
                $('#confirm').dialog({ //se agregan botones al confirm dialog y se abre
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
                params.id_habilidad_empleado = id;
                params.action = "habilidad-empleado";
                params.operation = "deleteHabilidadEmpleado";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Habilidad eliminada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidad-empleado", operation: "buscar", cuil: $("#cuil").val(), id_habilidad: $("#id_habilidad").val()});
                        $("#search").trigger("click");
                    }else{
                        $("#myElemento").html('Error al eliminar la habilidad').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');
                    }, 2000);

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


        <!--<div class="col-md-1"></div>-->

        <div class="col-md-12">

            <h4>Postulaciones</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <label for="search_busqueda" class="control-label">Búsqueda</label>
                        <select class="form-control selectpicker show-tick" id="search_busqueda" name="search_busqueda" data-live-search="true" data-size="5">
                            <option value="">Seleccione la búsqueda</option>
                            <?php foreach ($view->busquedas as $bu){
                                ?>
                                <option value="<?php echo $bu['id_busqueda']; ?>" >
                                    <?php echo $bu['fecha_apertura'].' '.$bu['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="search_postulante" class="control-label">Postulante</label>
                        <select id="search_postulante" name="search_postulante" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un postulante</option>
                            <?php foreach ($view->postulantes as $pos){
                                ?>
                                <option value="<?php echo $pos['id_postulante']; ?>">
                                    <?php echo $pos['apellido'].' '.$pos['nombre'].' '.$pos['dni'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-3">
                        <!--<label for="search_localidad" class="control-label">Área</label>
                        <select class="form-control selectpicker show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                            <option value="">Seleccione un área</option>
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>">
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>-->
                    </div>


                    <!--<div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="new">Nueva renovación</button>
                    </div>-->

                    <div class="form-group col-md-1" style="width: 11%">
                        <label for="search_renovado" class="control-label">&nbsp;</label>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="search_renovado" name="search_renovado">
                                <a href="#" title="Funcionalidad en contrucción">Ver todos</a>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-md-1" style="width: 7%">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-1" style="width: 7%">
                        <label for="search">&nbsp;</label>
                        <button type="button" style="background-color: #337ab7" class="form-control btn btn-primary btn-sm" title="nueva postulación" id="new" <?php echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>



                </form>
            </div>


        </div>


        <!--<div class="col-md-1"></div>-->

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