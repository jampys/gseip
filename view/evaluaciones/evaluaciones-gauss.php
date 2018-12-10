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





        $('.selectpicker').selectpicker();


        $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });


        //evento al salir o cerrar con la x el modal de actualizar el parte
        $("#myModal").on("hidden.bs.modal", function () {
            //alert('salir de etapas');
            //$("#search").trigger("click");
        });



    });

</script>





<!-- Modal -->
<div class="modal fade" id="myModalx" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php //echo $view->label ?></h4>
            </div>

            <div class="modal-body">


                <div class="row">

                    <div class="col-md-12">
                        <div class="alert alert-info">


                            <div class="row">


                                <div class="col-md-6">

                                    <p><strong> <?php //print $view->objetivo->getCodigo() ?> </strong>
                                        <?php //print $view->objetivo->getNombre() ?> </p>

                                </div>

                                <div class="col-md-6">

                                    <p><strong>Meta</strong> <?php //print $view->objetivo->getMeta() ?>
                                       <strong>Indicador</strong> <?php //print $view->objetivo->getIndicador() ?>
                                        <strong>Valor</strong> <?php //print $view->objetivo->getMetaValor() ?>
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


