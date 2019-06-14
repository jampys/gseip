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


            $(document).on('click', '#search', function(){ //ok
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_empleado = $("#search_empleado").val(); //$('#search_empleado option:selected').attr('id_empleado');
                params.eventos = ($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                params.search_fecha_desde = $("#search_fecha_desde").val();
                params.search_fecha_hasta = $("#search_fecha_hasta").val();
                params.search_contrato = $("#search_contrato").val();
                params.action = "sucesos";
                params.operation = "refreshGrid";
                //alert(params.renovado);
                $('#content').load('index.php', params);
            });


            //abre ventana modal para exportar sucesos
            $(document).on('click', '#export', function(){
                //alert('toco en export');
                //preparo los parametros
                params={};
                params.action = "sucesos";
                params.operation = "loadExport";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();

                    $('#myModal #id_contrato').val($('#search_contrato').val());
                    $('.selectpicker').selectpicker('refresh');
                });
                return false;

            });



            $(document).on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_suceso = id;
                params.action = "sucesos";
                params.operation = "editSuceso";
                params.target = "edit";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#id_evento').prop('disabled', true).selectpicker('refresh');
                })
            });



            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_suceso = id;
                params.action = "sucesos";
                params.operation = "editSuceso";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    $('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "sucesos";
                params.operation="newSuceso";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });



            $(document).on('click', '#cancel',function(){ //ok
                $('#myModal').modal('hide');
            });




            $(document).on('click', '#example .delete', function(){ //ok
                //alert('Funcionalidad en desarrollo');
                //throw new Error();
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


            $.fn.borrar = function(id) { //ok
                //alert(id);
                //preparo los parametros
                params={};
                params.id_suceso = id;
                params.action = "sucesos";
                params.operation = "deleteSuceso";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Suceso eliminado con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidad-empleado", operation: "buscar", cuil: $("#cuil").val(), id_habilidad: $("#id_habilidad").val()});
                        $("#search").trigger("click");
                        $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                        setTimeout(function() { $("#myElemento").hide();
                                                $('#confirm').dialog('close');
                                              }, 2000);
                    }else{
                        $("#myElemento").html('Error al eliminar el suceso').addClass('alert alert-danger').show();
                    }

                }, 'json');

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

            <h4>Sucesos de personal</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <!-- FILA DE ARRIBA -->
                    <div class="row">

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
                                <input class="form-control" type="text" name="search_fecha_desde" id="search_fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">a</div>
                                <input class="form-control" type="text" name="search_fecha_hasta" id="search_fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                            </div>
                        </div>

                    </div>

                    <!-- FILA DE ABAJO -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <label for="search_contrato" class="control-label">Contrato</label>
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
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-default" title="nuevo suceso" id="new" <?php echo ( PrivilegedUser::dhasAction('SUC_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-3">

                        </div>

                        <div class="form-group col-md-2">
                            <label for="export" class="control-label">&nbsp;</label>
                            <button id="export" class="form-control btn btn-default dp_blue" href="#" title="exportar sucesos"><i class="fas fa-file-export fa-fw fa-lg"></i></button>
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