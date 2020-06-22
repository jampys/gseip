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
                params.id_vehiculo = $('#search_vehiculo option:selected').attr('id_vehiculo');
                params.id_grupo = $('#search_vehiculo option:selected').attr('id_grupo');
                //params.id_vencimiento = $("#search_vencimiento").val();
                params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.id_contrato = $("#search_contrato").val();
                params.id_subcontratista = $("#search_subcontratista").val();
                params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "renovacionesVehiculos";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                //alert(params.renovado);
                $('#content').load('index.php', params);
            });



            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesVehiculos";
                params.operation = "editRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_vehiculo').prop('disabled', true).selectpicker('refresh');
                    $('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });

            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesVehiculos";
                params.operation = "editRenovacion";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.renovar', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesVehiculos";
                params.operation = "renovRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_vehiculo').prop('disabled', true).selectpicker('refresh');
                    $('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "renovacionesVehiculos";
                params.operation="newRenovacion";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#cancel',function(){ //ok
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

            <h4>Vencimientos de vehículos</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                <!-- FILA DE ARRIBA -->
                <div class="row">

                    <div class="form-group col-md-3">
                        <!--<label for="search_vehiculo" class="control-label">Vehículo / Grupo</label>-->
                        <select class="form-control selectpicker show-tick" id="search_vehiculo" name="search_vehiculo" data-live-search="true" data-size="5">
                            <option value="">Seleccione un vehículo o grupo</option>
                            <?php foreach ($view->vehiculosGrupos as $eg){
                                ?>
                                <option value=""
                                        id_vehiculo="<?php echo $eg['id_vehiculo']; ?>"
                                        id_grupo="<?php echo $eg['id_grupo']; ?>"
                                        data-icon="<?php echo ($eg['id_vehiculo'])? "fas fa-car fa-sm fa-fw" : "fas fa-users fa-sm fa-fw"; ?>"
                                    >
                                    <?php echo $eg['descripcion'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <!--<label for="search_vencimiento" class="control-label">Vencimiento</label>-->
                        <select multiple class="form-control selectpicker show-tick" id="search_vencimiento" name="search_vencimiento" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5" title="Seleccione un vencimiento">
                            <!--<option value="">Seleccione un vencimiento</option>-->
                            <?php foreach ($view->vencimientos as $vto){
                                ?>
                                <option value="<?php echo $vto['id_vencimiento']; ?>" >
                                    <?php echo $vto['nombre'] ;?>
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

                    <div class="form-group col-md-2">
                        <!--<label for="search_renovado" class="control-label">&nbsp;</label>-->
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="search_renovado" name="search_renovado">
                                <a href="#" title="Seleccione para visualizar todos los registros (incluyendo renovados y desactivados)">Ver todos</a>
                            </label>
                        </div>
                    </div>


                    <div class="form-group col-md-1">

                    </div>


                </div>

                <!-- FILA DE ABAJO -->
                <div class="row">

                    <div class="form-group col-md-3">
                        <!--<label for="search_contrato" class="control-label">Subcontratista</label>-->
                        <select class="form-control selectpicker show-tick" id="search_subcontratista" name="search_subcontratista" data-live-search="true" data-size="5">
                            <option value="">Seleccione un subcontratista</option>
                            <?php foreach ($view->subcontratistas as $sub){
                                ?>
                                <option value="<?php echo $sub['id_subcontratista']; ?>" >
                                    <?php echo $sub['razon_social']; ?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-7">

                    </div>

                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Nueva renovación" id="new" <?php echo ( PrivilegedUser::dhasAction('RPE_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                        </button>
                    </div>


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