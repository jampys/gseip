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
                params={};
                d.startDate = drp.startDate.format('YYYY-MM-DD');
                d.endDate = drp.endDate.format('YYYY-MM-DD');
                params.search_responsable_ejecucion = $("#search_responsable_ejecucion").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "nc_no_conformidad";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                //$('#content').load('index.php', params);
                $('#example').DataTable().ajax.reload();

            });



            $('#content').on('click', '.edit', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_no_conformidad = id;
                params.action = "nc_no_conformidad";
                params.operation = "editNoConformidad";
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
                params.id_no_conformidad = id;
                params.action = "nc_no_conformidad";
                params.operation = "editNoConformidad";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '#new', function(){ //ok
                //alert('presiono en nuevo');
                params={};
                params.action = "nc_no_conformidad";
                params.operation="newNoConformidad";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
            });





            var dialog;
            $(document).on('click', '#example .delete', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la No conformidad?</p>",
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



            $.fn.borrar = function(id) { //ok
                //alert(id);
                params={};
                params.id_no_conformidad = id;
                params.action = "nc_no_conformidad";
                params.operation = "deleteNoConformidad";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">No conformidad eliminada con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la No conformidad</div>');

                });

            };


            $('#content').on('click', '.acciones', function(){ //ok
                //alert('presiono sobre acciones');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_no_conformidad = id;
                params.action = "nc_acciones";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_no_conformidad', id);
                })

            });


            $('#content').on('click', '.verificaciones', function(){ //ok
                //alert('presiono sobre verificaciones');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_no_conformidad = id;
                params.action = "nc_verificaciones";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side #add').attr('id_no_conformidad', id);
                })

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

            <h4>No conformidades</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="search_vencimiento" class="control-label">Buscar partes</label>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="daterange" id="daterange" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>


                        <div class="form-group col-md-3">
                            <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                            <select id="search_responsable_ejecucion" name="search_responsable_ejecucion" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                                <option value="">Seleccione un responsable ejecución</option>
                                <?php foreach ($view->empleados as $em){
                                    ?>
                                    <option value="<?php echo $em['id_empleado']; ?>"
                                        <?php //echo ($em['id_empleado'] == $view->objetivo->getIdResponsableEjecucion())? 'selected' :'' ?>
                                        >
                                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
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

                        <div class="form-group col-md-2">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Nueva no conformidad" id="new" <?php echo ( PrivilegedUser::dhasAction('RPE_INSERT', array(1)) )? '' : 'disabled' ?>>
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