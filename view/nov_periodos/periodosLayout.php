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
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.search_contrato = $("#search_contrato").val();
                params.search_periodo_sup = $("#search_periodo_sup").val();
                params.action = "nov_periodos";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){
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





            var dialog;
            $(document).on('click', '#example .cerrar', function(){
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea cerrar el período?</p>",
                    size: 'small',
                    buttons: {
                        cancel: {
                            label: "No"
                        },
                        ok: {
                            label: "Si",
                            className: 'btn-danger',
                            callback: function(){
                                $.fn.cerrar(id);
                                return false; //evita que se cierre automaticamente
                            }
                        }
                    }
                });


            });

            $.fn.cerrar = function(id) {
                //alert(id);
                params={};
                params.id_periodo = id;
                params.action = "nov_periodos";
                params.operation = "cerrarPeriodo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Período cerrado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible cerrar el período</div>');

                });

            };



            $(document).on('click', '#example .abrir', function(){
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea re-abrir el período?</p>",
                    size: 'small',
                    buttons: {
                        cancel: {
                            label: "No"
                        },
                        ok: {
                            label: "Si",
                            className: 'btn-primary',
                            callback: function(){
                                $.fn.abrir(id);
                                return false; //evita que se cierre automaticamente
                            }
                        }
                    }
                });


            });

            $.fn.abrir = function(id) {
                //alert(id);
                params={};
                params.id_busqueda = id;
                params.action = "nov_periodos";
                params.operation = "abrirPeriodo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Período abierto con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible abrir el período</div>');

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

            <h4>Períodos de Liquidación</h4>
            <hr class="hr-primary"/>

            <div class="row clearfix">
                <form id="search_form" name="search_form">

                    <div class="form-group col-md-3">
                        <!--<label for="search_periodo_sup" class="control-label">Período</label>-->
                        <select id="search_periodo_sup" name="search_periodo_sup" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                            <option value="">Seleccione un período</option>
                            <?php foreach ($view->periodos_sup as $ps){
                                ?>
                                <option value="<?php echo $ps['periodo']; ?>">
                                    <?php echo $ps['nombre']; ?>
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