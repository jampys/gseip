<!DOCTYPE html>

<style type="text/css">

    .inactive small.text-muted{
        color: red;
    }

</style>

<html lang="en">
<head>

    <?php
    require_once('templates/libraries.php');
    ?>


    <script type="text/javascript">


        $(document).ready(function(){

            $('.selectpicker').selectpicker({ //ok
                //propiedades del selectpicker

            }).change(function(){
                $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                                 // elimine el mensaje de requerido de jquery validation
            });


            moment.locale('es');
            $('#daterange').daterangepicker({
                startDate: moment().subtract(1, 'weeks'),
                endDate: moment(),
                locale: {
                    format: 'DD/MM/YYYY',
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "customRangeLabel": "Rango personalizado"
                }
            }, function(start, end) {
                $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            }).on("apply.daterangepicker", function (e, picker) {
                //picker.element.val(picker.startDate.format(picker.locale.format));
                //picker.element.valid();
                $('#id_periodo').selectpicker('val', '');
            });

            var drp = $('#daterange').data('daterangepicker');



            $(document).on('click', '#search', function(){ //ok
                //alert('presiono en buscar');
                //var id = $(this).attr('data-id');
                //preparo los parametros
                /*params={};
                params.startDate = drp.startDate.format('DD/MM/YYYY');
                params.endDate = drp.endDate.format('DD/MM/YYYY');
                params.search_contrato = $("#add_contrato").val();
                params.id_periodo = $("#id_periodo").val();
                params.cuadrilla = $("#cuadrilla").val();
                params.action = "partes";
                params.operation = "refreshGrid";
                $('#content').load('index.php', params);*/
                $('#example').DataTable().ajax.reload();
            });


            $(document).on('click', '#txt', function(){ //ok

                //alert('toco en txt');
                //preparo los parametros
                params={};
                params.action = "partes";
                params.operation = "loadExportTxt";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();

                    $('#myModal #id_contrato').val($('#search_contrato').val());
                    $('.selectpicker').selectpicker('refresh');
                });
                return false;
            });


            $(document).on('click', '#control', function(){ //ok
                //alert('toco en txt');
                //preparo los parametros
                params={};
                params.action = "partes2";
                params.operation = "loadControl";
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    $('#myModal #id_contrato').val($('#search_contrato').val());
                    $('.selectpicker').selectpicker('refresh');
                });
                return false;
            });



            //Select dependiente: al seleccionar contrato carga periodos vigentes
            $('#add-form').on('change', '#add_contrato', function(e){
                //alert('seleccionó un contrato');
                //throw new Error();
                params={};
                params.action = "partes2";
                params.operation = "getPeriodosAndCuadrillas";
                //params.id_convenio = $('#id_parte_empleado option:selected').attr('id_convenio');
                params.id_contrato = $('#add_contrato').val();
                //params.activos = 1;

                $('#id_periodo').empty();
                $('#cuadrilla').empty();


                $.ajax({
                    url:"index.php",
                    type:"post",
                    //data:{"action": "parte-empleado-concepto", "operation": "getConceptos", "id_objetivo": <?php //print $view->objetivo->getIdObjetivo() ?>},
                    data: params,
                    dataType:"json",//xml,html,script,json
                    success: function(data, textStatus, jqXHR) {

                        //$("#id_periodo").html('<option value="">Seleccione un período</option>');
                        if(Object.keys(data['periodos']).length > 0){
                            $.each(data['periodos'], function(indice, val){
                                var label = data['periodos'][indice]["nombre"]+' ('+data['periodos'][indice]["fecha_desde"]+' - '+data['periodos'][indice]["fecha_hasta"]+')';
                                $("#id_periodo").append('<option value="'+data['periodos'][indice]["id_periodo"]+'"'
                                                        +' fecha_desde="'+data['periodos'][indice]["fecha_desde"]+'"'
                                                        +' fecha_hasta="'+data['periodos'][indice]["fecha_hasta"]+'"'
                                +'>'+label+'</option>');
                            });

                            //si es una edicion o view, selecciona el concepto.
                            //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        }

                        $("#cuadrilla").html('<option value="">Seleccione una cuadrilla</option>');
                        if(Object.keys(data['cuadrillas']).length > 0){
                            $.each(data['cuadrillas'], function(indice, val){
                                let label = data['cuadrillas'][indice]["nombre"];
                                let inactive_class = (data['cuadrillas'][indice]["disabled"])? 'inactive' : '';
                                let innactive_text = (data['cuadrillas'][indice]["disabled"])? 'Inactiva' : '';

                                $("#cuadrilla").append('<option class="'+inactive_class+'" value="'+data['cuadrillas'][indice]["nombre"]+'"'
                                +'data-subtext="'+innactive_text+'">'+label+'</option>');
                            });

                            //si es una edicion o view, selecciona el concepto.
                            //$("#id_concepto").val(<?php //print $view->concepto->getIdConceptoConvenioContrato(); ?>);
                        }

                        $('#id_periodo').selectpicker('refresh');
                        $('#cuadrilla').selectpicker('refresh');
                        $('#add_fecha').val('');

                    },
                    error: function(data, textStatus, errorThrown) {
                        //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                        alert(data.responseText);
                    }

                });


            });


            $('#add_contrato').trigger('change');



            //Al seleccionar el periodo restringe el rango de fechas del datepicker
            $('#add-form').on('change', '#id_periodo', function(e){
                //alert('seleccionó un periodo');
                //throw new Error();
                var fecha_desde = $('#id_periodo option:selected').attr('fecha_desde');
                var fecha_hasta = $('#id_periodo option:selected').attr('fecha_hasta');
                drp.setStartDate(fecha_desde);
                drp.setEndDate(fecha_hasta);
            });




            //para editar un parte
            //$(document).on('click', '.edit', function(){ //ok
            $('#content').on('click', '.edit', function(){ //ok
                //alert('editar parte');
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_parte = id;
                params.action = "partes";
                params.operation = "editParte";
                params.target = "edit";
                //alert(params.id_renovacion);
                $('#popupbox').load('index.php', params,function(){
                    $('#myModal').modal();
                    //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
                    //$('#id_vencimiento').prop('disabled', true).selectpicker('refresh');
                });
                return false;
            });

            //para ver un parte
            $('#content').on('click', '.view', function(){ //ok
                var id = $(this).closest('tr').attr('data-id');
                params={};
                params.id_parte = id;
                params.action = "partes";
                params.operation = "editParte";
                params.target = "view";
                $('#popupbox').load('index.php', params,function(){
                    //$("#parte-form input, #parte-form .selectpicker").prop("disabled", true);
                    //$('.selectpicker').selectpicker('refresh');
                    //$('.modal-footer').css('display', 'none');
                    $('#myModal').modal();
                });
                return false;
            });

            //para agregar partes de un contrato
            $(document).on('click', '#new', function(){ //ok

                if ($("#add-form").valid()){

                    params={};
                    params.action = "partes";
                    params.operation="newParte";
                    params.add_contrato = $("#add_contrato").val();
                    params.fecha_parte = $("#add_fecha").val(); //para mostrar en el titulo del modal
                    params.id_periodo = $("#id_periodo").val();
                    params.contrato = $("#add_contrato option:selected").text(); //para mostrar en el titulo del modal

                    /*$('#popupbox').load('index.php', params,function(){
                        $('#myModal').modal();
                    });*/
                    $("#content").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Obteniendo informacion de cuadrillas...').addClass('alert alert-info').show();
                    $('#content').load('index.php', params,function(){
                        $("#content").removeClass('alert alert-info');
                    });

                }

            });


            //eliminar parte
            var dialog;
            $('#content').on('click', '#example .delete', function(){

                var id = $(this).closest('tr').attr('data-id');
                dialog = bootbox.dialog({
                    message: "<p>¿Desea eliminar el parte?<br/>Se elimiminará el parte completo, incluyendo empleados, conceptos y ordenes.</p>",
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
                params.id_parte = id;
                params.action = "partes";
                params.operation = "deleteParte";

                $.post('index.php',params,function(data, status, xhr){
                    if(data >=0){
                        dialog.find('.modal-footer').html('<div class="alert alert-success">Parte eliminado con exito</div>');
                        setTimeout(function() {
                            dialog.modal('hide');
                            $("#search").trigger("click");
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el parte</div>');

                });

            };


            /*$('#add-form').validate({
                rules: {
                    add_fecha: {required: true},
                    add_contrato: {required: true},
                    id_periodo: {required: true}
                },
                messages:{
                    add_fecha: "Seleccione la fecha",
                    add_contrato: "Seleccione el contrato",
                    id_periodo: "Seleccione el período"
                }

            });*/



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

            <h4>Consulta de novedades</h4>
            <hr class="hr-primary"/>

            <div class="clearfix">




                <!-- FILA DE ARRIBA -->
                <div class="row">

                    <form id="add-form" name="add-form">

                        <div class="form-group col-md-3">
                            <!--<label for="add_contrato" class="control-label">Nuevos partes</label>-->
                            <select class="form-control selectpicker show-tick" id="add_contrato" name="add_contrato" data-live-search="true" data-size="5">
                                <!--<option value="">Seleccione un contrato</option>-->
                                <?php foreach ($view->contratos as $con){
                                    ?>
                                    <option value="<?php echo $con['id_contrato']; ?>" >
                                        <?php echo $con['nombre'].' '.$con['nro_contrato'];?>
                                    </option>
                                <?php  } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <!--<label for="id_periodo" class="control-label">&nbsp;</label>-->
                            <select class="form-control selectpicker show-tick" id="id_periodo" name="id_periodo" title="Seleccione un período" data-live-search="true" data-size="5">
                                <!-- se completa dinamicamente desde javascript  -->
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <!--<label for="search">&nbsp;</label>-->
                            <button type="button" class="form-control btn btn-default" title="Buscar novedad" id="search">
                                <span class="glyphicon glyphicon-search fa-lg dp_blue"></span>
                            </button>
                        </div>

                        <!--<div class="form-group col-md-3">
                            <label class="control-label" for="add_fecha">&nbsp;</label>
                            <div class="input-group date">
                                <input class="form-control" type="text" name="add_fecha" id="add_fecha" value = "" placeholder="DD/MM/AAAA">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>-->



                        <!--<div class="form-group col-md-2">
                            <label for="search">&nbsp;</label>
                            <button type="button" class="form-control btn btn-default" title="nuevo parte" id="new" <?php echo ( PrivilegedUser::dhasAction('PAR_INSERT', array(1)) )? '' : 'disabled' ?>>
                                <span class="glyphicon glyphicon-plus fa-lg dp_green"></span>
                            </button>
                        </div>-->

                        <div class="form-group col-md-5">

                        </div>


                    </form>



                </div>

                <!-- FILA DE ABAJO -->
                <div class="row">


                    <form id="search_form" name="search_form">

                        <div class="form-group col-md-3">
                            <!--<label for="search_vencimiento" class="control-label">Buscar partes</label>-->
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="daterange" id="daterange" placeholder="DD/MM/AAAA - DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>
                        </div>


                        <div class="form-group col-md-3">
                            <!--<label for="search_contrato" class="control-label">&nbsp;</label>-->
                            <select class="form-control selectpicker show-tick" id="cuadrilla" name="cuadrilla" data-live-search="true" data-size="5">
                                <!-- se completa dinamicamente desde javascript  -->
                            </select>
                        </div>


                        <div class="form-group col-md-4">

                        </div>



                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button id="control" class="form-control btn btn-default" href="#" title="Control de novedades">
                                <!--<span class="glyphicon glyphicon-check fa-lg dp_blue">-->
                                    <i class="fas fa-tasks fa-lg dp_blue"></i>
                            </button>
                        </div>

                        <div class="form-group col-md-1">
                            <!--<label for="search">&nbsp;</label>-->
                            <button id="txt" class="form-control btn btn-default" href="#" title="Exportar novedades"><i class="fas fa-file-export fa-fw fa-lg dp_blue"></i></button>
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