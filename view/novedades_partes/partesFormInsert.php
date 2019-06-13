<script type="text/javascript">


    $(document).ready(function(){

        $('.selectpicker').selectpicker({ //ok
            //propiedades del selectpicker

        }).change(function(){
            $(this).valid(); //Este trick de change ... valida hay que hacerlo para que despues de seleccionar un valor
                             // elimine el mensaje de requerido de jquery validation
        });


        /*$('.input-daterange').datepicker({ //ok
            //todayBtn: "linked",
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });*/

        $('.input-group.date').datepicker({
            //inline: true
            format:"dd/mm/yyyy",
            language: 'es',
            todayHighlight: true
        });



        $('#myModal').on('click', '#submit',function(){ //ok

            //if ($("#busqueda-form").valid()){

                var jsonCuadrillas = [];

                //alert('presiono en submit');

                $('.cu_cuadrilla').each(function(){ //recorre c/u de las cuadrillas
                    //alert('encontro cuadrilla');

                    if($(this).find('.cu_selected').prop('checked')){ //si la cuadrilla esta seleccionada para guardarse
                        item = {};
                        item.id_cuadrilla = $(this).attr('id_cuadrilla');
                        item.id_contrato = $(this).attr('id_contrato');
                        item.cuadrilla = $(this).find('.btn-primary').text();
                        //item.id_empleado_1 = $(this).find('.cu_id_empleado_1 option:selected').val();
                        item.id_empleado_1 = $(this).find('.cu_id_empleado_1 select').val();
                        item.id_empleado_2 = $(this).find('.cu_id_empleado_2 select').val();
                        item.id_area = $(this).find('.cu_id_area option:selected').val();
                        item.id_vehiculo = $(this).find('.cu_id_vehiculo option:selected').val();
                        item.id_evento = $(this).find('.cu_id_evento option:selected').val();

                        jsonCuadrillas.push(item);
                        //alert(item.id_empleado_2);
                        //throw new Error();
                    }

                });

                //alert(jsonCuadrillas[0].nombre);
                //throw new Error();


                var params={};
                params.action = 'partes';
                params.operation = 'insertPartes';
                //params.fecha_parte = $('#add_fecha').val();
                params.fecha_parte = $('#myModal #fecha_parte').val();
                params.id_periodo = $('#myModal #id_periodo').val();
                params.vCuadrillas = JSON.stringify(jsonCuadrillas);
                //params.id_empleado = $('#id_empleado option:selected').attr('id_empleado');
                //params.disabled = $('#disabled').prop('checked')? 1:0;


                $.post('index.php',params,function(data, status, xhr){

                    //alert(xhr.responseText);

                    if(data >=0){
                        //uploadObj.startUpload(); //se realiza el upload solo si el formulario se guardo exitosamente
                        $(".modal-footer button").prop("disabled", true); //deshabilito botones
                        $("#myElem").html('Partes guardados con exito').addClass('alert alert-success').show();
                        //$('#content').load('index.php',{action:"renovacionesPersonal", operation:"refreshGrid"});
                        $("#search").trigger("click");
                        setTimeout(function() { $("#myElem").hide();
                                                $('#myModal').modal('hide');
                                              }, 2000);
                    }else{
                        $("#myElem").html('Error al guardar los partes').addClass('alert alert-danger').show();
                    }

                }, 'json').fail(function(jqXHR, textStatus, errorThrown ) {
                    //alert('Entro a fail '+jqXHR.responseText);
                    $("#myElem").html('Error al guardar los partes').addClass('alert alert-danger').show();
                });

            //}
            return false;
        });



        $('#myModal').on('click', '#cancel, #back',function(){ //ok
            //$('#popupbox').dialog('close');
            //$('#content').load('index.php',{action:"partes", operation:"refreshGrid"});
            $("#search").trigger("click");
        });



        /*$('#myModal').modal({
            backdrop: 'static',
            keyboard: false
        });*/


        /*$('#busqueda-form').validate({
            rules: {
                nombre: {required: true},
                fecha_apertura: {required: true}
            },
            messages:{
                nombre: "Ingrese el nombre",
                fecha_apertura: "Seleccione la fecha de apertura"
            }

        });*/


        //Al presionar el boton con el nombre de la cuadrilla
        //$('.cu_cuadrilla .btn-primary').on('click', function(){ //ok
        $('.cu_cuadrilla .btn-block').on('click', function(){ //ok
            //$(this).closest('.row.cu_cuadrilla').find(':checkbox').prop('checked', true);
            var chk = $(this).closest('.row.cu_cuadrilla').find(':checkbox');
            chk.prop('checked', !chk.is(':checked'))

        });

        $('.cu_cuadrilla .btn-intercambio').on('click', function(){ //ok
            //alert('intercambiar');
            var c1 = $(this).closest('.row.cu_cuadrilla').find('.cu_id_empleado_1 select').val();
            var c2 = $(this).closest('.row.cu_cuadrilla').find('.cu_id_empleado_2 select').val();
            //alert(temp);
            $(this).closest('.row.cu_cuadrilla').find('.cu_id_empleado_1').val(c2).selectpicker('refresh');
            $(this).closest('.row.cu_cuadrilla').find('.cu_id_empleado_2').val(c1).selectpicker('refresh');
            //$('.selectpicker').selectpicker('refresh');

        });


    });

