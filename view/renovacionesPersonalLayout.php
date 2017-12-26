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
                params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                params.id_grupo = $('#search_empleado option:selected').attr('id_grupo');
                params.id_vencimiento = $("#search_vencimiento").val();
                params.id_contrato = $("#search_contrato").val();
                params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "renovacionesPersonal";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                //alert(params.renovado);
                $('#content').load('index.php', params);
            });



            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesPersonal";
                params.operation = "editRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.renovar', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesPersonal";
                params.operation = "renovRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "renovacionesPersonal";
                params.operation="newRenovacion";
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


        <div class="col-md-1"></div>

        <div class="col-md-10">

            <h4>Renovaciones de personal</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <!--<div class="form-group col-md-4">
                        <label for="search_empleado" class="control-label">Empleado</label>
                        <select id="search_empleado" name="search_empleado" class="form-control selectpicker show-tick" data-live-search="true" title="Seleccione un empleado">

                        </select>
                    </div>-->
                    <div class="form-group col-md-4">
                        <label for="search_empleado" class="control-label">Empleado/Grupo</label>
                        <select class="form-control selectpicker show-tick" id="search_empleado" name="search_empleado" data-live-search="true" data-size="5">
                            <option value="">Seleccione un empleado o grupo</option>
                            <?php foreach ($view->empleadosGrupos as $eg){
                                ?>
                                <option value="" id_empleado="<?php echo $eg['id_empleado']; ?>" id_grupo="<?php echo $eg['id_grupo']; ?>" >
                                    <?php echo $eg['descripcion'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="search_vencimiento" class="control-label">Vencimiento</label>
                        <select class="form-control selectpicker show-tick" id="search_vencimiento" name="search_vencimiento" data-live-search="true" data-size="5">
                            <option value="">Seleccione un vencimiento</option>
                            <?php foreach ($view->vencimientos as $vto){
                                ?>
                                <option value="<?php echo $vto['id_vencimiento']; ?>" >
                                    <?php echo $vto['nombre'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="search_vencimiento" class="control-label">Contrato</label>
                        <select class="form-control selectpicker show-tick" id="search_contrato" name="search_contrato" data-live-search="true" data-size="5">
                            <option value="">Seleccione el contrato</option>
                            <?php foreach ($view->contratos as $con){
                                ?>
                                <option value="<?php echo $con['id_contrato']; ?>" >
                                    <?php echo $con['nro_contrato'].' '.$con['nombre'];?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>


                    <!--<div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="new">Nueva renovación</button>
                    </div>-->


                    <div class="form-group col-md-1">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="search">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-1">
                        <label for="search">&nbsp;</label>
                        <button type="button" class="form-control btn btn-primary btn-sm" id="new">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </div>

                    <div class="form-group col-md-2">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="search_renovado" name="search_renovado">
                                    <a href="#" data-toggle="tooltip" title="Seleccione para visualizar registros ya renovados">Renovados</a>
                            </label>
                        </div>
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