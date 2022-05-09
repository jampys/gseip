<script type="text/javascript">


    $(document).ready(function(){


        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });



        $('.grid-ediciones').on('click', '.edit', function(){ //ok
            //alert('editar postulacion');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_edicion = id;
            params.action = "cap_ediciones";
            params.operation = "editEdicion";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        $('.grid-ediciones').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_edicion = id;
            params.action = "cap_ediciones";
            params.operation = "editEdicion";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#id_empleado').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        //Abre formulario para ingresar una nueva edicion a la capacitacion
        $('#etapas_left_side').on('click', '#add', function(){ //ok
            params={};
            params.action = "cap_ediciones";
            params.operation = "newEdicion";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
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



        //Actualiza encabezado de la tabla de empleados con el nombre de la edicion seleccionada
        $('.grid-ediciones').on('click', '.new', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            $('#etapas_left_side').attr('id_edicion', id);
            $('#table-empleados').DataTable().ajax.reload();
            $('#edicion').html($('#table-ediciones').DataTable().row( $(this).closest('tr') ).data().edicion);
            return false;
        });



        var dialog;
        $('.grid-ediciones').on('click', '.delete', function(){ //ok

            var id = $(this).closest('tr').attr('data-id');
            dialog = bootbox.dialog({
                message: "<p>¿Desea eliminar la edición?</p>",
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
            return false;

        });



        $.fn.borrarGv = function(id) { //ok
            //alert(id);
            params={};
            params.id_edicion = id;
            //params.id_busqueda = $('#etapas_left_side').attr('id_busqueda');
            params.action = "cap_ediciones";
            params.operation = "deleteEdicion";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Edición eliminada con exito</div>');
                    setTimeout(function() {
                            dialog.modal('hide');
                            $('#edicion-form').hide();
                            //$('#etapas_left_side .grid').load('index.php',{action:"postulaciones2", id_busqueda:params.id_busqueda, operation:"refreshGrid"});
                            $('#table-ediciones').DataTable().ajax.reload();
                            $('#table-empleados').DataTable().ajax.reload();

                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar la edición</div>');

            });

        };


        //evento al salir o cerrar con la x el modal ediciones
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de ediciones');
            $('#example').DataTable().ajax.reload(null, false);
        });




        ///----------------------------- empleados--------------------------------------///

        $('.grid-empleados').on('click', '.edit', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_capacitacion_empleado = id;
            params.action = "cap_empleados";
            params.operation = "editEmpleado";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                $('#id_empleado').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        $('.grid-empleados').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_capacitacion_empleado = id;
            params.action = "cap_empleados";
            params.operation = "editEmpleado";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            params.target = "view";
            //alert(params.id_renovacion);
            $('#etapas_right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $('#id_empleado').prop('disabled', true).selectpicker('refresh');
            });
            return false;
        });


        //Abre formulario para ingresar un nuevo empleado
        $('.grid-empleados').on('click', '#add', function(){ //ok
            params={};
            params.action = "cap_empleados";
            params.operation = "newEmpleado";
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            params.startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD'); //drp.startDate.format('YYYY-MM-DD');
            params.endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD'); //drp.endDate.format('YYYY-MM-DD');
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


        var dialog;
        $('#etapas_left_side').on('click', '.delete', function(){ //ok

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
            return false;

        });



        $.fn.borrar = function(id) { //ok
            //alert(id);
            params={};
            params.id_capacitacion_empleado = id;
            params.id_capacitacion = $('#etapas_left_side').attr('id_capacitacion');
            params.action = "cap_empleados";
            params.operation = "deleteEmpleado";

            $.post('index.php',params,function(data, status, xhr){
                if(data >=0){
                    dialog.find('.modal-footer').html('<div class="alert alert-success">Empleado eliminado con exito</div>');
                    setTimeout(function() {
                        dialog.modal('hide');
                        $('#empleado-form').hide();
                        //$('#etapas_left_side .grid').load('index.php',{action:"nc_acciones", id_no_conformidad:params.id_no_conformidad, operation:"refreshGrid"});
                        $('#table-empleados').DataTable().ajax.reload();
                    }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                dialog.find('.modal-footer').html('<div class="alert alert-danger">No es posible eliminar el empleado</div>');

            });

        };







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

                <!--<input type="hidden" name="id_busquedax" id="id_busquedax" value="<?php //print $view->grupo->getIdVencimiento() ?>">
                <input type="hidden" name="id_contrato" id="id_contrato" value="<?php //print $view->grupo->getIdVencimiento() ?>">-->
                
                <div class="row">

                        <div class="col-md-7" id="etapas_left_side">

                            <!-- seccion de postulantes-->
                            <div class="row">
                                <div class="col-md-12">

                                    <!--<div class="clearfix">
                                        <button <?php //echo (PrivilegedUser::dhasPrivilege('PTN_ABM', array(1)) )? '' : 'disabled' ?> class="btn btn-default pull-right dp_green" id="add" name="add" type="submit" title="Agregar postulante">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </div>-->

                                    <div class="grid-ediciones">
                                        <?php include_once('view/capacitaciones/edicionesGrid.php');?>
                                    </div>

                                </div>
                            </div>


                            <br/>
                            <h4><span style="display: block; text-align: left; font-weight: normal" class="label label-primary">Participantes de la edición: <span id="edicion"></span></span></h4>


                            <!-- seccion de empleados de la edicion-->
                            <div class="row">
                                <div class="col-md-12">

                                    <!-- incluir datatable de etapas de la postulacion-->
                                    <div class="grid-empleados">
                                        <?php include_once('view/capacitaciones/empleadosGrid.php');?>
                                    </div>

                                </div>
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




