<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-etp').dialog({
            autoOpen: false
            //modal: true,
        });

        //para editar empleado de un parte
        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_empleado = id;
            params.action = "parte-empleado";
            params.operation = "editEmpleado";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //para ver empleado de un parte
        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_parte_empleado = id;
            params.action = "parte-empleado";
            params.operation = "editEmpleado";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#etapas_right_side fieldset").prop("disabled", true);
                $("#etapa-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo empleado al parte
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "parte-empleado";
            params.operation = "newEmpleado";
            //params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            params.id_parte = $('#id_parte').val();
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Guardar parte-empleado luego de ingresar nuevo o editar
        $('#myModal').on('click', '#submit',function(){
            //alert('guardar etapa');

            if ($("#etapa-form").valid()){

                var params={};
                params.action = 'parte-empleado';
                params.operation = 'saveEmpleado';
                params.id_parte = $('#id_parte').val();
                params.id_parte_empleado = $('#id_parte_empleado').val();
                params.id_empleado = $('#id_empleado').val();
                params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#etapa-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        $('#etapas_left_side .grid').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#etapa-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        //$(document).on('click', '#example .delete', function(){
        $('#etapas_left_side').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            //throw new Error();
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-etp').dialog({ //se agregan botones al confirm dialog y se abre
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
            //params.id_postulacion = $('#etapas_left_side #add').attr('id_postulacion');
            params.action = "parte-empleado";
            params.operation = "deleteEmpleado";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-etp #myElemento").html('Empleado eliminado con exito').addClass('alert alert-success').show();
                    $('#etapas_left_side .grid').load('index.php',{action:"parte-empleado", id_parte: params.id_parte, operation:"refreshGrid"});
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-etp #myElemento").hide();
                                            $('#etapa-form').hide();
                                            $('#confirm-etp').dialog('close');
                                          }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar el empleado').addClass('alert alert-danger').show();
                }


            });

        };



        //evento al salir o cerrar con la x el modal de etapas
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            $("#search").trigger("click");
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

                        <div class="col-md-6" id="etapas_left_side">

                            <input type="hidden" name="id_parte" id="id_parte" value="<?php print $view->parte->getIdParte() ?>">

                            <div class="form-group">
                                <label for="etapa" class="control-label">Área</label>
                                <select class="selectpicker form-control show-tick cu_id_area" data-live-search="true" data-size="5">
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
                                <label for="etapa" class="control-label">Vehículo</label>
                                <select class="selectpicker form-control show-tick cu_id_vehiculo" data-live-search="true" data-size="5">
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
                                <label for="etapa" class="control-label">Evento</label>
                                <select class="selectpicker form-control show-tick cu_id_evento" data-live-search="true" data-size="5">
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
                                        <input class="form-control" type="text" name="hs_normal" id="hs_normal" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. Normal">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="hs_50">Hs. 50%</label>
                                        <input class="form-control" type="text" name="hs_50" id="hs_50" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. 50%">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="codigo">Hs. 100%</label>
                                        <input class="form-control" type="text" name="hs_100" id="hs_100" value = "<?php //print $view->puesto->getCodigo() ?>" placeholder="Hs. 100%">
                                    </div>
                                </div>

                            </div>



                            <hr/>
                            <div class="clearfix">
                                <button class="btn btn-primary btn-sm pull-right" id="add" name="add" type="submit" title="Agregar empleado">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/novedades_partes/empleadosGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-6" id="etapas_right_side">

                        </div>


                </div>


                <!--<div id="myElem" class="msg" style="display:none"></div>-->

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary btn-sm" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



<div id="confirm-etp">
    <div class="modal-body">
        ¿Desea eliminar el empleado?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


