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


            $('#content').on('click', '.edit', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_capacitacion = id;
                params.action = "cap_capacitaciones";
                params.operation = "editCapacitacion";
                params.target = "edit";
                //params.cerrado = $(this).closest('tr').attr('cerrado');
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;
            });


            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_capacitacion = id;
                params.action = "cap_capacitaciones";
                params.operation = "editCapacitacion";
                params.target = "view";
                //params.cerrado = $(this).closest('tr').attr('cerrado');
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;
            });


            $('#content').on('click', '.clone', function(){ //ok
                //alert('presionó en editar');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_capacitacion = id;
                params.action = "cap_capacitaciones";
                params.operation = "editCapacitacion";
                params.target = "clone";
                //params.cerrado = $(this).closest('tr').attr('cerrado');
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
                return false;
            });


            $('#content').on('click', '.empleados', function(){ //ok
                //alert('presiono sobre empleados');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_capacitacion = id;
                params.action = "cap_empleados";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side').attr('id_capacitacion', id);
                });
                return false;
            });



            $('#content').on('click', '.ediciones', function(){ //ok
                //alert('presiono sobre ediciones');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_capacitacion = id;
                params.action = "cap_ediciones";
                //params.operation = "etapas"; //entra en default
                $('#popupbox').load('index.php', params,function(){
                    //$("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    //$('#myModalLabel').html('');
                    $('#myModal').modal();
                    $('#etapas_left_side').attr('id_capacitacion', id);
                });
                return false;
            });



            $(document).on('click', '#new', function(){ //ok
                params={};
                params.action = "cap_capacitaciones";
                params.operation="newCapacitacion";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                });
            });



            var dialog;
            $('#content').on('click', '#example .delete', function(){ //ok

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar la capacitación?</p>",
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
                return false;

            });



            $.fn.borrar = function(id) {
                //alert(id);
                params={};
                params.id_capacitacion = id;
                params.action = "cap_capacitaciones";
                params.operation = "deleteCapacitacion";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Capacitación eliminada con exito</div>');
                        setTimeout(function() {
                                                dialog.modal('hide');
                                                $('#example').DataTable().ajax.reload();
                                            }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la capacitación</div>');

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

            <h4>Capacitaciones</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">
                <form id="search_form" name="search_form">


                    <!-- FILA DE ARRIBA -->
                    <div class="row">

                        <div class="form-group col-md-3">
                            <!--<label for="periodo" class="control-label">Periodo</label>-->
                            <select class="form-control" id="periodo" name="periodo">
                                <!--<option value="">Todos</option>-->
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
                            <select class="form-control selectpicker show-tick" id="id_categoria" name="id_categoria" data-live-search="true" data-size="5">
                                <option value="">Seleccione tipo de capacitación</option>
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


                        <div class="form-group col-md-3">
                            <!--<label for="search_vencimiento" class="control-label">Vencimiento</label>-->
                            <select class="form-control selectpicker show-tick" id="mes_programada" name="mes_programada" data-live-search="true" data-size="5">
                                <option value="">Todas las capacitaciones</option>
                                <option value="1">Programadas</option>
                                <option value="0">No Programadas</option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <!--<label for="search_contrato" class="control-label">Contrato</label>-->
                            <select multiple class="form-control selectpicker show-tick" id="id_contrato" name="id_contrato" data-selected-text-format="count" data-actions-box="true" data-live-search="true" data-size="5" title="Seleccione el contrato">
                                <!--<option value="">Seleccione un contrato</option>-->
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

                        </div>


                        <div class="form-group col-md-3">

                        </div>


                        <div class="form-group col-md-3">

                        </div>


                        <div class="form-group col-md-1">

                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>


                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button  id="new" type="button" class="form-control btn btn-default" title="Agregar capacitación" <?php //echo ( PrivilegedUser::dhasAction('PTN_INSERT', array(1)) )? '' : 'disabled' ?> >
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