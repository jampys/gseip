<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();

            moment.locale('es');
            $('#search_fecha').daterangepicker({
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
                $('#search_fecha span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            });

            var drp = $('#search_fecha').data('daterangepicker');


            $(document).on('click', '#search', function(){ //ok
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                params.id_empleado = $("#search_empleado").val(); //$('#search_empleado option:selected').attr('id_empleado');
                params.eventos = ($("#search_evento").val()!= null)? $("#search_evento").val() : '';
                //params.search_fecha_desde = $("#search_fecha_desde").val();
                //params.search_fecha_hasta = $("#search_fecha_hasta").val();
                params.search_fecha_desde = drp.startDate.format('DD/MM/YYYY');
                params.search_fecha_hasta = drp.endDate.format('DD/MM/YYYY');
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
                    //$('#myModal #id_contrato').val($('#search_contrato').val());
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
                    $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    $('#id_evento').prop('disabled', true).selectpicker('refresh');
                })
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
                            $("#search").trigger("click");
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
                            <!--<label for="search_empleado" class="control-label">Empleado</label>-->
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
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-4">

                        </div>

                    </div>

                    <!-- FILA DE ABAJO -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="search_evento" class="control-label">Eventos</label>-->
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


                        <div class="form-group col-md-3">
                            <!--<label for="search_vencimiento" class="control-label">Buscar partes</label>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="search_fecha" id="search_fecha" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>


                        <div class="form-group col-md-3">

                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="nuevo suceso" id="new" <?php echo ( PrivilegedUser::dhasAction('SUC_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="nuevo suceso programado" id="newp" <?php echo ( PrivilegedUser::dhasAction('SUC_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <i class="far fa-calendar-check fa-lg dp_green"></i>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="export" class="control-label">&nbsp;</label>-->
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