<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $(document).on('click', '#search', function(){
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                params.id_grupo = $('#search_empleado option:selected').attr('id_grupo');
                //params.id_vencimiento = $("#search_vencimiento").val();
                params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.id_contrato = $("#search_contrato").val();
                params.id_subcontratista = $("#search_subcontratista").val();
                params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "renovacionesPersonal";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                //alert(params.renovado);
                $('#content').load('index.php', params);
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

            <h4>Auditoría de vencimientos de personal</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="search_empleado" class="control-label">Empleado / Grupo</label>-->
                            <select class="form-control selectpicker show-tick" id="search_empleado" name="search_empleado" data-live-search="true" data-size="5">
                                <option value="">Seleccione un empleado o grupo</option>
                                <?php foreach ($view->empleadosGrupos as $eg){
                                    ?>
                                    <option value=""
                                            id_empleado="<?php echo $eg['id_empleado']; ?>"
                                            id_grupo="<?php echo $eg['id_grupo']; ?>"
                                            data-icon="<?php echo ($eg['id_empleado'])? "fas fa-user fa-sm fa-fw" : "fas fa-users fa-sm fa-fw"; ?>"
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
                            <!--<label for="search_vencimiento" class="control-label">Contrato</label>-->
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

                        <!--<div class="form-group col-md-2">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="search_renovado" name="search_renovado">
                                    <a href="#" title="Seleccione para visualizar todos los registros (incluyendo renovados y desactivados)">Ver todos</a>
                                </label>
                            </div>
                        </div>-->


                        <div class="form-group col-md-3">

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

                        <div class="form-group col-md-2">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>

                        <!--<div class="form-group col-md-1">
                            <button type="button" class="form-control btn btn-default" title="Nueva renovación" id="new" <?php echo ( PrivilegedUser::dhasAction('RPE_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                            </button>
                        </div>-->

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