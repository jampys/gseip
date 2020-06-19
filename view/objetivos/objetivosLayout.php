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
                params.todos = $('#search_todos').prop('checked')? 1:0;


                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "obj_objetivos";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });


            $('#content').on('click', '.edit', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('id_objetivo');
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
                var id = $(this).closest('tr').attr('id_objetivo');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "editObjetivo";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                })
            });


            $('#content').on('click', '.clone', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('id_objetivo');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "editObjetivo";
                params.target = "clone";
                params.cerrado = $(this).closest('tr').attr('cerrado');
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
                var id = $(this).closest('tr').attr('id_objetivo');
                params={};
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "detalle";
                params.cerrado = $(this).closest('tr').attr('cerrado');
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



            var dialog;
            $(document).on('click', '#example .delete', function(){ //ok

                var id = $(this).closest('tr').attr('id_objetivo');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el objetivo?</p>",
                    size: 'small',
                    centerVertical: true,
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
                params.id_objetivo = id;
                params.action = "obj_objetivos";
                params.operation = "deleteObjetivo";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Objetivo eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el objetivo</div>');

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
                            <!--<label for="search_renovado" class="control-label">&nbsp;</label>-->
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="search_todos" name="search_todos">
                                    <a href="#" title="Seleccione para visualizar todos los objetivos">Todos</a>
                                </label>
                            </div>
                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button  id="new" type="button" class="form-control btn btn-default" title="Nuevo objetivo" <?php //echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?> >
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