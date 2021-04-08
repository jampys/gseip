<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('#empleados_left_side').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
            params.id_cuadrilla_empleado = id;
            params.action = "cuadrilla-empleado";
            params.operation = "editEmpleado";
            //alert(params.id_renovacion);
            $('#empleados_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });

        $('#empleados_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
            params.id_cuadrilla_empleado = id;
            params.action = "cuadrilla-empleado";
            params.operation = "editEmpleado";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#empleados_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#empleados_right_side fieldset").prop("disabled", true);
                $("#empleado-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar un nuevo empleado
        $('#empleados_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "cuadrilla-empleado";
            params.operation = "newEmpleado";
            params.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
            //alert(params.id_renovacion);
            $('#empleados_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_cuadrilla').val(params.id_cuadrilla);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Guardar etapa luego de ingresar nueva o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar empleado');

            if ($("#empleado-form").valid()){

                var params={};
                params.action = 'cuadrilla-empleado';
                params.operation = 'saveEmpleado';
                params.id_cuadrilla_empleado = $('#id_cuadrilla_empleado').val();
                params.id_cuadrilla = $('#id_cuadrilla').val();
                params.id_empleado = $('#id_empleado').val();
                params.conductor = $('#conductor').prop('checked')? 1:0;
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){

                    //objeto.id = data; //data trae el id de la renovacion
                    //alert(objeto.id);
                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $("#empleado-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        $('#empleados_left_side .grid').load('index.php',{action:"cuadrilla-empleado", id_cuadrilla:params.id_cuadrilla, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#empleado-form').hide();
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                    }

                }, 'json');

            }
            return false;
        });



        //eliminar empleado de la cuadrilla
        var dialog;
        $('#empleados_left_side').on('click', '.delete', function(){

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
            params.id_cuadrilla_empleado = id;
            params.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
            params.action = "cuadrilla-empleado";
            params.operation = "deleteEmpleado";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Empleado eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#empleados_left_side .grid').load('index.php',{action:"cuadrilla-empleado", id_cuadrilla: params.id_cuadrilla, operation:"refreshGrid"});
                        $('#empleado-form').hide();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el empleado</div>');

            });

        };



        //evento al salir o cerrar con la x el modal de empleados
        $("#myModal").on("hidden.bs.modal", function () {
            //$("#search").trigger("click");
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

                        <div class="col-md-6" id="empleados_left_side">

                            <div class="clearfix">
                                <button class="btn btn-default pull-right" id="add" name="add" type="submit" title="Agregar empleado" <?php echo ( PrivilegedUser::dhasPrivilege('CUA_ABM', array(1)) && $view->target!='view' )? '' : 'disabled' ?>    >
                                    <span class="glyphicon glyphicon-plus dp_green"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/cuadrillas/empleadosGrid.php');?>
                            </div>

                        </div>

                        <div class="col-md-6" id="empleados_right_side">

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





