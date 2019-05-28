<!DOCTYPE html>

<html lang="en">
<head>

    <?php
        require_once('templates/libraries.php');
     ?>


    <script type="text/javascript">

        $(document).ready(function(){


            //Al cambiar el periodo
            //$('#search_panel').on('change', '#periodo', function(){ //ok
            $(document).on('click', '#search', function(){ //ok
                //alert('cambio el periodo');
                params={};
                params.periodo = $('#periodo').val();
                params.search_contrato = $('#search_contrato').val();
                params.cerrado = $('#periodo option:selected').attr('cerrado');
                params.search_puesto = $('#search_puesto').val();
                params.search_nivel_competencia = $('#search_nivel_competencia').val();
                params.search_localidad = $('#search_localidad').val();

                params.action = "evaluaciones";
                params.operation = "refreshGrid";
                //alert(params.cerrado);
                $('#content').load('index.php', params,function(){})

            });


            //$('.table-responsive').on("click", ".gauss", function(e){
            $(document).on("click", "#gauss", function(e){
                //e.preventDefault();
                //alert('Funcionalidad en desarrollo');
                //throw new Error();
                params={};
                params.action = "evaluaciones";
                params.operation="loadGauss";
                params.periodo = $("#periodo").val();
                params.search_contrato = $("#search_contrato").val();
                params.search_puesto = $('#search_puesto').val();
                params.search_nivel_competencia = $('#search_nivel_competencia').val();
                params.search_localidad = $('#search_localidad').val();
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.renovado = $('#search_renovado').prop('checked')? 1 : '';
                //alert(params.search_contrato);


                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });


                //var nro_version = Number($('#version').val());

                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                //var URL="<?php //echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_v.rptdesign&p_id_vehiculo="+params.id_vehiculo+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
                //var win = window.open(URL, "_blank", strWindowFeatures);
                //var win = window.open(URL, "_blank");
                return false;
            });



            //Al presionar en editar una evaluacion de competencias
            $(document).on('click', '.loadEac', function(){ //ok
                params={};
                //params.id_evaluacion_competencia = $(this).closest('tr').attr('id_evaluacion_competencia');
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.id_plan_evaluacion = $(this).closest('tr').attr('id_plan_evaluacion');
                params.periodo = $(this).closest('tr').attr('periodo'); //$('#periodo').val();
                //params.cerrado = $(this).closest('tr').attr('cerrado'); //$('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "loadEac";
                //alert(params.cerrado);
                $('#popupbox').load('index.php', params,function(){
                    $('#modalEac').modal();
                });
                //$('#popupbox').data({'id_empleado':params.id_empleado, 'id_plan_evaluacion': params.id_plan_evaluacion}); //paso parametros

            });


            //Al presionar en editar una evaluacion de aspectos generales
            $(document).on('click', '.loadEaag', function(){ //ok
                params={};
                //params.id_evaluacion_competencia = $(this).closest('tr').attr('id_evaluacion_competencia');
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.id_plan_evaluacion = $(this).closest('tr').attr('id_plan_evaluacion');
                params.periodo = $(this).closest('tr').attr('periodo'); //$('#periodo').val();
                //params.cerrado = $(this).closest('tr').attr('cerrado'); //$('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "loadEaag";
                //alert(params.cerrado);
                $('#popupbox').load('index.php', params,function(){
                    $('#modalEaag').modal();
                });

            });


            //Al presionar en editar una evaluacion de objetivos
            $(document).on('click', '.loadEao', function(){
                params={};
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.id_plan_evaluacion = $(this).closest('tr').attr('id_plan_evaluacion');
                params.periodo = $(this).closest('tr').attr('periodo'); //$('#periodo').val();
                //params.cerrado = $(this).closest('tr').attr('cerrado'); //$('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "loadEao";
                //alert(params.cerrado);
                $('#popupbox').load('index.php', params,function(){
                    $('#modalEao').modal();
                });

            });


            //Al presionar en editar una evaluacion de conclusiones
            $(document).on('click', '.loadEaconcl', function(){
                params={};
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.id_plan_evaluacion = $(this).closest('tr').attr('id_plan_evaluacion');
                params.periodo = $(this).closest('tr').attr('periodo'); //$('#periodo').val();
                //params.cerrado = $(this).closest('tr').attr('cerrado'); //$('#periodo option:selected').attr('cerrado');
                params.action = "evaluaciones";
                params.operation = "loadEaconcl";
                //alert(params.cerrado);
                $('#popupbox').load('index.php', params,function(){
                    $('#modalEaconcl').modal();
                });

            });






            //Al presionar en reporte individual
            $('#content').on("click", ".reporte", function(){
                //$('.table-responsive').on("click", ".pdf", function(){
                //alert('Funcionalidad en desarrollo');
                //throw new Error();
                params={};
                params.id_empleado = $(this).closest('tr').attr('id_empleado');
                params.periodo = $(this).closest('tr').attr('periodo');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.renovado = $('#search_renovado').prop('checked')? 1 : '';
                params.id_user = "<?php echo $_SESSION['id_user']; ?>";
                //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
                var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
                //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
                var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_ead_reporte_individual.rptdesign&p_id_empleado="+params.id_empleado+"&p_periodo="+params.periodo+"&p_id_user="+params.id_user;
                //var win = window.open(URL, "_blank", strWindowFeatures);
                var win = window.open(URL, "_blank");
                return false;
            });






            $(document).on('click', '.delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
                var id = $(this).attr('data-id');
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
                params.id_objetivo = id;
                params.action = "objetivos";
                params.operation = "deleteObjetivo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Objetivo eliminado con exito').addClass('alert alert-success').show();
                        $('#content').load('index.php',{action:"objetivos", operation: "refreshGrid"});
                        $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    }else{
                        $("#myElemento").html('Error al eliminar el objetivo').addClass('alert alert-danger').show();
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






        <div class="row" id="search_panel">
            <br/>


            <!--<div class="col-md-1"></div>-->

            <div class="col-md-12">

                <h4>Evaluación anual de desempeño</h4>
                <hr class="hr-primary"/>

                <div class="clearfix">
                    <form id="search_form" name="search_form">


                        <!-- FILA DE ARRIBA -->
                        <div class="row">

                            <div class="form-group col-md-3">
                                <!--<label for="periodo" class="control-label">Periodo</label>-->
                                <select class="form-control" id="periodo" name="periodo">
                                    <?php foreach ($view->periodos as $pe){
                                        ?>
                                        <option value="<?php echo $pe['periodo']; ?>"
                                                cerrado="<?php echo $pe['cerrado']; ?>"
                                            <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                                            >
                                            <?php echo $pe['periodo']; ?>
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


                            <div class="form-group col-md-3">
                                <!--<label for="periodo" class="control-label">Periodo</label>-->
                                <select class="form-control selectpicker show-tick" id="search_puesto" name="search_puesto" data-live-search="true" data-size="5">
                                    <option value="">Seleccione un puesto</option>
                                    <?php foreach ($view->puestos as $pu){
                                        ?>
                                        <option value="<?php echo $pu['id_puesto']; ?>"
                                            <?php //echo ($pu['id_puesto'] == $view->objetivo->getIdPuesto() )? 'selected' :'' ?>
                                            >
                                            <?php echo $pu['nombre']; ?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>


                            <div class="form-group col-md-3">
                                <!--<label for="search_vencimiento" class="control-label">Vencimiento</label>-->
                                <select class="form-control selectpicker show-tick" id="search_nivel_competencia" name="search_nivel_competencia" data-live-search="true" data-size="5">
                                    <option value="">Seleccione un nivel competencia</option>
                                    <?php foreach ($view->niveles_competencias as $ar){
                                        ?>
                                        <option value="<?php echo $ar['id_nivel_competencia']; ?>"
                                            <?php //echo ($ar['id_area'] == $view->objetivo->getIdArea() )? 'selected' :'' ?>
                                            >
                                            <?php echo $ar['nombre']; ?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>




                        </div>

                        <!-- FILA DE ABAJO -->
                        <div class="row">


                            <div class="form-group col-md-3">
                                <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                                <select class="form-control selectpicker show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                                    <option value="">Seleccione la ubicación</option>
                                    <?php foreach ($view->localidades as $loc){
                                        ?>
                                        <option value="<?php echo $loc['id_localidad']; ?>"
                                            <?php //echo ($loc['id_localidad'] == $view->empleado->getIdLocalidad())? 'selected' :'' ?>
                                            >
                                            <?php echo $loc['CP'].' '.$loc['ciudad'].' '.$loc['provincia'] ;?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>


                            <div class="form-group col-md-2">
                                <!--<label for="search">&nbsp;</label>-->
                                <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar" id="search">
                                    <span class="glyphicon glyphicon-search fa-lg"></span>
                                </button>
                            </div>

                            
                            <div class="form-group col-md-3">

                            </div>


                            <div class="form-group col-md-2">

                            </div>



                            <div class="form-group col-md-2">
                                <button type="button" class="form-control btn btn-primary btn-sm" title="Función de densidad" id="gauss">
                                    <i class="fas fa-chart-area fa-fw fa-lg"></i>
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