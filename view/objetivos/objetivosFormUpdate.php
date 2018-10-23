<script type="text/javascript">


    $(document).ready(function(){



        google.charts.load('current', {'packages':['gantt']});
        setTimeout(function() {
                google.charts.setOnLoadCallback(drawChart);
        }, 500);


        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Task ID');
            data.addColumn('string', 'Task Name');
            data.addColumn('string', 'Resource');
            data.addColumn('date', 'Start Date');
            data.addColumn('date', 'End Date');
            data.addColumn('number', 'Duration');
            data.addColumn('number', 'Percent Complete');
            data.addColumn('string', 'Dependencies');

            data.addRows([
                /*['id1', 'Requerimientos', 'spring', new Date('2014-01-05'), new Date('2014-01-20'), null, 100, null],
                 ['id2', 'Analisis de costos', 'pinchila', new Date('2014-01-21'), new Date('2014-03-20'), null, 50, null],
                 ['id3', 'Planificacion', 'autumn', new Date('2014-03-01'), new Date('2014-12-21'), null, 20, null]*/
                ['id1', 'Requerimientos', 'spring', new Date(2014, 0, 5), new Date(2014, 0, 20), null, 100, null],
                ['id2', 'Analisis de costos', 'pinchila', new Date(2014, 0, 21), new Date(2014, 2, 20), null, 50, null],
                ['id3', 'Planificacion', 'autumn', new Date(2014, 2, 1), new Date(2014, 11, 21), null, 20, null]

            ]);

            var options = {
                gantt: {
                    trackHeight: 30, //ancho de la fila
                    criticalPathEnabled: false
                }
            };

            var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

            chart.draw(data, options);
        }















        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        $('#confirm-tarea, #confirm-avance').dialog({
            autoOpen: false
            //modal: true,
        });

        //para mostrar avance de una tarea individual
        $('.grid-tareas').on('click', '.avance', function(){
            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_tarea = id;
            params.id_objetivo = $('#id_objetivo').val();
            params.action = "obj_avances";
            params.operation = "refreshGrid";
            $('#left_side .grid-avances').load('index.php', params, function(){
                //$("button[data-target='#demo-avances']").trigger("click");
                $('#demo-avances').collapse('show'); //https://getbootstrap.com/docs/3.3/javascript/#collapse-options
            });
        });

        //para editar una tarea
        $('.grid-tareas').on('click', '.edit', function(){ //ok
            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_tarea = id;
            params.action = "obj_tareas";
            params.operation = "editTarea";
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
            })
        });

        //para editar un avance
        $('.grid-avances').on('click', '.edit', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_avance = id;
            params.id_objetivo = $('#id_objetivo').val();
            params.action = "obj_avances";
            params.operation = "editAvance";
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            })
        });


        //para ver una tarea
        $('.grid-tareas').on('click', '.view', function(){ //ok
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_tarea = id;
            params.action = "obj_tareas";
            params.operation = "editTarea";
            params.target = "view";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#tarea-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });


        //para ver un avance
        $('.grid-avances').on('click', '.view', function(){ //ok
            //alert('editar orden del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_avance = id;
            params.action = "obj_avances";
            params.operation = "editAvance";
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                $("#right_side fieldset").prop("disabled", true);
                $("#avance-form #footer-buttons button").css('display', 'none');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                $('.selectpicker').selectpicker('refresh');
            })
        });



        //Abre formulario para ingresar una nueva tarea
        $('#left_side').on('click', '#add-tarea', function(){ //ok
            params={};
            params.action = "obj_tareas";
            params.operation = "newTarea";
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_tarea = $('#id_tarea').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
            })
        });


        //Abre formulario para ingresar un avance
        $('#left_side').on('click', '#add-avance', function(){ //ok
            params={};
            params.action = "obj_avances";
            params.operation = "newAvance";
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.id_objetivo = $('#id_objetivo').val();
            //alert(params.id_renovacion);
            $('#right_side').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_postulacion').val(params.id_postulacion);
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
            })
        });


        //eliminar una tarea
        $('.grid-tareas').on('click', '.delete', function(){ //ok
            //alert('Eliminar tarea');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-tarea').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarTarea(id);
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


        $.fn.borrarTarea = function(id) { //ok
            alert(id);
            //preparo los parametros
            params={};
            params.id_tarea = id;
            params.id_objetivo = $('#id_objetivo').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "obj_tareas";
            params.operation = "deleteTarea";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-tarea #myElemento").html('Tarea eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-tareas').load('index.php',{action:"obj_tareas", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-tarea #myElemento").hide();
                                            $('#tarea-form').hide();
                                            $('#confirm-tarea').dialog('close');
                                          }, 2000);
                }else{
                    $("#confirm-tarea #myElemento").html('Error al eliminar la tarea').addClass('alert alert-danger').show();
                }


            });

        };





        //eliminar un avance
        $('.grid-avances').on('click', '.delete', function(){
            //alert('Funcionalidad en desarrollo');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            $('#confirm-avance').dialog({ //se agregan botones al confirm dialog y se abre
                buttons: [
                    {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrarAvance(id);
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


        $.fn.borrarAvance = function(id) {
            //alert(id);
            //preparo los parametros
            params={};
            params.id_avance = id;
            params.id_objetivo = $('#id_objetivo').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "obj_avances";
            params.operation = "deleteAvance";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-avance #myElemento").html('Avance eliminado con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-avances').load('index.php',{action:"obj_avances", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-avance #myElemento").hide();
                                            $('#avance-form').hide();
                                            $('#confirm-avance').dialog('close');
                                          }, 2000);
                }else{
                    $("#confirm-avance #myElemento").html('Error al eliminar el avance').addClass('alert alert-danger').show();
                }


            });

        };


        //evento al salir o cerrar con la x el modal de actualizar el parte
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



                <div id="chart_div"></div>


                
                <div class="row">

                        <div class="col-md-7" id="left_side">

                            <form name ="parte-form" id="parte-form" method="POST" action="index.php">

                                <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">


                                <!-- seccion de tareas -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-tareas" title="Mostrar actividades">Actividades</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="add-tarea" name="add-tarea" title="Agregar tarea">
                                            <i class="fas fa-plus"></i>&nbsp
                                        </button>
                                    </div>
                                </div>

                                <div id="demo-tareas" class="collapse">
                                    <div class="grid-tareas">
                                        <?php include_once('view/objetivos/tareasGrid.php');?>
                                    </div>
                                </div>


                                <br/>


                                <!-- seccion de avances -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-avances" title="Mostrar avances">Avances</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" id="add-avance" name="add-avance" title="Agregar avance">
                                            <i class="fas fa-plus"></i>&nbsp
                                        </button>
                                    </div>
                                </div>

                                <div id="demo-avances" class="collapse">
                                    <div class="grid-avances">
                                        <?php include_once('view/objetivos/avancesGrid.php');?>
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
                <!--<button class="btn btn-primary btn-sm" id="calcular" name="calcular" type="submit">Calcular</button>-->
                <button class="btn btn-default btn-sm" id="salir" name="salir" type="button" data-dismiss="modal">Salir</button>
            </div>

        </div>
    </div>
</div>



<div id="confirm-tarea">
    <div class="modal-body">
        ¿Desea eliminar la tarea?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>




<div id="confirm-avance">
    <div class="modal-body">
        ¿Desea eliminar el avance?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>


