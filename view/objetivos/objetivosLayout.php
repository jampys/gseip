<!DOCTYPE html>

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
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                params.search_periodo = $("#search_periodo").val();
                params.search_puesto = $("#search_puesto").val();
                params.search_area = $("#search_area").val();
                params.search_contrato = $("#search_contrato").val();
                params.search_indicador = $("#search_indicador").val();
                params.search_responsable_ejecucion = $("#search_responsable_ejecucion").val();
                params.search_responsable_seguimiento = $("#search_responsable_seguimiento").val();


                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "obj_objetivos";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "editObjetivo";
                params.target = "edit";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "editObjetivo";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })
            });


            $('#content').on('click', '.clone', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "editObjetivo";
                params.target = "clone";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                    //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
                })
            });


            //$(document).on('click', '.detalle', function(){
            $('#content').on('click', '.detalle', function(){
                //alert('presiono sobre detalle');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "detalle";
                //params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    //$('#etapas_left_side #add').attr('id_postulacion', id);
                })

            });


            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "obj_objetivos";
                params.operation="newObjetivo";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                })
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

                    ],
                    close: function() { $("#myElem").empty().removeClass(); }
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) { //ok
                //alert(id);
                //preparo los parametros
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "deleteObjetivo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#confirm #myElem").html('Objetivo eliminado con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"objetivos", operation: "refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#confirm #myElem").hide();
                                                $('#confirm').dialog('close');
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#confirm #myElem").html('No es posible eliminar el objetivo').addClass('alert alert-danger').show();
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

            <h4>Objetivos</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="periodo" class="control-label">Periodo</label>-->
                            <select class="form-control" id="search_periodo" name="search_periodo">
                                <option value="">Todos</option>
                                <?php foreach ($view->periodos as $pe){
                                    ?>
                                    <option value="<?php echo $pe['periodo']; ?>"
                                        <?php echo ($pe['periodo'] == $view->periodo_actual   )? 'selected' :'' ?>
                                        >
                                        <?php echo $pe['periodo']; ?>
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
                            <select class="form-control selectpicker show-tick" id="search_area" name="search_area" data-live-search="true" data-size="5">
                                <option value="">Seleccione un área</option>
                                <?php foreach ($view->areas as $ar){
                                    ?>
                                    <option value="<?php echo $ar['id_area']; ?>"
                                        <?php //echo ($ar['id_area'] == $view->objetivo->getIdArea() )? 'selected' :'' ?>
                                        >
                                        <?php echo $ar['nombre']; ?>
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
                                    <option value="<?php echo $con['id_contrato']; ?>"
                                        <?php //echo ($con['id_contrato'] == $view->objetivo->getIdContrato() )? 'selected' :'' ?>
                                        >
                                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>


                    </div>

                    <!-- FILA DE ABAJO -->
                    <div class="row">


                        <div class="form-group col-md-3">
                            <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                            <select class="form-control selectpicker show-tick" id="search_indicador" name="search_indicador" data-live-search="true" data-size="5">
                                <option value="">Seleccione un indicador</option>
                                <?php foreach ($view->indicadores['enum'] as $ind){
                                    ?>
                                    <option value="<?php echo $ind; ?>"
                                        <?php //echo ($ind == $view->objetivo->getIndicador() OR ($ind == $view->indicadores['default'] AND !$view->objetivo->getIdObjetivo()) )? 'selected' :'' ?>
                                        >
                                        <?php echo $ind; ?>
                                    </option>
                                <?php  } ?>
                            </select>
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


                        <div class="form-group col-md-3">
                            <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                            <select id="search_responsable_seguimiento" name="search_responsable_seguimiento" class="form-control selectpicker show-tick" data-live-search="true" data-size="5">
                                <option value="">Seleccione un responsable seguimiento</option>
                                <?php foreach ($view->empleados as $em){
                                    ?>
                                    <option value="<?php echo $em['id_empleado']; ?>"
                                        <?php //echo ($em['id_empleado'] == $view->objetivo->getIdResponsableSeguimiento())? 'selected' :'' ?>
                                        >
                                        <?php echo $em['apellido'].' '.$em['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>


                        <div class="form-group col-md-1">

                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button  id="new" type="button" class="form-control btn btn-primary btn-sm" title="agregar objetivo" <?php //echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?> >
                                <span class="glyphicon glyphicon-plus"></span>
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