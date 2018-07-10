<!DOCTYPE html>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">

        $(document).ready(function(){

            $('.selectpicker').selectpicker();


            $('.input-group.date').datepicker({ //ok para fecha (nuevo)
                //inline: true
                format:"dd/mm/yyyy",
                language: 'es',
                todayHighlight: true
            });

            $('.input-daterange').datepicker({ //ok para fecha desde-hasta (buscar)
                //todayBtn: "linked",
                format:"dd/mm/yyyy",
                language: 'es',
                todayHighlight: true
            });


            $(document).on('click', '#search', function(){ //ok
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                params={};
                //params.id_empleado = $('#search_empleado option:selected').attr('id_empleado');
                //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
                //params.search_puesto = $("#search_puesto").val();
                //params.search_localidad = $("#search_localidad").val();
                //params.search_contrato = $("#search_contrato").val();
                //params.renovado = $('#search_renovado').prop('checked')? 1:0;
                params.action = "partes";
                params.operation = "refreshGrid";
                //alert(params.id_grupo);
                $('#content').load('index.php', params);
            });



            $(document).on('click', '.edit', function(){
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

            $(document).on('click', '.view', function(){
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_busqueda = id;
                params.action = "busquedas";
                params.operation = "editBusqueda";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    $("fieldset").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    $('.modal-footer').css('display', 'none');
                    $('#myModalLabel').html('');
                    $('#myModal').modal();
                })

            });


            $(document).on('click', '#new', function(){ //ok

                if ($("#add-form").valid()){

                    params={};
                    params.action = "partes";
                    params.operation="newParte";
                    $('#popupbox').load('index.php', params,function(){
                        $('#myModal').modal();
                    });

                }

            });



            $(document).on('click', '#cancel',function(){
                $('#myModal').modal('hide');
            });




            $(document).on('click', '#example .delete', function(){
                alert('Funcionalidad en desarrollo');
                throw new Error();
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

                    ]
                }).dialog('open');
                return false;
            });


            $.fn.borrar = function(id) {
                //alert(id);
                //preparo los parametros
                params={};
                params.id_habilidad_empleado = id;
                params.action = "habilidad-empleado";
                params.operation = "deleteHabilidadEmpleado";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        $("#myElemento").html('Habilidad eliminada con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"habilidad-empleado", operation: "buscar", cuil: $("#cuil").val(), id_habilidad: $("#id_habilidad").val()});
                        $("#search").trigger("click");
                    }else{
                        $("#myElemento").html('Error al eliminar la habilidad').addClass('alert alert-danger').show();
                    }
                    setTimeout(function() { $("#myElemento").hide();
                        $('#confirm').dialog('close');
                    }, 2000);

                });

            };


            $('#add-form').validate({
                rules: {
                    add_fecha: {required: true},
                    add_contrato: {required: true}
                },
                messages:{
                    add_fecha: "Seleccione la fecha",
                    add_contrato: "Seleccione el contrato"
                }

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

            <h4>Partes diarios cuadrilla</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">




                <!-- FILA DE ARRIBA -->
                <div class="row">

                    <form id="add-form" name="add-form">

                        <div class="form-group col-md-3">
                            <label class="control-label" for="add_fecha">Fecha</label>
                            <div class="input-group date">
                                <input class="form-control" type="text" name="add_fecha" id="add_fecha" value = "" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="add_contrato" class="control-label">Contrato</label>
                            <select class="form-control selectpicker show-tick" id="add_contrato" name="add_contrato" data-live-search="true" data-size="5">
                                <option value="">Seleccione un contrato</option>
                                <?php foreach ($view->contratos as $con){
                                    ?>
                                    <option value="<?php echo $con['id_contrato']; ?>" >
                                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>



                        <!--<div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="new">Nueva renovación</button>
                        </div>-->

                        <div class="form-group col-md-4">

                        </div>


                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" style="background-color: #337ab7" class="form-control btn btn-primary btn-sm" title="nuevo parte" id="new" <?php echo ( PrivilegedUser::dhasAction('BUS_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus"></span>
                            </button>
                        </div>



                    </form>



                </div>

                <!-- FILA DE ABAJO -->
                <div class="row">


                    <form id="search_form" name="search_form">


                        <div class="form-group col-md-3">
                            <label for="search_localidad" class="control-label">Área</label>
                            <select class="form-control selectpicker show-tick" id="search_localidad" name="search_localidad" data-live-search="true" data-size="5">
                                <option value="">Seleccione un área</option>
                                <?php foreach ($view->areas as $ar){
                                    ?>
                                    <option value="<?php echo $ar['id_area']; ?>">
                                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label for="search_contrato" class="control-label">Contrato</label>
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

                        <div class="form-group col-md-4">
                            <label for="search_vencimiento" class="control-label">Fecha desde / hasta</label>
                            <div class="input-group input-daterange">
                                <input class="form-control" type="text" name="search_fecha_desde" id="search_fecha_desde" value = "<?php //print $view->contrato->getFechaDesde() ?>" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">a</div>
                                <input class="form-control" type="text" name="search_fecha_hasta" id="search_fecha_hasta" value = "<?php //print $view->contrato->getFechaHasta() ?>" placeholder="DD/MM/AAAA">
                            </div>
                        </div>


                        <!--<div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="search">Buscar</button>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" id="new">Nueva renovación</button>
                        </div>-->

                        <!--<div class="form-group col-md-2">
                            <label for="search_renovado" class="control-label">&nbsp;</label>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="search_renovado" name="search_renovado">
                                    <a href="#" title="Funcionalidad en construcción">Ver todos</a>
                                </label>
                            </div>
                        </div>-->

                        <div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-primary btn-sm" title="Buscar partes" id="search">
                                <span class="glyphicon glyphicon-search"></span>
                            </button>
                        </div>

                    </form>

                </div>






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