</script>


<div class="col-md-12">

<div class="panel panel-default" id="myModal">

            <div class="panel-heading">
                <h4 class="pull-left"><span><?php echo $view->label ?></span></h4>
                <a id="back" class="pull-right" href="#"><i class="fas fa-arrow-left fa-fw"></i>&nbsp;Volver </a>
                <div class="clearfix"></div>
            </div>


            <div class="panel-body">

                <input type="hidden" name="fecha_parte" id="fecha_parte" value="<?php print $view->params['fecha_parte'] ?>">
                <input type="hidden" name="id_periodo" id="id_periodo" value="<?php print $view->params['id_periodo'] ?>">

                <?php if(isset($view->cuadrillas) && sizeof($view->cuadrillas) > 0) {?>

                    <?php foreach ($view->cuadrillas as $cu): ?>

                        <div class="row cu_cuadrilla" id_cuadrilla="<?php echo $cu['id_cuadrilla'] ?>" id_contrato="<?php echo $cu['id_contrato'] ?>">

                            <div class="col-md-2" style="padding-left: 5px; padding-right: 5px">
                                <button type="button" class="btn btn-primary btn-block" title="<?php echo $cu['nombre'] ?>"><?php echo substr($cu['nombre'], 0, 20) ?></button>
                            </div>



                            <div class="col-md-8">
                                <div class="row">

                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select multiple class="selectpicker form-control show-tick cu_id_empleado_1" data-live-search="true" data-size="5" title="Conductor">
                                                <!--<option value="">Conductor</option>-->
                                                <?php foreach ($view->empleados as $ar){ ?>
                                                    <option value="<?php echo $ar['id_empleado']; ?>" <?php echo (in_array($ar['id_empleado'], $cu['conductores']))? 'selected' :'' ?>><?php echo $ar['apellido'].' '.$ar['nombre']; ?></option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1" style="padding-left: 5px; padding-right: 5px">
                                        <button type="button" class="btn btn-default btn-intercambio form-control" title="intercambiar conductor/acompañante"><span class="glyphicon glyphicon-resize-horizontal fa-lg"></span></button>
                                    </div>

                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select multiple class="selectpicker form-control show-tick cu_id_empleado_2" data-live-search="true" data-size="5" title="Acompañantes">
                                                <!--<option value="">Acompañantes</option>-->
                                                <?php foreach ($view->empleados as $ar){ ?>
                                                    <option value="<?php echo $ar['id_empleado']; ?>" <?php echo (in_array($ar['id_empleado'], $cu['acompanantes']))? 'selected' :'' ?>><?php echo $ar['apellido'].' '.$ar['nombre']; ?></option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick cu_id_area" data-live-search="true" data-size="5">
                                                <option value="">Área</option>
                                                <?php foreach ($view->areas as $ar){ ?>
                                                    <option value="<?php echo $ar['id_area']; ?>"
                                                        <?php echo ($ar['id_area'] == $cu['default_id_area'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick cu_id_vehiculo" data-live-search="true" data-size="5">
                                                <option value="">Vehículo</option>
                                                <?php foreach ($view->vehiculos as $ar){ ?>
                                                    <option value="<?php echo $ar['id_vehiculo']; ?>"
                                                        <?php echo ($ar['id_vehiculo'] == $cu['default_id_vehiculo'])? 'selected' :'' ?>
                                                        >
                                                        <?php echo $ar['nro_movil'].' '.$ar['modelo']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="row">

                                    <div class="col-md-9" style="padding-left: 5px; padding-right: 5px">
                                        <div class="form-group">
                                            <select class="selectpicker form-control show-tick cu_id_evento" data-live-search="true" data-size="5">
                                                <option value="">Evento</option>
                                                <?php foreach ($view->eventos as $ar){ ?>
                                                    <option value="<?php echo $ar['id_evento']; ?>">
                                                        <?php echo $ar['codigo'].' '.$ar['nombre']; ?>
                                                    </option>
                                                <?php  } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <!--<a class="<?php echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </a>-->
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" class="cu_selected">
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>




                        </div>




                    <?php endforeach; ?>

                <?php }else{ ?>

                    <br/>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle fa-fw"></i> El contrato aún no tiene cuadrillas asociadas ó ya existen partes para las cuadrillas del contrato en la fecha indicada.
                    </div>

                <?php } ?>



                <div id="myElem" class="msg" style="display:none"></div>








            </div>

            <div class="panel-footer clearfix">
                <div class="button-group pull-right">
                    <button class="btn btn-primary" id="submit" name="submit" type="submit">Guardar</button>
                    <button class="btn btn-default" id="cancel" name="cancel" type="button">Cancelar</button>
                </div>
            </div>



</div>
</div>




