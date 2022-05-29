﻿<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            tippy('[data-tippy-content]', {
                theme: 'light-border',
                delay: [500,0]
            });

            tippy('#newp', {
                theme: 'light-border',
                delay: [500,0],
                content: '<strong>Nuevo suceso programado</strong><br/><span>Es un suceso programado a futuro que no se imputa inmediatamente.</span>',
                allowHTML: true
            });

            $('.selectpicker').selectpicker();

            moment.locale('es');
            $('#daterange').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment().add(12, 'months'),
                locale: {
                    format: 'DD/MM/YYYY',
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "customRangeLabel": "Rango personalizado"
                },
                ranges: {
                    'Últimos 30 dias': [moment().subtract(29, 'days'), moment()],
                    'Últimos 6 meses': [moment().subtract(6, 'months'), moment()],
                    'Último año': [moment().subtract(1, 'year'), moment()],
                    'Últimos 5 años': [moment().subtract(5, 'years'), moment()]
                }
            }, function(start, end) {
                $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });

            var drp = $('#daterange').data('daterangepicker');


            $(document).on('click', '#search', function(){ //ok
                /*params={};
                params.id_empleado = $("#id_empleado").val();
                params.eventos = ($("#eventos").val()!= null)? $("#eventos").val() : '';
                params.startDate = drp.startDate.format('DD/MM/YYYY');
                params.endDate = drp.endDate.format('DD/MM/YYYY');
                params.id_contrato = $("#id_contrato").val();
                params.action = "sucesos";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params);*/
                $('#example').DataTable().ajax.reload();
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
                    //$('#myModal #id_contrato').val($('#id_contrato').val());
                    $('.selectpicker').selectpicker('refresh');
                });
                return false;

            });


            //editar suceso
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
                    $('#myModal #id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#myModal #id_evento').prop('disabled', true).selectpicker('refresh');
                });
                return false;
            });

            //editar suceso programado
            $(document).on('click', '.editp', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_suceso = id;
                params.action = "sucesosP";
                params.operation = "editSuceso";
                params.target = "edit";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#myModal #id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#myModal #id_evento').prop('disabled', true).selectpicker('refresh');
                });
                return false;
            });


            //ver sucesos
            $(document).on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_suceso = id;
                params.action = "sucesos";
                params.operation = "editSuceso";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                    $('#myModal #id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#myModal #id_evento').prop('disabled', true).selectpicker('refresh');
                });
                return false;

            });


            //ver sucesos programados
            $(document).on('click', '.viewp', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_suceso = id;
                params.action = "sucesosP";
                params.operation = "editSuceso";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#myModal #id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#myModal #id_evento').prop('disabled', true).selectpicker('refresh');
                });
                return false;

            });


            //nuevo suceso
            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "sucesos";
                params.operation="newSuceso";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });


            //nuevo suceso programado
            $(document).on('click', '#newp', function(){ //ok
                params={};
                params.action = "sucesosP";
                params.operation="newSuceso";
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
                    message: "<p>¿Desea eliminar el suceso?</p>",
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
                return false;

            });



            $.fn.borrar = function(id) {
                //alert(id);
                params={};
                params.id_suceso = id;
                params.action = "sucesos";
                params.operation = "deleteSuceso";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Suceso eliminado con exito</div>');
                        setTimeout(function() {
                                                dialog.modal('hide');
                                                $('#example').DataTable().ajax.reload();
                                            }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el suceso</div>');

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

            <h4>Sucesos de personal</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">

                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="id_empleado" class="control-label">Empleado</label>-->
                            <select class="form-control selectpicker show-tick" id="id_empleado" name="id_empleado" data-live-search="true" data-size="5">
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
                            <!--<label for="id_contrato" class="control-label">Contrato</label>-->
                            <select class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-live-search="true" data-size="5">
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
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" data-tippy-content="Buscar" id="search">
                                <i class="fas fa-search fa-lg dp_blue"></i>
                            </button>
                        </div>

                        <div class="form-group col-md-4">

                        </div>

                    </div>

                    <!-- FILA DE ABAJO -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="eventos" class="control-label">Eventos</label>-->
                            <select multiple class="form-control selectpicker show-tick" id="eventos" name="eventos" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5">
                                <!--<option value="">Seleccione un vencimiento</option>-->
                                <?php foreach ($view->eventos as $ev){
                                    ?>
                                    <option value="<?php echo $ev['id_evento']; ?>" >
                                        <?php echo $ev['nombre'] ;?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>


                        <div class="form-group col-md-3">
                            <!--<label for="datarange" class="control-label">Buscar partes</label>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="daterange" id="daterange" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="fad fa-calendar-alt"></i>
                            </div>
                        </div>


                        <div class="form-group col-md-3">

                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" data-tippy-content="Nuevo suceso" id="new" <?php echo ( PrivilegedUser::dhasAction('SUC_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <i class="fas fa-plus fa-lg dp_green"></i>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" id="newp" <?php echo ( PrivilegedUser::dhasAction('SUC_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <i class="far fa-calendar-check fa-lg dp_green"></i>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="export" class="control-label">&nbsp;</label>-->
                            <button id="export" class="form-control btn btn-default dp_blue" href="#" data-tippy-content="Descargar sucesos"><i class="fas fa-file-download fa-fw fa-lg dp_blue"></i></button>
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