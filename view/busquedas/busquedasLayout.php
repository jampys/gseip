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
                params.search_puesto = $("#search_puesto").val();
                params.search_localidad = $("#search_localidad").val();
                params.search_contrato = $("#search_contrato").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "busquedas";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });



            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "editBusqueda";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    //$('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });

            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "editBusqueda";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            //Al presionar el boton detalles....
            $('#content').on('click', '.detalles', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_busqueda = id;
                params.action = "postulaciones2";
                params.operation = "loadDetalles";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_busqueda', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "busquedas";
                params.operation="newBusqueda";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });




            $(document).on('click', '#example .delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                return false;
            });



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

            <h4>Búsquedas laborales</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <!--<label for="search_puesto" class="control-label">Puesto</label>-->
                        <select id="search_puesto" name="search_puesto" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un puesto</option>
                            <?php foreach ($view->puestos as $pue){
                                ?>
                                <option value="<?php echo $pue['id_puesto']; ?>">
                                    <?php echo $pue['nombre']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-3">
                        <!--<label for="search_localidad" class="control-label">Área</label>-->
                        <select class="form-control selectpicker show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                            <option value="">Seleccione un área</option>
                            <?php foreach ($view->localidades as $loc){
                                ?>
                                <option value="<?php echo $loc['id_localidad']; ?>">
                                    <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                        <select class="form-control selectpicker show-tick" id="search_contrato" name="search_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione un contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-1">

                    </div>


                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="nueva búsqueda" id="new" <?php echo ( PrivilegedUser::dhasAction('BUS_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
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