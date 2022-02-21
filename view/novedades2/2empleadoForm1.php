<style>


    ul{
        list-style-type: none;
    }


    .fixedTable{
        max-height: 80%;       /* sobrescribe .fixedTable de dario.css         */
    }

</style>



<script type="text/javascript">


    $(document).ready(function(){


        /*$('.input-group.date.af').datepicker({
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true,
            autoclose: true
        }).on('changeDate', function (ev) {
                params={};
                params.action = "partes";
                params.operation="newParte";
                params.add_contrato = $("#id_contrato").val();
                params.fecha_parte = $("#add_fecha").val(); //para mostrar en el titulo del modal
                params.id_periodo = $("#id_periodo").val();
                params.contrato = $("#add_contrato option:selected").text(); //para mostrar en el titulo del modal

                $("#chulito").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Obteniendo informacion de cuadrillas...');
                $('#chulito').load('index.php', params,function(){
                    $("#chulito").removeClass('alert alert-info');
                });

        });

        //restringe el selector de fechas al periodo seleccionado
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        $('.input-group.date.af').datepicker('setStartDate', fecha_desde);
        $('.input-group.date.af').datepicker('setEndDate', fecha_hasta);
*/

        moment.locale('es');
        $('#add_fecha').daterangepicker({
            singleDatePicker: true,
            autoApply: true,
            autoUpdateInput: false,
            drops: 'auto',
            minDate: $('#fecha_desde').val(),
            maxDate: $('#fecha_hasta').val(),
            startDate: $('#fecha_desde').val(),
            "locale": {
                "format": "DD/MM/YYYY"
            }
        }).on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            //picker.element.valid();

            params={};
            params.action = "partes";
            params.operation="newParte";
            params.add_contrato = $("#id_contrato").val();
            params.fecha_parte = $("#add_fecha").val(); //para mostrar en el titulo del modal
            params.id_periodo = $("#id_periodo").val();
            params.contrato = $("#add_contrato option:selected").text(); //para mostrar en el titulo del modal

            $("#chulito").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Obteniendo informacion de cuadrillas...');
            $('#chulito').load('index.php', params,function(){
                $("#chulito").removeClass('alert alert-info');
            });


        });




        $(document).on('click', '#back',function(){
            //alert('regresar');
            let id_contrato = $('#id_contrato').val();
            window.location.href = 'index.php?action=novedades2&id_contrato='+id_contrato;
            return false;
        });


        $(document).on('click', '#cancel',function(){
            $('#chulito').empty();
        });







    });

</script>



        <!--<div class="col-md-1"></div>-->


        <div class="col-md-12">


            <br/>
            <a id="back" class="pull-right" href="#" title="Regresar a selección de períodos"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Volver</a>
            <h4><?php print $view->label ?></h4>

            <hr class="hr-primary"/>





                <div class="row">

                    <div class="col-md-3" id="panel-empleados"> <!-- panel izquierdo -->

                        <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">
                        <input type="hidden" name="id_periodo" id="id_periodo" value="<?php print $view->periodo->getIdPeriodo() ?>">
                        <input type="hidden" name="fecha_desde" id="fecha_desde" value="<?php print $view->periodo->getFechaDesde() ?>">
                        <input type="hidden" name="fecha_hasta" id="fecha_hasta" value="<?php print $view->periodo->getFechaHasta() ?>">


                        <div class="form-group">
                            <div class="inner-addon right-addon">
                                <input class="form-control" type="text" name="add_fecha" id="add_fecha" value = "<?php //print $view->empleado->getFechaNacimiento() ?>" placeholder="DD/MM/AAAA" readonly>
                                <i class="glyphicon glyphicon-calendar"></i>
                            </div>

                        </div>


                    </div>



                    <div class="col-md-9" id="panel-conceptos">
                        <!--se carga con un load -->
                    </div>




                </div>









            <div class="row">

                <div class="col-md-12" id="chulito">
                </div>


            </div>








        </div>


        <!--<div class="col-md-1"></div>-->




    <div id="myElem" class="msg" style="display:none">

    </div>





