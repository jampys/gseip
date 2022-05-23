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
            $('#daterange').daterangepicker({
                startDate: moment().startOf('year'), //moment().subtract(29, 'days'),
                endDate: moment(), //moment().add(12, 'months'),
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
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                /*params={};
                params.search_puesto = $("#search_puesto").val();
                params.search_localidad = $("#search_localidad").val();
                params.search_contrato = $("#search_contrato").val();
                params.action = "busquedas";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params);*/
                $('#example').DataTable().ajax.reload();
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
                });
                return false;
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
                });
                return false;
            });


            //Al presionar el boton detalles de la busqueda....
            $('#content').on('click', '.detalles', function(){ //ok
                //alert('tocó en contratos');
                var id = $(this).closest('tr').attr('data-id');
                //preparo los parametros
                params={};
                params.id_busqueda = id;
                params.action = "postulaciones2";
                //params.operation = "loadDetalles"; //entra al default del controller
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#etapas_left_side').attr('id_busqueda', id);
                });
                return false;
            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "busquedas";
                params.operation="newBusqueda";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
            });



            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });




            var dialog;
            $(document).on('click', '#example .delete', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la búsqueda?</p>",
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
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "deleteBusqueda";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Búsqueda eliminada con exito</div>');
                        setTimeout(function() {
                                                dialog.modal('hide');
                                                //$("#search").trigger("click");
                                                $('#example').DataTable().ajax.reload();
                                            }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la búsqueda</div>');

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


                    <div class="form-group col-md-2">
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

                    <div class="form-group col-md-2">
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


                    <div class="form-group col-md-3">
                        <!--<label for="search_vencimiento" class="control-label">Buscar partes</label>-->
                        <div class="inner-addon right-addon">
                            <input class="form-control" type="text" name="daterange" id="daterange" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                            <i class="glyphicon glyphicon-calendar"></i>
                        </div>
                    </div>


                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                            <i class="fas fa-search fa-lg dp_blue"></i>
                        </button>
                    </div>

                    <div class="form-group col-md-1">
                        <!--<label for="search">&nbsp;</label>-->
                        <button type="button" class="form-control btn btn-default" title="nueva búsqueda" id="new" <?php echo ( PrivilegedUser::dhasAction('BUS_INSERT', array(1)) )? '' : 'disabled' ?>>
                            <i class="fas fa-plus fa-lg dp_green"></i>
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