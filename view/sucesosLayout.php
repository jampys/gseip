<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();

            $('.input-daterange').datepicker({ //ok
                //todayBtn: "linked",
                format:"dd/mm/yyyy",
                language: 'es',
                todayHighlight: true
            });


            $(document).on('click', '#search', function(){
                alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_empleado = $("#search_vencimiento").val(); //$('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = $("#search_vencimiento").val();
                params.eventos = ($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                params.fecha_desde = $("#fecha_desde").val();
                params.fecha_hasta = $("#fecha_hasta").val();
                params.action = "sucesos";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                //alert(params.renovado);
                $('#content').load('index.php', params);
            });



            $(document).on('click', '.edit', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesPersonal";
                params.operation = "editRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });

            $(document).on('click', '.view', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesPersonal";
                params.operation = "editRenovacion";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    $('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '.renovar', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_renovacion = id;
                params.action = "renovacionesPersonal";
                params.operation = "renovRenovacion";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                })
            });



            $(document).on('click', '#new', function(){ 
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


        <!--<div class="col-md-1"></div>-->

        <div class="col-md-12">

            <h4>Eventos de personal</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <!--<div class="form-group col-md-4">
                        <label for="search_empleado" class="control-label">Empleado</label>
                        <select id="search_empleado" name="search_empleado" class="form-control selectpicker show-tick" data-live-search="true" title="Seleccione un empleado">

                        </select>
                    </div>-->
                    <div class="form-group col-md-3">
                        <label for="search_empleado" class="control-label">Empleado</label>
                        <select class="form-control selectpicker show-tick" id="search_empleado" name="search_empleado" data-live-search="true" data-size="5">
                            <option value="">Seleccione un empleado</option>
                            <?php foreach ($view->empleados as $em){
                                ?>
                                <option value="<?php echo $em['id_empleado']; ?>" data-icon="fas fa-user fa-sm fa-fw" >
                                    <?php echo $em['apellido']." ".$em['nombre'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="search_evento" class="control-label">Eventos</label>
                        <select multiple class="form-control selectpicker show-tick" id="search_evento" name="search_evento" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                            <!--<option value="">Seleccione un vencimiento</option>-->
                            <?php foreach ($view->eventos as $ev){
                                ?>
                                <option value="<?php echo $ev['id_evento']; ?>" >
                                    <?php echo $ev['nombre'] ;?>
                                </option>
                            <?php  } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="search_vencimiento" class="control-label">Fecha desde / hasta</label>
                        <div class="input-group input-daterange">
                            <input class="form-control" type="text" name="fecha_desde" id="fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                            <div class="input-group-addon">a</div>
                            <input class="form-control" type="text" name="fecha_hasta" id="fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
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
                        <button type="button" style="background-color: #337ab7" class="form-control btn btn-primary btn-sm" title="nueva renovación" id="new" <?php echo ( PrivilegedUser::dhasAction('RPE_INSERT', array(1)) )? '' : 'disabled' ?>>
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