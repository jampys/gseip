<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-emp, #confirm-ord').dialog({
            autoOpen: false
            //modal: true,
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
            //alert(params.id_renovacion);
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
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
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
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#empleado-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver orden de un parte
        $('.grid-ordenes').on('click', '.view', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_orden = id;
            params.action = "parte-orden";
            params.operation = "editOrden";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#orden-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
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
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_parte = $('#id_parte').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //eliminar empleado del parte
        $('.grid-empleados').on('click', '.delete', function(){ //ok
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-emp').dialog({ //se agregan botones al confirm dialog y se abre
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
            params.id_parte_empleado = id;
            params.id_parte = $('#id_parte').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "parte-empleado";
            params.operation = "deleteEmpleado";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-emp #myElemento").html('Empleado eliminado con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-emp #myElemento").hide();
                                            $('#empleado-form').hide();
                                            $('#confirm-emp').dialog('close');
                                          }, 2000);
                }else{
                    $("#confirm-emp #myElemento").html('Error al eliminar el empleado').addClass('alert alert-danger').show();
                }


            });

        };





        //eliminar orden del parte
        $('.grid-ordenes').on('click', '.delete', function(){ //ok
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-ord').dialog({ //se agregan botones al confirm dialog y se abre
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
            params.id_parte_orden = id;
            params.id_parte = $('#id_parte').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "parte-orden";
            params.operation = "deleteOrden";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-ord #myElemento").html('Orden eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-ordenes').load('index.php',{action:"parte-orden", id_parte: params.id_parte, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-ord #myElemento").hide();
                        $('#orden-form').hide();
                        $('#confirm-ord').dialog('close');
                    }, 2000);
                }else{
                    $("#confirm-ord #myElemento").html('Error al eliminar la orden').addClass('alert alert-danger').show();
                }


            });

        };


        //evento al salir o cerrar con la x el modal de etapas
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            $("#search").trigger("click");
        });




        //Guardar (calcular) parte
        $(document).on('click', '#calcular',function(){
            //alert('calcular');
            //throw new Error();

            //if ($("#empleado-form").valid()){
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
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data >=0){
                        //$("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('<a href="#" class="close" data-dismiss="alert">&times;</a><span class="glyphicon glyphicon-tags" ></span>&nbsp  mje fijo').addClass('alert alert-success').show();
                        //$('#left_side .grid-empleados').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        /*setTimeout(function() { $("#myElem").hide();
                            //$('#myModal').modal('hide');
                            $('#empleado-form').hide();
                        }, 2000);*/
                    }else{
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, 'json');

            //}
            return false;
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

                        <div class="col-md-6" id="left_side">

                            <input type="hidden" name="id_parte" id="id_parte" value="<?php print $view->parte->getIdParte() ?>">

                            <div class="form-group">
                                <label for="id_area" class="control-label">Área</label>
                                <select class="selectpicker form-control show-tick" id="id_area" name="id_area" data-live-search="true" data-size="5">
                                    <option value="">Seleccione un Área</option>
                                    <?php foreach ($view->areas as $ar){ ?>
                                        <option value="<?php echo $ar['id_area']; ?>"
                                            <?php echo ($ar['id_area'] == $view->parte->getIdArea() )? 'selected' :'' ?>
                                            >
                                            <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_vehiculo" class="control-label">Vehículo</label>
                                <select class="selectpicker form-control show-tick" id="id_vehiculo" name="id_vehiculo" data-live-search="true" data-size="5">
                                    <option value="">Seleccione un Vehículo</option>
                                    <?php foreach ($view->vehiculos as $ar){ ?>
                                        <option value="<?php echo $ar['id_vehiculo']; ?>"
                                            <?php echo ($ar['id_vehiculo'] == $view->parte->getIdVehiculo())? 'selected' :'' ?>
                                            >
                                            <?php echo $ar['nro_movil'].' '.$ar['modelo']; ?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="id_evento" class="control-label">Evento</label>
                                <select class="selectpicker form-control show-tick" id="id_evento" name="id_evento" data-live-search="true" data-size="5">
                                    <option value="">Seleccione un evento</option>
                                    <?php foreach ($view->eventos as $ar){ ?>
                                        <option value="<?php echo $ar['id_evento']; ?>"
                                            <?php echo ($ar['id_evento'] == $view->parte->getIdEvento())? 'selected' :'' ?>
                                            >
                                            <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                        </option>
                                    <?php  } ?>
                                </select>
                            </div>



                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="hs_normal">Hs. Normal</label>
                                        <input class="form-control" type="text" name="hs_normal" id="hs_normal" value = "<?php print $view->parte->getHsNormal() ?>" placeholder="Hs. Normal">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="hs_50">Hs. 50%</label>
                                        <input class="form-control" type="text" name="hs_50" id="hs_50" value = "<?php print $view->parte->getHs50() ?>" placeholder="Hs. 50%">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="hs_100">Hs. 100%</label>
                                        <input class="form-control" type="text" name="hs_100" id="hs_100" value = "<?php print $view->parte->getHs100() ?>" placeholder="Hs. 100%">
                                    </div>
                                </div>

                            </div>


                            <!-- seccion de empleados -->
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-empleados" title="Mostrar empleados">Empleados</button>
                                </div>

                                <div class="col-md-4">

                                </div>

                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" id="add-empleado" name="add-empleado" title="Agregar empleado">
                                        <i class="fas fa-plus"></i>&nbsp
                                    </button>
                                </div>
                            </div>

                            <div id="demo-empleados" class="collapse">
                                <div class="grid-empleados">
                                    <?php include_once('view/novedades_partes/empleadosGrid.php');?>
                                </div>
                            </div>


                            <br/>


                            <!-- seccion de ordenes -->
                            <div class="row">
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-ordenes" title="Mostrar órdenes">Órdenes</button>
                                </div>

                                <div class="col-md-4">

                                </div>

                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" id="add-orden" name="add-orden" title="Agregar orden">
                                        <i class="fas fa-plus"></i>&nbsp
                                    </button>
                                </div>
                            </div>

                            <div id="demo-ordenes" class="collapse">
                                <div class="grid-ordenes">
                                    <?php include_once('view/novedades_partes/ordenesGrid.php');?>
                                </div>
                            </div>






                        </div>



                        <div class="col-md-6" id="right_side">

                        </div>


                </div>


                <!-- <div id="myElem" class="msg" style="display:none"></div>  para mostrar los resultados de "calcular"   -->
                <div id="myElem" class="msg fade in" style="display:none">

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="calcular" name="calcular" type="submit">Calcular</button>
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



<div id="confirm-emp">
    <div class="modal-body">
        ¿Desea eliminar el empleado?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>




<div id="confirm-ord">
    <div class="modal-body">
        ¿Desea eliminar la orden?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


