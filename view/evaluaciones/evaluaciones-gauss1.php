<style type="text/css">
    
    text {
        font-family: 'Roboto' !important;
        font-weight: normal !important;
        font-size: 13px !important;
    }

</style>


<script type="text/javascript">


    $(document).ready(function(){

        //http://jsfiddle.net/gh/get/library/pure/highcharts/highcharts/tree/master/samples/highcharts/plotoptions/bellcurve-intervals-pointsininterval


        var temp = <?php echo $view->puntajes; ?>;
        //alert(Object.keys(temp).length);

        var data = $.map(temp, function(elem, index) {
            //if (parseFloat(temp[index]['puntaje']) === 0) { return null; } //excluyo los puntajes 0
            //else return parseFloat(temp[index]['puntaje']);
            return parseFloat(temp[index]['puntaje']);
        });


        /*var data = [3.5, 3, 3.2, 3.1, 3.6, 3.9, 3.4, 3.4, 2.9, 3.1, 3.7, 3.4, 3, 3, 4,
            4.4, 3.9, 3.5, 3.8, 3.8, 3.4, 3.7, 3.6, 3.3, 3.4, 3, 3.4, 3.5, 3.4, 3.2,
            3.1, 3.4, 4.1, 4.2, 3.1, 3.2, 3.5, 3.6, 3, 3.4, 3.5, 2.3, 3.2, 3.5, 3.8, 3,
            3.8, 3.2, 3.7, 3.3, 3.2, 3.2, 3.1, 2.3, 2.8, 2.8, 3.3, 2.4, 2.9, 2.7, 2, 3,
            2.2, 2.9, 2.9, 3.1, 3, 2.7, 2.2, 2.5, 3.2, 2.8, 2.5, 2.8, 2.9, 3, 2.8, 3,
            2.9, 2.6, 2.4, 2.4, 2.7, 2.7, 3, 3.4, 3.1, 2.3, 3, 2.5, 2.6, 3, 2.6, 2.3,
            2.7, 3, 2.9, 2.9, 2.5, 2.8, 3.3, 2.7, 3, 2.9, 3, 3, 2.5, 2.9, 2.5, 3.6,
            3.2, 2.7, 3, 2.5, 2.8, 3.2, 3, 3.8, 2.6, 2.2, 3.2, 2.8, 2.8, 2.7, 3.3, 3.2,
            2.8, 3, 2.8, 3, 2.8, 3.8, 2.8, 2.8, 2.6, 3, 3.4, 3.1, 3, 3.1, 3.1, 3.1, 2.7,
            3.2, 3.3, 3, 2.5, 3, 3.4, 3
        ];*/

        var pointsInInterval = 5;

        Highcharts.chart('chart_div', {
            chart: {
                margin: [50, 0, 50, 50],
                events: {
                    load: function () {
                        Highcharts.each(this.series[0].data, function (point, i) {
                            var labels = ['4σ', '3σ', '2σ', 'σ', 'μ', 'σ', '2σ', '3σ', '4σ'];
                            if (i % pointsInInterval === 0) {
                                point.update({
                                    color: 'black',
                                    dataLabels: {
                                        enabled: true,
                                        format: labels[Math.floor(i / pointsInInterval)],
                                        overflow: 'none',
                                        crop: false,
                                        y: -2,
                                        style: {
                                            fontSize: '13px'
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            },

            title: {
                text: null
            },

            legend: {
                enabled: false
            },

            xAxis: [{
                title: {
                    text: 'Data'
                },
                visible: false
            }, {
                title: {
                    text: 'Bell curve'
                },
                opposite: true,
                visible: false
            }],

            yAxis: [{
                title: {
                    text: 'Data'
                },
                visible: false
            }, {
                title: {
                    text: 'Bell curve'
                },
                opposite: true,
                visible: false
            }],

            series: [{
                name: 'Bell curve asd',
                type: 'bellcurve',
                xAxis: 1,
                yAxis: 1,
                pointsInInterval: pointsInInterval,
                intervals: 4,
                baseSeries: 1,
                zIndex: -1,
                marker: {
                    enabled: true
                }
            }, {
                name: 'Data',
                type: 'scatter',
                data: data,
                visible: true, //muestra los datos individuales
                marker: {
                    radius: 1.5
                }
            }]
        });









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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $view->label ?></h4>
            </div>

            <div class="modal-body">


                <div class="row">



                    <div class="col-md-4">

                        <p><strong>Período:</strong> <?php print $view->periodo; ?></p>
                        <p><strong>Contrato:</strong>   <?php print $view->contrato; ?> </p>

                    </div>


                    <div class="col-md-4">

                        <p><strong>Meta</strong> <?php //print $view->objetivo->getMeta() ?>
                            <strong>Indicador</strong> <?php //print $view->objetivo->getIndicador() ?>
                            <strong>Valor</strong> <?php //print $view->objetivo->getMetaValor() ?>
                        </p>

                    </div>


                    <div class="col-md-4">

                        <p><strong>Período:</strong> <?php echo $view->periodo; ?></p>
                        <p><strong>Contrato:</strong>   <?php //print $view->objetivo->getNombre() ?> </p>

                    </div>






                </div>

                <br/>






                
                <div class="row">

                        <div class="col-md-7" id="left_side">

                            <div id="chart_div" style="height: 300px"></div>

                        </div>



                        <div class="col-md-5" id="right_side">


                            <div class="table-responsive fixedTable" style="max-height: 300px">

                                <table class="table table-condensed dataTable table-hover">
                                    <thead>
                                    <tr>
                                        <th>Empleado</th>
                                        <th>Puntaje</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($view->rta as $em): ?>
                                        <tr data-id="<?php echo $em['id_empleado'];?>">
                                            <td><?php echo $em['apellido'].' '.$em['nombre']; ?></td>
                                            <td><?php echo $em['puntaje']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>

                            </div>












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