<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('.grid-vehiculos').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar vehiculo: '+id);
            params={};
            params.id_grupo_vehiculo = id;
            params.action = "vto_grupo-vehiculo";
            params.operation = "editVehiculo";
            params.id_grupo = $('#etapas_left_side').attr('id_grupo');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
            });
            return false;
        });


        $('.grid-vehiculos').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            params={};
            params.id_grupo_vehiculo = id;
            params.action = "vto_grupo-vehiculo";
            params.operation = "editVehiculo";
            params.id_grupo = $('#etapas_left_side').attr('id_grupo');
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#grupo-vehiculo-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            });
            return false;
        });



        //Abre formulario para ingresar un nuevo vehiculo al grupo
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "vto_grupo-vehiculo";
            params.operation = "newVehiculo";
            params.id_grupo = $('#etapas_left_side').attr('id_grupo');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_grupo').val(params.id_grupo);
            });
            return false;
        });


        //Guardar grupo-vehiculo luego de ingresar nuevo o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar grupo-vehiculo');

            if ($("#grupo-vehiculo-form").valid()){

                var params={};
                params.action = 'vto_grupo-vehiculo';
                params.operation = 'saveVehiculo';
                params.id_grupo_vehiculo = $('#id_grupo_vehiculo').val();
                params.id_grupo = $('#id_grupo').val();
                params.id_vehiculo = $('#id_vehiculo').val();
                params.fecha_desde = $('#fecha_desde').val();
                params.fecha_hasta = $('#fecha_hasta').val();
                params.certificado = $('#certificado').val();
                //alert(params.id_grupo);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#grupo-vehiculo-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Vehículo guardado con exito').addClass('alert alert-success').show();
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#grupo-vehiculo-form').hide();
                                                //$('#etapas_left_side .grid-vehiculos').load('index.php',{action:"vto_grupo-vehiculo", id_grupo:params.id_grupo, operation:"refreshGrid"});
                                                $('#table-vehiculos').DataTable().ajax.reload();
                                              }, 2000);
                    }

                }).fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('No es posible guardar el vehículo').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        var dialog;
        $('#etapas_left_side').on('click', '.delete', function(){

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar el vehículo del grupo?</p>",
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
            params.id_grupo_vehiculo = id;
            params.id_grupo = $('#etapas_left_side #add').attr('id_grupo');
            params.action = "vto_grupo-vehiculo";
            params.operation = "deleteVehiculo";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Vehículo eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#grupo-vehiculo-form').hide();
                        //$('#etapas_left_side .grid-vehiculos').load('index.php',{action:"vto_grupo-vehiculo", id_grupo:params.id_grupo, operation:"refreshGrid"});
                        $('#table-vehiculos').DataTable().ajax.reload();
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
            $('#example').DataTable().ajax.reload(null, false);
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

                <input type="hidden" name="id_vencimiento" id="id_vencimiento" value="<?php print $view->grupo->getIdVencimiento() ?>">
                
                <div class="row">

                        <div class="col-md-7" id="etapas_left_side">

                            <!--<div class="clearfix">
                                <button <?php //echo (PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)) )? '' : 'disabled' ?> class="btn btn-default pull-right" id="add" name="add" type="submit" title="Agregar vehículo">
                                    <i class="fas fa-plus dp_green"></i>
                                </button>
                            </div>-->

                            <div class="grid-vehiculos">
                                <?php include_once('view/grupos_vehiculos/vehiculosGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-5" id="etapas_right_side">

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





