<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#etapas_left_side').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_capacitacion_empleado = id;
            params.action = "cap_empleados";
            params.operation = "editEmpleado";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        $('#etapas_left_side').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_capacitacion_empleado = id;
            params.action = "cap_empleados";
            params.operation = "editEmpleado";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$("#etapas_right_side fieldset").prop("disabled", true);
                //$("#etapa-form #footer-buttons button").css('display', 'none');
                //$('.selectpicker').selectpicker('refresh');
            });
            return false;
        });



        //Abre formulario para ingresar nueva capacitacion
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "cap_empleados";
            params.operation = "newEmpleado";
            params.id_capacitacion = $('#etapas_left_side #add').attr('id_capacitacion');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_capacitacion').val(params.id_capacitacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        //Guardar etapa luego de ingresar nueva o editar
        $('#myModal').on('click', '#submit',function(){ //ok
            //alert('guardar etapa');

            if ($("#etapa-form").valid()){

                var params={};
                params.action = 'cap_empleados';
                params.operation = 'saveEmpleado';
                params.id_capacitacion_empleado = $('#myModal #id_capacitacion_empleado').val();
                params.id_empleado = $('#myModal #id_empleado').val();
                params.id_capacitacion = $('#myModal #id_capacitacion').val();
                params.id_contrato = $('#myModal #id_contrato').val();
                params.asistio = $('#myModal #asistio').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);

                    if(data >=0){
                        $("#etapa-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Empleado guardado con exito').addClass('alert alert-success').show();
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#etapa-form').hide();
                                                //$('#etapas_left_side .grid').load('index.php',{action:"nc_acciones", id_no_conformidad:params.id_no_conformidad, operation:"refreshGrid"});
                                                $('#table-empleados').DataTable().ajax.reload();
                                              }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar el empleado').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        var dialog;
        $('#etapas_left_side').on('click', '.delete', function(){ //ok

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la acción?</p>",
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



        $.fn.borrar = function(id) { //ok
            //alert(id);
            params={};
            params.id_accion = id;
            params.id_no_conformidad = $('#etapas_left_side #add').attr('id_no_conformidad');
            params.action = "nc_acciones";
            params.operation = "deleteAccion";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Acción eliminada con exito</div>');
                    setTimeout(function() {
                                    dialog.modal('hide');
                                    $('#etapa-form').hide();
                                    //$('#etapas_left_side .grid').load('index.php',{action:"nc_acciones", id_no_conformidad:params.id_no_conformidad, operation:"refreshGrid"});
                                    $('#table-acciones').DataTable().ajax.reload();
                                }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la acción</div>');

            });

        };



        //evento al salir o cerrar con la x el modal de acciones
        $("#myModal").on("hidden.bs.modal", function () { //ok
            //alert('salir de etapas');
            //$("#search").trigger("click");
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
                
                <div class="row">

                        <div class="col-md-6" id="etapas_left_side">

                            <div class="clearfix">
                                <button class="btn btn-default pull-right" id="add" name="add" type="submit" title="Agregar acción">
                                    <span class="glyphicon glyphicon-plus dp_green"></span>
                                </button>
                            </div>

                            <div class="grid">
                                <?php include_once('view/capacitaciones/empleadosGrid.php');?>
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



