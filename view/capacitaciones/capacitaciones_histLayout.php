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
                endDate: moment().endOf('year'), //moment().add(12, 'months'),
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




            $(document).on('click', '#search', function(){
                /*params={};
                params.search_periodo = $("#search_periodo").val();
                params.search_puesto = $("#search_puesto").val();
                params.search_area = $("#search_area").val();
                params.search_contrato = $("#search_contrato").val();
                params.search_indicador = $("#search_indicador").val();
                params.search_responsable_ejecucion = $("#search_responsable_ejecucion").val();
                params.search_responsable_seguimiento = $("#search_responsable_seguimiento").val();
                params.todos = $('#search_todos').prop('checked')? 1:0;
                params.action = "obj_objetivos";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params);*/
                $('#example').DataTable().ajax.reload();
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

            <h4>Capacitaciones históricas</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-2">
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="daterange" id="daterange" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="fad fa-calendar-alt"></i>
                            </div>
                        </div>


                        <div class="form-group col-md-2">
                            <!--<label for="periodo" class="control-label">Periodo</label>-->
                            <select class="form-control selectpicker show-tick" id="id_categoria" name="id_categoria" data-live-search="true" data-size="5">
                                <!--<option value="">Seleccione tipo de capacitación</option>-->
                                <option value="">Tipo de capacitación</option>
                                <?php foreach ($view->categorias as $cat){
                                    ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>"
                                        <?php //echo ($pu['id_puesto'] == $view->objetivo->getIdPuesto() )? 'selected' :'' ?>
                                        >
                                        <?php echo $cat['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <!--<label for="search_vencimiento" class="control-label">Vencimiento</label>-->
                            <select class="form-control selectpicker show-tick" id="mes_programada" name="mes_programada">
                                <option value="">Programa</option>
                                <option value="1">Programadas</option>
                                <option value="0">No Programadas</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <!--<label for="search_vencimiento" class="control-label">Vencimiento</label>-->
                            <select class="form-control selectpicker show-tick" id="asistio" name="asistio">
                                <option value="">Asistencia</option>
                                <option value="1">SI</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
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


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <i class="fas fa-search fa-lg dp_blue"></i>
                            </button>
                        </div>


                    </div>

                    <!-- FILA DE ABAJO -->
                    <div class="row">


                        <div class="form-group col-md-3">

                        </div>


                        <div class="form-group col-md-3">

                        </div>


                        <div class="form-group col-md-3">

                        </div>


                        <div class="form-group col-md-1">

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