<style type="text/css">
    
    text {
        font-family: 'Roboto' !important;
        font-weight: normal !important;
        font-size: 13px !important;
    }

</style>


<script type="text/javascript">


    $(document).ready(function(){

        var v_id_tarea;



        google.charts.load('current', {'packages':['gantt'], 'language': 'es'});
        setTimeout(function() {
                google.charts.setOnLoadCallback(drawChart);
        }, 500);


        function drawChart() {
            //alert('se ejecuto drawChart');
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Task ID');
            data.addColumn('string', 'Task Name');
            //data.addColumn('string', 'Resource');
            data.addColumn('date', 'Start Date');
            data.addColumn('date', 'End Date');
            data.addColumn('number', 'Duration');
            data.addColumn('number', 'Percent Complete');
            data.addColumn('string', 'Dependencies');

            /*data.addRows([

                ['id1', 'Requerimientos', 'spring', new Date(2014, 0, 5), new Date(2014, 0, 20), null, 100, null],
                ['id2', 'Analisis de costos', 'pinchila', new Date(2014, 0, 21), new Date(2014, 2, 20), null, 50, null],
                ['id3', 'Planificacion', 'autumn', new Date(2014, 2, 1), new Date(2014, 11, 21), null, 20, null]

            ]);*/


            $.ajax({
                url:"index.php",
                type:"post",
                data:{"action": "obj_objetivos", "operation": "graficarGantt", "id_objetivo": <?php print $view->objetivo->getIdObjetivo() ?>},
                dataType:"json",//xml,html,script,json
                success: function(data1, textStatus, jqXHR) {

                    if(Object.keys(data1).length > 0){
                        
                        $.each(data1, function(indice, val){
                            //alert(data1[indice]['Task_Name']);
                            data.addRows([
                                [
                                    data1[indice]['Task_ID'],
                                    data1[indice]['Task_Name'],
                                    //data1[indice]['Task_Name'],
                                    new Date(data1[indice]['Start_Date']),
                                    new Date(data1[indice]['End_Date']),
                                    null,
                                    (data1[indice]['Percent_Complete'])? parseInt(data1[indice]['Percent_Complete']) : 0,
                                    null
                                ]

                            ]);
                        });


                        var options = {
                            gantt: {
                                trackHeight: 25, //ancho de la fila
                                barHeight: 19, //ancho de la barra
                                criticalPathEnabled: false,
                                /*,innerGridHorizLine: {
                                    stroke: '#ffe0b2',
                                    strokeWidth: 1
                                },
                                innerGridTrack: {fill: '#fff3e0'},
                                innerGridDarkTrack: {fill: '#ffcc80'}*/
                                palette: [ //con esto configuro los colores de las barras del gantt https://stackoverflow.com/questions/35165271/customize-the-bar-colors-in-google-gantt-charts
                                    {
                                        "color": "#f2a600",
                                        "dark": "#ee8100",
                                        "light": "#fce8b2"
                                    }
                                ]
                            },
                            height: Object.keys(data1).length*25+50 // cantidad_elementos * trackHeight + margen
                        };

                        var chart = new google.visualization.Gantt(document.getElementById('chart_div'));
                        chart.draw(data, options);

                    }else{
                        $('#chart_div').empty();
                    }

                },
                error: function(data, textStatus, errorThrown) {
                    //console.log('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                    alert(data.responseText);
                }

            });

        }




        //Guardar tarea luego de ingresar nueva o editar. Traido de tarea_detailForm.php
        $('#myModal').on('click', '#tarea-form #submit',function(){ //ok
            //alert('guardar actividad');

            if ($("#tarea-form").valid()){

                var params={};
                params.action = 'obj_tareas';
                params.operation = 'saveTarea';
                params.id_objetivo = $('#id_objetivo').val();
                params.id_tarea = $('#id_tarea').val();
                params.nombre = $('#nombre').val();
                params.descripcion = $('#descripcion').val();
                params.fecha_inicio = $('#fecha_inicio').val();
                params.fecha_fin = $('#fecha_fin').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    if(data >=0){
                        $("#tarea-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#tarea-form #myElem").html('Tarea guardada con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-tareas').load('index.php',{action:"obj_tareas", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#tarea-form #myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#tarea-form').hide();
                                                drawChart();
                                                }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#tarea-form #myElem").html('Error al guardar la tarea').addClass('alert alert-danger').show();
                });

            }
            return false;
        });



        //Guardar avance luego de ingresar nuevo o editar. Traido de avance_detailForm.php
        $('#myModal').on('click', '#avance-form #submit',function(){ //ok

            if ($("#avance-form").valid()){

                var params={};
                params.action = 'obj_avances';
                params.operation = 'saveAvance';
                params.id_avance = $('#id_avance').val();
                params.id_objetivo = $('#id_objetivo').val();
                params.fecha = $('#fecha').val();
                params.id_tarea = $('#id_tarea').val();
                params.indicador = $('#indicador').val();
                params.cantidad = $('#cantidad').val();
                params.comentarios = $('#comentarios').val();
                //params.conductor = $('input[name=conductor]:checked').val();
                //params.conductor = $('#conductor').prop('checked')? 1:0;
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;
                //alert(params.aplica);

                $.post('index.php',params,function(data, status, xhr){
                    //alert(xhr.responseText);
                    if(data >=0){
                        $("#avance-form #footer-buttons button").prop("disabled", true); //deshabilito botones
                        $("#avance-form #myElem").html('Avance guardado con exito').addClass('alert alert-success').show();
                        $('#left_side .grid-avances').load('index.php',{action:"obj_avances", id_objetivo: params.id_objetivo, id_tarea: params.id_tarea, operation:"refreshGrid"});
                        //$("#search").trigger("click");
                        setTimeout(function() { $("#avance-form #myElem").hide();
                                                //$('#myModal').modal('hide');
                                                $('#avance-form').hide();
                                                drawChart();
                        }, 2000);
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#avance-form #myElem").html('Error al guardar el avance').addClass('alert alert-danger').show();
                });

            }
            return false;
        });















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
            v_id_tarea = id; //guardo el id_tarea para refrescar la grilla de avances
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

                ],
                close: function() { $("#confirm-tarea #myElem").empty().removeClass(); }
            }).dialog('open');
            return false;
        });


        $.fn.borrarTarea = function(id) { //ok
            //alert(id);
            //preparo los parametros
            params={};
            params.id_tarea = id;
            params.id_objetivo = $('#id_objetivo').val();
            //params.id_postulacion = $('#empleados_left_side #add').attr('id_postulacion');
            params.action = "obj_tareas";
            params.operation = "deleteTarea";
            //alert(params.id_etapa);

            $.post('index.php',params,function(data, status, xhr){
                //alert(xhr.responseText);
                if(data >=0){
                    $("#confirm-tarea #myElem").html('Actividad eliminada con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-tareas').load('index.php',{action:"obj_tareas", id_objetivo: params.id_objetivo, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-tarea #myElem").hide();
                                            $('#tarea-form').hide();
                                            $('#confirm-tarea').dialog('close');
                                            drawChart();
                                          }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                $("#confirm-tarea #myElem").html('No es posible eliminar la actividad').addClass('alert alert-danger').show();
            });

        };





        //eliminar un avance
        $('.grid-avances').on('click', '.delete', function(){
            //alert(v_id_tarea);
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

                ],
                close: function() { $("#confirm-avance #myElem").empty().removeClass(); }
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
                    $("#confirm-avance #myElem").html('Avance eliminado con exito').addClass('alert alert-success').show();
                    $('#left_side .grid-avances').load('index.php',{action:"obj_avances", id_objetivo: params.id_objetivo, id_tarea: v_id_tarea, operation:"refreshGrid"});
                    $('.ui-dialog .btn').attr("disabled", true); //deshabilito botones
                    //$("#search").trigger("click");
                    setTimeout(function() { $("#confirm-avance #myElem").hide();
                                            $('#avance-form').hide();
                                            $('#confirm-avance').dialog('close');
                                            drawChart();
                                          }, 2000);
                }

            }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                //alert('Entro a fail '+jqXHR.responseText);
                $("#confirm-avance #myElem").html('No es posible eliminar el avance').addClass('alert alert-danger').show();
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


                <div class="row">

                    <div class="col-md-12">
                        <div class="alert alert-info">


                            <div class="row">


                                <div class="col-md-6">

                                    <p><strong> <?php print $view->objetivo->getCodigo() ?> </strong>
                                        <?php print $view->objetivo->getNombre() ?> </p>

                                    <div class="progress" style="margin-bottom: 0px">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($view->objetivo->getProgreso() <= 100)? $view->objetivo->getProgreso():100; ?>%; min-width: 2em">
                                            <?php print $view->objetivo->getProgreso(); ?>%
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <p><strong>Meta</strong> <?php print $view->objetivo->getMeta() ?>
                                       <strong>Indicador</strong> <?php print $view->objetivo->getIndicador() ?>
                                        <strong>Valor</strong> <?php print $view->objetivo->getMetaValor() ?>
                                    </p>

                                </div>



                            </div>



                        </div>
                    </div>
                </div>

                <br/>



                <div id="chart_div"></div>


                
                <div class="row">

                        <div class="col-md-7" id="left_side">

                            <form name ="parte-form" id="parte-form" method="POST" action="index.php">

                                <input type="hidden" name="id_objetivo" id="id_objetivo" value="<?php print $view->objetivo->getIdObjetivo() ?>">


                                <!-- seccion de actividades -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="collapse" data-target="#demo-tareas" title="Mostrar actividades">Actividades</button>
                                    </div>

                                    <div class="col-md-4">

                                    </div>

                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-default btn-sm btn-block" id="add-tarea" name="add-tarea" title="Agregar actividad" <?php echo (!$view->params['cerrado'] && PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? '' : 'disabled' ?> >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
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
                                        <button type="button" class="btn btn-default btn-sm btn-block" id="add-avance" name="add-avance" title="Agregar avance" <?php echo (!$view->params['cerrado'] && PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) )? '' : 'disabled' ?>  >
                                            <i class="fas fa-plus dp_green"></i>&nbsp
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
        ¿Desea eliminar la actividad?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>




<div id="confirm-avance">
    <div class="modal-body">
        ¿Desea eliminar el avance?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>


