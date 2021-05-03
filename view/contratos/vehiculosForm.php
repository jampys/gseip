<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            //alert('editar vehiculo-contrato');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_contrato_vehiculo = id;
            params.action = "contrato-vehiculo";
            params.operation = "editVehiculo";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
            })
        });


        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_contrato_vehiculo = id;
            params.action = "contrato-vehiculo";
            params.operation = "editVehiculo";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#etapas_right_side fieldset").prop("disabled", true);
                $("#contrato-vehiculo-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo vehiculo al grupo
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "contrato-vehiculo";
            params.operation = "newVehiculo";
            params.id_contrato = $('#etapas_left_side #add').attr('id_contrato');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_contrato').val(params.id_contrato);
            })
        });


        //Guardar contrato-vehiculo luego de ingresar nuevo o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar grupo-vehiculo');

            if ($("#contrato-vehiculo-form").valid()){

                var params={};
                params.action = 'contrato-vehiculo';
                params.operation = 'saveVehiculo';
                params.id_contrato_vehiculo = $('#id_contrato_vehiculo').val();
                params.id_contrato = $('#id_contrato').val();
                params.id_vehiculo = $('#id_vehiculo').val();
                params.fecha_desde = $('#fecha_desde').val();
                params.fecha_hasta = $('#fecha_hasta').val();
                params.id_localidad = $('#id_localidad').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#contrato-vehiculo-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Vehículo guardado con exito').addClass('alert alert-success').show();
                        setTimeout(function() {
                                                $("#myElem").hide();
                                                $('#contrato-vehiculo-form').hide();
                                                //$("#search").trigger("click");
                                                $('#etapas_left_side .grid').load('index.php',{action:"contrato-vehiculo", id_contrato:params.id_contrato, operation:"refreshGrid"});
                                              }, 2000);
                    }

                }).fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el vehículo').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        var dialog;
        $('#etapas_left_side').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar vehículo del contrato?</p>",
                size: 'small',
                buttons: {
                    cancel: {
                        label: "No"
                    },
                    ok: {
                        label: "Si",
                        className: 'btn-danger',
                        callback: function(){
                            $.fn.borrarGv(id);
                            return false; //evita que se cierre automaticamente
                        }
                    }
                }
            });


        });



        $.fn.borrarGv = function(id) {
            //alert(id);
            params={};
            params.id_contrato_vehiculo = id;
            params.id_contrato = $('#etapas_left_side #add').attr('id_contrato');
            params.action = "contrato-vehiculo";
            params.operation = "deleteVehiculo";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Vehículo eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#contrato-vehiculo-form').hide();
                        $('#etapas_left_side .grid').load('index.php',{action:"contrato-vehiculo", id_contrato:params.id_contrato, operation:"refreshGrid"});
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el vehículo</div>');

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

                <!--<input type="hidden" name="id_contrato" id="id_contrato" value="<?php //print $view->grupo->getIdVencimiento() ?>">-->
                
                <div class="row">

                        <div class="col-md-6" id="etapas_left_side">

                            <div class="clearfix">
                                <button <?php echo (PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)) )? '' : 'disabled' ?> class="btn btn-default pull-right dp_green" id="add" name="add" type="submit" title="Agregar vehículo">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/contratos/vehiculosGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-6" id="etapas_right_side">

                        </div>


                </div>


                <!--<div id="myElem" class="msg" style="display:none"></div>-->

            </div>

            <div class="modal-footer">
                <!--<button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>-->
                <button class="btn btn-default" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>





