<style type="text/css">
    
    text {
        font-family: 'Roboto' !important;
        font-weight: normal !important;
        font-size: 13px !important;
    }

</style>


<script type="text/javascript">


    $(document).ready(function(){


        google.charts.load('current', {'packages':['line'], 'language': 'es'});
        setTimeout(function() {
                google.charts.setOnLoadCallback(drawChart);
        }, 500);


        function drawChart() {
            //alert('se ejecuto drawChart');
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'Day');
            data.addColumn('number', 'Guardians of the Galaxy');
            data.addColumn('number', 'The Avengers');
            data.addColumn('number', 'Transformers: Age of Extinction');


            $.ajax({
                url:"index.php",
                type:"post",
                data:{"action": "evaluaciones", "operation": "graficarGauss"},
                dataType:"json",//xml,html,script,json
                success: function(data1, textStatus, jqXHR) {

                    alert(data1[0]['puntaje']);
                    alert(Object.keys(data1).length);
                    /*data.addRows([
                        [1,  37.8, 80.8, 41.8],
                        [2,  30.9, 69.5, 32.4],
                        [3,  25.4,   57, 25.7],
                        [4,  11.7, 18.8, 10.5],
                        [5,  11.9, 17.6, 10.4],
                        [6,   8.8, 13.6,  7.7],
                        [7,   7.6, 12.3,  9.6],
                        [8,  12.3, 29.2, 10.6],
                        [9,  16.9, 42.9, 14.8],
                        [10, 12.8, 30.9, 11.6],
                        [11,  5.3,  7.9,  4.7],
                        [12,  6.6,  8.4,  5.2],
                        [13,  4.8,  6.3,  3.6],
                        [14,  4.2,  6.2,  3.4]
                    ]);*/

                    if(Object.keys(data1).length > 0){

                        $.each(data1, function(indice, val){
                            //alert(data1[indice]['Task_Name']);
                            if (data1[indice]['puntaje'] === 0.00) { return; }

                            data.addRows([
                                [
                                    //1,  37, 80, 41
                                    data1[indice]['puntaje'],
                                    5,
                                    3,
                                    7
                                    //data1[indice]['Task_Name'],
                                    //data1[indice]['Task_Name'],
                                    //new Date(data1[indice]['Start_Date']),
                                    //new Date(data1[indice]['End_Date']),
                                    //null,
                                    //(data1[indice]['Percent_Complete'])? parseInt(data1[indice]['Percent_Complete']) : 0,
                                    //null
                                ]

                            ]);
                        });


                        var options = {
                            chart: {
                                title: 'Box Office Earnings in First Two Weeks of Opening',
                                subtitle: 'in millions of dollars (USD)'
                            }
                            //width: 900,
                            //height: 500
                        };




                        var chart = new google.charts.Line(document.getElementById('chart_div'));
                        //chart.draw(data, options);
                        chart.draw(data, google.charts.Line.convertOptions(options));

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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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