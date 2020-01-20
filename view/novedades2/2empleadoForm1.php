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


        $('.input-group.date').datepicker({ //ok para fecha (nuevo)
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true,
            autoclose: true
        })//.datepicker('setDate', new Date()); //pone por defecto la fecha actual
        //$('.input-group.date').datepicker('setDate', new Date());
            .on('changeDate', function (ev) {
                //alert('cambio la fecha');
                params={};
                params.action = "partes";
                params.operation="newParte";
                params.add_contrato = $("#id_contrato").val();
                params.fecha_parte = $("#add_fecha").val(); //para mostrar en el titulo del modal
                params.id_periodo = $("#id_periodo").val();
                params.contrato = $("#add_contrato option:selected").text(); //para mostrar en el titulo del modal

                /*$('#popupbox').load('index.php', params,function(){
                 $('#myModal').modal();
                 });*/
                //$("#chulito").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Obteniendo informacion de cuadrillas...').addClass('alert alert-info').show();
                $("#chulito").html('<i class="fas fa-spinner fa-spin"></i>&nbsp; Obteniendo informacion de cuadrillas...');
                $('#chulito').load('index.php', params,function(){
                    $("#chulito").removeClass('alert alert-info');
                });


        });


        //restringe el selector de fechas al periodo seleccionado
        var fecha_desde = $('#fecha_desde').val();
        var fecha_hasta = $('#fecha_hasta').val();
        //$('#add_fecha').datepicker('setStartDate', '18/05/2019');
        //$('.input-group.date').datepicker('setStartDate', '21/04/2019');
        $('.input-group.date').datepicker('setStartDate', fecha_desde);
        $('.input-group.date').datepicker('setEndDate', fecha_hasta);
        //$('#add_fecha').datepicker('setDate', new Date()); //pone por defecto la fecha actual



        //al hacer click en un empleado carga el panel central con los datos...
        $('#table_empleados').on('click', 'a', function(){

            //alert('editar empleado del parte');
            var id = $(this).closest('tr').attr('data-id');
            //var id = $(this).attr('data-id');
            //alert('editar etapa: '+id);
            params={};
            params.id_empleado = id;
            params.id_parte = $(this).closest('tr').attr('id_parte');
            params.id_parte_empleado = $(this).closest('tr').attr('id_parte_empleado');
            params.id_contrato = $('#id_contrato').val();
            params.id_periodo = $('#id_periodo').val();
            params.fecha = $('#add_fecha').val();
            params.action = "novedades2";
            params.operation = "editParte";
            //alert(params.id_periodo);
            $('#panel-conceptos').load('index.php', params,function(){
                //alert('cargo el contenido en right side');
                //$('#myModal').modal();
                //$('#id_busqueda').prop('disabled', true).selectpicker('refresh');
                //$('#id_postulante').prop('disabled', true).selectpicker('refresh');
            });

            return false;
        });


        $(document).on('click', '#back',function(){
            //alert('regresar');
            window.location.href = "index.php?action=novedades2";
            return false;
        });







    });

</script>



        <!--<div class="col-md-1"></div>-->


        <div class="col-md-12">


            <br/>
            <a id="back" class="pull-right" href="#" title="seleccionar otro período"><i class="fa fa-arrow-left fa-fw"></i>&nbsp; Períodos</a>
            <h4><?php print $view->periodo->getNombre()." (".$view->periodo->getFechaDesde()." - ".$view->periodo->getFechaHasta().")" ?></h4>

            <hr class="hr-primary"/>





                <div class="row">

                    <div class="col-md-3" id="panel-empleados"> <!-- panel izquierdo -->

                        <input type="hidden" name="id_contrato" id="id_contrato" value="<?php print $view->contrato->getIdContrato() ?>">
                        <input type="hidden" name="id_periodo" id="id_periodo" value="<?php print $view->periodo->getIdPeriodo() ?>">
                        <input type="hidden" name="fecha_desde" id="fecha_desde" value="<?php print $view->periodo->getFechaDesde() ?>">
                        <input type="hidden" name="fecha_hasta" id="fecha_hasta" value="<?php print $view->periodo->getFechaHasta() ?>">


                        <div class="form-group required">
                            <!--<label class="col-md-4 control-label" for="fecha">Fecha nacimiento</label>-->
                                <div class="input-group date">
                                    <input class="form-control" type="text" name="add_fecha" id="add_fecha" value = "<?php //print $view->empleado->getFechaNacimiento() ?>" placeholder="DD/MM/AAAA">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
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





