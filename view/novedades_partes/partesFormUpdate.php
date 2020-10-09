<script type="text/javascript">


    $(document).ready(function(){

        $('#hs_normal').timepicker({
            showMeridian: false,
            //defaultTime: false
            defaultTime: '09:00 AM'
        });

        $('#hs_50, #hs_100').timepicker({
            showMeridian: false,
            //defaultTime: false
            defaultTime: '00:00 AM'
        });

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //para editar empleado de un parte
        $('.grid-empleados').on('click', '.edit', function(){ //ok
            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_empleado = id;
            params.action = "parte-empleado";
            params.operation = "editEmpleado";
            params.id_contrato = $('#myModal #id_contrato').val();
            //alert(params.id_contrato);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });

        //para editar orden de un parte
        $('.grid-ordenes').on('click', '.edit', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "edit";
            $('#right_side').load('index.php', params,function(){
            })
        });

        //para editar concepto de un parte
        $('.grid-conceptos').on('click', '.edit', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_empleado_concepto = id;
            params.action = "parte-empleado-concepto";
            params.operation = "editConcepto";
            params.id_parte = $('#id_parte').val();
            //params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //$("#right_side fieldset").prop("disabled", true);
                //$("#concepto-form #footer-buttons button").css('display', 'none');
                $('#id_parte_empleado').trigger('change'); //para cargar el combo de conceptos
                $('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver empleado de un parte
        $('.grid-empleados').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_empleado = id;
            params.action = "parte-empleado";
            params.operation = "editEmpleado";
            params.id_contrato = $('#myModal #id_contrato').val();
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$("#right_side fieldset").prop("disabled", true);
                //$("#empleado-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver orden de un parte
        $('.grid-ordenes').on('click', '.view', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //$("#right_side fieldset").prop("disabled", true);
                //$("#orden-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            })
        });

        //para clonar orden de un parte
        $('.grid-ordenes').on('click', '.clone', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            params.target = "clone";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //$("#right_side fieldset").prop("disabled", true);
                //$("#orden-form #footer-buttons button").css('display', 'none');
                $('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver concepto de un parte
        $('.grid-conceptos').on('click', '.view', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_parte_empleado_concepto = id;
            params.action = "parte-empleado-concepto";
            params.operation = "editConcepto";
            params.id_parte = $('#id_parte').val();
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //$("#right_side fieldset").prop("disabled", true);
                //$("#concepto-form #footer-buttons button").css('display', 'none');
                $('#id_parte_empleado').trigger('change'); //para cargar el combo de conceptos
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo empleado al parte
        $('#left_side').on('click', '#add-empleado', function(){ //ok
            params={};
            params.action = "parte-empleado";
            params.operation = "newEmpleado";
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_parte = $('#id_parte').val();
            params.id_contrato = $('#myModal #id_contrato').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Abre formulario para ingresar una nueva orden al parte
        $('#left_side').on('click', '#add-orden', function(){ //ok
            params={};
            params.action = "parte-orden";
            params.operation = "newOrden";
            params.id_parte = $('#id_parte').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
            })
        });


        //Abre formulario para ingresar un concepto de manera manual al parte
        $('#left_side').on('click', '#add-concepto', function(){ //ok
            params={};
            params.action = "parte-empleado-concepto";
            params.operation = "newConcepto";
            params.id_parte = $('#id_parte').val();
            $('#right_side').load('index.php', params,function(){
            })
        });

        //Abre formulario para editar comentarios del parte
        $('#left_side').on('click', '#edit-comentarios', function(){
            params={};
            params.action = "parte-comentarios";
            params.operation = "editComentarios";
            params.id_parte = $('#id_parte').val();
            params.id_periodo = $('#id_periodo').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
            })
        });


        //eliminar empleado del parte
        //var dialog;
        $('.grid-empleados').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar el empleado?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrarE(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrarE = function(id) {
            //alert(id);
            params={};
            params.id_parte_empleado = id;
            params.id_parte = $('#id_parte').val();
            params.action = "parte-empleado";
            params.operation = "deleteEmpleado";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Empleado eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#left_side .grid-conceptos').load('index.php',{action:"parte-empleado-concepto", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#empleado-form').hide();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el empleado</div>');

            });

        };





        //eliminar orden del parte
        var dialog;
        $('.grid-ordenes').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la orden?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrarO(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrarO = function(id) {
            //alert(id);
            params={};
            params.id_parte_orden = id;
            params.id_parte = $('#id_parte').val();
            params.action = "parte-orden";
            params.operation = "deleteOrden";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Orden eliminada con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#orden-form').hide();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la orden</div>');

            });

        };





        //eliminar concepto del parte
        //var dialog;
        $('.grid-conceptos').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar el concepto?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrarC(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrarC = function(id) {
            //alert(id);
            params={};
            params.id_parte_empleado_concepto = id;
            params.id_parte = $('#id_parte').val();
            params.action = "parte-empleado-concepto";
            params.operation = "deleteConcepto";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Concepto eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#left_side .grid-conceptos').load('index.php',{action:"parte-empleado-concepto", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#concepto-form').hide();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el concepto</div>');

            });

        };





        //evento al salir o cerrar con la x el modal de actualizar el parte
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salió de editar el parte');
            //$("#search").trigger("click");
        });




        //Guardar (calcular) parte
        //$(document).on('click', '#calcular',function(){
        $("#myModal").on('click', '#calcular', function () {
            //alert('calcular');
            //throw new Error();

            if ($("#parte-form").valid()){

                var params={};
                params.action = 'partes';
                params.operation = 'calcularParte';
                params.id_parte = $('#id_parte').val();
                params.id_area = $('#id_area').val();
                params.id_vehiculo = $('#id_vehiculo').val();
                params.id_evento = $('#id_evento').val();
                params.hs_normal = $('#hs_normal').val();
                params.hs_50 = $('#hs_50').val();
                params.hs_100 = $('#hs_100').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.id_parte);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data[0]['flag'] >=0){


                        //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#msg-container").html('<div id="myElem" class="msg alert alert-success fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-check fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                        $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        $('#left_side .grid-conceptos').load('index.php',{action:"parte-empleado-concepto", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        /*setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#empleado-form').hide();
                        }, 2000);*/
                    }else{
                        //$("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                        $("#msg-container").html('<div id="myElem" class="msg alert alert-danger fade in"><a href="#" class="close" data-dismiss="alert">&times;</a><i class="fas fa-exclamation-triangle fa-fw"></i></i>&nbsp '+data[0]['msg']+'</div>');
                    }

                }, 'json');

            }
            return false;
        });


        $('#parte-form').validate({ //ok
            rules: {
                id_area: {required: true},
                hs_normal: {
                    require_from_group: [1, ".hs-group"]
                    //digits: true,
                    //maxlength: 6
                },
                hs_50: {
                    require_from_group: [1, ".hs-group"]
                    //digits: true,
                    //maxlength: 6
                },
                hs_100: {
                    require_from_group: [1, ".hs-group"]
                    //digits: true,
                    //maxlength: 6
                }
            },
            messages:{
                id_area: "Seleccione un área",
                hs_normal: {
                    require_from_group: "Complete al menos un tipo de hora"
                    //digits: "Ingrese solo números",
                    //maxlength: "Máximo 6 dígitos"
                },
                hs_50: {
                    require_from_group: "Complete al menos un tipo de hora"
                    //digits: "Ingrese solo números",
                    //maxlength: "Máximo 6 dígitos"
                },
                hs_100: {
                    require_from_group: "Complete al menos un tipo de hora"
                    //digits: "Ingrese solo números",
                    //maxlength: "Máximo 6 dígitos"
                }

            }

        });


        //cancel de formulario de parte-orden. Movido desde orden_detailForm.php
        $('#myModal').on('click', '#orden-form #cancel', function(){
            $('#orden-form').hide();
         });





    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>

            <div class="modal-body">
                
                <div class="row">

                        <div class="col-md-7" id="left_side">

                            <form name ="parte-form" id="parte-form" method="POST" action="index.php">

                                <input type="hidden" name="id_parte" id="id_parte" value="<?php print $view->parte->getIdParte() ?>">
                                <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->parte->getIdContrato() ?>">

                                <div class="form-group">
                                    <label for="id_area" class="control-label">Área</label>
                                    <select class="selectpicker form-control show-tick" id="id_area" name="id_area" data-live-search="true" data-size="5" title="Seleccione un área">
                                        <?php foreach ($view->areas as $ar){ ?>
                                            <option value="<?php echo $ar['id_area']; ?>"
                                                <?php echo ($ar['id_area'] == $view->parte->getIdArea() )? 'selected' :'' ?>
                                                >
                                                <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                            </option>
                                        <?php  } ?>
                                    </select>
                                </div>

                                <!--<div class="form-group">
                                    <label for="id_vehiculo" class="control-label">Vehículo</label>
                                    <select class="selectpicker form-control show-tick" id="id_vehiculo" name="id_vehiculo" data-live-search="true" data-size="5">
                                        <option value="">Seleccione un Vehículo</option>
                                        <?php //foreach ($view->vehiculos as $ar){ ?>
                                            <option value="<?php //echo $ar['id_vehiculo']; ?>"
                                                <?php //echo ($ar['id_vehiculo'] == $view->parte->getIdVehiculo())? 'selected' :'' ?>
                                                >
                                                <?php //echo $ar['nro_movil'].' '.$ar['modelo']; ?>
                                            </option>
                                        <?php  //} ?>
                                    </select>
                                </div>-->

                                <div class="form-group">
                                    <label for="id_evento" class="control-label">Evento</label>
                                    <select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">
                                        <option value="">Seleccione un evento</option>
                                        <?php foreach ($view->eventos as $ar){ ?>
                                            <option value="<?php echo $ar['id_evento']; ?>"
                                                <?php echo ($ar['enabled'])? '':'disabled'; ?>
                                                <?php echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                                                >
                                                <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                            </option>
                                        <?php  } ?>
                                    </select>
                                </div>



                                <!--<div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="hs_normal">Hs. Normal</label>
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input type="text" class="form-control input-small hs-group" name="hs_normal" id="hs_normal" value = "<?php //print $view->parte->getHsNormal() ?>" >
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="hs_50">Hs. 50%</label>
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input type="text" class="form-control input-small hs-group" name="hs_50" id="hs_50" value = "<?php //print $view->parte->getHs50() ?>" >
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label" for="hs_100">Hs. 100%</label>
                                            <div class="input-group bootstrap-timepicker timepicker">
                                                <input type="text" class="form-control input-small hs-group" name="hs_100" id="hs_100" value = "<?php //print $view->parte->getHs100() ?>" >
                                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                </div>-->

                                <br/>


                                <!-- seccion de empleados -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-empleados" title="Mostrar empleados">Empleados</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-default btn-block" id="add-empleado" name="add-empleado" title="Agregar empleado" <?php //echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?>disabled >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
                                        </button>
                                </div>
                                </div>

                                <div id="demo-empleados" class="collapse">
                                    <div class="grid-empleados">
                                        <?php include_once('view/novedades_partes/empleadosGrid.php');?>
                                    </div>
                                </div>


                                <br/>


                                <!-- seccion de conceptos -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-conceptos" title="Mostrar conceptos">Conceptos</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-default btn-block" id="add-concepto" name="add-concepto" title="Agregar concepto" <?php //echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?>disabled >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
                                        </button>
                                    </div>
                                </div>

                                <div id="demo-conceptos" class="collapse">
                                    <div class="grid-conceptos">
                                        <?php include_once('view/novedades_partes/conceptosGrid.php');?>
                                    </div>
                                </div>


                                <br/>


                                <!-- seccion de ordenes -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-default btn-block" id="add-orden" name="add-orden" title="Agregar orden" <?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?> >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
                                        </button>
                                    </div>
                                </div>


                                <div id="demo-ordenes" class="collapse">
                                    <div class="grid-ordenes">
                                        <?php include_once('view/novedades_partes/ordenesGrid.php');?>
                                    </div>
                                </div>


                                <br/>


                                <!-- seccion de comentarios -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <!--<button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>-->
                                        <button type="button" class="btn btn-primary btn-block" id="edit-comentarios" name="edit-comentarios" title="Comentarios">Comentarios</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <!--<button type="button" class="btn btn-default btn-block" id="add-orden" name="add-orden" title="Agregar orden" <?php //echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?> >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
                                        </button>-->
                                    </div>
                                </div>





                            </form>


                        </div>



                        <div class="col-md-5" id="right_side">

                        </div>


                </div>


                <!-- <div id="myElem" class="msg" style="display:none"></div>  para mostrar los resultados de "calcular"   -->
                <div id="msg-container">

                </div>
            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="calcular" name="calcular" type="submit" <?php //echo ($view->target!='view' )? '' : 'disabled' ?> >Calcular</button>-->
                <button class="btn btn-default" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>


