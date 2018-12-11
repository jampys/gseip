<style type="text/css">
    
    text {
        font-family: 'Roboto' !important;
        font-weight: normal !important;
        font-size: 13px !important;
    }

</style>


<script type="text/javascript">


    $(document).ready(function(){


        google.charts.load('current', {'packages':['corechart'], 'language': 'es'});
        setTimeout(function() {
                google.charts.setOnLoadCallback(drawChart);
        }, 500);


        function drawChart() {
            //alert('se ejecuto drawChart');
            var data = new google.visualization.DataTable();
            data.addColumn('number', 'Day');
            data.addColumn('number', 'Guardians of the Galaxy');

                    var hola = <?php echo $view->puntajes; ?>;
                    alert(hola[0]['nombre']);

                    var myval;

                    var count = 0;
                    var suma = 0;
                    var promedio;
                    var desSt;

                    //alert(data1[0]['puntaje']);
                    //alert(parseFloat(data1[0]['puntaje']).toFixed(1));
                    //parseFloat(data1[0]['puntaje']).toFixed(1);
                    //alert(Object.keys(data1).length);
                    /*data.addRows([
                        [1,  37.8],
                        [2,  30.9]
                    ]);*/

                    if(Object.keys(hola).length > 0){


                        //calculo promedio
                        $.each(hola, function(indice, val){
                            if (parseFloat(hola[indice]['puntaje']) === 0) { return; } //excluyo los puntajes 0
                            count++;
                            suma += parseFloat(hola[indice]['puntaje']);
                        });
                        promedio = suma/count;
                        alert(promedio);


                        //calculo desvio standard
                        var sumaCuadrados = 0;
                        $.each(hola, function(indice, val){
                            var value = parseFloat(hola[indice]['puntaje']);
                            if ( value === 0) { return; } //excluyo los puntajes 0
                            //count++;
                            //suma += parseFloat(data1[indice]['puntaje']);
                            var diff = value - promedio;
                            var sqrDiff = diff * diff;
                            sumaCuadrados += sqrDiff;
                        });
                        desSt = Math.sqrt(sumaCuadrados/count);
                        alert(desSt);


                        function NormalDensityZx(x, Mean, StdDev) {
                            var a = x - Mean;
                            return Math.exp(-(a * a) / (2 * StdDev * StdDev)) / (Math.sqrt(2 * Math.PI) * StdDev);
                        }



                        $.each(hola, function(indice, val){
                            myval = parseFloat(hola[indice]['puntaje']);
                            //if (data1[indice]['puntaje'] === 0.00) { return; }

                            data.addRows([
                                [
                                    myval,
                                    NormalDensityZx(myval, promedio,desSt)
                                ]
                            ]);

                        });

                        


                        var options = {
                            title: 'Box Office Earnings in First Two Weeks of Opening',
                            //subtitle: 'in millions of dollars (USD)',
                            width: 800,
                            height: 500
                        };
                        options.hAxis = {};
                        options.hAxis.minorGridlines = {};
                        options.hAxis.minorGridlines.count = 12;




                        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                        chart.draw(data, options);
                        //chart.draw(data, google.charts.Line.convertOptions(options));

                    }else{
                        $('#chart_div').empty();
                    }



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