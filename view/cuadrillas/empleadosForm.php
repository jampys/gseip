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


        $('#empleados_left_side').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
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



        //$(document).on('click', '#example .delete', function(){
        $('#empleados_left_side').on('click', '.delete', function(){
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
            params.id_cuadrilla_empleado = id;
            params.id_cuadrilla = $('#empleados_left_side #add').attr('id_cuadrilla');
            params.action = "cuadrilla-empleado";
            params.operation = "deleteEmpleado";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-etp #myElemento").html('Empleado eliminado con exito').addClass('alert alert-success').show();
                    $('#empleados_left_side .grid').load('index.php',{action:"cuadrilla-empleado", id_cuadrilla: params.id_cuadrilla, operation:"refreshGrid"});
                    //$("#search").trigger("click");
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    setTimeout(function() { $("#confirm-etp #myElemento").hide();
                                            $('#empleado-form').hide();
                                            $('#confirm-etp').dialog('close');
                                          }, 2000);
                }else{
                    $("#myElemento").html('Error al eliminar el empleado').addClass('alert alert-danger').show();
                }


            });

        };



        //evento al salir o cerrar con la x el modal de empleados
        $("#myModal").on("hidden.bs.modal", function () {
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

                        <div class="col-md-6" id="empleados_left_side">

                            <div class="clearfix">
                                <button class="btn btn-primary btn-sm pull-right" id="add" name="add" type="submit" title="Agregar empleado">
                                    <span class="glyphicon glyphicon-plus"></span>
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


